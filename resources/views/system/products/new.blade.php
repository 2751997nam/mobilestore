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

        .sb-variants-menu a {
            margin-left: 15px;
        }

        .sb-bulk-select span,
        .sb-bulk-select a {
            margin-right: 10px;
        }

        .option-0,
        .variant-option-1 {
            color: #95a7b7
        }

        .option-1,
        .variant-option-1 {
            color: #29bc94;
        }

        .option-2,
        .variant-option-2 {
            color: #763eaf;
        }

        .option-3,
        .variant-option-3 {
            color: #ff9517;
        }

        .sb-variants .form-control {
            border: 1px solid #d2d6de;
        }

        .sb-variants tr:hover .form-control {
            border: 1px solid #d2d6de;
        }
        .btn-close-box {
            position: relative;
            font-size: 17px!important;
            font-weight: normal!important;
            padding: 1px 10px !important;
        }
        button.close {
            position: absolute;
            top: -6px;
            right: -7px;
            padding: 0px;
            display: inline-block;
            line-height: 10px;
            opacity: 1;
            color: white;
            font-size: 16px;
            font-weight: 200;
            margin-right: 10px;
            margin-top: 6px;
        }
        .bgGreen {
            position: relative;
            margin: 3px 0 3px 5px;
            padding: 3px 5px 3px 5px;
            border: 1px solid #aaa;
            border-radius: 3px;
            background-color: #e4e4e4;
            background-image: -webkit-gradient(linear,50% 0,50% 100%,color-stop(20%,#f4f4f4),color-stop(50%,#f0f0f0),color-stop(52%,#e8e8e8),color-stop(100%,#eee));
            background-image: -webkit-linear-gradient(#f4f4f4 20%,#f0f0f0 50%,#e8e8e8 52%,#eee 100%);
            background-image: -moz-linear-gradient(#f4f4f4 20%,#f0f0f0 50%,#e8e8e8 52%,#eee 100%);
            background-image: -o-linear-gradient(#f4f4f4 20%,#f0f0f0 50%,#e8e8e8 52%,#eee 100%);
            background-image: linear-gradient(#f4f4f4 20%,#f0f0f0 50%,#e8e8e8 52%,#eee 100%);
            background-clip: padding-box;
            box-shadow: 0 0 2px #fff inset, 0 1px 0 rgba(0,0,0,.05);
            color: #333;
            line-height: 13px;
            cursor: default;
        }
        .chosen-container-single, .chosen-container-multi {
            width: 100%!important;
        }
    </style>
@endsection
@section('header')
@include('system.products.inc.product-header')
@endsection
@section('script')
    <script>
        var productId = '<?= isset($id) ? $id : '' ?>';
    </script>
    <script src="/system/js/scripts/combinatorics.js?v=<?=env('APP_VERSION')?>" charset="utf-8"></script>
    <script src="/system/js/scripts/ckeditor/ckeditor.js?v=<?=env('APP_VERSION')?>" charset="utf-8"></script>
    <script src="/system/js/controllers/product-controller.js?v=<?=env('APP_VERSION')?>" charset="utf-8"></script>
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
                    <h3 ng-show="mode == 'create'">Thêm sản phẩm</h3>
                    <h3 ng-show="mode == 'update'">Sửa sản phẩm</h3>
                </div>
                <div class="product-container">
                    <div class="row">
                        <div class="col-md-9">
                            @include('system.products.inc.product-title')
                            @include('system.products.inc.product-inventory')
                            @include('system.products.inc.product-images')
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
                <button type="button" name="button" class="btn btn-flat btn-lg pull-right btn-primary"id="btn-save-2" ng-click="save(true)">Lưu và Đóng</button>
                <button type="button" name="button" class="btn btn-flat btn-lg pull-right btn-primary mr-3"id="btn-save-4" ng-click="save()">Lưu</button>
            </div>
        </div>
    </div>
</div>
@endsection
