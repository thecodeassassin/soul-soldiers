/*jslint browser: true*/
/*global $, jQuery, Modernizr, google, _gat*/
/*jshint strict: true */


var isMobile = false;
var isDesktop = false;


$(window).on("load resize",function(e){



    //mobile detection
    if(Modernizr.mq('only all and (max-width: 767px)') ) {
        isMobile = true;
    }else{
        isMobile = false;
    }


    //tablette and mobile detection
    if(Modernizr.mq('only all and (max-width: 1024px)') ) {
        isDesktop = false;
    }else{
        isDesktop = true;
    }
    toTop(isMobile);
});

//RESIZE EVENTS
$(window).resize(function () {

    Modernizr.addTest('ipad', function () {
        return !!navigator.userAgent.match(/iPad/i);
    });

    if (!Modernizr.ipad) {
        initializeMainMenu();
    }
});

/*
 |--------------------------------------------------------------------------
 | DOCUMENT READY
 |--------------------------------------------------------------------------
 */
$(document).ready(function() {


    "use strict";

    /** INIT FUNCTIONS **/
    initializeMainMenu();

    /*
     |--------------------------------------------------------------------------
     |  form placeholder for IE
     |--------------------------------------------------------------------------
     */
    if(!Modernizr.input.placeholder){

        $('[placeholder]').focus(function() {
            var input = $(this);
            if (input.val() == input.attr('placeholder')) {
                input.val('');
                input.removeClass('placeholder');
            }
        }).blur(function() {
            var input = $(this);
            if (input.val() == '' || input.val() == input.attr('placeholder')) {
                input.addClass('placeholder');
                input.val(input.attr('placeholder'));
            }
        }).blur();
        $('[placeholder]').parents('form').submit(function() {
            $(this).find('[placeholder]').each(function() {
                var input = $(this);
                if (input.val() == input.attr('placeholder')) {
                    input.val('');
                }
            })
        });

    }

    /*
     |--------------------------------------------------------------------------
     | MAGNIFIC POPUP
     |--------------------------------------------------------------------------
     */


    if( $("a.image-link").length){

        $("a.image-link").click(function (e) {

            var items = [];

            items.push( { src: $(this).attr('href')  } );

            if($(this).data('gallery')){

                var $arraySrc = $(this).data('gallery').split(',');

                $.each( $arraySrc, function( i, v ){
                    items.push( {
                        src: v
                    });
                });
            }



            $.magnificPopup.open({
                type:'image',
                mainClass: 'mfp-fade',
                items:items,
                gallery: {
                    enabled: true
                }
            });

            e.preventDefault();
        });

    }



    if( $("a.image-iframe").length){
        $('a.image-iframe').magnificPopup({type:'iframe',mainClass: 'mfp-fade'});
    }
});
/*
 |--------------------------------------------------------------------------
 | FUNCTIONS
 |--------------------------------------------------------------------------
 */


