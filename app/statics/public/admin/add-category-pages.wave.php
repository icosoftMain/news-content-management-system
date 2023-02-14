<wv-admin-main crumpTitle={Category Form} {crumpLink} crumpSlashTitle={/&nbsp;Category Form}>
    <wv-comp.form-alert Type={$alertType} Text={{#$alertText#}}/>
    <wv-comp.admin-white-box>
        <form action="{# $buttonText==='Save Category' ? url(':addCategory') : url(':editCategory') #}" name="add_category" id="add_category" method="post" class="form-horizontal form-material" > 
                {#@CSRF#}
                <div class="form-group">
                    <a href="{# url(':manage_categories') #}">&laquo Go Back to Category Details </a>
                    @if $buttonText <> 'Save Category':
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="{# url(':add_category') #}"><i class="fa fa-plus"></i>&nbsp;Category</a>
                    @endif
                </div>
                <input type="hidden" name="catId" value="{# $catId #}" />
                @if $buttonText === 'Save Category':
                    <div class="form-group">
                        <label class="col-md-12" for="category_name">Category Name</label>
                        <div class="col-md-12">
                            <input type="text" name="categoryName" id="category_name" value="" class="form-control form-control-line" placeholder="{# $categoryName #}" />
                            <br />      
                        </div>
                    </div>
                @endif
                <div class="form-group">
                    <label for="Make category visible" class="col-md-12">Make category visible</label>
                    <div class="col-md-12">
                        <span style="display:inline; padding:0.6em" class="radio-primary">
                            <label><input type="radio" name="visible" value="Y" {# $visibility === 'Y' ? 'checked': '' #}/> Yes</label>
                        </span>
                        <span style="display:inline; padding:0.6em" class="radio-primary">
                            <label><input type="radio" name="visible" value="N" {# $visibility === 'N' ? 'checked': '' #} /> No</label>
                        </span><br />
                    </div>
                </div>
                <div class="form-group">
                    <label for="Status" class="col-md-12">Status</label>
                    <div class="col-md-12">
                        <span style="display:inline; padding:0.6em" class="radio-primary">
                            <label><input type="radio" name="_status" value="1" {# $status === '1' ? 'checked': '' #} /> Active</label>
                        </span>
                        <span style="display:inline; padding:0.6em" class="radio-primary">
                            <label><input type="radio" name="_status" value="0" {# $status === '0' ? 'checked': '' #} /> Inactive</label>
                        </span><br />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="submit" name="save" id="save" value="{# $buttonText #}" class="btn btn-success" />
                    </div>
                </div>
        </form>
    </wv-comp.admin-white-box>
</wv-admin-main>