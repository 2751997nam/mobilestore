@extends('frontend.layout.main')

@section('title', 'Chi tiết giỏ hàng')

@section('css')
    <link rel="stylesheet" type="text/css" href="css/cart_styles.css">
    <link rel="stylesheet" type="text/css" href="css/cart_responsive.css">
@endsection

@section('content')
	<div class="cart_section">
		<div class="container">
			<div class="row">
				<div class="col-lg-10 offset-lg-1">
					<div class="cart_container">
						<div class="cart_title">Chi tiết giỏ hàng</div>
						<div class="cart_items">
							<ul class="cart_list">
                                @php
                                    $orderTotal = 0;
                                @endphp
                                @foreach($cartItems as $item)
                                    <li class="cart_item clearfix">
                                        <div class="cart_item_image"><img src="{{ $item->image_url }}" alt=""></div>
                                        <div class="cart_item_info d-flex flex-md-row flex-column justify-content-between">
                                            <div class="cart_item_name cart_info_col">
                                                <div class="cart_item_text">{{ $item->product_name }}</div>
                                            </div>
                                            <div class="cart_item_quantity cart_info_col">
                                                <div class="cart_item_text">{{ $item->quantity }}</div>
                                            </div>
                                            <div class="cart_item_price cart_info_col">
                                                <div class="cart_item_text">{{ $item->display_price }}</div>
                                            </div>
                                            <div class="cart_item_total cart_info_col">
                                                <div class="cart_item_text">{{ $item->total }}</div>
                                            </div>
                                        </div>
                                    </li>
                                    @php
                                        $orderTotal += $item->price * $item->quantity
                                    @endphp
                                @endforeach
							</ul>
						</div>
						
						<!-- Order Total -->
						<div class="order_total">
							<div class="order_total_content text-md-right">
								<div class="order_total_title">Thành tiền:</div>
								<div class="order_total_amount">{{ formatPrice($orderTotal) }}</div>
							</div>
						</div>

						<div class="cart_buttons">
							<a href="{{ route('index') }}" type="button" class="button cart_button_clear">Quay lại</a>
							<a href="{{ route('checkout') }}" type="button" class="button cart_button_checkout">Đặt hàng</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('js')
    <script src="js/cart_custom.js"></script>
@endsection