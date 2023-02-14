<?php namespace App\Widgets\Pages;
use FLY\DOM\{ Application, FML, Build};
use App\Widgets\Components\{CategoryContent};


class Advocacy_Page extends Application implements FML {

    public function __construct()
    {
        parent::__construct($root_tag = 'wv-index');    
    }
    
    public function render(): Build
    {
        return new Build($this, [
            new CategoryContent
        ]);
    }
}