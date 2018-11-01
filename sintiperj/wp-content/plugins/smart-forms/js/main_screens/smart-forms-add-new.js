window.smartFormsIntegrationFormula={};
window.smartFormsIntegrationFormula.Formulas={};
window.eventManager=null;
window.eventManager=require('sfMain/utilities/rxjs/EventManager').EventManager;
require('sfMain/editors/email-editor.js');
function SmartFormsAddNew()
{
    rnJQuery('#smartFormsBasicDetail [data-toggle="tooltip"]').tooltip({html:true});
    rnJQuery('.rnTheme').change(function(){
        if(rnJQuery('.rnTheme').val()=="material")
            $('.smartFormsSelectedElementContainer,#components').addClass('rnbsm');
        else
            $('.smartFormsSelectedElementContainer,#components').removeClass('rnbsm');
    });
    if(typeof smartFormId!='undefined')
        this.id=smartFormId;
    else
        this.id=0;

    var options=null;
    this.Emails=[{ToEmail:"",FromEmail:"",Name:smartFormsTranslation.Default,FromName:"",EmailSubject:"",EmailText:""}];
    this.ExtensionData={};
    this.RedirectToOptions=[this.CreateEmptyRedirectOption()];
    this.RedirectToMode='s';
    this.JavascriptCodes=[];
    this.ToolTipPosition='right';
    if(typeof smartFormsOptions!='undefined')
    {
        options=smartFormsOptions;
        rnJQuery('#smartFormName').val(smartFormsOptions.Name);
        rnJQuery('#smartFormDescription').val(smartFormsOptions.Description);

        if(RedNaoGetValueOrNull(smartFormsOptions.SendNotificationEmail)=='y')
            rnJQuery('#smartFormsSendNotificationEmail').attr('checked','checked');

        if(RedNaoGetValueOrNull(smartFormsOptions.Emails))
        {
            if(smartFormsOptions.Emails.length>0)
            {
                this.Emails=smartFormsOptions.Emails;
                if(this.Emails[0].FromEmail==null)
                    this.Emails[0].FromEmail='';
                this.EmailText=smartFormsOptions.Emails[0].EmailText;
            }
        }

        var property;
        if(typeof smartFormsOptions.Extensions!='undefined')
            for(property in smartFormsOptions.Extensions)
            {
                if(typeof this.ExtensionData[property]=='undefined')
                    this.ExtensionData[property]={};
                this.ExtensionData[property].Server=smartFormsOptions.Extensions[property];
            }
    }





    if(typeof smartFormClientOptions!='undefined')
    {
        if(typeof smartFormClientOptions.Theme!='undefined')
            rnJQuery('.rnTheme').val(smartFormClientOptions.Theme).change();

        if(typeof smartFormClientOptions.ToolTipPosition=='undefined')
            this.ToolTipPosition='None';
        else
            this.ToolTipPosition=smartFormClientOptions.ToolTipPosition;

        if(typeof smartFormClientOptions.JavascriptCode=='string' )
        {
            this.JavascriptCodes.push(
                {
                    Name:smartFormsTranslation.Default,
                    JavascriptCode:smartFormClientOptions.JavascriptCode,
                    ActionType:'customjs'
                }
            );
        }
        else if(rnJQuery.isArray(smartFormClientOptions.JavascriptCode))
            this.JavascriptCodes=smartFormClientOptions.JavascriptCode;


        if(typeof smartFormClientOptions.CSS!='undefined')
            rnJQuery('#smartFormsCSSText').val(smartFormClientOptions.CSS);

        if(typeof smartFormClientOptions.redirect_to=='string')
            this.RedirectToOptions[0].URL=smartFormClientOptions.redirect_to;
        else
            if(typeof smartFormClientOptions.redirect_to!='undefined')
                this.RedirectToOptions=smartFormClientOptions.redirect_to;


        rnJQuery('#alertMessageInput').val(RedNaoGetValueOrEmpty(smartFormClientOptions.alert_message));
        if(RedNaoGetValueOrEmpty(smartFormClientOptions.redirect_to_cb)=='y')
            rnJQuery('#redNaoRedirectToCB').attr('checked','checked');
        if(RedNaoGetValueOrEmpty(smartFormClientOptions.alert_message_cb)=='y')
            rnJQuery('#redNaoAlertMessageCB').attr('checked','checked');
        if(RedNaoGetValueOrNull(smartFormClientOptions.Formulas)!=null)
            smartFormsIntegrationFormula.Formulas=smartFormClientOptions.Formulas;
        if(RedNaoGetValueOrNull(smartFormClientOptions.InvalidInputMessage)!=null)
            rnJQuery("#smartFormsInvalidFieldMessage").val(smartFormClientOptions.InvalidInputMessage);
        if(RedNaoGetValueOrEmpty(smartFormClientOptions.DontClearForm)=='y')
            rnJQuery('#rednaoDontClearForm').attr('checked','checked');

        if(typeof smartFormClientOptions.Extensions !="undefined")
            for(property in smartFormClientOptions.Extensions)
            {
                if(typeof this.ExtensionData[property]=='undefined')
                    this.ExtensionData[property]={};
                this.ExtensionData[property].Client=smartFormClientOptions.Extensions[property];
            }
    }
    this.LoadRedirectToInfo();

    var formElements=[];
    if(typeof smartFormsElementOptions!='undefined')
        formElements=smartFormsElementOptions;
    this.CustomStyles=rnJQuery("<style type='text/css'></style>");
    rnJQuery("head").append(this.CustomStyles);

    this.FormBuilder= new RedNaoFormBuilder(options,formElements,(typeof smartFormClientOptions=='undefined'?{}:smartFormClientOptions) );

    var self=this;
    rnJQuery('#smartFormsBasic').click(self.SmartFormsTagClicked);
    rnJQuery('#smartFormsSaveButton').click(function(e){self.SaveForm(e);});


    rnJQuery('#redNaoEditEmailButton').click(function(e){e.preventDefault();self.EditEmailClicked();});
    rnJQuery('#redNaoRedirectToCB').change();
    rnJQuery('#redNaoAlertMessageCB').change();
    rnJQuery('#rednaoDontClearForm').change();
    rnJQuery('#sfApplyCss').click(function(){self.ApplyCustomCSS();});
    this.Subscribers=ISmartFormsAddNew.prototype.Subscribers;
    RedNaoEventManager.Subscribe('FormulaButtonClicked',function(data){self.OpenFormulaBuilder(data.FormElement,data.PropertyName,data.AdditionalInformation,data.Image)});

    var i;
    for(i=0;i<self.Subscribers.length;i++)
    {
        this.Subscribers[i].FormElements=this.FormBuilder.RedNaoFormElements;
        var saveDataId= self.Subscribers[i].GetSaveDataId();
        if(saveDataId!=null)
        {
            if(typeof self.ExtensionData[saveDataId]!='undefined')
                self.Subscribers[i].LoadSavedData(self.ExtensionData[saveDataId]);
        }
    }

    this.ApplyCustomCSS();
    self.PublishToSubscribers('OnLoadComplete');
    this.InitializeAfterSubmitUI();
    this.InitializeJavascriptTab();
    this.InitializeToolTipButtons();



    /*rnSystem.import('sfMain/utilities/rxjs/EventManager').then(function(module){
        eventManager=module.EventManager.prototype.constructor;
        eventManager.publishEvent('Initialize');
    });*/

}

