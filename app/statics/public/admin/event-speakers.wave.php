<wv-admin-main crumpTitle={Speakers} crumpLink={:manage_speakers} crumpSlashTitle={/&nbsp;Speaker}>
    <wv-comp.form-alert Type={$alertType} Text={{#$alertText#}}/>
    <wv-comp.admin-white-box>
        <div class="table-responsive">
            <div class="col-md-6">
                <a href="{# url(':add_speaker') #}" ><i class="fas fa-plus"></i> Speaker</a>&nbsp;&nbsp;
            </div>  
            <div class="col-md-6">
                <wv-comp.searchInput model={$speakers}/>
            </div>
            <div id="page_details">
                <wv-comp.tables.speakers />
            </div>
            @if $speakers['pagLim'] > 1:
                <wv-comp.pagination>
                    @for $index = 0; $index < $speakers['pagLim']; $index++:
                        <li class="page-item{# ($index + 1) === 1? ' active':''#} pagination-item">
                            <span class="page-link pag-numbers">{# $index+1 #}</span>
                        </li>
                    @endfor
                </wv-comp.pagination>
            @endif
        </div> 
    </wv-comp.admin-white-box>
</wv-admin-main>