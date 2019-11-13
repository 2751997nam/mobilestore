system.controller('ImportProductController', ImportProductController);

function ImportProductController ($scope, $http, $rootScope)
{
    this.__proto__ = new BaseController($scope, $http, $rootScope);
    $scope.step = 1;
    $scope.columns = [];
    $scope.example = [];

    $scope.fields = [
        {key: '', name: 'Không nhập vào'},
        {key: 'sku', name: 'Mã sản phẩm'},
        {key: 'name', name: 'Tên sản phẩm'},
        {key: 'slug', name: 'Slug'},
        {key: 'image_url', name: 'Đường dẫn ảnh'},
        {key: 'price', name: 'Giá'},
        {key: 'high_price', name: 'Giá thị trường'},
        {key: 'add_shipping_fee', name: 'Phí giao hàng'},
        {key: 'status', name: 'Trạng thái'},
        {key: 'description', name: 'Mô tả'},
        {key: 'content', name: 'Nội dung'},
        {key: 'note', name: 'Ghi chú'},
        {key: 'sold', name: 'Đã bán'},
        {key: 'view_count', name: 'Lượt xem'},
        {key: 'created_at', name: 'Thời gian tạo'},
        {key: 'updated_at', name: 'Thời gian cập nhật'},
    ];
    $scope.products = [];
    $scope.progress = 0;
    $scope.messages = [];
    $scope.fail = 0;
    $scope.success = 0;
    $scope.isUpdate = false;
    $scope.isShowHistory = false;
    $scope.file = null;
    $scope.validationFields = '';;
    $scope.importError = '';

    $scope.validateFields = function () {
        var check = false;
        for (var i = 0; i < $scope.columns.length; i++) {
            if ($scope.columns[i].field != '') {
                check = true;
                break;
            }
        }

        return check;
    }

    $scope.isNameNull = function () {
        var check = true;
        for (var i = 0; i < $scope.columns.length; i++) {
            if ($scope.columns[i].field == 'name') {
                check = false;
                break;
            }
        }

        return check;
    }

    $scope.nextStep = function () {
        if ($scope.step == 1) {
            if ($scope.validateFile()) {
                if (!$scope.loadFile()) {
                    $scope.validationFile = 'Tệp không đúng định dạng'
                    return;
                }
                $scope.validationFile = '';
            } else {
                return;
            }
        }
        if ($scope.step == 2) {
            if ($scope.isNameNull()) {
                $scope.validationFields = 'Vui lòng chọn trường liên kết với tên sản phẩm';
                $(window).scrollTop(0);
                return;
            } else if ($scope.validateFields()) {
                $scope.validationFields = '';
                $scope.importData(0);
            } else {
                $scope.validationFields = 'Vui lòng chọn ít nhất 1 trường liên kết';
                $(window).scrollTop(0);
                return;
            }
        }
        $scope.step = $scope.step + 1;
        var element = $('.progress-steps li:nth-child(' + $scope.step + ')');
        $(element).addClass('active');
        $('.box.no-border').addClass('d-none');
        var box = $('.box.no-border:nth-child(' + $scope.step + ')');
        $(box).removeClass('d-none');
    }

    $scope.prevStep = function () {
        var element = $('.progress-steps li:nth-child(' + $scope.step + ')');
        $(element).removeClass('active');
        $scope.step = $scope.step - 1;
        $(element).prev().addClass('active');
        $('.box.no-border').addClass('d-none');
        var box = $('.box.no-border:nth-child(' + $scope.step + ')');
        $(box).removeClass('d-none');
    }

    $scope.backToStep2 = function () {
        $scope.fail = 0;
        $scope.success = 0;
        $scope.messages = [];
        $scope.progress = 0;
        $scope.importError = '';
        $scope.isShowHistory = false;
        $scope.step = 2;
        $('.progress-steps li').removeClass('active');
        var element = $('.progress-steps li:nth-child(1)');
        $(element).addClass('active');
        $(element).next().addClass('active');
        $('.box.no-border').addClass('d-none');
        var box = $('.box.no-border:nth-child(' + $scope.step + ')');
        $(box).removeClass('d-none');
    }

    $scope.loadFile = async function () {
        try {
            var element = $('#file');
            if (element[0] && element[0].files) {
                $scope.file = $('#file')[0].files[0];
                var reader = new FileReader();
                reader.readAsText($scope.file);
                var products = [];
                reader.onload = await function(e) {
                    var content = e.target.result;
                    products = processData(content);
                    $scope.$apply(function () {
                        $scope.products = products;
                        var keys = Object.keys(products[0]);
                        $scope.columns = [];
                        $scope.example = [];
                        keys.forEach(function (key) {
                            $scope.columns.push({
                                key: key,
                                field: ''
                            });
                            $scope.example.push(products[0][key].replace(/"/g, ''));
                        });
                    });
                };
                if (reader.onload) {
                    if (products.length) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
            return false;
        } catch (error) {
            return false;
        }
    }

    $scope.validateFile = function () {
        var element = $('#file');
        if (element[0].files.length < 1) {
            $scope.validationFile = 'Vui lòng chọn một tệp tin';
            return false;
        }
        if (element[0].files[0].size > 1024 * 1024 * 2) {
            $scope.validationFile = 'Tệp tin có kích thước lớn hơn 2MB';
            return false;
        }
        if (getFileExtension(element[0].files[0].name) != 'csv') {
            $scope.validationFile = 'Tệp tin phải có định dạng .csv';
            return false;
        }

        return true;
    }

    $scope.importData = function (index) {
        if (index >= $scope.products.length) {
            return;
        }
        var products = [];
        var i = index;
        for (i = index; i < index + 20 && i < $scope.products.length; i++) {
            products.push($scope.products[i]);
        }
        $http.post($scope.buildUrl('/product/import'), {
            columns: $scope.columns,
            products: products,
            is_update: $scope.isUpdate
        }).then(function (response) {
            $scope.progress = Math.round(i / $scope.products.length * 100);
            if (response.data.messages) {
                $scope.messages  = $scope.messages.concat(response.data.messages);
            }
            if (response.data.success) {
                $scope.success += response.data.success;
            }
            if (response.data.fail) {
                $scope.fail += response.data.fail;
            }
            $scope.importData(i);
        }).catch(function (error) {
            $scope.importError = 'Đã có lỗi xảy ra khi gửi dữ liệu lên server!';
            $scope.nextStep();
        });
    }

    $scope.showHistory = function () {
        $scope.isShowHistory = !$scope.isShowHistory;
    }

    function processData(allText) {
        var objects = $.csv.toObjects(allText);
        
        return objects;
    }

    $scope.$watch('progress', function () {
        $('#js-progress').attr('aria-valuenow', $scope.progress);
        $('#js-progress').css('width', $scope.progress + '%');
        if ($scope.progress >= 100) {
            $scope.nextStep();
        }
    });
}

function getFileExtension(filename)
{
  var ext = /^.+\.([^.]+)$/.exec(filename);
  return ext == null ? "" : ext[1];
}
