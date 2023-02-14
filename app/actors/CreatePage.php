<?php namespace App\Actors;

use App\Actors\File_API\File;
use App\Actors\File_API\UploadImage;
use App\Models\ilapi_pro_cms\DS\{
    DsCategory,
    DsCategoryLevel,
    DsCategoryPage,
    DsPage,
    DsPageImages,
    DsSubCategoryPage
};
use FLY\Libs\{ Validator };
use FLY\Security\KeyGen;
use FLY\Security\Sessions;

class CreatePage extends Validator {

	public function create(array $requestData)
	{
		if($this->validator->has_error()) {
			$this->response = $this->validator->get_error_message();
		} else if($this->categoryHasLevel() && is_empty($this->request->levelName)) {
			$this->response['state'] = false;
			$this->response['payload'] = "Category Page '{$this->request->categoryName}' has sub categories page."
			." Please select any of the sub category page";
		} 
		else if($this->pageExists()) {
			$this->response['state'] = false;
			$this->response['payload'] = "The page you are creating with the title '{$this->request->title}' already exists.";
		} else if($this->imageUploaded()) {
			$this->save_page();
			$this->response['state'] = true;
			$this->response['payload'] = 'New page was successfully added.';
		} else {
			$this->response['state'] = false;
			$this->response['payload'] = 'Please upload your page image';
		}
		$this->saveRequestData($requestData);
		Sessions::set('reports',$this->response);
	}

	public function delete()
	{
	
		   $this->removeSubCatPage();
		   $this->removeCatPage();
		   $this->removePageImages();
		   $md = DsPage::get($this->request->pageId);
		   $title = $md->title;
		   $md->delete();
		Sessions::set('reports',['state' => true, 'payload' => "'{$title}' page was successfully deleted."]);
		return ['state' => true];
	}

	private function removeSubCatPage()
	{
		
		$md = DsSubCategoryPage::fetch(':pageId',new DsSubCategoryPage('','',$this->request->pageId));
		$sns = $md->sn;
		foreach($sns as $sn) 
			DsSubCategoryPage::get($sn['sn'])->delete();
	}

	private function removeCatPage()
	{
		$md = DsCategoryPage::fetch(':pageId',new DsCategoryPage('','',$this->request->pageId));
		$sns = $md->sn;
		foreach($sns as $sn) 
			DsCategoryPage::get($sn['sn'])->delete();
	}

	private function removePageImages()
	{
		$md = DsPageImages::fetch(':pageId',new DsPageImages('',$this->request->pageId));
		$images = $md->imageId;
		foreach($images as $img) {
			$md = DsPageImages::get($img['imageId']);
			if(File::exists("app/statics/images/pages/{$md->imageName}")) {
				File::remove("app/statics/images/pages/{$md->imageName}");
			}
			$md->delete();
		}
	}

	private function saveRequestData(array $data)
	{
		$this->response['data'] = [];
		if(!$this->response['state']) {
			$this->response['data'] = $data;
		}
	}

	private function categoryHasLevel()
	{
		$this->model['catLevel'] = new DsCategoryLevel;
		$this->model['catLevel']->categoryId = $this->getCategoryId();
		$this->model['catLevel']::fetch(':categoryId {1}');
		return !is_empty($this->model['catLevel']->levelName);
	}

	private function save_page()
	{
		$this->request::add('pageId', KeyGen::primaryKeys(15,'P%key',true));
		DsPage::save_request($this->request);
		if(!is_empty($this->request->levelName)) {
			$this->request::add('subCategoryId',$this->getSubCategoryId());
			DsSubCategoryPage::save_request($this->request);
		} else if(!is_empty($this->request->categoryName)) {
			$this->request::add('categoryId',$this->getCategoryId());
			DsCategoryPage::save_request($this->request);
		}
		$this->request::add('imageId',KeyGen::primaryKeys(15,'I%key',true));
		$this->request::add('imageName',$this->response['filename']);
		$this->request::add('imageSize',$this->response['imagesize']);

		DsPageImages::save_request($this->request);
		$this->request::remove_all();
	}

	protected function pageExists()
	{
		$this->model['page'] = DsPage::set_request($this->request)
		::fetch(':title & :pageType | :content ');
		$pageId = isset($this->model['page']->pageId[0]['pageId']) ? 
		$this->model['page']->pageId: [];
		$flag = false;
		foreach($pageId as $id) {
			if($this->pageExistence($id['pageId'])) {
				$flag = true;
				break;
			}
		}
		return $flag;
	}

	private function pageExistence($pageId)
	{
			$subCatPage = (
				new DsSubCategoryPage(
					'',
					$this->getSubCategoryId(),
					$pageId
				)
			)::fetch(':subCategoryId & :pageId');

			$flag = isset($subCatPage->pageId[0]['pageId']);
			if($flag) {
				return true;
			}

			$catPage = (
				new DsCategoryPage(
					'',
					$this->getCategoryId(),
					$pageId
				)
			)::fetch(':categoryId & :pageId');
			$flag = isset($catPage->pageId[0]['pageId']);

			if($flag) {
				return true;
			}
			
			return false;
	}

	protected function imageUploaded()
	{
		$upload = new UploadImage('pages/');
		$this->response = $upload->upload_file('pageImage');
		$this->response['imagesize'] = $upload->image_size();
		return $this->response['state'];
	}

	protected function getCategoryId()
	{
		$this->model['cat'] = DsCategory::set_request($this->request)::fetch(':categoryName');
		return $this->model['cat']->categoryId[0]['categoryId'];
	}

	protected function getSubCategoryId()
	{
		$this->request->levelName = str_replace('&amp;','&',$this->request->levelName);
		$this->model['catLevel'] = DsCategoryLevel::set_request($this->request)::fetch(':levelName');
		return $this->model['catLevel']->levelId[0]['levelId'];
	}


	protected function error_report():array
	{
		return [
            'title:text'         => 'Expected title to be non empty',
            'content:text'       => 'Expected a non empty content',
            'pageType:alpha'     => 'Page type must contain only alphabet',
			'published:(Y,N)'    => 'Published field must be Yes or No',
            'source:alpha'       => 'Source info must contain only alphabets',
            'categoryName:alpha' => 'Category Name must not be empty',
            'levelName:?text'    => 'Select Sub-Category Name'
		];
	}
}