<!-- Hot New Arrivals -->

<div class="new_arrivals">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="tabbed_container">
                    <div class="tabs clearfix tabs-right">
                        <div class="new_arrivals_title">{{ $title }}</div>
                        <ul class="clearfix">
                            <li class="active"></li>
                            <li></li>
                            <li></li>
                        </ul>
                        <div class="tabs_line"><span></span></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12" style="z-index:1;">
                            <!-- Product Panel -->
                            <div class="product_panel panel active">
                                <div class="arrivals_slider slider arrow-location-right-top">
                                    <!-- Slider Item -->
                                    @foreach($products as $product)
                                        <div class="arrivals_slider_item">
                                            <div class="border_active"></div>
                                            <div class="product_item is_new d-flex flex-column align-items-center justify-content-center text-center">
                                                <div class="product_image d-flex flex-column align-items-center justify-content-center">
                                                    <a href="{{ $product->url }}" class="js-recent-viewed" data-id="{{ $product->id }}">
                                                        <img src="{{ $product->display_image_url }}" alt="">
                                                    </a>
                                                </div>
                                                <div class="product_content">
                                                    <div class="product_price">
                                                        {{ $product->display_price }}
                                                        <br>
                                                        <span>
                                                            @if($product->high_price > $product->price)
                                                                {{ $product->display_high_price }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="product_name">
                                                        <div>
                                                            <a class="js-recent-viewed" data-id="{{ $product->id }}" href="{{ $product->url }}">{{ $product->name }}</a>
                                                        </div>
                                                    </div>
                                                    <div class="product_extras">
                                                        <button class="product_cart_button" style="background: #ffffff"></button>
                                                    </div>
                                                </div>
                                                <div class="product_fav"><i class="fas fa-heart"></i></div>
                                                <ul class="product_marks d-none">
                                                    <li class="product_mark product_discount">-25%</li>
                                                    <li class="product_mark product_new">new</li>
                                                </ul>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>		
</div>