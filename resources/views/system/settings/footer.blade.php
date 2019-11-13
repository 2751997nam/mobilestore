@extends('system.layout.main', [
    'header' => true,
])
@section('title')
<title>Cài đặt chân trang</title>
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
    .chosen-container{
        width: 100%!important;
        margin-left: 10px;
    }
    .chosen-single {
        height: 30px!important;
        padding: 3px 0px 0px 8px!important;
    }
    .type-label {
        float: right;
        margin-top: 12px;
    }
    .input-sm{
        height: 30px!important;
    }
</style>
@endsection
@section('script')
    <script>
        var apiUrl = '{{ env('SB_API_URL')}}';
    </script>
    <script src="/system/js/scripts/combinatorics.js?v=<?=env('APP_VERSION')?>" charset="utf-8"></script>
    <script src="/system/js/scripts/ckeditor/ckeditor.js?v=<?=env('APP_VERSION')?>" charset="utf-8"></script>
    <script src="/system/js/controllers/setting/footer-controller.js?v=<?=env('APP_VERSION')?>" charset="utf-8"></script>
@endsection
@section('content')
<div style="padding-bottom: 100px" ng-controller="FooterController">

        <div class="container-fluid">
            <div class="content">
                <div class="header">
                    <div class="pull-left">
                        <h3>Cài đặt chân trang</h3>
                        <a href="/admin/settings">
                            <i class="fa fa-angle-left"></i>
                            Quay lại cài đặt</a>
                    </div>
                    <button id="btnSave" ng-click="save()" style="margin-top: 50px;" class="btn btn-success btn-flat pull-right">Lưu thay đổi</button>
                    <div class="clearfix"></div>
                </div>
                <style media="screen">
                    img {
                        max-width: 100%;
                    }
                </style>
                <div class="row" style="margin-top: 25px;">
                    <div class="col-md-3" ng-repeat="item in footer.items">
                        <div class="box no-border">
                            <div class="box-header">
                                <h5>@{{ item.title }}</h5>
                            </div>

                            <div class="box-body" style="position: relative; height: 300px;">
                                <div ng-if="item.type.code == 'link'">
                                    <p ng-repeat="link in item.links">
                                        <a href="@{{ link.url }}">@{{ link.label }}</a>
                                    </p>
                                </div>
                                <div ng-if="item.type.code == 'html'">
                                    <div ng-bind-html="item.html">
                                    </div>
                                </div>
                                <div ng-if="item.type.code == 'text'">
                                    <div ng-bind="item.text">
                                    </div>
                                </div>
                                <div style="position: absolute; width: 100%; height: 100%; top:0; left:0; background-color : rgba(0,0,0,0); ">

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="body" ng-repeat="item in footer.items">
                        <div class="no-border">
                            <div style="margin-top: 25px;">
                                <div class="col-md-12">
                                    <div class="box box-solid">
                                        <div class="box-header" style="padding-top: 10px;">
                                                <strong>Cột @{{ $index + 1 }}</strong>
                                                <div class="box-tools" style="top: 10px;">
                                                <button type="button" class="btn btn-danger btn-sm" ng-show="footer.items.length > 0" ng-click="removeItem($index)"><i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <!-- /.box-header -->
                                        <div class="box-body">
                                            <div class="">
                                                    <table class='table'>
                                                        <tr>
                                                            <td class="col-xs-6">
                                                            <input type="text" class="form-control input-sm" ng-model="item.title"  placeholder="Tiêu đề cột"/>
                                                            </td>
                                                            <td class="col-xs-6">
                                                            <select
                                                                    style="height: 30px!important;"
                                                                    class="form-control"
                                                                    ng-model="item.type"
                                                                    ng-options="type.name for type in types">
                                                            </select>
                                                        </td>
                                                        </tr>
                                                    </table>

                                            </div>

                                            <div class="form-group" style="padding: 0px 5px 0px 9px;" ng-if="item.type.code == 'html'">
                                                <textarea ck-editor ng-model="item.html"></textarea>
                                            </div>
                                            <table class="table table-hover" ng-if="item.type.code == 'link' || item.type.code == 'text'">
                                                <tbody>
                                                    <tr ng-repeat="link in item.links" ng-if="item.type.code == 'link'">
                                                        <td width="50%"><input type="text" class="form-control input-sm" ng-model="link.label" placeholder="Nhập nhãn cho Link"/></td>
                                                        <td width="45%"><input type="text" class="form-control input-sm" ng-model="link.url" placeholder="Nhập Link"/></td>
                                                        <td>
                                                            <button type="button" ng-disabled="item.links.length == 1" class="btn btn-default btn-sm" ng-click="remove($index, item)"><i class="fa fa-times"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr ng-repeat="text in item.texts" ng-if="item.type.code == 'text'">
                                                        <td width="95%"><input type="text" class="form-control input-sm" ng-model="text.label" placeholder="Nhập text"/></td>
                                                        <td>
                                                            <button type="button" ng-disabled="item.texts.length == 1" class="btn btn-default btn-sm" ng-click="remove($index, item)"><i class="fa fa-times"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">
                                                            <button type="button" class="btn btn-default btn-sm"  ng-click="addValue(item)" data-widget="add"><i class="fa fa-plus-circle"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- /.box-body -->
                                        <div class="box-footer">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="body" ng-show="footer.items.length <= 3" ng-click="addItem()">
                        <div class="no-border">
                            <div style="margin-top: 25px;">
                                <div class="col-md-12">
                                    <div class="box box-solid">
                                        <div class="box-body p-3">
                                            <strong class="pull-left">
                                                <a href="javascript:;"><i class="fa fa-plus-square"></i> Thêm cột trong footer</a>
                                            </strong>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer" ng-if="footer.items.length > 0">
                    <button id="btnSave2" ng-click="save()" style="margin-top: 50px;" class="btn btn-success btn-flat pull-right">Lưu thay đổi</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
