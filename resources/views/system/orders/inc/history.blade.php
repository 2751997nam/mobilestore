<div class="row">
    <div class="col-md-8" ng-if="logs.length > 0">
        <div class="order-layout order-layout-left">
            <div>
                <h4>Lịch sử thay đổi</h4>
            </div>
            <div>
                <ul class="timeline">

                    <!-- timeline time label -->
                    {{-- <li class="time-label">
                        <span class="bg-red">
                            10 Feb. 2014
                        </span>
                    </li> --}}
                    <!-- /.timeline-label -->

                    <!-- timeline item -->
                    <li ng-repeat="log in logs">
                        <!-- timeline icon -->
                        <i class="fa fa-circle color-primary @{{ log.show ? 'active' : '' }}" ng-click="log.show = !log.show"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fa fa-clock-o"></i>
                                @{{ summarizeTime(log.created_at) }}
                                @{{ toVietnameseDate(log.created_at, true) }}
                            </span>

                            <h3 class="timeline-header" ng-click="log.show = !log.show">
                                <a href="javascript:void(0)">
                                    <span class="timeline-actor">@{{ log.actor_email ? log.actor_email : order.customer.full_name }}</span>
                                    đã
                                    @{{ orderFields[log.event_type] ? orderFields[log.event_type] : log.event_type }} đơn hàng
                                </a>
                            </h3>

                            <div class="timeline-body" ng-show="log.show">
                                <ul>
                                    <li ng-repeat="(key, value) in log.data" ng-if="key != 'customer' && key != 'items'">
                                        <span >
                                            @{{ orderFields[key] ? orderFields[key] : key }}:
                                        </span>
                                        <span class="text-primary @{{ key == 'status' && 'text-bold' }}">@{{ value }}</span>
                                    </li>
                                    <li ng-if="log.data.customer">
                                        <ul>
                                            <li>
                                                <span style="cursor: pointer" ng-click="log.isDisplayCustomer = !log.isDisplayCustomer">
                                                    <span >
                                                        @{{ orderFields['customer'] ? orderFields['customer'] : 'customer' }}:
                                                    </span>
                                                    <a href="javascript:void(0)">
                                                        <i class="fa @{{ log.isDisplayCustomer ? 'fa-caret-down' : 'fa-caret-right' }}"></i>
                                                    </a>
                                                </span>
                                            </li>
                                            <li
                                                class="ml-5"
                                                ng-repeat="(cKey, cValue) in log.data.customer"
                                                ng-if="log.isDisplayCustomer"
                                            >
                                                <span >
                                                    @{{ orderFields[cKey] ? orderFields[cKey] : cKey }}:
                                                </span>
                                                <span class="text-primary">@{{ cValue }}</span>
                                            </li>
                                        </ul>
                                    </li>
                                    <li ng-if="log.data.items">
                                        <ul>
                                            <li>
                                                <span style="cursor: pointer" ng-click="log.isDisplayItem = !log.isDisplayItem">
                                                    <span >
                                                        @{{ orderFields['items'] ? orderFields['items'] : 'items' }}:
                                                    </span>
                                                    <a href="javascript:void(0)">
                                                        <i class="fa @{{ log.isDisplayItem ? 'fa-caret-down' : 'fa-caret-right' }}"></i>
                                                    </a>
                                                </span>
                                            </li>
                                            <ul
                                                class="ml-5 log-item"
                                                ng-repeat="item in log.data.items"
                                                ng-if="log.isDisplayItem"
                                            >
                                                <li ng-repeat="(iKey, iValue) in item">
                                                    <span >
                                                        @{{ orderFields[iKey] ? orderFields[iKey] : iKey }}:
                                                    </span>
                                                    <span class="text-primary">@{{ iValue }}</span>
                                                </li>
                                            </ul>
                                        </ul>
                                    </li>
                                </ul>
                            </div>

                            {{-- <div class="timeline-footer">
                                <a class="btn btn-primary btn-xs">...</a>
                            </div> --}}
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
