@extends('system.layout.main', [
    'ngController' => 'ProductListController'
])
@section('title')
<title>Danh sách sản phẩm</title>
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

    .product-item .view-product {
        display: none;
    }

    .product-item:hover .view-product {
        display: block;
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
    $('.dropdown-toggle').dropdown();
</script>
<script src="/system/js/controllers/pagination/pagination-controller.js?v=<?= Config::get("sa.version") ?>" charset="utf-8"></script>
<script src="/system/js/controllers/product/product-list-controller.js?v=<?= Config::get("sa.version") ?>" charset="utf-8"></script>
@endsection
@section('content')
<div class="content" ng-cloak>
    <div class="header">
        <div class="pull-left">
            <h3 class="">Danh sách sản phẩm</h3>
            <div class="import-export">
                <a href="#" class="d-none"><i class="fa fa-upload"></i> Xuất</a>
                <a href="/admin/products/import"><i class="fa fa-download"></i> Nhập</a>
            </div>
        </div>
        <a href="/admin/products/new"><button type="button" name="button"
                class="btn btn-success btn-flat pull-right add">Thêm sản phẩm mới</button></a>
        <div class="clearfix">
        </div>
    </div>
    <div class="body" style="min-height: 500px">
        <div class="box no-border">
            {{-- <div class="box-header with-border"> --}}
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs hide-xs" style="border-bottom: 0px;">
                    <li ng-class="isFiltering == false ? 'active' : ''" ng-click="removeAllFilters()">
                        <a href="javascript:void(0)" style="cursor: pointer">
                            Toàn bộ sản phẩm
                        </a>
                    </li>
                    <li ng-class="isFiltering == true ? 'active' : ''">
                        <a href="javascript:void(0)" ng-show="isFiltering == true">
                            Tìm kiếm sản phẩm
                        </a>
                    </li>
                </ul>

                <!-- /.tab-content -->
            </div>
            {{-- </div> --}}
            <!-- /.box-header -->
            <div class="box-body">
                @include('system.products.inc.product-filter')
                @include('system.products.inc.product-list')
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
                @include('system.pagination')
            </div>
        </div>
    </div>
</div>
@endsection
