system.controller('UserController', UserController);

function UserController($scope, $http, $rootScope) {
    this.__proto__ = new BaseController($scope, $http, $rootScope);
    $scope.validationMessages = {};
    $scope.user = {
        email: ''
    };
    $scope.isSuperAdmin = false;

    $scope.users = [];

    $scope.initialize = function () {
        $scope.getUsers();
    }

    $scope.getUsers = function () {
        $http.get(SHOPBAY_VN_API_URL + '/user').then(function (response) {
            $scope.users = response.data;
            for (var i = 0; i < $scope.users.length; i++) {
                if ($scope.users[i].email == currentUserEmail && $scope.users[i].is_owner) {
                    $scope.isSuperAdmin = true;
                    break;
                }
            }
        });
    }

    $scope.validate = function () {
        let check = true;
        let keys = Object.keys($scope.user);
        for (let i = 0; i < keys.length; i++) {
            if(!$scope.user[keys[i]]) {
                $scope.validationMessages[keys[i]] = 'Không được bỏ trống trường này';
                check = false;
            }
        }

        return check;
    }

    $scope.save = function () {
        if ($scope.validate()) {
            $scope.callConfirmModal({
                title: 'Thêm quản trị viên',
                text: 'thêm quản trị viên'
            }, function () {
                $http.post(SHOPBAY_VN_API_URL + '/user', $scope.user)
                    .then(function () {
                        $scope.getUsers();
                        toastr.success("Thêm quản trị viên thành công.");
                        $('#modalAddUser').modal('toggle');
                        $scope.validationMessages.email = '';
                        $scope.user.email = '';
                    }).catch(function (error) {
                        $scope.validationMessages.email = error.data;
                    });
            })
        };
    }

    $scope.delete = function (email) {
        $scope.callConfirmModal({
            title: 'Xoá quản trị viên',
            text: 'xoá quản trị viên có email: ' + email
        }, function () {
            $http.post(SHOPBAY_VN_API_URL + '/user', {
                _method: "delete",
                email: email
            })
                .then(function (response) {
                    $scope.getUsers();
                    toastr.success(response.data);
                }).catch(function (error) {
                    toastr.error(error.data);
                });
        });
    }

    $scope.initialize();
}