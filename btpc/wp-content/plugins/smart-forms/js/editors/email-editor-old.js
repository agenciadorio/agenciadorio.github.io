function RedNaoEmailEditor()
{
    rnJQuery( "#redNaoAccordion" ).accordion({ clearStyle: true, autoHeight: false });
    this.SelectedEmail=null;
    this.SetUpFixedFields();
    this.FocusEventsInitialized=false;
    this.LastFocus='Body';
    this.FirstTimeLoaded=true;
    var self=this;
    //noinspection JSUnusedLocalSymbols
    this.Dialog=rnJQuery("#redNaoEmailEditor").dialog(
        {   width:"800",
            height:"815",
            modal:true,
            autoOpen:false,
            create: function(event, ui){
                rnJQuery(event.target).closest('.ui-dialog').wrap('<div class="smartFormsSlider" />');

            },
            open: function(event, ui){
                rnJQuery('.ui-widget-overlay').wrap('<div class="smartFormsSlider" />');
            },
            beforeClose:function(event,ui)
            {
                self.UpdateSelectedEmail();
                return self.EmailConfigurationIsValid();

            }


        });
    eventManager.publishEvent('InitializeEmailDialog',this.Dialog);

}

RedNaoEmailEditor.prototype.InitializeFocusEvents=function()
{
    var self=this;
    rntinymce.activeEditor.on('click',function(e) {
        self.LastFocus='Body';
    });

    rnJQuery('#redNaoFromName').focus(function(){
        self.LastFocus='FromName';
    });

    rnJQuery('#redNaoEmailSubject').focus(function()
    {
        self.LastFocus='Subject';
    });

    self.FocusEventsInitialized=true;
};

RedNaoEmailEditor.prototype.EmailConfigurationIsValid=function()
{
    for(var i=0;i<this.Emails.length;i++)
    {
        if(this.Emails[i].ToEmail.indexOf("[field")>=0||this.Emails[i].FromEmail.indexOf("[field")>=0)
        {
            if(!RedNaoLicensingManagerVar.LicenseIsValid('Sorry, you can\'t add fields to the "To Email" or "From Email" box in this version, please use only emails '))
            {
                return false;
            }
        }
    }

};

RedNaoEmailEditor.prototype.SetUpFixedFields=function()
{
    var fixedFieldList=rnJQuery('#redNaoEmailFormFixedFields');
    for(var i=0;i<smartFormsFixedFields.length;i++)
    {
        var button=this.CreateFixedFieldButton(smartFormsFixedFields[i]);
        fixedFieldList.append(button.wrap('<li></li>'));
    }
   // rnJQuery('#rnEmailCurrentDate').click(function(){RedNaoEmailEditorVar.AddFieldToEmail('{"Op":"CurrentDate", "Format":"m/d/y"}')});

};

RedNaoEmailEditor.prototype.CreateFixedFieldButton=function(buttonProperties)
{
    var self=this;
    var button=rnJQuery('<button>'+buttonProperties.Label+'</button>');
    button.click(function(){self.ExecuteFixedFieldButton(buttonProperties)});
    return button;
};

RedNaoEmailEditor.prototype.ExecuteFixedFieldButton=function(buttonProperties)
{
    var op={};
    op.Op=buttonProperties.Op;
    //noinspection JSUnresolvedVariable
    for(var param in buttonProperties.Parameters)
    {
        op[param]=buttonProperties.Parameters[param];
    }
    RedNaoEmailEditorVar.AddFieldToEmail(JSON.stringify(op));
};

RedNaoEmailEditor.prototype.UpdateFromEmail=function()
{
    var selectedToEmails=rnJQuery('#redNaoFromEmail').select2('val');

    var selectedEmailsString="";
    for(var i=0;i<selectedToEmails.length;i++)
    {
        if(selectedToEmails[i].indexOf("[field")==0)
        {
            if(!RedNaoLicensingManagerVar.LicenseIsValid('Sorry, you can\'t add fields to the "To Email" box in this version, please use only emails '))
            {
                return false;
            }
        }
        selectedEmailsString=selectedToEmails[i];
    }
    this.Emails[0].FromEmail=selectedEmailsString;
    return true;
};



