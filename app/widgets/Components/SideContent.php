<?php namespace App\Widgets\Components;

use App\Controllers\Events\Schedules;
use App\Models\ilapi_pro_cms\DS\DSMembershipForm;
use App\Views\App;
use FLY\DOM\{ Application, FML, Build};
use FLY\MVC\Model;

class SideContent extends Application implements FML {

    public function setData()
    {
        App::save_context([
            'sideLinks' => Schedules::arrangePayload(Model::query('CALL get_side_links'),'pageName')
        ]);
    }

    public function render(): Build {

        $paragraph_css = [
          'font-weight' => 'bold'
        ];
        $this->setData();
        return new Build(null, [
            $this->tag('div')([
                'class' => 'blo-top',
                'child' => $this->tag('div')([
                    'class' => 'tech-btm',
                    'children' => [
                        $this->tag('img')([
                            'class' => 'img-responsive',
                            'src'   => statics('images/banner-2.jpg'),
                            'alt'   => 'Banner Spot'
                        ]),
                        $this->tag('p')([
                            'style' => $paragraph_css,
                            'text'  => 'ILAPI Won the 2017 Africa Think Tank Shark Tank Competition In South Africa.'
                        ]),
                        $this->tag('p')([
                            'style' => $paragraph_css,
                            'child' => $this->tag('a')([
                                'target' => '_blank',
                                'href' => is_empty((DSMembershipForm::get(1))->formName) ? '#!' : statics('docs/'.(DSMembershipForm::get(1))->formName),
                                'text' => 'ILAPI Membership Form'
                            ])
                        ]),
                    ]
                ])
            ]),
            $this->tag('wv-comp.side-content')
        ]);
    }
}