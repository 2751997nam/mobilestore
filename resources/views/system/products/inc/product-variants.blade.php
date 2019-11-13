<style media="screen">
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
        border: 1px solid white;
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
<div id="js-product-variants" class="box no-border sb-variants" ng-class="{'collapsed-box': productVariants.length == 0}" ng-mouseover="initVariant();">
    <div class="box-header with-border">
        <h3 class="box-title">Biến thể sản phẩm</h3>
        <br>
        <h5>Thêm biến thể, nếu sản phẩm của bạn có nhiều phiên bản khác nhau như màu sắc hoặc kích cỡ</h5>
        <div class="box-tools pull-right">
            <a href="javascript:;"
               class="pull-right"
               style="margin-top: 10px;"
               data-widget="collapse"
               data-toggle="tooltip"
            >
                <span ng-show="productVariants.length == 0">Thêm biến thể</span>
                <span ng-show="productVariants.length > 0 && !isOpen" ng-click="isOpen = true">Đóng</span>
                <span ng-show="productVariants.length > 0 && isOpen" ng-click="isOpen = false">Mở</span>
            </a>
        </div>
    </div>
    <div class="box-body" ng-style="{ 'display' : (productVariants.length == 0) ? 'none' : 'block' }" ng-mouseover="initLfm();">
        <div class="box-body no-padding table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th width="30%">Nhóm biến thể</th>
                    <th>Biến thể</th>
                    <th width="5%"></th>
                </tr>
                <tr ng-repeat="variant in variants">
                    <td>
                        <div ng-keypress="clickerVariant(variant)">
                            <select class="form-control"
                                    chosen
                                    create-option-text="'Tạo nhóm biến thể'"
                                    persistent-create-option="true"
                                    skip-no-results="true"
                                    create-option="createVariant"
                                    ng-model="variant.group"
                                    ng-change="buildGroupVariant(variant)"
                                    ng-options="item.name for item in allVariants track by item.id">
                            </select>
                        </div>
                    </td>
                    <td>
                        <div ng-keypress="clickerOption(variant)">
                            <select class="form-control chosen-select"
                                    multiple
                                    chosen
                                    create-option-text="'Tạo biến thể'"
                                    persistent-create-option="true"
                                    create-option="createVariantOption"
                                    ng-model="variant.values"
                                    ng-change="changeVariantValue(variant)"
                                    ng-options="item.name for item in optionByVariantIds[variant.group.id] track by item.id">
                            </select>
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-flat btn-default" ng-click="removeVariant($index)"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <button type="button"
                                ng-show="variants.length < 3"
                                ng-click="addVariant()"
                                class="btn btn-flat btn-block btn-default btn-sm">Thêm biến thể</button>
                    </td>
                    <td colspan="2"></td>
                </tr>
            </table>
            <div class="sb-bulk-select" ng-show="productVariants.length > 0">
                Danh sách sản phẩm biến thể
            </div>
            <br>
            <table class="table table-hover" ng-show="productVariants.length > 0" style="font-size: 13px;">
                <tr>
                    <th> #</th>
                    <th>Biến thể</th>
                    <th>Ảnh</th>
                    <th>SKU</th>
                    <th>Giá bán</th>
                    <th>Giá thị trường</th>
                    <th>Mặc định</th>
                    <th></th>
                </tr>
                <tr ng-repeat="item in productVariants">
                    <td>@{{ $index + 1 }}</td>
                    <td>
                        <span class="pull-right-container">
                            <small class="label bgGreen"
                                   style="margin: 0px 2px;"
                                   ng-repeat="variant in item.variants">@{{variant.name}}</small>
                        </span>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a data-input="thumbnail-@{{$index}}" ng-if="!item.image_url" class="btn btn-flat btn-sm btn-primary lfm">
                                <i class="fa fa-picture-o"></i>
                            </a>
                            <input id="thumbnail-@{{$index}}"
                                   style="display: none;"
                                   type="text"
                                   name="filepath"
                                   ng-model="item.image_url">
                            <img ng-if="item.image_url && item.image_url != ''"
                                 data-input="thumbnail-@{{$index}}"
                                 class="lfm"
                                 style="max-height:30px;"
                                 ng-src="@{{ item.image_url }}"/>
                        </div>

                    </td>
                    <td><input type="text" ng-model="item.sku" placeholder="Mã sku" style="height: 30px!important;" class="form-control input-sm"/></td>
                    <td><input type="text" ng-model="item.price" placeholder="Giá tiền" awnum="price" style="height: 30px!important;" class="form-control input-sm"/></td>
                    <td><input type="text" ng-model="item.high_price" placeholder="Giá thị trường" style="height: 30px!important;" awnum="price" class="form-control input-sm"/></td>
                    <td>
                        <input type="checkbox" ng-model="item.is_default" ng-checked="item.is_default == true" ng-disabled="item.is_default == true" ng-click="setDefaultChecker(item)" style="margin: 6px!important;"/>
                    </td>
                    <td>
                        <button type="button" class="btn btn-flat btn-default btn-sm" ng-click="removeProductSku($index)"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
