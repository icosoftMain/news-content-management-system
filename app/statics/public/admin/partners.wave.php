<wv-admin-main crumpTitle={Partner Details} crumpLink={:manage_partners} crumpSlashTitle={/&nbsp;Partner Details}>
    <wv-comp.form-alert Type={$alertType} Text={{#$alertText#}}/>
    <wv-comp.admin-white-box>
        <div class="table-responsive">
            <div class="col-md-6">
                <a href="{# url(':add_partner') #}" ><i class="fas fa-plus"></i> Partner</a>&nbsp;&nbsp;
            </div>  
            <div class="col-md-6">
                <wv-comp.searchInput model={$partners}/>
            </div>
            <div id="page_details">
                <wv-comp.tables.partners/>
            </div>
            @if $partners['pagLim'] > 1:
                <wv-comp.pagination>
                    @for $index = 0; $index < $partners['pagLim']; $index++:            
                        <li class="page-item{# ($index + 1) === 1? ' active':''#} pagination-item">
                            <span class="page-link pag-numbers">{# $index+1 #}</span>
                        </li>
                    @endfor
                </wv-comp.pagination>
            @endif
        </div>   
    </wv-comp.admin-white-box>
</wv-admin-main>