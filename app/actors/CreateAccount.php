<?php namespace App\Actors;

use App\Actors\File_API\UploadImage;
use App\Models\ilapi_pro_cms\DS\DsLogin;
use App\Models\ilapi_pro_cms\DS\DsMember;
use FLY\Security\{
    Crypto,
    KeyGen,
    Sessions
};

final class CreateAccount extends FormValidator {

    public function create()
    {
        if($this->validator->has_error()) {
            $this->reports = $this->validator->get_error_message();
        } else if($this->usernameExists()) {
            $this->reports['state']   = false;
            $this->reports['payload'] = "Username '".$this->request->username."' already exists";
        } else if($this->request->_password !== $this->request->cpassword) {
             $this->reports['state'] = false;
            $this->reports['payload'] = 'Your confirmation password does not match with your real password';
        } else if($this->uploadImage()) {
            $this->saveDetails();
            $this->reports['state'] = true;
            $this->reports['payload'] = 'New member record was successfully added.';
        }

        Sessions::set('reports', $this->reports);
    }

    private function saveDetails()
    {
        $memberId = KeyGen::primaryKeys(9,'M%key',true);
        $this->model = new DsMember($memberId);
        $this->model->imageName = $this->reports['filename'] ;

        DsMember::save_request(
            $this->request,
            $this->model
        );
        $this->model = new DsLogin($memberId);
        $this->request->_password = Crypto::lock(
            $this->request->_password,
            $this->request->username.
            $this->request->_password.$memberId
        );
        DsLogin::save_request(
            $this->request,
            $this->model
        );
        $this->reports['filename'] = "";
        array_unshift($this->reports);
    }

    private function uploadImage()
    {
        $upload = new UploadImage('profilepics/');
        $this->reports = $upload->upload_file('profileimg');
        return $this->reports['state'];
    }

    protected function error_report(): array
    {
        return array(
            '_password:{cpassword}' => 'Your confirmation password does not match with your real password...',
            'firstName:alpha'       => 'First name must contain alphabet only.',
            'lastName:alpha'        => 'Last name must contain alphabet only.',
            'gender:(M,F)'          => 'Gender must be male or female',
            'email:email'           => 'Enter a valid email',
            'phoneNumber:tel'       => 'Invalid phone number',
            'username:text'         => 'Your username is invalid',
            'securityQuestion:text' => 'Security question not specified',
            'answer:text'           => 'Security answer not specified',
            'accessLevel:alpha'     => 'Select user\'s access level',
            'cpassword:text'        => 'Please fill in your confirmation password'
        );
    }
}