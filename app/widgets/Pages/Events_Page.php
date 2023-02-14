<?php namespace App\Widgets\Pages;
use FLY\DOM\{ Application, FML, Build };
use App\Widgets\Components\{CategoryContent};

class Events_Page extends Application implements FML {

    public function __construct()
    {
        parent::__construct($root_tag = 'wv-index');    
    }
    
    public function render(): Build
    {
        return new Build($this, [
            new CategoryContent(
                [
                    $this->tag('h2')([
                        'style' => [
                            'margin'      => '0rem auto',
                            'padding'     => '1rem .5rem',
                            'text-align'  => 'center',
                            'color'       => '#2a9bfa',
                            'font-weight' => '800'
                        ],
                        'text' => 'UPCOMING EVENTS'
                    ]),
                    $this->tag('hr')
                ],
                false,
                'events'
            )
        ]);
    }
}