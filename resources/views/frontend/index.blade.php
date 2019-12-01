@extends('frontend.layout.main')

@section('title', 'Mobilestore')

@section('content')
    @include('frontend.common.banner')

    @include('frontend.common.deal')

    @include('frontend.common.product-block', [
        'title' => 'Sản phẩm mới nhất',
        'products' => $newestProducts
    ])

    @foreach($popularCategories as $category)
        @if(isset($category->products) && !$category->products->isEmpty())
            @include('frontend.common.product-block', [
                'title' => $category->name,
                'products' => $category->products
            ])
        @endif
    @endforeach

    @include('frontend.common.popular-category', [
        'categories' => $popularCategories
    ])
@endsection

@section('js')
    <script src="js/shop_custom.js"></script>
@endsection
