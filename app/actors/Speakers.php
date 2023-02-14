<?php namespace App\Actors;

use App\Actors\File_API\{File, UploadImage};

use App\Models\ilapi_pro_cms\DS\{
    DsEventTime,
    DsLiveEvent,
    DsSpeakers
};
use FLY\Security\KeyGen;
use FLY\Security\Sessions;

class Speakers extends FormValidator {

	public function add(array $requestData)
	{
		if($this->validator->has_error()) 
			$this->reports = $this->validator->get_error_message();
		else if(!$this->speakerAdded()) {
			$this->save_speaker_details();
		} else {
            $this->reports['state'] = false;
            $this->reports['payload'] = "Speaker '{$this->request->firstName}' details already exist.";
		}
		$this->saveRequestData($requestData);
        Sessions::set('reports', $this->reports);
	}

	public function assignSchedule()
	{

		$speaker         = DsSpeakers::get($this->request->speakerId);
		$speakerFullName = "{$speaker->title} {$speaker->firstName} {$speaker->lastName}";
		$this->reports['state']   = true;
		$this->reports['payload'] = "Schedule(s) has been successfully assigned to {$speakerFullName}.";
		if($this->speakerExists()) {
			if(!$this->scheduleExists()) {
				$eventTime = DsEventTime::get($this->request->timeId);
				$payload   = "Unable to assign schedule to: {$speakerFullName} ";
				$payload  .= "The schedule with the start date of {$eventTime->startDate} and an ";
				$payload  .= "end date of {$eventTime->endDate} is no more current.";
				$payload  .= " You can now retry again once the page is refreshed.";
				Sessions::set($this->request->timeId,[
					'state'   => false,
					'payload' => $payload
				]);
			} else if(!$this->scheduleErrorExists()) {
				(
					new DsLiveEvent(
						KeyGen::primaryKeys(15,'L%keyE',true),
						$this->request->timeId,
						$this->request->speakerId
					)
				) -> save();
			}
		} else {
			$this->reports['state']   = false;
			$this->reports['payload'] = "Unable to assign schedule to speaker:";
			$this->reports['payload'].= " The speaker you are assigning a schedule to profile was deleted.";
		}
	
        Sessions::set('reports', $this->reports);
		return $this->request->scheduleRequestCompleted === 'true' ? $this->reports : [];
	}

	public function delete()
	{
		$this->removeSpeakerEvent();
		$md = DsSpeakers::get($this->request->speakerId);
		$speakerName = "{$md->firstName} {$md->lastName}";
		if(File::exists("app/statics/images/speakers_pics/{$md->imageName}")) {
			File::remove("app/statics/images/speakers_pics/{$md->imageName}");
		}
		$md->delete();
		Sessions::set('reports',
			[
				'state'   => true, 
				'payload' => "{$speakerName} has been removed as a speaker."
			]
		);
		return ['state' => true];
	}

	public function deleteSchedule()
	{
		$this->model = DsLiveEvent::get($this->request->liveEventId);
		if(!is_empty($this->model->liveEventId) && !is_empty($this->request->liveEventId)) {
			$this->model->delete();
			$this->reports = [
				'state'   => true,
				'payload' => 'Schedule removed successfully.'
			];
		} else {
			$this->reports = [
				'state'   => false,
				'payload' => 'Unable to delete the schedule you requested: Schedule does not exists'
			];
		}
		Sessions::set('reports', $this->reports);
		return $this->reports;
	}

	private function scheduleErrorExists()
	{
		$timeIds = (new DsEventTime())->timeId;
		foreach($timeIds as $timeId) {
			if(Sessions::exists($timeId['timeId'])) {
				$this->reports = Sessions::get($timeId);
				Sessions::remove($timeId);
				return true;
			}
		}
		return false;
	}

	private function scheduleExists()
	{
		return !is_empty(DsEventTime::get($this->request->timeId)->timeId);
	}

	private function speakerExists()
	{
		return !is_empty(DsSpeakers::get($this->request->speakerId)->speakerId);
	}

	private function speakerAdded()
	{
		$md = DsSpeakers::set_request($this->request);
		$this->model = $md::fetch(':firstName & :lastName & :email & :phoneNumber{1}');
		$speakerId = isset($this->model->speakerId[0]['speakerId'])?$this->model->speakerId[0]['speakerId']: NULL;
		$flag = false;
		if($speakerId !== NULL) {
			$md = DsLiveEvent::fetch(':speakerId',new DsLiveEvent('','',$speakerId));
			$flag = isset($md->timeId[0]['timeId']);
		}
		return $flag;
	}

	private function save_speaker_details()
	{
		if(File::is_present('speakerImage') && $this->imageUploaded()) {
			$this->request::add('speakerId',KeyGen::primaryKeys(12,'SP%key',true));
			$this->request::add('imageName',$this->reports['filename']);
			DsSpeakers::save_request($this->request);
			$this->reports['state']   = true;
            $this->reports['payload'] = "{$this->request->firstName}'s details was successfully saved as a speaker.";
		} else {
            $this->reports['state'] = false;
            $this->reports['payload'] = 'Please provide the image of the speaker.';
		}
	}

	protected function imageUploaded()
    {
        $upload = new UploadImage('speakers_pics/');
		$this->reports = $upload->upload_file('speakerImage');
		return $this->reports['state'];
    }


	private function removeSpeakerEvent()
	{
		$liveMD = new DsLiveEvent;
		$liveMD->speakerId = $this->request->speakerId;
		$md = DsLiveEvent::fetch(':speakerId',new DsLiveEvent(''));
		$liveEventId = $md->liveEventId;
		foreach($liveEventId as $event)
			DsLiveEvent::get($event['liveEventId'])->delete();
	}

	protected function error_report():array
	{
		return [
			'title:text'       => 'Please provide the title of the speaker.',                   
            'firstName:alpha'  => 'First name must contain alphabet only.',
            'lastName:alpha'   => 'Last name must contain alphabet only.',
            'email:email'      => 'Enter a valid email',
            'phoneNumber:tel'  => 'Invalid phone number',
			'about:text'       => 'Speaker description not specified' 
		];
	}
}