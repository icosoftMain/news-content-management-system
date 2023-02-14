<?php namespace App\Actors;

use App\Actors\File_API\{ UploadImage, File };
use App\Models\ilapi_pro_cms\DS\{DsLogin,DsMember};
use FLY\Libs\Request;
use FLY\Security\{
    Crypto,
    Sessions
};

class UpdateAccount extends FormValidator {
    
    public function update()
    {
        if($this->validator->has_error()) {
            $this->response = $this->validator->get_error_message();
        } else if($this->passwordMatched()) {
            if(!$this->passwordEditValid()) {
                $this->response['state'] = false;
                $this->response['payload'] = 'To change your password you must provide your current username';
            } else if(""<> $this->request->username && "" === $this->request->_password) {
                $this->response['state'] = false;
                $this->response['payload'] = 'To change your username you must provide your current password';
            } else $this->proceedUpdate();
        } else {
            $this->response['state'] = false;
            $this->response['payload'] = 'Your confirmation password does not match your current password';
        }
        Sessions::set('reports', $this->response);
    }

    private function proceedUpdate()
    {
        if (Request::is_empty()) {
            $this->response['state'] = true;
            $this->response['payload'] = 'No record changes made.';
        } else {
            $this->updateDetails();
            $this->response['state'] = true;
            $this->response['payload'] = 'Your record was successfully updated.';
        }
    }

    private function passwordMatched()
    {
        return ($this->request->_password === $this->request->confirmPassword);
    }

    private function passwordEditValid()
    {
        return !($this->request->username === ""  && $this->request->_password !== "");
    }

    private function updateDetails()
    {
        $memberId = Sessions::get('admin');
        $this->editMemberModel($memberId);
        $this->editLoginModel($memberId);
    }

    /**
     * @param $memberId
     * @return mixed
     * @throws \Exception
     */
    private function editMemberModel($memberId)
    {
        $this->model = new DsMember(
                $memberId,
                $this->request->firstName,
                $this->request->lastName,
                '',
                $this->request->phoneNumber,
                $this->request->email,
                $this->uploadImage()
        );
        $oldImage = $this->model->imageName;

        $this->model->edit();

        $this->deletePreviousImage($oldImage,$this->model);
        return $this->response['state'];
    }

    private function editLoginModel($memberId)
    {
        $this->model = new DsLogin();
        $this->model->memberId = $memberId;
        $this->model->username = $this->request->username;
        $this->setPassword($memberId);
        $this->model->securityQuestion = $this->request->securityQuestion;
        $this->model->answer = $this->request->answer;
        $this->model->edit();
    }

    private function setPassword($memberId)
    {
        if($this->request->username !== ""  && $this->request->_password !== "" ) {
            $this->request->_password = Crypto::lock(
                $this->request->_password,
                $this->request->username.
                $this->request->_password.$memberId
            );
        }
    }

    /**
     * @return string
     */
    private function uploadImage()
    {
        $upload = new UploadImage('profilepics/');
        $reports = $upload->upload_file('profileImage');

        $imageName = "";
        $this->response['state'] = true;
        if(File::upload_file_exists('profileImage') && !$reports['state'])
            $this->response = $reports;
        else if ($reports['state'] && File::upload_file_exists('profileImage'))
            $imageName = $reports['filename'];
        return $imageName;
    }

    /**
     * @param string $oldImage
     * @param DsMember $member
     * @throws \Exception
     */
    private function deletePreviousImage(string $oldImage, DsMember $member)
    {
        if($oldImage !== $member->imageName && 'user_default_image.png' <> $oldImage)
            unlink(FLY_ENV_STATIC.'images/profilepics/'.$oldImage);
    }


    protected function error_report(): array
    {
        return array(
            'firstName:?alpha'       => 'Your first name must contain alphabet only.',
            'lastName:?alpha'        => 'Your last name must contain alphabet only.',
            'email:?email'           => 'Your email is invalid',
            'phoneNumber:?tel'       => 'Invalid phone number',
            'username:?text'         => 'Your username is invalid',
            'securityQuestion:?text' => 'Your security question must be alphabet with a question mark ending',
            'answer:?text'           => 'Your security answer must in alpha numeric or alphabets only'
        );
    }
}