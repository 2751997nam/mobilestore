$(document).ready(function () {
    var SIZE = 10;
    function removeDups(value){
        var nums={};
        for(var i = 0; i < value.length;i++){
           var letter = value[i];
            if (nums[letter]) {
                value.splice(i, 1);
            } else {
                nums[letter] = 1;
            }
        }

        return value;
    }

    $(document).on('click', '.js-recent-viewed', function () {
        var productId = $(this).attr('data-id');
        var recentViewedProducts = localStorage.getItem('recentViewedProducts');
        if (recentViewedProducts) {
            recentViewedProducts = JSON.parse(recentViewedProducts);
            recentViewedProducts.unshift(productId);
            recentViewedProducts = removeDups(recentViewedProducts);
            if (recentViewedProducts.length > SIZE) {
                recentViewedProducts.length = SIZE;
            }
        } else {
            recentViewedProducts = [productId];
        }

        localStorage.setItem('recentViewedProducts', JSON.stringify(recentViewedProducts));
    });

    function getRecentViewsProducts() {
        var recentViewedProducts = localStorage.getItem('recentViewedProducts');
        if (recentViewedProducts && $('#js-show-recent-viewed').length > 0) {
            recentViewedProducts = JSON.parse(recentViewedProducts);
            $.ajax({
                url: '/recent-viewed',
                method: 'post',
                data: {productIds: recentViewedProducts}
            }).then(function(response) {
                $('#js-show-recent-viewed').html(response);
                initViewedSlider();
            });
        }
    }

    function initViewedSlider()
	{
		if($('.viewed_slider').length)
		{
			var viewedSlider = $('.viewed_slider');

			viewedSlider.owlCarousel(
			{
				loop:true,
				margin:30,
				autoplay:true,
                autoplayTimeout:6000,
                rows: 1,
				nav:false,
                dots:false,
                singleItem: true,
				responsive:
				{
					0:{items:1},
					575:{items:2},
					768:{items:3},
					991:{items:4},
					1199:{items:6}
				}
			});

			if($('.viewed_prev').length)
			{
				var prev = $('.viewed_prev');
				prev.on('click', function()
				{
					viewedSlider.trigger('prev.owl.carousel');
				});
			}

			if($('.viewed_next').length)
			{
				var next = $('.viewed_next');
				next.on('click', function()
				{
					viewedSlider.trigger('next.owl.carousel');
				});
			}
		}
	}

    getRecentViewsProducts();
});
