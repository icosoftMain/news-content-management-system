<?php namespace App\Widgets\Components;

use App\Controllers\Events\Schedules;
use App\Controllers\Packs\Filters;
use App\Controllers\Packs\Pages;
use App\Models\ilapi_pro_cms\DS\DsEvent;
use App\Models\ilapi_pro_cms\DS\DsPage;
use App\Models\ilapi_pro_cms\DS\DsPageImages;
use FLY\DOM\{ Application, FML, Build };
use App\Widgets\Components\{ SiteHeader, PagesContent };
use FLY\Libs\Event;
use FLY\Libs\Request;
use FLY\MVC\Model;
use FLY\Routers\Redirect;

class CategoryContent extends Application implements FML {

    private $contentHeader;

    private $isSub = false;

    private $pageType = "";

    private $pagData = [];

    private $lastPagNum = 1;

    private $contentLim = 15;

    public function __construct($contentHeader = "", $isSub = false, $pageType="pages")
    {
        parent::__construct();    
        $this->contentHeader = $contentHeader;
        $this->isSub = $isSub;
        $this->pageType = $pageType;
    }

    private function setQuery($oldstring,$newstring)
    {
        if(Request::hasQuery() && $this->isSub) {
            $requestQuery = "?". (
                Request::hasQuery()
                ? str_replace($oldstring,"",Request::query())
                : ""
            );
            return "{$requestQuery}&{$newstring}";
        }
        return "?".$newstring;
    }

    private function getContent()
    {
        $data = [];
        switch($this->pageType) {
            case 'events': case 'ajoet':
                $data = $this->eventContent();
            break;
            case 'speakers':
                $data = $this->speakersContent();
            break;
            default:
                $data = $this->defaultContents();
            break;            
        }
        return $data;
    }

    private function defaultContents()
    {
        $data = [];

        $pageIds = !$this->isSub ? (Event::emit('on_categoryRequests')??[]) : Event::emit('on_subCategoryRequests');
        $this->pagData = $pageIds;
        $pageIds = (
            !Request::exists('navi') ? array_reverse($pageIds,true)
            : (
                ($pageIndex = (int)Request::get('navi')) > 1 ? Filters::paginate($pageIndex, ($this->contentLim + 1),$pageIds,'true')
                : array_reverse($pageIds,true)
            )
        );
        $countLimit = 0;
        foreach($pageIds as $pageId) {
            if($countLimit === $this->contentLim) break;
            $page = DsPage::get($pageId['pageId']);
            if($page->published <> 'Y') continue;
            $imageName = (
                DsPageImages::fetch(':pageId{1}',new DsPageImages('',$pageId['pageId']))
            )->imageName[0]['imageName'] ?? ''; 
            $this->contents($data,$pageId['pageId'],$page->title,$imageName,$page->content);
            ++$countLimit;
        }
        return $data;
    }

    private function eventContent()
    {
        $data = [];

        $events = $this->eventIsAJOET(); 
        $this->pagData = $events;
        $events =(
            !Request::exists('navi') ? array_reverse($events,true)
            : (
                ($pageIndex = (int)Request::get('navi')) > 1 ? Filters::paginate($pageIndex, ($this->contentLim + 1),$events,'true')
                : array_reverse($events,true)
            )
        );
        $countLimit = 0;
        foreach($events as $event) {
            if($countLimit === $this->contentLim) break;
            $page = DsEvent::get($event['eventId']);
            if($page->published <> 'Y') continue;
            $this->contents($data,$event['eventId'],$page->eventName,$page->eventPoster,$page->_description);
            ++$countLimit;
        }
        return $data;
    }

