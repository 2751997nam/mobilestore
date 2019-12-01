$(document).ready(function () {
    var url = new URL(window.location.href);
    var filters = {};
    if ($('#js-filter-list').length > 0) {
        filters = JSON.parse($('#js-filter-list').attr('data-value'));
        if (!(filters instanceof Object) || filters instanceof Array) {
            filters = {};
        }
    }
    Object.keys(filters).forEach(function (index) {
        if (Array.isArray(filters[index])) {
            if (filters[index]) {
                filters[index].forEach(function (id) {
                    $('.js-filter-product[data-value=' + id + ']').addClass('active');
                });
            }
        } else {
            $('.js-filter-product[data-value=' + filters[index] + ']').addClass('active');
        }
    });

    function changePageSize(size) {
        url.searchParams.set('page_id', 0);
        url.searchParams.set('page_size', size);

        window.location.href = url;
    }

    function changeSortType(type) {
        url.searchParams.set('page_id', 0);
        url.searchParams.set('order', type);

        window.location.href = url;
    }

    $('#js-pageSize').change(function () {
        changePageSize($(this).val());
    });

    $('.js-sort').click(function () {
        changeSortType($(this).attr('data-type'));
    });

    function setSortType() {
        var value = url.searchParams.get('order');
        if (value) {
            console.log(value);
            let name = $(`.js-sort[data-type='${value}']`).text();
            $('.sorting_text').text(name);
        }
    }

    function buildFilterUrl() {
        url = new URL(url.origin + url.pathname);
        var fullUrl = new URL(window.location.href);
        Object.keys(filters).forEach(function (key) {
            if (filters[key] == null) {
                filters[key] = '';
            }
            if (key == 'category' && filters[key].length > 0) {
                url.searchParams.set('category[]', filters[key][0]);
            } else {
                url.searchParams.set(key, filters[key]);
            }
        });
        if (fullUrl.searchParams.get('q')) {
            url.searchParams.set('q', fullUrl.searchParams.get('q'));
        }
        window.location.href = url;
    }

    function removeFilter(field, isArray = false, index = null) {
        delete filters[field];
        // console.log(filters);
        // return;
        buildFilterUrl();
    }

    $('.js-remove-filter').click(function () {
        removeFilter($(this).attr('data-field'));
    });

    $('.js-remove-all-filter').click(function () {
        filters = {};
        buildFilterUrl();
    });

    $('.js-filter-product').click(function () {
        if ($(this).hasClass('active')) {
            return;
        }
        var field = $(this).attr('data-field');
        var value = $(this).attr('data-value');
        filters[field] = value;
        buildFilterUrl();
    });

    setSortType();
});