RedNaoEmailEditor.prototype.UpdateSelectedEmail=function()
{
    eventManager.publishEvent('UpdateSelectedEmail',{formElements:this.FormElements,email:this.SelectedEmail});
    var selectedToEmails=rnJQuery('#redNaoToEmail').select2('val');
    selectedToEmails=rnJQuery.unique(selectedToEmails);
    var selectedEmailsString="";
    for(var i=0;i<selectedToEmails.length;i++)
    {
        selectedEmailsString+=selectedToEmails[i]+",";
    }
    this.SelectedEmail.ToEmail=selectedEmailsString;


    selectedToEmails=rnJQuery('#redNaoFromEmail').select2('val');
    selectedEmailsString="";
    for(i=0;i<selectedToEmails.length;i++)
    {
        selectedEmailsString=selectedToEmails[i];
    }
    this.SelectedEmail.FromEmail=selectedEmailsString;

    this.SelectedEmail.FromName=rnJQuery('#redNaoFromName').val();
    this.SelectedEmail.EmailSubject=rnJQuery('#redNaoEmailSubject').val();
    this.SelectedEmail.EmailText=rntinymce.get('redNaoTinyMCEEditor').getContent();
};
RedNaoEmailEditor.prototype.SetupEmailTo=function(emailToOptions,alreadySelectedEmails,jQuerySelect,callBack,multiple)
{
    var selectOptions='<optgroup label="'+smartFormsTranslation.SelectAField+'">';
    selectOptions+=emailToOptions;

    for(var i=0;i<alreadySelectedEmails.length;i++)
    {
        if(alreadySelectedEmails[i]=="")
        {
            alreadySelectedEmails.splice(i,1);
            i--;
            continue;
        }
        if(alreadySelectedEmails[i].indexOf("[field")!=0)
        {
            selectOptions+='<option value="'+alreadySelectedEmails[i]+'">'+alreadySelectedEmails[i]+'</option>'
        }
    }

    selectOptions+='</optgroup>';


    var select2Options={
        placeholder: "Type email or field (e.g. example@gmail.com)",
        allowClear: true
    };

    if(!multiple)
        select2Options.maximumSelectionSize=1;

    var self=this;
    select2Options.formatSelection=function(state)
    {
        if(rnJQuery(state.element[0]).data('type')=='multiple')
        {
            var id=rnJQuery(state.element[0]).data('field-id');
            var $field=rnJQuery('<span style="color:blue;text-decoration: underline;cursor:hand;cursor:pointer;">'+RedNaoEscapeHtml(state.text)+'</span>');
            $field.click(function()
            {
                self.OpenMultipleOptionsFieldDialog(id);
            });

            return $field;

        }
        return state.text;
    };

    jQuerySelect.empty();
    jQuerySelect.append(selectOptions);
    var self=this;
    jQuerySelect.select2(
        select2Options
        ).unbind("dropdown-closed")
        .off("dropdown-closed")
        .on("dropdown-closed", function(event) {
            callBack(event.val);

        })
        .off('select2-selecting')
        .on('select2-selecting',function(event)
        {
            var $selectedOption=rnJQuery(event.object.element[0]);
            if($selectedOption.data('type')=='multiple')
            {
                var id=$selectedOption.data('field-id');
                event.preventDefault();
                jQuerySelect.select2('close');
                self.OpenMultipleOptionsFieldDialog(id);
            }
        });

    jQuerySelect.select2('val',alreadySelectedEmails);
    rnJQuery('#redNaoEmailEditor .select2-input').on('keyup', function(e) {
        if(e.which==13||e.which == 32)
        {

            var text=rnJQuery(this).val().trim();
            callBack(text);

        }
    });
};

RedNaoEmailEditor.prototype.OpenMultipleOptionsFieldDialog=function(fieldId)
{
    var selectedMultipleOptions=this.SelectedEmail.MultipleOptionsToEmails[fieldId];
    if(typeof selectedMultipleOptions=='undefined')
        selectedMultipleOptions=[];

    var fieldOptions='';
    var selectedField;
    var i;
    for(i=0;i<this.FormElements.length;i++)
    {
        if(this.FormElements[i].Id==fieldId)
        {
            selectedField=this.FormElements[i];
            fieldOptions=selectedField.Options.Options;
            break;
        }
    }

    var table='<table class="table table-striped""><thead><tr><th>When this option is selected</th><th>Email To (If multiple emails, separate them with a comma)</th></tr></thead><tbody>';
    for(i=0;i<fieldOptions.length;i++)
    {
        var emails='';
        for(var t=0;t<selectedMultipleOptions.length;t++)
        {
            if(selectedMultipleOptions[t].Label==fieldOptions[i].label)
                emails=selectedMultipleOptions[t].EmailTo;
        }
        table+=' <tr class="sfOptionRow">' +
        '        <td><label class="sfOptionLabel">'+RedNaoEscapeHtml(fieldOptions[i].label)+'</label></td>' +
        '        <td><input  type="text" class="sfEmailTo form-control" value="'+RedNaoEscapeHtml(emails)+'"/> </td>' +
        '       </tr>';
    }
    table+='</tbody></table>';
    var self=this;
    var $dialog=rnJQuery(table).RNDialog({
        ButtonClick:function(action,button){if(action=='accept')self.AddMultipleOptionsEmail(fieldId,selectedField.Options.Label,$dialog);},
        Width:'750px',
        Buttons:[
            {Label:'Cancel',Id:'dialogCancel',Style:'danger',Icon:'glyphicon glyphicon-remove',Action:'cancel'},
            {Label:'Apply',Id:'dialogAccept',Style:'success',Icon:'glyphicon glyphicon-ok',Action:'accept'}
        ]

    });

    $dialog.RNDialog('Show');

};