    private function speakersContent()
    {
        $data = [];
        $eventId = Event::emit("on_speakerRequest")?? NULL; 
        $speakers   = Model::query("CALL get_event_speakers('{$eventId}')");
        $this->pageData = $speakers;

        $speakers =(
            !Request::exists('navi') ? array_reverse($speakers,true)
            : (
                ($pageIndex = (int)Request::get('navi')) > 1 ? Filters::paginate($pageIndex, ($this->contentLim + 1),$speakers,'true')
                : array_reverse($speakers,true)
            )
        );
        $countLimit = 0;
        foreach($speakers as $sp) {
            if($countLimit === $this->contentLim) break;
            $fullName = "{$sp['title']} {$sp['firstName']} {$sp['lastName']}";
            $this->contents($data,$sp['speakerId'],$fullName,$sp['imageName'],$sp['about']);
            $test = json_encode(Schedules::getCurrAssignedSchedules($sp['speakerId']));
            array_push($data,$this->speakerSchedules($sp['speakerId'],$sp['firstName']));
            ++$countLimit;
        }
        
        return $data;
    }

    private function speakerSchedules($modalTarget,$firstName)
    {
        return  [
            $this->tag('div')([
                'class' => 'blog-poast-info',
                'child' => $this->tag('ul')([
                    'child' => $this->tag('li')([
                        'child' => $this->tag('a')([
                            'href'        => '#!',
                            'data-toggle' => 'modal',
                            'data-target' => "#{$modalTarget}",
                            'children' => [
                                $this->tag('i')([
                                    'class' => 'glyphicon glyphicon-calendar'
                                ]),
                                "View Schedules"
                            ]
                        ])
                    ])
                ])
            ]),
            $this->tag('br'),
            $this->tag('br'),
            $this->speakerModals($modalTarget,$firstName)
        ];
        
    }

    private function speakerModals($targetId,$firstName)
    {
        return $this->tag('wv-comp.staticModal')([
            'TargetId' => $targetId,
            'Title'    => " <fml_fragment>
                                <i class='fa fa-calendar'></i><i class='fa fa-edit'></i>
                                {$firstName}'s Schedule(s)
                            </fml_fragment>
            ",
            'children'    => $this->generateSchedules($targetId)
        ]);
    }

    private function generateSchedules($targetId)
    {
        $data = [];
        $availableScheds = Schedules::getCurrAssignedSchedules($targetId);
        foreach($availableScheds as $eventName => $scheds) {
            array_push($data,$this->tag('wv-comp.tables.speaker-schedules')([
                'eventName'  => "'{$eventName}'",
                'eventTotal' => count($scheds),
                'scheds'     => $scheds,
                'type'       => 'viewer',
                'spSchedId'  => '',
                'actionType' => ''
            ]));
        }
        return $data;
    }
    
    private function eventIsAJOET()
    {
        $events = DsEvent::all();
        if($this->pageType === 'ajoet') {
            $ajevent = [];
            foreach($events as $evt) {
                if($evt['eventType'] === 'ajoet') array_push($ajevent,$evt);
            }
            $events = $ajevent;
        }
        return $events;
    }

    static private function parseContent($str)
    {
        $str     = html_entity_decode($str);
        $str_arr = explode('</p>',$str);
        $payload = [];
        if(count($str_arr) >= 2) {
            $word = is_empty($str_arr[1]) ? $str_arr[0] : $str_arr[1];
            $payload[] = str_word_count($word) <= 35 ? $word: "<p>".word_lmt($word,35)."...</p>" ;
            $payload[] = "";
        } else {
            $payload[] = $str_arr[0];
            $payload[] = "";
        }
        $str = implode('</p>',$payload);
        return $str;
    }
    
    private function contents(array &$data,$pageId,$title,$imageName,$content)
    {
        $pageType = ($this->pageType==='ajoet'    ? 'events'       : $this->pageType);
        $pageType = ($this->pageType==='speakers' ? 'speakers_pics': $pageType);
        array_push(
            $data,
            $this->tag('div')([
                'class'    => 'rev',
                'children' => [
                    $this->tag('div')([
                        'class' => 'rev-img',
                        'child' => $this->tag('a')([
                            'href'  => $this->pageType <> 'speakers' ? url(":reader?search={$pageId}&title={$title}&type={$pageType}"): '#!',
                            'child' => $this->tag('img')([
                                'class' => 'img-responsive',
                                'src'   => statics("images/{$pageType}/{$imageName}"),
                                'alt'   => ''
                            ])
                        ])
                    ]),
                    $this->tag('div')([
                        'class' => 'rev-info',
                        'children' => [
                            $this->tag('h3')([
                                'child' => $this->tag('a')([
                                    'href' => $this->pageType <> 'speakers' ? url(":reader?search={$pageId}&title={$title}&type={$pageType}"): '#!',
                                    'text' => $title
                                ])
                            ]),
                           $this->pageType <> 'speakers' ? [
                                $this->parseContent($content),
                                $this->tag('a')([
                                    'href' => url(":reader?search={$pageId}&title={$title}&type={$pageType}"),
                                    'text' => 'view details'
                                ])
                            ] : $content
                        ]
                    ]),
                    $this->tag('div')([
                        'class' => 'clearfix'
                    ])
                ]
            ])
        );
    }

