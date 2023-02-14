<?php namespace App\Actors;

use App\Models\ilapi_pro_cms\DS\{ DsCategory, DsCategoryLevel};
use FLY\Security\Sessions;

class SubCategory {

    private $categoryName;

    public function __construct(string $categoryName)
    {
        $this->categoryName = $categoryName;
    }

    public function fetch() 
    {
        $payload = [];

        if(Sessions::exists('event_get_levelNames')) {
            $payload = Sessions::get('event_get_levelNames');
            Sessions::remove('event_get_levelNames');
        } else {
            $catModel = new DsCategory("","",$this->categoryName);
            $catModel = $catModel::fetch(':categoryName {1}');
            $catLevelModel = new DsCategoryLevel(
                '',
                '',
                $catModel->categoryId[0]['categoryId']
            );
            $payload = $catLevelModel::fetch(':categoryId')->levelName;
        }

        return ['payload' => $payload];
    }
}