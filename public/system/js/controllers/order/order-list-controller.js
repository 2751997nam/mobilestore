
system.controller('OrderListController', OrderListController);

function OrderListController($scope, $http, $rootScope) {
    this.__proto__ = new PaginationController($scope, $http, $rootScope);
    $scope.orders = [];
    $scope.isFiltering = false;
    $scope.search = '';
    $scope.statusFlow = {
        "PROCESSING":["PENDING","CANCELED"],
        "PENDING":["DELIVERING","CANCELED"],
        "DELIVERING":["FINISHED","RETURNED"],
        "FINISHED":["RETURNED"]
    };
    $scope.selectedChangeStatus = '';
    $scope.orderStatus = {
        'PROCESSING': 'Đang xử lý',
        'CANCELED': 'Đã huỷ',
        'PENDING': 'Chờ xuất hàng',
        'DELIVERING': 'Đang giao hàng',
        'FINISHED': 'Thành công',
        'RETURNED': 'Đã trả hàng'
    };

    $scope.orderActions = {
        'CANCELED': 'Huỷ',
        'PENDING': 'Chờ xuất hàng',
        'DELIVERING': 'Giao hàng',
        'FINISHED': 'Thành công',
        'RETURNED': 'Trả hàng'
    };
    $scope.filterOptions = {
        "": "-- Chọn bộ lọc --",
        'order.created_at': "Ngày tạo"
    };
    $scope.filterLabels = {
        'order.created_at': "Ngày tạo",
        'order.status': 'Trạng thái đơn hàng'
    };
    $scope.displayedFilters = {}
    $scope.filters = {};
    $scope.selectedFilter = ''
    $scope.baseUrl = '/api/order';
    $scope.locations = [];
    $scope.selectedProvinceId = '';
    $scope.selectedProvince = ''
    $scope.selectedDistrictId = '';
    $scope.minAmount = '';
    $scope.maxAmount = '';
    $scope.minDate = '';
    $scope.maxDate = '';
    
    $scope.initialize = function () {
        var filters = $scope.getCacheFilter('order_filter');
        var isFiltering = $scope.getCacheFilter('order_is_filtering');
        if (isFiltering == 'false' || !isFiltering) {
            isFiltering = false;
        }
        $scope.isFiltering = isFiltering;
        if (filters != null) {
            $scope.filters = JSON.parse(filters);
            var displayedFilters = $scope.getCacheFilter('order_filter_display');
            if (displayedFilters != null) {
                $scope.displayedFilters = JSON.parse(displayedFilters);
                if ($scope.displayedFilters.keyword) {
                    $scope.search = $scope.displayedFilters.keyword;
                }
            }
        }
        var meta = $scope.getCacheFilter('order_meta');
        if (meta != null) {
            $scope.meta = JSON.parse(meta);
        }
        $scope.filterOrders(false);
    }

    $scope.buildOrderUrl = function () {
        var url = $scope.buildFilterUrl($scope.baseUrl);
        url = $scope.buildPaginationUrl(url);
        url = $scope.buildUrl(url);
        return url;
    }

    $scope.fetchOrders = function (url) {
        $http.get(url)
            .then(function (response) {
                $scope.orders = response.data.result;
                for (var i = 0; i < $scope.orders.length; i++) {
                    var subTotal = 0;
                    subTotal = parseInt($scope.orders[i].amount) + parseInt($scope.orders[i].discount) - parseInt($scope.orders[i].tax) - parseInt($scope.orders[i].shipping_fee);
                    $scope.orders[i].subtotal = subTotal;
                }
                $scope.meta = response.data.meta;
            });
    }

    $scope.filterOrders = function (isResetPageId) {
        isResetPageId = isResetPageId === undefined ? true : isResetPageId;
        if (isResetPageId) {
            $scope.resetPageId();

        }
        $scope.cacheFilter('order_is_filtering', $scope.isFiltering);
        $scope.cacheFilter('order_filter', JSON.stringify($scope.filters));
        $scope.cacheFilter('order_meta', JSON.stringify($scope.meta));
        $scope.cacheFilter('order_filter_display', JSON.stringify($scope.displayedFilters));
        var url = $scope.buildOrderUrl();
        $scope.fetchOrders(url);
    }

    $scope.searchOrder = function () {
        if ($scope.search.length > 0) {
            $scope.isFiltering = 'search';
            $scope.filters.code = {
                operator: "=",
                value: $scope.search
            };
            $scope.displayedFilters.keyword = $scope.search;
        } else {
            delete $scope.filters.code;
            if ($scope.displayedFilters.keyword) {
                delete $scope.displayedFilters.keyword;
            }
            if (Object.keys($scope.filters).length == 0) {
                $scope.isFiltering = false;
            }
        }
        $scope.filterOrders();
    };

    $scope.searchWithoutFilter = function () {
        $scope.filters = {};
        $scope.displayedFilters = {};
        $scope.isFiltering = 'search';
        $scope.filters.code = {
            operator: "=",
            value: $scope.search
        };
        $scope.filterOrders();
    }

    $scope.next = function () {
        if ($scope.increasePageId()) {
            $scope.filterOrders(false);
        }
    };

    $scope.prev = function () {
        if ($scope.decreasePageId()) {
            $scope.filterOrders(false);
        }
    };

    $scope.changePage = function (page) {
        if ($scope.changePageId(page)) {
            $scope.filterOrders(false);
        }
    };

    $scope.getStatusClass = function (status)
    {
        var goodFlow = [
            'PROCESSING',
            'PENDING',
            'DELIVERING',
            'FINISHED',
        ];
        var cssClass = '';
        if (goodFlow.includes(status)) {
            cssClass = 'success';
        } else {
            cssClass = status === 'CANCELED' ? 'warning' : 'danger';
        }

        return cssClass;
    }

    $scope.changeStatus = function (order, status) {
        var title = 'Chuyển trạng thái';
        $scope.callConfirmModal({
            title: 'Chuyển trạng thái',
            text: 'chuyển trạng thái đơn hàng sang "' + $scope.orderStatus[status] + '"',
        }, function () {
            $http.patch($scope.buildUrl('/api/order') + '/' + order.id, {
                'status': status
            })
                .then(function (result) {
                    $scope.$applyAsync(function () {
                        order.status = status;
                    });
                    $scope.showSuccessModal(title);
                })
                .catch(function (error) {
                    $scope.showErrorModal(title);
                });
        });
    }

    $scope.addCreateDateFilter = function () {
        $scope.addFilterInRange($scope.minDate, $scope.maxDate, 'order.created_at', 'date');

        isFiltering = true;
    }

    $scope.resetSelectFilter = function () {
        $scope.selectedProvinceId = '';
        $scope.selectedProvince = ''
        $scope.selectedDistrictId = '';
        $scope.minAmount = '';
        $scope.maxAmount = '';
        $scope.minDate = '';
        $scope.maxDate = '';
    }

    $scope.addFilters = function ()
    {
        // $scope.addLocationFilter();
        // $scope.addAmountFilter();
        $scope.addCreateDateFilter();
        $scope.filterOrders();
        $scope.resetSelectFilter();
        $scope.isFiltering = true;

       $("#filter").removeClass("open");
    }

    $scope.removeFilter = function (key)
    {
        delete $scope.filters[key];
        delete $scope.displayedFilters[key];
        if (Object.keys($scope.filters).length == 0) {
            $scope.isFiltering = false;
        }
        $scope.filterOrders();
    }

    $scope.removeAllFilters = function () {
        $scope.filters = {};
        $scope.displayedFilters = {};
        $scope.selectedFilter = "";
        $scope.isFiltering = false;
        $scope.search = "";

        $scope.filterOrders();
    };

    $scope.addStatusFilter = function (type)
    {
        var filterStatus = "";
        switch (type) {
            case 'processing':
                filterStatus += "PROCESSING;PENDING;DELIVERING";
                $scope.displayedFilters['order.status'] = $scope.orderStatus['PROCESSING'] + ', ' +  $scope.orderStatus['PENDING'] + ', ' +  $scope.orderStatus['DELIVERING'];
                break;
            case 'canceled':
                filterStatus += "CANCELED;RETURNED";
                $scope.displayedFilters['order.status'] = $scope.orderStatus['CANCELED'] + ', ' +  $scope.orderStatus['RETURNED'];
                break;
            case 'success':
                filterStatus += "FINISHED";
                $scope.displayedFilters['order.status'] = $scope.orderStatus['FINISHED'];
                break;
        }
        $scope.filters['status'] = {
            operator: "=",
            value: filterStatus
        }
        $scope.isFiltering = type;

        $scope.filterOrders();
    }

    $scope.initialize();
}
