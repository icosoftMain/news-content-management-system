<?php namespace App\Actors;
use App\Models\ilapi_pro_cms\DS\DsCategory;
use FLY\Security\KeyGen;
use FLY\Security\Sessions;

class CreateCategory extends FormValidator {

    public function create()
    {
        if($this->validator->has_error()) {
            $this->reports = $this->validator->get_error_message();
        } else {
            if(!$this->categoryExists()) {
                $this->saveCategory();
            } else {
                $this->reports['state']   = false;
                $this->reports['payload'] = $this->request->categoryName.' category already exist';
            }
        }
        Sessions::set('reports', $this->reports);
    }

    private function saveCategory()
    {
        $this->request->categoryName = strtolower($this->request->categoryName);
        DsCategory::save_request(
            $this->request,
            new DsCategory('',KeyGen::primaryKeys(8,'C%key',true))
        );
        $this->reports['state']   = true;
        $this->reports['payload'] = $this->request->categoryName.' was added successfully as a category';
    }

    private function categoryExists()
    {
        $this->model = new DsCategory();
        $this->model->categoryName = $this->request->categoryName;

        $this->model = DsCategory::fetch(':categoryName',$this->model);

        return isset($this->model->categoryName[0]['categoryName']);
    }

    protected function error_report(): array 
    {
        return array(
            'categoryName:alpha' => 'Category name must contain alphabet only',
            'visible:(Y,N)'      => 'Category visibility was not specified',
            '_status:(0,1)'      => 'Category status was not set'
        );
    }
}