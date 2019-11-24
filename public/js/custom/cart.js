$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
    });
    $.ajax({
        url: '/preview-cart',
        method: 'get',
        success: function (response) {
            $('#cart-preview').html(response);
            let count = 0;

            let elements = $('.item-info .item-quantity');
            if (elements.length > 0) {
                for (let i = 0; i < elements.length; i++) {
                    count += parseInt($(elements[i]).attr('data-quantity'));
                }
            }

            $('.cart_count span').text(count);
        }
    });

    $(document).on('click', '.cart-close', function () {
        $('#cart-preview').addClass('d-none');
    });

    $(document).on('click', '.cart', function () {
        $('#cart-preview').toggleClass('d-none');
    });
});