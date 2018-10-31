jQuery(function ($) {
    $('.tabs a[tabid=1]').click(function () {
        $('.tabs a[tabid=101]').click();
    });

    $('.tabs a[tabid=4]').click(function () {
        $('.tabs a[tabid=401]').click();
    });

    $('.tabs a').on('click', function () {
        $('#sett_tabid').val($(this).attr('tabid'));
        location.hash = $(this).attr('tabid');
    });

    // Remove the # from the hash, as different browsers may or may not include it
    var hash = location.hash.replace('#', '');

    if (hash != '') {
        hash = parseInt(hash);
        $('.tabs a[tabid=' + Math.floor(hash / 100) + ']').click();
        $('.tabs a[tabid=' + hash + ']').click();
    } else {
        $('.tabs a[tabid=1]').click();
    }

    $('input[name="search_all_cf"]').change(function () {
        if ($(this).val() == 1)
            $('input[name="customfields"]').parent().addClass('disabled');
        else
            $('input[name="customfields"]').parent().removeClass('disabled');
    });
    $('input[name="search_all_cf"]').change();

    function check_redirect_url() {
        var click = $('select[name="redirect_click_to"]').val();
        var enter = $('select[name="redirect_enter_to"]').val();
        if (
            ( click == 'custom_url' ) ||
            ( enter == 'custom_url' )
        ) {
            $('input[name="custom_redirect_url"]').parent().removeClass('disabled');
        } else {
            $('input[name="custom_redirect_url"]').parent().addClass('disabled');
        }

        if ( click == 'ajax_search' || click == 'nothing' ) {
            $('select[name=click_action_location]').parent().addClass('hiddend');
        } else {
            $('select[name=click_action_location]').parent().removeClass('hiddend');
        }
        if ( enter == 'ajax_search' || enter == 'nothing' ) {
            $('select[name=return_action_location]').parent().addClass('hiddend');
        } else {
            $('select[name=return_action_location]').parent().removeClass('hiddend');
        }
    }

    $('select[name="redirect_click_to"]').change(check_redirect_url);
    $('select[name="redirect_enter_to"]').change(check_redirect_url);
    check_redirect_url();

    $('input[name="exactonly"]').change(function(){
        if ( $(this).val() == 0 ) {
            $('select[name="exact_match_location"]').parent().addClass('disabled');
            $('select[name="keyword_logic"]').closest('div').removeClass('disabled');
        } else {
            $('select[name="exact_match_location"]').parent().removeClass('disabled');
            $('select[name="keyword_logic"]').closest('div').addClass('disabled');
        }
    });
    $('input[name="exactonly"]').change();

    // Primary and Secondary fields for custom fields
    $.each(['titlefield', 'descriptionfield'],
        function(i, v){
            $("select[name='"+v+"']").change(function(){
                if ( $(this).val() != 'c__f' ) {
                    $("input[name='"+v+"_cf']").parent().css("display", "none");
                } else {
                    $("input[name='"+v+"_cf']").parent().css("display", "");
                }
            });
            $("select[name='"+v+"']").change();
        });

    // Theme options
    $('select[name=theme]').on('change', function(){
        $('.asl_theme').removeClass().addClass('asl_theme asl_theme-' + $(this).val());
    });
    $('select[name=theme]').trigger('change');

    $('input[name=override_bg]').on('change', function(){
        if ( $(this).val() == 0 ) {
            $('input[name=override_bg_color]').parent().addClass('disabled');
        } else {
            $('input[name=override_bg_color]').parent().removeClass('disabled');
        }
    });
    $('input[name=override_bg]').trigger('change');

    $('input[name=override_icon]').on('change', function(){
        if ( $(this).val() == 0 ) {
            $('input[name=override_icon_bg_color]').parent().addClass('disabled');
            $('input[name=override_icon_color]').parent().addClass('disabled');
        } else {
            $('input[name=override_icon_bg_color]').parent().removeClass('disabled');
            $('input[name=override_icon_color]').parent().removeClass('disabled');
        }
    });
    $('input[name=override_icon]').trigger('change');

    $('input[name=override_border]').on('change', function(){
        if ( $(this).val() == 0 ) {
            $('input[name=override_border_style]').closest('.wpdreamsBorder').addClass('disabled');
        } else {
            $('input[name=override_border_style]').closest('.wpdreamsBorder').removeClass('disabled');
        }
    });
    $('input[name=override_border]').trigger('change');
});