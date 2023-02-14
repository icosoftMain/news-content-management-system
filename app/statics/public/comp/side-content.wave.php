@if !is_empty($partners):
    <div class="blo-top">
        <div class="tech-btm text-center">
        <h4>Our Partners</h4>
            @each $partners as $partner:
                <wv-comp.partner 
                    webLink={@cdnurl($partner['partWebName'])} 
                    imgLink={@statics("images/partners/{$partner['partLogo']}")} 
                    imgName={{#$partner['partName']#}} 
                />
            @endeach
            <div class="clearfix"></div>
        </div>
    </div>
@endif
@if !is_empty($topArticles):
    <div class="blo-top1">
        <div class="tech-btm">
            <h4>Top articles of the week</h4>
            <div class="owl-carousel top-stories">
                @each $topArticles as $article:
                    <wv-comp.top-articles 
                        topLinks={@url(":reader?search={$article['id']}&title={$article['title']}")} 
                        topStoriesImageLink={@statics("images/pages/{$article['imageName']}")}
                    >
                        {# word_lmt($article['content'],10).'...' #}
                    </wv-comp.top-articles>
                @endeach
            </div>
        </div>
    </div>
@endif

@each $sideLinks as $headLink => $links:
    <wv-comp.blo_top/>
@endeach