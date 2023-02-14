@def nav_links($header_links, $active_type,$levelId,$page_status):
   {! $cnt = 0 !}
    @each $header_links as $category => $sub_categories:
        @if !empty($sub_categories) && $page_status[$category] === 'active':
            <li class="dropdown">
                <a href="#!" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    {# $category #} <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    @each $sub_categories as $subcategory:
                        <li>
                            <a href="@url(':ward?index='.$levelId[$category][$cnt++].'&title='.$subcategory)">
                                {# $subcategory #}
                            </a>
                        </li>
                    @endeach
                </ul>
            </li>
            @else:
              <li {# $active_type === strtolower($category) ? ' class="active"': '' #}><a href="{# $page_status[$category] === 'active' ? url(strtolower(':'.$category)): '#!' #}">{# $category #}</a></li>
        @endif
        {! $cnt = 0 !}
    @endeach 
@endef

<ul class="nav navbar-nav">
    <li {# $active_type === 'home' ? ' class="active"': '' #}><a href={:str.homelink}>Home</a></li>
    {# nav_links($header_links, $active_type, $header_level_id,$page_status) #}
</ul>