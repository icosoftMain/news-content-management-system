<?php namespace App\Controllers;

use App\Actors\Authenticate;
use App\Models\ilapi_pro_cms\DS\{
	DsCategory,
	DsCategoryLevel,
	DsCategoryPage,
    DsContact,
    DsEvent,
	DsLogin,
	DsPage,
	DsSubCategoryPage,
	DsSpeakers,
	DsPartners,
	DsSidePages,
	DsSidePagesLevel
};
use FLY\Libs\{FileReader, Request};
use FLY\MVC\Controller;
use FLY\MVC\Model;
use FLY\Routers\Redirect;

final class Admin extends Controller {

	use Packs\Pages;
	use Events\Schedules;

	static private $userDetails;

    static private function show()
	{
		self::$userDetails = Authenticate::detective();
		self::render_view([
			'user'         => self::$userDetails,
			'profileImage' => 'images/profilepics/'.self::$userDetails->imageName,
			'adminId'      => self::$sessions::get('admin') 
		]);
	}

	static function index()
	{
		Authenticate::userLogRedirect();
    	self::render_view();
	}

	static function dashboard()
	{
		self::show();
		self::clearReportSession();
		self::add_context([
			'totalPost'     => DsPage::count() + DsEvent::count(),
			'totalSpeakers' => DsSpeakers::count(),
			'totalViews'    => self::calculateTotalViews(DsPage::all())
		]);
	}

	static private function calculateTotalViews($pages)
	{
		$total = 0;
		foreach($pages as $page) $total += (int) $page['pageViews'];
		return $total;
	}

    /**
     * @throws \Exception
     */
    static function myProfile()
	{
		self::show();
		self::add_context([
			'logDetails' => DsLogin::get(self::$sessions::get('admin'))
		]);
        self::add_context(self::getRequestReports());
    }

	static function donations()
	{
		self::clearReportSession();
		self::show();
	}

	static function addPages()
	{
		self::show();	
		self::add_context(self::getRequestReports());
		self::$state['reqValues'] = self::$sessions::exists('reports') ? self::$sessions::get('reports')['data'] : [];
		self::$state['pageTypes'] = FileReader::fetchJSON('app/store/pageType');
		self::add_context(self::$state);
		self::$sessions::remove('reports');
	}

	static function editPages(Request $request)
	{
		self::show();
		self::add_context(self::getRequestReports());
		self::$sessions::set('editPage',"?page={$request->page}");
		$model = DsPage::get($request->page);
		self::authEditRequest($model);
		self::setPageRequestValue($model,FileReader::fetchJSON('app/store/pageType'));
		self::setEditCategoryName($request);
		self::add_context(self::$state);
	}

	static private function authEditRequest(DsPage $model)
	{
		if(is_empty($model->pageId)) Redirect::to(url(':manage_pages'));
	}

	static private function authRequest(string $requestType, string $url)
	{
		if(is_empty($requestType)) Redirect::to(url($url));
	}

	static private function setPageRequestValue(DsPage $model, array $pageTypes)
	{
		self::$state['reqValues'] = [
			'title'     => $model->title,
			'content'   => $model->content,
			'published' => $model->published,
			'source'    => $model->source
		];
		self::setEditPageTypes($model,$pageTypes);
	}

	static private function setEditPageTypes(DsPage $model,array $pageTypes)
	{
		$pageType = $model->pageType;
		$payload = [(object) ['id' => $pageType, 'name' => ucfirst($pageType)]];
		foreach($pageTypes as $type) {
			if($type->id <> $payload[0]->id) 
				array_push($payload,$type);
		}
		self::$state['pageTypes'] = $payload;
	}

	static private function setEditCategoryName(Request $request)
	{
		$model = new DsCategoryPage;
		$model->pageId = $request->page;
		$model = $model::fetch(':pageId{1}');
		$subCategory = null;
		$categoryId = isset($model->categoryId[0]['categoryId']) ? $model->categoryId[0]['categoryId']
		: (
			function($subModel,$req,&$subCat) {
				$subModel->pageId = $req->page;
				$subModel = $subModel::fetch(':pageId{1}');

				$subCatId = isset($subModel->subCategoryId[0]['subCategoryId'])
				? $subModel->subCategoryId[0]['subCategoryId'] : '';
				$subCat = DsCategoryLevel::get($subCatId);
				return $subCat->categoryId;
			}
		)(new DsSubCategoryPage,$request,$subCategory);

		self::$state['categoryName'] = self::arrangeEditCategories($categoryId);
		self::$state['pageId']       = $request->page;
		self::arrangeEditSubCategories($subCategory, $categoryId);
	}

