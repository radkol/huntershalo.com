$(document).ready(function() {



    //mini cart
    var cart = $('#cart');
    var mini_basket_button = $('#mini_basket_menu');
    var cart_container = $('#cart_container');

    function bind_hide_cart() {
        $(document).on('click.cart', function(e) {
            var target = $(e.target);
            if (target.parents('#cart').length == 0 && target.attr('id') !== 'cart') {
                cart.removeClass('opened');
                $(document).off('click.cart');
            }
        });
    }

    mini_basket_button.click(function(e) {
        if (cart_container.length > 0) {
            e.preventDefault();
            if (cart.hasClass('opened')) {
                cart.removeClass('opened');
            } else {
                cart.addClass('opened');
                bind_hide_cart();
            }
        }
    });



    //search
    var search_form = $('#search_form');
    var search_button = search_form.find('button');
    var search_field = search_form.find('input');

    function hide_search() {
        $(document).on('click.search', function(e) {
            var target = $(e.target);
            if (target.parents('#search_form').length == 0 && target.attr('id') !== 'search_form') {
                search_field.hide();
                $(document).off('click.search');
            }
        });
    }

    search_button.click(function(e) {
        if (search_field.css('display') == 'none') {
            e.preventDefault();
            search_field.show();
            hide_search();
            setTimeout(function () {
                search_field[0].focus();
            }, 100);
        }else if (search_field.css('display') == 'block' && search_field.val() !== '') {
            search_form.submit();
        } else {
            e.preventDefault();
            search_field.hide();
        }
    });



    //mobile menu btn
    var menu_btn = $('#menu_btn');
    var header = $('header');

    menu_btn.click(function(e) {
        e.preventDefault();
        header.toggleClass('menu_opened');
    });


    //toggle section
    var toggle_btn = $('.toggle_btn');
    var toggle_section = $('.toggle_section');

    toggle_btn.click(function(e) {
        e.preventDefault();
        toggle_section.slideToggle();
    });


    //flexslider init
    $('.flexslider').flexslider({
        //smoothHeight: true,
        animation: "slide",
        slideshow: false,
        controlNav: false,
        keyboard: false
    });










    $(document).on('click', '.toggle-handle', function(e){
        //if (e.target)
        //e.preventDefault();

        var target = $(e.target);
        var is_link = target.is('a') || target.parents('a').length > 0;

        if (!is_link) {

            var element = $(this);
            var toggleContainer = element.parents('.toggle-container:first');
            var toggleContent = $('.toggle-content:first', toggleContainer);

            if (!toggleContainer.hasClass('disabled')) {

                if (toggleContainer.hasClass('active')) {
                    toggleContainer.removeClass('active');
                    toggleContent.css('display', 'block').stop().slideUp(300, function () {
                        toggleContent.css('height', 'auto');
                        toggleContainer.removeClass('opened').addClass('closed');
                    });

                } else {
                    toggleContainer.addClass('active');
                    toggleContent.css('display', 'none').stop().slideDown(300, function () {
                        toggleContent.css('height', 'auto');
                        toggleContainer.removeClass('closed').addClass('opened');
                    });
                }
            }

        }


    });




    var select_elements = $('select');
    select_elements.fancySelect();


});