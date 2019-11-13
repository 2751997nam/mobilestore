<style media="screen">
    .main-header, .main-header nav {
        background-color: white !important;
    }
    .md-container {height: 1.5px!important;}
    .md-bar2{background-color: #367fa9!important;}
</style>

<header class="main-header">
    <!-- Logo -->
    <a href="/admin" class="logo hide-xs">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><img src="/system/images/logo-mobile.png" /></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><img src="/system/images/logo.png" /></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" style="margin-top: -2px;">
        <md-progress-linear md-mode="determinate" value="@{{determinateValue}}"></md-progress-linear>
        <div class="col-lg-10 col-lg-offset-1">
            <div class="post-header" style="padding: 4px 0;">
                <p class="pull-left" style="margin-top: 4px;font-size: 2rem;color: #637381;font-weight: bold">
                </p>
                <button type="button" name="button" class="btn btn-flat pull-right btn-primary" id="btn-save-1" ng-click="save('saveAndExit')">Lưu và đóng</button>
                <button type="button" name="button" class="btn btn-flat pull-right btn-primary mr-3" id="btn-save-1" ng-click="save('save')">Lưu</button>                
                <a href="/admin/posts" class="btn btn-flat btn-default pull-right" id="btn-cancel" style="margin: 0 10px;">Hủy</a>
            </div>
        </div>
    </nav>
</header>