	static private function arrangeEditSubCategories($subCategory, $categoryId)
	{
		if($subCategory <> null) {
			$subCatName = $subCategory->levelName;
			$catLevel = new DsCategoryLevel('','',$categoryId);
			$catLevel = $catLevel::fetch(':categoryId');
			$data = $catLevel->levelName;
			foreach($data as $dt) {
				if($subCatName === $dt['levelName']) {
					$payload[] = $dt;
					break;
				}
			}

			foreach($data as $dt) {
				if($payload[0]['levelName']<>$dt['levelName']) {
					array_push($payload,$dt);
				}
			}
			self::$sessions::set('event_get_levelNames',$payload);
		}
	}

	static private function arrangeEditCategories($categoryId)
	{
		$categoryName = DsCategory::get($categoryId)->categoryName;

		$payload = [];
		$data = DsCategory::all();

		foreach($data as $dt) {
			if($categoryName === $dt['categoryName']) {
			  	$payload[] = $dt;
				break;
			}
		}

		foreach($data as $dt) {
			if($payload[0]['categoryName']<>$dt['categoryName']) {
				array_push($payload,$dt);
			}
		}
		return $payload;
	}

	static function managePages()
	{
		self::show();
		self::add_context(self::getRequestReports());
		self::add_context([
			'pages'  => self::displayLimit(DsPage::all(),5)
		]);
		self::clearReportSession();
	}

	static function manageCategories()
	{
		self::show();
		self::clearReportSession();
		self::add_context([
			'categories' => self::displayLimit(DsCategory::all(),5)
		]);
	}

    /**
     * @throws \Exception
     */
    static function manageSubCategories()
	{
		self::show();
        self::clearReportSession();
        self::add_context([
            'subCategories' => self::displayLimit(DsCategoryLevel::all(),5),
            'Category'      => new DsCategory
        ]);
	}

	static function addCategoryPages() 
	{
		self::show();	
		self::add_context(self::getRequestReports());
	}

	static function editCategoryPages(Request $request) 
	{
		self::setEditCategoryDetail($request);
		self::show();
		self::add_context(self::getRequestReports());
		self::add_context(self::$state);
	}

	static private function setEditCategoryDetail(Request $request)
	{	
		if(Request::exists('cat')) {
			self::$sessions::set('editCatId','?cat='.$request->cat);
			$model = DsCategory::get($request->cat);
			self::authRequest($model->categoryId,':manage_categories');
			if(trim($model->categoryName) <> "") {
				self::$state['categoryName'] = $model->categoryName;
				self::$state['visibility'] = $model->visible;
				self::$state['status']     = $model->_status;
				self::$state['catId']      = $request->cat;
				self::$state['buttonText'] = 'Edit Category';
				self::$state['crumpLink'] = ":edit_category?cat=$request->cat";
			}
		} else self::authRequest('',':manage_categories');
		
	}

    /**
     * @throws \Exception
     */
    static function addSubCategoryPages()
	{
		self::show();
		self::add_context(self::getRequestReports());
	}

	static function editSubCategoryPages(Request $request)
	{
		self::show();
		self::add_context(self::getRequestReports());
		self::setEditSubCategoryDetail($request);
		self::add_context(self::$state);
	}

	static private function setEditSubCategoryDetail(Request $request)
	{	
		if(Request::exists('cat') && Request::exists('level')) {
			self::$sessions::set('editSubCatId',"?level=$request->level&cat=$request->cat");
			$cmodel = DsCategory::get($request->cat);	
			$smodel = DsCategoryLevel::get($request->level);
			self::authRequest($cmodel->categoryId,':manage_sub_categories');
			self::authRequest($smodel->categoryId,':manage_sub_categories');
			self::setSubCategoryEditState($cmodel,$smodel,$request);
		} else self::authRequest('',':manage_sub_categories');
		
	}

	static private function setSubCategoryEditState(DsCategory $cmodel, DsCategoryLevel $smodel, Request $request)
	{
		if(!is_empty($cmodel->categoryName)) {
			self::$state['levelName']    = $smodel->levelName;
			self::$state['crumpLink']    = ":edit_sub_category?level=$request->level&cat=$request->cat";
			self::$state['buttonText']   = 'Edit Sub Category';
			self::$state['levId']        = $request->level;
			self::$state['cId']          = $request->cat;
			$catName = DSCategory::all();
			$data = [];

			foreach($catName as $name) {
				if($name['categoryName'] === $cmodel->categoryName) {
					array_push($data,$name);
					break;
				}
			}

			foreach($catName as $name) {
				if($data[0]['categoryName'] <> $name['categoryName'])
					array_push($data,$name);
			}
			self::$state['categoryName'] = $data;
		}
	}

