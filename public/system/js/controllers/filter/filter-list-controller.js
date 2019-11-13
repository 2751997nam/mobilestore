system.controller("FilterListController", FilterListController);

function FilterListController($scope, $http, $rootScope, $window) {
    this.__proto__ = new PaginationController($scope, $http, $rootScope);
    $scope.items = [];
    $scope.keyword = '';
    $scope.filters = {};
    $scope.isFiltering = false;
    $scope.displayedFilters = {};
    $scope.initialize = function () {
        $scope.isFiltering = ($scope.getCacheFilter('flt_is_filtering') == 'true');
        var filters = $scope.getCacheFilter('flt_filter');
        if (filters == null) {
            $scope.fetchItems(
                $scope.buildUrl("/filter?embeds=values,categories&sorts=-created_at&page_size=20")
            );
        } else {
            $scope.filters = JSON.parse(filters);
            var meta = $scope.getCacheFilter('flt_meta');
            if (meta != null) {
                $scope.meta = JSON.parse(meta);
            }
            var displayedFilters = $scope.getCacheFilter('flt_filter_display');
            if (displayedFilters != null) {
                $scope.displayedFilters = JSON.parse(displayedFilters);
                if ($scope.displayedFilters.keyword) {
                    $scope.keyword = $scope.displayedFilters.keyword;
                }
            }
            if (typeof filters.name != 'undefined' && filters.name.value != 'undefined') {
                $scope.keyword = filters.name.value;
            }
            $scope.filterItems();
        }
    };

    $scope.fetchItems = function (url) {
        $http.get(url).then(function (response) {
            $scope.items = response.data.result;
            $scope.meta = response.data.meta;
        });
    };

    $scope.searchFilter = function() {
        if ($scope.keyword.length > 0) {
            $scope.isFiltering = true;
            $scope.filters.name = {
                operator: "~",
                value: $scope.keyword
            };
            $scope.displayedFilters.keyword = $scope.keyword;
        } else {
            if (typeof $scope.filters.name != 'undefined') {
                delete $scope.filters.name;
            }
            if (typeof $scope.displayedFilters.keyword != 'undefined') {
                delete $scope.displayedFilters.keyword;
            }
            if (Object.keys($scope.filters).length == 0) {
                $scope.isFiltering = false;
            }
        }
        $scope.filterItems();
    }

    $scope.buildFilterItemUrl = function () {
        var url = $scope.buildFilterUrl("/filter?embeds=values,categories");
        url = $scope.buildSortUrl(url);
        url = $scope.buildPaginationUrl(url);
        url = $scope.buildUrl(url);
        return url;
    };

    $scope.filterItems = function () {
        $scope.cacheFilter('flt_is_filtering', $scope.isFiltering);
        $scope.cacheFilter('flt_filter', JSON.stringify($scope.filters));
        $scope.cacheFilter('flt_meta', JSON.stringify($scope.meta));
        $scope.cacheFilter('flt_filter_display', JSON.stringify($scope.displayedFilters));
        var url = $scope.buildFilterItemUrl();
        $scope.fetchItems(url);
    };

    $scope.next = function () {
        if ($scope.increasePageId()) {
            $scope.filterItems();
        }
    };

    $scope.prev = function () {
        if ($scope.decreasePageId()) {
            $scope.filterItems();
        }
    };

    $scope.changePage = function (page) {
        if ($scope.changePageId(page)) {
            $scope.filterItems();
        }
    };

    $scope.removeAllFilters = function () {
        $scope.filters = {};
        $scope.displayedFilters = {};
        $scope.selectedFilter = "";
        $scope.isFiltering = false;
        $scope.keyword = "";

        $scope.filterItems();
    };

    $scope.deleteItem = function(idx, item) {
        if (typeof item.id != 'undefined' && item.id > 0) {
            var param = {
                title: 'Xóa bộ lọc',
                text: 'xóa bộ lọc này',
                arg: {id: item.id, idx: idx}
            };
            $scope.callConfirmModal(param, function(arg) {
                $http({
                    method: 'delete',
                    url: base_api_url + '/filter/' + arg.id
                }).then(function success(response) {
                    if (response.data.status == 'successful') {
                        $scope.items.splice(arg.idx, 1);
                        toastr.success('Xóa bộ lọc thành công');
                    } else {
                        toastr.error('Xóa sản bộ lọc thành công');
                    }
                });
            });
        }
    }

    $scope.getEditUrl = function (item) {
        return `/admin/filters/${item.id}`;
    }

    $scope.initialize();
}
