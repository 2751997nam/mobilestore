system.controller("BrandController", BrandController);

function BrandController($scope, $http, $rootScope, $timeout, Upload, $q) {
    $scope.baseController = this.__proto__ = new BaseController($scope, $http, $rootScope, Upload);

    const UPDATE_MODE = 'update';
    const CREATE_MODE = 'create';

    $scope.controllerName = "BrandController";
    $scope.brands = [];
    $scope.currentBrand = {};
    $scope.mode = CREATE_MODE;

    function initialize() {
        $scope.getBrand();

        $scope.baseController.addListenHiddenModal('#modalAddBrand', () =>
            $timeout(function () {
                $scope.reset();
                $scope.loading = false;
                $('#holder').removeAttr('src');
            })
        );

        $('#modalAddBrand').on('shown.bs.modal', function () {
            $("#brandName").focus();
        });

    }

    $scope.openBrand = function (brand) {
        $scope.mode = UPDATE_MODE;
        $scope.currentBrand = angular.copy(brand);
        $scope.currentBrand.is_display_home_page = $scope.currentBrand.is_display_home_page ? true : false;
        $scope.baseController.openDialogModal('#modalAddBrand');
    };

    $scope.getBrand = function () {
        $http.get(apiUrl + '/api/brand')
            .then(function (response) {
                $scope.brands = response.data.result;
            });
    };

    $scope.reset = function () {
        delete $scope.currentBrand;
        $scope.currentBrand = {};
        $scope.currentBrand.is_hidden = 0;
        $scope.mode = CREATE_MODE;

    }

    const fail = function (response, btnSelector = null) {
        $scope.loading = false;
        if (!btnSelector)
            btnSelector = ($scope.mode === UPDATE_MODE) ? "#btnSave" : "#btnUpdate";

        if (response.data.status === "fail") {
            toastr.error(response.data.message);
            $scope.baseController.stopLoaddingButton(btnSelector);
        }
    };

    $scope.delete = function (brand) {
        //$scope.baseController.openDialogModal('#modalDeleteBrand');

        $scope.callConfirmDeleteModal({
            url: apiUrl + "/api/brand/" + brand.id,
            title: "Xóa thương hiệu",
            text: 'xoá ' + brand.name,
            success: function (result) {
                $scope.getBrand();
                $scope.reset();
            },
        });

    };

    $scope.save = function () {
        let btnSelector = ($scope.mode === UPDATE_MODE) ? "#btnSave" : "#btnUpdate";

        if ($scope.loading === true) {
            toastr.warning('Bạn đang thao tác quá nhanh!');
            $scope.baseController.stopLoaddingButton(btnSelector);
            return;
        }

        $scope.baseController.loaddingButton(btnSelector);
        $scope.loading = true;

        const success = function (response) {
            toastr.success(response.data.message);

            $scope.getBrand();
            $scope.reset();

            $scope.baseController.stopLoaddingButton(btnSelector);
            $('#modalAddBrand').modal('toggle');
        };

        $http({
            method: ($scope.mode === UPDATE_MODE) ? "PUT" : "POST",
            url: apiUrl + "/api/brand" + (($scope.mode === UPDATE_MODE) ? "/" + $scope.currentBrand.id : ''),
            data: $scope.currentBrand

        }).then(function (response) {
            if (response.data.status !== "fail") {
                success(response);
            } else {
                fail(response);
            }

        }, fail)

    };

    $scope.uploadImage = async function (file) {
        if (file) {
            var image = await upload([file]);
            if (image) {
                $scope.$applyAsync(function () {
                    $scope.currentBrand.image_url = image[0];
                });
            }
        }
    }

    function upload(files)
    {
        var formData = new FormData();
        files.forEach(function (element) {
            formData.append('images[]', element);
        });

        return new Promise(function (resolve, reject) {
            $http.post('/api/brand/upload-image', formData, {
                transformRequest: angular.identity,
                headers: {'Content-Type': undefined}
            })
                .then(function (response) {
                    if (response.data.status == 'successful') {
                        resolve(response.data.result);
                    } else {
                        reject([]);
                    }
                }).catch(function (response) {
                    reject([]);
                });
        });
    }


    initialize();
}
