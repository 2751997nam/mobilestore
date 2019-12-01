<!-- Product Item -->
<div class="product_item" >
    <div class="product_border"></div>
    <div class="product_image d-flex flex-column align-items-center justify-content-center">
        <a href="{{ $product->url }}">
            <img src="{{ $product->image_url }}" alt="">
        </a>
    </div>
    <div class="product_content">
        <div class="product_price">
            {!! $product->display_price !!} 
            <br>
            <span>
            @if ($product->high_price > $product->price)
                {{ $product->display_high_price }}
            @endif
            </span>
        </div>
        <div class="product_name">
            <div>
                <a href="{{ route('product.detail', ['slug' => $product->slug, 'id' => $product->id]) }}" tabindex="0">
                    {{ $product->name }}
                </a>
            </div>
        </div>
    </div>
    {{-- <div class="product_fav"><i class="fas fa-heart"></i></div> --}}
    <ul class="product_marks">
        <li class="product_mark product_discount"></li>
        <li class="product_mark product_new"></li>
    </ul>
</div>
