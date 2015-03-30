<!--header start-->
<header class="header white-bg">
    <div class="sidebar-toggle-box">
        <div data-original-title="Toggle Navigation" data-placement="right" class="icon-reorder tooltips"></div>
    </div>
    <!--logo start-->
    <a href="#" class="logo">Flat<span>lab</span></a>
    <!--logo end-->
    <div class="nav notify-row" id="top_menu">
       
    </div>
    <div class="top-nav ">
        <!--search & user info start-->
        <ul class="nav pull-right top-menu">
            <!-- <li>
                <input type="text" class="form-control search" placeholder="Search">
            </li> -->
            <!-- user login dropdown start-->
            <li class="dropdown">
                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                    <img alt="" width="30" height="30" src="{{ \Confide::user()->avatar()->thumbnail }}">
                    <span class="username">{{ \Confide::user()->username }}</span>
                    <b class="caret"></b>
                </a>
                <ul class="dropdown-menu extended">
                    <div class="log-arrow-up"></div>
                    <li><a href="{{ route('admin_users.show', \Confide::user()->id) }}"><i class=" icon-suitcase"></i> Profile</a></li>
                    <!-- <li><a href="#"><i class="icon-cog"></i> Settings</a></li>
                    <li><a href="#"><i class="icon-bell-alt"></i> Notification</a></li> -->
                    <li><a href="{{ route('admin_logout') }}"><i class="icon-key"></i> Log Out</a></li>
                </ul>
            </li>
            <!-- user login dropdown end -->
        </ul>
        <!--search & user info end-->
    </div>
</header>
<!--header end