SmartFormsAddNew.prototype.InitializeToolTipButtons=function()
{
    rnJQuery('.sfToolTipPosition').css('display','inline');
    var self=this;
    rnJQuery('#toolTipPosition_none').click(function(){self.SelectToolTipPosition('none')});
    rnJQuery('#toolTipPosition_left').click(function(){self.SelectToolTipPosition('left')});
    rnJQuery('#toolTipPosition_top').click(function(){self.SelectToolTipPosition('top')});
    rnJQuery('#toolTipPosition_right').click(function(){self.SelectToolTipPosition('right')});
    rnJQuery('#toolTipPosition_bottom').click(function(){self.SelectToolTipPosition('bottom')});
    this.SelectToolTipPosition(this.ToolTipPosition);
};

SmartFormsAddNew.prototype.SelectToolTipPosition=function(position)
{
    rnJQuery('#toolTipPosition_none,#toolTipPosition_left,#toolTipPosition_top,#toolTipPosition_right,#toolTipPosition_bottom').removeClass('active');
    this.ToolTipPosition=position;
    rnJQuery('#toolTipPosition_'+position).addClass('active');
};

SmartFormsAddNew.prototype.InitializeJavascriptTab=function()
{
    var self=this;
    this.JavascriptListManager=rnJQuery('#javascriptList').RNList({
        ItemCreated:function(createdItem){
            self.RestoreDefault();
            createdItem.JavascriptCode=rnJQuery('#smartFormsJavascriptText').val();
            createdItem.ActionType='customjs';
        },
        ItemSelected:function(item){
            rnJQuery('#smartFormsJavascriptText').val(item.JavascriptCode);
            rnJQuery('#smartFormsJavascriptText').removeAttr('disabled');
        },
        ItemUpdated:function(item)
        {
            item.JavascriptCode=rnJQuery('#smartFormsJavascriptText').val();
        },
        Clear:function()
        {
            rnJQuery('#smartFormsJavascriptText').val('');
            rnJQuery('#smartFormsJavascriptText').attr('disabled','disabled');
        },
        CreationLabel:smartFormsTranslation.ClickHereToCreateSnippet,
        Items:this.JavascriptCodes
    });

    if(this.JavascriptCodes.length>0)
        this.JavascriptListManager.SelectItem(this.JavascriptCodes[0]);
};



