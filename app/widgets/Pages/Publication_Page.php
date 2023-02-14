<?php namespace App\Widgets\Pages;

use App\Widgets\Components\CategoryContent;
use FLY\DOM\{ Application, FML, Build };

class Publication_Page extends Application implements FML {

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