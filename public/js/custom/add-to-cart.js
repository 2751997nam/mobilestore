$(document).ready(function () {
    $('#js-add-to-cart').click(function () {
        let productId = $(this).attr('data-product-id');
        let quantity  = $('#quantity_input').val();
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
            }
        })
    });
});