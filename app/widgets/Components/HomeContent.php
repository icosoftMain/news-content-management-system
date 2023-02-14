<?php namespace App\Widgets\Components;

use App\Models\ilapi_pro_cms\DS\
{
    DsCategory,
    DsCategoryLevel,
    DsCategoryPage,
    DsPageImages,
    DsPage,
    DsSubCategoryPage
};
use FLY\DOM\{ Application, FML, Build};
use FLY\Libs\Event;

class HomeContent extends Application implements FML {

    private $pageIsSub = false;

    private $pageCategoryName = "";

    private $pageSubCategoryId = "";

    private $contentType = "home";

    public function __construct($contentType="home")
    {
        parent::__construct();
        $this->contentType = $contentType;
    }

    private function getHomeContent()
    {
        $contentTags = [];
        $pages = (
            $this->contentType==="search" 
            ? Event::emit('on_pageSearchRequest') 
            : DsPage::all()
        );
        $breakingNews = array_reverse($this->getNews($pages,'breaking',3),true);
        $defaultNews  = array_reverse($this->getNews($pages,'default', is_empty($breakingNews) ? 6 : 3),true);
        
        // Tag Breaking news 
        $this->fetchTags($breakingNews,$contentTags);
        $this->fetchTags($defaultNews,$contentTags);
        
        return $contentTags;
    }

    private function fetchTags(array $newsType, array &$contentTags)
    {
        foreach($newsType as $news) {
            if($news['published'] <> 'Y') continue;
            array_push(
                $contentTags,
                $this->contentTags(
                    $news['pageId'],
                    $news['title'],
                    $news['content'],
                    $news['dateAdded'],
                    $news['pageViews']
                )
            );
        }
        return $contentTags;
    }

    static private function parseContent($str)
    {
        $str     = html_entity_decode($str);
        $str_arr = explode('</p>',$str);
        $payload = [];

        if(count($str_arr) >= 2) {
            $payload[] = $str_arr[0];
            $payload[] = str_word_count($str_arr[1]) <= 100 ? $str_arr[1]: "<p>".word_lmt($str_arr[1],100)."...</p>" ;
            $payload[] = "";
        } else {
            $payload[] = $str_arr[0];
            $payload[] = "";
        }
        $str = implode('</p>',$payload);

        return $str;
    }

    private function contentTags($pageId,$title,$content,$dateAdded,$pageViews)
    {
        $pageName = $this->searchPageCategory($pageId);
        $date = dateQuery($dateAdded,'D, d-M-Y');
        $time = dateQuery($dateAdded,'h:i A');
        $time = !empty($time) ? $time : 'not specified';
        
        return [
            $this->tag('soci')([
                'class' => 'soci',
                'child' => $this->tag('ul')([
                    'children' => [
                        $this->tag('li')([
                            'child' => $this->tag('a')([
                                'class' => 'facebook-1',
                                'href'  => 'https://twitter.com/ILAPI_Ghana'
                            ])
                        ]),
                        $this->tag('li')([
                            'child' => $this->tag('a')([
                                'class' => 'facebook-1 chrome',
                                'href'  => 'https://www.facebook.com/ilapighana'
                            ])
                        ]),
                        $this->tag('li')([
                            'child' => $this->tag('a')([
                                'href'  => '#!',
                                'child' => $this->tag('i')([
                                    'class' => 'glyphicon glyphicon-envelope'
                                ])
                            ])
                        ])
                    ]
                ])
            ]),
            $this->tag('div')([
                'class'    => 'tc-ch',
                'children' => [
                    $this->tag('div')([
                        'class' => 'tch-img',
                        'child' => $this->tag('a')([
                            'href'  => url(":reader?search={$pageId}&title={$title}"),
                            'child' => $this->tag('img')([
                                'class' => 'img-responsive',
                                'alt'   => $title,
                                'src'   => statics("images/pages/{$this->searchPageImage($pageId)}")
                            ])
                        ])
                    ]),
                    
                    $this->tag('a')([
                        'href'  => (
                            !$this->pageIsSub ?(
                                !is_int(strpos(url(":{$pageName}"),":{$pageName}")) 
                                ? url(":{$pageName}") : "#!"  
                            ): url(":subPages?type={$this->pageSubCategoryId}")
                         ) ,
                        'class' => 'blog oren',
                        'text'  => preg_replace('/([&]\s+)Mis/','$1MIS',ucwords($pageName))
                    ]),
                    $this->tag('h3')([
                        'child' => $this->tag('a')([
                            'href' => url(":reader?search={$pageId}&title={$title}"),
                            'text' => $title
                        ])
                    ]),
                    $this->parseContent($content),
                    $this->tag('a')([
                        'href' => url(":reader?search={$pageId}&title={$title}"),
                        'text' => 'read more'
                    ]),
                    $this->tag('div')([
                        'class' => 'blog-poast-info',
                        'child' => $this->tag('ul')([
                                'children' => [
                                    $this->tag('li')([
                                        'children' => [
                                            $this->tag('i')([
                                                'class' => 'glyphicon glyphicon-calendar'
                                            ]),
                                            "{$date} at {$time}"
                                        ]
                                    ]),
                                    $this->tag('li')([
                                        'children' => [
                                            $this->tag('i')([
                                            'class' => 'glyphicon glyphicon-eye-open'
                                            ]),
                                            "{$pageViews} views"
                                        ]
                                    ])
                                ]
                        ])
                    ])
                ]
            ])
        ];
    }

