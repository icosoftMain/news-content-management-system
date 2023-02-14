<?php namespace App\Controllers\Events;

use App\Models\ilapi_pro_cms\DS\{
    DsCategory,
    DsCategoryLevel,
    DsCategoryPage,
    DsEvent,
    DsPage,
    DsPageImages,
    DsSubCategoryPage
};
use App\Widgets\Components\SliderBanner;
use FLY\Libs\Event;

trait AppEvent {

    static private function setIndexEvents()
    {
        Event::listener('on_load_sliderBanner',function(){return self::renderSliders();});
    }

    static private function setPageDetailEvent($req)
    {
        $pageModel = DsPage::get($req->search);
        $pageViews = ((int) $pageModel->pageViews);
        $pageModel->pageViews = ++$pageViews;
        $pageModel->edit();
        $pageImageModel = DsPageImages::fetch(':pageId {1}',new DsPageImages('',$req->search));
        Event::listener('on_pageRequest',function() use ($pageModel,$pageImageModel) {
            return [
                'type'      => 'page',
                'page'      => $pageModel,
                'pageImage' => $pageImageModel
            ];
        });
        return $pageModel->published === 'Y';
    }

    static private function setEventDetailEvent($req)
    {
        $pageModel = DsEvent::get($req->search);
        Event::listener('on_pageRequest',function() use ($pageModel) {
            return [
                'type'      => 'events',
                'page'      => $pageModel
            ];
        });
        return $pageModel->published === 'Y';
    }

    static private function setEventId($eventId)
    {
        Event::listener('on_speakerRequest',function() use($eventId) {return $eventId;});
    }

    static private function showPage($req)
    {
        $flag = false;
        if(self::pageExists($req)) {
            $flag = true;
            switch($req::exists('type')? $req->type : 'page') {
                case 'events':
                    $flag = self::setEventDetailEvent($req);
                break;
                default:
                    $flag = self::setPageDetailEvent($req);
                break;
            } 
        }
        return $flag;
    }

    static private function pageExists($req)
    {
        $flag = false;
        switch($req::exists('type')? $req->type : 'page') {
            case 'events':
                $flag = !is_empty(DsEvent::get($req->search)->eventName);
            break;
            default:
                $flag = !is_empty(DsPage::get($req->search)->title);
            break;
        } 
        return $flag;
    }

    static private function setSliders($pages, array $pics, array &$banners,$hasSlider=true)
    {
        $counter = 0;
        if(!$hasSlider) {
            self::setEmptyBanners($pics,$banners);
        } else {
            foreach($pages->title as $key => $title) {
                if(!isset($pics[$counter])) $counter = 0;
                if($pages->published[$counter]['published'] <> 'Y') continue;
                array_push($banners,new SliderBanner(
                    $pics[$counter++]['imageName'],
                    $title['title'],
                    
                    word_lmt($pages->content[$key]['content'],20).'...'
                    ,
                    ":reader?search={$pages->pageId[$key]['pageId']}&title={$title['title']}"
                ));
            }
            if(is_empty($banners)) {
                self::setEmptyBanners($pics,$banners);
            }
        }
        
    }

    static private function setEmptyBanners(array $pics, array &$banners)
    {
        foreach($pics as $pic) {
            array_push($banners,new SliderBanner(
                $pic['imageName'],
                '',
                '',
                ''
            ));
        }
    }

    static private function renderSliders()
    {
        $pics = [ 
            [
                'imageName' => 'banner.jpg'
            ],
            [
                'imageName' => 'banner1.jpg'
            ] 
        ];
        $banners = [];
        $trendingPages = self::trendingPages();
        $defaultPages  = self::defaultPages();
        if(isset($trendingPages->title[0])) 
            self::setSliders($trendingPages,$pics,$banners);
        else if(isset($defaultPages->title[0])) 
            self::setSliders($defaultPages,$pics,$banners);
        else 
            self::setSliders(null,$pics,$banners,false);

        return self::filterBanners(array_reverse($banners));
    }

    static private function filterBanners(array $banners)
    {
        $newbanners = [];
        foreach($banners as $key => $banner) {
            if($key === 7) break;
            array_push($newbanners,$banner);
        }
        return $newbanners;
    }

    static private function getPageImage($pageId)
    {
        $model = new DsPageImages;
        $model->pageId = $pageId;
        $img = DsPageImages::fetch(':pageId {1}',$model);
        $imageName = "";
        if(isset($img->imageName[0]['imageName'])) {
            $imageName = "pages/{$img->imageName[0]['imageName']}";
        }
        return $imageName;

    }

    static private function trendingPages()
    {
        $model = new DsPage;
        $model->pageType = 'trending';

        return DsPage::fetch(':pageType',$model);
    }

    static private function defaultPages()
    {
        $model = new DsPage;
        $model->pageType = 'default';

        return DsPage::fetch(':pageType',$model);
    }

    static public function setCategories(string $categoryName)
    {
        $categoryName = $categoryName <> 'pressRelease'?$categoryName:'press';
        Event::listener('on_categoryRequests',function() use($categoryName) { return self::getCategoryPageIds($categoryName);});
    }

    static private function getCategoryPageIds($categoryName)
    {
        $payload = [];
        $model = new DsCategory('','',$categoryName);
        $modelFetch = DsCategory::fetch(':categoryName {1}',$model);
        if(isset($modelFetch->categoryId[0]['categoryId'])) {
            $model = new DsCategoryPage;
            $model->categoryId = $modelFetch->categoryId[0]['categoryId'];
            $payload = (DsCategoryPage::fetch(':categoryId',$model))->pageId;
        }

        return $payload;
    }

    static public function setSubCatgories(string $levelId)
    {
        $pageModel = self::getSubCategoryPageIds($levelId);
        Event::listener('on_subCategoryRequests', function() use($pageModel) { return $pageModel; });
        Event::listener('get_subCategoryName', function() use($levelId) {
            return DsCategoryLevel::get($levelId)->levelName;
        });
        return isset($pageModel[0]['pageId']);
    }

    static private function getSubCategoryPageIds($levelId)
    {
        $model = new DsSubCategoryPage('',$levelId);
        $modelFetch = DsSubCategoryPage::fetch(':subCategoryId',$model);

        return $modelFetch->pageId;
    }

    static private function setSearchResult($result)
    {
        Event::listener('on_pageSearchRequest',function() use($result){return $result;});
    }

}