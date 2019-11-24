@extends('frontend.layout.main')

@section('title', 'Mobilestore')

@section('content')
    @include('frontend.common.banner')

    @include('frontend.common.deal')

    @include('frontend.common.product-block', [
        'title' => 'Sản phẩm mới nhất',
        'products' => $newestProducts
    ])

    @include('frontend.common.popular-category')
@endsection

