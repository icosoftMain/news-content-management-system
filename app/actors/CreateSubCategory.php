<?php namespace App\Actors;
use App\Models\ilapi_pro_cms\DS\{DsCategory,DsCategoryLevel};
use FLY\Security\Sessions;

class CreateSubCategory extends FormValidator {

    private $categoryId;

    public function create()
    {
        if($this->validator->has_error()) {
            $this->reports = $this->validator->get_error_message();
        }
        if(!$this->categoryIdExists()) {
            $this->reports['state']   = false;
            $this->reports['payload'] = "Category '".$this->request->categoryName."' does not exists";
            if($this->request->categoryName === "")
                $this->reports['payload'] = "Select the category of your sub-category page";

        } else if($this->subCategoryExists()) {
            $this->reports['state']   = false;
            $this->reports['payload']  = "Sub Category '".$this->request->levelName."' already exists ";
            $this->reports['payload'] .= "in Category named '".$this->request->categoryName;
        } else {
            $this->addCategory();
            $this->reports['state']   = true;
            $this->reports['payload'] = $this->request->levelName.' was added successfully as a sub category';
        }
        Sessions::set('reports', $this->reports);
    }

    private function subCategoryExists()
    {
        $model = DsCategoryLevel::fetch(':categoryId & :levelName',
            new DsCategoryLevel('',$this->request->levelName,$this->getCategoryId()));
        return isset($model->levelName[0]['levelName']);
    }

    private function addCategory()
    {
        $categoryId = $this->getCategoryId();
        $this->model = new DsCategoryLevel;
        $this->model->categoryId = $categoryId;
        DsCategoryLevel::save_request($this->request,$this->model);
    }

    private function getCategoryId()
    {
        return $this->categoryId[0]['categoryId'];
    }

    private function categoryIdExists()
    {
        $this->model = new DsCategory();
        $this->model->categoryName = $this->request->categoryName;
        $this->model = DsCategory::fetch(':categoryName',$this->model);
        $this->categoryId = $this->model->categoryId;
        return isset($this->model->categoryId[0]['categoryId']);
    }

    protected function error_report(): array 
    {
        return [
            'levelName:text'    => 'Sub-Category Name must not be empty',
            'categoryName:text' => 'Select the category of your sub-category page'
        ];
    }
}