@extends('system.layout.main', [
    'header' => true,
    'ngController' => 'UserController'
])

@section('title')
    <title>Thêm quản trị viên</title>
@endsection

@section('css')

@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('[data-toogle="modal"]').modal();
        });
        var SHOPBAY_VN_API_URL = "{{ env('SB_SHOPBAY_VN_URL') }}";
        var currentUserEmail = "{{ Auth::user()->email }}";
    </script>
    <script src="/system/js/controllers/setting/user-controller.js?v=<?=env('APP_VERSION')?>" charset="utf-8"></script>
@endsection

@section('content')
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="content">
            <h3 class="">Quản lý quản trị viên</h3>
            <div class="header">
                <div class="pull-left">
                    <a href="/admin/settings">
                        <i class="fa fa-angle-left"></i>
                        Quay lại cài đặt</a>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="body mt-5">
                <div class="box no-border">
                    <div class="box-body">
                        <div class="text-right">
                            <button ng-if="isSuperAdmin" class="btn btn-primary" data-toggle="modal" data-target="#modalAddUser"><i class="fa fa-plus"></i> Thêm mới</button>
                        </div>
                        <table class="table table-reponsive">
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Họ tên</th>
                                    <th>Vai trò</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="user in users">
                                    <td>@{{ user.email }}</td>
                                    <td>@{{ user.name }}</td>
                                    <td>@{{ user.is_owner ? 'Chủ shop' : 'Quản trị viên' }}</td>
                                    <td>
                                        <button class="btn btn-danger" ng-if="isSuperAdmin && !user.is_owner" ng-click="delete(user.email)">Xoá</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
    </div>
</div>
@include('system.settings.inc.add-user')
@endsection