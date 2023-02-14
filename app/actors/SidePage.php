<?php namespace App\Actors;

use App\Actors\File_API\File;
use App\Actors\File_API\Upload;
use App\Models\ilapi_pro_cms\DS\{DsSidePages,DsSidePagesLevel};
use FLY\Security\Sessions;

class SidePage extends FormValidator {

	protected $item;
	
	public function create($requestData)
	{
		if($this->validator->has_error()) {
			$this->reports = $this->validator->get_error_message();
		} else if(!$this->sideContentNameExists()) {
			$this->save();
			$this->reports['state']   = true;
            $this->reports['payload'] = 'Side content header has been successfully created.';
		} else {
			$this->reports['state']   = false;
			$this->reports['payload'] = "The header title '{$this->request->pageName}' already exists.";
		}
		self::saveRequestData($requestData);
		Sessions::set('reports',$this->reports);
		return $this->reports;
	}

	public function delete()
	{
		$pageTitle = DsSidePages::get($this->request->id);
		$pageName  = $pageTitle->pageName;
		$this->request::add('pageName',$pageName);
		if(!$this->sideContentNameExists()) {
			$this->reports['state']   = false;
			$pageName = !is_empty($pageName) ? "'{$pageName}'" : "";
			$this->reports['payload'] = "The header title {$pageName} you requested to delete does not exists.";
		} else {
			$this->removeSubLinks();
		    (DsSidePages::get($this->request->id))->delete();
			$this->reports['state']   = true;
			$this->reports['payload'] = "The header title with the name '{$pageName}' was successfully deleted.";
		}
		Sessions::set('reports',$this->reports);
		return $this->reports;
	}

	private function removeSubLinks()
	{
		$md = DsSidePagesLevel::fetch(':spId & :lType',new DsSidePagesLevel('',$this->request->id,'','filed'));
		$levelIds = $md->levelId;
		$items    = $md->item;
		foreach($levelIds as $key => $level) {
			$this->removeDoc($items[$key]['item']);
		    $t = $md->get_object()::get($level['levelId']);
		    $t->delete();
		}
	}

	protected function removeDoc($docname)
	{
		return File::remove("app/statics/docs/{$docname}");
	}

	protected function docUploaded($file)
	{
		$upload = new Upload('docs/');
		$this->item = $upload->file($file);
		return $this->item['state'];
	}

	protected function sideContentNameExists()
	{
		$this->model = DsSidePages::fetch(':pageName{1}',new DsSidePages('',$this->request->pageName));
		return isset($this->model->pageName[0]['pageName']);
	}

	private function save()
	{
		DsSidePages::save_request($this->request);
	}

	protected function error_report():array
	{
		$msg1  = "Header text is empty or may contain unacceptable characters.";
		$msg1 .= "However, character which are acceptable are letters or numbers, or a '()' or an '&' or a '-' or an '_' or ? or a 'space' ".
		$msg1 .= "or a '.' or a ','";
		return [
			'pageName:%[\w\d\s.\?\&\_\-\(\)\,]+'  => $msg1 ,
			'publish:(Y,N)'                       => 'Select a status for the side content.'
		];
	}
}