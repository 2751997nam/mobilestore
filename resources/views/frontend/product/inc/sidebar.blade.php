<!-- Shop Sidebar -->
<div class="shop_sidebar">
    <div class="sidebar_section">
        <div class="sidebar_title">Danh mục</div>
        <ul class="sidebar_categories">
            @foreach($categories as $category)
                <li><a href="javascript:void(0)">{{ $category->name }}</a></li>
            @endforeach
        </ul>
    </div>
    {{-- <div class="sidebar_section filter_by_section">
        <div class="sidebar_title">Filter By</div>
        <div class="sidebar_subtitle">Price</div>
        <div class="filter_price">
            <div id="slider-range" class="slider_range"></div>
            <p>Range: </p>
        </div>
    </div> --}}
    <div class="sidebar_section">
        <div class="sidebar_subtitle brands_subtitle">Thương hiệu</div>
        <ul class="brands_list">
            @foreach($brands as $brand)
                <li class="brand"><a href="#">{{ $brand['name'] }}</a></li>
            @endforeach
        </ul>
    </div>
</div>