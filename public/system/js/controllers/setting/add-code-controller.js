system.controller('AddCodeController', AddCodeController);

var editorSelectors = [
    'start-head',
    'end-head',
    'start-body',
    'end-body'
];

function AddCodeController ($scope, $http, $rootScope) {
    this.__proto__ = new BaseController($scope, $http, $rootScope);
    $scope.editorOptions = [
        {
            title: 'Sau thẻ <head>',
            hint: 'Thêm mã vào sau thẻ <head>',
        },
        {
            title: 'Trước thẻ </head>',
            hint: 'Thêm mã vào ngay trước thẻ </head>',
        },
        {
            title: 'Sau thẻ <body>',
            hint: 'Thêm mã vào sau thẻ <body>',
        },
        {
            title: 'Trước thẻ </body>',
            hint: 'Thêm mã vào ngay trước thẻ </body>',
        }
    ];

    $scope.editors = [];
    $scope.options = [];

    $scope.initialize = function () {
        $http.get(apiUrl + '/code').then(function (response) {
            data = response.data;
            if (data.status == 'successful') {
                $scope.options = data.result;
                for (var j = 0; j < editorSelectors.length; j++) {
                    if (editorSelectors[j] in $scope.options) {
                        $scope.editors[j].setValue($scope.options[editorSelectors[j]].value);
                    }
                }
            }
        });
        for (var i = 0; i < $scope.editorOptions.length; i++) {
            $scope.editorOptions[i].selector = editorSelectors[i];
        }
        $(document).ready(function () {
            ace.require("ace/ext/language_tools");
            // enable autocompletion and snippets
            for (var i = 0; i < editorSelectors.length; i++) {
                $scope.editors[i] = ace.edit(editorSelectors[i]);
            }
            for (var i = 0; i < $scope.editors.length; i++) {
                $scope.editors[i].setTheme("ace/theme/monokai");
                $scope.editors[i].session.setMode("ace/mode/html");
                $scope.editors[i].session.setTabSize(4);
                $scope.editors[i].setOptions({
                    enableBasicAutocompletion: true,
                    enableSnippets: true,
                    enableLiveAutocompletion: false,
                    autoScrollEditorIntoView: true,
                    maxLines: 30
                });
            }
        });
    }

    $scope.save = function () {
        swal({
            title: $scope.upperCaseFirstLetter('Cảnh báo!'),
            html: '<div style="font-size: 1.5em; color: #545454; font-weight: 300;">'
                + 'Tính năng này có thể làm thay đổi toàn bộ cấu trúc trang web. '
                + 'Bạn chỉ nên nhấn lưu khi chắc chắn không có lỗi gì xảy ra.<div>'
                + '<div style="margin-top: 20px">Bạn có muốn lưu không?</div>',
            type: "warning",
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonText: "Có",
            cancelButtonText: "Không"
        }).then(function (isConfirm) {
            if (isConfirm.value) {
                var data = [];
                for (var i = 0; i < $scope.editors.length; i++) {
                    data.push({
                        key: editorSelectors[i],
                        value: $scope.editors[i].getValue()
                    });
                }
                $http({
                    url: apiUrl + '/code',
                    method: 'POST',
                    data: {data: JSON.stringify(data)}
                }).then(function (response) {
                    var data = response.data;
                    if (data.status == 'successful') {
                        $scope.showSuccessModal(data.message, function () {
                            
                        });
                    }
                }).catch(function (error) {
                    error = error.data;
                    if (error.status == 'fail') {
                        $scope.showErrorModal(data.message);
                    } else {
                        $scope.showErrorModal('Lưu thất bại!', 'Lưu thất bại!');
                    }
                });
            }
        });

    }

    $scope.initialize();
}
