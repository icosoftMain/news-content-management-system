<wv-admin-main crumpTitle={Page Details} crumpLink={:manage_pages} crumpSlashTitle={/&nbsp;Page Details}>
    <wv-comp.form-alert Type={$alertType} Text={{#$alertText#}}/>
    <wv-comp.admin-white-box>
        <div class="table-responsive">
            <div class="col-md-6">
                <a href="{# url(':add_page') #}" ><i class="fas fa-plus"></i> Page</a>&nbsp;&nbsp;
                <a href="{# url(':manage_categories') #}" ><i class="fas fa-list"></i> Category List</a>
            </div>  
            <div class="col-md-6">
                <wv-comp.searchInput model={$pages}/>
            </div>
            <div id="page_details">
                <wv-comp.tables.pages/>
            </div>
            @if $pages['pagLim'] > 1:
                <wv-comp.pagination>
                    @for $index = 0; $index < $pages['pagLim']; $index++:
                        <li class="page-item{# ($index + 1) === 1? ' active':''#} pagination-item">
                            <span class="page-link pag-numbers">{# $index+1 #}</span>
                        </li>
                    @endfor
                </wv-comp.pagination>
            @endif
        </div>   
    </wv-comp.admin-white-box>
</wv-admin-main>
