<wv-admin-main crumpTitle={Sub Category Form} {crumpLink} crumpSlashTitle={/&nbsp;Sub Category Form}>
    <wv-comp.form-alert Type={$alertType} Text={{#$alertText#}}/>
    <wv-comp.admin-white-box>
        <form action="{# $formType === 'add' ? url(':add_subCategory'): url(':edit_subCategory') #}" class="form-horizontal form-material" enctype="application/x-www-form-urlencoded" id="add_subcategory"
              method="post" name="add_subcategory">
            <div class="form-group">
                <a href="{# url(':manage_sub_categories') #}" >&laquo Go Back to Sub Category Details </a>
                @if $formType <> 'add':
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="{#  url(':add_sub_category')  #}" ><i class="fa fa-plus"></i>&nbsp;Sub Category</a>
                @endif
            </div>
            <div class="form-group">
                <label for="categories" class="col-md-12">Main Category Name</label>
                <div class="col-md-12">
                    <select class="form-control form-control-line" name="categoryName" id="categories">
                        <wv-comp.set-category
                            label={Select category page}
                            varName={$categoryName}
                            typeName={categoryName}
                            listType={categoryName}
                            formType={$formType}
                        />
                    </select>
                    <br />
                </div>
            </div>
            {#@CSRF#}
            <input type="hidden" name="levId" value="{# $levId #}"/>
            <input type="hidden" name="catId" value="{# $cId #}"/>
            <div class="form-group">
                <label for="levelName" class="col-md-12">Sub Category Name</label>
                <div class="col-md-12">
                    <input class="form-control form-control-line" name="levelName" placeholder="{# $levelName #}"
                           type="text"
                           value=""/>
                    <br />
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