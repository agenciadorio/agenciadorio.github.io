/**
 * Checks if recaptcha is valid then allows form submission
 */

jQuery(document).ready(function($){

    var button = $(this).find('input[type=submit]');
    var error_text = $('.g-recaptcha').data('error-text');
    var error_html = '<strong>ERROR</strong>: ' + error_text + '<br>';

    $('form').submit(function(event){
        if(grecaptcha.getResponse() == ""){
            if($('#login_error').length){
                $('#login_error').append(error_html);
            }else{
                $('<div/>',{id:'login_error'}).html(error_html).insertAfter('.tt-body-login > h1');
            }
            /*setTimeout(function(){
                $('#login_error').fadeOut('slow')
            },4000);*/
            event.preventDefault();
            return false;
        }else {
            return true;
        }
    });

});