<div class="order-detail overflow-auto">
    <div class="p-0 col-md-6">
        <div class="overflow-auto">
            <div>
                <label for="">Ghi chú giao hàng</label>
            </div>
            <textarea class="col-md-10 form-control" rows="3" ng-model="orderInfo.delivery_note"></textarea>
        </div>
    </div>
    <div class="p-0 col-md-6">
        <table class="order-info">
            <!-- <thead>
                <tr>
                    <th class="col-md-4"></th>
                    <th style="col-md-8"></th>
                </tr>
            </thead> -->
            <tbody>
                <tr>
                    <td class="text-right" style="width: 30%">Tổng cộng</td>
                    <td>
                        <span ng-if="!order || isEditable()" class="price col-md-10 col-xs-10 p-0 text-right">@{{ formatCurrency(orderInfo.subtotal) }} <sup>đ</sup></span>
                        <span ng-if="!(!order || isEditable())" class="price col-md-12 col-xs-12 p-0 text-right">@{{ formatCurrency(orderInfo.subtotal) }} <sup>đ</sup></span>
                    </td>
                </tr>
                <tr class="d-none">
                    <td class="text-right">Giảm giá</td>
                    <td class="price text-left" ng-if="!order || isEditable()">
                        <div class="col-md-10 col-xs-10  p-0">
                            <input
                                class="form-control text-right"
                                type="text"
                                min="0"
                                ng-model="orderInfo.discount"
                                awnum="price"
                                num-sep=","
                                num-neg="false"
                                ng-focus="removeBlur($event)"
                                ng-blur="addBlur($event, orderInfo.discount)"
                            >
                        </div>
                        <div class="col-md-2 col-xs-2 p-0">
                            <select
                                class="form-control"
                                ng-model="orderInfo.discountType"
                            >
                                <option value="price">đ</option>
                                <option value="percent">%</option>
                            </select>
                        </div>
                    </td>
                    <td ng-if="!(!order || isEditable())">
                        <span class="col-md-12 col-xs-12 price p-0">@{{ formatCurrency(orderInfo.discount) }} <sup>đ</sup></span>
                    </td>
                </tr>
                <tr class="d-none">
                    <td class="text-right">Phí giao hàng</td>
                    <td>
                        <span ng-if="!order || isEditable()">
                            <div class="col-md-10 col-xs-10  p-0">
                                <input
                                    class="form-control text-right"
                                    type="text"
                                    min="0"
                                    ng-model="orderInfo.shipping"
                                    awnum="price"
                                    num-sep=","
                                    num-neg="false"
                                    ng-focus="removeBlur($event)"
                                    ng-blur="addBlur($event, orderInfo.shipping)"
                                >
                            </div>
                            <div class="col-md-2 col-xs-2 p-0">
                                <input class="form-control" style="background-color: transparent; border: none" type="text" readonly value="đ" />
                            </div>
                        </span>
                        <span
                            ng-if="!(!order || isEditable())"
                            class="col-md-12 col-xs-12 price p-0"
                        >
                            @{{ formatCurrency(orderInfo.shipping) }} <sup>đ</sup>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Thành tiền</strong></td>
                    <td ng-if="!order || isEditable()"><span class="col-md-10 col-xs-10 price p-0">@{{ formatCurrency(orderInfo.amount) }} <sup>đ</sup></span></td>
                    <td ng-if="!(!order || isEditable())"><span class="col-md-12 col-xs-12 price p-0">@{{ formatCurrency(orderInfo.amount) }} <sup>đ</sup></span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>