system.controller("ProductController", ProductController);

function ProductController($scope, $http, $rootScope, $interval, $window, Upload){
    this.__proto__ = new BaseController($scope, $http, $rootScope, Upload);
    $scope.controllerName = "ProductController";
    $scope.statusses = [
        {'code': 'ACTIVE', 'name': 'Đang bán'},
        {'code': 'PENDING', 'name': 'Dừng bán'}
    ];
    $scope.categories = [{id:null}];
    $scope.brands = [{id:null}];
    $scope.tags = [
        {id: null, title: ''}
    ];
    $scope.appUrl = app_url;
    $scope.product = {};
    $scope.allVariants = [];
    $scope.variants = [];
    $scope.optionByVariantIds = {};
    $scope.productVariants = [];
    $scope.attributes = [];
    $scope.initVariants = false;
    $scope.variantIdSelected = null;
    $scope.optionIdSelected = null;
    $scope.mode = 'create';
    $scope.determinateValue = 17;
    $scope.isSaving = false;
    $scope.isPage = true;
    $scope.isMetaTitleChanged = false;
    $scope.isMetaDescriptionChanged = false;
    $scope.isSlugChanged = false;
    $scope.isOpenAttr = true;
    $scope.gallery = [];
    $scope.isCloning = isCloning == '1' ? true : false;
    function initialize() {
        toastr.options = {
            "debug": false,
            "positionClass": "toast-bottom-right",
            "onclick": null,
            "fadeIn": 300,
            "fadeOut": 1000,
            "timeOut": 5000,
            "extendedTimeOut": 1000
        };
        buildCategories();
        buildBrands();
        buildTags();
        buildAllVariant();
        if (productId && productId != '') {
            buildProduct();
            buildVariant();
            if (!$scope.isCloning) {
                $scope.mode = 'update';
            }
        } else {
            $scope.product.status = $scope.getByField($scope.statusses, 'code', 'ACTIVE');
            $scope.product.categories = [];
            $scope.product.tags = [];
            $scope.product.variants = [];
            $scope.isLoad = true;
        }
    }

    function buildAllVariant() {
        $http({
            method: 'GET',
            url: base_api_url + '/product_variant?embeds=variantOptions'
        }).then(function success(response) {
            $scope.allVariants = [];
            $scope.optionByVariantIds = {};
            if (response.data.status == 'successful' && response.data.result.length > 0) {
                $scope.allVariants = response.data.result;
                response.data.result.forEach(function (item) {
                    $scope.optionByVariantIds[item.id] = item.variant_options;
                });
            } else {
                $scope.allVariants.push({});
            }
        });
    }

    function buildVariant() {
        $http({
            method: 'GET',
            url: base_api_url + '/variant/' + productId
        }).then(function success(response) {
            if (response.data.status == 'successful') {
                $scope.variants = response.data.result.variants;
                $scope.productVariants = response.data.result.productVariants;
                if ($scope.isCloning) {
                    for (let i = 0; i < $scope.productVariants.length; i++) {
                        $scope.productVariants[i].sku = '';
                        $scope.productVariants[i].id = makeId(6);
                    }
                }
            }
        });
    }

    function buildProduct() {
        $interval(function() {
            $scope.determinateValue += 1;
        }, 100, 0, true);
        $http({
           method: 'GET',
           url: base_api_url + '/product/' + productId + '?embeds=tags,categories,brand,meta,galleries'
        }).then(function success(response) {
            if (response.data.status == 'successful') {
                if (response.data.result != null) {
                    setTimeout(function () {
                        $scope.$apply(function () {
                            $scope.product = response.data.result;
                            $scope.product.status = $scope.getByField($scope.statusses, 'code',  response.data.result.status);
                            if ($scope.product.image_url
                                    && $scope.product.image_url != ''
                                    && $scope.product.image_url != 'https://s3.shopbay.vn/files/2/system/product-image-placeholder.png') {
                                var gallery = {
                                    id: makeId(4),
                                    image_url: $scope.product.image_url
                                }
                                $scope.gallery.push(gallery);
                            }
                            if (response.data.result && response.data.result.meta && response.data.result.meta.length > 0) {
                                response.data.result.meta.forEach(function(meta) {
                                    if (meta.key == 'seo') {
                                        try {
                                            var seo = JSON.parse(meta.value);
                                            if (typeof seo.meta_title != 'undefined') {
                                                $scope.product.meta_title = seo.meta_title;
                                            }
                                            if (typeof seo.meta_description != 'undefined') {
                                                $scope.product.meta_description = seo.meta_description;
                                            }
                                        } catch (e) {

                                        }
                                    }
                                });
                            }
                            loadAttributes();
                            if (response.data.result.galleries && response.data.result.galleries.length > 0) {
                                response.data.result.galleries.forEach(function (gallery) {
                                    $scope.gallery.push(gallery);
                                });
                            }
                        });
                        $scope.determinateValue = 100;
                        if ($scope.isCloning) {
                            $scope.product.sku = '';
                            if ($scope.product.id) {
                                delete $scope.product.id;
                            }
                        }
                    }, 250);
                } else {
                    $scope.isPage = false;
                }
                $scope.isLoad = true;
            }
        });
    }

    function buildCategories() {
        $http({
            method: 'GET',
            url: base_api_url + '/categories'
        }).then(function successCallback(response) {
            if (response.data.status == 'successful' && response.data.result.length > 0) {
               $scope.categories = response.data.result;
            }
        });
    }

    function buildBrands() {
        $http({
            method: 'GET',
            url: base_api_url + '/brand'
        }).then(function success(response){
           if (response.data.status == 'successful' && response.data.result.length > 0) {
               $scope.brands = response.data.result;
           }
        });
    }

    function buildTags() {
        $http({
            method: 'GET',
            url: base_api_url + '/tag'
        }).then(function success(response){
            if (response.data.status == 'successful' && response.data.result.length > 0) {
                $scope.tags = response.data.result;
            }
        });
    }

    function loadAttributes() {
        var categoryIds = [];
        if (typeof $scope.product.categories != 'undefined' && $scope.product.categories.length > 0) {
            $scope.product.categories.forEach(function(ctg) {
                categoryIds.push(ctg.id);
            });
        }
        var url = '';
        if (typeof $scope.product.id != 'undefined') {
            url += '?productId=' + $scope.product.id;
        }
        if (categoryIds.length > 0) {
            if (url == '') {
                url += '?';
            } else {
                url += '&';
            }
            url += 'ctgIds=' + categoryIds.join(',');
        }
        $http({
            method: 'GET',
            url: base_api_url + '/attribute' + url
        }).then(function success(response) {
            if (response.data.status == 'successful') {
                $scope.attributes = response.data.result;
            }
        });
    }

    function loading() {
        $scope.determinateValue = 17;
        $scope.loaddingButton('#btn-save-1');
        $scope.loaddingButton('#btn-save-2');
        $scope.loaddingButton('#btn-save-3');
        $scope.loaddingButton('#btn-save-4');
        $('#btn-cancel,#btn-delete').attr("disabled", true);
        $interval(function() {
            $scope.determinateValue += 1;
        }, 100, 0, true);
    }

    function loaded() {
        $scope.determinateValue = 100;
        $scope.stopLoaddingButton('#btn-save-1');
        $scope.stopLoaddingButton('#btn-save-2');
        $scope.stopLoaddingButton('#btn-save-3');
        $scope.stopLoaddingButton('#btn-save-4');
        $('#btn-cancel,#btn-delete').attr("disabled", false);
    }

    $scope.chooseCategories = function() {
        loadAttributes();
    }

    $scope.save = function(mode = "") {
        loading();
        //setTimeout handle ckeditor when code html mode
        setTimeout(function () {
            var product = buildDataProduct();
            var failMessages = validating(product);
            if (failMessages != '') {
                toastr.error(failMessages);
                loaded();
                return false;
            }
            var url = base_api_url + '/product'
            var method = 'POST';
            if ($scope.mode == 'update') {
                method = 'PUT';
                url += '/' + product.id;
            }
            $http({
                method: method,
                url: url,
                data: JSON.stringify(product, function(k, v) { return v === undefined ? '' : v; })
            }).then(function success(response) {
                if (response.data.status == 'successful' && response.data.result.id != 'undefined') {
                    $scope.product.id = response.data.result.id;
                    $scope.product.sku = response.data.result.sku;
                    toastr.success('Cập nhật sản phẩm thành công.');
                    if (mode == 'saveAndExit') {
                        $window.location.href = '/admin/products';
                    } else if (mode == 'save' && $scope.mode == 'create' ) {
                        $window.location.href = '/admin/products/' + $scope.product.id;
                    }
                    $scope.mode = 'update';
                } else {
                    toastr.error('Cập nhật sản phẩm không thành công. ' + response.data.message);
                }
                loaded();
            });
        }, 300);
    };

    function validating(data) {
        var retVal = '';
        if (typeof data.name == 'undefined' || data.name == '') {
            retVal += 'Chưa nhập tiêu đề cho sản phẩm.';
        }
        var variantValidate = '';
        $scope.variants.forEach(function (variant) {
            if (variant.name == '') {
                variantValidate = 'Chưa nhập tên nhóm biến thể sản phẩm.';
            };
            if (variant.values && variant.values.length == 0) {
                variantValidate = 'Chưa nhập biến thể cho sản phẩm.'
            }
        });
        if (variantValidate != '') {
            retVal += ' ' + variantValidate;
        }
        return retVal;
    }

    function buildDataProduct() {
        var checkDefaultSku = false;
        for (var i = 0; i < $scope.productVariants.length; i++) {
            if ($scope.productVariants[i].is_default) {
                checkDefaultSku = true;
                break;
            }
        }
        if (!checkDefaultSku && $scope.productVariants.length > 0) {
            $scope.productVariants[0].is_default = 1;
        }
        var retVal = {
            categoryIds: [],
            tagIds: [],
            variants: $scope.variants,
            productVariants: $scope.productVariants,
            image_url: $scope.product.image_url,
            price: 0,
            highPrice: 0,
            add_shipping_fee: 0,
            description: '',
            content: '',
            meta_title: '',
            meta_description: '',
            brand_id: null,
            attributeIds: [],
            gallery: []
        };
        if ($scope.product.id) {
            retVal.id = $scope.product.id;
        }
        if ($scope.product.name && $scope.product.name != '') {
            retVal.name = $scope.product.name;
        }
        if ($scope.product.price && $scope.product.price > 0) {
            retVal.price = $scope.product.price;
        }
        if ($scope.product.high_price && $scope.product.high_price > 0) {
            retVal.high_price = $scope.product.high_price;
        }
        if ($scope.product.add_shipping_fee && $scope.product.add_shipping_fee > 0) {
            retVal.add_shipping_fee = $scope.product.add_shipping_fee;
        }
        if ($scope.product.description && $scope.product.description != '') {
            retVal.description = $scope.product.description;
        }
        if ($scope.product.content && $scope.product.content != '') {
            retVal.content = $scope.product.content;
        }
        if ($scope.product.sku && $scope.product.sku != '') {
            retVal.sku = $scope.product.sku;
        }
        if ($scope.product.meta_title && $scope.product.meta_title != '') {
            retVal.meta_title = $scope.product.meta_title;
        }
        if ($scope.product.meta_description && $scope.product.meta_description != '') {
            retVal.meta_description = $scope.product.meta_description;
        }
        if ($scope.product.slug && $scope.product.slug != '') {
            retVal.slug = $scope.product.slug;
        }
        if ($scope.product.status != 'undefined') {
            retVal.status = $scope.product.status.code;
        }
        if ($scope.product.brand) {
            retVal.brand_id = $scope.product.brand.id;
        }
        if ($scope.product.categories && $scope.product.categories.length > 0) {
            $scope.product.categories.forEach(function(category) {
                retVal.categoryIds.push(category.id);
            });
        }
        if ($scope.product.tags && $scope.product.tags.length > 0) {
            $scope.product.tags.forEach(function(tag) {
                retVal.tagIds.push(tag.id);
            });
        }
        if ($scope.productVariants.length > 0) {
            $scope.productVariants.forEach(function (item) {
                if (item.is_default) {
                    retVal.price = item.price;
                    retVal.high_price = item.high_price;
                }
            });
        }
        if ($scope.attributes.length > 0) {
            $scope.attributes.forEach(function (attr) {
                if (attr.value && typeof attr.value.id != 'undefined') {
                    retVal.attributeIds.push(attr.value.id);
                }
            });
        }
        if ($scope.gallery.length > 0) {
            var i = 0;
            $scope.gallery.forEach(function (img) {
               if (i > 0) {
                   retVal.gallery.push(img);
               }
               i++;
            });
        }
        if ($scope.product.attributes) {
            retVal.attributes = $scope.product.attributes;
        }
        return retVal;
    }

    $scope.createBrand = function(term) {
        loading();
        var newBrand = {'name': term};
        $http({
            method: 'POST',
            url: base_api_url + '/brand',
            data: newBrand
        }).then(function success(response) {
            if (response.data.status == 'successful' && response.data.result.id != 'undefined') {
                var newBrand = {
                    id: response.data.result.id,
                    name: response.data.result.name,
                    slug: response.data.result.slug
                };
                $scope.brands.push(newBrand);
                $scope.product.brand = $scope.getByField($scope.brands, 'id', newBrand.id);
                toastr.success('Tạo hãng sản xuất thành công');
            } else {
                toastr.error('Tạo hãng sản xuất không thành công. ' + response.data.message);
            }
            loaded();
        });

    };

    $scope.createTag = function(term) {
        loading();
        var newTag = {'title': term};
        $http({
            method: 'POST',
            url: base_api_url + '/tag',
            data: newTag
        }).then(function success(response) {
            if (response.data.status == 'successful' && response.data.result.id != 'undefined') {
                var newTag = {
                    id: response.data.result.id,
                    title: response.data.result.title
                };
                $scope.tags.push(newTag);
                $scope.product.tags.push(newTag);
                toastr.success('Tạo Tag thành công');
            } else {
                toastr.error('Tạo Tag không thành công. ' + response.data.message)
            }
            loaded();
        });
    };

    $scope.initVariant = function() {
        if (!$scope.initVariants) {
            $scope.initVariants = true;
            if ($scope.variants.length > 0) {
                $scope.variants.forEach(function (item) {
                    item.group = $scope.getByField($scope.allVariants, 'id', item.id);
                    var selectedIds = [];
                    item.values.forEach(function(it) {
                        selectedIds.push(it.id);
                    });
                    item.values = [];
                    if (typeof $scope.optionByVariantIds[item.id] != 'undefined') {
                        $scope.optionByVariantIds[item.id].forEach(function (it) {
                            it.variant = item.name;
                            if (selectedIds.indexOf(it.id) != -1) {
                                item.values.push(it);
                            }
                        });
                    }
                });
            } else {
                var newId = makeId(5);
                $scope.variants.push({ 'id': newId });
            }
        }
    }

    $scope.clickerVariant = function(variant) {
        $scope.variantIdSelected = variant.id;
    }

    $scope.clickerOption = function(variant) {
        $scope.optionIdSelected = variant.id;
    }

    $scope.createVariant = function(term) {
        if ($scope.variantIdSelected != null) {
            $scope.variants.forEach(function(item) {
                if (item.id == $scope.variantIdSelected) {
                    var idNew =  makeId(5);
                    var newGroup = {
                        name: term,
                        id: idNew,
                        values: [],
                        variant_options: []
                    };
                    item.id = idNew;
                    item.name = term;
                    item.group = newGroup;
                    item.values = [];
                    $scope.allVariants.push(newGroup);
                    $scope.optionByVariantIds[idNew] = [{}];
                }
            });
        }
    }

    function makeId(length) {
        var result = '';
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        var charactersLength = characters.length;
        for (var i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    }

    $scope.createVariantOption = function(term) {
        if ($scope.optionIdSelected != null) {
            $scope.variants.forEach(function(item) {
                if (item.id == $scope.optionIdSelected) {
                    var newId = makeId(6);
                    var newOption = {
                        id: newId,
                        name: term,
                        variant: item.name
                    };
                    if (typeof item.values == 'undefined') {
                        item.values = [];
                    }
                    item.values.push(newOption);
                    $scope.optionByVariantIds[item.group.id].push(newOption);
                }
            });
            buildProductVariant()
        }
    }

    $scope.addVariant = function() {
        if ($scope.variants.length <= 3) {
            var newId = makeId(5);
            $scope.variants.push({id: newId});
        }
    };

    $scope.removeVariant = function(idx) {
        var param = {
            title: 'Xóa nhóm biến thể',
            text: 'xóa nhóm biến này',
            arg: {idx: idx}
        };
        $scope.callConfirmModal(param, function(arg) {
            $scope.variants.splice(arg.idx, 1);
            buildProductVariant();
        });
    }

    $scope.removeProductSku = function(idx) {
        var param = {
            title: 'Xóa sản phẩm biến thể',
            text: 'xóa sản phẩm này',
            arg: {idx: idx}
        };
        $scope.callConfirmModal(param, function(arg) {
            $scope.productVariants.splice(arg.idx, 1);
        });

    }

    $scope.changeVariantValue = function(variant) {
        variant.values.forEach(function (item) {
            item.variant = variant.name;
        });
        buildProductVariant();
    };
    $scope.buildGroupVariant = function(variant) {
        variant.name = variant.group.name;
        variant.id = variant.group.id;
    }


    function buildProductVariant() {
        var arrVariants = [];
        $scope.variants.forEach(function(item) {
            if (item.values.length > 0) {
                arrVariants.push(item.values);
            }
        });
        var arrItems = [];
        if (arrVariants.length == 1) {
            arrItems = Combinatorics.cartesianProduct(arrVariants[0]);
        } else if(arrVariants.length == 2) {
            arrItems = Combinatorics.cartesianProduct(arrVariants[0], arrVariants[1]);
        } else if(arrVariants.length == 3) {
            arrItems = Combinatorics.cartesianProduct(arrVariants[0], arrVariants[1], arrVariants[2]);
        }
        if (arrItems.length > 0) {
            var productVariants = arrItems.toArray();
            var backup = buildBackupProductVariant();
            $scope.productVariants = [];
            for(var i = 0; i < productVariants.length ; i++) {
                var product = {
                    id: makeId(7),
                    variants: productVariants[i],
                    sku: '',
                    price: '',
                    is_default: false
                };
                if (i == 0) {
                    product.is_default = true;
                }
                var valueIds = [];
                for (var j = 0; j < productVariants[i].length; j++) {
                    valueIds.push(productVariants[i][j].id);
                }
                var keyExist = null;
                for (var key in backup) {
                    var check = false;
                    for(var k = 0; k < valueIds.length; k++) {
                        if (backup[key].values && backup[key].values.indexOf(valueIds[k]) < 0) {
                            check = true;
                        }
                    }
                    if (!check) {
                        keyExist = key;
                        break;
                    }
                }

                if (keyExist != null) {
                    product.id = backup[keyExist].id;
                    product.sku = backup[keyExist].sku;
                    product.price = backup[keyExist].price;
                    product.high_price = backup[keyExist].highPrice;
                    product.image_url = backup[keyExist].imageUrl;
                    product.is_default = backup[keyExist].isDefault;
                } else {
                    if ($scope.product.sku && $scope.product.sku != '') {
                        product.sku = $scope.product.sku;
                    }
                    if ($scope.product.price && $scope.product.price != '') {
                        product.price = $scope.product.price;
                    }
                    if ($scope.product.high_price && $scope.product.high_price != '') {
                        product.high_price = $scope.product.high_price;
                    }
                    if ($scope.product.image_url && $scope.product.image_url != '') {
                        product.image_url = $scope.product.image_url;
                    }
                }
                $scope.productVariants.push(product);
            }
        }

    };

    function buildBackupProductVariant() {
        var retVal = {};
        $scope.productVariants.forEach(function(item) {
            retVal[item.id] = {
                id: item.id,
                values: [],
                sku: item.sku,
                price: item.price,
                highPrice: item.high_price,
                imageUrl: item.image_url,
                isDefault: item.is_default
            };
            for (var i = 0; i < item.variants.length; i++) {
                retVal[item.id].values.push(item.variants[i].id);
            }
        });
        return retVal;
    }

    $scope.delete = function() {
        if (typeof $scope.product.id != 'undefined' && $scope.product.id > 0) {
            var param = {
                title: 'Xóa sản phẩm',
                text: 'xóa sản phẩm này',
                arg: {id: $scope.product.id}
            };
            $scope.callConfirmModal(param, function(arg) {
                loading();
                $http({
                    method: 'delete',
                    url: base_api_url + '/product/' + arg.id
                }).then(function success(response) {
                    if (response.data.status == 'success') {
                        setTimeout(function () {
                            $window.location.href = '/admin/products';
                        }, 700);
                        toastr.success('Xóa sản phẩm thành công');
                    } else {
                        toastr.error('Xóa sản phẩm không thành công');
                    }
                    loaded();
                });
            });
        }
    };

    $scope.initLfm = function() {
        $('.lfm').filemanager('image');
    }

    $scope.prerenderSeo = function () {
        if (!$scope.isMetaTitleChanged) {
            $scope.product.meta_title = $scope.product.name;
        }
        if (!$scope.isMetaDescriptionChanged) {
            $scope.product.meta_description = $scope.product.description;
        }
        if (!$scope.isSlugChanged) {
            $scope.product.slug = $scope.toFriendlyString($scope.product.name);
        }
    }

    $scope.changeMetaTitle = function () {
        $scope.isMetaTitleChanged = true;
    }
    $scope.changeMetaDescription = function () {
        $scope.isMetaDescriptionChanged = true;
    }
    $scope.changeSlug = function () {
        $scope.isSlugChanged = true;
    }

    $scope.setDefaultChecker = function(productVariant) {
        $scope.productVariants.forEach(function(item) {
            if (item.id == productVariant.id) {
                item.is_default = true;
            } else {
                item.is_default = false;
            }
        });

    }

    $scope.uploadImages = async function (files) {
        loading();
        if (files) {
            var images = files.length + $scope.gallery.length;
            if (images > 9) {
                toastr.error('Không được upload quá 9 ảnh cho sản phẩm')
                return false;
            }
            images = null;
            try {
                images = await $scope.uploads(files);
            } catch (error) {
            }
            if (images) {
                $scope.$applyAsync(function () {
                    images.forEach(function(img){
                        var image = {
                            id: makeId(4),
                            image_url: img
                        };
                        $scope.gallery.push(image);
                    });
                    buildAvatar();
                });
            }
        }
        loaded();
    };

    $scope.removeImage = function(idx) {
        var param = {
            title: 'Xóa ảnh',
            text: 'xóa ảnh này',
            arg: {idx: idx}
        };
        $scope.callConfirmModal(param, function(arg) {
            $scope.$apply( function () {
                $scope.gallery.splice(arg.idx, 1);
                buildAvatar();
            });
        });
    }

    $scope.chooseAvatar = function($index) {
        let temp = $scope.gallery[$index];
        $scope.gallery[$index] = $scope.gallery[0];
        $scope.gallery[0] = temp;
        buildAvatar();
    }

    function buildAvatar() {
        if ($scope.gallery && $scope.gallery[0] && $scope.gallery[0].image_url) {
            $scope.product.image_url = $scope.gallery[0].image_url
        }
    };

    function updateVariantsImage() {
        if ($scope.gallery.length > 0) {
            for (let i = 0; i < $scope.productVariants.length; i++) {
                if (!$scope.productVariants[i].image_url || $scope.productVariants[i].image_url == '') {
                    $scope.productVariants[i].image_url = $scope.gallery[0].image_url;
                }
            }
        }
    }

    $scope.$watch('gallery', function () {
        updateVariantsImage();
    }, true);

    initialize();
}
