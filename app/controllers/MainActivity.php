<?php namespace App\Controllers;
use App\Actors\{ AppPages, Authenticate, SubCategory, UserAccount };
use App\Models\ilapi_pro_cms\DS\DsEvent;
use FLY\Libs\Request;
use FLY\MVC\Controller;
use FLY\Routers\Redirect;
use FLY\Security\Sessions;

final class MainActivity extends Controller {

	static private $object;

	public function __construct()
    {
        parent::__construct();
        new Sessions;
    }

    /**
     * @param $url_namespace
     */
    static private function locatePath($url_namespace,$concatReq="")
    {
        Redirect::to(url($url_namespace).$concatReq);
    }

    static private function setSessions(Request $request)
    {
        if(!Sessions::get('reports')['state'])
            Sessions::set('requestValues',$request::unsigned_all());
    }

    /**
     * @param Request $request
     * @throws \Exception
     */
    
    static function login(Request $request)
	{
		self::$object = new Authenticate($request);
		self::add_context(self::$object->login());
	}

    /**
     * @param Request $request
     */
    static function addMember(Request $request)
	{
	    UserAccount::create($request);
		self::setSessions($request);
		self::locatePath(':add_user');
	}

    /**
     * @param Request $request
     * @throws \Exception
     */
    
    static function quarantineMember(Request $request)
    {
        self::add_context(UserAccount::quarantine($request));
    }

    /**
     * @param Request $request
     * @throws \Exception
     */
    static function updateAccount(Request $request)
	{
		UserAccount::update($request);
        self::setSessions($request);
        self::locatePath(':my_profile');
	}

    /**
     * @param Request $request
     * @throws \Exception
     */
    static function createSubCategory(Request $request)
	{
		AppPages::createSubCategory($request);
		self::locatePath(/** @lang text */ ':add_sub_category');
    }
    
    static function editSubCategory(Request $request)
	{
		AppPages::editSubCategory($request);
		self::locatePath(/** @lang text */ ':edit_sub_category',Sessions::exists('editSubCatId') ? Sessions::get('editSubCatId') : '');
    }

    /**
     * @param Request $request
     * @throws \Exception
     */
    
    static function createPage(Request $request)
    {
        AppPages::createPage($request);
		self::locatePath(/** @lang text */ ':add_page');
    }

    /**
     * @param Request $request
     * @throws \Exception
     */

     static function updatePage(Request $request)
     {
        AppPages::editPage($request);
        self::locatePath(/** @lang text */ ':edit_page',Sessions::exists('editPage') ? Sessions::get('editPage') : '');
     }

     static function deletePage(Request $request)
     {
        self::add_context(AppPages::deletePage($request));
     }

    /**
     * @param Request $request
     * @throws \Exception
     */

    static function createCategory(Request $request)
	{
        AppPages::createCategory($request);
		self::locatePath(/** @lang text */ ':add_category');        
    }
    
    static function editCategory(Request $request)
    {
        AppPages::editCategory($request);
		self::locatePath(/** @lang text */ ':edit_category',Sessions::exists('editCatId') ? Sessions::get('editCatId') : '');        
    }

    static function selectSubCategory(Request $request)
    {
       if(Request::exists('catName')) {
           self::add_context((new SubCategory($request->catName))->fetch());
       }   
    }

    static function addEvent(Request $request)
    {
        AppPages::setEvent($request);
		self::locatePath(/** @lang text */ ':add_event');        
    }

    static function updateSchedule(Request $request)
    {
        AppPages::updateSchedule($request);
		self::locatePath(/** @lang text */ ':edit_schedule',Sessions::exists('scheduleId') ? Sessions::get('scheduleId') : '');        
    }

    static function addPartner(Request $request)
    {
        AppPages::addPartner($request);
		self::locatePath(/** @lang text */ ':add_partner');        
    }

    static function updatePartner(Request $request)
    {
        AppPages::updatePartnerDetails($request);
		self::locatePath(/** @lang text */ ':edit_partner',Sessions::exists('editPartId') ? Sessions::get('editPartId') : '');        
    }

    static function deletePartner(Request $request)
    {
        self::add_context(AppPages::deletePartner($request));
    }
    
    static function updateEvent(Request $request)
    {
        AppPages::updateEvent($request);
		self::locatePath(/** @lang text */ ':edit_event',Sessions::exists('event') ? Sessions::get('event') : '');        
    }

    static function addSpeaker(Request $request)
    {
        AppPages::addSpeaker($request);
		self::locatePath(/** @lang text */ ':add_speaker');        
    }

    static function updateSpeaker(Request $request)
    {
        AppPages::updateSpeakerDetails($request);
		self::locatePath(/** @lang text */ ':edit_speaker',Sessions::exists('speaker') ? Sessions::get('speaker') : '');        
    }

    static function deleteSpeaker(Request $request)
    {
        self::add_context(AppPages::deleteSpeaker($request));
    }

    static function deleteSpeakerSchedule(Request $request)
    {
        self::add_context(AppPages::deleteSpeakerSchedule($request));
    }

    static function addSpeakerSchedule(Request $request)
    {   
        self::add_context(AppPages::addSpeakerSchedule($request)); 
    }

    static function addSchedule(Request $request)
    {
        AppPages::addSchedule($request);
        $eventName = DsEvent::get($request->eventId)->eventName;
		self::locatePath(/** @lang text */ ":add_schedule?event={$eventName}");        
    }

    static function deleteSchedule(Request $request)
    {
       self::add_context(AppPages::deleteSchedule($request));
    }

    static function deleteEvent(Request $request)
    {
        self::add_context(AppPages::deleteEvent($request));
    }

    static function sendMessage(Request $request)
    {
        AppPages::sendMessage($request);
        self::locatePath(/** @lang text */':contact');
    }

    static function deleteMessage(Request $request)
    {
       self::add_context(AppPages::deleteMessage($request));
    }

    static function readUnreadMessage(Request $request)
    {
        self::add_context(AppPages::readUnreadMessage($request));
    }

    static function setMessageStatus(Request $request)
    {
        self::add_context(AppPages::setMessageStatus($request));
    }

    static function addSidePageHead(Request $request)
    {
        self::add_context(AppPages::createSidePage($request));
    }

    static function addSideItem(Request $request)
    {
        AppPages::addSideItem($request);
        self::locatePath(/** @lang text */":add_side_itm","?ps={$request->spId}&t={$request->linkType}");
    }

    static function editSidePage(Request $request)
    {
        AppPages::editSidePage($request);
        self::locatePath(/** @lang text */":edit_sidepage","?page={$request->id}");
    }

    static function editSideItem(Request $request)
    {
        AppPages::editSideItem($request);
        self::locatePath(/** @lang text */":edit_side_itm","?link={$request->levelId}&t={$request->linkType}");
    }

    static function deleteSideItem(Request $request)
    {
        self::add_context(AppPages::deleteSideItem($request));
    }

    static function deleteSideHeader(Request $request)
    {
        self::add_context(AppPages::deleteSideHeader($request));
    }

    static function editMembershipForm(Request $request)
    {
        AppPages::editMembershipForm($request);
        self::locatePath(/** @lang text */":manage_sidepages");
    }
}