SmartFormsAddNew.prototype.InitializeAfterSubmitUI=function()
{
    rnJQuery('#smartFormsAfterSubmitDiv .sfAfterSubmitAction').each(
        function()
        {
            var $row=rnJQuery(this);
            $row.find('td:first input[type="checkbox"]').change(
                function()
                {
                    $actionsContainer=rnJQuery(this).parent().parent().find('td:nth-child(2)');
                    if(rnJQuery(this).is(':checked'))
                    {
                        $actionsContainer.find('button,input[type=text],textarea').removeAttr('disabled');
                        $actionsContainer.find('span').removeClass('text-muted');
                    }else{
                        $actionsContainer.find('button,input[type=text],textarea').attr('disabled','disabled');
                        $actionsContainer.find('span').addClass('text-muted');
                    }
                }
            ).change();
        }
    );
};

SmartFormsAddNew.prototype.ApplyCustomCSS=function()
{
    this.CustomStyles.empty().append(rnJQuery('#smartFormsCSSText').val());
};

SmartFormsAddNew.prototype.PublishToSubscribers=function(methodName,args)
{
    for(var i=0;i<this.Subscribers.length;i++)
    {
        if(args!=null)
            this.Subscribers[i][methodName](args);
        else
            this.Subscribers[i][methodName]();
    }
};


SmartFormsAddNew.prototype.OpenParameterPicker=function($row)
{
    var self=this;
    this.ShowFieldPicker(smartFormsTranslation.SelectTheFieldsRedirectPage,this.FormBuilder.RedNaoFormElements.slice(0),function(success,selectedFields){
                                                                                                                                                    if(success)self.AddFieldsToRedirectUrl(selectedFields,$row);});
};


