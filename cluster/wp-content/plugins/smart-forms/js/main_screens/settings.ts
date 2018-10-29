rnJQuery(()=>{
    if(rnparams.SmartFormsEnableGDPR=='y')
        rnJQuery('#inputGDPR').attr('checked','checked');
   rnJQuery('#smartFormsSave').click(()=>{
        rnJQuery('#smartFormsSave').RNWait('start');

        let options:{key:string,value:string}[]=[];
        options.push({
            key:'SmartFormsEnableGDPR',
            value:rnJQuery('#inputGDPR').is(':checked')?'y':'n'
        });


        rnJQuery.post(ajaxurl,{
            action:'rednao_smart_forms_save_settings',
            options:options
        },()=>{
            rnJQuery('#smartFormsSave').RNWait('stop');
            toastr["success"]("Settings saved successfully")
        });

   })
});

declare let toastr:any;
declare let rnparams:any;