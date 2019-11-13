@extends('system.layout.main')
@section('title')
<title>Cài đặt</title>
@endsection
@section('css')
<style media="screen">
    .header {
        margin-bottom: 25px;
    }

    .small-box{
        background-color: #f9fafb;
        text-align: left!important;
    }

    .small-box .config-icon{
        position: absolute;
        background-color: #ccc;
        width: 40px;
        height: 40px;
        padding-top: 8px;
        /* padding-left: 9px; */
        border-radius: 5px;
        top: 10px;
        left: 10px;
        text-align: center;

    }

    .fa-config-icon{
        font-size: 25px;
        width: 25px;
        height: 25px;
        color: #777;
    }

    .small-box:hover .icon {
        top: 5px;
        font-size: 60px;
        visibility: visible;
        transition: all .2s linear;
    }

    .small-box:hover {
        color: #0f3e68;
        box-shadow: 0 0 0 1px rgba(63,63,68,0.05), 0 1px 3px 0 rgba(63,63,68,0.15);
        background-color: #f9fafb;
    }

    .small-box {
        color: #0d6aad;
        box-shadow: none;
        transition: all .2s linear;
    }

    .inner{
        padding-left: 60px!important;
    }

    .inner h4{
        margin: 0;
        padding-top: 5px;
        padding-bottom: 5px;
        font-size: 1em;
    }

    .small-box{
        min-height: 96px;
    }

    .setting-group .text-black{
        line-height: 20px;
        height: 40px;
    }

</style>
@endsection
@section('script')
<script>

</script>
<script src="" charset="utf-8"></script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="content">
                <div class="header">
                    <div class="pull-left">
                        <h3 class="">Cài đặt</h3>
                    </div>
                    <div class="clearfix">
                    </div>
                </div>
                <div class="body">
                    <div class="box no-border">
                            <div class="box-body row setting-group">
                            <div class="col-md-4">
                                <a href="settings/general">
                                    <div class="small-box bg-light">
                                        <div class="config-icon">
                                                <i class="fa-config-icon fa fa-gear"></i>
                                        </div>
                                        <div class="inner">
                                            <h4>Chung</h4>
                                            <p class="text-black" style="opacity: .7">Cập nhật thông tin chung về cửa hàng của bạn</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-4">
                                <a href="settings/slides">
                                    <div class="small-box bg-light">
                                        <div class="config-icon">
                                                <i class="fa-config-icon fa fa-clone"></i>
                                        </div>
                                        <div class="inner">
                                            <h4>Slide</h4>
                                            <p class="text-black" style="opacity: .7">Cấu hình slide trang chủ</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-4">
                                <a href="themes">
                                    <div class="small-box bg-light">
                                        <div class="config-icon">
                                                <i class="fa-config-icon fa fa-window-restore"></i>
                                        </div>
                                        <div class="inner">
                                            <h4>Giao diện</h4>
                                            <p class="text-black" style="opacity: .7">Chọn giao diện cho cửa hàng của bạn</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-4">
                                <a href="https://s3.shopbay.vn/laravel-filemanager">
                                    <div class="small-box bg-light">
                                        <div class="config-icon">
                                                <i class="fa-config-icon fa fa-files-o"></i>
                                        </div>
                                        <div class="inner">
                                            <h4>Files</h4>
                                            <p class="text-black" style="opacity: .7">Quản lý tập tin của bạn</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-4">
                                <a href="/admin/settings/domain">
                                    <div class="small-box bg-light">
                                        <div class="config-icon">
                                                <i class="fa-config-icon fa fa-globe"></i>
                                        </div>
                                        <div class="inner">
                                            <h4>Tên miền</h4>
                                            <p class="text-black" style="opacity: .7">Quản lý tên miền của bạn</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-4">
                                <a href="/admin/settings/shipping-fee">
                                    <div class="small-box bg-light">
                                        <div class="config-icon">
                                                <i class="fa-config-icon fa fa-truck"></i>
                                        </div>
                                        <div class="inner">
                                            <h4>Phí vận chuyển</h4>
                                            <p class="text-black" style="opacity: .7">Quản lý phí vận chuyển của cửa hàng</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-4">
                                <a href="/admin/settings/menu">
                                    <div class="small-box bg-light">
                                        <div class="config-icon">
                                                <i class="fa-config-icon fa fa-th-list"></i>
                                        </div>
                                        <div class="inner">
                                            <h4>Menu</h4>
                                            <p class="text-black" style="opacity: .7">Cấu hình menu của bạn</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-4">
                                <a href="/admin/settings/user">
                                    <div class="small-box bg-light">
                                        <div class="config-icon">
                                                <i class="fa-config-icon fa fa-user"></i>
                                        </div>
                                        <div class="inner">
                                            <h4>Quản trị viên</h4>
                                            <p class="text-black" style="opacity: .7">Quản lý quản trị viên</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-4">
                                <a href="/admin/settings/add-code">
                                    <div class="small-box bg-light">
                                        <div class="config-icon">
                                                <i class="fa-config-icon fa fa-code"></i>
                                        </div>
                                        <div class="inner">
                                            <h4>Thêm mã nguồn</h4>
                                            <p class="text-black" style="opacity: .7">Thêm mã nguồn vào trang web</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                                <div class="col-md-4">
                                    <a href="/admin/settings/footer">
                                        <div class="small-box bg-light">
                                            <div class="config-icon">
                                                <i class="fa-config-icon fa fa-list-alt"></i>
                                            </div>
                                            <div class="inner">
                                                <h4>Footer</h4>
                                                <p class="text-black" style="opacity: .7">Cài đặt chân trang</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
