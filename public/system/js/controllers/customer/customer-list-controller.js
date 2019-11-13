system.controller("CustomerListController", CustomerListController);

function CustomerListController($scope, $http, $rootScope){
    this.__proto__ = new BaseController($scope, $http, $rootScope);
    this.__proto__ = new PaginationController($scope, $http, $rootScope);
    $scope.customers = [];
    $scope.filter = "Khách hàng mới nhất";
    $scope.searchFilter = "";
    $scope.selectedCustomers = [];
    $scope.selectedAll = false;
    $scope.controllerName = "CustomerListController";
    $scope.sorts = [
        {'key': '-created_at', 'name': 'Khách hàng mới nhất'},
        {'key': '-updated_at', 'name': 'Cập nhật gần đây'},
        {'key': 'order', 'name': 'Nhiều đơn nhất'},
        {'key': 'spent', 'name': 'Chi nhiều tiền nhất'},
        {'key': 'full_name', 'name': 'Theo tên (A-Z)'}
    ];
    function initialize() {
        $scope.filter = "Khách hàng mới nhất";
        $scope.searchFilter = "";
        $scope.selectedCustomers = [];
        var sortBy = $scope.getCacheFilter('customer_sort');
        var searchFilter = $scope.getCacheFilter('customer_search_filter');
        var meta = $scope.getCacheFilter('customer_meta');
        if (meta != null) {
            $scope.meta = JSON.parse(meta);
        }
        if (sortBy != null && searchFilter != null) {
            var filter = 'Khách hàng mới nhất';
            $scope.sorts.forEach(function (sort) {
                if (sortBy == sort.key) {
                    filter = sort.name;
                }
            });
            $scope.filter = filter;
            $scope.searchFilter = searchFilter;
            $scope.find(sortBy, searchFilter);
        } else {
            $scope.find();
        }

    }

    $scope.find = (sortBy = "-created_at", searchFilter = "", reset = false) => {
        $scope.customers = [];
        var url = $scope.buildUrl('/customers');
        if ( searchFilter == "" ) {
            url += '?sorts=' + sortBy;
        } else {
            url += '?search=' + searchFilter + '&sorts=' + sortBy ;
        }
        if ( !reset ) {
            url = $scope.buildPaginationUrl(url);
        }
        if ( !url.includes('page_id') ) {
            url += '&page_id=0&page_size=20';
        }
        $scope.cacheFilter('customer_meta', JSON.stringify($scope.meta));
        $http.get(url).then( function(response) {
            if ( response.data.status == 'successful' ) {
                $scope.customers = response.data.result;
                $scope.customers.forEach(function(item) {
                    if (item.orders != null) {
                        item.amount = sumOrderAmount(item.orders);
                        item.total = item.orders.length;
                    }
                    item.selected = false;
                })
                $scope.meta = response.data.meta;
            }
        });
    }

    $scope.next = function () {
        if ($scope.increasePageId()) {
            $scope.searchByFilter();
        }
    };

    $scope.prev = function () {
        if ($scope.decreasePageId()) {
            $scope.searchByFilter();
        }
    };

    $scope.changePage = function (page) {
        if ($scope.changePageId(page)) {
            $scope.searchByFilter();
        }
    };

    function sumOrderAmount(arr) {
        var sum = 0;
        arr.forEach(function(item) {
            sum += parseInt(item.amount);
        })
        return sum;
    }

    $scope.reset = () => {
        $scope.filter = "";
        $scope.searchFilter = "";
        $scope.selectedCustomers = [];
        $scope.selectedAll = false;
        $scope.find();
    }

    $scope.searchByFilter = () => {
        switch ($scope.filter) {
            case 'Khách hàng mới nhất' :
                sortBy = '-created_at';
                break;
            case 'Cập nhật gần đây' :
                sortBy = '-updated_at';
                break;
            case 'Nhiều đơn nhất' :
                sortBy = 'order';
                break;
            case 'Chi nhiều tiền nhất' :
                sortBy = 'spent';
                break;
            case 'Theo tên (A-Z)' :
                sortBy = 'full_name';
                break;
            default :
                sortBy = '-created_at';
                break;
        }
        $scope.cacheFilter('customer_sort', sortBy);
        $scope.cacheFilter('customer_search_filter', $scope.searchFilter);
        $scope.cacheFilter('customer_meta', JSON.stringify($scope.meta));
        $scope.find(sortBy, $scope.searchFilter);
    }

    $scope.selectAllCustomers = function () {
        if ( !$scope.selectedAll ) {
            $scope.selectedCustomers = [];
            $scope.customers.forEach(function(item) {
                item.selected = true;
                $scope.selectedCustomers.push(item.id);
            })
        } else {
            $scope.selectedCustomers = [];
            $scope.customers.forEach(function(item) {
                item.selected = false;
            })
        }
    };

    $scope.selectCustomer = (item) => {
        if ( !item.selected ) {
            $scope.selectedCustomers.push(item.id)
        } else {
            var index = $scope.selectedCustomers.indexOf(item.id);
            $scope.selectedCustomers.splice(index,1);
        }
    }

    $scope.deleteCustomer = function (index) {
        if (index in $scope.customers) {
            var customer = $scope.customers[index];
            var title = 'Xoá khách hàng';
            $scope.callConfirmDeleteModal({
                url: $scope.buildUrl("/customer/" + customer.id),
                title: title,
                text: 'xoá ' + customer.full_name,
                success: function (result) {
                    $scope.reset();
                },
            });
        }
    };

    $scope.deleteListCustomers = function () {
        if ( $scope.selectedCustomers.length <= 0 ) {
            alert('Vui lòng chọn sản phẩm');
            return;
        }
        var url = $scope.buildDeleteUrl('/customer', $scope.selectedCustomers);
        $scope.callConfirmDeleteModal({
            url: url,
            title: 'xoá khách hàng',
            text: 'xoá ' + $scope.selectedCustomers.length + ' khách hàng',
            success: function (result) {
                $scope.reset();
            },
        });
    }

    $scope.search = () => {
        $scope.filter = "Khách hàng mới nhất";
        $scope.cacheFilter('customer_sort', '-created_at');
        $scope.cacheFilter('customer_search_filter', $scope.searchFilter);
        $scope.cacheFilter('customer_meta', JSON.stringify($scope.meta));
        $scope.find("-created_at", $scope.searchFilter, true);
    }

    $scope.getEditUrl = function (item) {
        return `/admin/customers/${ item.id }`;
    }

    initialize();
}
