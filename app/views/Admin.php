<?php namespace App\Views;

use App\Controllers\Packs\Shares;
use App\Models\ilapi_pro_cms\DS\{
	DsCategory,
    DsDonation,
	DsDonor,
	DsEvent
};
use FLY\Libs\{ Request,FileReader };
use FLY\Routers\Redirect;

use FLY\MVC\View;

use App\Widgets\Pages\{ 
	Login_Page
};
use FLY\Security\Sessions;

final class Admin extends View {
	static $context;

	use Shares;

	public function __construct()
	{
		parent::__construct();
		self::$context = [
			'app_style_type' => 'admin',
			'bodyClass'      => 'fix-header'
		];	
	}

	static function index()
	{
		self::$context = [
			'app_name'  => 'ILAPI::Login',
            'app_style_type' => 'login'
		];
		return new Login_Page;
	}

	static function dashboard()
	{
		self::$context = [
			'app_style_type' => 'admin_dash',
			'app_name'  => 'ILAPI::Dashboard'
		];
	}

	static function myProfile()
	{
		self::$context = [
			'app_name'  => 'ILAPI::Profile',
            'alertType' => '',
            'alertText' => '',
            'requestValues' => self::submissionRequestPayload()
		];
	}

	static function managePages()
	{
		self::$context = [
			'app_name'  => 'ILAPI::Manage Pages'
		];
	}

	static function manageCategories()
	{
		self::$context = [
			'app_name'  => 'ILAPI::Manage Categories'
		];
	}

	static function manageSubCategories()
	{
		self::$context = [
			'app_name'  => 'ILAPI::Manage Sub Categories'
		];
	}

	static function addCategoryPages() 
	{
		self::$context = [
			'app_name'     => 'ILAPI::Add Category Pages',
			'crumpLink'    => ':add_category',
			'categoryName' => 'Enter Category Name',
			'visibility'   => '',
			'status'       => '',
			'buttonText'   => 'Save Category'
		];
	}

	static function editCategoryPages() 
	{
		self::$context = [
			'app_name'  => 'ILAPI::Edit Category Pages'
		];
	}

	static function addSubCategoryPages() 
	{
		self::$context = [
			'app_name'     => 'ILAPI::Add Sub Category Pages',
            'alertType'    => '',
			'alertText'    => '',
			'formType'     => 'add',
			'levelName'    => 'Enter Sub Category Name',
			'buttonText'   => 'Save',
			'crumpLink'    => ':add_sub_category',
            'categoryName' => DsCategory::all()
		];
	}

	static function editSubCategoryPages() 
	{
		self::$context = [
			'app_name'     => 'ILAPI::Edit Sub Category Pages',
            'alertType'    => '',
			'alertText'    => '',
			'formType'     => 'edit',
            'categoryName' => DsCategory::all()
		];
	}

	static function addPages()
	{
		self::$context = [
			'app_name'     => 'ILAPI::Add Pages',
			'categoryName' => DsCategory::all(),
			'formType'     => 'add',
			'crumpLink'    => ':add_page',
			'pageId'       => ''
		];
	}

	static function editPages()
	{
		$linkPayload = Request::exists('page') ? '?page='. Request::get('page'): Redirect::to(url(':manage_pages'));
		self::$context = [
			'app_name'     => 'ILAPI::Edit Pages',
			'categoryName' => DsCategory::all(),
			'formType'     => 'edit',
			'crumpLink'    => ':edit_page'.$linkPayload
		];
	}

	static function addUser()
	{
		self::$context = [
			'app_name'  => 'ILAPI::Add User',
            'alertType' => '',
            'alertText' => '',
            'requestValues' => self::submissionRequestPayload()
		];
	}

	static function manageUser()
	{
		self::$context = [
			'app_name'  => 'ILAPI::Manage User'
		];
	}

	static function currentSchedules()
	{
		$linkPayload = Request::exists('speaker') ? '?speaker='. Request::get('speaker'): Redirect::to(url(':manage_speakers'));

		self::$context = [
			'app_name'  => 'ILAPI::Current Schedules',
			'crumpLink' => ':add_speaker_schedules'.$linkPayload
		];
	}

	static function assignedSchedules()
	{
		$linkPayload = Request::exists('speaker') ? '?speaker='. Request::get('speaker'): Redirect::to(url(':manage_speakers'));

		self::$context = [
			'app_name'  => 'ILAPI::Assigned Schedules',
			'crumpLink' => ':assigned_schedules'.$linkPayload
		];
	}

	static function editSchedule()
	{
		$linkPayload = Request::exists('schedule') ? '?schedule='. Request::get('schedule'): Redirect::to(url(':manage_events'));

		self::$context = [
			'app_name'  => 'ILAPI::Edit Schedule',
			'formType'  => 'edit',
			'crumpLink' => ":edit_schedule{$linkPayload}"
		];
	}

	static function addEvent() 
	{
		self::$context = [
			'app_name'   => 'ILAPI::Add Event',
			'eventTypes' => FileReader::fetchJSON('app/store/eventType'),
			'formType'   => 'add',
	        'crumpLink'  => ':add_event'
		];
	}

