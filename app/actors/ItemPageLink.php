<?php namespace App\Actors;

use App\Models\ilapi_pro_cms\DS\DsSidePagesLevel;
use FLY\Libs\Request;
use FLY\Security\Sessions;

class ItemPageLink extends SidePage {

	public function add(array $requestData)
	{
		if($this->validator->has_error()) {
			$this->reports = $this->validator->get_error_message();
		} else if(!$this->linkNameExists()) {
			if($this->save()) {
				$this->reports['state']   = true;
				$this->reports['payload'] = 'New side link was successfully added.';
			}
		} else {
			$this->reports['state']   = false;
			$this->reports['payload'] = "The link name '{$this->request->levelName}' already exists."; 
		}
		self::saveRequestData($requestData);
		Sessions::set('reports',$this->reports);
	}

	public function delete()
	{
		$linkPage = DsSidePagesLevel::get($this->request->levelId);
		$linkName = $linkPage->levelName;
		$this->request::add('levelName',$linkName);
		if(!$this->linkNameExists()) {
			$this->reports['state']   = false;
			$this->reports['payload'] = "The link name '{$linkName}' you requested to delete does not exists.";
		} else {
			if($linkPage->lType === 'filed') $this->removeDoc($linkPage->item);
			$linkPage->delete();
			$this->reports['state']   = true;
			$this->reports['payload'] = "The link with the name '{$linkName}' was successfully deleted.";
		}
		Sessions::set('reports',$this->reports);
		return $this->reports;
	}

	private function save() 
	{
		if($this->request->linkType === 'doc') {
			if(!$this->docUploaded('item')) {
				$this->reports['state']   = false;
				$this->reports['payload'] = 'Unable to add link: Link file was not provided for upload or file already exists.';	
				return false;				
			} 
			$this->request::add('item',$this->item['filename']);
			$this->request::add('lType','filed');
		}
		DsSidePagesLevel::save_request($this->request);
		return true;
	}

	protected function linkNameExists()
	{
		$this->model = DsSidePagesLevel::fetch(':levelName{1}',new DsSidePagesLevel('','',$this->request->levelName));
		return isset($this->model->levelName[0]['levelName']);
	}
	
	protected function error_report():array
	{
		$msg1  = "Link text is empty or may contain unacceptable characters.";
		$msg1 .= "However, character which are acceptable are letters or numbers, or a '()' or an '&' or a '-' or an '_' or ? or a 'space' ".
		$msg1 .= "or a '.' or a ','";
        $errmsg = [
			'levelName:%[\w\d\s.\?\&\_\-\(\)\,]+' => $msg1
		];	
		if(Request::exists('linkType') && Request::get('linkType') === 'link') $errmsg['item:url'] = 'Website link was not provided or it\'s invalid.';

		return $errmsg;
	}
}