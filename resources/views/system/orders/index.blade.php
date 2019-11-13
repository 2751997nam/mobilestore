@extends('system.layout.main', [
    'ngController' => 'OrderListController'
])
@section('title')
<title>Danh sách đơn hàng</title>
@endsection
@section('css')
<style media="screen">
    .header {
        margin-bottom: 25px;
    }

    .import-export a {
        color: #637381;
        padding: 7px 10px;
        margin-left: -7px;
        font-size: 15px;
        font-weight: 300;
        margin-right: 15px;
    }

    .import-export a:hover {
        background-color: #ccc;
        border-radius: 3px;
    }

    button.add {
        margin-top: 30px;
    }
    .product-item {
        cursor: pointer;

    }

    .product-item .view-product{
        display: none;
    }
    .product-item:hover .view-product{
        display: block;
    }
    th {
        vertical-align: middle !important;
    }
</style>
@endsection
@section('script')
<script>
    $('#filter > button').on('click', function(event) {
        $(this).parent().toggleClass('open');
    });
    $('body').on('click', function(e) {
        if (!$('#filter').is(e.target) &&
            $('#filter').has(e.target).length === 0 &&
            $('.open').has(e.target).length === 0
        ) {
            $('#filter').removeClass('open');
        }
    });
</script>
<script src="/system/js/controllers/pagination/pagination-controller.js" charset="utf-8"></script>
<script src="/system/js/controllers/order/order-list-controller.js?v=<?= Config::get("sa.version") ?>" charset="utf-8"></script>
@endsection
@section('content')
<div class="content">
    <div class="header">
        <div class="pull-left">
            <h3 class="">Danh sách đơn hàng</h3>
            <div class="import-export d-none">
                <a href="#"><i class="fa fa-upload"></i> Xuất Excel</a>
            </div>

        </div>
        <a href="orders/create"><button type="button" name="button" class="btn btn-success btn-flat pull-right add">Thêm đơn hàng mới</button></a>

        <div class="clearfix">
        </div>
    </div>
    <div class="body" style="min-height: 500px">
        <div class="box no-border">
            {{-- <div class="box-header with-border"> --}}
                <div class="nav-tabs-custom">
                <ul class="nav nav-tabs hide-xs" style="border-bottom: 0px;">
                    <li ng-class="isFiltering == false ? 'active' : ''" ng-click="removeAllFilters()">
                        <a style="cursor: pointer" href="#tab_1" data-toggle="tab"> Toàn bộ <span class="hidden-xs">Đơn hàng</span> </a>
                    </li>
                    <li ng-class="isFiltering == 'processing' ? 'active' : ''" ng-click="addStatusFilter('processing')">
                        <a href="javascript:void(0)">
                            <span class="hidden-xs"> Đơn hàng </span> Cần xử lý
                        </a>
                    </li>
                    <li ng-class="isFiltering === 'canceled' ? 'active' : ''" ng-click="addStatusFilter('canceled')">
                        <a href="javascript:void(0)">
                            <span class="hidden-xs"> Đơn hàng </span> Đã huỷ / Trả lại
                        </a>
                    </li>
                    <li ng-class="isFiltering == 'success' ? 'active' : ''" ng-click="addStatusFilter('success')">
                        <a href="javascript:void(0)" >
                            <span class="hidden-xs"> Đơn hàng </span> Thành công
                        </a>
                    </li>
                    <li ng-class="isFiltering == 'search' && search ? 'active' : ''">
                        <a href="javascript:void(0)" ng-show="isFiltering && search" ng-click="searchWithoutFilter()">
                            Tìm kiếm đơn hàng
                        </a>
                    </li>
                </ul>

            <!-- /.tab-content -->
          </div>
            {{-- </div> --}}
            <!-- /.box-header -->
            <div class="box-body">
                @include('system.orders.inc.order-filter')
                @include('system.orders.inc.order-list')
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
                @include('system.pagination')
            </div>
        </div>
    </div>
</div>
@endsection
