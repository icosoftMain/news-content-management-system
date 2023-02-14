<?php namespace App\Actors;

use App\Models\ilapi_pro_cms\DS\{
    DsCategory,
    DsCategoryLevel,
    DsCategoryPage,
    DsPage,
    DsPageImages,
    DsSubCategoryPage
};

use FLY\Security\Sessions;
use App\Actors\File_API\File;

class UpdatePage extends CreatePage {

    private $categoryId = "";

    private $subCategoryId = "";

    private $imageId =  "";
    
	public function update()
	{
		if($this->validator->has_error()) {
			$this->response =  $this->validator->get_error_message();
		}  else if($this->page_exists()) {
            $this->response['state'] = false;
			$this->response['payload'] = "The page title '{$this->request->title}' already exists.";
        }  else {
            $file_flag = false;
            if(File::is_present('pageImage') && $this->imageUploaded()) {
                $this->removeCurrentImage();
                $this->editPageImage();
            } else if(File::is_present('pageImage')) {
                $file_flag = true;
            }
            $this->editPage();
            if(!$file_flag) {
                $this->response['state'] = true; 
                $this->response['payload'] = 'Page was successfully updated';
            }
        }
        Sessions::set('reports',$this->response);
    }
    
    private function removeCurrentImage()
    {
        $model = DsPageImages::set_request($this->request)::fetch(':pageId');
        if(isset($model->imageId[0]['imageId'])) {
            $this->imageId = $model->imageId[0]['imageId'];
            $imageName = $model->imageName[0]['imageName'];
            File::remove("app/statics/images/pages/{$imageName}");
        }
    }

    private function editPageImage()
    {
        if(!is_empty($this->imageId)) {
            $model = DsPageImages::get($this->imageId);
            $model->imageName = $this->response['filename'];
            $model->imageSize = $this->response['imagesize'];
            $model->edit();
        }
    }

    private function editPage()
    {
		$this->request->levelName = str_replace('&amp;','&',$this->request->levelName);

        if(!is_empty($this->request->levelName) && !is_empty($this->request->categoryName)) {
            if($this->checkCategory()) {
                $this->deleteCategoryPage();
            }
            if($this->checkSubCategory()) {
                $this->subCategoryAction('update');
            } else {
                $this->subCategoryAction('save');
            }
        } else if(is_empty($this->request->levelName) && !is_empty($this->request->categoryName)) {
            if($this->checkSubCategory()) {
                $this->deleteSubCategoryPage();
            }

            if($this->checkCategory()) {
                $this->categoryAction('update');
            } else {
                $this->categoryAction('save');
            }
        }
        DsPage::edit_request($this->request);
    }

    private function deleteCategoryPage()
    {
        $model = DsCategoryPage::set_request($this->request);
        $model = $model::fetch(':pageId');
        if(isset($model->sn[0]['sn'])) {
            $sn = $model->sn[0]['sn'];
            DsCategoryPage::get($sn)->delete();
        }
    }

    private function deleteSubCategoryPage()
    {
        $model = DsSubCategoryPage::set_request($this->request);
        $model = $model::fetch(':pageId');
        if(isset($model->sn[0]['sn'])) {
            $sn = $model->sn[0]['sn'];
            DsSubCategoryPage::get($sn)->delete();
        }
    }

    private function checkCategory()
    {
        $model = DsCategoryPage::set_request($this->request);
        $model = $model::fetch(':pageId');
        $flag = false;
        if(isset($model->categoryId[0]['categoryId'])) {
            $this->categoryId = $model->categoryId[0]['categoryId'];
            $flag = true;
        }
        return $flag;
    }

    private function checkSubCategory()
    {
        $model = DsSubCategoryPage::set_request($this->request);
        $model = $model::fetch(':pageId');
        $flag = false;
        if(isset($model->subCategoryId[0]['subCategoryId'])) {
            $this->subCategoryId = $model->subCategoryId[0]['subCategoryId'];
            $flag = true;
        }
        return $flag;
    }

    private function subCategoryAction($type='update')
    {
        $model = DsCategoryLevel::set_request($this->request);
        $model = $model::fetch(':levelName');

        if(isset($model->levelId[0]['levelId'])) {
            $smodel = new DsSubCategoryPage;
            $smodel->pageId = $this->request->pageId;
            $md = $smodel::fetch(':pageId'); 
            $smodel->subCategoryId = $this->getSubCategoryId();
            if($type === 'update' and isset($md->sn[0]['sn'])) {
                $smodel->sn = $md->sn[0]['sn'];
                $smodel->edit();
            }
            else if($type='save') $smodel->save();
        }
    }

    private function categoryAction($type='update')
    {
        $model = DsCategory::set_request($this->request);
        $model = $model::fetch(':categoryName');

        if(isset($model->categoryId[0]['categoryId'])) {
            $pmodel = new DsCategoryPage;
            $pmodel->pageId = $this->request->pageId;
            $md = $pmodel::fetch(':pageId'); 
            $pmodel->categoryId = $this->getCategoryId();
            if($type === 'update' and isset($md->sn[0]['sn'])) {
                $pmodel->sn = $md->sn[0]['sn'];
                $pmodel->edit();
            }
            else if($type === 'save') $pmodel->save();
        }
    }

    private function page_exists()
    {
        $model = new DsPage;
        $model->title = $this->request->title;
        $model = $model::fetch(':title');
        $flag = false;
        if(isset($model->pageId[0]['pageId'])) {
            $otherPageId = $model->pageId[0]['pageId'];
            if($otherPageId <> $this->request->pageId) {
                $flag = ( 
                    (
                        $this->inCategoryPage($otherPageId) &&
                        $this->inCategoryPage($this->request->pageId)
                    ) ||
                    (
                        $this->inSubCategoryPage($otherPageId) &&
                        $this->inSubCategoryPage($this->request->pageId)
                    )
                );
            }
        }
        return $flag;
    }

    private function inCategoryPage($pageId)
    {
        $model = new DsCategoryPage;
        $model->pageId = $pageId;
        $model = $model::fetch(':pageId');
        return isset($model->pageId[0]['pageId']);
    }


    private function inSubCategoryPage($pageId)
    {
        $model = new DsSubCategoryPage;
        $model->pageId = $pageId;
        $model = $model::fetch(':pageId');
        return isset($model->pageId[0]['pageId']);
    }

	protected function error_report():array
	{
		return [
            'title:?text'         => 'Expected title to be non empty',
            'content:?text'       => 'Expected a non empty content',
            'pageType:?alpha'     => 'Page type must contain only alphabet',
            'published:?(Y,N)'    => 'Published field must be Yes or No',
            'source:?alpha'       => 'Source info must contain only alphabets',
            'categoryName:?text'  => 'Category Name must not be empty',
            'levelName:?text'     => 'Sub-Category Name must not be aphabets'
		];
	}
}