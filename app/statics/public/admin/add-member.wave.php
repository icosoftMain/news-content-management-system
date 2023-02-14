<wv-admin-main crumpTitle={User Form} crumpLink={:add_user} crumpSlashTitle={/&nbsp;User Form}>
    <wv-comp.admin-white-box>   
        <form action="@url(':add_member')" class="form-horizontal form-material" enctype="multipart/form-data" id="add-user-form" method="post"
              name="add_user">
            <wv-comp.form-alert Type={$alertType} Text={{#$alertText#}}/>
            <div class="form-group">
                <a href="{# url(':manage_user') #}" >&laquo Go Back to Users Details </a>
            </div>
            <div class="form-group">
                <label for="first_name" class="col-md-12">First Name</label>
                <div class="col-md-12">
                    <input id="first_name" type="text" name="firstName" value="{# isset($requestValues['firstName'])? $requestValues['firstName']: '' #}" class="form-control form-control-line" required="">
                    <br>
                </div>
            </div>
            {#@CSRF#}
            <div class="form-group">
                <label for="last_name" class="col-md-12">Last Name</label>
                <div class="col-md-12">
                    <input type="text" name="lastName" id="last_name" value="{# isset($requestValues['lastName'])? $requestValues['lastName']: '' #}" class="form-control form-control-line" required="">
                    <br>
                </div>
            </div>
            <div class="form-group">
                <label for="gender" class="col-md-12">Gender</label>
                <div class="col-md-12">
                    <select id="gender" name="gender" class="form-control form-control-line">
                        <option value="">Select user's gender</option>
                        <option value="M" {# isset($requestValues['gender']) && $requestValues['gender']==='M' ? 'selected' : '' #}>Male</option>
                        <option value="F" {# isset($requestValues['gender']) && $requestValues['gender']==='F' ? 'selected' : '' #}>Female</option>
                    </select>
                    <br>
                </div>
            </div>
            <div class="form-group">
                <label for="phone_number" class="col-md-12">Phone Number</label>
                <div class="col-md-12">
                    <input type="text" name="phoneNumber" id="phone_number" value="{# isset($requestValues['phoneNumber'])? $requestValues['phoneNumber'] : '' #}" class="form-control form-control-line" required="">
                    <br>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-md-12">Email</label>
                <div class="col-md-12">
                    <input type="text" name="email" id="email" value="{# isset($requestValues['email'])? $requestValues['email'] : '' #}" class="form-control form-control-line" required="">
                    <br>
                </div>
            </div>
            <div class="form-group">
                <label for="username" class="col-md-12">Username</label>
                <div class="col-md-12">
                    <input type="text" name="username" id="username" value="{# isset($requestValues['username'])? $requestValues['username'] : '' #}" class="form-control form-control-line">
                    <br>
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-md-12">Password</label>
                <div class="col-md-12">
                    <input type="password" name="_password" id="password" value="{# isset($requestValues['_password'])? $requestValues['_password'] : '' #}" class="form-control form-control-line">
                    <br>
                </div>
            </div>
            <div class="form-group">
                <label for="cpassword" class="col-md-12">Confirm Password</label>
                <div class="col-md-12">
                    <input type="password" name="cpassword" id="cpassword" value="{# isset($requestValues['cpassword'])? $requestValues['cpassword'] : '' #}" class="form-control form-control-line">
                    <br>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-12">Security Question</label>
                <div class="col-md-12">
                    <select id="securityQuest" name="securityQuestion" class="form-control form-control-line form-select">
                        <wv-comp.set-select options={[
                            'What is your favorite color?'             => 'What is your favorite color?',
                            'What is favorite teachers name?'          => 'What is favorite teachers name?',
                            'What is your mother\'s last name?'        => 'What is your mother\'s last name?',
                            'Which year did you complete high school?' => 'Which year did you complete high school?'
                        ]} flag={isset($requestValues['securityQuestion'])? $requestValues['securityQuestion'] : ''}
                        />
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-12">Answer</label>
                <div class="col-md-12">
                    <input class="form-control form-control-line" id="securityAns" name="answer" placeholder="Enter security answer"
                           type="text" value="{# isset($requestValues['answer'])? $requestValues['answer'] : '' #}">
                </div>
            </div>
            <div class="form-group">
                <label for="accesslevel" class="col-md-12">Access Level</label>
                <div class="col-md-12">
                    <select name="accessLevel" id="accesslevel" class="form-control form-control-line">
                        <option value="">Select user's access level</option>
                        <option value="U" {# isset($requestValues['accessLevel']) && $requestValues['accessLevel']==='U' ? 'selected' : '' #}>User</option>
                        <option value="E" {# isset($requestValues['accessLevel']) && $requestValues['accessLevel']==='E' ? 'selected' : '' #}>Editor</option>
                        <option value="M" {# isset($requestValues['accessLevel']) && $requestValues['accessLevel']==='M' ? 'selected' : '' #}>Moderator</option>
                        <option value="A" {# isset($requestValues['accessLevel']) && $requestValues['accessLevel']==='A' ? 'selected' : '' #}>Administrator</option>
                    </select>
                    <br>
                </div>
            </div>
            <div class="form-group">
                <label for="profileimg">Upload Your Profile Picture</label>
                <div class="col-md-12">
                    <input type="file" name="profileimg" id="profileimg" class="form-control form-control-line">
                    <br>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <input type="submit" name="submit" value="Add New Member" class="btn btn-success">
                </div>
            </div>
        </form>
    </wv-comp.admin-white-box>
</wv-admin-main>