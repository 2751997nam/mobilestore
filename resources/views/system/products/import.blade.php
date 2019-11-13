@extends('system.layout.main', [
    'ngController' => 'ImportProductController'
])

@section('title')
    <title>Nhập sản phẩm</title>
@endsection

@section('css')
    <style>
        .progress-form-wrapper{
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .progress-steps {
            padding: 0 0 24px;
            margin: 0;
            list-style: none outside;
            overflow: hidden;
            color: #ccc;
            width: 100%;
            display: -webkit-inline-flex;
            display: -webkit-inline-box;
            display: inline-flex;
        }

        .progress-steps li{
            width: 25%;
            float: left;
            padding: 0 0 .8em;
            margin: 0;
            text-align: center;
            position: relative;
            border-bottom: 4px solid #ccc;
            line-height: 1.4em
        }

        .progress-steps li.active {
            border-color: #a16696;
            color: #a16696;
        }

        .progress-steps li.active::before {
            border-color: #a16696;
            color: #a16696;
        }

        .progress-steps li::before {
            content: "";
            border: 4px solid #ccc;
            border-radius: 100%;
            width: 15px;
            height: 15px;
            position: absolute;
            bottom: 0;
            left: 50%;
            margin-left: -10px;
            margin-bottom: -9px;
            background: #fff;
        }

        .text-example {
            color: #a7a4a4;
            -webkit-line-clamp: 5;
            -webkit-box-orient: vertical;
            display: -webkit-box;
            text-overflow: ellipsis;
            overflow: hidden;
            word-break: break-word;
        }

        .history {
            line-height: 30px;
        }
    </style>
@endsection

@section('script')
    <script src="/system/js/scripts/jquery-csv.min.js"></script>
    <script src="/system/js/controllers/product/import-product-controller.js?v=<?= Config::get("sa.version") ?>"></script>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
            <div class="content" ng-cloak>
                <div class="header">
                    <div class="pull-left">
                        <div class="back">
                            <a style="color: #637381" href="/admin/products"><i class="fa fa-chevron-left"></i> Danh sách sản phẩm</a>
                        </div>
                        <h3 class="">Nhập sản phẩm</h3>
                    </div>
                    <div class="clearfix">
                    </div>
                </div>
                <div class="row">
                    <div class="progress-form-wrapper col-md-10 col-md-offset-1">
                        <ol class="progress-steps">
                            <li class="active">Tải lên tập tin CSV</li>
                            <li class="">Sắp xếp cột</li>
                            <li class="">Nhập vào</li>
                            <li class="">Hoàn Thành!</li>
                        </ol>
                    </div>
                    <div class="body col-md-10 col-md-offset-1">
                        <div class="box no-border">
                            <div class="box-header with-border">
                                <h3>Nhập sản phẩm từ file CSV</h3>
                                <span>Công cụ này cho phép bạn nhập (hoặc hợp nhất) dữ liệu sản phẩm vào cửa hàng của bạn từ một tập tin CSV.</span>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="form-group row">
                                    <div class="col-md-4">Chọn tập tin CSV từ máy tính của bạn:</div>
                                    <div class="col-md-8">
                                        <input type="file" id="file">
                                        <span>kích thước tối đa 2MB</span>
                                        <br>
                                        <span class="text-danger" ng-if="validationFile">@{{ validationFile }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-4">Cập nhật các sản phẩm hiện có</div>
                                    <div class="col-md-8">
                                        <input type="checkbox" ng-model="isUpdate" ng-checked="isUpdate == true">
                                        Sản phẩm đã có khớp mã sản phẩm sẽ được cập nhật. Sản phẩm không tồn tại sẽ được bỏ qua.
                                    </div>

                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer clearfix">
                                <button class="btn btn-primary pull-right" ng-click="nextStep()">Tiếp tục</button>
                            </div>
                        </div>
                        <div class="box no-border d-none">
                            <div class="box-header with-border">
                                <h3>Lập bản đồ các trường CSV cho sản phẩm</h3>
                                <span>Chọn các cột tương ứng từ file CSV để gắn với sản phẩm, hoặc bỏ qua trong quá trình nhập.</span>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="form-group row" ng-if="validationFields">
                                    <div class="col-md-12">
                                        <span class="text-danger">@{{ validationFields }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label for="">Tên cột</label>
                                    </div>
                                    <div class="col-md-8">
                                        <label for="">Liên kết tới trường</label>
                                    </div>
                                </div>
                                <div class="form-group row" ng-repeat="column in columns">
                                    <div class="col-md-4">
                                        @{{ column.key }}
                                        <p class="text-example">Mẫu: @{{ example[$index] }}</p>
                                    </div>
                                    <div class="col-md-8">
                                        <select
                                            class="form-control" 
                                            ng-model="column.field" 
                                            ng-options="field.key as field.name for field in fields">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer clearfix">
                                <button class="btn btn-primary pull-right ml-3" ng-click="nextStep()">Tiếp tục</button>
                                <button class="btn btn-default pull-right" ng-click="prevStep()">Quay lại</button>
                            </div>
                        </div>
                        <div class="box no-border d-none">
                            <div class="box-header with-border">
                                <h3>Đang nhập</h3>
                                <span>Sản phẩm của bạn đang được nhập vào...</span>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <div class="progress active" style="margin-top: 20px">
                                            <div id="js-progress" class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                <span>@{{ progress }}%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer clearfix">
                            </div>
                        </div>
                        <div class="box no-border d-none">
                            <div class="box-header no-border text-center">
                                <h3>@{{ importError ? 'Lỗi' : 'Nhập sản phẩm thành công' }}</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body text-center">
                                <div class="mb-4">
                                    <img ng-if="!importError" style="width: 100px" src="/system/images/success.svg" alt="success">
                                    <img ng-if="importError" style="width: 100px" src="/system/images/error.svg" alt="error">
                                </div>
                                <span ng-if="importError">@{{ importError }}</span>
                                <span ng-if="!importError">
                                    <strong>@{{ success }}</strong> sản phẩm đã được nhập vào. <strong>@{{ fail }}</strong> sản phẩm đã bị bỏ qua.
                                    <p><a href="javascript:void(0)" ng-if="fail > 0" ng-click="showHistory()">Xem nhật ký nhập vào</a></p>
                                </span>
                                <div ng-if="isShowHistory && fail > 0" class="mt-5 text-left">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <label for="">Sản phẩm</label>
                                        </div>
                                        <div class="col-md-7">
                                            <label for="">Lý do thất bại</label>
                                        </div>
                                    </div>
                                    <div class="row history" ng-repeat="message in messages">
                                        <div class="col-md-5">@{{ message.name }}</div>
                                        <div class="col-md-7">@{{ message.message }}</div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer clearfix">
                                <a href="/admin/products" class="btn btn-primary pull-right">Xem sản phẩm</a>
                                <button class="btn btn-default pull-right mr-3" ng-click="backToStep2()">Quay lại</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection