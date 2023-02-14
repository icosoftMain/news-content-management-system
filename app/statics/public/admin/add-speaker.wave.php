<wv-admin-main crumpTitle={Speaker Form} {crumpLink} crumpSlashTitle={/&nbsp;Speaker Form}>
    <wv-comp.form-alert Type={$alertType} Text={{#$alertText#}}/>
    <wv-comp.admin-white-box>
        <form action="{# $formType === 'add' ? url(':addSpeaker') : url(':updateSpeaker') #}" name="page" id="page-admin-form" method="post" enctype="multipart/form-data" class="form-horizontal form-material">
            <div class="form-group">
                <a href="{# url(':manage_speakers') #}" >&laquo Go Back to Speakers Details </a>
                @if $formType <> 'add':
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="{#  url(':add_speaker')  #}" ><i class="fa fa-plus"></i>&nbsp;Speaker</a>
                @endif
            </div>
            <div class="form-group">
                <label for="stitle" class="col-md-12">Title</label>
                <div class="col-md-12">
                    <input type="text" name="title" id="stitle"
                        value="{# !empty($reqValues) ? $reqValues['title']: '' #}" 
                        class="form-control form-control-line"
                        placeholder="Enter Speaker's Title"
                    />
                </div>
            </div>
            {# @CSRF #}
            <input type="hidden" name="speakerId" value="{# $speakerId #}"/>
            <div class="form-group">
                <label for="sfirstName" class="col-md-12">First Name</label>
                <div class="col-md-12">
                    <input type="text" name="firstName" id="sfirstName" 
                        value="{# !empty($reqValues) ? $reqValues['firstName']: '' #}" 
                        class="form-control form-control-line" 
                        placeholder="Enter Speaker's First Name" 
                    />
                    <br />
                </div>
            </div>
            <div class="form-group">
                <label for="slastName" class="col-md-12">Last Name</label>
                <div class="col-md-12">
                    <input type="text" name="lastName" id="slastName" 
                        value="{# !empty($reqValues) ? $reqValues['lastName']: '' #}" 
                        class="form-control form-control-line" 
                        placeholder="Enter Speaker's Last Name" 
                    />
                    <br />
                </div>
            </div>
            <div class="form-group">
                <label for="semail" class="col-md-12">Email</label>
                <div class="col-md-12">
                    <input type="email" name="email" id="semail" 
                        value="{# !empty($reqValues) ? $reqValues['email']: '' #}" 
                        class="form-control form-control-line" 
                        placeholder="Enter Speaker's Email Address" 
                    />
                    <br />
                </div>
            </div>
            <div class="form-group">
                <label for="sphoneNumber" class="col-md-12">Phone Number</label>
                <div class="col-md-12">
                    <input type="tel" name="phoneNumber" id="sphoneNumber" 
                        value="{# !empty($reqValues) ? $reqValues['phoneNumber']: '' #}" 
                        class="form-control form-control-line" 
                        placeholder="Enter Speaker's Phone Number" 
                    />
                    <br />
                </div>
            </div>
            <div class="form-group">
                <label for="suploadimage" class="col-md-12">Speaker Image</label>
                <div class="col-md-12">
                    <input type="file" name="speakerImage" id="uploadimage" multiple="multiple" class="form-control form-control-line">
                    <br />
                </div>
            </div>
            <div class="form-group">
                <label for="speaker_form" class="col-md-12">About Speaker</label>
                <div class="col-md-12">
                    <textarea name="about" id="speaker_speaker" rows ="20" cols="50" class="form-control form-control-line ckeditor" placeholder="Type Story...">
                        {# !empty($reqValues) ? $reqValues['about']: '' #}
                    </textarea>
                    <br />
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <input type="submit" name="save" id="save" value="{# $formType === 'add' ? 'Save Details' : 'Edit Details'#}" class="btn btn-success" />
                </div>
            </div>
        </form>
    </wv-comp.admin-white-box>
</wv-admin-main>
