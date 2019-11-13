var jsonMenu = [];
var jsonMenuItem = {};
var jsonSubItem = {};
$(document).ready(function() {
    
    //Update menu json object
    var updateOutputjs = function(e)
    {   
        var list   = e.length ? e : $(e.target), output = '';
        if (window.JSON) {
            try {
                var objectMenuItem = list.nestable('serialize');
                var onchangeMenu = window.JSON.stringify(objectMenuItem);
                localStorage.setItem("jsonmenu", onchangeMenu);
            } catch (err) {
                
            }
        } else {
            output.val('JSON browser support required for this demo.');
        }
    };

    $('.list-accordion').accordion({
        defaultOpen: 'section3',
        cookieName: 'accordion_nav',
        speed: 'slow',
        animateOpen: function (elem, opts) { //replace the standard slideUp with custom function
            elem.next().stop(true, true).slideFadeToggle(opts.speed);
        },
        animateClose: function (elem, opts) { //replace the standard slideDown with custom function
            elem.next().stop(true, true).slideFadeToggle(opts.speed);
        }
    });

    // activate Nestable
    $('#nestable').nestable({
        group: 1
    }).on('change', updateOutputjs);

    $.fn.slideFadeToggle = function(speed, easing, callback) {
        return this.animate({opacity: 'toggle', height: 'toggle'}, speed, easing, callback);
    };

    $('#nestable-menu').on('click', function(e)
    {
        var target = $(e.target),
            action = target.data('action');
        if (action === 'expand-all') {
            $('.dd').nestable('expandAll');
        }
        if (action === 'collapse-all') {
            $('.dd').nestable('collapseAll');
        }
    });

    $(document).on('click', '.menu-pull-btn', function() {
        var caretClass = $(this).find('i').attr('class');
        if ( caretClass.indexOf('fa-caret-down') >= 0 ) {
            $(this).find('i').removeClass('fa-caret-down');
            $(this).find('i').addClass('fa-caret-up');
        } else {
            $(this).find('i').removeClass('fa-caret-up');
            $(this).find('i').addClass('fa-caret-down');
        }
    });

    $('.category-selection').click(function() {
        var caretClass = $(this).find('i').attr('class');
        if ( caretClass.indexOf('fa-caret-down') >= 0 ) {
            $(this).find('i').removeClass('fa-caret-down');
            $(this).find('i').addClass('fa-caret-up');
        } else {
            $(this).find('i').removeClass('fa-caret-up');
            $(this).find('i').addClass('fa-caret-down');
        }
    });

    $('.custom-url-selection').click(function() {
        var caretClass = $(this).find('i').attr('class');
        if ( caretClass.indexOf('fa-caret-down') >= 0 ) {
            $(this).find('i').removeClass('fa-caret-down');
            $(this).find('i').addClass('fa-caret-up');
        } else {
            $(this).find('i').removeClass('fa-caret-up');
            $(this).find('i').addClass('fa-caret-down');
        }
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = e.target.attributes.href.value;
        if ( target == '#tab_2' ) {
            $(target +' input').focus();
        }
      })
});