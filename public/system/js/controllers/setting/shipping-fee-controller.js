system.controller('ShippingFeeController', ShippingFeeController);

function ShippingFeeController($scope, $http, $rootScope, $timeout) {
    $scope.baseController = this.__proto__ = new BaseController($scope, $http, $rootScope);
    $scope.shippingFees = [];
    $scope.shippingFee = {};
    $scope.mode = '';
    $scope.domain = '';
    $scope.locations = [];

    $scope.initialize = function () {
        $scope.getLocations();
        $scope.getShippingFees();
    }

    $scope.datetimeFormat = function (timeObject) {
        return new Date(timeObject).toISOString();
    }


    $scope.getShippingFees = function(){
        var url = $scope.buildUrl('/shipping_fee');
        $http.get(url)
            .then(function (response) {
                $scope.shippingFees = response.data.result;
                for (var i = 0; i < $scope.shippingFees.length; i++) {
                    for (var j = 0; j < $scope.shippingFees[i].items.length; j++) {
                        $scope.shippingFees[i].items[j].max_amount = parseInt($scope.shippingFees[i].items[j].max_amount);
                        $scope.shippingFees[i].items[j].min_amount = parseInt($scope.shippingFees[i].items[j].min_amount);
                        $scope.shippingFees[i].items[j].fee = parseInt($scope.shippingFees[i].items[j].fee);
                    }
                }
            });
    }

    const fail = function (btnSelector = '#btnSave', messenger){
        $scope.loading = false;
        $scope.baseController.stopLoaddingButton(btnSelector);
        toastr.error(messenger);
    };

    const success = function(btnSelector = '#btnSave', messenger) {
        $scope.loading = false;
        toastr.success("Thành công!");
        $scope.baseController.stopLoaddingButton(btnSelector);
    };

    $scope.buildRangeAmountText = function (item) {
        var retVal = '';
        if (item.max_amount) {
            retVal = $scope.moneyToString(parseInt(item.min_amount)) + '₫ - ' + $scope.moneyToString(parseInt(item.max_amount)) + '₫';
        } else {
            retVal = $scope.moneyToString(parseInt(item.min_amount)) + '₫ trở lên';
        }
        return retVal;
    };

    $scope.buildFeeText = function (item) {
        return $scope.moneyToString(parseInt(item.fee)) + '₫';;
    };

    $scope.showModal = function (obj, mode) {
        $scope.mode = mode;
        if (!obj) {
            obj = {
                items: [{
                    min_amount: 0,
                    fee: 40000
                }]
            };
        }
        $scope.shippingFee = angular.copy(obj);
        $('#shipping-fee-modal').modal('show');
    };

    $scope.getLocations = function () {
        var url = $scope.buildUrl('/province?page_size=-1&sorts=-sorder');
        $http.get(url)
            .then(function (response) {
                $scope.locations = response.data.result;
                $scope.locations.unshift({
                    id: 0,
                    name: 'Các tỉnh/thành khác'
                })
            });
    };

    $scope.addItem = function (obj) {
        obj.items.push({
        })
    };

    $scope.save = function(type){
        var btnSelector = type == 'create' ? '#btnAdd' : '#btnUpdate';
        $scope.baseController.loaddingButton(btnSelector);
        if (typeof $scope.shippingFee.location_id == 'undefined') {
            fail('#btnAdd', 'Vui lòng chọn khu vực!');
            return;
        }

        if ($scope.shippingFee.items.length > 0) {
            for (var i = 0; i < $scope.shippingFee.items.length; i++) {
                var item = $scope.shippingFee.items[i];
                if (typeof item.min_amount == 'undefined' || item.min_amount == null ||  item.min_amount < 0) {
                    fail(btnSelector, 'Vui lòng nhập giá trị tối thiểu đúng định dạng (#' + (i + 1) + ')');
                    return;
                }
                if (typeof item.max_amount != 'undefined' && item.max_amount != null && item.max_amount < item.min_amount) {
                    fail(btnSelector, 'Vui lòng nhập giá trị tối đa không nhỏ hơn giá trị tối thiểu (#' + (i + 1) + ')');
                    return;
                }
                if (typeof item.fee == 'undefined' || item.fee == null || item.fee < 0) {
                    fail(btnSelector, 'Vui lòng nhập phí vận chuyển đúng định dạng (#' + (i + 1) + ')');
                    return;
                }
            }
        } else {
            fail(btnSelector, 'Vui lòng thêm ít nhất 1 phí vận chuyển cho khu vực');
            return;
        }
        if (typeof $scope.shippingFee.id != 'undefined' && $scope.shippingFee.id) {
            $http({
                method: "POST",
                url: $scope.buildUrl('/shipping-fee/update'),
                data: {
                    locationId: $scope.shippingFee.location_id,
                    items: $scope.shippingFee.items,
                    id: $scope.shippingFee.id
                }
            }).then(function (response) {
                if (response.data.status == "successful") {
                    success(btnSelector, 'Cập nhật thành công!');
                    $scope.getShippingFees();
                    $('#shipping-fee-modal').modal('toggle');
                    $scope.shippingFee = {};
                } else if (response.data.status == "duplicate") {
                    fail(btnSelector, 'Khu vực đã tồn tại, vui lòng chọn khu vực khác!');
                } else {
                    fail(btnSelector, 'Có lỗi xảy ra, vui lòng thử lại!');
                }
            })
        } else {
            $http({
                method: "POST",
                url: $scope.buildUrl('/shipping-fee/add'),
                data: {
                    locationId: $scope.shippingFee.location_id,
                    items: $scope.shippingFee.items
                }
            }).then(function (response) {
                if (response.data.status == "successful") {
                    success(btnSelector, 'Thêm mới thành công!');
                    $scope.getShippingFees();
                    $('#shipping-fee-modal').modal('toggle');
                    $scope.shippingFee = {};
                } else if (response.data.status == "duplicate") {
                    fail(btnSelector, 'Khu vực đã tồn tại, vui lòng chọn khu vực khác!');
                } else {
                    fail(btnSelector, 'Có lỗi xảy ra, vui lòng thử lại!');
                }
            })
        }
    };

    $scope.deleteItem = function (index) {
        $scope.shippingFee.items.splice(index, 1);
    }

    $scope.delete = function(obj){
        $scope.callConfirmDeleteModal({
            url: $scope.buildUrl("/shipping-fee/delete/" + obj.id),
            title: 'Xóa khu vực',
            text: 'xoá khu vực ' + obj.location_name,
            success: function (result) {
                $scope.getShippingFees();
            },
        });
    }



    $scope.initialize()
}
