<?php namespace App\Actors;

use App\Actors\File_API\File;
use App\Actors\File_API\UploadImage;
use App\Models\ilapi_pro_cms\DS\{
	DsPartners
};
use FLY\Security\Sessions;

class AddPartner extends FormValidator {

	public function saveDetail(array $requestData)
	{
		if($this->validator->has_error()) {
			$this->reports = $this->validator->get_error_message();
		} else if($this->partnerExists()) {
			$this->reports['state']   = false;
			$this->reports['payload'] = "This partner details already exists";
		} else if($this->logoUploaded()) {
			$this->save();
			$this->reports['state'] = true;
			$this->reports['payload'] = 'New Partner has been added successfully';
		}
		$this->saveRequestData($requestData);
		Sessions::set('reports',$this->reports);
	}

	public function delete()
	{
		$md = DsPartners::get($this->request->partId);
		$partName = $md->partName;
		if(File::exists("app/statics/images/partners/{$md->partLogo}")) {
			File::remove("app/statics/images/partners/{$md->partLogo}");
		}

		$md->delete();
		Sessions::set('reports',[
			'state' => true, 
			'payload' => "The partner named '{$partName}' has been removed."
		]);
		return ['state' => true];
	}

	private function partnerExists()
	{
		$this->model = DsPartners::set_request($this->request)::fetch(':partName & :partWebName {1}');

		return isset($this->model->partName[0]) || isset($this->model->partWebName[0]);
	}

	protected function logoUploaded()
	{
		$upload = new UploadImage('partners/');
		$this->reports = $upload->upload_file('partLogo');
		return $this->reports['state'];
	}

	private function save()
	{
		$this->request::add('partLogo',$this->reports['filename']);
		DsPartners::save_request($this->request);
	}

	protected function error_report():array
	{
		return [
			'partName:alpha'  => 'Partner name must be in alphabets',
			'partWebName:url' => 'Please enter a valid website'
		];
	}
}