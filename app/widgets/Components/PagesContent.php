<?php namespace App\Widgets\Components;
use FLY\DOM\{ Application, FML, Build};

class PagesContent extends Application implements FML {
    
    private $children;

    public function __construct($children = [])
    {
        parent::__construct();
        $this->children = $children;
    }

    public function render(): Build 
    {
        return new Build(null,[
            $this->tag('div')([ 'class' => 'banner1']),
            $this->tag('br'),
            $this->tag('wv-comp.pages-content')([
                'children' => [
                    $this->tag('div')([
                        'class'    => 'col-md-9 technology-left',
                        'children' => $this->children
                    ]),
                    $this->tag('div')([
                        'class' => 'col-md-3 technology-right-1',
                        'child' => new SideContent
                    ])
                ]
            ]),
            $this->tag('wv-comp.footer')([
                'homelink' => url(':home')
            ])
        ]);
    }
}