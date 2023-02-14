<?php namespace App\Views;

use App\Controllers\Events\AppEvent;
use App\Controllers\Packs\Contexts;
use App\Controllers\Packs\Shares;
use App\Widgets\Pages\{ 
	Advocacy_Page,
    Ajoet_Page,
    Blog_Page,
    Centers_Page,
    Events_Page,
    Page_404,
    Page_Stage,
    PressRelease_Page,
    Programs_Page,
    Publication_Page,
    Research_Page,
    Search_Page,
    Speakers_Page
};
use FLY\Libs\Event;
use FLY\MVC\View;
use FLY\Routers\Redirect;

final class DisplayView extends View {

    use Contexts;

	use Shares;

	static function index()
	{   
		$event = Event::emit('on_pageRequest');
		$title = "";
		$eventShedules = [];
		switch($event['type']) {
			case 'events':
				$title = "::{$event['page']->eventName}";
				$eventShedules = self::_currentEventSchedule();
			break;
			default:
				$title = "::{$event['page']->title}";
			break;
		}

		self::$context = [
            'app_name'    => "ILAPI{$title}",
            'active_type' => '',
			'technologyType' => '',
			'eventSchedules' => $eventShedules
			
		];
    	return new Page_Stage;
	}

	static function categories($categoryName)
	{
		$page = null; 
		AppEvent::setCategories($categoryName);
		switch($categoryName) {
			case 'publication': 
				$page = new Publication_Page;
			break;
			case 'advocacy':
				$page = new Advocacy_Page;
			break;
			case 'programs':
				$page = new Programs_Page;
			break;
			case 'research':
				$page = new Research_Page;
			break;
			case 'blog':
				$page = new Blog_Page;
			break;
			case 'events':
				$page = new Events_Page;
			break;
			case 'ajoet':
				$page = new Ajoet_Page;
			break;
			case 'pressRelease':
				$page = new PressRelease_Page;
			break;
			case 'speakers':
				$page = new Speakers_Page;
			break;
			case '404':
				$page = new Page_404;
			break;
			default:
				Redirect::to(url(':home'));
			break;
		}
		return $page;
	}

	static function subCategories()
	{
		return new Centers_Page;
	}

	static function searchResults()
	{
		return new Search_Page;
	}

}