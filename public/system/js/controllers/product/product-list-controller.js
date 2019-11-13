system.controller("ProductListController", ProductListController);

function ProductListController($scope, $http, $rootScope) {
    this.__proto__ = new PaginationController($scope, $http, $rootScope);
    $scope.products = [];
    $scope.categories = [];
    $scope.search = "";
    $scope.brands = [];
    $scope.minPrice = "";
    $scope.maxPrice = "";
    $scope.filterOptions = {
        "": "-- Chọn bộ lọc --",
        'categories.category_id': "Danh mục",
        brand_id: "Thương hiệu",
        price: "Khoảng giá"
    };
    $scope.displayedFilters = {};
    $scope.filters = {};
    $scope.newFilters = {};
    $scope.selectedFilter = "";
    $scope.selectedProducts = [];
    $scope.isFiltering = false;
    $scope.showActions = false;
    $scope.categoryFilter = '';
    $scope.initialize = function () {
        $scope.isFiltering = ($scope.getCacheFilter('product_is_filtering') == 'true');
        var filters = $scope.getCacheFilter('product_filter');
        if (filters == null) {
            $scope.fetchProducts(
                $scope.buildUrl("/product?embeds=brand,categories&page_size=20")
            );
        } else {
            $scope.filters = JSON.parse(filters);
            var displayedFilters = $scope.getCacheFilter('product_filter_display');
            if (displayedFilters != null) {
                $scope.displayedFilters = JSON.parse(displayedFilters);
                if ($scope.displayedFilters.keyword) {
                    $scope.search = $scope.displayedFilters.keyword;
                }
            }
            var meta = $scope.getCacheFilter('product_meta');
            if (meta != null) {
                $scope.meta = JSON.parse(meta);
            }
            $scope.filterProducts();
        }
        $http.get($scope.buildUrl("/brand?fields=id,name")).then(function (response) {
            $scope.brands = response.data.result;
        });
        $http.get($scope.buildUrl('/category?fields=id,name&filters=type=PRODUCT')).then(function (response) {
            $scope.categories = response.data.result;
        })
    };

    $scope.fetchProducts = function (url) {
        return $http.get(url).then(function (response) {
            $scope.products = response.data.result;
            $scope.meta = response.data.meta;
        });
    };

    $scope.searchProduct = function () {
        if ($scope.search.length > 0) {
            $scope.isFiltering = true;
             // $scope.filters.q = $scope.search;
            // $scope.filters.raw = {
            //     operator: "=",
            //     value: 'MATCH(`name`) AGAINST("' + $scope.search +'") or `name` like "%' + $scope.search + '%" or `sku` like "' + $scope.search + '%"'
            // };
            $scope.displayedFilters.keyword = $scope.search;
        } else {
            if (typeof $scope.filters.raw != 'undefined') {
                delete $scope.filters.raw;
            }
            if (typeof $scope.displayedFilters.keyword != 'undefined') {
                delete $scope.displayedFilters.keyword;
            }
            delete $scope.filters.name;
            if (Object.keys($scope.filters).length == 0) {
                $scope.isFiltering = false;
            }
        }
        $scope.filterProducts();
    };

    $scope.buildProductUrl = function () {
        var url = $scope.buildFilterUrl("/product?embeds=brand,categories");
        url = $scope.buildSortUrl(url);
        url = $scope.buildPaginationUrl(url);
        url = $scope.buildUrl(url);
        if ($scope.search) {
            url += "&q=" + $scope.search;
        }
        return url;
    };

    $scope.filterProducts = function (not_refresh_page_id) {
        not_refresh_page_id = not_refresh_page_id || false;
        if (!not_refresh_page_id) {
            $scope.meta.page_id = 0;
        }
        $scope.cacheFilter('product_is_filtering', $scope.isFiltering);
        $scope.cacheFilter('product_filter', JSON.stringify($scope.filters));
        $scope.cacheFilter('product_filter_display', JSON.stringify($scope.displayedFilters));
        $scope.cacheFilter('product_meta', JSON.stringify($scope.meta));
        var url = $scope.buildProductUrl();
        var result = $scope.fetchProducts(url);
        result.then(function () {
            $scope.$$postDigest(function () {
                $scope.resetSelectedProducts();
            });
        })
    };

    $scope.next = function () {
        if ($scope.increasePageId()) {
            $scope.filterProducts(true);
        }
    };

    $scope.prev = function () {
        if ($scope.decreasePageId()) {
            $scope.filterProducts(true);
        }
    };

    $scope.changePage = function (page) {
        if ($scope.changePageId(page)) {
            $scope.filterProducts(true);
            $scope.resetSelectedProducts();
        }
    };

    $scope.resetOrCheckAll = function () {
        var element = $('#js-reset-or-check-all')[0];
        if (element.checked) {
            $scope.selectAllProducts();
        } else {
            $scope.resetSelectedProducts();
        }
    }

    $scope.resetSelectedProducts = function () {
        $scope.selectedProducts.length = 0;
        var checkAll = $('#js-product-checkall')[0];
        checkAll.checked = false;
        var elements = $('.js-product-checkbox');
        for (var i = 0; i < elements.length; i++) {
            elements[i].checked = false;
        }

        $('#js-reset-or-check-all')[0].checked = false;
        $('.product-item').removeClass('product-selected');
        $('#js-table-head').css('opacity', 1);
    }

    $scope.sortProduct = function (field) {
        $scope.sorts.field = field;
        if ($scope.sorts.type === "asc") {
            $scope.sorts.type = "desc";
        } else {
            if ($scope.sorts.type === "desc") {
                $scope.sorts.field = "";
                $scope.sorts.type = "";
            } else {
                $scope.sorts.type = "asc";
            }
        }

        $scope.filterProducts();
    };

    $scope.addPriceFilter = function () {
        $scope.addFilterInRange($scope.minPrice, $scope.maxPrice, 'price', 'number');

        isFiltering = true;
    };

    $scope.findFilterValueName = function (type, value) {
        var name = "";
        var objects = [];
        switch (type) {
            case 'brand_id':
                objects = $scope.brands;
                break;
            case 'category':
                objects = $scope.categories;
                break;
            default:
                break;
        }
        for (index in objects) {
            if (objects[index].id == value) {
                name = objects[index].name;
            }
        }

        return name;
    }

    $scope.addCategoryFilter = function () {
        if ($scope.categoryFilter !== '') {
            $scope.filters['categories.category_id'] = $scope.categoryFilter;
            $scope.displayedFilters['categories.category_id'] = $scope.findFilterValueName('category', $scope.categoryFilter);
        }
    }

    $scope.addBrandFilter = function () {
        if ('brand_id' in $scope.filters) {
            $scope.displayedFilters.brand_id = $scope.findFilterValueName('brand_id', $scope.filters['brand_id']);
        }
    }

    $scope.addFilters = function () {
        let keys = Object.keys($scope.newFilters);
        keys.forEach(function (key) {
            $scope.filters[key] = $scope.newFilters[key];
        });
        $scope.addPriceFilter();
        $scope.addBrandFilter();
        $scope.addCategoryFilter();
        $scope.filterProducts();
        $scope.isFiltering = true;
        // $scope.refresh();
        // $scope.selectedFilter = '';

       $("#filter").removeClass("open");
    };

    $scope.removeFilter = function (field) {
        delete $scope.filters[field];
        delete $scope.displayedFilters[field];
        if (Object.keys($scope.filters).length === 0) {
            $scope.selectedFilter = '';
            $scope.categoryFilter = '';
            $scope.isFiltering = false;
        }

        $scope.filterProducts();
    };

    $scope.removeAllFilters = function () {
        $scope.filters = {};
        $scope.displayedFilters = {};
        $scope.selectedFilter = "";
        $scope.isFiltering = false;
        $scope.search = "";

        $scope.filterProducts();
    };

    $scope.addSelectedProducts = function (id) {
        console.log(id);
        $('#js-product-checkall')[0].checked = false;
        var index = $scope.selectedProducts.indexOf(id);
        if (index === -1) {
            $scope.selectedProducts.push(id);
        } else {
            $scope.selectedProducts.splice(index, 1);
        }
        var checkAll = $('#js-reset-or-check-all')[0];
        if ($scope.selectedProducts.length < $scope.products.length) {
            checkAll.checked = false;
        }
        if ($scope.selectedProducts.length === $scope.products.length) {
            checkAll.checked = true;
        }

        $('.product-item').removeClass('product-selected');

        var elements = $('.js-product-checkbox:checked');
        if (elements.length > 0) {
            $('#js-table-head').css('opacity', 0);
            for (var i = 0; i < elements.length; i++) {
                $(elements[i]).parents('tr').addClass('product-selected');
            }
        } else {
            $('#js-table-head').css('opacity', 1);
        }

        return false;
    };

    $scope.selectAllProducts = function () {
        console.log('check All');
        $('#js-reset-or-check-all')[0].checked = true;
        var checkAll = $('#js-product-checkall')[0];
        $scope.selectedProducts.length = 0;
        // if (checkAll.checked == true) {
            for (item in $scope.products) {
                $scope.selectedProducts.push($scope.products[item].id);
            }
            var elements = $('.js-product-checkbox');
            for (var i = 0; i < elements.length; i++) {
                elements[i].checked = true;
            }
        // }
        $('.product-item').addClass('product-selected');
        $('#js-table-head').css('opacity', 0);
    };

    $scope.deleteProduct = function (index) {
        $scope.resetSelectedProducts();
        if (index in $scope.products) {
            var product = $scope.products[index];
            var title = 'Xoá sản phẩm';
            $scope.callConfirmDeleteModal({
                url: $scope.buildUrl("/product/" + product.id),
                title: title,
                text: 'xoá ' + product.name,
                success: function (result) {
                    $scope.filterProducts();
                },
            });
        }
    };

    $scope.deleteProducts = function () {
        var url = $scope.buildDeleteUrl('/product', $scope.selectedProducts);
        $scope.callConfirmDeleteModal({
            url: url,
            title: 'xoá sản phẩm',
            text: 'xoá ' + $scope.selectedProducts.length + ' sản phẩm',
            success: function (result) {
                $scope.filterProducts();
                $scope.resetSelectedProducts();
            },
        });
    }

    $(document).on('click', function (event) {
        if(!$(event.currentTarget).hasClass('js-show-actions')) {
            $scope.showActions = false;
        } else {
            $scope.showActions = !$scope.showActions;
        }
    });

    $scope.refresh = function () {
        $scope.categoryFilter = '';
        $scope.minPrice = '';
        $scope.maxPrice = '';
        $scope.newFilters = {};
    }

    $scope.changeStatus = function(status) {
        if ($scope.selectedProducts.length > 0) {
            var url = $scope.buildUrl('/product/change-status');
            var text = 'ẩn ' + $scope.selectedProducts.length + ' sản phẩm';
            var title = 'Ẩn sản phẩm';
            if (status == 'Active') {
                text = 'hiện ' + $scope.selectedProducts.length + ' sản phẩm';
                title = 'Hiện sản phẩm';
            }
            $scope.callConfirmModal({
                title: title,
                text: text
            }, function() {
                $http.post(url, {data: $scope.selectedProducts, status: status})
                    .then(function success(response) {
                        if (response.data.status == 'successful') {
                            $scope.showSuccessModal('Đổi trạng thái');
                        } else {
                            $scope.showErrorModal(status == 'Active' ? 'hiện' : 'ẩn', response.data.message);
                        }
                    }, function error() {
                        $scope.showErrorModal('Lỗi! Vui lòng thử lại');
                    })
            })
        }
    }

    $scope.initialize();
}
