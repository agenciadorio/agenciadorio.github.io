(function ($) {
    $(document).one('click',
        '.supsystic-admin-notice a, .supsystic-admin-notice button',
        function(event) {

        var responseCode = $(this).data('response-code') || 'hide';
        
        $('.supsystic-admin-notice .notice-dismiss').trigger('click');

        $.post(wp.ajax.settings.url, {
            action: 'supsystic-slider',
            route: {
                module: 'slider',
                action: 'reviewNoticeResponse'
            },
            responseCode: responseCode,
        });
    });
})(jQuery)