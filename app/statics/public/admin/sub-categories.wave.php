<wv-admin-main crumpTitle={Sub Category Details} crumpLink={:manage_sub_categories} crumpSlashTitle={/&nbsp;Sub Category Details}>
    <wv-comp.admin-white-box>
        <div class="table-responsive">
            <div class="col-md-6">
                <a href="{# url(':add_sub_category') #}" ><i class="fas fa-plus"></i> Sub Category</a>&nbsp;&nbsp;
                <a href="{# url(':manage_categories') #}"><i class="fas fa-list"></i>&nbsp;Category List</a>
            </div>  
            <div class="col-md-6">
                <wv-comp.searchInput model={$subCategories}/>
            </div>
            <div id="page_details">
                <wv-comp.tables.sub-category />
            </div>
            @if $subCategories['pagLim'] > 1:
                <wv-comp.pagination>
                    @for $index = 0; $index < $subCategories['pagLim']; $index++:
                        <li class="page-item{# ($index + 1) === 1? ' active':''#} pagination-item">
                            <span class="page-link pag-numbers">{# $index+1 #}</span>
                        </li>
                    @endfor
                </wv-comp.pagination>
            @endif
        </div>
    </wv-comp.admin-white-box>  
</wv-admin-main>