@extends('system.layout.main')
@section('title')
<title>Thông tin khách hàng</title>
@endsection
@section('script')
    <script>
        var customerId = '<?= isset($id) ? $id : "" ?>';
    </script>
    <script src="/system/js/scripts/ckeditor/ckeditor.js?v=<?=env('APP_VERSION')?>" charset="utf-8"></script>
    <script src="/system/js/controllers/customer-controller.js?v=<?=env('APP_VERSION')?>" charset="utf-8"></script>
    <script src="/system/js/controllers/pagination/pagination-controller.js?v=<?=env('APP_VERSION')?>" charset="utf-8"></script>
    <script>
    </script>
@endsection
@section('content')
<div class="content" ng-controller="CustomerController">
    <div class="row" >
        <div class="col-lg-10 col-lg-offset-1">
            <div class="back">
                <a href="/admin/customers" style="color: #637381">
                    <i class="fa fa-chevron-left"></i>
                    Danh sách khách hàng
                </a>
            </div>
                <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                    <h3>Thông tin khách hàng</h3>
                    <button class="btn btn-flat btn-primary" ng-click="openModal(customer)">Sửa</button>
                </div>
                @include('system.customers.inc.edit-customer-modal')
                <div class="product-container">
                    <div class="row">
                        <div class="col-md-8">
                        @include('system.customers.inc.customer-info')
                        @include('system.customers.inc.customer-orders')
                        </div>
                        <div class="col-md-4">
                        @include('system.customers.inc.customer-contact')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