	static function editEvent() 
	{
		$linkPayload = Request::exists('event') ? '?event='. Request::get('event'): Redirect::to(url(':manage_events'));
		self::$context = [
			'app_name'   => 'ILAPI::Edit Event',
			'eventTypes' => FileReader::fetchJSON('app/store/eventType'),
			'formType'   => 'edit',
			'crumpLink'  => ':edit_event'.$linkPayload,
			'eventId'    => ''
		];
	}

	static function addSchedule()
	{
		self::$context = [
			'app_name'     => 'ILAPI::Add Schedules',
			'formType'     => 'add',
			'crumpLink'    => ':add_schedule'.(Request::exists('event') ? '?event='.Request::get('event') : '')
		];
	}

	static function manageEvents() 
	{
		self::$context = [
			'app_name'       => 'ILAPI::Manage Events',
			'eventSchedules' => self::_currentEventSchedule()
		];
	}


	static function eventSpeakers() 
	{
		self::$context = [
			'app_name'      => 'ILAPI::Event Speakers',
		];
	}

	static function editSpeaker()
	{
		$linkPayload = Request::exists('speaker') ? '?speaker='. Request::get('speaker'): Redirect::to(url(':manage_speakers'));
		self::$context = [
			'app_name'   => 'ILAPI::Edit Speaker Detail',
			'formType'   => 'edit',
			'crumpLink'  => ':edit_speaker'.$linkPayload,
			'speakerId'  => ''
		];
	}

	static function addSpeaker()
	{
		self::$context = [
			'app_name'     => 'ILAPI::Add Speakers',
			'eventNames'   => DsEvent::all(),
			'formType'     => 'add',
			'crumpLink'    => ':add_speaker',
			'speakerId'    => ''
		];
	}
	static function ourPartners()
	{
		self::$context = [
			'app_name'  => 'ILAPI::Our Partners'
		];
	}

	static function donations()
	{
		$donors = DsDonor::all();
		$dts = self::getDonations($donors);
		$donors = empty($dts) ? $donors: [];

		self::$context = [
			'app_name'  => 'ILAPI::Donations',
			'donors'    => $donors,
			'donation'  => $dts
		];
	}

	static private function getDonations(array $donors)
	{
		$payload = [];
		foreach($donors as $donor) {
			$donations = DsDonation::fetch(':donorId{1}', new DsDonor($donor['donorId']));
			if(isset($donations->donationId[0]['donationId'])) {
				array_push($payload,[
					'amount'      => $donations->amount[0]['amount'],
					'dateDonated' => $donations->dateDonated[0]['dateDonated']
				]);			
			} else {
				$payload = [];
				break;
			}
		}
		return $payload;
	}

	static function addPartner()
	{
		self::$context = [
			'app_name'     => 'ILAPI::Add Partner',
			'formType'     => 'add',
			'crumpLink'    => ':add_partner',
			'partId'       => ''
		];
	}

	static function editPartner()
	{
		$linkPayload = Request::exists('partner') ? '?partner='. Request::get('partner'): Redirect::to(url(':manage_partners'));
		self::$context = [
			'app_name'   => 'ILAPI::Edit Partner Details',
			'formType'   => 'edit',
			'crumpLink'  => ':edit_partner'.$linkPayload,
			'partId'     => ''
		];
	}

	static function sidePages()
	{
		self::$context = [
			'app_name'   => 'ILAPI::Side Pages',
			'crumpLink'  => ':manage_sidepages'
		];
	}

	static function setSideItem()
	{
		$linkPayload = (
			(Request::exists('ps') && Request::exists('t')) 
			? '?ps='.Request::get('ps').'&t='.Request::get('t')
			: Redirect::to(url(':manage_sidepages'))
		);

		if(!in_array(Request::get('t'),['link','doc'])) Redirect::to(url(':manage_sidepages'));
		
		self::$context = [
			'app_name'  => 'ILAPI::Add Link Item',
			'formType'  => 'add',
			'crumpLink' => ":add_side_itm{$linkPayload}",
			'id'        => Request::get('ps'),
			'linkType'  => Request::get('t') === 'link' ? 'link': 'doc'
		];
	}

	static function editSideItem()
	{

		$linkPayload = (
			(Request::exists('link') && Request::exists('t')) 
			? '?link='.Request::get('link').'&t='.Request::get('t')
			: Redirect::to(url(':manage_sidepages'))
		);

		if(!in_array(Request::get('t'),['link','doc'])) Redirect::to(url(':manage_sidepages'));
		
		self::$context = [
			'app_name'  => 'ILAPI::Edit Link Item',
			'formType'  => 'edit',
			'crumpLink' => ":edit_side_itm{$linkPayload}",
			'id'        => Request::get('link'),
			'linkType'  => Request::get('t') === 'link' ? 'link': 'doc'
		];
	}

	static function  setSidePageEdit() 
	{
		$linkPayload = (
			Request::exists('page') 
			? '?page='.Request::get('page')
			: Redirect::to(url(':manage_sidepages'))
		);

		self::$context = [
			'app_name'  => 'ILAPI::Edit Side Header',
		    'crumpLink' => ":edit_sidepage{$linkPayload}"
		];
	}
	
	static function contactMessages()
	{
		self::$context = [
			'app_name'  => 'ILAPI::Messages'
		];
	}

	static private function submissionRequestPayload()
    {
        $data = [];
        if(Sessions::exists('requestValues'))
            $data = Sessions::get('requestValues');
        return $data;
    }
}