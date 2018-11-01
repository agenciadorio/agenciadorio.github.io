jQuery(document).ready(function($) {

    var h5absettings = h5abPrintSettings;

    $('#h5ab-print-post-frame').remove();

    $('.h5ab-print-button').on('click',function(){

        $('#h5ab-print-post-frame').remove();
        $('body').prepend('<iframe id="h5ab-print-post-frame" name="h5ab-print-post-frame"></iframe>')

        $('#h5ab-print-post-frame').css({
            'position' : 'fixed',
            'left'     : '-9999px'
        })

        var iframeContent = document.getElementById("h5ab-print-post-frame").contentDocument;

        iframeContent.open();
        iframeContent.write('<!DOCTYPE html>');
        iframeContent.close();

        var iframeHead = $('#h5ab-print-post-frame').contents().find('head');
        var iframeBody = $('#h5ab-print-post-frame').contents().find('body');
        var linkArray = [],
            styleArray = [];

        iframeHead.empty();

        $('head').find('link:not([href*="themes"])').each(function(){
            linkArray.push($(this).attr('href'));
        });

        $('head').find('style').each(function(){
            styleArray.push($(this).html());
        });

        $.each(linkArray, function( index, value ) {
            iframeHead[0].innerHTML += '<link rel="stylesheet" href="' + value + '" />';
        });

        $.each(styleArray, function( index, value ) {
            iframeHead[0].innerHTML += '<style>' + value + '</style>';
        });

        iframeHead[0].innerHTML += ('<style>body { font-family: arial!important; }' + h5absettings.customCSS + '</style>');

        iframeHead[0].innerHTML += '<style>html, body {width: 100%; height: 100%; margin: 0!important; background: #fff!important; background-color: #fff!important; font-family: arial!important;}html, body, div, span, applet, object, iframe,h1, h2, h3, h4, h5, h6, p, blockquote, pre,a, abbr, acronym, address, big, cite, code,del, dfn, em, img, ins, kbd, q, s, samp,small, strike, strong, sub, sup, tt, var,b, u, i, center,dl, dt, dd, ol, ul, li,fieldset, form, label, legend,table, caption, tbody, tfoot, thead, tr, th, td,article, aside, canvas, details, embed, figure, figcaption, footer, header, hgroup, menu, nav, output, ruby, section, summary,time, mark, audio, video {font-family: arial!important;-webkit-box-sizing: border-box!important;-moz-box-sizing: border-box!important;box-sizing: border-box!important;font-family: arial!important;color: #111!important;}h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {text-decoration: none!important; color: #000!important;}a, a:visited {color: #333!important;} img {max-width: 100%!important; height: auto!important;} article, aside, details, figcaption, figure, footer, header, hgroup, menu, nav, section {display: block!important;}table, th, tr, td {color: #111!important;}table {border: 1px solid #ddd!important;}</style>';

        var fullPrintContent = sessionStorage.getItem('h5ab-print-article');
        var printContent = $(fullPrintContent);

        printContent.find('script').remove();
        printContent.find('body').attr('onload', null)

        var inlineJavaScript = ['onafterprint', 'onbeforeprint', 'onbeforeunload', 'onerror', 'onhashchange', 'onload', 'onmessage', 'onoffline', 'ononline', 'onpagehide', 'onpageshow', 'onpopstate', 'onresize', 'onstorage', 'onunload', 'onblur', 'onchange', 'oncontextmenu', 'onfocus', 'oninput', 'oninvalid', 'onreset', 'onsearch', 'onselect', 'onsubmit', 'onkeydown', 'onkeypress', 'onkeyup', 'onclick', 'ondblclick', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onmousedown', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onscroll', 'onwheel', 'oncopy', 'oncut', 'onpaste', 'onabort', 'oncanplay', 'oncanplaythrough', 'oncuechange', 'ondurationchange', 'onemptied', 'onended', 'onerror', 'onloadeddata', 'onloadedmetadata', 'onloadstart', 'onpause', 'onplay', 'onplaying', 'onprogress', 'onratechange', 'onseeked', 'onseeking', 'onstalled', 'onsuspend', 'ontimeupdate', 'onvolumechange', 'onwaiting', 'onerror', 'onshow', 'ontoggle'];

        $.each(inlineJavaScript, function( index, value ) {
            printContent.find('*').attr(value, null)
        });

        iframeBody.empty();
        iframeBody.html(printContent);
        iframeBody.find('.entry-meta, .h5ab-print-button, #post-nav, iframe').hide(function(){
            document.getElementById('h5ab-print-post-frame').contentWindow.focus()
            document.getElementById('h5ab-print-post-frame').contentWindow.print()
        });

    });
    
});