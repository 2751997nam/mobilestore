<div class="input-group">
    <div class="input-group-btn d-none" id="filter">
        <button type="button" class="btn btn-default dropdown-toggle btn-flat hide-xs" aria-expanded="false">Lọc
            sản phẩm
            <span class="fa fa-caret-down"></span></button>
        <div class="dropdown-menu" style="padding: 20px; background-color: white;">
            <div>
                <div class="form-group d-block overflow-auto">
                    <label>Hiển thị sản phẩm theo tiêu chí:</label>
                    <select
                        class="form-control"
                        onclick="event.preventDefault()"
                        ng-model="selectedFilter"
                        ng-change="refresh()"
                    >
                        <option
                            ng-repeat="(key, value) in filterOptions"
                            value="@{{ key }}"
                        >
                            @{{ value }}
                        </option>
                    </select>
                </div>
                <div ng-show="selectedFilter != ''">
                    <div ng-show="selectedFilter === 'categories.category_id'" class="form-group d-block overflow-auto">
                        <label>là</label>
                        <select
                            class="form-control"
                            ng-model="categoryFilter"
                            >
                            <option value="" selected>-- Chọn danh mục --</option>
                            <option
                                ng-repeat="item in categories"
                                value="@{{ item.id }}"
                            >
                                @{{ item.name }}
                            </option>
                        </select>
                    </div>
                    <div ng-show="selectedFilter === 'brand_id'" class="form-group d-block overflow-auto">
                        <label>là</label>
                        <select
                            class="form-control"
                            ng-model="newFilters.brand_id"
                            >
                            <option value="" selected>-- Chọn thương hiệu --</option>
                            <option
                                ng-repeat="item in brands"
                                value="@{{ item.id }}"
                            >
                                @{{ item.name }}
                            </option>
                        </select>
                    </div>
                    <div ng-show="selectedFilter === 'price'" class="mt-2 form-group d-block">
                        <div class="row content-middle">
                            <div class="col-md-3">
                                <span>Nhỏ nhất:</span>
                            </div>
                            <div class="col-md-9">
                                <input
                                    type="text"
                                    awnum="price"
                                    num-sep=","
                                    num-neg="false"
                                    class="form-control"
                                    ng-model="minPrice"
                                >
                            </div>
                        </div>
                        <div class="row content-middle mt-2">
                            <div class="col-md-3">
                                <span>Lớn nhất:</span>
                            </div>
                            <div class="col-md-9">
                                <input
                                    type="text"
                                    awnum="price"
                                    num-sep=","
                                    num-neg="false"
                                    class="form-control"
                                    ng-model="maxPrice"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 d-block">
                        <button class="btn btn-flat btn-success" ng-click="addFilters()">Thêm bộ lọc</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /btn-group -->
    <input ng-keyUp="$event.keyCode === 13 && searchProduct()" ng-model="search" type="text" class="form-control"
        placeholder="Tìm kiếm sản phẩm">
    <div class="input-group-btn">
        <button type="button" class="btn btn-default btn-flat" ng-click="searchProduct()">
            <i class="fa fa-search"></i>
            Tìm kiếm
        </button>
    </div>
</div>
<div class="mt-4">
    <span ng-repeat="(key, value) in displayedFilters" class="mr-3">
        <span class="filter-option" ng-show="key != 'keyword'">
            @{{ filterOptions[key] + ': ' + value }}
            <a href="javascript:void(0)" ng-click="removeFilter(key)">
                <i class="fa fa-times-circle" aria-hidden="true"></i>
            </a>
        </span>
    </span>
</div>
