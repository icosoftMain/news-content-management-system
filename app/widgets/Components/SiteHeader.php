<?php namespace App\Widgets\Components;
use FLY\DOM\{ Application, FML, Build};

class SiteHeader extends Application implements FML {
    
    public function render(): Build
    {
        return new Build(null,[
            $this->tag('div')([
                'class'    => 'header',
                'children' => [
                    $this->tag('div')([
                        'class' => 'header-top',
                        'child' => $this->tag('div')([
                            'class'    => 'container',
                            'children' => [
                                $this->tag('div')([
                                    'class' => 'logo',
                                    'child' => $this->tag('a')([
                                        'href'  => url(':home'),
                                        'child' => $this->tag('img')([
                                            'src' => statics('images/ilapi_logo.jpg'),
                                            'alt' => 'ILAPI LOGO'
                                        ])
                                    ])
                                ]), // end logo
                                $this->tag('div')([
                                    'class' => 'search',
                                    'child' => $this->tag('form')([
                                        'action' => url(':atSearch'),
                                        'name'   => 'search_box',
                                        'method' => 'get',
                                        'class'  => 'searchform',
                                        'children' => [
                                            $this->tag('input')([
                                                'type' => 'text',
                                                'name' => 'q',
                                                'value' => '',
                                                'placeholder' => 'Search Articles'
                                            ]), // end search input
                                            $this->tag('input')([
                                                'type' => 'submit',
                                                'name' => 'search-btn',
                                                'id'   => 'search-btn',
                                                'value' => ''
                                            ]), // end search button
                                        ]
                                    ]) // end form
                                ]),  // end search
                                $this->tag('div')([
                                    'class' => 'social',
                                    'child' => $this->tag('ul')([
                                        'children' => \array_map(function($class, $link) {
                                            return $this->tag('li')([
                                                'child' => $this->tag('a')([
                                                    'class' => $class,
                                                    'href'  => $link
                                                ])
                                            ]);
                                        },[
                                            'facebook','facebook twitter'
                                        ],[
                                            'https://www.facebook.com/ilapighana',
                                            'https://www.twitter.com/ILAPI_GHANA'
                                        ])
                                    ])
                                ]), // end social links
                                $this->tag('div')([
                                    'class' => 'topright-links',
                                    'child' => $this->tag('ul')([
                                            'children' => array_map(function($link, $value){
                                                if($link === ':ajoet') 
                                                    return $this->tag('li')([
                                                        'child' => $this->tag('a')([
                                                            'href'   => url($link),
                                                            'target' => '_blank',
                                                            'text'   => $value
                                                        ])
                                                    ]);
                                                return $this->tag('li')([
                                                    'child' => $this->tag('a')([
                                                        'href' => url($link),
                                                        'text' => $value
                                                    ])
                                                ]);
                                            }, [':ajoet',':press',':donate',':contact'],[
                                                'Ajoet','Press Release','Donate','Contact Us'
                                            ])
                                        ])
                                    
                                ]), // end target links
                                $this->tag('div')(['class' => 'clearfix'])
                            ]
                        ])
                    ]),
                    $this->tag('div')([
                        'class' => 'head-bottom',
                        'child' => $this->tag('div')([
                            'class' => 'container',
                            'children' => [ 
                                $this->tag('div')([
                                    'class' => 'navbar-header',
                                    'child' => $this->tag('button')([
                                        'type' => 'button',
                                        'class' => 'navbar-toggle collapsed',
                                        'data-toggle' => 'collapse',
                                        'data-target' => '#navbar',
                                        'aria-expanded' => 'false',
                                        'aria-controls' => 'navbar',
                                        'children' => [
                                            $this->tag('span')([
                                                'class' => 'sr-only',
                                                'text'  => 'Toggle navigation'
                                            ]),
                                            $this->tag('span')([
                                                'class' => 'icon-bar'
                                            ]),
                                            $this->tag('span')([
                                                'class' => 'icon-bar'
                                            ]),
                                            $this->tag('span')([
                                                'class' => 'icon-bar'
                                            ])
                                        ]
                                    ])
                                ]), // end nav-bar header
                                $this->tag('div')([
                                    'id' => 'navbar',
                                    'class' => 'navbar-collapse collapse',
                                    'child' => $this->tag('wv-comp.site-navlinks')([
                                        'homelink' => url(':home')
                                    ])
                                ]),// end nav bar
                            ]
                        ])
                    ])
                ]
            ])
        ]);
    }
}