SmartFormsAddNew.prototype.AddFieldsToRedirectUrl=function(selectedFields,$row)
{
    var parameterString="";
    for(var i=0;i<selectedFields.length;i++)
    {
        parameterString+=selectedFields[i].Id+'={'+selectedFields[i].Id+'}'+'&';
    }
    parameterString=parameterString.substring(0,parameterString.length-1);

    var currentRedirectUrl=rnJQuery.trim($row.find('.redirectToInput').val());
    if(currentRedirectUrl.indexOf('?')>=0)
        currentRedirectUrl+='&';
    else
    {
        if(currentRedirectUrl.length==0||currentRedirectUrl[currentRedirectUrl.length-1]!='/')
            currentRedirectUrl+='/';
        currentRedirectUrl+='?';
    }
    currentRedirectUrl+=parameterString;

    $row.find('.redirectToInput').val(currentRedirectUrl).change();



};

SmartFormsAddNew.prototype.ShowFieldPicker=function(popUpTitle,formElements,callBack)
{
    var $dialog=rnJQuery(
        '<div class="modal fade"  tabindex="-1">'+
            '<div class="modal-dialog">'+
            '<div class="modal-content">'+
            '<div class="modal-header">'+
            '<h4 style="display: inline" class="modal-title">'+popUpTitle+'</h4>'+
            '</div>'+
            '<div class="modal-body">'+
            '</div>'+
            '<div class="modal-footer">'+
            '<button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>Cancel</button>'+
            '<button type="button" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span>Apply</button>'+
            '</div>'+
            '</div>'+
            '</div>'+
            '</div>');

    var container=rnJQuery('<div class="bootstrap-wrapper"></div>');
    container.append($dialog);
    rnJQuery('body').append(container);
    $dialog.modal('show');

    $dialog.on('hidden.bs.modal',function(){
       $dialog.remove();
    });

    var $body=rnJQuery('<div class="bootstrap-wrapper"><form role="form"></form></div>');
    var $form=$body.find("form");

    formElements.splice(0,0,{Id:'_formid',Options:{Label:'Form Id'},StoresInformation:function(){return true;}});
    for(var i=0;i<formElements.length;i++)
    {
        if(formElements[i].StoresInformation())
        {
            $form.append('<div class="checkbox" style="font-size: 15px;margin-bottom: 10px;">'+
                '<input id="fieldPickerFor'+i+'" type="checkbox" value="'+RedNaoEscapeHtml(formElements[i].Id)+'"> ' +
                '<label for="fieldPickerFor'+i+'">'+RedNaoEscapeHtml(formElements[i].Options.Label)+ '</label>'+
                '</div>');
        }
    }
    $dialog.find('.modal-body').append($form);
    $dialog.find(".btn-success").click(function()
    {
        $dialog.modal('hide');
        var $checkedBoxes=$form.find('input[type=checkbox]:checked');
        var selectedFields=[];
        for(var i=0;i<$checkedBoxes.length;i++)
        {
            for(var t=0;t<formElements.length;t++)
                if(formElements[t].Id==$checkedBoxes[i].value)
                {
                    selectedFields.push(formElements[t]);
                    break;
                }
        }
        callBack(true,selectedFields);
    });

};



SmartFormsAddNew.prototype.OpenFormulaBuilder=function(formElement,propertyName,additionalInformation,image)
{
    RedNaoFormulaWindowVar.OpenFormulaEditor(this.FormBuilder.RedNaoFormElements,formElement.Options,propertyName,additionalInformation,image);
};

SmartFormsAddNew.prototype.EditEmailClicked=function()
{
    RedNaoEmailEditorVar.OpenEmailEditor(this.FormBuilder.RedNaoFormElements,this.Emails);
};



