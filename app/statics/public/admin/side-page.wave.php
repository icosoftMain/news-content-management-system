<wv-admin-main crumpTitle={Side Page Details} crumpLink={:manage_sidepages} crumpSlashTitle={/&nbsp;Side Page Details}>
    <wv-comp.form-alert Type={$alertType} Text={{#$alertText#}}/>
    <wv-comp.admin-white-box>
        <div class="table-responsive">
            <div class="col-md-6">
                <a href="#!" title="Add Side Page" data-toggle="modal" data-target="#addSidePageModal"><i class="fas fa-plus"></i> Side Page</a>&nbsp;&nbsp;
                <a href="#!" title="Edit Membership Forms" data-toggle="modal" data-target="#editMembershipFormModal"><i class="fas fa-edit"></i> Edit Memebership Form</a>
            </div>  
            <div class="col-md-6">
                <wv-comp.searchInput model={$sidePages}/>
            </div>
            @if isset($sidePages[0]) && !is_empty($sidePages):
                <div id="page_details">
                    <br/>
                    <br/>
                    <wv-comp.tables.sidePages/>
                </div>
            @endif
            @if $sidePages['pagLim'] > 1:
                <wv-comp.pagination>
                    @for $index = 0; $index < $sidePages['pagLim']; $index++:
                        <li class="page-item{# ($index + 1) === 1? ' active':''#} pagination-item">
                            <span class="page-link pag-numbers">{# $index+1 #}</span>
                        </li>
                    @endfor
                </wv-comp.pagination>
            @endif
        </div>   
    </wv-comp.admin-white-box>

    <wv-comp.staticModal
        TargetId={addSidePageModal}
        Title={<i class="fas fa-plus"></i>&nbsp;Add Side Page Header}
        Component={
            <button id="admin-sdfrm-btn" type="button" class="btn btn-primary">Add Title</button>
        }
    >
        <form id="tbl-admin-sdfrm" method="post" class="form-horizontal form-material">
            {# @CSRF #}
            <div class="form-group">
                <label for="pageName" class="col-md-12">Header Title</label>
                <div class="col-md-12">
                    <input type="text" id="pageName" name="pageName" class="form-control form-control-line" placeholder="Enter Header Title" />
                    <br />
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <span style="display:inline; padding:0.6em" class="radio-primary">
                        <label><input id="_pub1" type="radio" name="publish" value="Y" checked />&nbsp;Show to public</label>
                    </span>
                    <span style="display:inline; padding:0.6em" class="radio-primary">
                        <label><input id="_pub2" type="radio" name="publish" value="N"/>&nbsp;Hide from public</label>
                    </span><br />
                </div>
            </div>
        </form>
    </wv-comp.staticModal>

    <wv-comp.staticModal
        TargetId={editMembershipFormModal}
        Title={Edit Membership Form}
    >
        <form action="@url(':editMemForm')" method="post" enctype="multipart/form-data" class="form-horizontal form-material">
            {# @CSRF #}
            <div class="form-group">
                <label for="pageName" class="col-md-12">Membership Form</label>
                <div class="col-md-12">
                    <input type="file" id="memberForm" name="memberForm" class="form-control form-control-line"/>
                    <br />
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Edit Form</button>
                    <br />
                </div>
            </div>
        </form>
    </wv-comp.staticModal>
</wv-admin-main>
