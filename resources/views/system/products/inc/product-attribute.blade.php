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
<div id="js-product-attributes" class="box no-border sb-variants collapsed-box">
    <div class="box-header with-border">
        <h3 class="box-title">Cài đặt bộ lọc sản phẩm</h3>
        <br>
        <h5>Hãy cài đặt <a href="/admin/filters/new" target="_blank" title="Cấu hình bộ lọc">bộ lọc</a> cho danh mục trước khi sử dụng tính năng này. Nếu bộ lọc chưa hiện ra hãy thử chọn lại danh mục cho sản phẩm.</h5>
        <div class="box-tools pull-right">
            <a href="javascript:;"
               class="pull-right"
               style="margin-top: 10px;"
               data-widget="collapse"
               data-toggle="tooltip"
            >
                <span ng-show="!isOpenAttr" ng-click="isOpenAttr = true">Đóng</span>
                <span ng-show="isOpenAttr" ng-click="isOpenAttr = false">Thêm bộ lọc</span>
            </a>
        </div>
    </div>
    <div class="box-body" style="display: none;">
        <div class="box-body no-padding">
            <table class="table table-bordered">
                <tr>
                    <th>Bộ lọc</th>
                    <th>Giá trị lọc</th>
                </tr>
                <tr ng-repeat="attr in attributes">
                    <td width="30%">
                        @{{ attr.name }}
                    </td>
                    <td>
                        <select class="form-control chosen-select"
                                chosen
                                persistent-create-option="true"
                                skip-no-results="true"
                                ng-model="attr.value"
                                ng-options="value.name for value in attr.values track by value.id">
                            <option value="">Không xác định</option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
