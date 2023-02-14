<wv-admin-main
    crumpTitle={My Profile}
    crumpLink={:my_profile}
    crumpSlashTitle={/&nbsp;My Profile}>
    <wv-comp.form-alert Type={$alertType} Text={{#$alertText#}}/>
<div class="row">
    <div class="col-md-4 col-xs-12">
        <div class="white-box">
            <div class="user-bg"> <img width="100%" alt="user" src="{# statics('images/profilepics/'.$user->imageName) #}">
                <div class="overlay-box">
                    <div class="user-content">
                        <a href="javascript:void(0)"><img src="{# statics('images/profilepics/'.$user->imageName) #}" class="thumb-lg img-circle" alt="img"></a>
                        <h4 class="text-white">{# $logDetails->username #}</h4>
                        <h5 class="text-white">{# $user->email #}</h5> </div>
                </div>
            </div>
            <div class="user-btm-box">
                <div class="col-md-12 col-sm-12 text-center">
                    <p class="text-purple"><i class="ti-facebook"></i></p>
                    <h1>{# $user->phoneNumber #}</h1>  
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8 col-xs-12">
        <div class="white-box">
            <form action="@url(':change_profile')" class="form-horizontal form-material" enctype="multipart/form-data" method="post">
                <h1 class="text-center"><i class="fas fa-user"></i>&nbsp;Account Details</h1>
                <div class="form-group">
                    <label class="col-md-12">First Name</label>
                    <div class="col-md-12">
                        <input class="form-control form-control-line" name="firstName"
                               placeholder="{# $user->firstName #}" type="text"> </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Last Name</label>
                    <div class="col-md-12">
                        <input class="form-control form-control-line" name="lastName"
                               placeholder="{# $user->lastName #}" type="text"> </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Gender</label>
                    <div class="col-md-12">
                        <select disabled class="form-control form-control-line gender-select">
                           <wv-comp.set-select options={[
                                'M' => 'Male',
                                'F' => 'Female'
                           ]} flag={$user->gender} />
                        </select>
                    </div>
                </div>
                {#@CSRF#}
                <div class="form-group">
                    <label class="col-md-12">Phone Number</label>
                    <div class="col-md-12">
                        <input class="form-control form-control-line" name="phoneNumber" placeholder="{# $user->phoneNumber #}"
                               type="text"> </div>
                </div>
                <div class="form-group">
                    <label for="example-email" class="col-md-12">Email</label>
                    <div class="col-md-12">
                        <input class="form-control form-control-line" id="example-email"
                               name="email" placeholder="{# $user->email #}" type="email"> </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Upload Your Profile Picture</label>
                    <div class="col-md-12">
                        <input class="form-control form-control-line" name="profileImage" type="file">
                    </div>
                </div>
                <h1 class="text-center"><i class="fas fa-lock"></i> Security</h1>
                <div class="form-group">
                    <label class="col-md-12">Username</label>
                    <div class="col-md-12">
                        <input class="form-control form-control-line" name="username" placeholder="{# $logDetails->username #}" type="text">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Change Password</label>
                    <div class="col-md-12">
                        <input class="form-control form-control-line" name="_password" placeholder="Your Password"
                               type="password">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Confirm Password</label>
                    <div class="col-md-12">
                        <input class="form-control form-control-line" name="confirmPassword" placeholder="Your Confirmation Password"
                               type="password"> </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Security Question</label>
                    <div class="col-md-12">
                        <select class="form-control form-control-line form-select" name="securityQuestion">
                        {# 
                            generate_select([
                                    'What is your favorite color?'             => 'What is your favorite color?',
                                    'What is favorite teachers name?'          => 'What is favorite teachers name?',
                                    'What is your mother\'s last name?'        => 'What is your mother\'s last name?',
                                    'Which year did you complete high school?' => 'Which year did you complete high school?'   
                                ],
                                $logDetails->securityQuestion
                            )
                        #}
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Answer</label>
                    <div class="col-md-12">
                        <input class="form-control form-control-line" name="answer:?text" placeholder="{# $logDetails->answer #}"
                               type="text">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <button class="btn btn-success">Update Profile</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</wv-admin-main>
