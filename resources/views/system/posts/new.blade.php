@extends('system.layout.main', [
'header' => false,
'ngController' => "PostController"
])
@section('title')
    @if (isset($id))
        <title>Sửa bài viết</title>
    @else
        <title>Thêm bài viết mới</title>
    @endif
@endsection
@section('header')
@include('system.posts.inc.post-header')
@endsection
@section('style')
<style>
.chosen-container-single, .chosen-container-multi {
        width: 100%!important;
    }
    </style>
@endsection
@section('script')
    <script>
        var postId = '<?= isset($id) ? $id : "" ?>';
    </script>
    <script src="/system/js/scripts/combinatorics.js?v=<?=env('APP_VERSION')?>" charset="utf-8"></script>
    <script src="/system/js/scripts/ckeditor/ckeditor.js?v=<?=env('APP_VERSION')?>" charset="utf-8"></script>
    <script src="/system/js/controllers/post-controller.js?v=<?=microtime(true)?>" charset="utf-8"></script>
@endsection
@section('content')
<div class="content">
    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;" ng-show="!isLoad">
        Đang tải dữ liệu...
    </p>
    <div class="row" ng-show="isLoad">
        <div class="col-lg-10 col-lg-offset-1">
            <div class="back">
                <a href="/admin/posts" style="color: #637381">
                    <i class="fa fa-chevron-left"></i>
                    Danh sách bài viết
                </a>
            </div>
            <br>
            <div ng-show="isPage">
                <div class="title" style="margin-top: -15px">
                    <h3>
                    @if (isset($id))
                        Sửa bài viết
                    @else
                        Thêm bài viết mới
                    @endif
                    </h3>
                </div>
                <div class="post-container">
                    <div class="row">
                        <div class="col-md-8">
                            @include('system.posts.inc.post-title')
                            @include('system.posts.inc.post-images')

                        </div>
                        <div class="col-md-4">
                            @include('system.posts.inc.post-organization')
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
            <div class="post-footer" ng-show="isPage">
                <button type="button" ng-show="mode == 'update'" name="button" class="btn btn-flat btn-lg pull-left" id="btn-delete" ng-click="delete()" >Xóa</button>
                <button type="button" name="button" class="btn btn-flat btn-lg pull-right btn-primary"id="btn-save-2" ng-click="save('saveAndExit')">Lưu và đóng</button>                
                <button type="button" name="button" class="btn btn-flat btn-lg pull-right btn-primary mr-2"id="btn-save-4" ng-click="save('save')">Lưu</button>                            
            </div>
        </div>
    </div>
</div>
@endsection
