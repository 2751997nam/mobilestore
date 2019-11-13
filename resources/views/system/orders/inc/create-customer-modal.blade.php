<div class="modal fade" id="create-customer-modal">
    <div class=" modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="resetNewCustomer()">
                <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">@{{ newCustomer.editting === false ? 'Thêm khách hàng mới' : 'Sửa thông tin giao hàng' }}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div ng-if="!order">
                        <div class="form-group col-md-6">
                            <label>Họ tên <span class="text-danger">*</span></label>
                            <div>
                                <input
                                    class="form-control"
                                    name="full_name"
                                    type="text"
                                    ng-model="newCustomer.full_name"
                                    required
                                >
                                <span class="text-danger" ng-show="validationErrors.full_name != ''">
                                    @{{ validationErrors.full_name[0] }}
                                </span>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Số điện thoại <span class="text-danger">*</span></label>
                            <div>
                                <input
                                    class="form-control"
                                    name="phone"
                                    type="text"
                                    ng-model="newCustomer.phone"
                                    required
                                >
                                <span class="text-danger" ng-show="validationErrors.phone != ''">
                                    @{{ validationErrors.phone[0] }}
                                </span>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Email</label>
                            <div>
                                <input
                                    class="form-control"
                                    name="email"
                                    type="text"
                                    ng-model="newCustomer.email"
                                >
                                <span class="text-danger" ng-show="validationErrors.email != ''">
                                    @{{ validationErrors.email[0] }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-12 mt-3">
                        <label for="">Địa chỉ</label>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="p-0 col-md-3">
                            <label>Tỉnh/thành phố <span class="text-danger">*</span></label>
                        </div>
                        <div class="p-0 col-md-7">
                            <select
                                chosen
                                class="form-control"
                                ng-model="newCustomer.province_id"
                                ng-change="setDistricts()"
                                ng-options="province.id as province.name for province in provinces"
                                name="location"
                                persistent-create-option="false"
                                {{-- skip-no-results="true" --}}
                                required
                            >
                                {{-- <option value="">-- Chọn tỉnh/thành phố</option>
                                <option ng-repeat="item in provinces" value="@{{ province.id }}">@{{ item.name }}</option> --}}
                            </select>
                            <span class="text-danger" ng-show="validationErrors.province_id != ''">
                                @{{ validationErrors.province_id[0] }}
                            </span>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="p-0 col-md-3">
                            <label for="">Quận/huyện <span class="text-danger">*</span></label>
                        </div>
                        <div class="p-0 col-md-7" ng-if="newCustomer.province_id">
                            <select
                                class="form-control"
                                ng-model="newCustomer.district_id"
                                ng-options="district.id as district.name for district in districts"
                                ng-change="setCommunes()"
                                chosen
                                persistent-create-option="false"
                                {{-- skip-no-results="true" --}}
                                required
                            >
                            </select>
                            <span class="text-danger" ng-show="validationErrors.district_id != ''">
                                @{{ validationErrors.district_id[0] }}
                            </span>
                        </div>
                        <div class="p-0 col-md-7" ng-if="!newCustomer.province_id">
                            <select
                                class="form-control"
                                chosen
                                persistent-create-option="false"
                            >
                            </select>
                            <span class="text-danger" ng-show="validationErrors.district_id != ''">
                                @{{ validationErrors.district_id[0] }}
                            </span>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="p-0 col-md-3">
                            <label for="">Xã/phường</label>
                        </div>
                        <div class="p-0 col-md-7" ng-if="newCustomer.district_id">
                            <select
                                class="form-control"
                                ng-model="newCustomer.commune_id"
                                ng-options="commune.id as commune.name for commune in communes"
                                chosen
                                persistent-create-option="false"
                                skip-no-results="true"
                                required
                            >
                            </select>
                            <span class="text-danger" ng-show="validationErrors.commune_id != ''">
                                @{{ validationErrors.commune_id[0] }}
                            </span>
                        </div>
                        <div class="p-0 col-md-7" ng-if="!newCustomer.district_id">
                            <select
                                class="form-control"
                                chosen
                                persistent-create-option="false"
                            >
                            </select>
                            <span class="text-danger" ng-show="validationErrors.commune_id != ''">
                                @{{ validationErrors.commune_id[0] }}
                            </span>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="p-0 col-md-3">
                            <label for="">Địa chỉ</label>
                        </div>
                        <div class="p-0 col-md-7">
                            <textarea class="form-control" rows="3" ng-model="newCustomer.address"></textarea>
                        </div>
                        <span class="text-danger" ng-show="validationErrors.address != ''">
                            @{{ validationErrors.address[0] }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button
                    class="btn btn-default"
                    type="button"
                    data-dismiss="modal"
                    ng-click="resetNewCustomer()"
                >Đóng</button>
                <button
                    class="btn btn-primary"
                    type="button"
                    ng-click="addSelectedCustomer()"
                >@{{ newCustomer.editting === false ? 'Thêm' : 'Lưu' }}</button>
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
