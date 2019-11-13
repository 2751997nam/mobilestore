system.controller('DomainController', DomainController);

function DomainController($scope, $http, $rootScope, $timeout) {
    $scope.baseController = this.__proto__ = new BaseController($scope, $http, $rootScope);
    $scope.domains = [];
    $scope.domain = '';
    $scope.primaryDomain;

    $scope.initialize = function () {
        $scope.getDomain();

        $('#addDomain').on('shown.bs.modal', function () {
            $("#domain").focus();
        });

        $('#addDomain').on('hidden.bs.modal', function () {
            $timeout(function () {
                $scope.domain = '';
            })
        });
    };

    $scope.datetimeFormat = function (timeObject) {
        return new Date(timeObject).toISOString();
    };

    $scope.checkDomain = function (domain) {
        var reg = /^(?!:\/\/)([a-zA-Z0-9-_]+\.)*[a-zA-Z0-9][a-zA-Z0-9-_]+\.[a-zA-Z]{2,11}?$/.test(domain);
        var len = (domain.length < 254);
        return (reg && len);
    };

    $scope.formatDomain = function () {
        $scope.domain = $scope.domain.replace(/https?:\/\//gi, '');
        if ($scope.domain.length > 254)
            toastr.error("Độ dài tên miền không hợp lệ!");
    };

    $scope.getDomain = function () {
        $http.get($scope.buildUrl('/domain'))
            .then(function (response) {
                $scope.domains = response.data.result;
            });
    };

    $scope.save = function () {
        let failCallback = function () {
            $scope.baseController.stopLoaddingButton('#btnSave');
            toastr.error("Có lỗi xảy ra! Tên miền không đúng hoặc đã tồn tại!");
        };

        let successCallback = function () {
            $scope.baseController.stopLoaddingButton('#btnSave');
            $("a[data-target='#instructionAddDomain']").trigger("click");
            $('#addDomain').modal('toggle');
            toastr.success("Thêm tiên miền thành công!");
            $scope.domain = '';
        };

        $scope.baseController.loaddingButton('#btnSave');
        $http({
            method: "POST",
            url: apiUrl + "/domain",
            data: {
                'domain': $scope.domain.toLowerCase(),
                'status': 'PENDING'
            }

        }).then(function (response) {
            if (response.data.status !== "fail") {
                success(response, successCallback);
            } else {
                fail(response, failCallback);
            }

        }, function (response) {
            fail(response, failCallback);
        })
    };

    $scope.delete = function (domain) {
        $scope.callConfirmDeleteModal({
            url: $scope.buildUrl("/domain/" + domain.id),
            title: 'Xóa tên miền',
            text: 'xoá ' + domain.domain,
            success: function (result) {
                $scope.getDomain();
            },
        });
    };

    $scope.changePrimaryDomain = function () {
        let primaryDomainId = $('#primaryDomain').val();

        let failCallback = function () {
            $scope.baseController.stopLoaddingButton('#btnChangePrimaryDomain');
            toastr.error("Cố lỗi xảy ra! không thể đổi tên miền chính");
        };

        let successCallback = function () {
            $scope.baseController.stopLoaddingButton('#btnChangePrimaryDomain');
            $('#changePrimaryDomain').modal('toggle');
            toastr.success("Thay đổi thành công!");
            $scope.domain = '';
        };

        $scope.baseController.loaddingButton('#btnChangePrimaryDomain');
        $http({
            method: "PUT",
            url: apiUrl + "/domain/change-primary",
            data: {
                'domainId': primaryDomainId
            }

        }).then(function (response) {
            if (response.data.status !== "fail") {
                success(response, successCallback);
            } else {
                fail(response, failCallback);
            }

        }, function (response) {
            fail(response, failCallback);
        })
    };

    const fail = function (response, callback = null) {
        $scope.loading = false;
        if (response.data.status === "fail") {
            if (callback) {
                callback();
            } else {
                toastr.error("Có lỗi xảy ra!");
            }

        }
    };

    const success = function (response, callback = null) {
        $scope.getDomain();

        if (callback) {
            callback();
        } else {
            toastr.success("Thành công!");
        }
    };

    $scope.initialize()
}