SmartFormsAddNew.prototype.DonationConfigurationIsValid=function()
{
    var formElements=this.FormBuilder.RedNaoFormElements;
    for(var i=0;i<formElements.length;i++)
    {
        if(formElements[i].Options.ClassName=="rednaodonationbutton")
        {
            if(rnJQuery('#redNaoCampaign').val()=='')
            {
                alert(smartFormsTranslation.SelectCampaignBeforeSaving);
                this.GoToSmartDonations();
                return false;
            }

            if(rnJQuery('#smartDonationsEmail').val()=='')
            {
                alert(smartFormsTranslation.SelectPaypalEmailBeforeSaving);
                this.GoToSmartDonations();
                return false;
            }

            if(typeof smartFormsIntegrationFormula.Formulas.DonationFormula=='undefined'||smartFormsIntegrationFormula.Formulas.DonationFormula=='')
            {
                alert(smartFormsTranslation.SetupDonationFormulaBeforeSaving);
                this.GoToSmartDonations();
                return false;
            }
        }

    }

    return true;
};

SmartFormsAddNew.prototype.FormOptionsAreValid = function (formOptions) {
    rnJQuery("#redNaoEditEmailButton").removeClass('redNaoInvalidInput');
    if(formOptions.SendNotificationEmail=='y')
    {
        if(formOptions.Emails[0].EmailText.trim()=="")
        {
            rnJQuery("#redNaoEditEmailButton").addClass('redNaoInvalidInput');
            alert(smartFormsTranslation.ConfigureEmailIsGoingToBeSent);
            this.GoToAfterSubmit();
            return false;
        }
    }

    return true;
};
SmartFormsAddNew.prototype.SaveForm=function(e)
{
    e.preventDefault();
    e.stopPropagation();

    var formOptions=this.GetFormOptions();
    if(formOptions==null)
        return;
    var clientFormOptions=this.GetClientFormOptions(formOptions.UsesCaptcha,formOptions.CaptchaVersion);
    if(clientFormOptions==null)
        return;
    var elementsOptions=this.FormBuilder.GetFormInformation();
    if(!this.DonationConfigurationIsValid())
        return;

    if(!this.FormOptionsAreValid(formOptions))
        return;

    this.ExecuteSaveRequest(formOptions,clientFormOptions,elementsOptions);

};

SmartFormsAddNew.prototype.ExecuteSaveRequest=function(formOptions,clientFormOptions,elementOptions)
{
    var data={};
    data.form_options=JSON.stringify(formOptions);
    data.element_options=JSON.stringify(elementOptions);
    data.donation_email=rnJQuery('#smartDonationsEmail').val();
    data.client_form_options=JSON.stringify(clientFormOptions);
    data.extensions={};

    var self=this;
    rnJQuery('#smartFormsSaveButton').RNWait('start');
    data.id=this.id;
    data.action="rednao_smart_forms_save";
    rnJQuery.post(ajaxurl,data,function(result){
        rnJQuery('#smartFormsSaveButton').RNWait('stop');
        result=rnJQuery.parseJSON(result);
        alert(result.Message);
        if(result.Message=="saved")
            self.id=result.FormId;
    });
};

SmartFormsAddNew.prototype.GetFormOptions=function()
{
    var formOptions={};
    formOptions.Name=rnJQuery('#smartFormName').val();
    formOptions.Description=rnJQuery('#smartFormDescription').val();
    formOptions.NotifyTo=rnJQuery('#smartFormsSubmissionNotifyTo').val();
    formOptions.LatestId=sfFormElementBase.IdCounter;
    formOptions.SendNotificationEmail=(rnJQuery('#smartFormsSendNotificationEmail').is(':checked')?'y':'n');
    formOptions.Emails=this.Emails;
    formOptions.FieldServerOptions={};

    var usesCaptcha='n';
    var formElements=this.FormBuilder.RedNaoFormElements;
    var i;
    var captchaVersion='';
    for(i=0;i<formElements.length;i++)
    {
        if(formElements[i].Id=="captcha")
        {
            usesCaptcha='y';
            captchaVersion="1";
        }
        if(formElements[i].Id=="captcha2")
        {
            usesCaptcha='y';
            captchaVersion="2";
        }

        formOptions.FieldServerOptions[formElements[i].Id]=formElements[i].ServerOptions;
    }
    formOptions.UsesCaptcha=usesCaptcha;
    formOptions.CaptchaVersion=captchaVersion;
    formOptions.RedNaoSendThankYouEmail=(rnJQuery('#redNaoSendThankYouEmail').is(':checked')?'y':'n');
    formOptions.Extensions={};

    for(i=0;i<this.Subscribers.length;i++)
    {
        if(this.Subscribers[i].GetSaveDataId()!=null)
        {
            try {
                var dataToSave=this.Subscribers[i].GetServerDataToSave();
                if(dataToSave!=null)
                    formOptions.Extensions[this.Subscribers[i].GetSaveDataId()]=dataToSave;
            }catch(Exception)
            {
                return null;
            }

        }
    }
    return formOptions;
};