/* MAIN MENU (submenu slide and setting up of a select box on small screen)*/
function initializeMainMenu() {

    "use strict";
    var $mainMenu = $('#mainMenu').children('ul');


    //var action0 = (isMobile === false)?'mouseenter':'click';
    //var action1 = (isMobile === false)?'mouseleave':'click';

    if(Modernizr.mq('only all and (max-width: 767px)') ) {


        // Responsive Menu Events
        var addActiveClass = false;

        $("a.hasSubMenu").unbind('click');
        $('li',$mainMenu).unbind('mouseenter mouseleave');

        $("a.hasSubMenu").on("click", function(e) {

            e.preventDefault();


            addActiveClass = $(this).parent("li").hasClass("Nactive");

            if($(this).parent("li").hasClass('primary')){
                $("li", $mainMenu).removeClass("Nactive");
            }else{
                $("li:not(.primary)", $mainMenu).removeClass("Nactive");
            }


            if(!addActiveClass) {
                $(this).parents("li").addClass("Nactive");
            }else{
                $(this).parent().parent('li').addClass("Nactive");
            }

            return;

        });


    }else if (Modernizr.mq('only all and (max-width: 1024px)') && Modernizr.touch) {

        $("a.hasSubMenu").attr('href', '');
        $("a.hasSubMenu").on("touchend",function(e){

            var $li = $(this).parent(),
                $subMenu = $li.children('.subMenu');


            if ($(this).data('clicked_once')) {

                if($li.parent().is($(':gt(1)', $mainMenu))){

                    if($subMenu.css('display') == 'block'){
                        $li.removeClass('hover');
                        $subMenu.css('display', 'none');


                    }else{

                        $('.subMenu').css('display', 'none');
                        $subMenu.css('display', 'block');

                    }

                }else{

                    $('.subMenu').css('display', 'none');

                }

                $(this).data('clicked_once', false);

            } else {

                $li.parent().find('li').removeClass('hover');
                $li.addClass('hover');

                if($li.parent().is($(':gt(1)', $mainMenu))){

                    $li.parent().find('.subMenu').css('display', 'none');
                    $subMenu.css('left', $subMenu.parent().outerWidth(true));
                    $subMenu.css('display', 'block');



                }else{

                    $('.subMenu').css('display', 'none');
                    $subMenu.css('display', 'block');

                }

                $('a.hasSubMenu').data('clicked_once', false);

                $(this).data('clicked_once', true);

            }

            e.preventDefault();
            return false;
        });

        window.addEventListener("orientationchange", function() {

            $('a.hasSubMenu').parent().removeClass('hover');
            $('.subMenu').css('display', 'none');
            $('a.hasSubMenu').data('clicked_once', false);

        }, true);


    }else{


        $("li", $mainMenu).removeClass("Nactive");
        $('a', $mainMenu).unbind('click');


        $('li',$mainMenu).hover(

            function() {

                var $this = $(this),
                    $subMenu = $this.children('.subMenu');


                if( $subMenu.length ){
                    $this.addClass('hover').stop();
                }else {

                    if($this.parent().is($(':gt(1)', $mainMenu))){

                        $this.stop(false, true).fadeIn('slow');

                    }
                }


                if($this.parent().is($(':gt(1)', $mainMenu))){

                    $subMenu.stop(true, true).fadeIn(200,'easeInOutQuad');
                    $subMenu.css('left', $subMenu.parent().outerWidth(true));


                }else{

                    $subMenu.stop(true, true).delay( 300 ).fadeIn(200,'easeInOutQuad');

                }

            }, function() {

                var $nthis = $(this),
                    $subMenu = $nthis.children('ul');

                if($nthis.parent().is($(':gt(1)', $mainMenu))){


                    $nthis.children('ul').hide();
                    $nthis.children('ul').css('left', 0);


                }else{

                    $nthis.removeClass('hover');
                    $('.subMenu').stop(true, true).delay( 300 ).fadeOut();
                }

                if( $subMenu.length ){$nthis.removeClass('hover');}

            });

    }
}

//
///*
// |--------------------------------------------------------------------------
// | Menu shrink
// |--------------------------------------------------------------------------
// */
//
//$(window).scroll(function () {
//
//    if($(window).width() > 1024 && !$('#onePage').length){
//
//        if($(window).scrollTop() > 0){
//            $('#mainHeader').addClass('fixedHeader');
//            /* $('body').css('margin-top', $('#mainHeader').outerHeight(true));*/
//
//        }else{
//            $('#mainHeader').removeClass('fixedHeader');
//            /*$('body').css('margin-top', 0);*/
//
//        }
//    }
//});



/* ANALYTICS */
function gaSSDSLoad (acct) {
    "use strict";
    var gaJsHost = (("https:" === document.location.protocol) ? "https://ssl." : "http://www."),
        pageTracker,
        s;
    s = document.createElement('script');
    s.src = gaJsHost + 'google-analytics.com/ga.js';
    s.type = 'text/javascript';
    s.onloadDone = false;
    function init () {
        pageTracker = _gat._getTracker(acct);
        pageTracker._trackPageview();
    }
    s.onload = function () {
        s.onloadDone = true;
        init();
    };
    s.onreadystatechange = function() {
        if (('loaded' === s.readyState || 'complete' === s.readyState) && !s.onloadDone) {
            s.onloadDone = true;
            init();
        }
    };
    document.getElementsByTagName('head')[0].appendChild(s);
}


/* TO TOP BUTTON */

function toTop(mobile) {

    if (mobile == false) {

        if (!$('#nekoToTop').length)
            $('body').append('<a href="#" id="nekoToTop"><i class="icon-up-open"></i></a>');

        $(window).scroll(function () {
            if ($(this).scrollTop() > 100) {
                $('#nekoToTop').fadeIn('fast');
            } else {
                $('#nekoToTop').fadeOut('fast');
            }
        });

        $('#nekoToTop').click(function (e) {
            event.preventDefault();
            $('html, body').animate({scrollTop: 0}, 700);
            return false;

        });
    } else {

        if ($('#nekoToTop').length)
            $('#nekoToTop').remove();

    }
}

