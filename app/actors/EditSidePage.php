<?php namespace App\Actors;

use App\Actors\File_API\File;
use App\Models\ilapi_pro_cms\DS\DSMembershipForm;
use App\Models\ilapi_pro_cms\DS\DsSidePages;
use FLY\Security\Sessions;

class EditSidePage extends SidePage {

	public function update()
	{
		if($this->validator->has_error()) {
			$this->reports = $this->validator->get_error_message();
		} else if(!$this->sideContentExists()) {
			$this->edit();
			$this->reports['state']   = true;
            $this->reports['payload'] = 'Side content header has been successfully updated.';
		} else {
			$this->reports['state']   = false;
			$this->reports['payload'] = "The header title '{$this->request->pageName}' already exists.";
		}
		Sessions::set('reports',$this->reports);
	}

	public function editMemForm()
	{
		if(File::is_present('memberForm') && $this->docUploaded('memberForm')) {
			if(DSMembershipForm::count() === 0) {
				(
					new DSMembershipForm(1,$this->item['filename'])
				)->save();
			} else {
				$memForm = DSMembershipForm::get(1);
				$this->removeDoc($memForm->formName);
				$memForm->formName = $this->item['filename'];
				$memForm->edit();
			}
			$this->reports['state']   = true;
			$this->reports['payload'] = 'Membership form is successfully updated.';
		} else {
			$this->reports['state']   = false;
			$this->reports['payload'] = 'Unable to update memebership form: No file was provided for update.';
		}
		Sessions::set('reports',$this->reports);
	}


	public function sideContentExists()
	{
		$sidePage = DsSidePages::get($this->request->id);
		return $this->sideContentNameExists() && $sidePage->publish === $this->request->publish;
	}

	private function edit()
	{
		DsSidePages::edit_request($this->request);
	}

	protected function error_report():array
	{
		return [
			'pageName:?alpha' => 'Header title must be in alphabet.',
			'publish:?(Y,N)'  => 'Select a status for the side content.'
		];
	}
}