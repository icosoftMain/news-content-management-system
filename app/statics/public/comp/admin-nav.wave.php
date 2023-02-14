<nav class="navbar navbar-default navbar-static-top m-b-0">
    <div class="navbar-header">
        <div class="top-left-part">
            <!-- Logo -->
            <a class="logo" href="{# url(':admin_dash') #}">
                <!-- Logo icon image, you can use font-icon also --><b>
                <!--This is dark logo icon--><img src="{# statics('images/admin-logo.png') #}" alt="home" class="dark-logo" /><!--This is light logo icon--><img src="{# statics('images/admin-logo-dark.png') #}" alt="home" class="light-logo" />
                </b>
                <!-- Logo text image you can use text also --><span class="hidden-xs">
                <!--This is dark logo text--><img src="{# statics('images/admin-text.png') #}" alt="home" class="dark-logo" /><!--This is light logo text--><img src="{# statics('images/admin-text-dark.png') #}" alt="home" class="light-logo" />
                </span> </a>
        </div>
        <!-- /Logo -->
        <ul class="nav navbar-top-links navbar-right pull-right">
            <!-- <li>
                <form role="search" class="app-search hidden-sm hidden-xs m-r-10">
                    <input type="text" placeholder="Search..." class="form-control"> <a href=""><i class="fa fa-search"></i></a> </form>
            </li> -->
            <li>
                <a class="profile-pic" href="{# url(':my_profile') #}"> <img src="@statics($profileImage)" alt="Profile Pics" width="36" class="img-circle"><strong class="hidden-xs">{# $user->firstName #}</strong></a>
            </li>
        </ul>
    </div>
    </nav>
    <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav slimscrollsidebar">
                <div class="sidebar-head">
                    <h3><span class="fa-fw open-close"><i class="ti-close ti-menu"></i></span> <span class="hide-menu">Navigation</span></h3>
                </div>
                <ul class="nav" id="side-menu">
                    <li style="padding: 70px 0 0;">
                        <a href="{# url(':admin_dash') #}" class="waves-effect"><i class="fas fa-tachometer-alt fa-fw" aria-hidden="true"></i>Dashboard</a>
                    </li>
                    <li><a href="{# url(':my_profile') #}" class="waves-effect" aria-hidden="true"><i class="fas fa-user"></i> My Profile</a></li>
                    <li><a href="{# url(':manage_pages') #}" class="waves-effect" aria-hidden="true"><i class="fas fa-newspaper"></i> Manage Pages</a></li>
                    <li><a href="{# url(':manage_sidepages') #}" class="waves-effect" aria-hidden="true"><i class="fas fa-newspaper"></i> Manage Side Pages</a></li>
                    <li><a href="{# url(':manage_user') #}" class="waves-effect" aria-hidden="true"><i class="fas fa-user-plus"></i> Manage Users </a></li>
                    <li><a href="{# url(':manage_events') #}" class="waves-effect" aria-hidden="true"><i class="fas fa-list"></i> Manage Events </a></li>
                    <li><a href="@url(':manage_speakers')" class="waves-effect" aria-hidden="true"><i class="fas fa-bullhorn"></i> Manage Speakers </a></li>  
                    <li><a href="@url(':manage_partners')" class="waves-effect" aria-hidden="true"><i class="fas fa-handshake"></i> Manage Partners </a></li>
                    <li><a href="@url(':messages')" class="waves-effect" aria-hidden="true"><i class="fas fa-inbox"></i> Messages</a></li>  
                </ul>
                <div class="center p-20">
                     <a href="@url(':logout')" class="btn btn-danger btn-block waves-effect waves-light"><i class="fas fa-sign-out-alt"></i> Log Out</a>
                 </div>
            </div>
            
        </div>