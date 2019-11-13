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
        /* border: 1px solid white; */
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
<div class="box no-border">
    <div class="box-header">
        <h3 class="box-title">Nội dung lọc *</h3>
        <br>
        <h5>Thêm nội dung cho bộ lọc của bạn. VD: Bộ lọc Giới Tính có 2 nội dung là Nam, Nữ</h5>
    </div>
    <div class="box-body" >
        <div class="box-body table-responsive no-padding">
            <div class="form-group">
                <input type="text" ng-model="filteringValue" class="form-control" ng-keyup="inputFilteringValue($event)" placeholder="Nội dung cách nhau bởi dấu phẩy hoặc nhấn phím Enter" />
            </div>
            <div class="form-group" ng-show="filtering.values.length > 0">
                <span class="select2 select2-container select2-container--default select2-container--below" dir="ltr" data-select2-id="8" style="width: 100%;">
                    <span class="selection">
                        <span class="select2-selection select2-selection--multiple" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="-1">
                            <ul class="select2-selection__rendered">
                                <li class="select2-selection__choice" ng-repeat="value in filtering.values" title="@{{value.name}}">
                                    <span class="select2-selection__choice__remove" role="presentation" ng-click="removeValue($index)">×</span>@{{value.name}}
                                </li>
                            </ul>
                        </span>
                    </span>
                    <span class="dropdown-wrapper" aria-hidden="true"></span>
                </span>
            </div>
        </div>
    </div>
</div>
