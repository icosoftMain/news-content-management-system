<?php namespace App\Controllers\Packs;
use App\Models\ilapi_pro_cms\DS\
{
    DsCategory,
    DsContact,
    DsEvent,
    DsPage,
    DsSidePages
};
use FLY\Libs\Request;

trait Filters {
    use Pages;
    private static $model;

    static public function setModels(Request $request)
    {
		self::$model = [];

        if($request->modelType === 'PAGE'): 
			self::$model = DsPage::all();
			elseif($request->modelType === 'CATEGORY'):
				self::$model = DsCategory::all();
			elseif($request->modelType === 'SUBCATEGORY'):
				self::$model = self::getSubCategoryFields();
			elseif($request->modelType === 'EVENT'):
				self::$model = DsEvent::all();
			elseif($request->modelType === 'SPEAKERS'):
				self::$model = self::getSpeakers();
			elseif($request->modelType === 'PARTNERS'):
				self::$model = self::getPartners();
			elseif($request->modelType === 'MESSAGES'):
				self::$model = DsContact::all();
			elseif($request->modelType === 'USERS'):
                self::$model = self::getUsers();
            elseif($request->modelType === 'SIDEPAGE'):
                self::$model = DsSidePages::all();
		endif;
    }

    static private function searchField(string $searchValue, array $model)
    {
        $searchValue = preg_replace(
			'/(?:\[|\])/'
			,'%'
			,str_replace('/','\\/',$searchValue)
        );
        if(is_empty($searchValue)) 
            return self::limitFields($model,5);

        return self::searchEngine($searchValue,$model);
    }

    static public function paginate(int $pagIndex,int $limit,array $model,$reverse)
    {
        if($reverse !== 'false') $model = \array_reverse($model,true);
        $listWrap = [];
        $modLen = count($model);
        $control = ceil($modLen/$limit);
        $count = 0;
        for($index = 0; $index < $control; $index++) {
            $listWrap = [];
            for($j = ($count++)*$limit; $j < ($index + 1) * $limit; $j++) {
                if(!isset($model[$j])) break;
                array_push($listWrap,$model[$j]);
            }
            if(($index + 1) === $pagIndex) break;
        }
        return $listWrap;
    }

    static private function searchEngine($searchValue, $model)
    {
        if(preg_match('/%\s*(?P<searchKey>[a-zA-Z_][a-zA-Z_\s]*)%(?P<searchValue>.*)/',$searchValue,$match))
            return self::searchSpecific($match,$model);
        return self::searchGeneral($searchValue,$model);
    }

    static private function searchGeneral($searchValue, $models)
    {
        $payload = [];
        $searchValue = self::searchAdvance($searchValue);
        foreach($models as $model) {
            foreach($model as $mdValue) {
                if(preg_match('/^'.$searchValue.'/i',trim($mdValue),$match)) {
                    if(!is_empty($match[0]))
                        array_push($payload,$model);
                    break;
                }
            }
        }
        return $payload;
    }

    static private function searchSpecific(array $match, array $models)
    {
        $payload = [];
        if(isset($match['searchKey']) && isset($match['searchValue'])) {
            $searchKey = \str_camel(trim($match['searchKey']));
            $searchValue = trim($match['searchValue']);
            foreach($models as $model) {
                if(!isset($model[$searchKey])) break;
                if(preg_match('/^'.self::searchAdvance($searchValue).'/i',trim($model[$searchKey]),$match)) {
                    if(!is_empty($match[0]))
                        array_push($payload,$model);
                }
            }
        }
        return $payload; 
    }

    static private function searchAdvance(string $searchValue)
    {
        $flag = false;
        if(preg_match('/[*]/',$searchValue)) {
            $searchValue = preg_replace('/[*]+/','(?:.*)',$searchValue);
            $flag = true;
        }
        if(preg_match('/[_]/',$searchValue)) {
            $searchValue = preg_replace('/[_]/','(?:.)',$searchValue);
            $flag = true;
        }
        return $flag ? "{$searchValue}$" : $searchValue;
    }
}