	static function addUser() 
	{
		self::show();
        self::add_context(self::getRequestReports());
	}

	static function manageUser(Request $request)
	{
		self::show();

		self::add_context([
		    'allUsers'  => self::displayLimit(Model::query('CALL get_users'),5),
            'alertType' => isset($request::all()['user']) && $request->user !=="" ? 'success'      : '',
            'alertText' => isset($request::all()['user']) && $request->user !=="" ? $request->user : ''
        ]);
        self::clearReportSession();
	}

	static function addEvent() 
	{
		self::show();
		self::add_context(self::getRequestReports());
		self::$state['reqValues'] = self::$sessions::exists('reports') ? self::$sessions::get('reports')['data'] : [];
		self::add_context(self::$state);
	}

	static function editEvent(Request $request)
	{
		if(Request::exists('event')) {
			$model = DsEvent::get($request->event);
			self::authRequest($model->eventId,':manage_events');
			self::$sessions::set('event','?event='.$request->event);
			$state = [];
			$state['eventName']    = $model->eventName;
			$state['_location']    = $model->_location;
			$state['_description'] = $model->_description;
			self::$state['eventTypes'] = self::getSelectEventType($model);
			$state['published']        = $model->published;
			self::$state['reqValues']  = $state;
			self::$state['eventId']    = $model->eventId;
		} else self::authRequest('',':manage_events');

		self::show();
		self::add_context(self::getRequestReports());
		self::add_context(self::$state);
	}

	static private function getSelectEventType(DsEvent $model)
	{
		$eventType = $model->eventType;
		$events    = FileReader::fetchJSON('app/store/eventType');
		$payload   = [];
		foreach($events as $event) {
			if($event->id === $eventType) {
				array_push($payload,$event);
			break;
			}
		}

		foreach($events as $event) {
			if($event->id <> $eventType) {
				array_push($payload,$event);
			}
		}
		return $payload;
	}

	static function currentSchedules(Request $request)
	{
		if($request::exists('speaker')) {
			$model = DsSpeakers::get($request->speaker);
			self::authRequest($model->speakerId,':manage_speakers');
			self::$sessions::set('speaker','?speaker='.$request->speaker);
			self::$state['speakerId']         = $model->speakerId;
			self::$state['speakerImageName']  = $model->imageName;
			self::$state['speakerFullName']   = "$model->title $model->firstName $model->lastName";
			self::$state['availableScheds']   = self::fetchCurrentSchedules(self::$state['speakerId']);
			self::$state['scheduleType']      = 'unassigned';
			self::$state['actionType']        = 'Assign';
			self::$state['crumpTitle']        = 'Speaker Event Schedule Form';
			self::$state['speakerImageTitle'] = "Assign event schedule to $model->title $model->firstName $model->lastName";
			self::$state['emptySchedule']     = "No schedule is available for&nbsp;";
			self::$state['schedTypeLink']     = ":assigned_schedules?speaker=".self::$state['speakerId'];
			self::$state['schedTypeLabel']    = "Assigned Schedules";

		} else self::authRequest('',':manage_speakers');

		self::show();
		self::add_context(self::getRequestReports());
		self::add_context(self::$state);
		self::clearReportSession();
	}

	static function assignedSchedules(Request $request)
	{
		if($request::exists('speaker')) {
			$model = DsSpeakers::get($request->speaker);
			self::authRequest($model->speakerId,':manage_speakers');
			self::$sessions::set('speaker','?speaker='.$request->speaker);
			self::$state['speakerId']         = $model->speakerId;
			self::$state['speakerImageName']  = $model->imageName;
			self::$state['speakerFullName']   = "$model->title $model->firstName $model->lastName";
			self::$state['availableScheds']   = self::getCurrAssignedSchedules(self::$state['speakerId']);
			self::$state['scheduleType']      = 'assigned';
			self::$state['actionType']        = 'Delete';
			self::$state['crumpTitle']        = 'Speaker Assigned Schedule';
			self::$state['speakerImageTitle'] = "$model->title $model->firstName $model->lastName's assigned schedules";
			self::$state['emptySchedule']     = "No schedule is assigned to&nbsp;";
			self::$state['schedTypeLink']     = ":add_speaker_schedules?speaker=".self::$state['speakerId'];
			self::$state['schedTypeLabel']    = "Available Schedules";

		} else self::authRequest('',':manage_speakers');

		self::show();
		self::add_context(self::getRequestReports());
		self::add_context(self::$state);
		self::clearReportSession();
	}

