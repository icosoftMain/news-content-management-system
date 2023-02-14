<?php namespace App\Actors;

use App\Models\ilapi_pro_cms\DS\{
    DsCategory,
    DsCategoryLevel
};
use FLY\Libs\{ Validator };
use FLY\Security\Sessions;

class UpdateSubCategory extends Validator {

	public function update()
	{
		if($this->validator->has_error()) {
			$this->response = $this->validator->get_error_message();
		} else {
            $this->editSubCat();
        }

        Sessions::set('reports',$this->response);
    }

    private function editSubCat()
    {
        if($this->levelExists()) {
            $this->response['state'] = false;
            $this->response['payload']  = "No changes made: Sub Category '";
            $this->response['payload'] .= "{$this->request->levelName}' already exists.";
        } else {
            $this->request::add('levelId',$this->request->levId);
            $this->request::add('categoryId',$this->request->catId);
            $this->model['level']::edit_request($this->request);
            $this->response['state'] = true;
            $this->response['payload'] = 'Sub-Category was successfully updated.';
            Sessions::set(
                'editSubCatId',
                "?level={$this->request->levId}&cat={$this->request->catId}"
            );
        }
    }

    private function levelExists()
    {
        $this->model['level'] = DsCategoryLevel::get($this->request->levId);
        $this->setCatId();
        return (
            $this->model['level']->levelName  === $this->request->levelName &&
            $this->model['level']->categoryId === $this->request->catId
        );
    }

    private function setCatId()
    {
        $this->model['cat'] = DsCategory::fetch(
            ':categoryName {1}',
            new DsCategory('','',$this->request->categoryName)
        );
        $this->request->catId =  $this->model['cat']->categoryId[0]['categoryId'];
    }
    
	protected function error_report():array
	{
		return [
            'levelName:?text'    => 'Sub-Category name must not be empty',
            'categoryName:text'  => 'Category name must not be empty',
            'levId:text'         => 'Access denied: Authentication error',
            'catId:text'         => 'Access denied: Authentication error'
		];
	}
}