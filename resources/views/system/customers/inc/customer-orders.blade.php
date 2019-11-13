<style type="text/css">
    .title {
        font-size: 16px;
        font-weight: 600;
        color: rgb(33, 43, 54);
    }
</style>
<div class="box no-border">
    <div class="box-body">
        <div class="form-group">
            <div class="">
                <h3 class="title" style="margin-left: 10px;">Đơn hàng</h3>
                <div class="box-body table-responsive" >
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Mã đơn hàng</th>
                                <th>Trạng thái</th>
                                <th>Số tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="(index,item) in orders" ng-click="navigate(item.editUrl)">
                                <td>@{{ index + 1 + meta.page_id * meta.page_size }}</td>
                                <td>#@{{ item.code }}</td>
                                <td>@{{ orderStatus[item.status] }}</td>
                                <td class="text-right">@{{ item.display_amount }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="box-footer clearfix">
                    @include('system.pagination')
                </div>
            </div>
        </div>
    </div>
</div>
