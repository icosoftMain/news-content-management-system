<wv-admin-main crumpTitle={Category Details} crumpLink={:manage_categories} crumpSlashTitle={/&nbsp;Category Details}>
    <wv-comp.admin-white-box>
        <div class="table-responsive">
            <div class="col-md-6">
                <a href="{# url(':add_category') #}" ><i class="fas fa-plus"></i> Category</a>&nbsp;&nbsp;
                <a href="{# url(':manage_sub_categories') #}" ><i class="fas fa-list"></i> Sub Category List</a>
            </div>  
            <div class="col-md-6">
                <wv-comp.searchInput model={$categories}/>
            </div>
            <div id="page_details">
               <wv-comp.tables.category />
            </div>
            @if $categories['pagLim'] > 1:
                <wv-comp.pagination>
                    @for $index = 0; $index < $categories['pagLim']; $index++:
                        <li class="page-item{# ($index + 1) === 1? ' active':''#} pagination-item">
                            <span class="page-link pag-numbers">{# $index+1 #}</span>
                        </li>
                    @endfor
                </wv-comp.pagination>
            @endif
        </div>
    </wv-comp.admin-white-box>
</wv-admin-main>