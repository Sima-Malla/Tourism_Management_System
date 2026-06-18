$(window).on("load", function() {
    "use strict";


    /*========== Sticky Header ==========*/
    $(window).on("scroll",function(){
        if ($(this).scrollTop() >= 35) {       
            $('.bottom-header').addClass("sticky animated fadeInDown");    
        } else {
            $('.bottom-header').removeClass("sticky animated fadeInDown");
        }
    });


    // ========== ResonSive Mobile Menu ============ //
    $(".navigations > ul > li").addClass("menu-item-has-children"); 
    $(".navigations > ul > li.menu-item-has-children > a").on("click", function(){
        $(this).parent().toggleClass("active").siblings().removeClass("active");
        $(this).next("ul").slideToggle();
        $(this).parent().siblings().find("ul").slideUp();
        return false;
    });


    // ========== Search Form On Click Function ============ //
    $(".search i").on("click", function(){
    	$(this).parent().toggleClass("active");
    });


    // ========== Responsive Header Menu ============ //
    $(".menu-icon").on("click", function(){
        $(".second-bar").toggleClass("active");
        $(".first-bar").toggleClass("active");
        $(".third-bar").toggleClass("active");
        $(".responsive-header").toggleClass("active");
    })

   
   /*=================== Accordion ===================*/
    $(".toggle").each(function(){
        $(this).find(".toggle-content").hide();
        $(this).find('h2:first').addClass("active").next().slideDown(500);
        $('h2').on("click",function(){
            if($(this).next().is(':hidden')) {
                $(this).parent().parent().find("h2").removeClass("active").next().slideUp(500).removeClass('animated fadeInUp').parent().removeClass("activate");
                $(this).toggleClass("active").next().slideDown(500).addClass('animated fadeIn').parent().toggleClass("activate");
            }
        });
    });


    /*=================== Scrolling Function ================*/
    $('.scrolltop').on("click",function(){
        $('html, body').animate({scrollTop : 0},600);
        return false;
    });


    /*================ house-detail-img setting ===============*/
    if($(window).width() > 767) {
        var house_detail_height = $(".house-detail-info").innerHeight();
        $(".house-detail-img").css({
            "height" : house_detail_height
        }); 
    }


    /*================ Room-info setting =============*/
    if($(window).width() > 991) {
        $(".room-listing").each(function(){
            var room_info_height = $(this).find(".room-info").innerHeight();
            $(this).find(".room-img").css({
                "height" : room_info_height
            }); 
        })
    }


});/*=== End strict ===*/