SmartFormsAddNew.prototype.GetClientFormOptions=function(usesCaptcha,captchaVersion)
{
    var clientOptions= {
        JavascriptCode:this.GetJavascriptCode(),
        CSS:rnJQuery('#smartFormsCSSText').val(),
        Conditions:this.FormBuilder.Conditions,
        UsesCaptcha:usesCaptcha,
        CaptchaVersion:captchaVersion,
        alert_message:rnJQuery('#alertMessageInput').val(),
        alert_message_cb:(rnJQuery('#redNaoAlertMessageCB').is(':checked')?'y':'n'),
        DontClearForm:(rnJQuery('#rednaoDontClearForm').is(':checked')?'y':'n'),
        redirect_to:this.RedirectToOptions,
        redirect_to_cb:(rnJQuery('#redNaoRedirectToCB').is(':checked')?'y':'n'),
        Campaign:rnJQuery('#redNaoCampaign').val(),
        PayPalEmail:rnJQuery('#smartDonationsEmail').val(),
        PayPalDescription:rnJQuery('#smartDonationsDescription').val(),
        PayPalCurrency:rnJQuery('#smartDonationsCurrencyDropDown').val(),
        Formulas:smartFormsIntegrationFormula.Formulas,
        InvalidInputMessage:rnJQuery("#smartFormsInvalidFieldMessage").val(),
        FormType:this.FormBuilder.FormType,
        SplitSteps:this.FormBuilder.GetMultipleStepsOptions(),
        ToolTipPosition:this.ToolTipPosition,
        Theme:rnJQuery('.rnTheme').val()!=''?rnJQuery('.rnTheme').val():'basic'
    };
    clientOptions.Extensions={};
    for(var i=0;i<this.Subscribers.length;i++)
    {
        if(this.Subscribers[i].GetSaveDataId()!=null)
        {
            try{
                var dataToSave=this.Subscribers[i].GetClientDataToSave();
                if(dataToSave!=null)
                    clientOptions.Extensions[this.Subscribers[i].GetSaveDataId()]=dataToSave;
            }catch(Exception)
            {
                return null;
            }

        }
    }

    return clientOptions;


};

SmartFormsAddNew.prototype.SendTestEmail=function()
{

    RedNaoEmailEditorVar.UpdateSelectedEmail();
    var emailData={};
    emailData.action="rednao_smart_form_send_test_email";
    emailData.element_options=JSON.stringify(this.FormBuilder.GetFormInformation());
    emailData=rnJQuery.extend(true, emailData,this.Emails[0]);
    // this.FillEmailData(emailData);

    //noinspection JSUnresolvedVariable
    rnJQuery.post(ajaxurl,emailData,function(result){
        result=rnJQuery.parseJSON(result);
        alert(result.Message);

    });

};

SmartFormsAddNew.prototype.ActivateTab=function(activationName)
{
    rnJQuery('#smartFormsTopTab a').removeClass("nav-tab-active");
    rnJQuery('#smartFormsGeneralDiv,#smartFormsJavascriptDiv,#smartFormsCSSDiv,#smartFormsAfterSubmitDiv,#smartDonationsDiv,.smartFormsCustomTab').css('display','none');

    rnJQuery('#'+activationName+'Tab').addClass('nav-tab-active');
    rnJQuery('#'+activationName+'Div').css('display','block');

    var tabId=rnJQuery('#'+activationName+'Tab').data('tab-id');
    if(typeof tabId!='undefined'&&tabId!=null)
    {
        this.PublishToSubscribers('TabActivated',{"TabId":tabId});
    }

};