    private function searchPageCategory($pageId)
    {
        $subName = $this->getSubCategoryName($pageId);
        $catName = $this->getCategoryName($pageId);

        if($catName <> false) return $catName;
        $this->pageIsSub = true;
        return $subName;
    }

    private function getSubCategoryName($pageId)
    {
        $model = new DsSubCategoryPage('','',$pageId);
        $mdl = DsSubCategoryPage::fetch(':pageId',$model);
        if(!isset($mdl->subCategoryId[0]['subCategoryId'])) return false;
        $subCatName = DsCategoryLevel::get($mdl->subCategoryId[0]['subCategoryId']);
        $this->pageCategoryName = DsCategory::get($subCatName->categoryId)->categoryName;
        $this->pageSubCategoryId = $mdl->subCategoryId[0]['subCategoryId'];
        return $this->pageCategoryName;
    }

    private function getCategoryName($pageId)
    {
        $model = new DsCategoryPage('','',$pageId);
        $mdl = DsCategoryPage::fetch(':pageId',$model);
        if(!isset($mdl->categoryId[0]['categoryId'])) return false;
        $categoryName = DsCategory::get($mdl->categoryId[0]['categoryId'])->categoryName;
        return $categoryName;
    }

    private function searchPageImage($pageId)
    {
        $md = new DsPageImages('',$pageId);
        $mdl = DsPageImages::fetch(':pageId {1}',$md);
        if(isset($mdl->imageName[0]['imageName'])) {
            return $mdl->imageName[0]['imageName'];
        }
        return "";
    }

    private function getNews(array $pages, string $type, int $range)
    {
        $data = [];
        $counter = 0;
        foreach($pages as $page) {
            if($counter === $range) break;
            if($page['pageType'] === $type) {
                array_push($data,$page);
                ++$counter;
            }
            continue;
        }
        return $data;
    }
    
    public function render(): Build 
    {
        $contents = $this->getHomeContent();
        array_push($contents,$this->tag('div')(['class' => 'clearfix']));
        array_push($contents,
            $this->tag('div')([
                'class'    => 'youtube_channel',
                'children' => [
                    $this->tag('h3')([
                        'text' => 'ILAPI TV'
                    ]),
                    $this->tag('p')([
                        'text' => 'Embedded Video'
                    ])
                ]
            ])
        );

        return new Build(null,[
            $this->tag('wv-comp.pages-content')([
                'children' => [
                    $this->tag('div')([
                        'class'    => 'col-md-9 technology-left',
                        'children' => $contents
                    ]),
                    $this->tag('div')([
                        'class' => 'col-md-3 technology-right-1',
                        'child' => new SideContent
                    ])
                ]
            ]),
            $this->tag('wv-comp.footer')([])
        ]);
    }
}