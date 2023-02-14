<wv-admin-main crumpTitle={Link Form} {crumpLink} crumpSlashTitle={/&nbsp;Link Form}>
    <wv-comp.form-alert Type={$alertType} Text={{#$alertText#}}/>
    <wv-comp.admin-white-box>
        <form action="{# $formType === 'add' ? url(':addSideItm') : url(':editSideItm') #}" name="side-item-frm" id="admin-side-item-frm" method="post" enctype="multipart/form-data" class="form-horizontal form-material">
            <div class="form-group">
                <a href="{# url(':manage_sidepages') #}" >&laquo Go Back to Side Page List </a>
            </div>
            {# @CSRF #}
            @if $formType === 'edit':
                <input type="hidden" name="levelId" value="{# $id #}"/>
            @else:
                <input type="hidden" name="spId" value="{# $id #}"/>
            @endif
            <input type="hidden" name="linkType" value="{# $linkType #}"/>
            <div class="form-group">
                <label for="title" class="col-md-12">Link Name</label>
                <div class="col-md-12">
                    <input type="text" name="levelName" id="levelName" 
                        value="{# !empty($reqValues) ? $reqValues['levelName']: '' #}" 
                        class="form-control form-control-line" 
                        placeholder="Enter Link Name" 
                    />
                    <br />
                </div>
            </div>
            {# getItemType($linkType,$reqValues) #}
            <div class="form-group">
                <div class="col-md-12">
                    <input type="submit" name="save" id="save" value="{# $formType === 'add' ? 'Save Link' : 'Edit Link'#}" class="btn btn-success" />
                </div>
            </div>
        </form>
    </wv-comp.admin-white-box>
</wv-admin-main>

@def getItemType($type,$reqValues):
    @if $type === 'link':
        <div class="form-group">
            <label for="title" class="col-md-12">Website</label>
            <div class="col-md-12">
                <input type="website" name="item" id="item" 
                    value="{# !empty($reqValues) ? $reqValues['item']: '' #}" 
                    class="form-control form-control-line" 
                    placeholder="Enter Website" 
                />
                <br />
            </div>
        </div>
    @else:
        <div class="form-group">
            <label for="uploadimage" class="col-md-12">Upload a document file</label>
            <div class="col-md-12">
                <input type="file" name="item" id="item" multiple="multiple" class="form-control form-control-line">
                <br />
            </div>
        </div>
    @endif
@endef