<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                {{-- <th style="width: 10px">
                    <input type="checkbox" name="" value="">
                </th> --}}
                <th>Mã đơn hàng</th>
                <th style="width: 120px">Thời gian tạo</th>
                <th>Khách hàng</td>
                <th style="min-width: 150px">
                    Sản phẩm
                    {{-- <span class="th-item-quantity">Số lượng</span> --}}
                </th>
                <th>Thành tiền</th>
                <th style="width: 120px">Trạng thái</th>
                <th style="width: 100px"></th>
            </tr>
        </thead>
        <tbody>
            <tr ng-repeat="order in orders" ng-click="navigate(order.editUrl)">
                <td><a href="#">#@{{ order.code }}</a><br>
                </td>
                <td>
                    @{{ toVietnameseDate(order.created_at, true) }}<br>
                    @{{ summarizeTime(order.created_at) }}
                </td>
                <td>
                    <p>@{{ order.customer.full_name }}</p>
                    <p><a href="tel:@{{ order.customer.phone }}">@{{ order.customer.phone }}</a></p>
                </td>
                <td>
                    <ul>
                        <li class="order-list-items" ng-repeat="product in order.items">
                            <a href="@{{ product.url}}" target="_blank" ng-bind="product.product_name"></a>
                            <span>x @{{ product.quantity }}</span>
                        </li>
                    </ul>
                </td>
                <td class="text-right width-fit-content"><strong>@{{ formatCurrency(order.amount) }} <sup>đ</sup></strong></td>
                <td>
                    <span class="label label-@{{ getStatusClass(order.status) }}">
                        @{{ orderStatus[order.status] }}
                    </span>
                    {{-- <p class="text-@{{ getStatusClass(order.status) }}">@{{ orderStatus[order.status] }}</p> --}}
                </td>
                <td>
                    <button
                        ng-repeat="status in statusFlow[order.status]"
                        class="btn btn-flat btn-@{{ getStatusClass(status) }} btn-block btn-sm"
                        data-toggle="tooltip"
                        title="Chuyển sang trạng thái @{{ orderStatus[status] }}"
                        ng-click="changeStatus(order, status); $event.stopPropagation();"
                    >
                    @{{ orderActions[status] }}
                    </button>
                </td>
            </tr>
            <tr ng-if="orders.length == 0">
                <td colspan="3" class="text-center">Không có đơn hàng nào</td>
            </tr>
        </tbody>
    </table>
</div>
