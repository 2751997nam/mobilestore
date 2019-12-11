@extends('frontend.layout.main')

@section('title', 'Chi tiết giỏ hàng')

@section('css')
    <link rel="stylesheet" type="text/css" href="css/cart_styles.css">
    <link rel="stylesheet" type="text/css" href="css/cart_responsive.css">
    <style>
        .cart_empty h3 {
            padding: 20px;
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <input type="hidden" id="js-cart-item" value="{{ json_encode($cartItems) }}" />
	<div class="cart_section" ng-app="myApp" ng-controller="CartController" ng-cloak>
		<div class="container">
			<div class="row">
				<div class="col-lg-10 offset-lg-1">
					<div class="cart_container">
						<div class="cart_title">Chi tiết giỏ hàng</div>
						<div class="cart_items" ng-if="cartItems.length > 0">
							<ul class="cart_list">
                                <li class="cart_item clearfix" ng-repeat="item in cartItems track by $index">
                                    <div class="cart_item_image"><img src="@{{ item.display_image_url }}" alt=""></div>
                                    <div class="cart_item_info d-flex flex-md-row flex-column justify-content-between">
                                        <div class="cart_item_name cart_info_col">
                                            <div class="cart_item_text">@{{ item.product_name }}</div>
                                        </div>
                                        <div class="cart_item_quantity cart_info_col">
                                            <div class="cart_item_text">
                                                @{{ item.quantity }}
                                            </div>
                                        </div>
                                        <div class="cart_item_price cart_info_col">
                                            <div class="cart_item_text">@{{ item.display_price }}</div>
                                        </div>
                                        <div class="cart_item_total cart_info_col">
                                            <div class="cart_item_text">@{{ item.total }}</div>
                                        </div>
                                        <div class="cart_item_total cart_info_col">
                                            <div class="cart_item_text">
                                                <a href="javascript:void(0)" ng-click="removeItem($index)">Xoá</a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
							</ul>
						</div>
                        <div class="cart_items" ng-if="cartItems.length == 0">
							<ul class="cart_list">
                                <li class="cart_item clearfix">
                                    <div class="cart_empty col-md-12">
                                        <h3>Giỏ hàng trống</h3>
                                    </div>
                                </li>
                            </ul>
                        </div>
						
						<!-- Order Total -->
						<div class="order_total" ng-if="cartItems.length > 0">
							<div class="order_total_content text-md-right">
								<div class="order_total_title">Thành tiền:</div>
								<div class="order_total_amount">@{{ formatCurrency(total) }} ₫</div>
							</div>
						</div>

						<div class="cart_buttons">
							<a href="{{ route('product.search') }}" type="button" class="button cart_button_clear">Quay lại</a>
							<a href="{{ route('checkout') }}" ng-if="cartItems.length > 0" type="button" class="button cart_button_checkout">Đặt hàng</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('js')
    <script src="js/cart_custom.js"></script>
    <script src="js/angular.min.js"></script>
    <script src="js/custom/cart.js"></script>
@endsection