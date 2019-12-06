@extends('system.layout.main', [
    'header' => false,
    'ngController' => 'EditOrderController'
])

@section('title')
<title>Chi tiết đơn hàng</title>
@endsection

@section('header')
    @include('system.orders.inc.order-header')
@endsection

@section('css')
    <link rel="stylesheet" href="/system/css/order.css">
@endsection

@section('script')
<script>
    var orderId = "<?= isset($id) ? $id : '' ?>";
</script>
<script src="/system/js/controllers/order/create-order-controller.js?v=<?= Config::get("sa.version") ?>" charset="utf-8"></script>
<script src="/system/js/controllers/order/edit-order-controller.js?v=<?= Config::get("sa.version") ?>" charset="utf-8"></script>
@endsection

@section('content')
<div class="" ng-cloak>
    <div class="header">
        <div class="pull-left">
            <h3 style="padding: 10px;">Đơn hàng #@{{ order.code }} 
                <span style="font-size: 50%; vertical-align: middle;" class="label label-@{{ getStatusClass(order.status) }}">
                    @{{ orderStatus[order.status] }}
                </span>
            </h3>
        </div>

        <div class="clearfix">
        </div>
    </div>
    <div class="body">
        <div class="box no-border" style="background-color: #ecf0f5; box-shadow: none; margin-bottom: 0;">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="order-layout order-layout-left box no-border">
                        <!-- <div>
                            <h4>Chi tiết đơn hàng</h4>
                        </div> -->
                            <div class="order-detail">
                                <div class="overflow-auto">
                                    <!-- <label class="col-md-4 p-0">
                                        Trạng thái: <span class="label label-@{{ getStatusClass(order.status) }}">
                                            @{{ orderStatus[order.status] }}
                                        </span>
                                    </label> -->

                                    <div class="pull-right">
                                        <button
                                            ng-repeat="status in statusFlow[order.status]"
                                            class="btn btn-flat btn-@{{ getStatusClass(status) }} ml-3"
                                            data-toggle="tooltip"
                                            title="Chuyển sang trạng thái @{{ orderStatus[status] }}"
                                            ng-click="changeStatus(status)"
                                        >@{{ orderActions[status] }}</button>
                                    </div>
                                </div>
                            </div>
                            <div class="search-product">
                                <div class="position-relative">
                                    <label>Sản phẩm <span class="text-danger">*</span></label>
                                    <a ng-if="!order || isEditable()" style="right: 0; top: 0" class="position-absolute" target="_blank" href="/admin/products/new">Thêm sản phẩm</a>
                                </div>
                                <div class="input-group" ng-if="!order || isEditable()">
                                    <input type="text" ng-model="searchProductQuery" ng-change="searchProduct(true)" ng-model-options='{ debounce: 500 }' type="text" class="form-control" placeholder="Tìm kiếm...">
                                    <div class="input-group-btn">
                                        <button class="btn btn-default" type="submit" ng-click="searchProduct(true)">
                                            <i class="fa fa-search" aria-hidden="true"></i> Tìm kiếm
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="@{{ selectedProducts.length > 0 && 'order-detail' }} table-responsive">
                                <table class="order-items">
                                    <thead>
                                        <tr>
                                            <th style="width: 75px;"></th>
                                            <th></th>
                                            <th class="width-fit-content"></th>
                                            <th class="width-fit-content"></th>
                                            <th class="@{{ isEditable() ? 'item-quantity' : 'width-fit-content' }}"></th>
                                            <th class="width-fit-content"></th>
                                            <th style="width: 125px"></th>
                                            <th class="width-fit-content"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-if="selectedProducts.length > 0" ng-repeat="item in selectedProducts">
                                            <td class="p-2">
                                                <img ng-src="@{{ item.display_image_url }}" alt="@{{ item.name }}" style="width: 100%">
                                            </td>
                                            <td style="min-width: 200px;">@{{ item.name }}</td>
                                            <td class="price">@{{ formatCurrency(item.price) }}</td>
                                            <td>x</td>
                                            <td class="price" style="padding: 5px; min-width: 50px;">
                                                <input
                                                    style="width: 100%"
                                                    class="form-control"
                                                    type="text"
                                                    awnum
                                                    num-neg="false"
                                                    ng-model="item.quantity"
                                                    value="@{{ item.quantity }}"
                                                    ng-change="updateInfo()"
                                                    ng-if="isEditable()"
                                                >
                                                <span ng-if="!isEditable()">@{{ item.quantity }}</span>
                                            </td>
                                            <td class="p-0">=</td>
                                            <td class="price">@{{ formatCurrency(item.price * item.quantity) }}</td>
                                            <td class="p-2">
                                                <a href="javascript:void(0)" ng-click="removeSelectedProduct(item)" ng-if="isEditable()">
                                                    <i class="fa fa-times-circle"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @include('system.orders.inc.amount')
                            <div class="order-detail">
                                <label for="">Ghi chú</label>
                                <div class="form-group overflow-auto mt-3">
                                    <textarea rows="3" class="form-control" ng-model="orderInfo.note"></textarea>
                                </div>
                            </div>
                            {{-- <div class="overflow-auto order-action">
                                <span ng-show="editting"><strong>Thay đổi chưa được lưu</strong></span>
                                <div class="d-inline-block" style="float: right">
                                    <a class="btn btn-secondary" role="button" href="/admin/orders">Huỷ</a>
                                    <button
                                        id="btnSave"
                                        class="btn btn-primary ml-4"
                                        ng-click="saveEditOrder()"
                                        ng-disabled="!editting || disabledSave"
                                    >Lưu</button>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    @include('system.orders.inc.customer-select')
                </div>
                @include('system.orders.inc.history')
            </div>
        </div>
    </div>
</div>
@include('system.orders.inc.search-product-modal')
@include('system.orders.inc.create-customer-modal')
@endsection
