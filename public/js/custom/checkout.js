var app = angular.module('myApp', []);

app.controller('myCtrl', function($scope, $http) {

    $scope.order = {
        delivery_address: '',
        delivery_note: ''
    }

    $scope.customer = {
        full_name: '',
        email: '',
        phone: '',
        address: ''
    };

    $scope.success = false;

    $scope.getProvinces = function() {
        $http({
            method: 'GET',
            url: '/api/province'
        }).then(function successCallback(response) {
            $scope.provinces = response.data.result;
        }, function errorCallback(response) {

        });
    }

    $scope.getDistrict = function() {
        if ($scope.order.province_id) {
            $http({
                method: 'GET',
                url: '/api/district?province_id=' + $scope.order.province_id
            }).then(function successCallback(response) {
                $scope.districts = response.data.result;
            }, function errorCallback(response) {

            });

        }

    }

    $scope.getCommune = function() {
        if ($scope.order.district_id) {
            $http({
                method: 'GET',
                url: '/api/commune?district_id=' + $scope.order.district_id
            }).then(function successCallback(response) {
                $scope.communes = response.data.result;
            }, function errorCallback(response) {

            });

        } else {
            $scope.communes = [];
        }

    }

    $scope.changeProvince = function () {
        $scope.district_id = null;
        $scope.commune_id = null;
        $scope.getDistrict();
    };

    $scope.hasError = function(fieldName) {
        let retval = false;
        if ($scope.errors && $scope.errors[fieldName]) {
            retval = true;
        }

        return retval;
    }

    $scope.getErrorMsg = function(fieldName) {
        let retval = '';
        if ($scope.errors && $scope.errors[fieldName]) {
            retval = $scope.errors[fieldName][0];
        }

        return retval;
    }

    $scope.submit = function () {
        let data = $scope.order;
        $scope.customer.address = $scope.order.delivery_address;
        $scope.customer.province_id = $scope.order.province_id;
        $scope.customer.district_id = $scope.order.district_id;
        $scope.customer.commune_id = $scope.order.commune_id;

        data.customer = $scope.customer;
        $http.post('/order', JSON.stringify(data)).then(function (response) {
            if (response.data.status == 'successful') {
                $scope.success = true;
                $('.cart_count span').text(0);
                $('#cart-preview').html('');
            } else {
                alert(response.data.message);
            }
        }).catch(function (response) {
            alert('Hệ thống bị lỗi, vui lòng thử lại.');
            console.log(response);
        });
    }

    $scope.getProvinces();
});