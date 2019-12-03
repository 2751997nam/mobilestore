<!-- Recently Viewed -->
@if (!empty($recentProducts))
<div class="viewed">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="viewed_title_container">
                    <h3 class="viewed_title">Sản phẩm đã xem</h3>
                    <div class="viewed_nav_container">
                        <div class="viewed_nav viewed_prev"><i class="fas fa-chevron-left"></i></div>
                        <div class="viewed_nav viewed_next"><i class="fas fa-chevron-right"></i></div>
                    </div>
                </div>

                <div class="viewed_slider_container">
                    <!-- Recently Viewed Slider -->
                    <div class="owl-carousel owl-theme viewed_slider">
                        @foreach($recentProducts as $product)
                            <div class="owl-item">
                                <div class="viewed_item discount d-flex flex-column align-items-center justify-content-center text-center">
                                    <div class="viewed_image">
                                    <a href="{{ $product->url }}" class="js-recent-viewed" data-id="{{ $product->id }}">
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                        </a> 
                                    </div>
                                    <div class="viewed_content text-center">
                                        <div class="viewed_price">
                                            {!! $product->display_price !!} 
                                            <br>
                                            <span>
                                            @if ($product->high_price > $product->price)
                                                {{ $product->display_high_price }}
                                            @endif
                                            </span>
                                        </div>
                                        <div class="viewed_name">
                                            <a href="{{ $product->url }}" class="js-recent-viewed" data-id="{{ $product->id }}">{{ $product->name }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
