<div style="position: relative">
    <div class="bulk-actions" ng-show="selectedProducts.length > 0">
        <div class="bulk-actions-inner">
            <ul class="bulk-actions-inner-bar">
                <li class="segments" style="background: #dfe3e8; font-weight: bold">
                    <input id="js-reset-or-check-all" type="checkbox" ng-click="resetOrCheckAll()">
                    <span>@{{ selectedProducts.length }} sản phẩm được chọn</span>
                </li>
                {{-- <li class="segments">
                    Sửa sản phẩm
                </li> --}}
                <li class="segments dropdown" style="cursor: pointer">
                    <span class="dropdown-toggle" data-toggle="dropdown">Chọn hành động <span class="caret"></span></span>
                    <ul class="dropdown-menu" style="left: -30px; clear: both">
                        <li class="actions" ng-click="deleteProducts()">Xoá</li>
                        <li class="actions" ng-click="changeStatus('Pending')">Ẩn</li>
                        <li class="actions" ng-click="changeStatus('Active')">Hiện</li>
                    </ul>
                </li>
            </ul>
        </div>

    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <br>
        <tbody>
            <tr id="js-table-head">
                <th style="width: 10px">
                    <input type="checkbox" id="js-product-checkall" ng-click="selectAllProducts()">
                </th>
                <th>#</th>
                <th>SKU</th>
                <th style="width: 55px"></th>
                <th>
                    <a href="javascript:void(0)" style="color: #333" ng-click="sortProduct('name')">
                        Tên sản phẩm
                        <span class="ml-1">
                            <i
                                ng-show="sorts.field === 'name' && sorts.type !== 'asc'"
                                class="fa fa-long-arrow-down"
                                aria-hidden="true"
                            ></i>
                            <i
                                ng-show="sorts.field === 'name' && sorts.type !== 'desc'"
                                class="fa fa-long-arrow-up"
                                aria-hidden="true"
                            ></i>
                        </span>
                    </a>
                </th>
                <th>
                    <a href="javascript:void(0)" style="color: #333" ng-click="sortProduct('price')">
                        Giá
                        <span class="ml-1" >
                            <i
                                ng-show="sorts.field === 'price' && sorts.type !== 'asc'"
                                class="fa fa-long-arrow-down"
                                aria-hidden="true"
                            ></i>
                            <i
                                ng-show="sorts.field === 'price' && sorts.type !== 'desc'"
                                class="fa fa-long-arrow-up"
                                aria-hidden="true"
                            ></i>
                        </span>
                    </a>
                </th>
                <th>Danh mục</th>
                <th style="width: 125px;">Thương hiệu</th>
                <th style="width: 150px;"></th>
            </tr>
            <tr ng-show="products.length > 0" ng-repeat="(index, product) in products" class="product-item" ng-click="navigate(product.editUrl)">
                <td><input type="checkbox" class="js-product-checkbox" ng-checked="false" ng-click="addSelectedProducts(product.id); $event.stopPropagation();"></td>
                <td>@{{ meta.page_id * meta.page_size + index + 1 }}</td>
                <td><a href="@{{ product.url }}" target="_blank">#@{{ product.sku }}</a></td>
                <td>
                    <div class="sb-product-thumbnail" ng-if="product.image_url">
                        <img ng-src="@{{ product.display_image_url }}" :alt="product.name" class="adminImageSearch">
                    </div>
                </td>
                <td>
                    @{{ product.name }}
                </td>

                    <td class="width-fit-content text-right">
                        <strong>@{{ product.display_price }}</strong>
                    </td>
                    <td>
                        <span class="label label-info ml-2" ng-repeat="category in product.categories">
                            @{{ category.name }}
                        </span>
                    </td>
                    <td>
                        @{{ product.brand.name }}
                    </td>
                    <td class="text-right">
                        <a href="@{{ product.url }}" target="_blank" class="btn btn-flat btn-primary" role="button" alt="Xem sản phẩm" title="Xem sản phẩm" ng-click="$event.stopPropagation();">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </a>
                        <button class="btn btn-flat btn-danger" ng-click="deleteProduct(index); $event.stopPropagation();">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </button>
                    </td>
                </tr>
                <tr ng-show="products.length == 0">
                    <td colspan="6" class="text-center">Không có sản phẩm nào</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
