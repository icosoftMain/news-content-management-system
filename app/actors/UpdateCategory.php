<?php namespace App\Actors;
use App\Models\ilapi_pro_cms\DS\DsCategory;
use FLY\Security\Sessions;

class UpdateCategory extends FormValidator {

    public function update()
    {
        if($this->validator->has_error()) {
            $this->reports = $this->validator->get_error_message();
        } else {
            $this->model = new DsCategory('',$this->request->catId);
            $this->setUpdate();
        }
        Sessions::set('reports', $this->reports);
    }

    private function setUpdate()
    {
        if($this->categoryNameExists()) {
            $this->reports['state'] = false;
            $this->reports['payload']  = "Update unsuccessful '".$this->request->categoryName;
            $this->reports['payload'] .= "' already exist as a category name";
        }  else {
            $this->model->visible = $this->request->visible;
            $this->model->_status = $this->request->_status;
            $this->model->edit();
            $this->reports['state'] = true;
            $this->reports['payload'] = 'Category detail was successfully updated';
        }
    }

    private function categoryNameExists()
    {
        $cat = DsCategory::set_request($this->request)::fetch(':categoryName');
        return isset($cat->categoryName[0]['categoryName']);
    }

    protected function error_report(): array 
    {
        return array(
            'visible:?(Y,N)'      => 'Category visibility was not specified',
            '_status:?(0,1)'      => 'Category status was not set'
        );
    }
}