SmartFormsAddNew.prototype.GoToGeneral=function()
{
    this.ActivateTab('smartFormsGeneral');
};

SmartFormsAddNew.prototype.GoToJavascript=function()
{
    this.ActivateTab('smartFormsJavascript');
};

SmartFormsAddNew.prototype.GoToAfterSubmit=function()
{
    this.ActivateTab('smartFormsAfterSubmit');
};

SmartFormsAddNew.prototype.GoToCSS=function()
{
    this.ActivateTab('smartFormsCSS');
};

SmartFormsAddNew.prototype.RestoreDefault=function()
{
    rnJQuery('#smartFormsJavascriptText').val('\
  //AUTO GENERATED CODE, DO NOT DELETE\n\
(function(){var javaObject={\n\n\n\
//YOU CAN PUT YOUR CODE BELLOW\n\n\n\
//jQueryFormReference:A jquery reference of the loaded form\n\
AfterFormLoaded:function(jQueryFormReference){\n\
     //Here you can put code that you want to be executed after the form is loaded\n\
},\n\n\n\
//jQueryFormReference:A jquery reference of the loaded form\n\
//formData:An object with the information that is going to be submitted\n\
BeforeFormSubmit:function(formData,jQueryFormReference){\n\
    //Here you can put code that you want to be executed before the form is submitted\n\
}\n\n\n\n\
//MORE AUTO GENERATED CODE, DO NOT DELETE\n\
}; return javaObject;})\
    ');
};

SmartFormsAddNew.prototype.GetJavascriptCode=function()
{
    this.JavascriptListManager.Commit();
    return this.JavascriptCodes;
};


SmartFormsAddNew.prototype.Validate=function()
{
    var javascriptCode=rnJQuery('#smartFormsJavascriptText').val();
    try{
        var obj=eval(javascriptCode);
        var code=obj();
        if(typeof code.AfterFormLoaded=='undefined')
        {
            throw 'Method AfterFormLoaded was not found';
        }
        code.AfterFormLoaded(rnJQuery('<form></form>'));
        if(typeof code.BeforeFormSubmit=='undefined')
        {
            throw 'Method BeforeFormSubmit was not found';
        }
        code.BeforeFormSubmit({},rnJQuery('<form></form>'));
    }catch(exception)
    {
        alert(smartFormsTranslation.AnErrorOccurred+'\n'+exception);
        return;
    }

    alert(smartFormsTranslation.CodeTestedSuccessfully);
};

//used in an string
//noinspection JSUnusedGlobalSymbols
SmartFormsAddNew.prototype.GoToCustomTab=function(tabIndex)
{
    this.ActivateTab("smartFormsCustom"+tabIndex.toString());
};


//------------------------------------------------------------------------------------------------------------------------------------------------RedirectToUIConfiguration------------------------------------------------------------------------------------------------------------------------------
SmartFormsAddNew.prototype.LoadRedirectToInfo=function(redirectOptions)
{

    for(var i=0;i<this.RedirectToOptions.length;i++)
    {
        var $row=this.CreateRedirectToOption(this.RedirectToOptions[i]);
        if(i==0)
            $row.find('.sfDelete').remove();
    }
    this.ValidateRedirectToMode();
};


