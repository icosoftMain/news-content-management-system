<?php


namespace App\Actors;


use FLY\Libs\Request;

final class AppPages
{
    /**
     * @param Request $request
     */

    static function createPage(Request $request)
    {
        $reqData = $request::all();
   
        if(!in_array('published',$reqData)) {
            $reqData['published'] = '';
        }
                
        (new CreatePage($request))->create($reqData);
    }

    static function setEvent(Request $request)
    {
        $reqData = $request::all();
        if(!in_array('published',$reqData)) {
            $reqData['published'] = '';
        }

        (new SetEvent($request))->create($reqData);
    }

    static function deleteSpeakerSchedule(Request $request)
    {
        return (new Speakers($request))->deleteSchedule();
    }

    static function addSpeakerSchedule(Request $request)
    {
        return (new Speakers($request))->assignSchedule();
    }

    static function updateEvent(Request $request)
    {
        (new UpdateEvent($request))->update();
    }

    static function editPage(Request $request)
    {
        (new UpdatePage($request))->update();
    }

    static function deletePage(Request $request)
    {
        return (new CreatePage($request))->delete();
    }

    static function createSubCategory(Request $request)
    {
        (new CreateSubCategory($request))->create();
    }
    
    static function editSubCategory(Request $request)
    {
        (new UpdateSubCategory($request))->update();
    }

    static function createCategory(Request $request)
    {
        (new CreateCategory($request))->create();
    }

    static function editCategory(Request $request)
    {
        (new UpdateCategory($request))->update();
    }
    
    static function addPartner(Request $request)
    {   
        self::parseLink('partWebName',$request);
        $reqData = $request::all();
        (new AddPartner($request))->saveDetail($reqData);
    }

    static private function parseLink(string $name,Request &$req)
    {
        if($req::exists($name)) {
            if(!preg_match('/^http[s]?[:]\/\/www[.]/',$req::get($name))) {
                $link ='https://www.'.$req::get($name);
                if(preg_match('/^www[.]/',$req::get($name))) {
                    $link = preg_replace('/^www[.]/','https://www.',$req::get($name));
                }
                $req::add($name,$link);
            }
        }

    }

    static function updatePartnerDetails(Request $request)
    {
        (new EditPartner($request))->update();
    }

    static function deletePartner(Request $request)
    {
        return (new AddPartner($request))->delete();
    }

    static function addSpeaker(Request $request)
    {
        $reqData = $request::all();
        (new Speakers($request))->add($reqData);
    }

    static function updateSpeakerDetails(Request $request)
    {
        (new UpdateSpeaker($request))->edit();
    }

    static function deleteSpeaker(Request $request)
    {
        return (new Speakers($request))->delete();
    }

    static function deleteSchedule(Request $request)
    {
       return (new EventSchedules($request))->delete();
    }

    static function deleteEvent(Request $request)
    {
        return (new SetEvent($request))->delete();
    }

    static function updateSchedule(Request $request) 
    {
        $reqData = $request::all();
        (new ScheduleUpdate($request))->save($reqData);
    }
    
    static function addSchedule(Request $request)
    {
        $reqData = $request::all();
        (new EventSchedules($request))->save($reqData);
    }

    static function sendMessage(Request $request)
    {
        $request::add('message',trim($request->message));
        $reqData = $request::all();
        (new ContactMessage($request))->send($reqData);
    }

    static function deleteMessage(Request $request)
    {
        return (new ContactMessage($request))->delete();
    }

    static function readUnreadMessage(Request $request)
    {
        return (new ContactMessage($request))->getReadUnreadMsg();
    }

    static function setMessageStatus(Request $request)
    {
        return (new ContactMessage($request))->setStatus();
    }

    static function createSidePage(Request $request)
    {
        $reqData = $request::all();
        return (new SidePage($request))->create($reqData);
    }

    static function editSidePage(Request $request)
    {
        (new EditSidePage($request))->update();
    }

    static function addSideItem(Request $request)
    {
        self::parseLink('item',$request);
        $reqData = $request::all();
        (new ItemPageLink($request))->add($reqData);
    }

    static function editSideItem(Request $request)
    {
        self::parseLink('item',$request);
        (new EditItemPageLink($request))->update();
    }

    static function deleteSideItem(Request $request)
    {
        return (new ItemPageLink($request))->delete();
    }

    static function deleteSideHeader(Request $request)
    {
        return (new SidePage($request))->delete();
    }

    static function editMembershipForm(Request $request)
    {
        (new EditSidePage($request))->editMemForm();
    }
}