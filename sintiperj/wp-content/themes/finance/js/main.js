(function($) {

    'use strict'; 

    new WOW().init();

        $(window).load(function() { 
            $('#status').fadeOut(); 
            $('#preloader').delay(150).fadeOut('fast'); 
            $('body').delay(150).css({'overflow':'visible'});
        });

        /*-----------------------------------------------------------------------------------*/
        /*  Vertical Center
        /*-----------------------------------------------------------------------------------*/

        function verticalCenterHeight() {
            var screenHeight = $(window).height();
            var screenWidth = $(window).width();

            if( screenWidth > 1024 ) {
                $(".vertical-center").each(function() {
                $(this).css('top', ($(this).parent().height() - $(this).height()) / 2);
            });
        }
        }

        window.onload = function() {
          verticalCenterHeight();
        };

        window.onresize = function() {
          verticalCenterHeight();
        };
        
        /*-----------------------------------------------------------------------------------*/
        /*  Menu Mobile
        /*-----------------------------------------------------------------------------------*/

        var slideRight = new Menu({
            wrapper: '#main-wrapper',
            type: 'slide-right',
            menuOpenerClass: '.slide-button',
            maskId: '#slide-overlay'
        });

        var slideRightBtn = document.querySelector('#slide-buttons');
      
        slideRightBtn.addEventListener('click', function(e) {
            e.preventDefault;
            slideRight.open();
        });

        $("#c-menu--slide-right ul li.menu-item-has-children, #c-menu--slide-right ul li.page_item_has_children").click(function() {
          $( this ).toggleClass( "menu-selected" );
        });

        /*-----------------------------------------------------------------------------------*/
        /*  Blog Masonry
        /*-----------------------------------------------------------------------------------*/
        $(window).load(function(){

            var $container = $('.blog-section.grid');

            $container.isotope({ transitionDuration: '0.65s' });

            $(window).resize(function() {
                $container.isotope('layout');
            });

            //service
            var $container = $('.service-page .row');

            $container.isotope({ transitionDuration: '0.65s' });

            $(window).resize(function() {
                $container.isotope('layout');
            });
        });


        

})(jQuery); 