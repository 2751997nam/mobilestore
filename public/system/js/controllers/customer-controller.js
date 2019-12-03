system.controller("CustomerController", CustomerController);

function CustomerController($scope, $http, $rootScope){
    this.__proto__ = new BaseController($scope, $http, $rootScope);
    this.__proto__ = new PaginationController($scope, $http, $rootScope);
    $scope.cities = [];
    $scope.customer = {};
    $scope.mode = "create";
    $scope.provinces = {};
    $scope.districts = {};
    $scope.communes = {};
    $scope.orderStatus = {
        'PROCESSING': 'Đang xử lý',
        'CANCELED': 'Đã huỷ',
        'PENDING': 'Chờ xuất hàng',
        'DELIVERING': 'Đang giao hàng',
        'FINISHED': 'Thành công',
        'RETURNED': 'Đã trả hàng'
    };

    $scope.controllerName = "CustomerController";

    function initialize() {
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
        if ( customerId && customerId != "" ) {
            $scope.mode = "update";
            $scope.find();
        } else {
            $scope.customer = {};
        }
        $scope.getOrder();

    }

    $scope.find = () => {
        var url = $scope.buildUrl('/api/customer/' + customerId)
        $http.get(url).then( function(response) {
            if ( response.data.status == 'successful' ) {
                $scope.customer = response.data.result;
            }
        },function error (response) {
            toastr.error("Không tìm thấy khách hàng");
        });
    }

    $scope.getOrder = function () {
        let url = $scope.buildUrl('/api/order?customer_id=' + customerId);
        url = $scope.buildPaginationUrl(url);
        $http.get(url).then(function (response) {
            if ( response.data.status == 'successful' ) {
                $scope.orders = response.data.result;
                $scope.meta = response.data.meta;
                $scope.index = $scope.meta.page_id+1;
            }
        });
    }

    $scope.next = function () {
        if ($scope.increasePageId()) {
            $scope.getOrder();
        }
    };

    $scope.prev = function () {
        if ($scope.decreasePageId()) {
            $scope.getOrder();
        }
    };

    $scope.changePage = function (page) {
        if ($scope.changePageId(page)) {
            $scope.getOrder();
        }
    };

    $scope.save = () => {
        var data = $scope.customer.note;
        var method = 'POST';
        var url =  '/api/customer/' + $scope.customer.id;
        method = 'PUT';
        const success = function(response) {
            toastr.success( response.data.message);
            $('#modal-default').modal('hide');
        };
        const fail = function (response){
            if (response.data.status === "fail") {
                let textErr = '';
                Object.keys(response.data.result).forEach((item)=>{
                    textErr += response.data.result[item].join(', ');
                });
                toastr.error(response.data.message + textErr);
            }
        };
        $http({
            method: method ,
            url : url,
            data : {note: data},
        }).then(function (response) {
            if ( response.data.status == 'successful' ) {
                success(response)
            } else {
                fail(response)
            }
        }, fail)
    }

    $scope.resetFilters = () => {
        $scope.customer = {};
        $scope.find();
    }

    $scope.openModal = (customer) => {
        $scope.currentCustomer = angular.copy(customer);
        $('#modal-default').modal();
    }

    $scope.closeModal = () => {
        $('#modal-default').modal('hide');
        $scope.customer = angular.copy($scope.currentCustomer);
    }

    initialize();
}
