system.controller('CreateOrderController', CreateOrderController);

function CreateOrderController($scope, $http, $rootScope)
{
    this.__proto__ = new BaseController($scope, $http, $rootScope);

    $scope.products = [];
    $scope.customers = [];
    $scope.provinces = [];
    $scope.districts = [];
    $scope.communes = [];
    $scope.selectedProducts = [];
    $scope.checkedProducts = [];
    $scope.searchProductQuery = '';
    $scope.addedItems = [];
    $scope.orderInfo = {
        discount: 0,
        discountType: 'price',
        shipping: 0,
        subtotal: 0,
        amount: 0,
        note: '',
        delivery_note: ''
    }
    $scope.searchCustomerQuery = '';
    $scope.selectedCustomer = '';
    $scope.newCustomer = '';
    $scope.validationErrors = {};
    $scope.disabledSave = true;

    $scope.displayedFieldName = {
        full_name: 'họ tên',
        phone: 'số điện thoại',
        email: 'email',
        province_id: 'tỉnh/thành phố',
        district_id: 'quận/huyện',
        commune_id: 'xã/phường',
        address: 'địa chỉ'
    }

    $scope.initialize = function ()
    {
        $scope.resetNewCustomer();

        var url = $scope.buildUrl('/province?page_size=1000');
        $http.get(url).then(function (response) {
            $scope.provinces = response.data.result;
            $scope.provinces.unshift({id: 0, name: 'Chọn tỉnh/thành phố'});
        });
    }

    $scope.resetNewCustomer = function ()
    {
        $scope.newCustomer = {
            full_name: '',
            phone: '',
            email: '',
            province_id: 0,
            district_id: 0,
            commune_id: 0,
            address: '',
            editting: false,
        };
        $scope.validationErrors = {};
    }

    $scope.setDistricts = function () {
        if ($scope.newCustomer.province_id > 0) {
            // for (var i = 0; i < $scope.provinces.length; i++)
            // {
            //     if ($scope.provinces[i].id == $scope.newCustomer.province_id && $scope.provinces[i].districts)
            //     {
            //         $scope.districts = JSON.parse(JSON.stringify($scope.provinces[i].districts));
            //         $scope.districts.unshift({id: 0, name: 'Chọn quận/huyện'});
            //         break;
            //     }
            // }
            $http.get($scope.buildUrl('/district?embeds=communes&page_size=10000&filters=province_id=' + $scope.newCustomer.province_id))
                .then(function (response) {
                    $scope.districts = response.data.result;
                    $scope.districts.unshift({id: 0, name: 'Chọn quận/huyện'});
                }).catch (function (error) {
                    $scope.districts = [];
                })
        }
    }

    $scope.setCommunes = function () {
        $scope.communes = [];
        if ($scope.newCustomer.district_id > 0) {
            for (var i = 0; i < $scope.districts.length; i++)
            {
                if ($scope.districts[i].id == $scope.newCustomer.district_id && $scope.districts[i].communes)
                {
                    $scope.communes = JSON.parse(JSON.stringify($scope.districts[i].communes));
                    $scope.communes.unshift({id: 0, name: 'Chọn xã/phường'});
                    break;
                }
            }
        }
    }

    $scope.searchProduct = function (isShowModal)
    {
        if (isShowModal) {
            $scope.checkedProducts = JSON.parse(JSON.stringify($scope.selectedProducts));
            $('#search-product-modal').modal('show');
            var input = $('#search-product-modal').find('input[type=text]')[0];
            if (input) {
                input.focus();
            }
        }
        var url = $scope.buildUrl('/product/find?name=' + $scope.searchProductQuery);

        $http.get(url).then(function (response) {
            $scope.products = response.data.result;
            for (var i = 0; i < $scope.products.length; i++) {
                if (typeof $scope.products[i].id == 'undefined') {
                    $scope.products[i].id = makeId(5);
                }
                if (typeof $scope.products[i].product_id == 'undefined') {
                    $scope.products[i].product_id = $scope.products[i].id;
                }
                // if (typeof $scope.products[i].product_sku_id == 'undefined') {
                //     $scope.products[i].product_sku_id = $scope.products[i].id;
                // }
                $scope.products[i].checked = false;
                $scope.products[i].quantity = 1;
            }
        });
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

    $scope.updateCheckedList = function (product) {
        if (product.checked === true) {
            $scope.addCheckedProduct(product);
        } else {
            $scope.removeCheckedProduct(product);
        }
    }

    $scope.addCheckedProduct = function (product)
    {
        var check = true;
        for (var i = 0; i < $scope.checkedProducts.length; i++) {
            if ($scope.isEqualProduct($scope.checkedProducts[i], product)) {
                check = false;
            }
        }
        if (check) {
            $scope.checkedProducts.unshift(product);
        }
    }

    $scope.removeCheckedProduct = function (product)
    {
        product.checked = false;
        for (var i = 0; i < $scope.checkedProducts.length; i++)
        {
            if ($scope.isEqualProduct($scope.checkedProducts[i], product)) {
                $scope.checkedProducts.splice(i, 1);
            }
        }
    }

    $scope.addSelectedProducts = function ()
    {
        $scope.selectedProducts = JSON.parse(JSON.stringify($scope.checkedProducts));
        $scope.checkedProducts = [];
        $scope.searchProductQuery = '';

        $scope.updateInfo();
    }

    $scope.removeSelectedProduct = function (product)
    {
        $.each($scope.selectedProducts, function (i) {
            if ($scope.isEqualProduct($scope.selectedProducts[i], product)) {
                product.checked = false;
                $scope.selectedProducts.splice(i, 1);
                $scope.updateInfo();
                return false;
            }
        });
    }

    $scope.isEqualProduct = function (productA, productB) {
        return (productA.product_id && productA.product_id === productB.product_id) &&
        (productA.product_sku_id ? productA.product_sku_id === productB.product_sku_id : true)
    }

    $scope.wasAdded = function (product)
    {
        var index = -1;
        var result = false;

        $.each($scope.checkedProducts, function (i) {
            if ($scope.isEqualProduct($scope.checkedProducts[i], product)) {
                product.quantity = $scope.checkedProducts[i].quantity;
                index = i;
                return false;
            }
        });

        if (index >= 0) {
            product.checked = true;
            return true;
        } else {
            product.checked = false;
        }

        return result;
    }

    $scope.selectCustomer = function (customer)
    {
        $scope.selectedCustomer = customer;
    }

    $scope.removeCustomer = function ()
    {
        $scope.selectedCustomer = '';
        $scope.resetNewCustomer();
    }

    $scope.addSelectedCustomer = function ()
    {
        $http({
            url: $scope.buildUrl('/order/validate-customer'),
            method: 'POST',
            data: JSON.stringify($scope.newCustomer),
            headers: {
                'Content-Type': 'application/json'
            },
        }).then(function () {
            $scope.selectedCustomer = JSON.parse(JSON.stringify($scope.newCustomer));
            $scope.validationErrors = {};
            $('#create-customer-modal').modal('toggle');
        }).catch(function (error) {
            if (error.data && error.data.message) {
                $scope.validationErrors = error.data.message;
            } else {
                console.log(error);
            }
        });
    }

    $scope.getLocation = function ()
    {
        var result = '';
        var province = {}, district = {}, commune = {};
        for (var i = 0; i < $scope.provinces.length; i++)
        {
            if ($scope.provinces[i].id == $scope.selectedCustomer.province_id) {
                province = $scope.provinces[i];
                for (var j = 0; j < $scope.districts.length; j++)
                {
                    if ($scope.districts[j].id == $scope.selectedCustomer.district_id) {
                        district = $scope.districts[j];
                        var communes = district.communes;
                        if (communes) {
                            for (var k = 0; k < communes.length; k++) {
                                if (communes[k].id == $scope.selectedCustomer.commune_id) {
                                    commune = communes[k];
                                }
                            }
                        }
                        break;
                    }
                }
                break;
            }
        }
        if (commune && commune.id && commune.name) {
            result += commune.name + ' - ';
        }

        if (district && district.id && district.name) {
            result += district.name + ' - ';
        }

        if (province && province.id && province.name) {
            result += province.name;
        }

        return result;
    }

    $scope.calculateAmount = function ()
    {
        if ($scope.orderInfo.discountType == 'price') {
            $scope.orderInfo.amount = $scope.orderInfo.subtotal + $scope.orderInfo.shipping - $scope.orderInfo.discount;
        } else {
            $scope.orderInfo.amount = $scope.orderInfo.subtotal + $scope.orderInfo.shipping - ($scope.orderInfo.discount * $scope.orderInfo.subtotal / 100);
        }
    }

    $scope.calculateSubtotal = function () {
        $scope.orderInfo.subtotal = 0;
        for (var i = 0; i < $scope.selectedProducts.length; i++)
        {
            if ($scope.selectedProducts[i].quantity) {
                $scope.orderInfo.subtotal += $scope.selectedProducts[i].price * $scope.selectedProducts[i].quantity;
            }
        }
    }

    $scope.updateInfo = function ()
    {
        $scope.calculateSubtotal();
        $scope.calculateAmount();
    }

    $scope.searchCustomer = function ()
    {
        $('#js-input-search').focus();
        var url = '/customer?';
        $scope.searchCustomerQuery = $('#js-input-search').val();
        if ($scope.searchCustomerQuery) {
            url += '&sorts=raw(-name)&fields=*,raw(match(full_name) AGAINST("' + $scope.searchCustomerQuery + '") as name)&filters=raw=MATCH(`full_name`) AGAINST("' + $scope.searchCustomerQuery +'") or `full_name` like "%' + $scope.searchCustomerQuery + '%" or `phone` like "' + $scope.searchCustomerQuery + '%"';
        } else {
            url += '&sorts=-created_at';
        }
        url = $scope.buildUrl(url);
        $http.get(url).then(function (response) {
            $scope.customers = response.data.result;
            if ($(window).width() <= 976) {
                $('#js-dropdown').addClass('dropup');
            } else {
                $('#js-dropdown').removeClass('dropup');
            }
            $('#js-dropdown').addClass('open');
        });
    }

    $scope.showEditCustomerModal = function ()
    {
        $scope.newCustomer = JSON.parse(JSON.stringify($scope.selectedCustomer));
        $scope.setDistricts();
        $scope.setCommunes();
        $scope.newCustomer.editting = true;

        $('#create-customer-modal').modal('toggle');
    }

    $scope.fail = function (response) {
        let textErr = response.data.message;
        toastr.error(textErr);
    };

    $scope.getPreparedData = function (isEdit) {
        isEdit = isEdit || false;
        var data = {};
        data.items = [];
        var products = $scope.selectedProducts;
        for (var i = 0; i < products.length; i++)
        {
            var item = {
                product_name: products[i].name,
                quantity: products[i].quantity,
                price: products[i].price,
            };
            if (typeof products[i].product_sku_id != 'undefined') {
                item.product_id = products[i].product_id;
                item.product_sku_id = products[i].product_sku_id;
            } else {
                item.product_id = products[i].id;
            }
            data.items.push(item);
        }
        if ($scope.selectedCustomer.id !== undefined)
        {
            data.customer_id = $scope.selectedCustomer.id;
        }
        if (!isEdit) {
            data.customer = $scope.selectedCustomer;
            delete data.customer.editting;
        }
        if ($scope.orderInfo.discountType === 'price') {
            data.discount = $scope.orderInfo.discount;
        } else {
            data.discount = $scope.orderInfo.discount * $scope.orderInfo.subtotal / 100;
        }
        data.shipping_fee = $scope.orderInfo.shipping;
        data.amount = $scope.orderInfo.amount;
        data.delivery_address = $scope.selectedCustomer.address;
        data.commune_id = $scope.selectedCustomer.commune_id ? $scope.selectedCustomer.commune_id : null;
        data.district_id = $scope.selectedCustomer.district_id;
        data.province_id = $scope.selectedCustomer.province_id;
        data.delivery_note = $scope.orderInfo.delivery_note;
        data.note = $scope.orderInfo.note;

        return data;
    }

    $scope.saveOrder = function ()
    {
        var data = $scope.getPreparedData();
        $scope.disabledSave = true;
        $http({
            url: $scope.buildUrl('/order'),
            method: 'POST',
            data: JSON.stringify(data),
            headers: {
                'Content-Type': 'application/json'
            },
        }).then(function (response) {
            $scope.showSuccessModal('thêm đơn hàng', function () {
                window.location.href = '/admin/orders';
            });
        }).catch(function (error) {
            if (error.status == 422) {
                $scope.fail(error);
            } else {
                $scope.showErrorModal('thêm đơn hàng');
            }
            $scope.disabledSave = false;
        });
    }

    $scope.resetCheckedProduct = function ()
    {
        $scope.checkedProducts = [];
    }

    $scope.isEditable = function ()
    {
        var retVal = true;
        if (!$scope.order) {
            retVal = true;
        }

        if ($scope.order && $scope.order.status != 'PROCESSING' && $scope.order.status != 'PENDING') {
            retVal = false;
        }

        return retVal;
    }

    $scope.isEditAddress = function ()
    {
        var retVal = $scope.isEditable();
        if ($scope.order && $scope.order.status == 'DELIVERING') {
            retVal = true;
        }

        return retVal;
    }

    $scope.removeBlur = function ($event) {
        $($event.currentTarget).val() == 0 ? $($event.currentTarget).val('') : '';
    }

    $scope.addBlur = function ($event, value) {
        if (value < 1) {
            $($event.currentTarget).val(0);
        }
    }

    $scope.$watch('orderInfo.discount', function () {
        if ($scope.orderInfo.discountType == 'percent' && $scope.orderInfo.discount > 100)
        {
            $scope.orderInfo.discount = 100;
        }
        if ($scope.orderInfo.subtotal === 0 || !$scope.orderInfo.discount) {
            $scope.orderInfo.discount = 0;
        }
        if ($scope.orderInfo.discount > $scope.orderInfo.subtotal) {
            $scope.orderInfo.discount = $scope.orderInfo.subtotal;
        }
        $scope.calculateAmount();
    });

    $scope.$watch('orderInfo.discountType', function () {
        $scope.orderInfo.discount = 0;
        $scope.calculateAmount();
    });

    $scope.$watch('orderInfo.shipping', function () {
        if (!$scope.orderInfo.shipping) {
            $scope.orderInfo.shipping = 0;
        }
        $scope.calculateAmount();
    });

    $scope.$watch('orderInfo.subtotal', function () {
        if ($scope.orderInfo.subtotal == 0) {
            $scope.orderInfo.discount = 0;
        }

        if ($scope.orderInfo.subtotal < $scope.orderInfo.discount && $scope.orderInfo.discountType == 'price') {
            $scope.orderInfo.discount = $scope.orderInfo.subtotal;
        }
    });

    $scope.$watchGroup(['selectedProducts', 'selectedCustomer'], function () {
        if ($scope.selectedProducts.length > 0 && $scope.selectedCustomer)
        {
            $scope.disabledSave = false;
        } else {
            $scope.disabledSave = true;
        }
    });

    $scope.initialize();
}
