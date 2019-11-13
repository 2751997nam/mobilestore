system.controller('FooterController', FooterController);


function FooterController($scope, $http, $rootScope) {
    this.__proto__ = new BaseController($scope, $http, $rootScope);
    $scope.types = [
        {"code": "link", "name": "Danh sách link"},
        {"code": "text", "name": "Đoạn văn bản"},
        {"code": "html", "name": "Trình soạn thảo"}
    ];
    $scope.footer = {
        key: 'footer',
        items: [
            {
                title: "",
                type: $scope.getByField($scope.types, 'code', 'link'),
                links: [{label: null, link: null}],
                texts: [{label: null}],
                edit_title: true,
                html: null
            }
        ]
    }
    $scope.initialize = function () {
        toastr.options = {
            "debug": false,
            "positionClass": "toast-bottom-right",
            "onclick": null,
            "fadeIn": 300,
            "fadeOut": 1000,
            "timeOut": 5000,
            "extendedTimeOut": 1000
        };
        buildSettingFooter();
    }

    function buildSettingFooter() {
        $http({
            method: 'GET',
            url: base_api_url + '/option?filters=key=footer'
        }).then(function success(response) {
            if (response.data.status == 'successful' && response.data.result.length > 0) {
                $scope.footer = {};
                $scope.footer.key = 'footer';
                $scope.footer.id = response.data.result[0].id;
                var items = [];
                var values = JSON.parse(response.data.result[0].value);
                values.forEach(function (item) {
                   var newItem = {
                       title: item.title,
                       type: $scope.getByField($scope.types, 'code', item.type),
                       links: [{label: null, url: null}],
                       texts: [{label: null}],
                       html: null
                   };
                   if (item.title == '') {
                       newItem.edit_title = true;
                   }
                   if (item.type == 'link') {
                       if (item.values.length > 0) {
                           newItem.links = [];
                           item.values.forEach(function(value) {
                               newItem.links.push({
                                   label: value.title,
                                   url: value.url
                               });
                           });
                       }
                   }
                   if (item.type == 'text') {
                       if (item.values.length > 0) {
                           newItem.texts = [];
                           item.values.forEach(function(value) {
                               newItem.texts.push({
                                   label: value.title
                               });
                           });
                       }
                   }
                   if (item.type == 'html') {
                        newItem.html = item.values;
                   }
                    items.push(newItem);
                });
                $scope.footer.items = items;
            }
        });
    }

    $scope.save = function () {
        loading();
        setTimeout(function () {
            var data = buildData();
            var method = 'POST';
            var url = base_api_url + '/option';
            if (typeof data.id != 'undefined') {
                method = 'PUT';
                url += '/' + data.id;
            }
            $http({
                method: method,
                url: url,
                data: data
            }).then(function success(response) {
                if (response.data.status == 'successful') {
                    if (typeof $scope.footer.id == 'undefined') {
                        $scope.footer.id = response.data.result.id;
                    }
                    toastr.success('Cập nhật cấu hình thành công.');
                }
                loaded();
            });
        }, 300);

    }

    function buildData() {
        var retVal = {
            key: 'footer',
            value: ''
        };
        if (typeof $scope.footer.id != 'undefined' && $scope.footer.id) {
            retVal.id = $scope.footer.id;
        }
        var value = [];
        $scope.footer.items.forEach(function(item) {
            var newItem = {
                title: item.title,
                type: item.type.code
            };
            if (item.type.code == 'link') {
                newItem.values = [];
                item.links.forEach(function (link) {
                   var newLink = {
                       title: link.label ? link.label : '',
                       url: link.url ? link.url : ''
                   };
                   newItem.values.push(newLink);
                });
            }
            if (item.type.code == 'text') {
                newItem.values = [];
                item.texts.forEach(function (text) {
                   var newText = {
                       title: text.label ? text.label : ''
                   };
                   newItem.values.push(newText);
                });
            }
            if (item.type.code == 'html') {
                newItem.values = item.html ? item.html : '';
            }
            value.push(newItem);
        });
        retVal.value = JSON.stringify(value);
        return retVal;
    }

    $scope.saveTitle = function(item) {
        if (item.title != null && item.title != '') {
            item.edit_title = false;
        } else {
            toastr.error('Bạn phải nhập tiêu đề cho cột');
        }
    }

    $scope.addItem = function() {
        $scope.footer.items.push({
            title: "",
            type: $scope.getByField($scope.types, 'code', 'link'),
            links: [{label: null, link: null}],
            texts: [{label: null}],
            edit_title: true,
            html: null
        });
    }
    $scope.removeItem = function(idx) {
        var param = {
            title: 'Xóa cấu hình cột này',
            text: 'xóa cấu hình cột này',
            arg: {idx: idx}
        };
        $scope.callConfirmModal(param, function(arg) {
            $scope.$apply( function () {
                $scope.footer.items.splice(arg.idx, 1);
            });
        });
    }

    $scope.addValue = function(item) {
        if (item.type.code == 'link') {
            item.links.push({label: '', url: ''});
        }
        if (item.type.code == 'text') {
            item.texts.push({label: ''});
        }
    }

    $scope.remove = function(idx, item) {
        if (item.type.code == 'link') {
            item.links.splice(idx, 1);
        }
        if (item.type.code == 'text') {
            item.texts.splice(idx, 1);
        }
    }

    function loading() {
        $scope.loaddingButton('#btnSave');
        $scope.loaddingButton('#btnSave2');
    }

    function loaded() {
        $scope.stopLoaddingButton('#btnSave');
        $scope.stopLoaddingButton('#btnSave2');
    }

    $scope.initialize();
}
