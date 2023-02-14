<?php namespace App\Controllers\Packs;

use App\Models\ilapi_pro_cms\DS\{ 
    DsCategory,
    DsCategoryLevel,
    DsCategoryPage,
    DsPage,
    DsPageImages,
    DsPartners
};

trait Contexts {
    static $context;

    public function __construct()
    {
        parent::__construct();
        $ids = [];
        $stata = [];
        $_header_links = $this->get_header_links($ids,$stata);
        self::$context = [
            'technologyType'   => '-1',
            'active_type'      => '',
            'app_style_type'   => 'app',
            'header_links'     => $_header_links,
            'header_level_id'  => $ids,
            'page_status'      => $stata,
            'bodyClass'        => '',
            'partners'         => DsPartners::all(),
            'topArticles'      => $this->top_articles_payload()
        ];  
    }

    private function top_articles_payload()
    {
        $model = new DsCategory('','','top stories');
        $catId = DsCategory::fetch(':categoryName {1}',$model)->categoryId[0]['categoryId'];

        $model = new DsCategoryPage('',$catId);
        $pageIds = DsCategoryPage::fetch(':categoryId',$model)->pageId;
        return array_reverse($this->getPages($pageIds),true);
    }

    private function getPages($pageIds)
    {
        $pages = [];
        foreach($pageIds as $pageId) {
            $page = DsPage::get($pageId['pageId']);
            if($page->published <> 'Y') continue;
            array_push($pages,[
                'id'         => $pageId['pageId'],
                'title'      => $page->title,
                'content'    => str_replace('&gt;','>',str_replace('&lt;','<',$page->content)),
                'imageName'  => $this->getPageImage($pageId['pageId'])
            ]);       
        }
        return $pages;
    }

    private function getPageImage($pageId)
    {
        $model = new DsPageImages('',$pageId);
        $md    = DsPageImages::fetch(':pageId {1}',$model);
        $imageName = "";
        if(isset($md->imageName[0]['imageName'])) 
            $imageName = $md->imageName[0]['imageName'];
        return $imageName;
    }

    private function get_header_links(array &$ids = [], array &$stata = [])
    {
        $_header_links = DsCategory::all();
        return $this->get_headers($_header_links,$ids,$stata);
    }

    private function get_headers($_header_links,array &$ids = [], array &$stata = [])
    {
        $nav_links = [];
        
        foreach($_header_links as $link) {
            if($link['visible'] === 'Y') {
                $stata[$link['categoryName']] = $link['_status'] === '1'? 'active' : 'inactive';
                
                $category_level = DsCategoryLevel::fetch(':categoryId', new DsCategoryLevel('','',$link['categoryId']));
                if(isset($category_level->levelName[0])) {
                    $nav_links[$link['categoryName']] = $this->set_sub_categoriesNames($category_level->levelName);
                    $ids[$link['categoryName']] = []; 
                    $this->set_sub_categoriesId($ids[$link['categoryName']],$category_level->levelId); 
                } else {
                    $nav_links[$link['categoryName']] = [];
                }            
            }
        }
        return $nav_links;
    }

    private function set_sub_categoriesNames($levelNames)
    {
        $names = [];

        foreach($levelNames as $levelName) {
            $names[] = $levelName['levelName'];
        }
        return $names;
    }

    private function set_sub_categoriesId(array &$ids,$levelIds)
    {
        foreach($levelIds as $levelId) {
            $ids[]   = $levelId['levelId']; 
        }
    }
}