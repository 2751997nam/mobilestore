@extends('frontend.layout.main')

@section('title', 'Tìm kiếm sản phẩm')

@section('css')
    <link rel="stylesheet" type="text/css" href="css/shop_styles.css">
    <link rel="stylesheet" type="text/css" href="css/shop_responsive.css">
@endsection

@section('content')
	<!-- Home -->

	<!-- Shop -->

	<div class="shop">
		<div class="container">
			<div class="row">
				<div class="col-lg-3">

					@include('frontend.product.inc.sidebar', [
                        'categories' => $data['categories'],
                        'brands' => $data['brands'],
                        'filters' => $data['filters'],
                        'displayedFilters' => $data['displayedFilters']
                    ])

				</div>

				<div class="col-lg-9">
					
					<!-- Shop Content -->

					<div class="shop_content">
						<div class="shop_bar clearfix">
							<div class="shop_product_count"><span>{{ $data['meta']['total_count'] }}</span> Sản phẩm</div>
							<div class="shop_sorting">
								<span>Sắp xếp:</span>
								<ul>
									<li>
										<span class="sorting_text">Mặc định<i class="fas fa-chevron-down"></span></i>
										<ul class="text-left">
											<li class="shop_sorting_button js-sort" data-type="lastest">Mới nhất</li>
											<li class="shop_sorting_button js-sort" data-type="low_price">Giá thấp đến cao</li>
											<li class="shop_sorting_button js-sort" data-type="high_price">Giá cao đến thấp</li>
											<li class="shop_sorting_button js-sort" data-type="sale">Giảm giá theo %</li>
										</ul>
									</li>
								</ul>
							</div>
						</div>

						<div class="product_grid">
							<div class="product_grid_border"></div>
                            @foreach ($data['result'] as $product)
                                @include('frontend.common.product-item')
                            @endforeach
                        </div>

						<!-- Shop Page Navigation -->

                        @include('frontend.common.pagination', [
                            'meta' => $data['meta']
                        ])
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('js')
    <script src="js/custom/product.js"></script>
@endsection