RedNaoEmailEditor.prototype.AddMultipleOptionsEmail=function(fieldId,label,$dialog)
{
    var optionRows=$dialog.find('.sfOptionRow');
    var options=[];
    for(var i=0;i<optionRows.length;i++)
    {
        var $row=rnJQuery(optionRows[i]);
        options.push({
            Label:$row.find('.sfOptionLabel').text(),
            EmailTo:$row.find('.sfEmailTo').val()

        });
    }

    this.SelectedEmail.MultipleOptionsToEmails[fieldId]=options;

    $dialog.RNDialog('Hide');
    var field='[field '+fieldId+']';

    var select=rnJQuery('#redNaoToEmail');
    var selectedValues=select.select2('val');
    selectedValues.push(field);
    select.select2('val',selectedValues);

};

RedNaoEmailEditor.prototype.AddEmailIfValid=function(text,select,multiple)
{
    if(text.trim()=="")
        return;
    if(!multiple&&select.select2('val').length>0)
    {
        alert('You can only have one email in this field');
        return;
    }

    if(sfRedNaoEmail.prototype.EmailIsValid(text))
        this.AddEmail(text,select);
    else
        alert('Please type a valid email');
};

RedNaoEmailEditor.prototype.AddEmail=function(email,select)
{
    select.append(rnJQuery('<option>', {value:email, text: email}));
    var selectedValues=select.select2('val');
    selectedValues.push(email);
    select.select2('val',selectedValues);

};

RedNaoEmailEditor.prototype.OpenEmailEditor=function(redNaoFormElements,emails)
{
    var self=this;
    if(self.FirstTimeLoaded)
    {
        rntinymce.init({
            relative_urls: false,
            convert_urls: false,
            selector:'#redNaoTinyMCEEditor',
            menubar:false,
            plugins: "code link",
            toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table | fontsizeselect | code link",
            setup:function(ed)
            {
                ed.on('init',function(args){
                    self.InitializeFocusEvents();
                    self.OpenEmailEditor(redNaoFormElements,emails);
                });
            }
        });
        self.FirstTimeLoaded=false;
        rnJQuery('#rnAddMedia').click(function()
        {
            if (wp.media.frames.sfmediaPost==undefined) {
                wp.media.frames.sfmediaPost = wp.media({
                    title: "Select a file to add",
                    multiple: false,
                    button: {
                        text: 'Insert file'
                    }
                });

                wp.media.frames.sfmediaPost.on('select',function(){
                    var selection = wp.media.frames.sfmediaPost.state().get('selection');
                    if(selection==null)
                        return;
                    selection.each(function(attachment){
                        rntinymce.get('redNaoTinyMCEEditor').execCommand('mceInsertContent', false,'<img src="'+attachment.attributes.url+'">');
                    });
                });
            }
            wp.media.frames.sfmediaPost.open();
        });
        return;
    }


    RedNaoEventManager.Publish('ContextTutorialRequested',1);
    this.SelectedEmail=null;
    this.Emails=emails;
    this.Dialog.dialog('open');
    var formList=rnJQuery('#redNaoEmailFormFields');
    formList.empty();
    this.EmailToOptions="";
    this.MultiSelectOptions="";
    this.FormElements=redNaoFormElements;
    for(var i=0;i<redNaoFormElements.length;i++)
    {
        if(redNaoFormElements[i].StoresInformation())
        {
            formList.append('<li><button onclick="RedNaoEmailEditorVar.AddFieldToEmail(\''+redNaoFormElements[i].Options.Id+'\');">'+redNaoFormElements[i].Options.Label+'</button></li>');
            if(redNaoFormElements[i].Options.ClassName=="rednaoemail")
                this.EmailToOptions+='<option value="[field '+redNaoFormElements[i].Options.Id+']">'+redNaoFormElements[i].Options.Label+'</option>';
            if(redNaoFormElements[i].Options.ClassName=="rednaomultipleradios"||redNaoFormElements[i].Options.ClassName=="rednaomultiplecheckboxes"
                ||redNaoFormElements[i].Options.ClassName=="rednaoselectbasic"||redNaoFormElements[i].Options.ClassName=="rednaosearchablelist")
                this.MultiSelectOptions+='<option data-type="multiple" data-field-id="'+redNaoFormElements[i].Options.Id+'" value="[field '+redNaoFormElements[i].Options.Id+']">'+redNaoFormElements[i].Options.Label+'</option>';
        }
    }


    if(typeof emails[0].Name=='undefined')
    {
        emails[0].Name='Default';
    }
    this.ListManager=rnJQuery('#emailList').RNList({
        ItemCreated:function(createdItem){
            createdItem.FromName='';
            createdItem.FromEmail='';
            createdItem.ToEmail='';
            createdItem.EmailSubject='';
            createdItem.EmailText='';
            createdItem.MultipleOptionsToEmails={};
        },
        ItemSelected:function(item){self.EmailSelected(item)},
        CreationLabel:'Click here to create a new email',
        Items:emails
    });
    this.ListManager.SelectItem(emails[0]);
};

