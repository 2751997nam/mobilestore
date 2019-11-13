system.controller("FilterController", FilterController);

function FilterController($scope, $http, $rootScope, $interval, $window){
    this.__proto__ = new BaseController($scope, $http, $rootScope);
    $scope.controllerName = "FilterController";
    $scope.mode = "create";
    $scope.categories = [{id:null}];
    $scope.backupCategories = [];
    $scope.isChangeDisplayName = false;
    $scope.determinateValue = 17;
    $scope.filtering = {
        'values': [],
        'allCategory': false
    };
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
        if (filterId && filterId != '') {
            buildFiltering();
            $scope.mode = 'update';
        } else {
            $scope.filtering.categories = [];
        }
    }

    function buildFiltering() {
        $interval(function() {
            $scope.determinateValue += 1;
        }, 100, 0, true);
        $http({
                method: 'GET',
                url: base_api_url + '/filter/' + filterId + '?embeds=values,categories'
        }).then(function success(response) {
            if (response.data.status == 'successful') {
                setTimeout(function () {
                    $scope.$apply(function () {
                        $scope.filtering = response.data.result;
                    });
                    $scope.determinateValue = 100;
                },300);
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

    $scope.prerenderDisplayName = function() {
        if (!$scope.isChangeDisplayName) {
            $scope.filtering.display_name = $scope.filtering.name;
        }
    }

    $scope.save = function(mode = "") {
        loading();
        var dataFilter = buildData();
        var failMessages = validating(dataFilter);
        if (failMessages != '') {
            toastr.error(failMessages);
            loaded();
            return false;
        }
        var url = base_api_url + '/filter'
        var method = 'POST';
        if ($scope.mode == 'update') {
            method = 'PUT';
            url += '/' + dataFilter.id;
        }
        $http({
            method: method,
            url: url,
            data: dataFilter
        }).then(function success(response) {
            if (response.data.status == 'successful' && response.data.result.id != 'undefined') {
                toastr.success('Cập nhật bộ lọc thành công.');
                if (mode == 'saveAndExit') {
                    $window.location.href = '/admin/filters';
                } else if (mode == 'save' &&  $scope.mode == 'create') {
                    $window.location.href = '/admin/filters/' + response.data.result.id;
                }
                $scope.mode = 'update';
            } else {
                toastr.error('Cập nhật bộ lọc không thành công. ' + response.data.message);
            }
            loaded();
        });

    }

    function validating(data) {
        var retVal = '';
        if (typeof data.name == 'undefined' || data.name == '') {
            retVal += '<p>Chưa nhập tên bộ lọc.</p>';
        }
        if (typeof data.display_name == 'undefined' || data.name == '') {
            retVal += ' <p>Chưa nhập tên hiển thị trên web.</p>';
        }
        if (typeof data.values == 'undefined' || data.values.length == 0) {
            retVal += ' <p>Chưa nhập nội dung cho bộ lọc.</p>';
        }
        if (data.categoryIds.length == 0) {
            retVal += ' <p>Chưa chọn danh mục áp dụng.</p>'
        }
        return retVal;
    }

    function buildData() {
        var retVal = {
            categoryIds: []
        };
        if (typeof $scope.filtering.id != 'undefined' && $scope.filtering.id != '') {
            retVal.id = $scope.filtering.id;
        }
        if (typeof $scope.filtering.name != 'undefined' && $scope.filtering.name != '') {
            retVal.name = $scope.filtering.name;
        }
        if (typeof $scope.filtering.display_name != 'undefined' && $scope.filtering.display_name != '') {
            retVal.display_name = $scope.filtering.display_name;
        }
        if ($scope.filtering.values.length > 0) {
            retVal.values = $scope.filtering.values;
        }
        if ($scope.filtering.categories && $scope.filtering.categories.length > 0) {
            $scope.filtering.categories.forEach(function(category) {
                retVal.categoryIds.push(category.id);
            });
        }
        return retVal;
    }

    $scope.inputFilteringValue = function(event) {
        if (event.which == 13 || event.which == 188) {
            var valueSelecteds = [];
            $scope.filtering.values.forEach(function (val) {
                valueSelecteds.push(val.name);
            });
            var arrValues = $scope.filteringValue.split(',');
            for (var i = 0; i < arrValues.length; i++) {
                var value = arrValues[i].trim();
                if (value != '' && valueSelecteds.indexOf(value) == -1) {
                    var obj = {
                        'id': makeId(5),
                        'name': value
                    };
                    $scope.filtering.values.push(obj);
                    valueSelecteds.push(value);
                }
            }
            $scope.filteringValue = '';
        }
    }

    $scope.removeValue = function (idx) {
        var param = {
            title: 'Xóa nội dung bộ lọc',
            text: 'xóa nội dung bộ lọc này',
            arg: {idx: idx}
        };
        $scope.callConfirmModal(param, function(arg) {
            $scope.$apply(function () {
                $scope.filtering.values.splice(arg.idx, 1);
            });
        });
    };

    $scope.checkerApplyCategory = function() {
        if ($scope.filtering.allCategory) {
            $scope.backupCategories = $scope.filtering.categories;
            $scope.filtering.categories = $scope.categories;
        } else {
            $scope.filtering.categories = $scope.backupCategories;
        }
    }

    $scope.chooseCategories = function () {
        $scope.backupCategories = $scope.filtering.categories;
        $scope.filtering.allCategory = false;
        if ($scope.filtering.categories.length == $scope.categories.length) {
            $scope.filtering.allCategory = true;
        }
    }

    function loading() {
        $scope.determinateValue = 17;
        $scope.loaddingButton('#btn-save-1');
        $scope.loaddingButton('#btn-save-2');
        $('#btn-cancel').attr("disabled", true);
        $interval(function() {
            $scope.determinateValue += 1;
        }, 100, 0, true);
    }

    function loaded() {
        $scope.determinateValue = 100;
        $scope.stopLoaddingButton('#btn-save-1');
        $scope.stopLoaddingButton('#btn-save-2');
        $('#btn-cancel').attr("disabled", false);
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

    initialize();
}
