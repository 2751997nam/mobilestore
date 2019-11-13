@extends('system.layout.main', [
    'ngController' => 'DomainController'
])
@section('title')
<title>Cài đặt tên miền</title>
@endsection
@section('css')
<style media="screen">
    .header {
        margin-bottom: 2px;
    }
    table.borderless td,table.borderless th{
        border: none !important;
    }
    .tt-domain {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .tt-domain {
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 16px;
        max-height: 16px;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        margin-bottom: 8px;
    }
    .modal-body {
        max-height: calc(100vh - 212px);
        overflow-y: auto;
        margin-right: 2px;
    }
</style>
@endsection
@section('script')
<script>
    var apiUrl = '{{ env('SB_API_URL')}}';
</script>
<script src="/system/js/controllers/setting/domain-controller.js?v=<?=env('APP_VERSION')?>" charset="utf-8"></script>
@endsection
@section('content')
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="content">
            <div class="header">
                <div class="pull-left">
                    <h3 class="">Tên miền</h3>
                    
                </div>
                <div class="clearfix">
                </div>
            </div>
            <div style="padding: 5px;">
                <a href="/admin/settings">
                        <i class="fa fa-angle-left"></i>
                        Quay lại cài đặt</a>
                <a class="pull-right btn btn-flat btn-success" style="cursor: pointer" data-toggle="modal" data-target="#addDomain">
                    <svg style="fill: #fff; width: 1.1em; height: 1.1em; padding-top: 2px"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M11 11h1c.553 0 1 .448 1 1s-.447 1-1 1h-1v1c0 .552-.447 1-1 1-.552 0-1-.448-1-1v-1H8c-.552 0-1-.448-1-1s.448-1 1-1h1v-1c0-.552.448-1 1-1 .553 0 1 .448 1 1v1zM4 2H2v2h2V2zm2 0v2h12V2H6zm14 2.975V19c0 .552-.448 1-1 1H1c-.552 0-1-.448-1-1V1c0-.552.448-1 1-1h18c.552 0 1 .448 1 1v3.975zM2 6v12h16V6H2z"></path></svg></svg>
                    Thêm tên miền</a>
                    @include('system.settings.inc.add-domain')
            </div>
            <div class="body" style="margin-top: 15px">
                <div class="box no-border">
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Tên miền</th>
                                        <th style="width: 130px">Trạng thái</th>
                                        <!-- <th style="width: 100px" class="text-center">Ngày tạo</th> -->
                                        <th style="width: 100px" class="text-right"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="domain in domains">
                                        <td>
                                            <div class="tt-domain" title="@{{ domain.domain }}">@{{ domain.domain }}</div>
                                        </td>
                                        <td ng-switch on="domain.status">
                                            <span ng-switch-when="PENDING" class="label label-warning">Chờ xác nhận</span>
                                            <span ng-switch-when="ACTIVE" class="label label-success">Đang kích hoạt</span>
                                        </td>
                                        <!-- <td class="text-center">
                                            @{{ datetimeFormat(domain.created_at) | date : "hh:mm:ss dd/MM/y" }}
                                        </td> -->
                                        <td class="text-right">
                                            <button ng-show="!domain.is_primary" ng-click="delete(domain)" class="btn btn-flat btn-sm btn-danger">Xoá</button>
                                            <span class="label label-primary" ng-show="domain.is_primary">Tên miền chính</span>
                                        </td>
                                    </tr>
    
                                </tbody>
                            </table>
                            <p ng-show="!domains.length" style="margin-top: 15px;" class="text-center">Không có tên miền nào!</p>
                        </div>
                    <!-- /.box-body -->
                </div>
                <span>
                    <i class="fa fa-question-circle" aria-hidden="true"></i> <a data-toggle="modal" data-target="#instructionAddDomain" style="cursor: pointer">Hướng dẫn kết nối tên miền.</a>
                    @include('system.settings.inc.instruction-add-domain')
                </span>
                <span class="pull-right" ng-show="domains.length > 0">
                    <a data-toggle="modal" data-target="#changePrimaryDomain" style="cursor: pointer"><i class="fa fa-key" aria-hidden="true"></i> Đổi tên miền chính</a>
                    @include('system.settings.inc.change-primary-domain')
                </span>
            </div>
        </div>
    </div>
</div>

@endsection
