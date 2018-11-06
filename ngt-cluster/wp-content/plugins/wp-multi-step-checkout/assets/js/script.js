jQuery(window).ready(function($){
    "use strict";

    var tabs = $('.wpmc-tab-item');
    var sections = $('.wpmc-step-item');
    var buttons = $('.wpmc-nav-buttons .button');
    var checkout_form = $('form.woocommerce-checkout');
    var coupon_form = $('#checkout_coupon');

    $('.wpmc-step-item:first').addClass('current');

    // Find the current index
    function currentIndex() {
        return sections.index(sections.filter('.current'));
    }

    // Click on "next" button
    $('#wpmc-next, #wpmc-skip-login').on('click', function() {
      switchTab(currentIndex() + 1);
    });

    // Click on "previous" button
    $('#wpmc-prev').on('click', function() {
      switchTab(currentIndex() - 1);
    });

    // After submit, switch tabs where the invalid fields are
    $(document).on('checkout_error', function() {
        var section_class = $('.woocommerce-invalid-required-field').closest('.wpmc-step-item').attr('class');
        $('.wpmc-step-item').each(function(i) {
            if ($(this).attr('class') === section_class ) {
                switchTab(i)
            }
        })
    });


    // Switch the tab
    function switchTab(theIndex) {
        if ( theIndex < 0 || theIndex > sections.length - 1 ) return false;

        // scroll to top
        var diff = $('.wpmc-tabs-wrapper').offset().top - $(window).scrollTop();
        if ( diff < -40 ) {
            $('html, body').animate({
                scrollTop: $('.wpmc-tabs-wrapper').offset().top - 70, 
            }, 800);
        }

        $('html, body').promise().done(function() {

            tabs.removeClass('previous').filter('.current').addClass('previous');
            sections.removeClass('previous').filter('.current').addClass('previous');

            // Change the tab
            tabs.removeClass('current', {duration: 500});
            tabs.eq(theIndex).addClass('current', {duration: 500});
     
            // Change the section
            sections.removeClass('current', {duration: 500});
            sections.eq(theIndex).addClass('current', {duration: 500});

            // Which buttons to show?
            buttons.removeClass('current');
            checkout_form.addClass( 'processing' );
            coupon_form.hide();

            // Show "next" button 
            if ( theIndex < sections.length - 1 ) $('#wpmc-next').addClass('current');

            // Show "skip login" button
            if ( theIndex === 0 && $('.wpmc-step-login').length > 0 ) {
                $("#wpmc-skip-login").addClass('current');
                $("#wpmc-next").removeClass('current');
            }
            // Last section
            if ( theIndex === sections.length - 1) {
              $("#wpmc-prev").addClass('current');
              $('#wpmc-submit').addClass('current');
              checkout_form.removeClass( 'processing' ).unblock();
            }
            // Show "previous" button 
            if ( theIndex != 0 ) $('#wpmc-prev').addClass('current');



            if( $('.wpmc-order.current').length > 0 ) {
                coupon_form.show();
            }

        });


    }


    // Compatibility with Super Socializer
    if ( $('.the_champ_sharing_container').length > 0 ) {
        $('.the_champ_sharing_container').insertAfter($(this).parent().find('#checkout_coupon'));
    }

    // Prevent form submission on Enter
    $('.woocommerce-checkout').keydown(function(e) {
        if (e.which === 13) {
            e.preventDefault();
            return false;
        }
    });

    // "Back to Cart" button
    $('#wpmc-back-to-cart').click(function() {
        window.location.href = $(this).data('href'); 
    });

    // Switch tabs with <- and -> keyboard arrows
    if ( WPMC.keyboard_nav == '1' ) {
        $(document).keydown(function (e) {
          var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
          if ( key === 39) {
              switchTab(currentIndex() + 1);
          }
          if ( key === 37) {
              switchTab(currentIndex() - 1);
          }
        });
    }
});
