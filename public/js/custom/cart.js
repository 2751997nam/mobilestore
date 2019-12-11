var app = angular.module('myApp', []);

app.controller('CartController', function($scope, $http) {

    $scope.cartItems = [];
    $scope.total = 0;

    $scope.initialize = function () {
        let items = $('#js-cart-item').val();
        $scope.cartItems = JSON.parse(items);
        $scope.calculateTotal();
    }

    $scope.calculateTotal = function () {
        $scope.total = 0;
        $scope.cartItems.forEach(function (item) {
            $scope.total += item.price * item.quantity;
        });
    }

    $scope.removeItem = function (index) {
        $http.delete('/cart/' +  $scope.cartItems[index].id)
            .then(function (response) {
                if (response.data.status == 'successful') {
                    $scope.cartItems.splice(index, 1);
                    $scope.calculateTotal();
                    $('.cart_count span').text($scope.cartItems.length);
                } else {
                    alert('Đã xảy ra lỗi');
                }
            });
    }

    $scope.formatCurrency = function(num) {
        if (num != parseFloat(num)) {
            num = 0;
        }
        var newstr = '';
        num = parseFloat(num);
        var p = num.toFixed(2).split(".");
        var chars = p[0].split("").reverse();
        var count = 0;
        for (x in chars) {
            count++;
            if (count % 3 == 1 && count != 1) {
                newstr = chars[x] + '.' + newstr;
            } else {
                newstr = chars[x] + newstr;
            }
        }

        return newstr;
    }

    $scope.initialize();
});
