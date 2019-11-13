system.controller("CategoryController", CategoryController);

function CategoryController($scope, $http, $rootScope, $timeout, Upload, $q) {
    $scope.baseController = this.__proto__ = new BaseController($scope, $http, $rootScope, Upload);

    const UPDATE_MODE = 'update';
    const CREATE_MODE = 'create';

    $scope.controllerName = "CategoryController";
    $scope.categories = [];
    $scope.currentCategory = {};
    $scope.mode = CREATE_MODE;
    $scope.TYPE = ['PRODUCT', 'POST'];
    $scope.categoryType = categoryType;


    function initialize() {
        $scope.getCategory();

        $scope.baseController.addListenHiddenModal('#modalAddCategory', () =>
            $timeout(function () {
                $scope.reset();
                $scope.loading = false;
                $('#holder').removeAttr('src');
            })
        );

        $('#modalAddCategory').on('shown.bs.modal', function () {
            $("#categoryName").focus();
        });

    }

    $scope.openCategory = function (category) {
        $scope.mode = UPDATE_MODE;
        $scope.currentCategory = angular.copy(category);
        $scope.currentCategory.is_display_home_page = $scope.currentCategory.is_display_home_page ? true : false;
        $scope.baseController.openDialogModal('#modalAddCategory');

    };

    $scope.getCategory = function () {
        $http.get(apiUrl + '/category/tree?type=' + $scope.categoryType)
            .then(function (response) {
                $scope.categories = response.data.result;
            });
    };

    $scope.reset = function () {
        delete $scope.currentCategory;
        $scope.currentCategory = {};
        $scope.currentCategory.is_hidden = 0;
        $scope.mode = CREATE_MODE;

    }

    const fail = function (response, btnSelector = null) {
        $scope.loading = false;
        if (!btnSelector)
            btnSelector = ($scope.mode === UPDATE_MODE) ? "#btnSave" : "#btnUpdate";

        if (response.data.status === "fail") {
            let textErr = '';
            Object.keys(response.data.result).forEach((item) => {
                textErr += response.data.result[item].join(', ');
            });
            toastr.error(response.data.message + textErr);
            $scope.baseController.stopLoaddingButton(btnSelector);
        }
    };

    $scope.delete = function (category) {
        //$scope.baseController.openDialogModal('#modalDeleteCategory');

        $scope.callConfirmDeleteModal({
            url: apiUrl + "/category/" + category.id,
            title: "Xóa danh mục",
            text: 'xoá ' + category.name,
            success: function (result) {
                $scope.getCategory();
                $scope.reset();
            },
        });

        // $scope.deleteId = category.id;
    };

    /*$scope.doDelete = function(){
        $scope.baseController.loaddingButton('#doDelete');

        const success = function(response) {
            toastr.success('#' + response.data.result.id + '. ' + response.data.message);

            $scope.getCategory();
            $scope.reset();

            $scope.baseController.stopLoaddingButton('#doDelete');
            $('#modalDeleteCategory').modal('toggle');
        }

        const fail = function (response){
            if (response.data.status === "fail") {
                toastr.error(response.data.message);
                $scope.baseController.stopLoaddingButton('#doDelete');
                $('#modalDeleteCategory').modal('toggle');
            }
        };

        $http({
            method: "DELETE" ,
            url: apiUrl + "/category/" + $scope.deleteId
        }).then(function (response) {
            if (response.data.status !== "fail") {
                success(response);
            }else{
                fail(response);
            }
        }, fail)
    }*/

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

            $scope.getCategory();
            $scope.reset();

            $scope.baseController.stopLoaddingButton(btnSelector);
            $('#modalAddCategory').modal('toggle');
        };

        $scope.currentCategory.type = $scope.categoryType;

        $http({
            method: ($scope.mode === UPDATE_MODE) ? "PUT" : "POST",
            url: apiUrl + "/category" + (($scope.mode === UPDATE_MODE) ? "/" + $scope.currentCategory.id : ''),
            data: $scope.currentCategory

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
            var image = await $scope.upload(file);
            if (image) {
                $scope.$applyAsync(function () {
                    $scope.currentCategory.image_url = image;
                });
            }
        }
    }

    initialize();
}
