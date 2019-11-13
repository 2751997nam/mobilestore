@extends('system.layout.main', [
'header' => false,
'ngController' => "ProductController"
])
@section('title')
<title><?= isset($id) ? 'Sửa sản phẩm' : (request()->query("clone") ? 'Sao chép sản phẩm' : 'Thêm sản phẩm mới') ?></title>
@endsection
@section('css')
    <style>
        .product-title {
            margin-top: 4px;
            font-size: 1.6rem;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            word-break: break-word;
            width: 70%;
        }
        @media only screen (max-width: 768px) {
            .product-title {
                width: 60%;
            }
        }
    </style>
@endsection
@section('header')
@include('system.products.inc.product-header')
@endsection
@section('script')
    <script>
        var productId = '<?= isset($id) ? $id : request()->query("clone") ?>';
        var isCloning = '{{ request()->query("clone") ? 1 : 0 }}';
    </script>
    <script src="/vendor/laravel-filemanager/js/lfm.js"></script>
    <script src="/system/js/scripts/combinatorics.js?v=<?=env('APP_VERSION')?>" charset="utf-8"></script>
    <script src="/system/js/scripts/ckeditor/ckeditor.js?v=<?=env('APP_VERSION')?>" charset="utf-8"></script>
    <script src="/system/js/controllers/product-controller.js?v=<?=env('APP_VERSION')?>" charset="utf-8"></script>
    <script>
        $('.lfm').filemanager('image');
    </script>
    @view('system.products.inc.product-addon-script')
@endsection
@section('content')
<div class="content">
    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;" ng-show="!isLoad">
        Đang tải dữ liệu...
    </p>
    <div class="row" ng-show="isLoad">
        <div class="col-lg-12 col-lg-offset-">
            <div class="back">
                <a href="/admin/products" style="color: #637381">
                    <i class="fa fa-chevron-left"></i>
                    Danh sách sản phẩm
                </a>
            </div>
            <div ng-show="isPage">
                <div class="title">
                    <h3 ng-show="mode == 'create'">@{{ isClone ? 'Sao chép sản phẩm' : 'Thêm sản phẩm' }}</h3>
                    <h3 ng-show="mode == 'update'">Sửa sản phẩm</h3>
                </div>
                <div class="product-container">
                    <div class="row">
                        <div class="col-md-9">
                            @include('system.products.inc.product-title')
                            @view('system.products.inc.product-addon')
                            @include('system.products.inc.product-images')
                            @include('system.products.inc.product-inventory')
                            @include('system.products.inc.product-variants')
                            @include('system.products.inc.product-attribute')
                            @include('system.products.inc.product-seo')
                        </div>
                        <div class="col-md-3">
                            @include('system.products.inc.product-organization')
                        </div>
                    </div>
                </div>
            </div>
            <div class="error-page" ng-show="!isPage">
                <div class="error-content">
                    <h2 class="headline text-yellow"> 404</h2>
                    <h3><i class="fa fa-warning text-yellow"></i> Rất tiếc! Không tìm thấy trang.</h3>

                    <p>
                        Chúng tôi không thể tìm thấy trang bạn đang tìm kiếm.
                        <br/>
                        Bạn có thể quay về <a href="/admin">trang chủ</a> hoặc thử lại.
                    </p>

                </div>
                <!-- /.error-content -->
            </div>
            <div class="product-footer" ng-show="isPage">
                <button type="button" ng-show="mode == 'update'" name="button" class="btn btn-flat btn-lg pull-left" id="btn-delete" ng-click="delete()" >Xóa</button>
                <button type="button" name="button" class="btn btn-flat btn-lg pull-right btn-primary"id="btn-save-2" ng-click="save('saveAndExit')">Lưu và Đóng</button>
                <button type="button" name="button" class="btn btn-flat btn-lg pull-right btn-primary mr-3"id="btn-save-4" ng-click="save('save')">Lưu</button>
            </div>
        </div>
    </div>
</div>
@endsection
