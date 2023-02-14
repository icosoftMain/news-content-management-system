<?php namespace App\Actors;

use FLY\Security\Sessions;
use App\Models\ilapi_pro_cms\DS\{
	DsPartners
};

use App\Actors\File_API\{ File };

class EditPartner extends AddPartner {

	public function update()
	{
		if($this->validator->has_error()) {
			$this->validator->get_error_message();
		} else {
			$file_flag = false;
			if(File::is_present('partLogo') && $this->logoUploaded()) {
                $this->removeCurrentLogo();
                $this->editPartnerLogo();
            } else if(File::is_present('partLogo')) {
                $file_flag = true;
			}
			if(!$file_flag) {
				$partName = $this->setUpdate();
                $this->reports['state'] = true; 
                $this->reports['payload'] = "Partner '{$partName}' details was successfully updated";
            }
		}
		Sessions::set('reports',$this->reports);
	}

	private function removeCurrentLogo()
    {
		$this->model = DsPartners::set_request($this->request)::fetch(':partId');
        if(isset($this->model->partId[0]['partId'])) {
			$imageName = $this->model->partLogo[0]['partLogo'];
            File::remove("app/statics/images/partners/{$imageName}");
        }
	}

	private function editPartnerLogo()
    {
        if(!is_empty($this->request->partId)) {
            $this->model = DsPartners::get($this->request->partId);
            $this->model->partLogo = $this->reports['filename'];
            $this->model->edit();
        }
	}

	private function setUpdate()
	{
		DsPartners::edit_request($this->request);
		$this->model = DsPartners::get($this->request->partId);
		return $this->model->partName;
	}

	protected function error_report():array
	{
		return [
			'partName:?alpha'  => 'Partner name must be in alphabets',
			'partWebName:?url' => 'Please enter a valid website'
		];
	}
}