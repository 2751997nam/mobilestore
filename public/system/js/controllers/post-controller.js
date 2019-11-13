system.controller("PostController", PostController);

function PostController($scope, $http, $rootScope, $interval, $window, Upload){
    this.__proto__ = new BaseController($scope, $http, $rootScope, Upload);
    $scope.controllerName = "PostController";
    $scope.statusses = [
        {'code': 'ACTIVE', 'name': 'Công khai'},
        {'code': 'PENDING', 'name': 'Ẩn'}
    ];

    $scope.isLoad = true;
    $scope.isPage = true;

    $scope.getPost = function () {
        if (!postId) {
            $scope.post = {
                status : 'ACTIVE'
            };
        } else {
            $http.get(base_api_url + '/post/' + postId + "?embeds=category").then(
                function success (response) {
                    console.log(response)
                    $scope.post = response.data.result;

                },
                function error (response) {
                    toastr.error("Không tìm thấy bài viết");
                }
            );

        }

    }

    $scope.categories = [{id:null}];

    $scope.categorySearchKeyword = "";

    $scope.init = function () {
        $scope.getPostCategories();
        $scope.getPost();
    }

    $scope.getPostCategories = function () {
        $http.get(base_api_url + '/post-category').then(
            function success (response) {
                if (response.data.length) {
                    $scope.categories = response.data;
                } else {
                    $scope.categories = [{id:null}];
                }
            },
            function error (response) {
            }
        );
    }



    $scope.createCategory = function (item) {
        $http.post(base_api_url + '/post-category', {name : item }).then(
            function success (response) {
                let category = response.data;
                $scope.categories.push(category);
                $scope.post.category = category;
            },
            function error (response) {
                console.log(response)
            }
        );

    }

    $scope.save = function (mode = "") {
        //setTimeout handle ckeditor when code html mode
        setTimeout(function () {
            if (!postId) {
                $http.post(base_api_url + '/post', $scope.post).then(
                    function success (response) {
                        toastr.success("Tạo bài viết thành công.");
                        if (mode == 'saveAndExit') {
                            $window.location.href = '/admin/posts';
                        } else if (mode == 'save') {
                            $window.location.href = '/admin/posts/' + response.data.id;
                        }
                    },
                    function error (response) {
                        toastr.error(response.data.message);
                    }
                );
            } else {
                $http.put(base_api_url + '/post/' + postId, $scope.post).then(
                    function success (response) {
                        toastr.success("Cập nhật bài viết thành công.");
                        if (mode == 'saveAndExit') {
                            $window.location.href = '/admin/posts';
                        }
                    },
                    function error (response) {
                        if (response.data.message) {
                            toastr.error(response.data.message);

                        } else {
                            toastr.error("Không thể cập nhật bài viết.");
                        }
                    }
                );
            }
        }, 300);
    }

    $scope.uploadImage = async function (file) {
        if (file) {
            var image = await $scope.upload(file);
            if (image) {
                $scope.$applyAsync(function () {
                    $scope.post.image_url = image;
                });
            }
        }
    };

    $scope.init();

}
