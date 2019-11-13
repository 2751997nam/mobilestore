<div class="modal fade" id="shipping-fee-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog vertical-align-center">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                    </button>
                     <h4 class="modal-title" id="myModalLabel" ng-show="mode == 'create'">Thêm khu vực</h4>
                     <h4 class="modal-title" id="myModalLabel" ng-show="mode == 'update'">Cập nhật khu vực</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group shipping-location" style="position: relative;">
                        <label for="domain">Khu vực <span style="color: red">*</span></label>
                        <select class="form-control"
                                chosen
                                ng-model="shippingFee.location_id"
                                ng-options="location.id as location.name for location in locations">
                        </select>
                    </div>
                    <div class="form-group" style="position: relative;">
                        <label for="domain">Danh sách phí vận chuyển theo giá trị đơn hàng</label>
                        <button class="btn btn-flat btn-info btn-xs" style="cursor: pointer; margin-bottom: 5px" ng-click="addItem(shippingFee)">
                            <i class="fa fa-plus-square" aria-hidden="true"></i> Thêm phí vận chuyển
                        </button>
                        <div class="table-responsive scrollbar-style" style="max-height: 300px; overflow-y: scroll">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>Giá trị tối thiểu <span style="color: red">*</span></td>
                                        <td>Giá trị tối đa</td>
                                        <td>Phí vận chuyển <span style="color: red">*</span></td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="item in shippingFee.items track by $index">
                                        <td>@{{ $index + 1 }}</td>
                                        <td>
                                            <div class="input-group">
                                                <input type="text" ng-model="item.min_amount" placeholder="0" awnum="price" class="form-control">
                                                <span class="input-group-addon">đ</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input type="text" ng-model="item.max_amount" placeholder="0" awnum="price" class="form-control">
                                                <span class="input-group-addon">đ</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input type="text" ng-model="item.fee" placeholder="0" awnum="price" class="form-control">
                                                <span class="input-group-addon">đ</span>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-flat btn-danger btn-sm" ng-click="deleteItem($index)" ng-show="shippingFee.items.length > 1">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="pull-right">
                        <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Hủy</button>
                        <button ng-show="mode == 'create'" ng-click="save('create')" id="btnAdd" type="button" class="btn btn-flat btn-primary">Thêm</button>
                        <button ng-show="mode == 'update'" ng-click="save('update')" id="btnUpate" type="button" class="btn btn-flat btn-primary">Cập nhật</button>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>
