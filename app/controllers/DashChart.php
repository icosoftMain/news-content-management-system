<?php namespace App\Controllers;
use FLY\MVC\Controller;
use FLY\MVC\Model;

final class DashChart extends Controller {

	static function index()
	{
		$viewsPerYear      = Model::query("CALL get_views_peryear");
		$postsPerYear      = Model::query("CALL get_posts_peryear");
		$eventPostsPerYear = Model::query("CALL get_eventpost_peryear");
		$speakersPerYear   = Model::query("CALL get_speakers_peryear");

		$chartYears        = self::getChartsYears($viewsPerYear); 
		$postsViews        = self::getPostViewsStat($viewsPerYear,$eventPostsPerYear);
		$pagePosts         = self::getPostsStats($postsPerYear,	$eventPostsPerYear);
		$speakersStat      = self::getSpeakersStat($speakersPerYear);

		self::add_context([
			'chartYears'   => $chartYears,
			'postsViews'   => $postsViews,
			'pagePosts'    => $pagePosts,
			'speakersStat' => $speakersStat
		]);
	}

	private static function getPostViewsStat($postViews) 
	{
		$data = [];
		foreach($postViews as $pvs) {
			array_push($data,(int) $pvs['PageViews']);
		}
		return $data;
	}

	private static function getPostsStats($posts,$eventPosts)
	{
		$data = [];
		foreach($posts as $pst) {
			$pst['PagePosts'] = (function($_posts,$ePosts){
				foreach($ePosts as $evp) {
					if($evp['EventYear'] === $_posts['PostYear']) {
						return ((int) $evp['EventPosts'] + (int) $_posts['PagePosts']);
					}
				}
				return (int) $_posts['PagePosts'];
			})($pst,$eventPosts);
			array_push($data,$pst['PagePosts']);
		}
		return $data;
	}

	private static function getSpeakersStat($speakers)
	{
		$data = [];
		foreach($speakers as $sps) array_push($data,(int) $sps['Speakers']);
		return $data;
	}

	private static function getChartsYears($payload) 
	{
		$data = [];
		$filtered_data = [];
		$counter = 0;
		foreach($payload as $pd) {
			if(thisYear() >= $pd['ViewYear'])
				array_push($data,$pd['ViewYear']);
		}
		$data = array_reverse($data);

		foreach($data as $dt) {
			if($counter === 8) break;
			array_push($filtered_data,$dt);
			++$counter;
		}
		$data = array_reverse($filtered_data);
		while(count($data) < 8 && count($data) <> 0) {
			$newdate = (string) ((int) $data[count($data) - 1] + 1);
			array_push($data,$newdate);
		}
		return $data;
	}

}