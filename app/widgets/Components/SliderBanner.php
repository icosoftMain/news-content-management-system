<?php namespace App\Widgets\Components;
use FLY\DOM\{ Application, FML, Build};

class SliderBanner extends Application implements FML {

    private $imageName;

    private $bannerTitle;

    private $bannerText;

    private $readMoreLink;

    public function __construct($imageName,$bannerTitle,$bannerText,$readMoreLink = '#!')
    {
        parent::__construct();    
        $this->imageName    = $imageName;
        $this->bannerTitle  = $bannerTitle;
        $this->bannerText   = $bannerText;
        $this->readMoreLink = $readMoreLink;
    }

    public function render(): Build
    {
        $style = [
            'background'              => 'url('.statics('images/'.$this->imageName).') no-repeat 0px 0px',
            'background-size'         => 'cover',
            '-webkit-background-size' => 'cover',
            '-o-background-size'      => 'cover',
            '-ms-background-size'     => 'cover',
            '-moz-background-size'    => 'cover',
            'min-height'              => '550px'
        ];
        return new Build(null, [
            $this->tag('div')([
                'class' => 'item',
                'child' =>  $this->tag('div')([
                    'style' => $style,
                    'class' => 'banner',
                    'child' => $this->tag('div')([
                        'class'    => 'container',
                        'children' => [
                            $this->tag('h2')([
                                'text' => $this->bannerTitle
                            ]),
                            $this->tag('p')([
                                'text' => $this->bannerText
                            ]),
                            (
                                function() {
                                    if(is_empty($this->bannerText) && is_empty($this->readMoreLink)) return '';
                                    return $this->tag('a')([
                                        'href' => url($this->readMoreLink),
                                        'text' => 'READ MORE'
                                    ]);
                                }
                            )()                            
                        ]
                    ])
                ])
            ])
        ]);
    }
}
    