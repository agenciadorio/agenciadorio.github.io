/**
 * Main admin JS file
 */
jQuery(document).ready(function($){
    //Select 2 for multiple selects
    $('select[multiple]').each(function(index,el) {
        $(this).select2({
            placeholder: $(el).attr('placeholder'),
            allowClear: true
        });
    });

    //==================================Templates================================================
    $('#tt_login_template-container input:radio:not(:checked)').on('click',function(){
        var content = $(this).siblings('span').attr('data-content');
        $(this).siblings('span').attr('data-content','');
        var goAhead = confirm( $('#tt_login_template-container > div').data('alert-message') );
        $(this).siblings('span').attr('data-content',content);
        if( goAhead == true) {
            url = window.location.href;
            if (url.indexOf('?') > -1) {
                url += '&enabled=template-' + $(this).val();
            } else {
                url += '?enabled=template-' + $(this).val();
            }
            window.location.href = url;
            return true;
        }else{
            return false;
        }
    });

});