<header class="main-header">
    <!-- Logo -->
    <a href="/" class="logo hide-xs" title="Về trang chủ" target="_blank">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><img src="/system/images/logo-mobile.png" /></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><img src="/system/images/logo.png" /></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Ẩn hiện menu</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="/system/images/no-avatar.png" class="user-image" alt="User Image">
                        <span class="hidden-xs">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu">

                        <style>
                            .navbar-custom-menu>.navbar-nav>li>.dropdown-menu{
                                right: 0 !important;
                            }
                            .navbar-nav>.user-menu>.dropdown-menu>.user-footer{
                                padding: 0 !important;
                            }
                            .navbar-nav>.user-menu>.dropdown-menu>.user-footer{
                                padding: 0 !important;
                            }
                            .navbar-nav .open .dropdown-menu>li>a{
                                line-height: 40px;
                            }
                            .navbar-nav>.user-menu>.dropdown-menu{
                                width: 200px;
                            }

                            .navbar-custom-menu>.navbar-nav>li>.dropdown-menu{
                                border: 0px !important;
                            }
                        </style>

                        <!-- Menu Footer-->
                        <li class="user-footer">
                            {{-- <div class="pull-left"> --}}
                                <a href="https://shopbay.vn/websites" class="btn btn-default btn-flat">
                                    <i class="fa fa-bars"></i>
                                    Cửa hàng của bạn</a>
                            {{-- </div> --}}
                            {{-- <div class="pull-right"> --}}
                                <a href="https://shopbay.vn/logout?token={{ \Session::get('ssoToken') }}" class="btn btn-default btn-flat">
                                    <i class="fa fa-sign-out"></i>
                                    Đăng xuất</a>
                            {{-- </div> --}}
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