    private function generatePagination($limit)
    {
        $numOfPagination = Pages::displayLimit($this->pagData, $limit)['pagLim'];
        $this->lastPagNum = $numOfPagination;
        $pags = [];
        if($numOfPagination > 1) {
            for($i = 0; $i < $numOfPagination; $i++) {
                array_push(
                    $pags,
                    $this->pageList(($i + 1))
                );
            }
            return $this->setPags($pags);
        }
        return $pags;
    }

    private function setPags($numberList)
    {
        return $this->tag('div')([
            'style' => ['margin' => '0 auto'],
            'child' => $this->tag('ol')([
                'class'    => 'pagination',
                'children' => [
                    $this->tag('li')([
                        'class' => 'page-item',
                        'child' => $this->tag('a')([
                            'href'  => (
                                !Request::exists('navi') ? url(":{$this->pageType}{$this->setQuery('navi=1','navi=1')}") :
                                (
                                    Request::get('navi') == 1 ? url(":{$this->pageType}?navi=1") 
                                    : url(":{$this->pageType}{$this->setQuery('navi='.Request::get("navi"),'navi='.((int) Request::get("navi") - 1))}")
                                )
                            ),
                            'child' => $this->tag('span')([
                                'class' => 'page-link',
                                'text'  => '« Prev'
                            ])
                        ])
                        
                    ]),
                    $numberList,
                    $this->tag('li')([
                        'class' => 'page-item',
                        'child' => $this->tag('a')([
                            'href'  => (
                                !Request::exists('navi') ? url(":{$this->pageType}{$this->setQuery('navi=2','navi=2')}") :
                                (
                                    Request::get('navi') == $this->lastPagNum ? url(":{$this->pageType}{$this->setQuery("navi={$this->lastPagNum}","navi={$this->lastPagNum}")}") 
                                    : url(":{$this->pageType}{$this->setQuery('navi='.Request::get("navi"),'navi='.((int) Request::get("navi") + 1))}")
                                )
                            ),
                            'child' => $this->tag('span')([
                                'class' => 'page-link',
                                'text'  => 'Next »'
                            ])
                        ])
                    ])
                ]
            ])
        ]);
    }

    private function pageList($num)
    {
        return $this->tag('li')([
            'class' => "page-item". (($num) == (!Request::exists('navi') ? 1 : Request::get('navi')) ? ' active': ''),
            'child' => $this->tag('a')([
                'href'  => url(":{$this->pageType}?navi={$num}"),
                'child' => $this->tag('span')([
                    'class' => 'page-link',
                    'text'  => $num
                ])
            ])
        ]);
    }

    public function render(): Build
    {
       
        $content = $this->getContent();
        $pagination = $this->generatePagination($this->contentLim);
        if(Request::exists('navi')) {
            if(!is_numeric(Request::get('navi')) || (((int) Request::get('navi')) > $this->lastPagNum) || $this->lastPagNum === 1)
                Redirect::to(url(":{$this->pageType}"));
        }
        return new Build($this, [
            new SiteHeader,
            new PagesContent([
                $this->tag('div')([
                    'class'    => 'vide-1',
                    'children' => [
                        $this->contentHeader,
                        count($content) === 0 ? ($this->tag('h1')([
                            'style'=> ['color' => '#777;', 'text-align' => 'center'],
                            'text' => 'Content unavailable'
                        ])): $content,
                        $pagination
                    ]
                ]),
            ])
        ]);
    }
}