<div class="cart-preview">
    <div class="cart-title">
        <h3>Thông tin giỏ hàng</h3>
        <a href="javascript:void(0)" class="cart-close"><i class="fa fa-times"></i></a>
    </div>
    @if (!empty($cartItems))
        @foreach($cartItems as $item)
            <div class="cart-items">
                <div class="item-image">
                    <img src="{{ $item->display_image_url }}" alt="">
                </div>
                <div class="item-info">
                    <div class="item-title">
                        <span class="item-name">{{ $item->product_name }}</span>
                        <span class="item-quantity" data-quantity="{{ $item->quantity }}">x{{ $item->quantity }}</span>
                    </div>
                    <div class="item-price">{{ $item->display_price }}</div>
                </div>
            </div>
        @endforeach

        <div class="cart-action">
            <a class="btn btn-success text-light" href="{{ route('cart.index') }}">Xem chi tiết</a>
            <a class="btn btn-primary text-light" style="margin-left: 50px;" href="{{ route('checkout') }}">Thanh toán</a>
        </div>
    @else
        <div class="cart-empty">
            Giỏ hàng trống
        </div>
    @endif
</div>