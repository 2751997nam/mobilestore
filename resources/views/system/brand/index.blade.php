@extends('system.layout.main')
@section('title')
<title>Danh sách thương hiệu</title>
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

    .product-item .view-product{
        display: none;
    }
    .product-item:hover .view-product{
        display: block;
    }
</style>
@endsection
@section('script')
<script>
    var apiUrl = '{{ env('SB_API_URL')}}';
    // Select your input element.
    var number = document.getElementById('number');

    // Listen for input event on numInput.
    number.onkeydown = function(e) {
        if(!((e.keyCode > 95 && e.keyCode < 106)
            || (e.keyCode > 47 && e.keyCode < 58)
            || e.keyCode == 8)) {
            return false;
        }
    }
</script>

<script type="text/javascript" src="/system/js/controllers/brand-controller.js?v=<?= Config::get("sa.version") ?>"></script>

@endsection
@section('content')
    <div ng-controller="BrandController" id="BrandController">
        <div class="content">
            <div class="header">
                <div class="pull-left">
                    <h3 class="">Danh sách thương hiệu</h3>
                </div>
                <a href="#" data-toggle="modal" data-target="#modalAddBrand"><button type="button" name="button" class="btn btn-success btn-flat pull-right add">Thêm thương hiệu mới</button></a>
                @include('system.brand.inc.add-brand-modal')
                @include('system.brand.inc.confirm-delete-modal')
                <div class="clearfix"></div>
            </div>
            <div class="body">
                <div class="box no-border">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs hide-xs" style="border-bottom: 0px;">
                            <li class="active">
                                <a href="#tab_1" data-toggle="tab">
                                    Toàn bộ thương hiệu
                                </a>
                            </li>
                        </ul>

                    </div>
                    <div class="box-body table-responsive">
                        <style>
                            .row-brand{
                                display: inline-block;
                            }
                        </style>
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                                <th class="text-center" style="width: 10px">
                                    #
                                </th>
                                <th class="text-center">Hình ảnh</th>
                                <th>Tên thương hiệu</td>
                                <th class="text-center">Độ ưu tiên</th>
                                <th class="text-center"></th>
                            </tr>
                            <tr ng-repeat="brand in brands" style="cursor: pointer">
                                <td style="vertical-align: middle;" class="text-center">
                                    @{{ $index + 1 }}
                                </td>
                                <td class="text-center" ng-click="openBrand(brand)">
                                    <img style="width: 50px; height: 50px" ng-src="@{{ brand.image_url }}" />
                                </td>
                                <td style="vertical-align: middle;" ng-click="openBrand(brand)" ng-bind-html="brand.name"></td>
                                <td class="text-center" style="vertical-align: middle;" ng-click="openBrand(brand)">@{{ brand.sorder }}</td>
                                <td style="vertical-align: middle; text-align: right;">
                                    <button class="btn btn-flat btn-danger" ng-click="delete(brand)"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>

    </div>

@endsection
