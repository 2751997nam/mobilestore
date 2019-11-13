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
    <nav class="navbar navbar-static-top">
        <md-progress-linear md-mode="determinate" value="@{{ determinateValue }}"></md-progress-linear>
        <div class="col-lg-10 col-lg-offset-1">
            <div class="product-header" style="padding: 4px 0;">
                <button
                    id="btn-save-1"
                    class="btn btn-flat pull-right btn-primary"
                    ng-click="editting == undefined ? saveOrder() : saveEditOrder()"
                    ng-disabled="editting == undefined ? disabledSave : (!editting || disabledSave)"
                >Lưu</button>
                <a href="/admin/orders" class="btn btn-flat btn-default pull-right ml-4 mr-4" id="btn-cancel">Hủy</a>
            </div>
        </div>
    </nav>
</header>
