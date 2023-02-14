<?php namespace App\Controllers;

use App\Models\ilapi_pro_cms\DS\DsCategoryLevel;
use App\Models\ilapi_pro_cms\DS\DsEvent;
use FLY\Libs\Request;
use FLY\MVC\Controller;
use FLY\Routers\Redirect;

final class App extends Controller {

	use Packs\Pages;
    use Events\AppEvent;

    static function index()
    {
        self::setIndexEvents();
    	self::render_view();
    }

    static function about()
    {
        self::render_view();
    }

    static function ajoet()
    {
        self::render_view();
    }

    static function locateSubPage(Request $request)
    {
        if(!$request::exists('type')) Redirect::to(url(':home'));
        $levelName = DsCategoryLevel::get($request->type)->levelName;
        Redirect::to(html_entity_decode(url(":ward?index={$request->type}&title={$levelName}")));
    }

    static function contact()
    {
        self::render_view(self::getRequestReports());
		self::$state['reqValues'] = self::$sessions::exists('reports') ? self::$sessions::get('reports')['data'] : [];
        self::add_context(self::$state);
        self::clearReportSession();
    }

    static function pressRelease()
    {
        self::render_view();
    }

    static function donate()
    {
        self::render_view();
    }

    static function publication()
    {
        self::render_view();
    }

    static function advocacy()
    {
        self::render_view();
    }

    static function programs()
    {
        self::render_view();
    }

    static function research()
    {
        self::render_view();
    }

    static function blog()
    {
        self::render_view();
    }
    
    static function events()
    {
        self::render_view();
    }

    static function page404()
    {
        self::render_view();
    }
    
    static function speakers(Request $request)
    {
        if(is_empty(DsEvent::get($request->s)->eventId))
            Redirect::to(url(':events'));
        self::setEventId($request->s);
        self::render_view();
    }
}