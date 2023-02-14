<wv-admin-main crumpTitle={Edit&nbsp;Side&nbsp;Head&nbsp;Form} {crumpLink} crumpSlashTitle={/&nbsp;Edit&nbsp;Side&nbsp;Head&nbsp;Form}>
    <wv-comp.form-alert Type={$alertType} Text={{#$alertText#}}/>
    <wv-comp.admin-white-box>
        <form action="@url(':editSidePage')" method="post" class="form-horizontal form-material">
            <div class="form-group">
                <a href="{# url(':manage_sidepages') #}" >&laquo Go Back to Side Page List </a>
            </div>
            {# @CSRF #}
            <div class="form-group">
                <label for="pageName" class="col-md-12">Header Title</label>
                <div class="col-md-12">
                    <input 
                        type="text" 
                        id="pageName" 
                        name="pageName" 
                        class="form-control form-control-line" 
                        value="{# !empty($reqValues) ? $reqValues->pageName: '' #}" placeholder="Enter Header Title" />
                    <br />
                </div>
            </div>
            <input type="hidden" name="id" value="{# !empty($reqValues) ? $reqValues->id: '' #}">
            <div class="form-group">
                <div class="col-md-12">
                    <span style="display:inline; padding:0.6em" class="radio-primary">
                        <label>
                            <input 
                                id="_pub1" 
                                type="radio" 
                                name="publish" 
                                value="Y" 
                                {# !empty($reqValues) && $reqValues->publish === 'Y' ? 'checked': '' #}/>&nbsp;Show to public</label>
                    </span>
                    <span style="display:inline; padding:0.6em" class="radio-primary">
                        <label>
                            <input 
                                id="_pub2" 
                                type="radio" 
                                name="publish" 
                                value="N"
                                {# !empty($reqValues) && $reqValues->publish === 'N' ? 'checked': '' #}/>&nbsp;Hide from public</label>
                    </span><br />
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <input type="submit" name="edit" id="side_head_edit" value="Edit" class="btn btn-success" />
                </div>
            </div>
        </form>
    </wv-comp.admin-white-box>
</wv-admin-main>
