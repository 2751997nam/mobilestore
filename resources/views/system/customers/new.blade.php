@extends('system.layout.main')
@section('title')
<title>Thêm khách hàng mới</title>
@endsection
@section('css')
<style media="screen">
    .form-box {
        background : white;
        float : right;
        margin-right : 15px;
        border-radius : 5px;
    }
    .product-footer > a{
        color: black;
    }
    .product-footer > a:hover{
        background-color: black;
    }
    .title-info {
        font-size: 16px;
        font-weight : 600;
        color : rgb(33, 43, 54);
    }
    .modal-body {
    }
    hr {
    }
    .chosen-container-single, .chosen-container-multi {
        width: 100%!important;
    }
    .chosen-single {
        height: 34px!important;
        padding-top: 5px!important;
        font-size: 15px;
    }

    .box-bg-white {
        border-radius: 3px;
        background: #ffffff;
        box-shadow: 0 0 0 1px rgba(63,63,68,0.05), 0 1px 3px 0 rgba(63,63,68,0.15);
        padding: 10px;
    }
</style>
@endsection
@section('script') 
    <script>
        var customerId = '<?= isset($id) ? $id : "" ?>';
    </script>
<script src="/system/js/scripts/ckeditor/ckeditor.js?v=<?=env('APP_VERSION')?>" charset="utf-8"></script>
<script src="/system/js/controllers/customer-controller.js?v=<?=env('APP_VERSION')?>" charset="utf-8"></script>
<script src="/system/js/controllers/pagination/pagination-controller.js?v=<?=env('APP_VERSION')?>" charset="utf-8"></script>
@endsection
@section('content')
<div class="content" ng-controller="CustomerController">
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
            <div class="back">
                <a href="/admin/customers" style="color: #637381">
                    <i class="fa fa-chevron-left"></i>
                    Danh sách khách hàng
                </a>
            </div>
            <div class="title">
                <h3 ng-show="mode == 'create'">Thêm khách hàng</h3>
            </div>
            <div class="">
                <hr style="opacity: .2;"/>
                <div class="row">
                    <div class="col-md-4">
                        <div class="title" style="margin-top: 10px;">
                            <p class="title-info">Thông tin cơ bản</p>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="box-bg-white">
                            <div class="form-group">
                                <label>Số điện thoại *</label>
                                <input type="text" class="form-control" ng-model="customer.phone" required>
                            </div>
                            <div class="form-group">
                                <label>Họ và tên *</label>
                                <input type="text" class="form-control" ng-model="customer.full_name" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" class="form-control" ng-model="customer.email" required>
                            </div>
                        </div>
                    </div>
                </div>
                <hr style="opacity: .2;"/>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="title" style="margin-top: 10px;">
                            <p class="title-info">Địa chỉ</p>
                        </div>
                    </div>
                    <div class="col-md-8" >
                        <div class="box-bg-white" >
                            <div class="form-group" style="position: relative;">
                                <label>Tỉnh/thành phố</label>
                                <select 
                                    class="form-control chosen-select" 
                                    id="province" 
                                    ng-model="customer.province" 
                                    ng-change="setLocations('district',true)" 
                                    chosen
                                >
                                    <option value="">-- Chọn tỉnh/thành phố --</option>
                                    <option ng-repeat="item in provinces" >@{{ item.name }}</option>
                                </select>
                            </div>
                            <div class="form-group" ng-if="customer.province" style="position: relative;">
                                <label>Quận/huyện</label>
                                <select 
                                    class="form-control chosen-select" 
                                    id="district" 
                                    ng-model="customer.district"
                                    ng-change="setLocations('commune',true)" 
                                    chosen
                                >
                                    <option value="">-- Chọn quận/huyện --</option>
                                    <option ng-repeat="item in districts" >@{{ item.name }}</option>
                                </select>
                            </div>
                            <div class="form-group" ng-if="customer.district" style="position: relative;">
                                <label>Xã/phường</label>
                                <select 
                                    class="form-control chosen-select" 
                                    id="commune" 
                                    ng-model="customer.commune"
                                    chosen
                                >
                                    <option value="">-- Chọn xã/phường --</option>
                                    <option ng-repeat="item in communes" >@{{item.name}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Địa chỉ</label>
                                <input type="text" class="form-control" ng-model="customer.address">
                            </div>
                        </div>
                    </div>
                </div>
                <hr style="opacity: .2;"/>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="title" style="margin-top: 10px;">
                            <p class="title-info">Ghi chú</p>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="box-bg-white">
                            <div class="form-group">
                                <label>Ghi chú</label>
                                <span class="pull-right " id="left-characters" ng-show="customer.note.length > 0">@{{ customer.note.length }}/200 ký tự</span>
                                <textarea 
                                    class="form-control" rows="3" cols="80" ng-model="customer.note" placeholder="Thêm ghi chú">
                                </textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="product-footer" style="margin-top: 15px;">
                    <a href="/admin/customers" class="cancel-button">
                        <button type="button" name="button" class="btn btn-flat btn-default pull-right">Hủy</button>
                    </a>
                    <button style="margin-right: 10px;" type="button" name="button" class="btn btn-flat pull-right btn-primary" ng-click="save()">Lưu</button>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection