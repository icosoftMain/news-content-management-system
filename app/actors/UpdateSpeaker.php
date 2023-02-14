<?php namespace App\Actors;

use App\Actors\File_API\{ File };
use App\Models\ilapi_pro_cms\DS\DsSpeakers;
use FLY\Security\Sessions;

class UpdateSpeaker extends Speakers {

	public function edit()
	{
		if($this->validator->has_error()) {
			$this->validator->get_error_message();
		} else {
			if(File::is_present('speakerImage') && $this->imageUploaded()) {
				$this->removeCurrentPicture();
				$this->request::add('imageName',$this->response['filename']);
			}
			$speakerName = DsSpeakers::get($this->request->speakerId)->firstName;
			DsSpeakers::edit_request($this->request);
			$this->response['state'] = true; 
			$this->response['payload'] = "'{$speakerName}'s details was successfully updated.";
		}
		Sessions::set('reports',$this->response);
	}

	private function removeCurrentPicture()
	{
		$this->model = DsSpeakers::get($this->request->speakerId);
		if(!is_empty($this->model->imageName)) {
			$imageName = $this->model->imageName;
			File::remove("app/statics/images/speakers_pics/{$imageName}");
		}
	}

	private function editSpeakerLogo()
	{

	}

	protected function error_report():array
	{
		return [
			'title:?text'       => 'Please provide the title of the speaker.',                   
            'firstName:?alpha'  => 'First name must contain alphabet only.',
            'lastName:?alpha'   => 'Last name must contain alphabet only.',
            'email:?email'      => 'Enter a valid email',
            'phoneNumber:?tel'  => 'Invalid phone number',
			'about:?text'       => 'Speaker description not specified' 
		];
	}
}