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
            $scope.getLocation();
        }
        $scope.getOrder();

    }

    $scope.find = () => {
        $scope.getLocation();
        var url = $scope.buildUrl('/customer/' + customerId)
        $http.get(url).then( function(response) {
            if ( response.data.status == 'successful' ) {
                $scope.customer = response.data.result;
                $scope.setLocations('district', false)
                $scope.setLocations('commune', false)
            }
        },function error (response) {
            toastr.error("Không tìm thấy khách hàng");
        });
    }

    $scope.getOrder = function () {
        let url = $scope.buildUrl('/order?filters=customer_id=' + customerId);
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

    $scope.getLocation = () => {
        var url = $scope.buildUrl('/province');
        $http.get(url).then(function (response) {
            if ( response.data.status == 'successful') {
                $scope.provinces = response.data.result;
            }
        });

    }

    $scope.save = () => {
        var data = $scope.buildDataCustomer()
        var method = 'POST';
        var url = base_api_url + '/customer';
        if ( $scope.mode == 'update' ) {
            method = 'PUT';
            url += '/' + data.id;
        }
        const success = function(response) {
            toastr.success( response.data.message);
            if ( $scope.mode == 'create' ) {
                setTimeout(() => {
                    window.location.href = '/admin/customers';
                }, 500);
            } else {
                $('#modal-default').modal('hide');
                $scope.resetFilters();
            }
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
            data : data,
        }).then(function (response) {
            if ( response.data.status == 'successful' ) {
                success(response)
            } else {
                fail(response)
            }
        }, fail)
    }

    $scope.buildDataCustomer = () => {
        var retVal = {};
        if ($scope.customer.id) {
            retVal.id = $scope.customer.id;
        }
        if ($scope.customer.full_name) {
            retVal.full_name = $scope.customer.full_name;
        }
        if ($scope.customer.address) {
            retVal.address = $scope.customer.address;
        } else {
            retVal.address = '';
        }
        if ($scope.customer.email) {
            retVal.email = $scope.customer.email;
        } else {
            retVal.email = '';
        }
        if ($scope.customer.phone) {
            retVal.phone = $scope.customer.phone;
        }
        if ($scope.customer.note) {
            retVal.note = $scope.customer.note;
        } else {
            retVal.note = '';
        }
        if ($scope.customer.commune) {
            retVal.commune_id = getCommuneId($scope.customer.commune);
        } else {
            retVal.commune_id = '';
        }
        if ($scope.customer.district) {
            retVal.district_id = getDistrictId($scope.customer.district);
        } else {
            retVal.district_id = '';
        }
        if ($scope.customer.province) {
            retVal.province_id = getProvinceId($scope.customer.province);
        } else {
            retVal.province_id = '';
        }
        return retVal;
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

    $scope.setLocations = function (type = '',reset = false) {
        if ( reset ) {
            if ( type == 'district' ) {
                $scope.customer.district = "";
                $scope.customer.commune = "";
                $scope.customer.province_id = getProvinceId($scope.customer.province);
            } else {
                $scope.customer.commune = "";
                $scope.customer.district_id = getDistrictId($scope.customer.district);
            }
        }
        var url = "";
        if ( type == 'district' ) {
            url = $scope.buildUrl('/district?filters=province_id=' + $scope.customer.province_id)
        } else {
            url = $scope.buildUrl('/commune?filters=district_id=' + $scope.customer.district_id)
        }
        for ( i=0;i<$scope.provinces.length;i++ ) {
            if ( $scope.customer.province_id == $scope.provinces[i].id ) {
                $scope.customer.province = $scope.provinces[i].name;
                break;
            }
        }
        if (type == 'district') {
            $http.get(url).then(function(response) {
                if ( response.data.status == 'successful' ) {
                    $scope.districts = response.data.result;
                    for ( i=0;i<$scope.districts.length;i++ ) {
                        if ( $scope.customer.district_id == $scope.districts[i].id ) {
                            $scope.customer.district = $scope.districts[i].name;
                            break;
                        }
                    }
                }
            })
        } else {
            $http.get(url).then(function(response) {
                if ( response.data.status == 'successful' ) {
                    $scope.communes = response.data.result;
                    for ( i=0;i<$scope.communes.length;i++ ) {
                        if ( $scope.customer.commune_id == $scope.communes[i].id ) {
                            $scope.customer.commune = $scope.communes[i].name;
                            break;
                        }
                    }
                }
            })
        }
    }

    function getProvinceId(name) {
        for(i=0;i<$scope.provinces.length;i++) {
            if ( name == $scope.provinces[i].name ) {
                return $scope.provinces[i].id;
            }
        }
    }

    function getDistrictId(name) {
        for(i=0;i<$scope.districts.length;i++) {
            if ( name == $scope.districts[i].name ) {
                return $scope.districts[i].id;
            }
        }
    }

    function getCommuneId(name) {
        for(i=0;i<$scope.communes.length;i++) {
            if ( name == $scope.communes[i].name ) {
                return $scope.communes[i].id;
            }
        }
    }

    initialize();
}
