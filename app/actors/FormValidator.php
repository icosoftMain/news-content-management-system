<?php namespace App\Actors;

use App\Models\ilapi_pro_cms\DS\DsLogin;
use FLY\Libs\{Validator,Request};
use FLY\Security\Sessions;

abstract class FormValidator extends Validator {

    protected $model;

    protected $reports = [];

    protected $qry_model;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        new Sessions;
    }

    protected function usernameExists()
    {
        $this->qry_model = DsLogin::set_request($this->request)::fetch(':username');
        return isset($this->qry_model->_password[0]['_password']) && 
               isset($this->qry_model->username[0]);
    }

    protected function saveRequestData(array $data)
	{
		$this->reports['data'] = [];
		if(!$this->reports['state']) {
			$this->reports['data'] = $data;
		}
    }
}