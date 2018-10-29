rnJQuery(function () {
    if (rnparams.SmartFormsEnableGDPR == 'y')
        rnJQuery('#inputGDPR').attr('checked', 'checked');
    rnJQuery('#smartFormsSave').click(function () {
        rnJQuery('#smartFormsSave').RNWait('start');
        var options = [];
        options.push({
            key: 'SmartFormsEnableGDPR',
            value: rnJQuery('#inputGDPR').is(':checked') ? 'y' : 'n'
        });
        rnJQuery.post(ajaxurl, {
            action: 'rednao_smart_forms_save_settings',
            options: options
        }, function () {
            rnJQuery('#smartFormsSave').RNWait('stop');
            toastr["success"]("Settings saved successfully");
        });
    });
});
//# sourceMappingURL=settings.js.map