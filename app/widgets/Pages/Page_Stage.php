<?php namespace App\Widgets\Pages;

use App\Widgets\Components\{
    PagesContent,
    SiteHeader
};
use FLY\DOM\{ Application, FML, Build};
use FLY\Libs\Event;

class Page_Stage extends Application implements FML {

    private $pageTitle     = "";

    private $pageImage     = "";

    private $pageContent   = "";

    private $pageSource    = "";

    private $modelType     = "";

    private $id            = "";

    private $eventLocation = "";
    
    public function __construct()
    {
        parent::__construct($root_tag = 'wv-index'); 
        $this->setPageModel();
    }

    private function setPageModel()
    {
        $models = Event::emit('on_pageRequest');
        $this->modelType = $models['type'];
        switch($models['type']) {
            case 'events':
                $this->id            = $models['page']->eventId;
                $this->pageTitle     = $models['page']->eventName;
                $this->pageContent   = $models['page']->_description;
                $this->eventLocation = $models['page']->_location;
                $this->pageImage     = "events/{$models['page']->eventPoster}";
            break;
            default:
                $this->pageTitle   = $models['page']->title;
                $this->pageContent = $models['page']->content;
                $this->pageSource  = $models['page']->source;
                $this->pageImage   = "pages/{$models['pageImage']->imageName[0]['imageName']}";
            break;
        }
    }

    private function scheduleModal()
    {
        return $this->tag('wv-comp.staticModal')([
            'TargetId' => $this->pageTitle,
            'Title'    => " <fml_fragment>
                                <i class='fa fa-calendar'></i><i class='fa fa-edit'></i>
                                Schedule(s)
                            </fml_fragment>
            ",
            'child' => $this->tag('wv-comp.tables.event-schedule')([
                'id'        => '',
                'type'      => 'viewer',
                'eventId'   => "'{$this->id}'",
                'eventName' => $this->pageTitle
            ])
        ]);
    }

    private function hasSchedules()
    {
        return  $this->tag('div')([
            'class' => 'blog-poast-info',
            'child' => $this->tag('ul')([
                    'children' => [
                        $this->tag('li')([
                            'child' => $this->tag('a')([
                                'href'        => '#!',
                                'data-toggle' => 'modal',
                                'data-target' => "#{$this->pageTitle}",
                                'children' => [
                                    $this->tag('i')([
                                        'class' => 'glyphicon glyphicon-calendar'
                                    ]),
                                    "View Schedules"
                                ]
                            ])
                        ]),
                        $this->tag('li')([
                            'child' => $this->tag('a')([
                                'href' => url(":speakers?s={$this->id}"),
                                'children' => [
                                     $this->tag('i')([
                                        'class' => 'glyphicon glyphicon-bullhorn'
                                    ]),
                                    "View Speakers"
                                ]
                            ])
                        ]),
                        $this->tag('li')([
                            'child' => $this->tag('div')([
                                'title'    => 'location',
                                'children' => [
                                     $this->tag('i')([
                                        'class' => 'fa fa-map x2',
                                        'style' => ['color' => '#aeaeae']
                                    ]),
                                    "&nbsp;{$this->eventLocation}"
                                ]
                            ])
                        ])
                    ]
            ])
        ]);
    }

    public function render(): Build
    {
        $css = [
            'text-align'  => 'justify',
            'line-height' => '1.8em !important'
        ];
        return new Build($this, [
            new SiteHeader,
            new PagesContent([
                'child' => $this->tag('div')([
                    'class' => 'vide-1',
                    'child' => $this->tag('div')([
                        'class' => 'blog-grid2',
                        'children' => [
                            $this->tag('img')([
                                'src'   => statics("images/{$this->pageImage}"),
                                'class' => 'img-responsive'
                            ]),
                            $this->tag('div')([
                                'class'    => 'blog-text',
                                'children' => [
                                    $this->tag('h5')([
                                        'text' => $this->pageTitle
                                    ]),
                                    $this->tag('div')([
                                        'style' => $css,
                                        'text'  => $this->pageContent,
                                    ]),
                                    $this->tag('br'),
                                    $this->tag('p')([
                                        'child' => $this->tag('strong')([
                                            'text' => !is_empty($this->pageSource) ? "Source: {$this->pageSource}" : ""
                                        ])
                                    ]),
                                    $this->modelType === 'events' ? [ $this->hasSchedules(), $this->scheduleModal() ] : ""
                                ]
                            ])
                        ]
                    ])
                ])    
            ])
        ]);
    }
}