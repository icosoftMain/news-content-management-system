<?php namespace App\Actors;

use App\Models\ilapi_pro_cms\DS\DsContact;
use FLY\MVC\Model;
use FLY\Security\{ KeyGen, Sessions };

class ContactMessage extends FormValidator {

    public function send(array $requestData)
    {
        if($this->validator->has_error()) {
            $this->reports = $this->validator->get_error_message();
        } else {
            $this->store();
            $this->reports['state'] = true;
            $this->reports['payload'] = 'Your message has been successfully sent.Thank You.';
        }
        self::saveRequestData($requestData);
        Sessions::set('reports',$this->reports);
    }
    
    public function getReadUnreadMsg()
    {
        $msgType = trim($this->request->messageType);
        $this->model = Model::query("CALL get_messages_by_status('{$msgType}')");
        $this->reports['searchResult'] = $this->model;
        if(!is_empty($this->model)) 
            $this->reports['state']   = true;
        else $this->reports['state']  = false;
        return $this->reports;
    }
    
    public function setStatus()
    {
        if(!is_empty($this->request->contactId)) {
            $this->model = DsContact::get($this->request->contactId);
            $this->model->status = trim($this->request->status);
            $this->model->edit();
            $this->reports['state'] = true;
            $this->reports['elementPos'] = $this->request->position;
            $this->reports['msgType']    = trim($this->request->status);
            $this->reports['payload']    = "This message was successfully marked as {$this->request->status}.";
        } else { 
            $this->reports['state'] = true;
            $this->reports['payload'] = 'This message you are about to view does not exists and may be deleted.';
        }
        return $this->reports;
    }

    public function delete()
	{
		$md = DsContact::get($this->request->contactId);
		$fullName = $md->fullName;
		$md->delete();
		Sessions::set('reports',[
			'state' => true, 
			'payload' => "{$fullName}'s message was successfully deleted."
		]);
		return ['state' => true];
    }
    
    private function store()
    {
        DsContact::save_request($this->request,new DsContact(KeyGen::primaryKeys(9,'',true)));
    }

    protected function error_report(): array
    {
        return array(
            'fullName:alpha' => 'Please enter your full name in alphabet only',
            'email:email'    => 'Please enter a valid email address',
            'subject:text'   => 'Please enter your subject',
            'message:text'   => 'Please enter your message'
        );
    }
}