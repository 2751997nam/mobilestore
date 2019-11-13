@extends('system.layout.main', [
    'ngController' => 'FilterListController'
])
@section('title')
<title>Danh sách bộ lọc</title>
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
<script src="/system/js/controllers/pagination/pagination-controller.js" charset="utf-8"></script>
<script src="/system/js/controllers/filter/filter-list-controller.js?v=<?= Config::get("sa.version") ?>" charset="utf-8"></script>
@endsection
@section('content')
<div class="content" ng-cloak>
    <div class="header">
        <div class="pull-left">
            <h3 class="">Danh sách bộ lọc</h3>
        </div>
        <a href="/admin/filters/new"><button type="button" name="button"
                class="btn btn-success btn-flat pull-right add">Thêm bộ lọc mới</button></a>
        <div class="clearfix">
        </div>
    </div>
    <div class="body">
        <div class="box no-border">
            {{-- <div class="box-header with-border"> --}}
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs hide-xs" style="border-bottom: 0px;">
                    <li ng-class="isFiltering == false ? 'active' : ''">
                        <a href="javascript:void(0)" style="cursor: pointer" ng-click="removeAllFilters()">
                            Tất cả bộ lọc
                        </a>
                    </li>
                    <li ng-class="isFiltering == true ? 'active' : ''" ng-show="isFiltering">
                        <a href="javascript:void(0)">
                            Tìm kiếm bộ lọc
                        </a>
                    </li>
                </ul>

                <!-- /.tab-content -->
            </div>
            {{-- </div> --}}
            <!-- /.box-header -->
            <div class="box-body">
                @include('system.filters.inc.form-filter')
                @include('system.filters.inc.filter-list')
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
                @include('system.pagination')
            </div>
        </div>
    </div>
</div>
@endsection
