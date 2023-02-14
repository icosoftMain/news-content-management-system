<?php namespace App\Controllers;

use FLY\MVC\Controller;
use FLY\Libs\Request;
use FLY\Routers\Redirect;

final class DisplayController extends Controller {
    use Events\AppEvent;

	static function index(Request $request)
	{
    	if(!self::showPage($request)) Redirect::to(url(':home'));
    	self::render_view();
	}

	static function searchResults(Request $request)
	{
		if($request::count() > 1) Redirect::to(url(":atSearch?q={$request->q}"));
		self::setSearchResult(Search::getSearchData($request));
		self::render_view(['app_name' => "ILAPI::Searching for ".str_capitalize($request->searchValue,'','')]);
	}

	static function subCategories(Request $request)
	{
		if(!self::setSubCatgories($request->index)) Redirect::to(url(':home'));
		self::render_view(['app_name' => "ILAPI::".str_capitalize($request->title,'',' ')]);
	}

}