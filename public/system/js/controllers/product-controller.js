system.controller("ProductController", ProductController);

function ProductController($scope, $http, $rootScope, $interval, $window, Upload){
    this.__proto__ = new BaseController($scope, $http, $rootScope, Upload);
    $scope.mode = productId ? 'update' : 'create';
    $scope.categories = [];
    $scope.brands = [];
    $scope.isPage = true;
    $scope.isLoad = true;
    $scope.product = {
        status: 'ACTIVE',
        categories: []
    };
    $scope.gallery = [];
    $scope.statuses = [
        {
            value: 'ACTIVE',
            name: 'Hiển thị'
        },
        {
            name: 'Ẩn',
            value: 'PENDING'
        }
    ];

    $scope.initialize = function ()
    {
        $scope.isPage = true;
        getCategories();
        getBrands();
        if (productId) {
            getProduct();
        }
    }

    function getProduct() 
    {
        $http.get('/api/product/' + productId).then(function (response) {
            if (response.data.status == 'successful') {
                $scope.product = response.data.result;
                $scope.gallery.push({
                    image_url: $scope.product.image_url
                });
                if ($scope.product.galleries.length > 0) {
                    $scope.product.galleries.forEach(function (element) {
                        $scope.gallery.push({
                            image_url: element.image_url
                        });
                    });
                }
            } else {
                $scope.fail(response);
            }
        }); 
    }

    function getCategories() {
        $http.get('/api/category').then(function (response) {
            $scope.categories = response.data.result;
        });
    }

    function getBrands() {
        $http.get('/api/brand').then(function (response) {
            $scope.brands = response.data.result;
        });
    }

    $scope.uploadImages = async function (files) {
        if (files.length > 0) {
            var images = files.length + $scope.gallery.length;
            if (images > 9) {
                toastr.error('Không được upload quá 9 ảnh cho sản phẩm')
                return false;
            }
            images = null;
            try {
                images = await upload(files);
            } catch (error) {
            }
            if (images) {
                $scope.$applyAsync(function () {
                    images.forEach(function(img){
                        var image = {
                            image_url: img
                        };
                        $scope.gallery.push(image);
                    });
                    buildAvatar();
                });
            }
        }
    };

    function upload(files)
    {
        var formData = new FormData();
        files.forEach(function (element) {
            formData.append('images[]', element);
        });

        return new Promise(function (resolve, reject) {
            $http.post('/api/product/upload-image', formData, {
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

    $scope.chooseAvatar = function (index) {
        let image = $scope.gallery[index];
        $scope.gallery.splice(index, 1);
        $scope.gallery.unshift(image);
    }

    $scope.removeImage = function (index) {
        $scope.gallery.splice(index, 1);
    }

    function buildAvatar() {
        if ($scope.gallery && $scope.gallery[0] && $scope.gallery[0].image_url) {
            $scope.product.image_url = $scope.gallery[0].image_url
        }
    };

    $scope.fail = function (response) {
        let textErr = response.data.message;
        toastr.error(textErr);
    };

    $scope.save = function () {
        let data = buildProductData();
        if (productId) {
            $http.put('/api/product/' + productId, JSON.stringify(data))
            .then(function (response) {
                if (response.data.status == 'successful') {
                    $scope.showSuccessModal('Sửa sản phẩm thành công', function () {
                        // window.location.href = '/admin/products';
                    });
                } else {
                    $scope.fail(response);
                }
            });
        } else {
            $http.post('/api/product', JSON.stringify(data))
            .then(function (response) {
                if (response.data.status == 'successful') {
                    $scope.showSuccessModal('Thêm sản phẩm thành công', function () {
                        // window.location.href = '/admin/products';
                    });
                } else {
                    $scope.fail(response);
                }
            });
        }
    }


    function buildProductData()
    {
        let data = {};

        let product = $scope.product;

        if (product.status) {
            data.status = product.status;
        }
        if (product.name) {
            data.name = product.name;
        }

        if (product.price) {
            data.price = product.price;
        }

        if (product.high_price) {
            data.high_price = product.high_price;
        }

        if (product.sku) {
            data.sku = product.sku;
        }
        if (product.content) {
            data.content = product.content;
        }
        if (product.inventory) {
            data.inventory = product.inventory;
        }
        if (product.brand) {
            data.brand_id = product.brand.id;
        }
        if (product.categories) {
            data.categories = [];

            product.categories.forEach(element => {
                data.categories.push(element.id);
            });
        }
        if ($scope.gallery) {
            data.images = [];

            $scope.gallery.forEach(function (element) {
                data.images.push(element.image_url);
            });
        }

        return data;
    }

    $scope.initialize();
}
