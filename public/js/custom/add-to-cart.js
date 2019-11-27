$(document).ready(function () {
    $('#js-add-to-cart').click(function () {
        let productId = $(this).attr('data-product-id');
        let quantity  = parseInt($('#quantity_input').val());
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
        });
        $.ajax({
            url: '/add-to-cart',
            method: 'POST',
            data: {
                product_id: productId,
                quantity: quantity
            },
            success: function (response) {
                $('#cart-preview').html(response);
                $('#cart-preview').removeClass('d-none');
                $('.cart_count span').text(parseInt($('.cart_count span').text()) + quantity);
            }
        })
    });
});