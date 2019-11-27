$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
    });

    function loadPreview() {
        return $.ajax({
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
                let url = window.location.href;
                if (count == 0 && url.indexOf('/checkout') >= 0) {
                    window.location.href = '/cart';
                }
    
                $('.cart_count span').text(count);
            }
        });
    }

    loadPreview();

    $(document).on('click', '.cart-close', function () {
        $('#cart-preview').addClass('d-none');
    });

    $(document).on('click', '.cart', function () {
        if ($(this).is(':empty')) {
            let result = loadPreview();
            result.then(function (response) {
                $('#cart-preview').toggleClass('d-none');
            });
        } else {
            $('#cart-preview').toggleClass('d-none');
        }
    });
});