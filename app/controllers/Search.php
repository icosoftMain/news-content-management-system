<?php namespace App\Controllers;

use App\Controllers\Packs\Filters;
use App\Models\ilapi_pro_cms\DS\
{
    DsCategory,
    DsCategoryLevel,
    DsLogin,
    DsMember,
    DsPartners,
    DsSpeakers
};
use FLY\Libs\Request;
use FLY\MVC\Controller;

final class Search extends Controller {
	use Filters;
	static $type = 'ajax';
	
	static function getSearchData(Request $request)
	{
		if(Request::exists('q')) {
			Request::add('modelType','PAGE');
			Request::add('searchValue',$request->q);
			Request::remove('q');
		}
		self::setModels($request);
		$searchResult = self::searchField(trim($request->searchValue),self::$model);
		if(self::$type === 'ajax') {
			self::add_context([
				'searchResult' => $searchResult
			]);
		}
		return $searchResult;
	}

	static function getDataNavigate(Request $request)
	{
		self::setModels($request);
		self::add_context([
			'searchResult' => self::paginate($request->pagIndex,$request->lim,self::$model,$request->_reverse)
		]);
	}

	static private function getSubCategoryFields()
	{
		$levels = DsCategoryLevel::all();
		$payload = [];

		foreach($levels as $level) {
			array_push($payload,[
				'levelId'         => $level['levelId'],
				'categoryId'      => $level['categoryId'],
				'subCategoryName' => $level['levelName'],
				'categoryName'    => DsCategory::get($level['categoryId'])->categoryName
			]);
		}
		return $payload;
	}

	static private function getSpeakers()
	{
		$speakers = DsSpeakers::all();
		$payload = [];

		foreach($speakers as $speaker) {
			array_push($payload,[
				'speakerId'   => $speaker['speakerId'],
				'imageName'   => $speaker['imageName'],
				'lastName'    => $speaker['lastName'],
				'title'       => $speaker['title'],
				'fullName'    => "{$speaker['firstName']} {$speaker['lastName']}",
				'email'       => $speaker['email'],
				'phoneNumber' => $speaker['phoneNumber']
			]);
		}
		return $payload;
	}

	static private function getPartners()
	{
		$partners = DsPartners::all();
		$payload = [];

		foreach($partners as $partner) {
			array_push($payload,[
				'partnerName'    => $partner['partName'],
				'partnerWebsite' => $partner['partWebName'],
				'partId'         => $partner['partId']
			]);
		}
		return $payload;
	}

	static private function getUsers()
	{
		$users = DsMember::all();
		$payload = [];
		foreach($users as $user) {
			$login = DsLogin::get($user['memberId']);
			array_push($payload,[
				'memberId'    => $user['memberId'],
				'fullName'    => "{$user['lastName']}, {$user['firstName']}",
				'gender'      => $user['gender'],
				'userName'    => $login->username,
				'phoneNumber' => $user['phoneNumber'],
				'email'       => $user['email'],
				'role'        => [
					'A' => 'Administrator',
					'M' => 'Moderator',
					'E' => 'Editor',
					'U' => 'User'
				][$login->accessLevel]
			]);
		}
		return $payload;
	}

}