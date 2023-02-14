<form id="form-login" action="#" method="post">
    <div class="w3ls-main">
        <div class="wthree-heading">
            <h1>Login as an Admin</h1>
        </div>
        <div class="wthree-container">
            <div class="wthree-form">
                <div class="agileits-2">
                    <h2>login</h2>
                </div>
                    {#@CSRF#}
                    <div class="w3-user">
                        <span><i class="login-icons fas fa-user" aria-hidden="true"></i></span>
                        <input id="logusername" type="text" name="_username" placeholder="Username" required="">
                    </div>
                    <div class="clear"></div>
                    <div class="w3-psw">
                        <span><i class="login-icons fas fa-key" aria-hidden="true"></i></span>
                        <input id="logpassword" type="password" name="password" placeholder="Password" required="">
                    </div>
                    <div class="clear"></div>
                    <!-- <div class="w3l">
                        <span><a href="#">forgot password ?</a></span>  
                    </div> -->
                    <!-- <div class="clear"></div> -->
                    <br/>
                    <div class="w3l-submit">
                        <input type="submit" value="login" id="logbutton">
                    </div>
                    <div class="w3l" style="color: white; font-size: 20px; margin-top: 1.5rem;">
                        Click <a href="@url(':home')" style="color: lightblue;">here</a> to visit home page
                    </div>
                    <div class="clear"></div>
            </div>
        </div>
    </div>
</form>

<div class="agileits-footer">
    <p class="copy_text"> &copy; Rasarp Multimedia Inc with FLY Corporation & Powered by FLY-ARTISAN.v2.0&trade; - @thisYear()</p></div>
</div>