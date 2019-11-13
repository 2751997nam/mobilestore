@extends('system.layout.main', [
    'ngController' => 'MenuController'
])
@section('title')
<title>Cấu hình menu</title>
@endsection
@section('css')
<link rel="stylesheet" href="/system/css/menu-setting/nestable.css?v=<?= env('APP_VERSION') ?>" />
@endsection
@section('script')
<script>
    var apiUrl = '{{ env('
    SB_API_URL ')}}';
</script>
<script src="/system/js/controllers/setting/menu-controller.js?v=<?= env('APP_VERSION') ?>" charset="utf-8"></script>
<script src="/system/js/scripts/setting-menu/jquery.accordion.js?v=<?= env('APP_VERSION') ?>" charset="utf-8"></script>
<script src="/system/js/scripts/setting-menu/jquery.nestable.js?v<?= env('APP_VERSION') ?>" charset="utf-8"></script>
<script src="/system/js/scripts/setting-menu/menu-manager.js?v=<?= env('APP_VERSION') ?>" charset="utf-8"></script>
@endsection
@section('content')
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="content">
            <div class="header">
                <div class="pull-left">
                    <h3 class="">Cấu hình menu</h3>

                </div>
                <div class="clearfix">
                </div>
            </div>
            <div style="padding: 5px;" class="btn-wrapper">
                <button class="btn btn-primary btn-flat pull-right" ng-click="saveMenu();">Lưu menu</button>
                <button class="btn btn-danger btn-flat pull-right" ng-click="deleteMenu();">Làm rỗng</button>
                <a href="/admin/settings">
                    <i class="fa fa-angle-left"></i>
                    Quay lại cài đặt</a>
            </div>
            <div class="body" style="margin-top: 15px">
                <div class="box no-border">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="config-menu">
                                    <h4>Thiết lập menu</h4>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label>
                                                <input type="radio" ng-model="option.auto" ng-value="true" />
                                                Sử dụng menu mặc định của shopbay
                                            </label>
                                        </div>
                                        <div class="input-group">
                                            <label>
                                                <input type="radio" ng-model="option.auto" ng-value="false" />
                                                Tự tuỳ chỉnh
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel box otpion-box">
                                    <div class="box-header with-border">
                                        <h4 class="box-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" class="category-selection">
                                                <i class="fa fa-caret-up pull-left"></i>
                                                Danh mục
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in" aria-expanded="true" style="">
                                        <div class="box-body">
                                            <div class="nav-tabs-custom">
                                                <ul class="nav nav-tabs hide-xs">
                                                    <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true" ng-click="setMode('allCate')">Tất cả</a></li>
                                                    <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false" ng-click="setMode('searchCate')">Tìm kiếm</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane active" id="tab_1">
                                                        <ul class="list-categories">
                                                            <li ng-repeat="category in allCategories">
                                                                <label>
                                                                    <input type="checkbox" id="category_@{{ category.id }}" name="category_item_@{{ category.id }}" class="category_item" ng-checked="category.checked" ng-model="category.checked" />
                                                                    @{{ category.name }}
                                                                </label>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <!-- /.tab-pane -->
                                                    <div class="tab-pane" id="tab_2">
                                                        <input type="text" id="search_cate" name="search-category" ng-model="search.category" ng-model-options="{ debounce: 1000 }" ng-change="searchCategory();" placeholder="Tìm danh mục" autofocus/>
                                                        <i class="fa fa-spin fa-refresh ico-spinner" ng-if="isSearching"></i>
                                                        <ul class="list-categories" ng-if="!isSearching && searchCategories.length > 0 ">
                                                            <li ng-repeat="scategory in searchCategories">
                                                                <label>
                                                                    <input type="checkbox" id="category_@{{ scategory.id }}" name="category_item" class="category_item" ng-checked="scategory.checked" ng-model="scategory.checked" />
                                                                    @{{ scategory.name }}
                                                                </label>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <!-- /.tab-pane -->
                                                </div>
                                                <!-- /.tab-content -->
                                            </div>
                                            <span id="categories-selected-all" class="select-all pull-left" ng-click="toggleAllCategory();">Chọn toàn bộ</span>
                                            <button class="btn btn-success btn-flat pull-right btn-add-to-menu" ng-click="addCategoryToMenu();">Thêm vào menu </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel box otpion-box">
                                    <div class="box-header with-border">
                                        <h4 class="box-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="collapsed custom-url-selection" aria-expanded="false">
                                                <i class="fa fa-caret-down pull-left"></i>
                                                Liên kết tự tạo
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                        <div class="box-body">
                                            <div class="form-group custom-link-wrapper">
                                                <div class="form-input">
                                                    <label for="custom-name">
                                                        Nhãn điều hướng
                                                        <input name="custom-name" class="form-control" type="text" ng-model="custom.name" placeholder="" />
                                                    </label>
                                                </div>
                                                <div class="form-input">
                                                    <label for="custom-url">
                                                        URL
                                                        <input name="custom-url" class="form-control" type="text" ng-model="custom.url" placeholder="http://" />
                                                    </label>
                                                </div>
                                            </div>
                                            <button class="btn btn-success btn-flat pull-right btn-add-to-menu" ng-click="addCustomLinkToMenu();">Thêm vào menu </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="dd nestable-wrapper" id="nestable" compile="menuHtml"></div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection