<style>
    a.btn-pagination {
        width: 100%;
        height: 100%;
        text-align: center;
        padding: 14px 0;
    }
    ul.page_nav li.active {
        background-color: #0077ff;
    }

    ul.page_nav li.active a {
        color: #ffffff;
    }
</style>
<div class="shop_page_nav d-flex flex-row justify-content-center">
    @if($meta['page_count'] > 1)
        {{-- Nút điều hướng : quay lại --}}
        @if($meta['page_id'] > 0)
            <div class="page_prev d-flex flex-column align-items-center">
                <a class="btn-pagination"
                    href="{{ getPaginationPageUrl($meta['page_id'] === 0 ? 0 : $meta['page_id'] - 1) }}">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </div>
        @endif
        <ul class="page_nav d-flex flex-row">
            {{-- Trang đầu --}}
            @if($meta['page_id'] != 0)
            <li>
                <a class="btn-pagination"
                   href="{{ getPaginationPageUrl(0) }}">1</a>
            </li>
            @endif

            @php
                $n = 2;
                $i = $meta['page_id'] - $n;
            @endphp
            {{-- Đằng trước n trang --}}
            @if($meta['page_id'] > 3)
                <li>
                    <a class="btn-pagination"> ... </a>
                </li>
            @endif
            @for(; ($i < $meta['page_id']); $i ++)
                @if($i > 0)
                <li>
                    <a class="btn-pagination" href="{{ getPaginationPageUrl($i) }}">{{ $i + 1 }}</a>
                </li>
                @endif
            @endfor

            {{-- Trang hiện tại --}}
            @if($meta['page_id'] != $meta['page_count'])
            <li class="active">
                <a class="btn-pagination" href="{{ getPaginationPageUrl($meta['page_id']) }}">{{ $meta['page_id'] + 1 }}</a>
            </li>
            @endif

            {{-- Tiếp theo n trang --}}
            @for($i = $meta['page_id'] + 1; ($i <= $meta['page_id'] + $n) && $i < $meta['page_count'] - 1; $i ++)
                <li>
                    <a class="btn-pagination" href="{{ getPaginationPageUrl($i) }}">{{ $i + 1 }}</a>
                </li>
            @endfor
            @if($i < $meta['page_count'] - 1)
                <li>
                    <a class="btn-pagination"> ... </a>
                </li>
            @endif

            {{-- Trang cuối --}}
            @if($meta['page_id'] != $meta['page_count'] - 1)
            <li>
                <a class="btn-pagination"
                   href="{{ getPaginationPageUrl($meta['page_count'] - 1 ) }}">{{ $meta['page_count'] }}</a>
            </li>
            @endif

        </ul>
        {{-- Nút điều hướng : trang tiếp --}}
        @if($meta['has_next'])
            <div class="page_next d-flex flex-column align-items-center justify-content-center">
                <a class="btn-pagination"
                    href="{{ getPaginationPageUrl($meta['page_id'] + 1 >= $meta['page_count'] - 1 ? $meta['page_count'] - 1 : $meta['page_id'] + 1) }}">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        @endif
    @endif

</div>