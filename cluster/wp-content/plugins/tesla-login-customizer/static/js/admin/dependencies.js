/**
 * Actions/Dependencies of options with other options
 */

function tt_dependencies(onInit) {
    $ = jQuery;
    tt_deps.forEach(function (dep) {
        var id = dep.id;
        var option = $('#' + id);
        var optionContainer = $('#' + id + '-container');

        $(dep.actions).each(function (actionType, action) {
            var show = false;
            $(action.show).each(function (index, rule) {
                var dependingOption = $('#'+rule.id).length > 0 ? $('#'+rule.id) : $('[name='+rule.id+']');
                var opt_value = get_value(dependingOption);
                //console.log(opt_value == rule.val ||
                //    ( rule.val === 1 && typeof opt_value !== 'undefined' && opt_value !== ''), rule.id , opt_value , rule.val);
                if ( opt_value == rule.val || ( rule.val === 1 && typeof opt_value !== 'undefined' && opt_value !== '' ) ) {
                    //console.log('show #' +id + '-container')
                    show = true;
                    return false;
                    //else if(){
                }else{
                    show = false;
                    //console.log('hide #' +id + '-container')
                }
            });
            $(action.hide).each(function (index, rule) {
                var dependingOption = $('#'+rule.id)
                var opt_value = get_value(dependingOption);
                if ( opt_value == rule.val || ( rule.val === 1 && typeof opt_value !== 'undefined' && opt_value !== '' ) ) {
                    show = false;
                    return false;
                }else{
                    show = true;
                }
            });
            var optRow = optionContainer.parent('td').parent('tr');
            if(typeof onInit == 'undefined') {
                if (show)
                    optRow.fadeIn();
                else
                    optRow.fadeOut();
            }else{
                if (show)
                    optRow.show();
                else
                    optRow.hide();
            }
        });
    });
}

function get_value(opt){
    if(opt.length > 1){
        if(opt.eq(0).is(':radio')){
            return opt.filter(function(){return $(this).is(':checked')}).val();
        }
    }
    if(opt.is(':checkbox')){
        return opt.is(':checked') ? 1 : '';
    }
    return opt.val();
}

(function ($) {

    $(document).ready(function () {
        tt_dependencies(true);
        $('.tt-plugin-option input,.tt-plugin-option select').on('change', function (el) {
            tt_dependencies();
        });
    });

})(jQuery);