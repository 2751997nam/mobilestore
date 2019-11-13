@extends('system.layout.main', [
    'ngController' => 'ShippingFeeController'
])
@section('title')
<title>Cấu hình phí vận chuyển</title>
@endsection
@section('css')
<style media="screen">
    .header {
        margin-bottom: 2px;
    }
    table.borderless td,table.borderless th{
        border: none !important;
    }
    .tt-domain {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .tt-domain {
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 16px;
        max-height: 16px;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        margin-bottom: 8px;
    }
</style>
@endsection
@section('script')
<script>
    var apiUrl = '{{ env('SB_API_URL')}}';
</script>
<script src="/system/js/controllers/setting/shipping-fee-controller.js?v=<?=env('APP_VERSION')?>" charset="utf-8"></script>
@endsection
@section('content')
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="content">
            <div class="header">
                <div class="pull-left">
                    <h3 class="">Phí vận chuyển</h3>
                    
                </div>
                <div class="clearfix">
                </div>
            </div>
            <div style="padding: 5px;">
                <button type="button" name="button" class="btn btn-success btn-flat pull-right add" ng-click="showModal(null, 'create')">Thêm khu vực</button>
                <a href="/admin/settings">
                        <i class="fa fa-angle-left"></i>
                        Quay lại cài đặt</a>
                @include('system.settings.inc.shipping-fee-modal')
            </div>
            <div class="body" style="margin-top: 15px">
                <div class="box no-border">
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                            <table class="table table-borderless" style="table-layout: fixed;">
                                <thead>
                                    <tr>
                                        <th style="width: 200px">Khu vực</th>
                                        <th style="width: 300px">Giá trị đơn hàng <span class="pull-right">Phí vận chuyển</span></th>
                                        <th style="width: 150px" class="text-center">Thời gian tạo</th>
                                        <th style="width: 100px" class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="shippingFee in shippingFees">
                                        <td>
                                            <div class="tt-domain" title="@{{ shippingFee.location_name }}"  ng-click="showModal(shippingFee, 'update')" style="cursor: pointer"><b>@{{ shippingFee.location_name }}</b></div>
                                        </td>
                                        <td>
                                            <div>
                                                <table>
                                                    <tr ng-repeat="item in shippingFee.items">
                                                        <td style="width: 250px">@{{ buildRangeAmountText(item) }}</td>
                                                        <td style="width: 30%; text-align: right;">@{{ buildFeeText(item) }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @{{ summarizeDateTime(shippingFee.created_at, true)}}
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-primary btn-sm" ng-click="showModal(shippingFee, 'update')">
                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm" ng-click="delete(shippingFee)">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </button>
                                        </td>
                                    </tr>
    
                                </tbody>
                            </table>
                            <p ng-show="!shippingFees.length" style="margin-top: 15px;" class="text-center">Không có phí vận chuyển!</p>
                        </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
