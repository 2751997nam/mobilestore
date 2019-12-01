@extends('system.layout.main')
@section('title')
<title>Trang chủ</title>
@endsection
@section('css')
<link rel="stylesheet" href="/system/css/home.css">
@endsection
@section('script')
<script>
    $(document).ready(function(){
        if($(window).width() <= 991 ){
            $(document).on('click', ".home-tab a", function(){
                var self = $(this);
                var offset = 30;
                var headerHeight = $('.main-header').height() + offset;
                $('html, body').animate({
                    scrollTop: $(self.attr('href')).offset().top - headerHeight
                }, 200);
            })
        }
    });
</script>
@endsection
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
            <div class="sb-welcome-box-header">
                <h4 style="line-height: 150%;" class="hide-xs">Cửa hàng của bạn đã sẵn sàng.</h4>
                <a href="/" target="_blank">
                    <button type="button" name="button" class="btn btn-default">
                        <i class="fa fa-eye"></i>
                        Xem cửa hàng của bạn
                    </button>
                </a>
            </div>
            <div class="sb-welcome-box">
                <div class="col-md-4 no-padding sb-sidebar home-tab">
                    <ul>
                        <li class="active" style="">
                            <a href="#tab_2" data-toggle="tab" aria-expanded="true">
                                <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                Thêm sản phẩm
                            </a>
                        </li>
                        <li class="" style="">
                            <a href="#tab_category" data-toggle="tab" aria-expanded="true">
                                <i class="fa fa-tags"></i>
                                Thêm danh mục
                            </a>
                        </li>
                        <li class="" style="">
                            <a href="#tab_brand" data-toggle="tab" aria-expanded="true">
                                <i class="fa fa-tags"></i>
                                Thêm thương hiệu
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-8 content">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_2">
                            @include('system.home.tab-content', [
                            'title' => "Thêm sản phẩm đầu tiên",
                            'description' => "Thêm sản phẩm đầu tiên của bạn và bắt đầu kinh doanh.",
                            'buttonUrl' => "/admin/products/new",
                            'buttonText' => "Thêm sản phẩm",
                            'imageUrl' => "https://cdn.shopify.com/s/assets/admin/home/onboarding/home-onboard-prod-incomplete-c9ba45eb06fa725535a6ce48fb6fd472b7d2f94588f01248460fbeb180249312.svg"
                            ])
                        </div>

                        <div class="tab-pane" id="tab_category">
                            @include('system.home.tab-content', [
                            'title' => "Thêm danh mục sản phẩm",
                            'description' => "Thể hiện sự đa dạng mặt hàng của cửa hàng bạn.",
                            'buttonUrl' => "/admin/categories",
                            'buttonText' => "Thêm danh mục sản phẩm",
                            'imageUrl' => "/system/images/category-box.svg"
                            ])
                        </div>
                        
                        <div class="tab-pane" id="tab_brand">
                            @include('system.home.tab-content', [
                            'title' => "Thêm thương hiệu sản phẩm",
                            'description' => "Thể hiện sự đa dạng mặt hàng của cửa hàng bạn.",
                            'buttonUrl' => "/admin/brand",
                            'buttonText' => "Thêm thương hiệu",
                            'imageUrl' => "/system/images/category-box.svg"
                            ])
                        </div>
                    </div>
                </div>
                <div class="clearfix">
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
