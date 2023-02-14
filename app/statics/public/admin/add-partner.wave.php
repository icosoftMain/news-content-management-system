<wv-admin-main crumpTitle={Partner Form} {crumpLink} crumpSlashTitle={/&nbsp;Partner Form}>
    <wv-comp.form-alert Type={$alertType} Text={{#$alertText#}}/>
    <wv-comp.admin-white-box>
        <form action="{# $formType === 'add' ? url(':addPartner') : url(':updatePartner') #}" name="partner" id="admin-partner-form" method="post" enctype="multipart/form-data" class="form-horizontal form-material">
            <div class="form-group">
                <a href="{# url(':manage_partners') #}" >&laquo Go Back to Partners Details </a>
                @if $formType <> 'add':
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="{#  url(':add_partner')  #}"><i class="fa fa-plus"></i>&nbsp;Partner</a>
                @endif
            </div>
            {# @CSRF #}
            <input type="hidden" name="partId" value="{# $partId #}"/>
            <div class="form-group">
                <label for="title" class="col-md-12">Partner Name</label>
                <div class="col-md-12">
                    <input type="text" name="partName" id="partName" 
                        value="{# !empty($reqValues) ? $reqValues['partName']: '' #}" 
                        class="form-control form-control-line" 
                        placeholder="Enter Partner Name" 
                    />
                    <br />
                </div>
            </div>
            <div class="form-group">
                <label for="title" class="col-md-12">Partner Website</label>
                <div class="col-md-12">
                    <input type="website" name="partWebName" id="partWebName" 
                        value="{# !empty($reqValues) ? $reqValues['partWebName']: '' #}" 
                        class="form-control form-control-line" 
                        placeholder="Enter Partner Website" 
                    />
                    <br />
                </div>
            </div>
            <div class="form-group">
                <label for="uploadimage" class="col-md-12">Upload Partner Logo</label>
                <div class="col-md-12">
                    <input type="file" name="partLogo" id="uploadlogo" multiple="multiple" class="form-control form-control-line">
                    <br />
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <input type="submit" name="save" id="save" value="{# $formType === 'add' ? 'Save Page' : 'Edit Partner Detail'#}" class="btn btn-success" />
                </div>
            </div>
        </form>
    </wv-comp.admin-white-box>
</wv-admin-main>