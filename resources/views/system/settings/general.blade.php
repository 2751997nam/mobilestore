@extends('system.layout.main', [
    'header' => true,
    'ngController' => "GeneralController"
])
@section('title')
    <title>Cài đặt chung</title>
@endsection
@section('css')
    <style media="screen">

        .small-box .icon {
            font-size: 55px;
            visibility: hidden;
        }

        .small-box:hover .icon {
            /*font-size: 60px;*/
            visibility: visible;
            transition: all .2s linear;
        }

        .small-box:hover {
            color: #0f3e68;
            box-shadow: 0 0 0 1px rgba(63, 63, 68, 0.05), 0 1px 3px 0 rgba(63, 63, 68, 0.15);
        }

        .small-box {
            color: #0d6aad;
            box-shadow: none;
            transition: all .2s linear;
        }

        .box-bg-white {
            border-radius: 3px;
            background: #ffffff;
            box-shadow: 0 0 0 1px rgba(63, 63, 68, 0.05), 0 1px 3px 0 rgba(63, 63, 68, 0.15);
            padding: 10px;
        }
    </style>
@endsection
@section('script')
    <script>
        var apiUrl = '{{ env('SB_API_URL')}}';
    </script>
    <script src="/system/js/controllers/setting/general-controller.js?v=<?=env('APP_VERSION')?>"
            charset="utf-8"></script>
@endsection
@section('content')
    <div class="">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="content">
                    <div class="header">
                        <div class="pull-left">
                            <h3 class="">Cài đặt chung</h3>
                            <a href="/admin/settings">
                                <i class="fa fa-angle-left"></i>
                                Quay lại cài đặt</a>
                        </div>
                        <button id="btnSave" style="margin-top: 50px;" ng-click="save()"
                                class="btn btn-success btn-flat pull-right">Lưu thay đổi
                        </button>
                        <div class="clearfix">
                        </div>
                    </div>
                    <!-- <hr style="opacity: .2; margin: 5px 0;"> -->
                    <div class="body">
                        <div class="no-border">
                            <div style="margin-top: -15px;">
                                {{-- config thương hiệu --}}
                                <div class="row" style="margin-top: 25px;">
                                    <div class="col-md-4">
                                        <h4>Hình ảnh thương hiệu</h4>
                                        <span class="text-black"
                                              style="opacity: .7; padding-bottom: 5px; display: block">@{{ defaultValue['general.brand'].description }}</span>
                                    </div>

                                    <div class="col-md-8">
                                        <div class="box-bg-white">
                                            <div ng-repeat="(key, item) in defaultValue['general.brand'].items">
                                                <div class="form-group">
                                                    <label for="">@{{ item.label }}: </label>
                                                    <a ngf-select="uploadImage($file, item.key)"
                                                       data-input="thumbnail@{{ $index }}"
                                                       data-preview="holder@{{ $index }}" style="cursor: pointer;"
                                                       class="pull-right">
                                                        <i class="fa fa-picture-o"></i> Chọn ảnh
                                                    </a>
                                                    <div class="text-center" style="margin-bottom: 15px;">
                                                        <img ng-if="generalOptions[item.key] && generalOptions[item.key] != ''"
                                                             id="holder@{{ $index }}"
                                                             style="max-height:100px; max-width: 200px"
                                                             ng-src="@{{ generalOptions[item.key] }}">
                                                        <div ng-if="generalOptions[item.key] == '' || !generalOptions[item.key]">
                                                            @include('system.products.inc.upload-image-svg')
                                                        </div>
                                                        <input style="display: none" id="thumbnail@{{ $index }}"
                                                               class="form-control" type="text" name="filepath"
                                                               ng-model="generalOptions[item.key]">
                                                    </div>
                                                    <hr ng-if="$first" style="opacity: .2; margin: 5px 0;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" ng-repeat="(key, option) in defaultValue"
                                     ng-if="key != 'general.brand'" style="margin-top: 25px;">
                                    <div class="col-md-4">
                                        <h4>@{{ option.name }}</h4>
                                        <span class="text-black"
                                              style="opacity: .7; padding-bottom: 5px; display: block">@{{ option.description }}</span>
                                    </div>

                                    <div class="col-md-8">
                                        <div class="box-bg-white">
                                            <div class="form-group"
                                                 ng-repeat="(keyItem, item) in defaultValue[key].items">
                                                <label for="">@{{ item.label }}: </label>
                                                <textarea ng-show="item.tag == 'textarea'"
                                                          ng-model="generalOptions[item.key]" class="form-control"
                                                          name="" id="" cols="30" rows="5"></textarea>
                                                <input data-replace="@{{ item.replace }}" onchange="this.value = this.value.replace(new RegExp(this.dataset.replace), '')"
                                                       ng-show="!item.tag || item.tag == 'input'"
                                                       ng-model="generalOptions[item.key]" class="form-control"
                                                       type="text">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <hr style="opacity: 0; margin: 5px">
                                        <button id="btnSave" ng-click="save()"
                                                class="btn btn-flat btn-success pull-right">Lưu thay đổi
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
