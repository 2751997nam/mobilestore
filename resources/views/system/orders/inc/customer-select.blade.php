<div class="col-md-4">
    <div class="box no-border order-layout" ng-if="selectedCustomer !== ''">
        <div class="position-relative box-body" ng-if="selectedCustomer !== ''">
            <a role="button" href="javascript:void(0)" class="remove-customer-btn" ng-click="removeCustomer()" ng-if="!order">
                <i class="fa fa-times-circle"></i>
            </a>
            <h4>Khách hàng</h4>
            <div class="position-relative">
                <div class="customer-detail">
                    <p>@{{ selectedCustomer.full_name }}</p>
                    <p>@{{ selectedCustomer.email }}</p>
                    <p>@{{ selectedCustomer.phone }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="box no-border order-layout" ng-if="selectedCustomer !== ''">
        <div class="position-relative box-body">
            <h4>Địa chỉ giao hàng</h4>
            <div>
                <a role="button" href="javascript:void(0)" class="edit-customer-btn" ng-click="showEditCustomerModal()" ng-if="isEditable()">
                    Sửa
                </a>
                <p style="word-break: break-word">@{{ selectedCustomer.address }}</p>
                <p style="word-break: break-word">@{{ getLocation() }}</p>
            </div>
        </div>
    </div>

    <div class="box no-border order-layout" ng-if="selectedCustomer === ''">
        <div style="position: relative" class="dropdown box-body" id="js-dropdown">
            <h4>Tìm hoặc thêm khách hàng mới <span class="text-danger">*</span></h4>
            <div data-toggle="dropdown">
                <input id="js-input-search" class="form-control" ng-model="searchCustomerQuery" ng-model-options='{ debounce: 500 }' ng-change="searchCustomer()" ng-click="searchCustomer()" type="text" placeholder="Tìm kiếm theo tên, SĐT...">
            </div>
            <div id="user-search-recommand" class="dropdown-menu">
                <ul>
                    <li class="search-customer-result" style="padding: 2rem" data-toggle="modal" data-target="#create-customer-modal">
                        <i class="fa fa-plus mr-4"></i>
                        Thêm khách hàng mới
                    </li>
                    <li class="search-customer-result p-3" ng-repeat="customer in customers" ng-click="selectCustomer(customer)">
                        <div class="mini-avatar d-inline-block mr-2">
                            <img src="/system/images/customer.png" alt="">
                        </div>
                        <div class="d-inline-block">
                            <span style="font-weight: 600">@{{ customer.full_name }}</span>
                            <br>
                            <span>@{{ customer.phone }}</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    @view('system.order.metas')

</div>
