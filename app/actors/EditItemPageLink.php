<?php namespace App\Actors;

use App\Actors\File_API\File;
use App\Models\ilapi_pro_cms\DS\DsSidePagesLevel;
use FLY\Security\Sessions;

class EditItemPageLink extends ItemPageLink {

	public function update()
	{
        if($this->validator->has_error()) {
			return $this->validator->get_error_message();
		} else if($this->linkNotExists()) {
			$this->edit();
			$this->reports['state']   = true;
			$this->reports['payload'] = 'New side link was successfully updated.';
		} else {
			$this->reports['state']   = false;
			$this->reports['payload'] = "The link name '{$this->request->levelName}' already exists."; 
		}
		Sessions::set('reports',$this->reports);
	}

	private function edit()
	{
		if($this->request->linkType === 'doc') {
			if($this->docUploaded()) {
				$this->manage_file();
				$this->request::add('item',$this->item['filename']);
				$this->request::add('lType','filed');
			}
		}
		DsSidePagesLevel::edit_request($this->request);
	}

	private function linkNotExists()
	{
		$model = DsSidePagesLevel::get($this->request->levelId);

		return (
			!(
				$this->linkNameExists()                &&
				($model->item === $this->request->item || is_empty($this->request->item))
			) || File::is_present('item')
		);
	}
	
	private function manage_file()
	{
		$this->model = DsSidePagesLevel::get($this->request->levelId);
		if($this->model->lType === 'filed') $this->removeDoc($this->model->item);
	}

	protected function error_report():array
	{
		$msg1  = "Link text is empty or may contain unacceptable characters.";
		$msg1 .= "However, character which are acceptable are letters or numbers, or a '()' or an '&' or a '-' or an '_' or ? or a 'space' ".
		$msg1 .= "or a '.' or a ','";
		return [
			'levelName:?%[\w\d\s.\?\&\_\-\(\)\,]+' => $msg1,
			'item:?url'                            => 'Website link was not provided or it\'s invalid.'
		];
	}
}