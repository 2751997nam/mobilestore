@extends('system.layout.main', [
'header' => false,
'ngController' => "FilterController"
])
@section('title')
<title><?= isset($id) ? 'Sửa bộ lọc sản phẩm' : 'Thêm bộ lọc mới'?></title>
@endsection
@section('header')
@include('system.filters.inc.filter-header')
@endsection
@section('script')
    <script>
        var filterId = '<?= isset($id) ? $id : "" ?>';
    </script>
    <script src="/system/js/controllers/filter-controller.js?v=<?=env('APP_VERSION')?>" charset="utf-8"></script>
@endsection
@section('content')
<div class="content">
{{--    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">--}}
{{--        Đang tải dữ liệu...--}}
{{--    </p>--}}
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
            <div class="back">
                <a href="/admin/filters" style="color: #637381">
                    <i class="fa fa-chevron-left"></i>
                    Danh sách bộ lọc
                </a>
            </div>
            <div>
                <div class="title">
                    <h3 ng-show="mode == 'create'">Thêm bộ lọc</h3>
                    <h3 ng-show="mode == 'update'">Sửa bộ lọc</h3>
                </div>
                <div class="product-container">
                    <div class="row">
                        <div class="col-md-8">
                            @include('system.filters.inc.filter-title')
                            @include('system.filters.inc.filter-value')
                        </div>
                        <div class="col-md-4">
                            @include('system.filters.inc.filter-organization')
                        </div>
                    </div>
                </div>
            </div>
{{--            <div class="error-page" ng-show="!isPage">--}}
{{--                <div class="error-content">--}}
{{--                    <h2 class="headline text-yellow"> 404</h2>--}}
{{--                    <h3><i class="fa fa-warning text-yellow"></i> Rất tiếc! Không tìm thấy trang.</h3>--}}

{{--                    <p>--}}
{{--                        Chúng tôi không thể tìm thấy trang bạn đang tìm kiếm.--}}
{{--                        <br/>--}}
{{--                        Bạn có thể quay về <a href="/admin">trang chủ</a> hoặc thử lại.--}}
{{--                    </p>--}}

{{--                </div>--}}
{{--                <!-- /.error-content -->--}}
{{--            </div>--}}
            <div class="product-footer">

            </div>
        </div>
    </div>
</div>
@endsection
