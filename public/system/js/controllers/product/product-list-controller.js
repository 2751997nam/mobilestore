system.controller("ProductListController", ProductListController);

function ProductListController($scope, $http, $rootScope) {
    this.__proto__ = new PaginationController($scope, $http, $rootScope);
    $scope.products = [];
    $scope.categories = [];
    $scope.search = "";
    $scope.brands = [];
    $scope.minPrice = "";
    $scope.maxPrice = "";
    $scope.search = '';
    $scope.filterOptions = {
        "": "-- Chọn bộ lọc --",
        'categories.category_id': "Danh mục",
        brand_id: "Thương hiệu",
        price: "Khoảng giá"
    };

    $scope.initialize = function ()
    {
        $scope.getProducts();
    }

    $scope.buildPainationUrl = function (url)
    {
        return url + '?page_id=' + $scope.meta.page_id + '&page_size=' + $scope.meta.page_size;
    }

    $scope.getProducts = function ()
    {
        let url = '/api/product';
        url = $scope.buildPainationUrl(url);
        $http.get(url).then(function (response) {
            if (response.data.status == 'successful') {
                $scope.products = response.data.result;
                $scope.meta = response.data.meta;
            }
        });
    }

    $scope.next = function () {
        $scope.increasePageId();
        $scope.getProducts();
    }

    $scope.prev = function () {
        $scope.decreasePageId();
        $scope.getProducts();
    }

    $scope.changePage = function (page) {
        $scope.changePageId(page);
        $scope.getProducts();
    }

    $scope.initialize();
}
