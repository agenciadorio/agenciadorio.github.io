/*jslint browser:true */
/*global jQuery,ajaxurl*/
"use strict";
function TranslateVariant(variant) {
    var name = variant;
    switch (variant) {
    case '100':
        name = 'Thin 100';
        break;
    case '100italic':
        name = 'Thin 100 Italic';
        break;
    case '200':
        name = 'Extra-Light 200';
        break;
    case '200italic':
        name = 'Extra-Light 200 Italic';
        break;
    case '300':
        name = 'Light 300';
        break;
    case '300italic':
        name = 'Light 300 Italic';
        break;
    case 'regular':
        name = 'Normal 400';
        break;
    case 'italic':
        name = 'Normal 400 Italic';
        break;
    case '500':
        name = 'Medium 500';
        break;
    case '500italic':
        name = 'Medium 500 Italic';
        break;
    case '600':
        name = 'Semi-Bold 600';
        break;
    case '600italic':
        name = 'Semi-Bold 600 Italic';
        break;
    case '700':
        name = 'Bold 700';
        break;
    case '700italic':
        name = 'Bold 700 Italic';
        break;
    case '800':
        name = 'Extra-Bold 800';
        break;
    case '800italic':
        name = 'Extra-Bold 800 Italic';
        break;
    case '900':
        name = 'Ultra-Bold 900';
        break;
    case '900italic':
        name = 'Ultra-Bold 900 Italic';
        break;
    }

    return name;
}

function ShowPreview() {
    jQuery('#example_area').html(jQuery('#loader_area').html());
    jQuery('#example_area').show();
    var data = {
        action: 'gfonts_preview',
        font: jQuery('#gfonts_list').val(),
        size: jQuery('#preview_fontsize').val(),
        text: jQuery('#preview_text').val(),
    };
    jQuery.post(ajaxurl, data, function (response) {
        jQuery('#example_area').html(response);
    });

    return false;
}

function ShowPreviewForAll(page) {
    jQuery('#example_area').html(jQuery('#loader_area').html());
    jQuery('#example_area').show();
    var data = {
        action: 'gfonts_preview_for_all',
        size: jQuery('#preview_fontsize').val(),
        text: jQuery('#preview_text').val(),
        page: page
    };
    jQuery.post(ajaxurl, data, function (response) {
        jQuery('#example_area').html(response);
    });

    return false;
}

function NextPage() {
    var page = parseInt(jQuery('#gfpage').val(), 10) + 1;
    return ShowPreviewForAll(page);
}

function PreviousPage() {
    var page = parseInt(jQuery('#gfpage').val(), 10) - 1;
    return ShowPreviewForAll(page);
}

function GfInstallFont(name, variant, obj) {
    var span = jQuery(obj).parent(),
        data = {
            action: 'gfonts_install_font',
            name: name,
            variant: variant
        };
    jQuery(obj).parent().html('Installing...');
    jQuery.post(ajaxurl, data, function (response) {
        span.html(response);
    });

    return false;
}

function GfUninstallFont(name, variant, obj) {
    var span = jQuery(obj).parent(),
        data = {
            action: 'gfonts_uninstall_font',
            name: name,
            variant: variant
        };
    jQuery(obj).parent().html('Uninstalling...');
    jQuery.post(ajaxurl, data, function (response) {
        span.html(response);
    });

    return false;
}

function SetValuesForCurrentFont() {
    var autopreview = jQuery('#gf_autopreview').is(':checked');
    if (autopreview) {
        ShowPreview();
    }
}

jQuery(function () {
    SetValuesForCurrentFont();
    jQuery('#gfonts_list').change(function () {
        SetValuesForCurrentFont();
    });
});


