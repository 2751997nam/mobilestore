@extends('system.layout.main', [
    'ngController' => 'PostListController'
])
@section('title')
<title>Danh sách bài viết</title>
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

    .post-item {
        cursor: pointer;

    }

    .post-item .view-post {
        display: none;
    }

    .post-item:hover .view-post {
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
<script src="/system/js/controllers/post/post-list-controller.js?v=<?= Config::get("sa.version") ?>" charset="utf-8"></script>
@endsection
@section('content')
<div class="content" ng-cloak>
    <div class="header">
        <div class="pull-left">
            <h3 class="">Danh sách bài viết</h3>
            <div class="import-export d-none">
                <a href="#"><i class="fa fa-upload"></i> Export</a>
                <a href="#"><i class="fa fa-download"></i> Import</a>
            </div>
        </div>
        <a href="/admin/posts/new"><button type="button" name="button"
                class="btn btn-success btn-flat pull-right add">Thêm bài viết mới</button></a>
        <div class="clearfix">
        </div>
    </div>
    <div class="body">
        <div class="box no-border">
            {{-- <div class="box-header with-border"> --}}
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs hide-xs" style="border-bottom: 0px;">
                    <li ng-class="isFiltering == false ? 'active' : ''" ng-click="removeAllFilters()">
                        <a href="javascript:void(0)" style="cursor: pointer" ng-click="removeAllFilters()">
                            Toàn bộ bài viết
                        </a>
                    </li>
                    <li ng-class="isFiltering == true ? 'active' : ''">
                        <a href="javascript:void(0)" ng-show="isFiltering == true">
                            Tìm kiếm bài viết
                        </a>
                    </li>
                </ul>

                <!-- /.tab-content -->
            </div>
            {{-- </div> --}}
            <!-- /.box-header -->
            <div class="box-body">
                @include('system.posts.inc.post-filter')
                @include('system.posts.inc.post-list')
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
                @include('system.pagination')
            </div>
        </div>
    </div>
</div>
@endsection