RedNaoEmailEditor.prototype.EmailSelected=function(email)
{
    eventManager.publishEvent('EmailSelected',{formElements:this.FormElements,email:email});
    rnJQuery('#sfNotReceivingEmail').unbind('click');
    var self=this;
    rnJQuery('#sfNotReceivingEmail').click(function()
    {
        var emailIndex=0;
        for(var i=0;i<self.Emails.length;i++)
        {
            if(email==self.Emails[i])
                emailIndex=i;
        }

        alert('Please make sure to save your form before using this feature, as the next page will use the latest saved information.');
        var url=smartFormsEmailDoctorUrl+'&action=debugemail&form_id='+smartFormId+'&email_index='+emailIndex;
        window.open(url,'_blank');

    });

    if(this.SelectedEmail!=null)
        this.UpdateSelectedEmail();
    this.SelectedEmail=email;
    if(typeof this.SelectedEmail.MultipleOptionsToEmails=='undefined')
        this.SelectedEmail.MultipleOptionsToEmails={};
    rnJQuery('#redNaoFromName').val(email.FromName);
    rnJQuery('#redNaoEmailSubject').val(email.EmailSubject);
    var self=this;
    this.SetupEmailTo(this.EmailToOptions+this.MultiSelectOptions,RedNaoGetValueOrEmpty(email.ToEmail).split(','),rnJQuery('#redNaoToEmail'),function(text){self.AddEmailIfValid(text,rnJQuery('#redNaoToEmail'),true);},true);
    this.SetupEmailTo(this.EmailToOptions,RedNaoGetValueOrEmpty(email.FromEmail).split(','),rnJQuery('#redNaoFromEmail'),function(text){self.AddEmailIfValid(text,rnJQuery('#redNaoFromEmail'),false);},false);
    rntinymce.get('redNaoTinyMCEEditor').setContent(email.EmailText);


};

RedNaoEmailEditor.prototype.CloseEmailEditor=function()
{
    this.Dialog.dialog('close');
};

RedNaoEmailEditor.prototype.AddFieldToEmail=function(id)
{
    var field="[field "+id.trim()+"]";
    if(this.LastFocus=='Body')
        rntinymce.get('redNaoTinyMCEEditor').execCommand('mceInsertContent', false,field );
    else
    {
        if(!RedNaoLicensingManagerVar.LicenseIsValid('Sorry, you can\'t add fields to the "subject" or "From Name" box in this version, please type the subject or From Name that you want to use'))
        {
            return false;
        }

        if(this.LastFocus=="Subject")
            rnJQuery('#redNaoEmailSubject').val(rnJQuery('#redNaoEmailSubject').val() + field).focus();
        else
            rnJQuery('#redNaoFromName').val(rnJQuery('#redNaoFromName').val() + field).focus();
    }
};



window.RedNaoEmailEditorVar=null;
rnJQuery(function(){
    window.RedNaoEmailEditorVar=new RedNaoEmailEditor();
});