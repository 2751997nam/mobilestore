system.controller("PostListController", PostListController);

function PostListController($scope, $http, $rootScope) {
    this.__proto__ = new PaginationController($scope, $http, $rootScope);

    $scope.posts = [];

    $scope.posts = [];
    $scope.categories = [];
    $scope.search = "";
    $scope.brands = [];
    $scope.minPrice = "";
    $scope.maxPrice = "";
    $scope.filterOptions = {
        "": "-- Chọn bộ lọc --",
        'category_id': "Danh mục",
    };
    $scope.filters = {};
    $scope.displayedFilters = {};
    $scope.selectedFilter = "";
    $scope.selectedPosts = [];
    $scope.isFiltering = false;
    $scope.showActions = false;
    $scope.categoryFilter = '';
    $scope.initialize = function () {
        $scope.isFiltering = ($scope.getCacheFilter('post_is_filtering') == 'true');
        var filters = $scope.getCacheFilter('post_filter');
        if (filters != null){
            $scope.filters = JSON.parse(filters);
            var displayedFilters = $scope.getCacheFilter('post_filter_display');
            if (displayedFilters.length != 0 ){
                $scope.displayedFilters = JSON.parse(displayedFilters);
                if ($scope.displayedFilters.keyword) {
                    $scope.search = $scope.displayedFilters.keyword;
                } 
            }
        }
        var meta = $scope.getCacheFilter('post_meta');
        if (meta != null) {
            $scope.meta = JSON.parse(meta);
        }
        $scope.filterPosts();
        $http.get($scope.buildUrl('/post-category')).then(function (response) {
            $scope.categories = response.data;
        })
    };

    $scope.fetchPosts = function (url) {
        $http.get(url).then(function (response) {
            $scope.posts = response.data.result;
            $scope.meta = response.data.meta;
        });
    };

    $scope.searchPost = function () {
        if ($scope.search.length > 0) {
            $scope.isFiltering = true;
            $scope.filters.name = {
                operator: "~",
                value: $scope.search
            };
            $scope.displayedFilters.keyword = $scope.search;
        } else {
            delete $scope.filters.name;
            if (typeof $scope.displayedFilters.keyword != 'undefined') {
                delete $scope.displayedFilters.keyword;
            }
            if (Object.keys($scope.filters).length == 0) {
                $scope.isFiltering = false;
            }
        }
        $scope.filterPosts();
    };

    $scope.buildPostUrl = function () {
        $scope.cacheFilter('post_is_filtering', $scope.isFiltering);
        $scope.cacheFilter('post_filter', JSON.stringify($scope.filters));
        $scope.cacheFilter('post_filter_display', JSON.stringify($scope.displayedFilters));
        $scope.cacheFilter('post_meta', JSON.stringify($scope.meta));
        var url = $scope.buildFilterUrl("/post?embeds=category");
        url = $scope.buildSortUrl(url);
        url = $scope.buildPaginationUrl(url);
        url = $scope.buildUrl(url);

        return url;
    };

    $scope.filterPosts = function () {
        var url = $scope.buildPostUrl('/post?embeds=category');
        $scope.fetchPosts(url);
        $scope.resetSelectedPosts();
    };

    $scope.next = function () {
        if ($scope.increasePageId()) {
            $scope.filterPosts();
        }
    };

    $scope.prev = function () {
        if ($scope.decreasePageId()) {
            $scope.filterPosts();
        }
    };

    $scope.changePage = function (page) {
        if ($scope.changePageId(page)) {
            $scope.filterPosts();
            $scope.resetSelectedPosts();
        }
    };

    $scope.resetOrCheckAll = function () {
        var element = $('#js-reset-check-all')[0];
        if (element.checked) {
            $scope.selectAllPosts();
        } else {
            $scope.resetSelectedPosts();
        }
    }

    $scope.resetSelectedPosts = function () {
        $scope.selectedPosts.length = 0;
        var checkAll = $('#js-post-checkall')[0];
        checkAll.checked = false;
        var elements = $('.js-post-checkbox');
        for (var i = 0; i < elements.length; i++) {
            elements[i].checked = false;
        }
        $('#js-reset-check-all')[0].checked = false;
        $('.post-item').removeClass('post-selected');
    }

    $scope.sortPost = function (field) {
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

        $scope.filterPosts();
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
            $scope.filters['category_id'] = $scope.categoryFilter;
            $scope.displayedFilters['category_id'] = $scope.findFilterValueName('category', $scope.categoryFilter);
        }
    }

    $scope.addBrandFilter = function () {
        if ('brand_id' in $scope.filters) {
            $scope.displayedFilters.brand_id = $scope.findFilterValueName('brand_id', $scope.filters['brand_id']);
        }
    }

    $scope.addFilters = function () {
        $scope.addPriceFilter();
        $scope.addBrandFilter();
        $scope.addCategoryFilter();
        $scope.filterPosts();
        $scope.isFiltering = true;

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
        $scope.filterPosts();
    };

    $scope.removeAllFilters = function () {
        $scope.filters = [];
        $scope.displayedFilters = {};
        $scope.selectedFilter = "";
        $scope.isFiltering = false;
        $scope.search = "";

        $scope.filterPosts();
    };

    $scope.addSelectedPosts = function (id) {
        var index = $scope.selectedPosts.indexOf(id);
        if (index === -1) {
            $scope.selectedPosts.push(id);
        } else {
            $scope.selectedPosts.splice(index, 1);
        }
        var checkAll = $('#js-post-checkall')[0];
        var resetCheckAll = $('#js-reset-check-all')[0];
        if ($scope.selectedPosts.length < $scope.posts.length) {
            checkAll.checked = false;
            resetCheckAll.checked = false;
        }
        if ($scope.selectedPosts.length === $scope.posts.length) {
            checkAll.checked = true;
            resetCheckAll.checked = true;
        }

        $('.post-item').removeClass('post-selected');

        var elements = $('.js-post-checkbox:checked');
        for (var i = 0; i < elements.length; i++) {
            $(elements[i]).parents('tr').addClass('post-selected');
        }
    };

    $scope.selectAllPosts = function () {
        var checkAll = $('#js-post-checkall')[0];
        $('#js-reset-check-all')[0].checked = true;
        $scope.selectedPosts.length = 0;
        // if (checkAll.checked == true) {
            for (item in $scope.posts) {
                $scope.selectedPosts.push($scope.posts[item].id);
            }
            var elements = $('.js-post-checkbox');
            for (var i = 0; i < elements.length; i++) {
                elements[i].checked = true;
            }
        // }
        $('.post-item').addClass('post-selected');
    };

    $scope.deletePost = function (index) {
        $scope.resetSelectedPosts();
        if (index in $scope.posts) {
            var post = $scope.posts[index];
            var title = 'Xoá bài viết';
            $scope.callConfirmDeleteModal({
                url: $scope.buildUrl("/post/" + post.id),
                title: title,
                text: 'xoá ' + post.name,
                success: function (result) {
                    $scope.filterPosts();
                },
            });
        }
    };

    $scope.deletePosts = function () {
        var url = $scope.buildDeleteUrl('/post', $scope.selectedPosts);
        $scope.callConfirmDeleteModal({
            url: url,
            title: 'xoá bài viết',
            text: 'xoá ' + $scope.selectedPosts.length + ' bài viết',
            success: function (result) {
                $scope.filterPosts();
                $scope.resetSelectedPosts();
            },
        });
    }

    $scope.initialize();
}
