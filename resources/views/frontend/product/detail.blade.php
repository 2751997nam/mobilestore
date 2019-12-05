@extends('frontend.layout.main')

@section('title', $product->name)

@section('css')
    <link rel="stylesheet" type="text/css" href="css/product_styles.css">
    <link rel="stylesheet" type="text/css" href="css/product_responsive.css">
    <style>
        .product-content {
            font-size: 30px;
            border-bottom: 1px solid darkgrey;
            padding: 0;
        }

        .product-discount {
            font-weight: 300;
            margin-left: 30px;
            color: #a59d9d;
        }

        .product_price.has-discount {
            color: red;
        }
    </style>
@endsection

@section('content')
	<div class="single_product">
		<div class="container">
			<div class="row">

				<!-- Images -->
				<div class="col-lg-2 order-lg-1 order-2">
					<ul class="image_list">
						<li data-image="{{ $product->display_image_url }}"><img src="{{ $product->display_image_url }}" alt=""></li>
                        @foreach ($product->galleries as $gallery)
						    <li data-image="{{ $gallery->display_image_url }}"><img src="{{ $gallery->display_image_url }}" alt=""></li>
                        @endforeach
					</ul>
				</div>

				<!-- Selected Image -->
				<div class="col-lg-5 order-lg-2 order-1">
					<div class="image_selected"><img src="{{ $product->display_image_url }}" alt=""></div>
				</div>

				<!-- Description -->
				<div class="col-lg-5 order-3">
					<div class="product_description">
						<div class="product_category">
                            @foreach ($product->categories as $category)
                                <span>{{ $category->name }}</span>
                            @endforeach
                        </div>
						<div class="product_name">{{ $product->name }}</div>
						<div class="product_text">{{ $product->description }}</div>
						<div class="order_info d-flex flex-row">
                            <div>
                                <div class="clearfix" style="z-index: 1000;">
                                    <!-- Product Quantity -->
                                    <div class="product_quantity clearfix">
                                        <span>Số lượng: </span>
                                        <input id="quantity_input" type="text" pattern="[0-9]*" value="1">
                                        <div class="quantity_buttons">
                                            <div id="quantity_inc_button" class="quantity_inc quantity_control"><i class="fas fa-chevron-up"></i></div>
                                            <div id="quantity_dec_button" class="quantity_dec quantity_control"><i class="fas fa-chevron-down"></i></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="product_price {{ $product->high_price > $product->price ? 'has-discount' : '' }}">
                                    {{ $product->display_price }}
                                    @if ($product->high_price > $product->price)
                                        <strike class="product-discount">{{ $product->display_high_price }}</strike>
                                    @endif
                                </div>
                                <div class="button_container">
                                    <a href="#" id="js-add-to-cart" data-product-id="{{ $product->id }}" class="button cart_button">Thêm vào giỏ hàng</a>
                                    <div class="product_fav"><i class="fas fa-heart"></i></div>
                                </div>
                            </div>
						</div>
					</div>
				</div>

			</div>
            <div class="row mt-5">
                <div class="col-lg-12">
                    <div class="product-content">Mô tả sản phẩm</div>
                </div>
                <div class="col-lg-12 mt-3">
                    {!! $product->content !!}
                </div>
            </div>
		</div>
	</div>
@endsection

@section('js')
    <script src="js/product_custom.js"></script>
    <script src="js/custom/add-to-cart.js"></script>
@endsection