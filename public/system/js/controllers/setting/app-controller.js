system.controller('AppController', AppController);

function AppController($scope, $http, $rootScope, $timeout) {
    $scope.baseController = this.__proto__ = new BaseController($scope, $http, $rootScope);
    $scope.apps = [];

    $scope.initialize = function () {
        $scope.getApps();

    };

    $scope.datetimeFormat = function (timeObject) {
        return new Date(timeObject).toISOString();
    };

    $scope.getApps = function () {
        $http.get($scope.buildUrl('/app/all'))
            .then(function (response) {
                $scope.apps = response.data.result;
            });
    };

    $scope.remove = function (app) {
        $scope.callConfirmDeleteModal({
            url: "/module/remove/" + app.name_space,
            title: 'Xóa ứng dụng',
            text: 'xoá ' + app.name,
            success: function (result) {
                $scope.getApps();
            }
        });
    };

    $scope.disable = function (app) {
        $scope.callConfirmUpdateModal({
            url: "/module/toggle/" + app.name_space,
            data: {status: 'disable'},
            title: 'Vô hiệu ứng dụng',
            text: 'Tắt ' + app.name,
            success: function (result) {
                $scope.getApps();
            }
        });
    };

    $scope.enable = function (app) {
        $scope.callConfirmUpdateModal({
            url: "/module/toggle/" + app.name_space,
            data: {status: 'enable'},
            title: 'Kích hoạt ứng dụng',
            text: 'Bật ' + app.name,
            success: function (result) {
                $scope.getApps();
            }
        });
    };

    $scope.initialize()
}