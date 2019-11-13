@extends('system.layout.main',[
'ngController' => "HomeController"
])
@section('title')
<title>Trang chủ</title>
@endsection
@section('css')
<link rel="stylesheet" href="/system/css/home.css">
@endsection
@section('script')
<script src="/system/js/controllers/home-controller.js" charset="utf-8"></script>
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
            <div class="notice @{{ (packageService.days < 4) ? 'notice-error' : 'notice-warning' }}" style="display: block;" ng-show="packageService && packageService.days < 30">
                Bạn còn <span>@{{ packageService.days }}</span> ngày dùng thử.
            </div>
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
                            <a href="#tab_1" data-toggle="tab" aria-expanded="true">
                                <i class="fa fa-sliders" aria-hidden="true"></i>
                                Đổi thông tin
                            </a>
                        </li>

                        <li class="" style="">
                            <a href="#tab_category" data-toggle="tab" aria-expanded="true">
                                <i class="fa fa-tags"></i>
                                Thêm danh mục
                            </a>
                        </li>

                        <li class="" style="">
                            <a href="#tab_2" data-toggle="tab" aria-expanded="true">
                                <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                Thêm sản phẩm
                            </a>
                        </li>
                        <li class="" style="">
                            <a href="#tab_3" data-toggle="tab" aria-expanded="true">
                                <i class="fa fa-paint-brush"></i>
                                Chọn giao diện
                            </a>
                        </li>
                        <li class="" style="">
                            <a href="#tab_4" data-toggle="tab" aria-expanded="true">
                                <i class="fa-config-icon fa fa-globe"></i>
                                Thêm tên miền
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-8 content">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            @include('system.home.tab-content', [
                            'title' => "Thay đổi thông tin cửa hàng",
                            'description' => "Để khách hàng có thể dễ dàng liên hệ, nhận dạng thương hiệu, tăng uy tín cửa hàng.",
                            'buttonUrl' => "/admin/settings/general",
                            'buttonText' => "Vào cài đặt chung",
                            'imageUrl' => "/system/images/store.svg"
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

                        <div class="tab-pane" id="tab_2">
                            @include('system.home.tab-content', [
                            'title' => "Thêm sản phẩm đầu tiên",
                            'description' => "Thêm sản phẩm đầu tiên của bạn và bắt đầu kinh doanh.",
                            'buttonUrl' => "/admin/products/new",
                            'buttonText' => "Thêm sản phẩm",
                            'imageUrl' => "https://cdn.shopify.com/s/assets/admin/home/onboarding/home-onboard-prod-incomplete-c9ba45eb06fa725535a6ce48fb6fd472b7d2f94588f01248460fbeb180249312.svg"
                            ])
                        </div>

                        <div class="tab-pane" id="tab_3">
                            @include('system.home.tab-content', [
                            'title' => "Thay đổi giao diện",
                            'description' => "Chọn giao diện phù hợp với mặt hàng bạn kinh doanh.",
                            'buttonUrl' => "/admin/themes",
                            'buttonText' => "Thay đổi giao diện",
                            'imageUrl' => "https://cdn.shopify.com/s/assets/admin/home/onboarding/home-onboard-theme-incomplete-4451d19f00cd9295c74675366cee273ebc332b57a72eec05bbdc17b2f01c4f98.svg",
                            ])
                        </div>
                        <div class="tab-pane" id="tab_4">
                            @include('system.home.tab-content', [
                            'title' => "Thay đổi tên miền",
                            'description' => "Trở nên chuyển nghiệp hơn khi sử dụng đúng tên miền của bạn",
                            'buttonUrl' => "/admin/settings/domain",
                            'buttonText' => "Chọn tên miền",
                            'imageUrl' => "https://cdn.shopify.com/s/assets/admin/home/onboarding/home-onboard-domain-incomplete-abde25d348f48b7e872ea5338304d05dee0dbc145b049114a4e020069caa427c.svg",
                            ])
                        </div>
                    </div>
                </div>
                <div class="clearfix">
                </div>

            </div>
            @include('system.home.box', [
                'title' => "Cấu hình chi phí vận chuyển cho cửa hàng của bạn",
                'description' => "Nhiều khách hàng quan tâm và tìm xem <strong> phí vận chuyển </strong> khi đặt hàng. Cấu hình phí vận chuyển cho từng khu vực giúp cửa hàng của bạn dễ tiếp cận khách hàng hơn",
                'buttonUrl' => "/admin/settings/shipping-fee",
                'buttonText' => "Cấu hình phí vận chuyển"
            ])

            @include('system.home.box', [
                'title' => "Cấu hình Slide",
                'description' => "Slide là một vị trí tuyệt vời để tìm được sự chú ý của khách hàng! Hãy đặt những banner thể hiện những gì tốt nhất của cửa hàng!",
                'buttonUrl' => "/admin/settings/slides",
                'buttonText' => "Cấu hình Slide"
            ])

            @include('system.home.box', [
                'title' => "Tạo bài viết",
                'description' => "Khách hàng có xu hướng tìm hiểu về sản phẩm trước khi quyết định mua hàng qua những bài viết đánh giá, chia sẻ kinh nghiệm của những người đã sử dụng! Hãy viết những bài viết đánh giá, giới thiệu về sản phẩm của bạn thật hấp dẫn!",
                'buttonUrl' => "/admin/posts/new",
                'buttonText' => "Tạo bài viết"
            ])
        </div>
    </div>
</div>
</div>
@endsection
