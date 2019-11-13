system.controller("SlideController", SlideController);

function SlideController($scope, $http, $rootScope, $timeout, Upload) {
    $scope.baseController = this.__proto__ = new BaseController($scope, $http, $rootScope, Upload);

    $scope.slides = [];
    $scope.slideSize = {
        w: 1920,
        h: 600
    };
    $scope.temp = {};

    function initialize() {
        $scope.getSlide();

        toastr.options = {
            "autoDismiss": true,
            "preventDuplicates": true,
            "debug": false,
            "positionClass": "toast-bottom-right",
            "onclick": null,
            "fadeIn": 300,
            "fadeOut": 1000,
            "timeOut": 3000,
            "extendedTimeOut": 1000
        };
    }

    $scope.sortableOptions = {
        update: function (e, ui) {
            // console.log($scope.slides);
        },
        'ui-floating': true,
        handle: ".handle",
        connectWith: ".group",
        axis: 'y',
        helper: "clone",
        dropOnEmpty: false,
    };

    $scope.getSlide = function () {
        $http.get(apiUrl + '/option?filters=key=slide')
            .then(function (response) {
                if (typeof response.data.result[0] !== 'undefined') {
                    let result = JSON.parse(response.data.result[0].value);

                    $scope.id = response.data.result[0].id;
                    $scope.slides = result.slides;
                    $scope.slideSize = result.size;

                    if (typeof result.size.h == "undefined") {
                        $scope.slideSize.h = 600;
                    }

                    if (typeof result.size.w == "undefined") {
                        $scope.slideSize.w = 1920;
                    }

                    $scope.temp = {
                        size: angular.copy(result.size),
                        slides: angular.copy(result.slides)
                    }
                }
            });
    };

    const fail = function (response, message = '') {
        $scope.loading = false;
        if (response.data.status === "fail") {
            $scope.baseController.stopLoaddingButton('#btnSave');
            toastr.error("Có lỗi xảy ra! " + message);
        }
    };

    const success = function (response) {
        toastr.success("Thành công!");
        $scope.baseController.stopLoaddingButton('#btnSave');
        $scope.temp = {
            size: angular.copy($scope.slideSize),
            slides: angular.copy($scope.slides)
        }
        if (!$scope.id) {
            $scope.id = response.data.result.id;
        }

    };

    $scope.cancel = function () {
        $scope.slideSize = $scope.temp.size;
        $scope.slides = $scope.temp.slides;
        setTimeout(function () {
            $scope.baseController.stopLoaddingButton('#btnSave');
            $('.lfm').filemanager('image');
        })
    };

    $scope.save = function () {
        $scope.baseController.loaddingButton('#btnSave');
        let err = false;

        $scope.slides.forEach(element => {
            let hasErrValidate = false;
            if (!element.image_url || !element.image_url.length > 0) {
                toastr.error("Vui chọn hình ảnh cho Slide!");
                hasErrValidate = true;
            }
            if (!element.href || !element.href.length > 0) {
                toastr.error("Vui lòng điền liên kết cho slide!");
                hasErrValidate = true;
            }

            if (hasErrValidate) {
                $scope.baseController.stopLoaddingButton('#btnSave');
                err = true;
                return false;
            }

        });

        if (!err) {
            $http({
                method: ($scope.id) ? "PUT" : "POST",
                url: apiUrl + "/option" + (($scope.id) ? "/" + ($scope.id) : ''),
                data: {
                    key: 'slide',
                    value: JSON.stringify({
                        size: $scope.slideSize,
                        slides: $scope.slides
                    })
                }

            }).then(function (response) {
                if (response.data.status !== "fail") {
                    success(response);
                } else {
                    fail(response);
                }

            }, fail)
        }
    };

    $scope.remove = function (index) {
        $scope.slides.splice(index, 1);
    };

    $scope.addNew = function () {
        let obj = {
            image_url: '',
            href: ''
        };

        $scope.slides.push(obj);
    };

    $scope.delete = function (category) {
        var index = $scope.slides.findIndex(s => s.id == category.id);
    };

    $scope.uploadImage = async function (file, index) {
        if (file) {
            var image = await $scope.upload(file);
            if (image) {
                $scope.$applyAsync(function () {
                    if ($scope.slides.length > index) {
                        $scope.slides[index].image_url = image;
                    }
                });
            }
        }
    }

    initialize();
}
