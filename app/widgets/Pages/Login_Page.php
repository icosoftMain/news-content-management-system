<?php namespace App\Widgets\Pages;
use FLY\DOM\{ Application, FML, Build };

class Login_Page extends Application implements FML {

    public function __construct()
    {
        parent::__construct($root_tag = 'wv-index');    
    }
    
    public function render(): Build
    {
        return new Build($this, [
            $this->tag('wv-comp.admin-login')([])
        ]);
    }
}