system.controller('PaginationController', PaginationController)

function PaginationController($scope, $http, $rootScope) {
    this.__proto__ = new BaseController($scope, $http, $rootScope);
    $scope.meta = {
        total_count: 0,
        page_size: 20,
        page_id: 0,
        has_next: false,
        page_count: 0
    };

    $scope.range = function (min, max) {
        var range = [];
        for (var i = min; i <= max; i++) {
            range.push(i);
        }

        return range;
    }

    $scope.buildPaginationUrl = function (url) {
        var hasParam = url.split('?')[1] !== undefined;
        if (!hasParam) {
            url += '?';
        } else {
            url += '&';
        }

        if ($scope.meta.page_id >= 0) {
            url += 'page_id=' + $scope.meta.page_id + '&page_size=' + $scope.meta.page_size;
        }

        return url;
    }

    $scope.increasePageId = function () {
        var checkChangePage = false;
        if ($scope.meta.page_id < $scope.meta.page_count - 1) {
            $scope.meta.page_id += 1;
            checkChangePage = true;
        }
        if (checkChangePage) {
            $(window).scrollTop(0);
        }

        return checkChangePage;
    }

    $scope.decreasePageId = function () {
        var checkChangePage = false;
        if ($scope.meta.page_id > 0) {
            $scope.meta.page_id -= 1;
            checkChangePage = true;
        }
        if (checkChangePage) {
            $(window).scrollTop(0);
        }

        return checkChangePage;
    }

    $scope.changePageId = function (page) {
        var checkChangePage = false;
        if ($scope.meta.page_id !== page - 1) {
            $scope.meta.page_id = page - 1;
            checkChangePage = true;
        }
        if (checkChangePage) {
            $(window).scrollTop(0);
        }

        return checkChangePage;
    }

    $scope.resetPageId = function () {
        $scope.meta.page_id = 0;
    }
}