SmartFormsAddNew.prototype.CreateRedirectToOption=function(redirectOption)
{

    var $row=rnJQuery('<tr>'+
    '<td style="padding-bottom:3px;"><input type="text" style="width: 600px !important;display: inline;" class="redirectToInput form-control"  /></td>'+
    '<td style="padding-bottom:3px;">'+
    '<button class="btn btn-default smartFormsAddParameter" >' +smartFormsTranslation.AddParametersToUrl+'</button>'+
    '<span  class="addConditionLogic glyphicon glyphicon glyphicon-link sfConditionLogic" style="cursor: pointer; cursor:hand;margin-left:5px;" title="'+smartFormsTranslation.AddEditConditionalLogic+'"></span>'+
    '<span  class="glyphicon glyphicon glyphicon-minus sfDelete" style="cursor: pointer; cursor:hand;margin-left:5px;" title="'+smartFormsTranslation.DeleteRow+'"></span>'+
    '</td>'+
    '</tr>');

    var self=this;
    $row.find('.smartFormsAddParameter').click(function(e){e.preventDefault();self.OpenParameterPicker($row);});
    $row.find('.redirectToInput').val(redirectOption.URL);


    $row.find('.redirectToInput').change(function()
    {
        redirectOption.URL=rnJQuery(this).val();
    });
    $row.find('.addConditionLogic').click(function()
    {
        if(!rnJQuery('#redNaoRedirectToCB').is(':checked'))
            return;

        var pop=new SmartFormsPopUpWizard(
            {
                Steps:[new SFRedirectWizardCondition(self.FormBuilder.RedNaoFormElements,redirectOption.URL)],
                SavedData:redirectOption,
                OnFinish:function(data){
                    self.ValidateRedirectToMode();
                }
            });
        pop.Show();
    });

    $row.find('.sfDelete').click(function()
    {
        rnJQuery.RNGetConfirmationDialog().ShowConfirmation(smartFormsTranslation.DeletingRow,smartFormsTranslation.AreYouSureDeleteRow,
            function(){
                var index=rnJQuery.inArray(redirectOption,self.RedirectToOptions);
                if(index>=0)
                {
                    self.RedirectToOptions.splice(index,1);
                    $row.remove();
                }
            });
    });
    rnJQuery('#redirectToOptionsItems').append($row);

    return $row;

};


SmartFormsAddNew.prototype.ValidateRedirectToMode=function()
{
    if(this.RedirectToMode=='s')
    {
        if(this.RedirectToOptions.length>1||(this.RedirectToOptions.length==1&&this.RedirectToOptions[0].RCSettings.Redirect!='always'))
            this.ChangeToMultipleRedirectsMode();
    }

    if(this.RedirectToMode=='m')
    {
        if(this.RedirectToOptions.length==0||(this.RedirectToOptions.length==1&&this.RedirectToOptions[0].RCSettings.Redirect=='always'))
        {
            this.ChangeToSingleRedirectMode();
        }
    }

};

SmartFormsAddNew.prototype.GoToSmartDonations=function()
{
    this.ActivateTab('smartDonations');
};

SmartFormsAddNew.prototype.CreateEmptyRedirectOption=function()
{
    return {
        URL:'',
        RCSettings:{
                Redirect:'always',
                ConditionSettings:[]
        }
    }
};

SmartFormsAddNew.prototype.ChangeToMultipleRedirectsMode=function()
{
    this.RedirectToMode='m';
    var $buttonRow=rnJQuery('<tr class="sfAddRedirectToRow"><td><button style="margin-top:0px;" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span>' +smartFormsTranslation.AddAnotherRedirectToUrl+'</button></td></tr>');
    var self=this;
    $buttonRow.find('button').click(function()
    {
        var newRedirectOption=self.CreateEmptyRedirectOption();
        self.RedirectToOptions.push(newRedirectOption);
        self.CreateRedirectToOption(newRedirectOption);
    });
    $buttonRow.insertAfter(rnJQuery('#redirectToOptionsItems'));
};

SmartFormsAddNew.prototype.ChangeToSingleRedirectMode=function()
{
    this.RedirectToMode='s';
    rnJQuery('.sfAddRedirectToRow').remove();
};
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


window.SmartFormsAddNewVar=null;
rnJQuery(function(){window.SmartFormsAddNewVar=new SmartFormsAddNew();});

window.SmartFormsAddNew=SmartFormsAddNew;