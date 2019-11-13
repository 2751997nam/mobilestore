<div style="position: relative">
    <div class="bulk-actions" ng-show="selectedProducts.length > 0">
        <div class="bulk-actions-inner">
            <ul class="bulk-actions-inner-bar">
                <li class="segments" style="background: #dfe3e8; font-weight: bold">
                    <input id="js-reset-or-check-all" type="checkbox">
                    <span>3 sản phẩm được chọn</span>
                </li>
                <li class="segments" style="cursor: pointer">
                    Chọn hành động
                    <span class="caret"></span>
                    <div class="actions-list" >
                        <ul>
                            <li class="actions">Xoá</li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>

    </div>
</div>
<div class="table-responsive">
    <table class="table table-hover">
        <br>
        <tbody>
            <tr>
                <th>#</th>
                <th>Tên</th>
                <th>Nội dung</th>
                <th>Danh mục áp dụng</th>
                <th width="10%"></th>
            </tr>
            <tr class="product-item" ng-repeat="item in items" ng-show="items.length > 0" ng-click="navigate(getEditUrl(item))">
                <td>@{{$index + 1}}</td>
                <td>@{{item.name}}</td>
                <td>
                    <span class="select2 select2-container select2-container--default select2-container--below" dir="ltr" data-select2-id="8" style="width: 100%;">
                        <span class="selection">
                                <span class="select2-selection select2-selection--multiple" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="-1">
                                    <ul class="select2-selection__rendered">
                                        <li class="select2-selection__choice" ng-repeat="value in item.values" title="@{{value.name}}">
                                            @{{value.name}}
                                        </li>
                                    </ul>
                                </span>
                        </span>
                    </span>
                </td>
                <td>
                    <span class="select2 select2-container select2-container--default select2-container--below" dir="ltr" data-select2-id="8" style="width: 100%;">
                        <span class="selection">
                                <span class="select2-selection select2-selection--multiple" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="-1">
                                    <ul class="select2-selection__rendered">
                                        <li class="select2-selection__choice" ng-repeat="it in item.categories" title="@{{it.name}}">
                                            @{{it.name}}
                                        </li>
                                    </ul>
                                </span>
                        </span>
                    </span>
                </td>
                <td class="text-right">
                    <button class="btn btn-flat btn-danger" ng-click="deleteItem($index, item); $event.stopPropagation();">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>
                </td>
            </tr>
            <tr ng-show="items.length == 0">
                <td colspan="4" class="text-center">Không có bộ lọc nào</td>
            </tr>
        </tbody>
    </table>
</div>
