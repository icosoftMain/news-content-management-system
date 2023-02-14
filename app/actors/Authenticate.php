<?php namespace App\Actors;
use App\Models\ilapi_pro_cms\DS\{DsMember};
use FLY\Routers\Redirect;
use FLY\Security\{Crypto,Sessions};

class Authenticate extends FormValidator {

    public function login()
    {
        $this->reports = array(
            'state'    => false,
            'payload'  => 'Invalid username or password'
        );

        if($this->validator->has_error()) {
            return $this->validator->get_error_message();
        } 
        if($this->loginCredentialsExists()) 
            $this->permitLogger();
        return $this->reports;
        
    }

    static public function detective()
    {
        if(
            (!Sessions::exists('admin') && trim(DsMember::get(Sessions::get('admin'))->phoneNumber) === "") ||
            trim(DsMember::get(Sessions::get('admin'))->accountStatus) === 'quarantined'
        ) {
            Sessions::removeAll();
            Redirect::to(url(':home'));
        }
        return DsMember::get(Sessions::get('admin'));
    }

    static public function userLogRedirect()
    {
        if(Sessions::exists('admin') && trim(DsMember::get(Sessions::get('admin'))->phoneNumber) !== "") {
            Redirect::to(url(':admin_dash'));
        }
    }

    private function loginCredentialsExists() 
    {
        return $this->usernameExists() && $this->logIsValid() && $this->accountOpened();
    }

    private function logIsValid()
    {
        return (
            isset($this->qry_model->_password[0]['_password']) &&
            Crypto::verify(
                $this->request->_password,
                $this->qry_model->_password[0]['_password'],
                $this->request->username.
                $this->request->_password.
                $this->qry_model->memberId[0]['memberId']
            )
        );
    }
    
    private function accountOpened()
    {
        return (
            trim(
                DsMember::get($this->qry_model->memberId[0]['memberId'])
                -> accountStatus
            ) === 'opened'
        );
    }

    private function permitLogger()
    {
        Sessions::add('admin',$this->qry_model->memberId[0]['memberId']);
        $this->reports = array(
            'state' => true
        );
    }

    protected function error_report(): array 
    {
        return  array(
            'username' => 'Your username is invalid'
        );
    }
}