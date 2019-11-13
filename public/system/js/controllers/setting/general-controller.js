system.controller("GeneralController", GeneralController);

function GeneralController($scope, $http, $rootScope, $timeout, Upload){
    $scope.baseController = this.__proto__ = new BaseController($scope, $http, $rootScope, Upload);

    $scope.generalOptions = {};

    $scope.defaultValue = {
        "general.brand": {
            "name": "Thương hiệu",
            "description": "Hình ảnh thương hiệu là cách đơn giản nhất để khách hàng nhớ tới bạn",
            "items": {
                "logo": {
                    "label": "Logo",
                    "type": "text",
                    "value": "",
                    "key": "general.logo",
                },
                "favicon": {
                    "label": "Favicon",
                    "type": "text",
                    "value": "",
                    "key": "general.favicon"
                }
            }
        },
        "general.detail": {
            "name": "Chi tiết cửa hàng",
            "description": "Tiếp cận với khách hàng tốt hơn khi họ có được đầy đủ thông tin của cửa hàng bạn",
            "items": {
                "store_name": {
                    "label": "Tên cửa hàng",
                    "type": "text",
                    "value": "",
                    "key": "general.store_name"
                },
                "about": {
                    "tag": "textarea",
                    "label": "Giới thiệu",
                    "type": "text",
                    "value": "",
                    "key": "general.about"
                },
                "store_email": {
                    "label": "Email",
                    "type": "email",
                    "value": "",
                    "key": "general.store_email"
                },
                "hotline": {
                    "label": "Hotline",
                    "type": "tel",
                    "value": "",
                    "key": "general.hotline"
                },
                "address": {
                    "tag": "textarea",
                    "label": "Địa chỉ",
                    "type": "text",
                    "value": "",
                    "key": "general.address"
                }
            }
        },
        "general.social": {
            "name": "Mạng xã hội",
            "description": "Tương tác với người dùng tốt hơn khi họ biết địa chỉ mạng xã hội mà bạn dùng",
            "items": {
                "facebook_link": {
                    "label": "Facebook",
                    "type": "text",
                    "value": "",
                    "key": "general.facebook_link",
                    "replace": "https?:\\/\\/(www\\.)?facebook\\.com\\/?"
                },
                "zalo": {
                    "label": "Zalo",
                    "type": "text",
                    "value": "",
                    "key": "general.zalo_link",
                    "replace": "https?:\\/\\/zalo.me\\/"
                },
                "youtube_link": {
                    "label": "Youtube",
                    "type": "text",
                    "value": "",
                    "key": "general.youtube_link",
                    "replace": "https?:\\/\\/(www\\.)?(m\\.)?youtube\\.com\\/channel\\/"
                },
                "instagram_link": {
                    "label": "Instagram",
                    "type": "text",
                    "value": "",
                    "key": "general.instagram_link",
                    "replace": "https?:\\/\\/(www\\.)?instagram\\.com\\/?"
                },
                "twitter_link": {
                    "label": "Twitter",
                    "type": "text",
                    "value": "",
                    "key": "general.twitter_link",
                    "replace": "https?:\\/\\/(www\\.)?twitter\\.com\\/?"
                },
            }
        }
    };

    function initialize() {
        // Lấy dữ liệu từ biến toàn cục được khai báo tại footer html
        for(let key in sbOptions) {
            if(/general\..*/.test(key)){
                // sbOptions[key].key = key;
                $scope.generalOptions[key] = sbOptions[key];
            }
        }

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

    const fail = function (response, btnSelector = null){
        $scope.loading = false;
        if (response.data.status === "fail") {
            $scope.baseController.stopLoaddingButton('#btnSave');
            toastr.error("Có lỗi xảy ra!");
        }
    };

    const success = function(response) {
        toastr.success("Thành công!");
        $scope.baseController.stopLoaddingButton('#btnSave');
    };

    $scope.cancel = function(){
        $scope.slideSize = $scope.temp.size;
        $scope.slides = $scope.temp.slides;
        setTimeout(function () {
            $scope.baseController.stopLoaddingButton('#btnSave');
        })
    };

    $scope.save = function(){
        $scope.baseController.loaddingButton('#btnSave');
        $http({
            method: "PUT",
            url: apiUrl + "/option/update-general",
            data: $scope.generalOptions

        }).then(function (response) {
            if (response.data.status !== "fail") {
                success(response);
            }else{
                fail(response);
            }

        }, fail)
    };

    $scope.uploadImage = async function (file, key) {
        if (file) {
            image = await $scope.upload(file);
            if (image) {
                $scope.$applyAsync(function () {
                    $scope.generalOptions[key] = image;
                })
            }
        }
    }


    initialize();
}
