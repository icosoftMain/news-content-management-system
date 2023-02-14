<wv-admin-main crumpTitle={Page Form} {crumpLink} crumpSlashTitle={/&nbsp;Page Form}>
    <wv-comp.form-alert Type={$alertType} Text={{#$alertText#}}/>
    <wv-comp.admin-white-box>
        <form action="{# $formType === 'add' ? url(':createPage') : url(':updatePage') #}" name="page" id="page-admin-form" method="post" enctype="multipart/form-data" class="form-horizontal form-material">
            <div class="form-group">
                <a href="{# url(':manage_pages') #}" >&laquo Go Back to Pages Details </a>
                @if $formType <> 'add':
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                    <a href="{# url(':add_page') #}"><i class="fa fa-plus"></i>&nbsp;Page</a>
                @endif
            </div>
            <div class="form-group">
                <label for="page_type" class="col-md-12">Page Type</label>
                <div class="col-md-12">
                    <select name="pageType" id="page_type" class="form-control form-control-line">
                        @each $pageTypes as $type:
                            <option value="{# $type->id #}">{# $type->name #}</option>
                        @endeach
                    </select><br />
                </div>
            </div>
            {# @CSRF #}
            <input type="hidden" name="pageId" value="{# $pageId #}"/>
            <div class="form-group">
                <label for="title" class="col-md-12">Page Title</label>
                <div class="col-md-12">
                    <input type="text" name="title" id="title" 
                        value="{# !empty($reqValues) ? $reqValues['title']: '' #}" 
                        class="form-control form-control-line" 
                        placeholder="Enter Page Title" 
                    />
                    <br />
                </div>
            </div>
            <div class="form-group">
                <label for="page_content" class="col-md-12">Page Content</label>
                <div class="col-md-12">
                    <textarea name="content" id="page_content" rows ="20" cols="50" class="form-control form-control-line ckeditor" placeholder="Type Story...">
                        {# !empty($reqValues) ? $reqValues['content']: '' #}
                    </textarea>
                    <br />
                </div>
            </div>
            <div class="form-group">
                <label for="page_category" class="col-md-12">Page Category</label>
                <div class="col-md-12">
                    <select name="categoryName" id="page_category" class="form-control form-control-line">
                        <wv-comp.set-category
                            label={Select Category Page}
                            varName={$categoryName}
                            typeName={categoryName}
                            listType={categoryName}
                            formType={$formType}
                        />
                    </select>
                    <br />
                </div>
            </div>
            <div class="form-group">
                <label for="sub_category" class="col-md-12">Sub Category</label>
                <div class="col-md-12">
                    <select name="levelName" id="sub_category" class="form-control form-control-line"></select>
                    <br />
                </div>
            </div>
            <div class="form-group">
                <label for="uploadimage" class="col-md-12">Upload Image</label>
                <div class="col-md-12">
                    <input type="file" name="pageImage" id="uploadimage" multiple="multiple" class="form-control form-control-line">
                    <br />
                </div>
            </div>
            <div class="form-group">
                <label for="Publish this page" class="col-md-12">Make this page visible on the website</label>
                <div class="col-md-12">
                    <span style="display:inline; padding:0.6em" class="radio-primary">
                        <label><input type="radio" name="published" value="Y" {# !empty($reqValues) && $reqValues['published'] === 'Y' ? 'checked': '' #} /> Yes</label>
                    </span>
                    <span style="display:inline; padding:0.6em" class="radio-primary">
                        <label><input type="radio" name="published" value="N" {# !empty($reqValues) && $reqValues['published'] === 'N' ? 'checked': '' #} /> No</label>
                    </span>
                    <br />
                </div>
            </div>
            <!-- <div class="form-group">
                <label for="Make this page visible on website" class="col-md-12">Make this page visible on website</label>
                <div class="col-md-12">
                    <span style="display:inline; padding:0.6em" class="radio-primary">
                        <label><input type="radio" name="visible" value="1" {# !empty($reqValues) && $reqValues['visible'] === '1' ? 'checked': '' #}  /> Visible</label>
                    </span>
                    <span style="display:inline; padding:0.6em" class="radio-primary">
                        <label><input type="radio" name="visible" value="0" {# !empty($reqValues) && $reqValues['visible'] === '0' ? 'checked': '' #}  /> Invisible</label>
                    </span>
                    <br />
                </div>
            </div> -->
            <div class="form-group">
                <label for="source" class="col-md-12">Source</label>
                <div class="col-md-12">
                    <input type="text" name="source" id="source" value="{# !empty($reqValues) ? $reqValues['source']: '' #}" class="form-control form-control-line" placeholder="Enter Source " />
                    <br />
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <input type="submit" name="save" id="save" value="{# $formType === 'add' ? 'Save Page' : 'Edit Page'#}" class="btn btn-success" />
                </div>
                <div class="form-group"><div class="col-md-12">
                    <input type="hidden" name="createdBy" id="createdby" value="{# $adminId #}"  />
                </div>
            </div>
        </form>
    </wv-comp.admin-white-box>
</wv-admin-main>