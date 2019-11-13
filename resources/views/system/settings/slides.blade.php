@extends('system.layout.main', [
    'header' => true,
    'ngController' => "SlideController"
])
@section('title')
<title>Cài đặt Slide</title>
@endsection
@section('css')
<style media="screen">
    .header {
        margin-bottom: 25px;
    }

    .list {
        list-style: none outside none;
    }

    .item {
        padding: 5px 10px;
        background-color: #e5eded;
        font-size: 1.1em;
        cursor: pointer;
    }

    .ui-sortable-helper {
        cursor: move;
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
    }

    .has-bg{
        min-height: 50px; background: url('/system/images/missing-image-16x9.svg') no-repeat center center;
        background-size: contain;
    }

    .p-0{
        padding: 0!important;
    }
</style>
@endsection
@section('script')
<script>
    var apiUrl = '{{ env('SB_API_URL')}}';
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
<script src="/system/js/controllers/setting/slide-controller.js?v=<?=env('APP_VERSION')?>" charset="utf-8"></script>
@endsection
@section('content')
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="content">
            <h3 class="">Cài đặt Slide trang chủ</h3>
            <div class="header">
                <button id="btnSave" class="btn btn-flat btn-success pull-right" ng-click="save()"><i class="fa fa-floppy-o"></i> Lưu thay đổi</button>
                <div class="pull-left">
                    <a href="/admin/settings">
                        <i class="fa fa-angle-left"></i>
                        Quay lại cài đặt</a>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="body">
                <div class="box no-border">
                    <div class="box-body">
                        <div class="form-group form-inline">

                            <label for="">Kích thước slide: </label>
                            <div class="input-group" style="max-width: 300px;">
                                <input ng-model="slideSize.w" type="number" class="form-control text-right" placeholder="Chiều rộng">
                                <span class="input-group-addon"> x </span>
                                <input ng-model="slideSize.h" type="number" class="form-control" placeholder="Chiều cao">
                            </div>

                        </div>

                        <p style="color: red" class="small">Kích thước slide khuyên dùng: 1920x600.</p>

                        <div style="background-color: #f6f6f6; padding: 10px; font-weight: bold">
                            <div class="row">
                                <div class="col-xs-3 text-center">Hình ảnh</div>
                                <div class="col-xs-6">Liên kết</div>
                                <div class="col-xs-1 text-center"></div>
                                <div class="col-xs-2 text-center">Sắp xếp <span class="text-danger">*</span></div>
                            </div>
                        </div>
                        <div class="table-reponsive">
                            <ul ui-sortable="sortableOptions" ng-model="slides" class="list">
                                <li ng-repeat="slide in slides track by $index" class="item">
                                    <div class="row" style="">
                                        <div class="col-xs-3">
                                            <div ngf-select="uploadImage($file, $index)" data-input="input@{{ $index }}" data-preview="holder@{{ $index }}" ng-class="{'has-bg': !slide.image_url}" style="cursor: pointer;">
                                                <div class="image text-center">
                                                    <img id="holder@{{ $index }}" style="max-width: 100%;  height: 50px;" ng-src="@{{ getImageCdn(slide.image_url, 120, 50) }}" alt="">
                                                </div>
                                                <input style="display: none" type="text" id="input@{{ $index }}" ng-model="slide.image_url">
                                            </div>
                                        </div>
                                        <div class="col-xs-6" style="padding: 8px 0;">
                                            <input class="form-control" style="height: 35px;"  ng-model="slide.href" type="text">
                                        </div>
                                        <div class="col-xs-1 text-center" style="line-height: 50px;">
                                            <button ng-click="remove($index)" type="button" class="btn btn-danger btn-flat btn-xs"><i class="fa fa-times"></i></button>
                                        </div>
                                        <div class="col-xs-2 text-center handle" style="line-height: 50px;">
                                            <i class="fa fa-sort" aria-hidden="true"></i>
                                        </div>
                                    </div>

                                </li>
                            </ul>
                        </div>
                        <div class="text-center">
                            <button ng-click="addNew()" class="btn btn-default btn-block" ><i class="fa fa-plus"></i> Thêm mới</button>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <p><span class="text-danger">*</span> Nhấn giữ, kéo lên - xuống để sắp xếp</p>
            </div>
        </div>
    </div>
</div>
@endsection
