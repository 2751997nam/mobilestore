<div class="deals_featured">
    <div class="container">
        <div class="row">
            <div class="col d-flex flex-lg-row flex-column align-items-center justify-content-start">
                
                <!-- Deals -->

                <div class="deals">
                    <div class="deals_title">Giảm giá trong tuần</div>
                    <div class="deals_slider_container">
                        
                        <!-- Deals Slider -->
                        <div class="owl-carousel owl-theme deals_slider">
                            @foreach ($deals as $item)
                                <div class="owl-item deals_item">
                                    <div class="deals_image"><img src="{{ $item->display_image_url }}" alt=""></div>
                                    <div class="deals_content">

                                        <div class="deals_info_line d-flex flex-row justify-content-start">
                                            <div class="deals_item_name">{{ $item->name }}</div>
                                        </div>
                                        <div class="deals_info_line d-flex flex-row justify-content-start align-items-center">
                                            <div class="deals_item_price ml-auto">{{ $item->display_price }}</div>
                                            <div class="deals_item_price_a ml-auto">{{ $item->display_high_price }}</div>
                                        </div>
                                        <div class="available">
                                            <div class="available_line d-flex flex-row justify-content-start">
                                                <div class="available_title">Còn: <span>6</span></div>
                                                <div class="sold_title ml-auto">Đã bán: <span>28</span></div>
                                            </div>
                                            <div class="available_bar"><span style="width:17%"></span></div>
                                        </div>
                                        <div class="deals_timer d-flex flex-row align-items-center justify-content-start">
                                            <div class="deals_timer_title_container">
                                                <div class="deals_timer_title">Hurry Up</div>
                                                <div class="deals_timer_subtitle">Offer ends in:</div>
                                            </div>
                                            <div class="deals_timer_content ml-auto">
                                                <div class="deals_timer_box clearfix" data-target-time="">
                                                    <div class="deals_timer_unit">
                                                        <div id="deals_timer1_hr" class="deals_timer_hr"></div>
                                                        <span>hours</span>
                                                    </div>
                                                    <div class="deals_timer_unit">
                                                        <div id="deals_timer1_min" class="deals_timer_min"></div>
                                                        <span>mins</span>
                                                    </div>
                                                    <div class="deals_timer_unit">
                                                        <div id="deals_timer1_sec" class="deals_timer_sec"></div>
                                                        <span>secs</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>

                    <div class="deals_slider_nav_container">
                        <div class="deals_slider_prev deals_slider_nav"><i class="fas fa-chevron-left ml-auto"></i></div>
                        <div class="deals_slider_next deals_slider_nav"><i class="fas fa-chevron-right ml-auto"></i></div>
                    </div>
                </div>
                
                <!-- Featured -->
                <div class="featured">
                    <div class="tabbed_container">
                        <div class="tabs">
                            <ul class="clearfix">
                                <li class="active">Giảm giá</li>
                                <li>Xem nhiều</li>
                            </ul>
                            <div class="tabs_line"><span></span></div>
                        </div>

                        <!-- Product Panel -->
                        <div class="product_panel panel active">
                            <div class="featured_slider slider">
                                <!-- Slider Item -->
                                @foreach($discountProducts as $item)
                                    <div class="featured_slider_item">
                                        <div class="border_active"></div>
                                            <div class="product_item discount d-flex flex-column align-items-center justify-content-center text-center">
                                            <div class="product_image d-flex flex-column align-items-center justify-content-center">
                                                <a class="js-recent-viewed" data-id="{{ $item->id }}" href="{{ $item->url }}">
                                                    <img src="{{ $item->display_image_url }}" alt="">
                                                </a>
                                            </div>
                                            <div class="product_content">
                                                <div class="product_price discount">
                                                    {{ $item->display_price }}
                                                    <br>
                                                    <span>{{ $item->display_high_price }}</span>
                                                </div>
                                                <div class="product_name">
                                                    <div>
                                                        <a class="js-recent-viewed" data-id="{{ $item->id }}" href="{{ $item->url }}">{{ $item->name }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Product Panel -->

                        <div class="product_panel panel">
                            <div class="featured_slider slider">

                                <!-- Slider Item -->
                                @foreach($viewedProducts as $item)
                                    <div class="featured_slider_item">
                                        <div class="border_active"></div>
                                        <div class="product_item discount d-flex flex-column align-items-center justify-content-center text-center">
                                            <div class="product_image d-flex flex-column align-items-center justify-content-center">
                                                <a class="js-recent-viewed" data-id="{{ $item->id }}" href="{{ $item->url }}">
                                                    <img src="{{ $item->display_image_url }}" alt="">
                                                </a>
                                            </div>
                                            <div class="product_content">
                                                <div class="product_price discount">
                                                    {{ $item->display_price }}
                                                    <br>
                                                    <span>
                                                        {{ $item->high_price > $item->price ? $item->display_high_price : '' }}
                                                    </span>
                                                </div>
                                                <div class="product_name">
                                                    <div>
                                                        <a class="js-recent-viewed" data-id="{{ $item->id }}" href="{{ $item->url }}">{{ $item->name }}</a>
                                                    </div>
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
    </div>
</div>