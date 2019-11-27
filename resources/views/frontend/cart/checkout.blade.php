@extends('frontend.layout.main')

@section('title', 'Thanh toán')

@section('css')
    <link rel="stylesheet" type="text/css" href="css/contact_styles.css">
    <link rel="stylesheet" type="text/css" href="css/contact_responsive.css">

    <style>
        .order-success {
            text-align: center;
            min-height: 300px;
        }
    </style>
@endsection

@section('content')
<div class="contact_form" ng-app="myApp" ng-controller="myCtrl">
    <div class="container">
        <div class="row" ng-if="!success">
            <div class="col-lg-10 offset-lg-1">
                <div class="contact_form_container">
                    <div class="contact_form_title">Đặt hàng</div>

                    <div id="contact_form">
                        <div class="contact_form_inputs d-flex flex-md-row flex-column justify-content-between align-items-between">
                            <input 
                                type="text" 
                                id="contact_form_name" 
                                class="contact_form_name input_field" 
                                placeholder="Họ tên" 
                                required="required"
                                ng-model="customer.full_name"
                            >
                            <input 
                                type="text" 
                                id="contact_form_email" 
                                class="contact_form_email input_field" 
                                placeholder="Email"
                                ng-model="customer.email"
                            >
                            <input 
                                type="text" 
                                id="contact_form_phone" 
                                class="contact_form_phone input_field" 
                                placeholder="Số điện thoại" 
                                required="required"
                                ng-model="customer.phone"
                            >
                        </div>
                        <div class="contact_form_inputs d-flex flex-md-row flex-column justify-content-between align-items-between">
                            <select 
                                class="contact_form_name input_field ml-0"
                                ng-model="order.province_id"
                                ng-options="province.id as province.name for province in provinces track by province.id"
                                ng-change="changeProvince()"
                            >
                                <option value="">Tỉnh/thành phố</option>
                            </select>
                            <select 
                                class="contact_form_name input_field ml-0"
                                ng-model="order.district_id"
                                ng-options="district.id as district.name for district in districts track by district.id"
                                ng-change="getCommune()"
                            >
                                <option value="">Quận/huyện</option>
                            </select>
                            <select 
                                class="contact_form_name input_field ml-0"
                                ng-model="order.commune_id"
                                ng-options="commune.id as commune.name for commune in communes track by commune.id"
                            >
                                <option value="">Xã/phường</option>
                            </select>
                        </div>
                        <div class="contact_form_text">
                            <textarea ng-model="order.delivery_address" id="contact_form_message" class="text_field" name="message" rows="2" placeholder="Địa chỉ" required="required"></textarea>
                        </div>
                        <div class="contact_form_text">
                            <textarea ng-model="order.delivery_note" id="contact_form_message" class="text_field contact_form_message" name="message" rows="4" placeholder="Ghi chú giao hàng" required="required"></textarea>
                        </div>
                        <div class="contact_form_button">
                            <button ng-click="submit()" class="button contact_submit_button">Đặt hàng</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="row" ng-if="success">
            <div class="col-lg-10 offset-lg-1">
                <div class="order-success">
                    <h4>Đặt hàng thành công</h4>
                    <a href="{{ route('product.search') }}" class="btn btn-primary mt-3">Tiếp tục mua sắm</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script src="js/angular.min.js"></script>
    <script src="js/custom/checkout.js"></script>
@endsection

