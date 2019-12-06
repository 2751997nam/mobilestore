<div class="modal fade" id="search-product-modal">
    <div class=" modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="resetCheckedProduct()">
                <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Tìm kiếm sản phẩm</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="input-group search-group">
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-flat" style="background: #fff">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                        <input
                            ng-model="searchProductQuery"
                            ng-change="searchProduct()"
                            ng-model-options='{ debounce: 500 }'
                            style="border: none"
                            class="form-control"
                            type="text"
                            placeholder="Tìm kiếm..."
                        >
                    </div>
                    <div id="checked-product-list" class="mt-2" ng-if="checkedProducts.length > 0">
                        <div
                            class="product-thumbnail mr-2"
                            ng-repeat="product in checkedProducts"
                        >
                            <img ng-src="@{{ product.display_image_url }}" :alt="product.name" class="adminImageSearch">
                            <a class="btn-remove" href="javascript:void(0)" ng-click="removeCheckedProduct(product)">
                                <i class="fa fa-times-circle"></i>
                            </a>
                        </div>
                    </div>
                    <div class="search-product-result mt-2">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 10px"></th>
                                    <th style="width: 80px"></th>
                                    <th></th>
                                    <th style="width: 150px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="product in products" class="@{{ wasAdded(product) && 'was-added' }}">
                                    <td>
                                        <input
                                            class="js-select-product"
                                            type="checkbox"
                                            ng-model="product.checked"
                                            ng-change="updateCheckedList(product)"
                                        >
                                    </td>
                                    <td>
                                        <img class="adminImageSearch" ng-src="@{{ product.display_image_url }}" alt="@{{ product.name }}">
                                    </td>
                                    <td>@{{ product.name }}</td>
                                    <td class="price">@{{ formatCurrency(product.price) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span class="pull-left">@{{ checkedProducts.length }} sản phẩm được chọn</span>
                <button type="button" class="btn btn-default" data-dismiss="modal" ng-click="resetCheckedProduct()">Đóng</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="addSelectedProducts()">Thêm</button>
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
