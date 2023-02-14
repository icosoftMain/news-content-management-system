<div class="blo-top">
    <div class="tech-btm">
        <h4>{# $headLink #}</h4>
        @each $links as $lnk:
            @if $lnk['lType'] === 'filed':
                <p style="font-weight:bold"><a target="_blank" href="@statics('docs/'.$lnk['item'])">{# $lnk['levelName'] #}</a></p>
            @else:
                <p style="font-weight:bold"><a target="_blank" href="{# $lnk['item'] #}">{# $lnk['levelName'] #}</a></p>
            @endif
        @endeach
	<div class="clearfix"> </div>
   </div>
</div>