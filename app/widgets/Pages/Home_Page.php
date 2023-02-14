<?php namespace App\Widgets\Pages;
use App\Widgets\Components\{HomeContent,SiteHeader};
use FLY\DOM\{Application,FML,Build};
use FLY\Libs\Event;

class Home_Page extends Application implements FML {

    public function __construct()
    {
        parent::__construct($root_tag = 'wv-index');    
    }
    
    public function render(): Build
    {
        return new Build($this, [
            new SiteHeader,
            $this->tag('div')([
                'class'    => 'owl-carousel owl-theme',
                'children' => Event::emit('on_load_sliderBanner')
            ]),
            new HomeContent
        ]);
    }

    
}