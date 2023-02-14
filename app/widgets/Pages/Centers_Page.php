<?php namespace App\Widgets\Pages;

use App\Widgets\Components\CategoryContent;
use FLY\DOM\{ Application, FML, Build };
use FLY\Libs\Event;

class Centers_Page extends Application implements FML {

    public function __construct()
    {
        parent::__construct($root_tag = 'wv-index');  
    }
    
    public function render(): Build
    {
        $pageName = Event::emit('get_subCategoryName');
        return new Build($this, [
            new CategoryContent(
                !is_empty($pageName) ? $this->pageHeader("CENTER FOR ".strtoupper($pageName)) : "",
                true
            )
        ]);
    }

    private function pageHeader($text)
    {

        return [
            $this->tag('h2')([
                'style' => [
                    'margin'      => '0rem auto',
                    'padding'     => '1rem .5rem',
                    'text-align'  => 'center',
                    'color'       => '#2a9bfa',
                    'font-weight' => '800'
                ],
                'text' => $text
            ]),
            $this->tag('hr')
        ];
    }
}