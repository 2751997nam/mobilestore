<div class="input-group">
    <div class="input-group-btn" id="filter">
        <button type="button" class="btn btn-default dropdown-toggle btn-flat hide-xs" aria-expanded="false">
            Lọc đơn hàng
            <span class="fa fa-caret-down"></span></button>
        <div class="dropdown-menu" style="padding: 20px; background-color: white;">
            <div>
                <div class="form-group d-block overflow-auto">
                    <label>Hiển thị đơn hàng theo tiêu chí:</label>
                    <select
                        class="form-control"
                        onclick="event.preventDefault()"
                        ng-model="selectedFilter"
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
                    <div ng-show="selectedFilter === 'address'" class="form-group d-block overflow-auto">
                        <label>là</label>
                        <select class="form-control" ng-model="selectedProvinceId" ng-change="setSelectedProvince()">
                            <option value="" selected>-- Chọn tỉnh/thành phố --</option>
                            <option
                                ng-repeat="location in locations"
                                value="@{{ location.id }}"
                            >
                                @{{ location.name }}
                            </option>
                        </select>
                        <select class="form-control mt-3" ng-model="selectedDistrictId" ng-show="selectedProvinceId != ''">
                            <option value="" selected>-- Chọn quận/huyện --</option>
                            <option
                                ng-repeat="district in selectedProvince.districts"
                                value="@{{ district.id }}"
                            >
                                @{{ district.name }}
                            </option>
                        </select>
                    </div>
                    <div ng-show="selectedFilter === 'amount'" class="mt-2 form-group d-block">
                        <div class="row content-middle">
                            <label class="col-md-3">Min:</label>
                            <div class="col-md-9">
                                <input
                                    type="text"
                                    awnum="price"
                                    num-sep=","
                                    num-neg="false"
                                    class="form-control"
                                    ng-model="minAmount"
                                >
                            </div>
                        </div>
                        <div class="row content-middle mt-2">
                            <label class="col-md-3">Đến:</label>
                            <div class="col-md-9">
                                <input
                                    type="text"
                                    awnum="price"
                                    num-sep=","
                                    num-neg="false"
                                    class="form-control"
                                    ng-model="maxAmount"
                                >
                            </div>
                        </div>
                    </div>
                    <div ng-show="selectedFilter === 'order.created_at'" class="mt-2 form-group d-block">
                        <div class="row content-middle">
                            <label class="col-md-3">Từ:</label>
                            <div class="col-md-9">
                                <input
                                    type="date"
                                    class="form-control"
                                    ng-model="minDate"
                                >
                            </div>
                        </div>
                        <div class="row content-middle mt-2">
                            <label class="col-md-3">Đến:</label>
                            <div class="col-md-9">
                                <input
                                    type="date"
                                    class="form-control"
                                    ng-model="maxDate"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 d-block">
                        <button class="btn btn-success" ng-click="addFilters()">Thêm bộ lọc</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /btn-group -->
    <input
        ng-keyPress="$event.keyCode === 13 && searchOrder()"
        ng-model="search"
        type="text" class="form-control"
        placeholder="Nhập mã đơn hàng"
    >
    <div class="input-group-btn">
        <button type="button" class="btn btn-default btn-flat" ng-click="searchOrder()">
            <i class="fa fa-search"></i>
            Tìm kiếm
        </button>
    </div>
</div>
<div class="mt-4 mb-4" style="overflow-x: auto">
    <span ng-repeat="(key, value) in displayedFilters" class="mr-3">
        <span class="filter-option text-nowrap" ng-show="key != 'keyword'">
            @{{ filterLabels[key] + ': ' + value }}
            <a href="javascript:void(0)" ng-click="removeFilter(key)">
                <i class="fa fa-times-circle" aria-hidden="true"></i>
            </a>
        </span>
    </span>
</div>
