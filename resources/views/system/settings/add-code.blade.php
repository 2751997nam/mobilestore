@extends('system.layout.main', [
    'header' => true,
    'ngController' => "AddCodeController"
])
@section('title')
<title>Cài đặt chung</title>
@endsection
@section('css')
<style media="screen">

    .small-box .icon{
        font-size: 55px;
        visibility: hidden;
    }

    .small-box:hover .icon {
        /*font-size: 60px;*/
        visibility: visible;
        transition: all .2s linear;
    }

    .small-box:hover {
        color: #0f3e68;
        box-shadow: 0 0 0 1px rgba(63,63,68,0.05), 0 1px 3px 0 rgba(63,63,68,0.15);
    }

    .small-box {
        color: #0d6aad;
        box-shadow: none;
        transition: all .2s linear;
    }

    .box-bg-white{
        border-radius: 3px;
        background: #ffffff;
        box-shadow: 0 0 0 1px rgba(63,63,68,0.05), 0 1px 3px 0 rgba(63,63,68,0.15);
        padding: 10px;
    }
    .editor {
        min-height: 150px;
    }
    .swal2-modal {
        width:850px !important;
    }

    button.swal2-styled {
        margin: 0 20px !important;
    }
</style>
@endsection
@section('script')
    <script>
        var apiUrl = '{{ env('SB_API_URL')}}';
    </script>
    <script src="/system/js/scripts/ace/src-min-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
    <script src="/system/js/scripts/ace/src-min-noconflict/ext-language_tools.js" type="text/javascript" charset="utf-8"></script>
    <script src="/system/js/controllers/setting/add-code-controller.js?v=<?=env('APP_VERSION')?>" charset="utf-8"></script>
@endsection
@section('content')
<div style="padding-bottom: 100px">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="content">
                <div class="header">
                    <div class="pull-left">
                        <h3>Thêm mã nguồn</h3>
                        <a href="/admin/settings">
                            <i class="fa fa-angle-left"></i>
                            Quay lại cài đặt</a>
                    </div>
                    <button id="btnSave" ng-click="save()" style="margin-top: 50px;" class="btn btn-success btn-flat pull-right">Lưu thay đổi</button>
                    <div class="clearfix">
                    </div>
                </div>
                <!-- <hr style="opacity: .2; margin: 5px 0;"> -->
                <div class="body" ng-repeat="editorOption in editorOptions">
                    <div class="no-border">
                        <div>
                            <div class="row" style="margin-top: 25px;">
                                <div class="col-md-4">
                                    <h4>@{{ editorOption.title }}</h4>
                                    <span class="text-black" style="opacity: .7">@{{ editorOption.hint }}</span>
                                </div>

                                <div class="col-md-8">
                                    <div class="box-bg-white">
                                        <div>
                                            <div id="@{{ editorOption.selector }}" class="editor"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
