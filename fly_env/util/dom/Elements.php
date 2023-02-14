<?php namespace FLY\DOM;


class Elements {
    private static $elementList;

    public static function get()
    {
        self::$elementList = [
            'division'          => 'div',
            'tablerow'          => 'tr',
            'tablecolumn'       => 'td',
            'tablehead'         => 'thead',
            'tablebody'         => 'tbody',
            'list'              => 'li',
            'icon'              => 'i',
            'anchor'            => 'a',
            'image'             => 'img',
            'Navigation'        => 'nav',
            'unorderedlist'     => 'ul',
            'orderedlist'       => 'ol',
            'paragraph'         => 'p',
            'descriptiondetail' => 'dd',
            'descriptionlist'   => 'dl',
            'descriptionterm'   => 'dt',
            'horizontalrule'    => 'hr',
            'optiongroup'       => 'optgroup',
            'quotation'         => 'q',
            'strike'            => 's',
            'wordbreak'         => 'wbr',
            'break'             => 'br',
            'tablefoot'         => 'tfoot',
            'preformat'         => 'pre',
            'subscript'         => 'sub',
            'superscript'       => 'sup',
            'emphasis'          => 'em',
            'definition'        => 'dfn',
            'delete'            => 'del',
            'horizontalrule'    => 'hr',
            'bold'              => 'b',
            'abbreviation'      => 'abbr',
            'headerone'         => 'h1',
            'headertwo'         => 'h2',
            'headerthree'       => 'h3',
            'headerfour'        => 'h4',
            'headerfive'        => 'h5',
            'headersix'         => 'h6'
        ];
        return self::$elementList;
    }
}
