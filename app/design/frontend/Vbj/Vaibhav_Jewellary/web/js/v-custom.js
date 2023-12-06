
window.FontAwesomeConfig = { autoReplaceSvg: false }

define(['jquery'], function($) {
    'use strict';
    var Jquery= $.noConflict();
    Jquery(document).ready(function (){
        /* Home page */
        // if($('.recomended-item').length){
        //     $('.recomended-item').owlCarousel({
        //         center: true,
        //         items: 4,
        //         loop: true,
        //         margin: 10
        //     });
        // }
              // Jquery(document).on('mouseenter','.products', function (event) {
   // var dataImage_1 = jQuery(this).find('.t-product-img').attr("data-imgsrc");
  //jQuery(this).children().children().addClass("hoverImg");
  // jQuery(this).children(".t-product-img").css({
  //   "background": "url(" + dataImage_1 + ") no-repeat center scroll",
  // });
//   jQuery(this).children().children().children(".product-image-container").css("opacity", "0");
// }).on('mouseleave','.products',  function(){
//      jQuery(this).children().children().children(".product-image-container").css("opacity", "1");
//   jQuery(this).children(".t-product-img").css("background", "transparent");
// });

        Jquery(document).on('mouseenter','.categories', function (event) {
    var images = Jquery(this).find('.nav_links img');
             //console.log("Menu Hover working");
            if (!images.attr('src')) {
               //images.closest('a').addClass('img-loading');
              //console.log("Menu Hover working");
               images.each(function(index,value){
                images.closest('a').addClass('img-loading');
                Jquery(value).attr('src',Jquery(value).data('img-src'));
             });

            }
}).on('mouseleave','.categories',  function(){
    // if (Jquery('.img-loading img').attr('src') != '') {
               // Jquery('.img-loading img').closest('a').removeClass('img-loading');
            //}
});
        Jquery('[data-toggle="slide-collapse"]').on('click', function () {
            $navMenuCont = Jquery(Jquery(this).data('target'));
            $navMenuCont.animate({
                'width': 'toggle'
            }, 350);
            Jquery(".menu-overlay").fadeIn(500);
        });
        Jquery(".menu-overlay").click(function (event) {
            Jquery(".navbar-toggle").trigger("click");
            Jquery(".menu-overlay").fadeOut(500);
        });
        Jquery(".search-dropdown").hide();
        Jquery(".search-button").click(function(){
            Jquery(".search-dropdown").toggle();
        });
        Jquery(".menu-close").click(function(){
            Jquery("#slide-navbar-collapse, .menu-overlay").css("display", "none");
        });

        Jquery(".switcher-trigger").click(function(){
          if (Jquery(".dropdown-menu").hasClass("show")) { 
             Jquery(".dropdown-toggle").trigger('click');
           }
            if (Jquery(".search-dropdown").css("display") == "block") { 
               Jquery(".search-button").trigger('click');
            }  
        });
        Jquery(".showcart").click(function(){
          if (Jquery(".dropdown-menu").hasClass("show")) {
             Jquery(".dropdown-toggle").trigger('click');
           }
           if (Jquery(".search-dropdown").css("display") == "block") { 
               Jquery(".search-button").trigger('click');
            }
        });

         Jquery(".dropdown-toggle").click(function(){

         if (Jquery(".showcart").hasClass("active")) {
            Jquery(".showcart").trigger('click');
           }

            if (Jquery(".search-dropdown").css("display") == "block") { 
               Jquery(".search-button").trigger('click');
            }

           if (Jquery(".switcher-trigger").hasClass("active")) {
            Jquery(".switcher-trigger").trigger('click');
           }
        });

        

        /* Product Listing page */
        /*mobile Sorting code*/

        Jquery(".sorfilter").click(function(){
            Jquery(".show_fli").show();
        });
        Jquery(".close_fli").click(function(){
            Jquery(".show_fli").hide();
        });
        /* emi page start*/
        Jquery(".cardEmi").click(function () {
            Jquery(".cardEmi").removeClass("cardEmiActive");
            Jquery(this).addClass("cardEmiActive");
           
            if(Jquery("#bn").hasClass("cardEmiActive")){
            
           Jquery(".imag").css("display", "none");
            Jquery(".imag1").css("display", "block");
          }
           else{
           
            Jquery(".imag").css("display", "block");
            Jquery(".imag1").css("display", "none");
           }
        });
        /* emi page end*/
        /*mobile Filter code*/
        Jquery(".filtersort").click(function(){
            Jquery("#exTab2").show();
        });
        Jquery(".close_fli1").click(function(){
            Jquery("#exTab2").hide();
        });
        /*Sticky menu*/
        if(!Jquery("body").hasClass("checkout-index-index"))
        {
            var $_e = Jquery('.page-header .header');
            var $_f = Jquery('.header-top');
            var headerfull=Jquery('.page-header .header .header-bottom');
            var sticky = Jquery('#sticky_header');
            var top_menu = Jquery('.sections.nav-sections');
            var main_header_add=Jquery('#main_header_right_content');
            var main_header_container=Jquery('#main-header-right-container');
            var sticky_right_container= Jquery('#sticky_right_container');
            
            
            if ($_e.length) {          
                var sticky_navigation = function() {
                    var wWindow = Jquery(window).width();
                    var scroll_top = Jquery(window).scrollTop();
                    var navpos = Jquery('#header-position').offset().top;
                    if (wWindow > 767) {
                       if (/*scroll_top > navpos */document.body.scrollTop > 10 || document.documentElement.scrollTop > 10) {
                        // $('.categories .menu-dropdown').css('top','0px');
                         if (sticky.hasClass('sticky_header_open')) {
                           $('.categories .menu-dropdown').css('top','70px');
                         } else {
                          $('.categories .menu-dropdown').css('top','0px');
                         }
                       } else {
                          $('.categories .menu-dropdown').css('top','auto');
                       }
                      if (/*scroll_top > navpos */document.body.scrollTop > 150 || document.documentElement.scrollTop > 150) {


                          if (!$_e.hasClass('v-navbar-fixed-top')) {
                            $_e.addClass('v-navbar-fixed-top');
                            
        
                            if (headerfull.hasClass('d-flex')) { 
                                 headerfull.removeClass('d-flex');
                                   Jquery(main_header_add).appendTo(sticky_right_container);
                                   Jquery(sticky).attr('class', 'sticky_header_open');
                                   
                                    setTimeout(function () {
                     $('.sticky_header_open').css('overflow','visible');
                 }, 2500);
                              }
                           
                          }
                          if (!$_f.hasClass('v-navbar-fixed-htop')) {
                            $_f.addClass('v-navbar-fixed-htop');
                          }


                    } else {   

                        if ($_e.hasClass('v-navbar-fixed-top')) { 
                            $_e.removeClass('v-navbar-fixed-top');

                            // Jquery(sticky).slideUp(500);
                           

                            if (!headerfull.hasClass('d-flex')) {
                                 headerfull.addClass('d-flex');
                                  Jquery(sticky).attr('class', 'sticky_header');
                                   $('.sticky_header').css('overflow','hidden');
                                   
                                 Jquery(main_header_add).appendTo(main_header_container);
                              }
                            /*if($('.mobile-search-icon').hasClass('active'))
                              $('.mobile-search-icon').click();*/
                        }
                        if ($_f.hasClass('v-navbar-fixed-htop')) { 
                            $_f.removeClass('v-navbar-fixed-htop');
                            /*if($('.mobile-search-icon').hasClass('active'))
                              $('.mobile-search-icon').click();*/
                        }
                    }
                  } else {
                      if ($_e.hasClass('v-navbar-fixed-top')) {   
                          $_e.removeClass('v-navbar-fixed-top');
                      }
                      if ($_f.hasClass('v-navbar-fixed-htop')) {   
                          $_f.removeClass('v-navbar-fixed-htop');
                      }
                  }
                };
                Jquery(window).scroll(function() {
                    sticky_navigation();
                });
                sticky_navigation();
            }
        }      
    });
});    