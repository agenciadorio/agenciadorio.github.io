/**
 * Main front script file
 */

//=============================Form animations===========================
jQuery(document).ready(function($){

    $('#login').wrapInner('<div />').children().addClass('tt-body-login');
    $('#login').show();

    if(!$('body').hasClass('tt-form-animated-in')) {
        $('.tt-body-login').animate({'opacity': 1}, 500);
    }

    function ttAnimation() {
        //In Animation
        if($('body').hasClass('tt-form-animated-in') && typeof ttAnimationIn !== 'undefined' && !( $('body').hasClass('tt-form-animated-error') && $('#login_error').length > 0 ) ){
            $('.tt-body-login').addClass('animated ' + ttAnimationIn);
            $('body').addClass('tt-animating');
        }
        //Error animation
        if($('#login_error').length > 0 && $('body').hasClass('tt-form-animated-error') && typeof ttAnimationError !== 'undefined'){
            $('.tt-body-login').addClass('animated ' + ttAnimationError);
            $('body').addClass('tt-animating');
        }

        //Out Animation
        if($('body').hasClass('tt-form-animated-out') && typeof ttAnimationOut !== 'undefined'){
            $('form').on( 'submit' , function(event) {

                if( !$('.tt-body-login').hasClass(ttAnimationOut) && !( typeof grecaptcha !== 'undefined' && grecaptcha.getResponse() == "" ) ) {
                    event.preventDefault();
                    $('.tt-body-login').addClass('animated ' + ttAnimationOut);
                    $('body').addClass('tt-animating');
                }

            });
        }

        if(typeof ttAnimationIn !== 'undefined') {
            if(ttAnimationIn.indexOf('fadeIn') < 0) {
                $('.tt-body-login').animate({'opacity': 1}, 500);
            }
        }
    }

    setTimeout(ttAnimation, 300);

    //Removing classes after animation executed (Out animation continued)
    $('#login').on('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', '.tt-body-login.animated',function(e){
        $('body').removeClass('tt-animating');
        if(typeof ttAnimationOut !== 'undefined' && $(this).hasClass(ttAnimationOut)) {
            $('form').submit();
            return;
        }
        if(typeof ttAnimationError !== 'undefined')
            $(this).removeClass(ttAnimationError);
        if(typeof ttAnimationIn !== 'undefined' && $(this).hasClass(ttAnimationIn)) {
            $(this).removeClass(ttAnimationIn);
            $(this).css('opacity','1');
        }
    });

    /* Themes helpers */

    // Mars & Mercury
    if($('body').hasClass('tt-login-form-template-mars') || $('body').hasClass('tt-login-form-template-mercury') || $('body').hasClass('tt-login-form-template-venus') || $('body').hasClass('tt-login-form-template-terra') || $('body').hasClass('tt-login-form-template-jupiter')) {
        var label = $('#loginform p:first-child label, #loginform p:first-child + p label, #registerform p:first-child label, #registerform p:first-child + p label, #lostpasswordform p:first-child label'),
            inputs = $('#loginform input[type="text"], #loginform input[type="password"], #registerform input[type="text"], #registerform input[type="password"], #registerform input[type="email"], #lostpasswordform input[type="text"]');
        label.contents().filter(function(){return this.nodeType === 3 && this.nodeValue.trim().length > 0}).wrap('<span />');
        label.each(function(i, e) {
            $(this).find('span').appendTo(this);
        });

        // Jupiter
        if($('body').hasClass('tt-login-form-template-jupiter')) {
            $('#nav a:first-child').prependTo('.submit');
            $('#nav a:last-child').appendTo('#backtoblog');
        }

        // Mercury && Venus
        if($('body').hasClass('tt-login-form-template-mercury') || $('body').hasClass('tt-login-form-template-venus')) {
            label.each(function(i, e) {
                $(this).parent().append('<i class="tt-icon"></i>');
            });
        }

        // Terra
        if($('body').hasClass('tt-login-form-template-terra')) {
            // Long shadow
            var login = $('.tt-body-login'),
                diagonal = Math.sqrt(Math.pow(login.innerHeight(), 2) + Math.pow(login.innerWidth(), 2)),
                rotation = -Math.asin(login.innerHeight() / diagonal) * (180 / Math.PI),
                duration = 200;

            if($('body').hasClass('tt-form-animated-in')) {
                duration = 1000;
            }
            setTimeout(function() {
                login.append('<div class="tt-shadow"></div>');
                 $('.tt-shadow').css({
                    transform: 'rotate('+rotation+'deg)',
                    width: diagonal + 'px',
                });

                $('.tt-shadow').animate({
                    height: '1400px',
                    opacity: 1
                }, 300);

            }, duration);
        }

        function hasContent(input, val) {
            if(val !== '') {
                input.next().addClass('have-content');
            } else {
                input.next().removeClass('have-content');
            }
        };

        inputs.each(function() {
            var input = jQuery(this); 
            var currentVal = input.val();
            hasContent(input, currentVal);
        });

        inputs.blur(function() {
            var input = $(this); 
            var currentVal = input.val();
            hasContent(input, currentVal);
            input.closest('p').removeClass('focused-input');
        });

        inputs.on('focus', function() {
            var input = $(this);
            input.closest('p').addClass('focused-input');
        });

    }

});