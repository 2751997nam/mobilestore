<!-- Shop Sidebar -->
<div class="shop_sidebar" id="js-filter-list" data-value="{{ json_encode($filters) }}">
    @if (!empty($displayedFilters))
        <div class="sidebar_section">
            <div class="sidebar_title brands_subtitle">Lọc 
                <a class="js-remove-all-filter float-right font-weight-normal" href="javascript:void(0);">xoá</a>
            </div>
            <ul class="brands_list">
                @foreach($displayedFilters as $key => $filter)
                    <li class="brand js-remove-filter" data-field="{{ $key }}">
                        <a href="javascript:void(0)">
                            @if(is_array($filter))
                                {{ $key . ':' }}
                                @foreach ($filter as $value)
                                    <span>{{ $value }}<i class="fa fa-times float-right"></i></span>
                                @endforeach
                            @else
                                <span>{{ $key . ': ' . $filter }}<i class="fa fa-times float-right"></i></span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="sidebar_section">
        <div class="sidebar_title brands_subtitle">Danh mục</div>
        <ul class="brands_list">
            @foreach($categories as $category)
                <li class="brand js-filter-product" data-field="category[]" data-value="{{ $category->id }}"><a href="javascript:void(0)">{{ $category->name }}</a></li>
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
                <li class="brand js-filter-product" data-field="brand" data-value="{{ $brand['slug'] }}"><a href="javascript:void(0)">{{ $brand['name'] }}</a></li>
            @endforeach
        </ul>
    </div>
</div>