	static function manageEvents() 
	{
		
		self::show();
		self::add_context(self::getRequestReports());
		self::add_context([
			'events' => self::displayLimit(DsEvent::all(),5)
		]);
		self::clearReportSession();
	}

	static function eventSpeakers()
	{
		self::show();
		self::add_context(self::getRequestReports());
		self::add_context([
			'speakers' => self::displayLimit(DsSpeakers::all(),5)
		]);	
		self::clearReportSession();
	}

	static function addSpeaker() 
	{
		self::show();
		self::add_context(self::getRequestReports());
		self::$state['reqValues'] = self::$sessions::exists('reports') ? self::$sessions::get('reports')['data'] : [];
		self::add_context(self::$state);
	}

	static function editSpeaker(Request $request)
	{
		if($request::exists('speaker')) {
			$model = DsSpeakers::get($request->speaker);
			self::authRequest($model->speakerId,':manage_speakers');
			self::$sessions::set('speaker','?speaker='.$request->speaker);
			$state = [];
			$state['title']            = $model->title;
			$state['firstName']        = $model->firstName;
			$state['lastName']         = $model->lastName;
			$state['email']            = $model->email;
			$state['phoneNumber']      = $model->phoneNumber;
			$state['about']            = $model->about;
			self::$state['reqValues']  = $state;
			self::$state['speakerId']  = $model->speakerId;

		} else self::authRequest('',':manage_speakers');
		self::show();
		self::add_context(self::getRequestReports());
		self::add_context(self::$state);
	}

	static function ourPartners()
	{
		self::show();
        self::add_context(self::getRequestReports());
		self::add_context([
			'partners'  => self::displayLimit(DsPartners::all(),5)
		]);
		self::clearReportSession();
	}

	static function addPartner()
	{
		self::show();
		self::add_context(self::getRequestReports());
		self::$state['reqValues'] = self::$sessions::exists('reports') ? self::$sessions::get('reports')['data'] : [];
		self::add_context(self::$state);
	}

	static function editPartner(Request $request)
	{
		if($request::exists('partner')) {
			$model = DsPartners::get($request->partner);
			self::authRequest($model->partName,':manage_partners');
			$state = [];
			self::$sessions::set('editPartId',"?partner={$request->partner}");
			self::$state['partId'] = $request->partner;
			$state['partName']     = $model->partName;
			$state['partWebName']  = $model->partWebName;
			self::$state['reqValues']  = $state;
			self::add_context(self::$state);
		} else self::authRequest('',':manage_partners');
		self::show();
        self::add_context(self::getRequestReports());
	}

	static function contactMessages()
	{
		self::show();
        self::add_context(self::getRequestReports());
		self::add_context([
			'messages'  => self::displayLimit(DsContact::all(),5)
		]);
		self::clearReportSession();
	}

	static function sidePages()
	{
		self::show();
		self::add_context(self::getRequestReports());
		self::add_context([
			'sidePages' => self::displayLimit(DsSidePages::all(),5),
			'pageLinks' => DsSidePagesLevel::all()
		]);
		self::clearReportSession();
	}

	static function setSideItem(Request $request)
	{
		$model = DsSidePages::get($request::get('ps'));
		self::authRequest($model->pageName,':manage_sidepages');
		self::show();
		self::$state['reqValues'] = self::$sessions::exists('reports') ? self::$sessions::get('reports')['data'] : [];
		self::add_context(self::$state);		
		self::add_context(self::getRequestReports());
		self::clearReportSession();
	}

	static function editSideItem(Request $request)
	{
		$model = DsSidePagesLevel::get($request::get('link'));
		self::authRequest($model->levelName,':manage_sidepages');
		self::show();
		self::$state['reqValues'] = [
			'levelName' => $model->levelName,
			'item'      => $request::get('t') === 'link' ? $model->item : ''
		];
		self::add_context(self::$state);		
		self::add_context(self::getRequestReports());
		self::clearReportSession();
	}

	static function setSidePageEdit(Request $request)
	{
		$model = DsSidePages::get($request::get('page'));
		self::authRequest($model->pageName,':manage_sidepages');
		self::show();
		self::$state['reqValues'] = $model;
		self::add_context(self::$state);		
		self::add_context(self::getRequestReports());
		self::clearReportSession();
	}

	static function logout()
	{
		self::$sessions::removeAll();
		Redirect::to(url(':admin'));
	}
}