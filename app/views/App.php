<?php namespace App\Views;

use App\Controllers\Packs\Contexts;
use App\Models\ilapi_pro_cms\DS\DsEvent;
use FLY\MVC\View;
use App\Widgets\Pages\{ 
    Home_Page, 
    About_Page,
    Contact_Page,
    Donate_Page 
};
use FLY\Libs\Request;

final class App extends View {
    use Contexts;
    
    static function index()
    {
    	self::$context = [
            'app_name'    => 'ILAPI::Home',
            'active_type' => 'home',
            'technologyType' => ''
        ];
	    return new Home_Page;
    }

    static function contact()
    {
        self::$context = [
            'app_name'    => 'ILAPI::Contact'
        ];
        return new Contact_Page;
    }

    static function donate()
    {
        self::$context = [
            'app_name'    => 'ILAPI::Donate'
        ];
        return new Donate_Page;
    }

    static function about()
    {
        self::$context = [
            'app_name' => 'ILAPI::About',
            'active_type' => 'about'
        ]; 
        return new About_Page;
    }

    static function pressRelease()
    {
        self::$context = [
            'app_name' => 'ILAPI::Press Release',
            'active_type' => 'press'
        ];
        return DisplayView::categories('pressRelease');
    }

    static function publication()
    {
        self::$context = [
            'app_name' => 'ILAPI::Publication',
            'active_type' => 'publication'
        ];
        return DisplayView::categories('publication');
    }

    static function advocacy()
    {
        self::$context = [
            'app_name' => 'ILAPI::Advocacy',
            'active_type' => 'advocacy'
        ];
        return DisplayView::categories('advocacy');
    }

    static function programs()
    {
        self::$context = [
            'app_name' => 'ILAPI::Programs',
            'active_type' => 'programs'
        ];
        return DisplayView::categories('programs');
    }
    
   static function research()
    {
        self::$context = [
            'app_name' => 'ILAPI::Research',
            'active_type' => 'research'
        ];
        return DisplayView::categories('research');
    }

    static function blog()
    {
        self::$context = [
            'app_name' => 'ILAPI::Blog',
            'active_type' => 'blog'
        ];
        return DisplayView::categories('blog');
    }

    static function events()
    {
        self::$context = [
            'app_name' => 'ILAPI::Events',
            'active_type' => 'events'
        ];
        return DisplayView::categories('events');
    }

    static function ajoet()
    {
        self::$context = [
            'app_name' => 'ILAPI::Ajoet',
            'active_type' => ''
        ];
        return DisplayView::categories('ajoet');
    }

    static function speakers()
    {
        $eventName = DsEvent::get(Request::get('s'))->eventName;
        self::$context = [
            'app_name'    => "ILAPI::Speakers of {$eventName}",
            'scheduleType'=> 'assigned',
            'activeType'  => ''
        ];
        return DisplayView::categories('speakers');
    }

    static function page404()
    {
        self::$context = [
            'app_name'  => 'ILAPI::404 PAGE'
        ];
        return DisplayView::categories('404');
    }
}