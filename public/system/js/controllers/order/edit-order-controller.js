system.controller('EditOrderController', EditOrderController);

function EditOrderController($scope, $http, $rootScope) {
    this.__proto__ = new CreateOrderController($scope, $http, $rootScope);
    $scope.editting = false;
    $scope.loaded = false;
    $scope.order = {};
    $scope.statusFlow = {};
    $scope.orderStatus = {
        'PROCESSING': 'Đang xử lý',
        'CANCELED': 'Đã huỷ',
        'PENDING': 'Chờ xuất hàng',
        'DELIVERING': 'Đang giao hàng',
        'FINISHED': 'Thành công',
        'RETURNED': 'Đã trả hàng'
    };
    $scope.orderActions = {
        'CANCELED': 'Huỷ',
        'PENDING': 'Chờ xuất hàng',
        'DELIVERING': 'Giao hàng',
        'FINISHED': 'Thành công',
        'RETURNED': 'Trả hàng'
    };
    $scope.statuses = [];

    $scope.selectedStatus = '';
    $scope.logs = [];
    $scope.orderFields = [];
    $scope.initialize = function () {
        $http.get($scope.buildUrl('/order?filters=id=' + orderId + '&embeds=customer&metric=first'))
            .then(function (response) {
                $scope.order = response.data.result;
                $scope.selectedProducts = $scope.order.items;
                $scope.orderInfo.note = $scope.order.note;
                $scope.orderInfo.delivery_note = $scope.order.delivery_note;
                $scope.orderInfo.delivery_address = $scope.order.delivery_address;
                $scope.orderInfo.amount = parseFloat($scope.order.amount);
                $scope.orderInfo.discount = parseFloat($scope.order.discount);
                $scope.orderInfo.shipping = parseFloat($scope.order.shipping_fee);
                for (var i = 0; i < $scope.selectedProducts.length; i++)
                {
                    $scope.selectedProducts[i].checked = true;
                    $scope.selectedProducts[i].quantity = $scope.selectedProducts[i].quantity;
                    $scope.selectedProducts[i].price = $scope.selectedProducts[i].price;
                }
                $scope.selectedCustomer = $scope.order.customer;
                $scope.selectedCustomer.commune_id = $scope.order.commune_id;
                $scope.selectedCustomer.district_id = $scope.order.district_id;
                $scope.selectedCustomer.province_id = $scope.order.province_id;
                $scope.selectedCustomer.address = $scope.orderInfo.delivery_address;
                $scope.selectedStatus = $scope.order.status;
                $scope.calculateSubtotal();
                $scope.calculateAmount();
                $scope.newCustomer = JSON.parse(JSON.stringify($scope.selectedCustomer));
                $scope.setDistricts();

                var url = $scope.buildUrl('/order/status-flow');
                $http.get(url)
                    .then(function (response) {
                        $scope.statusFlow = response.data;
                        $scope.loaded = true;
                    });
        });


        $http.get('/translate/order')
            .then(function (response) {
                $scope.orderFields = response.data;
            });
        $scope.getLogs();
    }

    $scope.getLogs = function () {
        $http.get($scope.buildUrl('/log/order/' + orderId))
            .then(function (response) {
                $scope.logs = response.data;
                for (var i = 0; i < $scope.logs.length; i++) {
                    if ($scope.logs[i].data) {
                        $scope.logs[i].data = $scope.logs[i].data;
                        $scope.logs[i].isDisplayItem = false;
                        $scope.logs[i].isDisplayCustomer = false;
                        $scope.logs[i].show = false;
                    }
                }
                if ($scope.logs.length > 0) {
                    $scope.logs[0].show = true;
                }
            });
    }

    $scope.changeStatus = function () {
        var element = $('#js-status');
        $scope.selectedStatus = $(element).val();
        if ($scope.loaded) {
            $scope.editting = true;
        }
    }

    $scope.saveEditOrder = function ()
    {
        var data = $scope.getPreparedData(true);
        data.id = orderId;

        $scope.disabledSave = true;
        $http({
            url: $scope.buildUrl('/order/' + data.id),
            method: 'PUT',
            data: JSON.stringify(data),
            headers: {
                'Content-Type': 'application/json'
            },
        }).then(function (response) {
            $scope.disabledSave = false;
            $scope.showSuccessModal('sửa đơn hàng', function () {
                window.location.href = '/admin/orders';
            });
        }).catch(function (error) {
            $scope.disabledSave = false;
            if (error.status == 422) {
                $scope.fail(error);
            } else {
                $scope.showErrorModal('sửa đơn hàng');
            }
        });
    }

    $scope.$watch('selectedProducts', function () {
        if ($scope.loaded) {
            $scope.editting = true;
        }
    }, true);

    $scope.$watchGroup(['selectedStatus', 'selectedCustomer', 'orderInfo.discount', 'orderInfo.shipping', 'orderInfo.note', 'orderInfo.delivery_note'], function () {
        if ($scope.loaded) {
            $scope.editting = true;
        }
    }, true);

    $scope.getStatusClass = function (status)
    {
        var goodFlow = [
            'PROCESSING',
            'PENDING',
            'DELIVERING',
            'FINISHED',
        ];
        var cssClass = '';
        if (goodFlow.includes(status)) {
            cssClass = 'success';
        } else {
            cssClass = status === 'CANCELED' ? 'warning' : 'danger';
        }

        return cssClass;
    }

    $scope.addSelectedCustomer = function () {
        $scope.newCustomer.delivery_address = $scope.newCustomer.address;
        $http({
            url: $scope.buildUrl('/order/validate-address'),
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

    $scope.changeStatus = function (status) {
        var title = 'Chuyển trạng thái';
        $scope.callConfirmModal({
            title: 'Chuyển trạng thái',
            text: 'chuyển trạng thái đơn hàng sang "' + $scope.orderStatus[status] + '"',
        }, function () {
            $http.patch($scope.buildUrl('/order') + '/' + $scope.order.id, {
                'status': status
            })
                .then(function (result) {
                    $scope.$applyAsync(function () {
                        $scope.order.status = status;
                    });
                    $scope.showSuccessModal(title);
                    $scope.getLogs();
                })
                .catch(function (error) {
                    $scope.showErrorModal(title);
                });
        });
    }

    $scope.initialize();
}