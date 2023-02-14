<wv-admin-main crumpTitle={Event Details} crumpLink={:manage_events} crumpSlashTitle={/&nbsp;Event Details}>
    <wv-comp.form-alert Type={$alertType} Text={{#$alertText#}}/>
    <wv-comp.admin-white-box>
        <div class="table-responsive">
            <div class="col-md-6">
                <a href="{# url(':add_event') #}" ><i class="fas fa-plus"></i> Event</a>&nbsp;&nbsp;
            </div>
            <div class="col-md-6">
                <wv-comp.searchInput model={$events}/>
            </div>
            <div id="page_details">
                <wv-comp.tables.events />
            </div>
            @if $events['pagLim'] > 1:
                <wv-comp.pagination>
                    @for $index = 0; $index < $events['pagLim']; $index++:
                        <li class="page-item{# ($index + 1) === 1? ' active':''#} pagination-item">
                            <span class="page-link pag-numbers">{# $index+1 #}</span>
                        </li>
                    @endfor
                </wv-comp.pagination>
            @endif
        </div>   
    </wv-comp.admin-white-box>
</wv-admin-main>
