/* ==========================================================================
 Scripts voor de frontend
 ========================================================================== */
require(['jquery'], function ($) {
	//$('.o-list').find('li').slideUp();
    $(function () {
        $('.sidebar').on('click', '.o-list .expand, .o-list .expanded', function () {
            var element = $(this).parent('li');

            if (element.hasClass('active')) { 
                element.find('ul').slideUp();

                element.removeClass('active');
                element.find('li').removeClass('active');

                element.find('i').removeClass('fa-minus-circle').addClass('fa-plus-circle');
            } else {
                element.children('ul').slideDown();
                element.siblings('li').children('ul').slideUp();
                element.parent('ul').find('i').removeClass('fa-minus-circle').addClass('fa-plus-circle');
                element.find('> span i').removeClass('fa-plus-circle').addClass('fa-minus-circle');

                element.addClass('active');
                element.siblings('li').removeClass('active');
                element.siblings('li').find('li').removeClass('active');
                element.siblings('li').find('ul').slideUp();
            }
        });
    });
});
