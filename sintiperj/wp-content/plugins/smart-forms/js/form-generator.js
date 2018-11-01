function smartFormGenerator(options){
    this.client_form_options=options.client_form_options;
    this.Extensions=[];
    RedNaoEventManager.Publish('GetClientExtension',{Generator:this,Extensions:this.Extensions});
    this.MultipleStepsManager=null;
    this.RedirectUrl='';
    this.InitializeConditionalLogic();
    this.SetDefaultIfUndefined('InvalidInputMessage','*Please fill all the required fields');
    this.SubmittingThroughIframe=false;

    this.JavascriptCodes=[];
    if(typeof this.client_form_options.JavascriptCode!='undefined')
    {
        if(typeof this.client_form_options.JavascriptCode=='string')
            this.client_form_options.JavaScriptCode=[{
                JavascriptCode:this.client_form_options.JavascriptCode,
                ActionType:'customjs'
            }];
    }

    if(typeof this.client_form_options.ToolTipPosition=='undefined')
        this.client_form_options.ToolTipPosition='none';

    var i;
    for(i=0;i<this.client_form_options.JavascriptCode.length;i++)
    {
        try{
            this.JavascriptCodes.push(eval(this.client_form_options.JavascriptCode[i].JavascriptCode)());

        }catch(exception)
        {

        }
    }

    if(typeof this.client_form_options.Theme!='undefined'&&this.client_form_options.Theme=='material'){
        this.Theme='material';
        rnJQuery.RNLoadLibrary([smartFormsPath+'js/bootstrap/material.min.js'],[smartFormsPath+'css/bootstrap/bootstrap-material-scoped.css'],function(){

        });
    }else
        this.Theme='basic';


    this.form_id=options.form_id;
    this.options=options;
    this.RedNaoFormElements=[];
    this.FormElements=[];
    var elementOptions=options.elements;
    this.client_form_options.DocumentWidth=rnJQuery(window).width();
    for(i=0;i<elementOptions.length;i++)
    {
        var element=sfRedNaoCreateFormElementByName(elementOptions[i].ClassName,elementOptions[i]);
        element.FormId=this.form_id;
        element.InvalidInputMessage=RedNaoEscapeHtml(this.client_form_options.InvalidInputMessage);
        element.ClientOptions=this.client_form_options;
        this.RedNaoFormElements.push(element);
        this.FormElements.push(element);
    }

    this.containerName=options.container;
    if(typeof this.client_form_options.CSS!='undefined')
        this.CreateCSS();
    if(this.client_form_options.redirect_to_cb=="y"&&typeof this.client_form_options.redirect_to=='string')
    {
        this.client_form_options.redirect_to={
            URL:this.client_form_options.redirect_to,
            RCSettings:{
                Redirect:'always',
                ConditionSettings:[]
            }
        }
    }
    this.CreateForm();

}

smartFormGenerator.prototype.GetCurrentData=function()
{
  return RedNaoFormulaManagerVar.Data;
};

smartFormGenerator.prototype.CreateCSS=function()
{
    if(SmartFormsIsIE8OrEarlier())
        return;

    var $style=rnJQuery("<style type='text/css'></style>");
    $style.append(this.client_form_options.CSS);
    rnJQuery("head").append($style);
};

smartFormGenerator.prototype.InitializeConditionalLogic=function()
{
    if(typeof this.client_form_options.Conditions !='undefined')
    {
        for(var i=0;i<this.client_form_options.Conditions.length;i++)
        {
            var condition=this.client_form_options.Conditions[i];
            this.client_form_options.Conditions[i]=SmartFormsGetConditionalHandlerByType(condition.Type,condition);
        }
    }else
        this.client_form_options.Conditions=[];
};

smartFormGenerator.prototype.SetDefaultIfUndefined=function(propertyName,defaultValue)
{
    if(typeof this.client_form_options[propertyName]=='undefined')
        this.client_form_options[propertyName]=defaultValue;
    if(typeof this.client_form_options.CaptchaVersion=='undefined')
        this.client_form_options.CaptchaVersion='1';
};

smartFormGenerator.prototype.CreateForm=function(){
    var container=this.GetRootContainer();
    container.empty();
    var themeStyle=this.Theme=='material'?'rnbsm':'';
    this.JQueryForm=rnJQuery('<form id="sf'+this.form_id+'" class="form-horizontal '+themeStyle+'" ></form>');
    this.JQueryForm.css('visibility','hidden');
    container.append(this.JQueryForm);

    var i;
    for(i=0;i<this.RedNaoFormElements.length;i++)
    {
        this.RedNaoFormElements[i].AppendElementToContainer(this.JQueryForm);
    }
    this.JQueryForm.append('<div class="sfClearFloat"></div>');





    var self=this;
    //if(RedNaoGetValueOrNull(this.client_form_options.Campaign))
      //  this.CreatePayPalHiddenFields();

    this.SubmittingRedNaoDonationForm='n';
    this.JQueryForm.submit(function(e){
        if(self.SubmittingRedNaoDonationForm=='y')
        {
            self.SubmittingRedNaoDonationForm='n';
            return;
        }

        if(self.SubmittingThroughIframe==true)
        {
            self.SubmittingThroughIframe=false;
            return;
        }

        e.preventDefault();
        e.stopPropagation();
        self.SaveForm();
    });



    if(typeof this.client_form_options.FormType!='undefined'&&this.client_form_options.FormType=='sec')
    {
        rnJQuery.RNLoadLibrary([smartFormsPath+'js/utilities/fuelux/wizard.js' ,smartFormsPath+'js/multiple_steps/multiple_steps_base.js'],[smartFormsPath+'js/utilities/fuelux/fuelux.css'],function(){
            self.InitializeMultipleSteps();
            self.FormLoaded();
        });
    }else
    {
        if(this.JQueryForm.width()<=500)
            this.JQueryForm.parent().addClass('compact');
        this.FormLoaded();
    }

};

smartFormGenerator.prototype.FireExtensionMethod=function(methodName)
{
    for(var i=0;i<this.Extensions.length;i++)
        this.Extensions[i][methodName]();
};

smartFormGenerator.prototype.FormLoaded=function()
{
    this.FireExtensionMethod('BeforeInitializingFieldData');
    var i;
    for(i=0;i<this.RedNaoFormElements.length;i++)
    {
        if(this.RedNaoFormElements[i].StoresInformation())
            RedNaoFormulaManagerVar.SetFormulaValue(this.RedNaoFormElements[i].Id,this.RedNaoFormElements[i].GetValueString())
    }

    RedNaoFormulaManagerVar.RefreshAllFormulas();
    if(RedNaoGetValueOrNull(this.client_form_options.Campaign))
        this.CreatePayPalHiddenFields();
    this.ExecuteConditionalLogicInAllFields();
    this.JQueryForm.css('visibility', 'visible');
    try{
        for(i=0;i<this.JavascriptCodes.length;i++)
            this.JavascriptCodes[i].AfterFormLoaded();
    }catch(exception)
    {

    }
};

smartFormGenerator.prototype.ExecuteConditionalLogicInAllFields=function()
{
    for(i=0;i<this.client_form_options.Conditions.length;i++)
    {
        this.client_form_options.Conditions[i].Initialize(this,RedNaoFormulaManagerVar.Data);
    }
};

smartFormGenerator.prototype.InitializeMultipleSteps=function()
{
    this.MultipleStepsManager=new SfMultipleStepsBase(this.client_form_options.SplitSteps,this.JQueryForm,this.FormElements,this);
    this.MultipleStepsManager.Generate();
};

smartFormGenerator.prototype.CreatePayPalHiddenFields=function()
{
    if(smartDonationsSandbox=='y')
        this.JQueryForm.attr('action','https://www.sandbox.paypal.com/cgi-bin/webscr');
    else
        this.JQueryForm.attr('action','https://www.paypal.com/cgi-bin/webscr');
    this.JQueryForm.attr('method','POST');
    this.JQueryForm.attr('target','_self');

    var target="_self";
    if(window.self !== window.top)
        target="_parent";
    this.JQueryForm.attr('target',target);


    var options=this.client_form_options;
    this.JQueryForm.append(' <input type="hidden" name="cmd" class="smartDonationsPaypalCommand" value="_donations">\
                <input type="hidden" name="item_name" value="'+options.PayPalDescription+'">\
                <input type="hidden" name="business" value="'+options.PayPalEmail+'">\
                <input type="hidden" name="lc" value="US">                       \
                <input type="hidden" name="no_note" value="0">                    \
                <input type="hidden" name="currency_code" value="'+options.PayPalCurrency+'">             \
                <input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">\
                <input type="hidden" name="custom" value=type=form&campaign_id='+options.Campaign+'&formId='+this.options.form_id+'>\
                <input type="hidden" name="amount" class="amountToDonate" value="0">\
                <input name="bn" value="Rednao_SP" type="hidden">\
                <input type="hidden" name="notify_url" value="'+smartDonationsRootPath+'ipn/rednao_paypal_ipn.php">'
        );

    if(RedNaoGetValueOrEmpty(this.client_form_options.redirect_to_cb)=="y")
        this.JQueryForm.append('<input type="hidden" name="return" value="">');

};

smartFormGenerator.prototype.GenerationCompleted=function()
{
    var form=this.GetRootContainer().find('form');
    form.addClass('formelements').attr('id','redNaoElementlist');

    for(var i=0;i<this.FormElements.length;i++)
    {
        this.FormElements[i].AppendElementToContainer(form);
    }

    var self=this;
    form.find('.redNaoDonationButton').click(function()
        {

            try{

                self.SaveForm();

            }catch(error)
            {


            }finally{
                //noinspection ReturnInsideFinallyBlockJS
                return false;
            }
        }
    );

};

smartFormGenerator.prototype.GenerateDefaultStyle=function()
{
    this.styles.formelements="width:600px;padding:10px;margin:0px;";
};


smartFormGenerator.prototype.SaveForm=function()
{
    var formValues={};
    var formIsValid=true;
    var amount=0;

    this.GetRootContainer().find('.redNaoValidationMessage').remove();
    this.GetRootContainer().find('.redNaoSubmitButton').removeClass('btn-danger');
    this.GetRootContainer().find('.redNaoInputText,.redNaoRealCheckBox,.redNaoInputRadio,.redNaoInputCheckBox,.redNaoSelect,.redNaoTextArea,.redNaoInvalid,.has-error').removeClass('redNaoInvalid').removeClass('has-error');
    var isUsingAFileUploader=false;
    RedNaoEventManager.Publish('BeforeValidatingForm',{Generator:this});
    var firstInvalidField=null;
    for(var i=0;i<this.FormElements.length;i++)
    {
        this.FormElements[i].ClearInvalidStyle();
        if(this.FormElements[i].Options.ClassName=="sfFileUpload")
            isUsingAFileUploader=true;
        if(!this.FormElements[i].IsIgnored()&&!this.FormElements[i].IsValid())
        {
            formIsValid=false;
            if(firstInvalidField==null)
                firstInvalidField=this.FormElements[i];
            continue;
        }
        if(this.FormElements[i].StoresInformation())
        {
            var value=this.FormElements[i].GetValueString();
            amount+=this.FormElements[i].amount;
            formValues[this.FormElements[i].Id]=value;
        }
    }
    if(!formIsValid)
    {
        //this.GetRootContainer().prepend('<p class="redNaoValidationMessage" style="margin:0;padding: 0; font-style: italic; color:red;font-family:Arial,serif;font-size:12px;">'+RedNaoEscapeHtml(this.client_form_options.InvalidInputMessage)+'</p>');
        this.GetRootContainer().find('.redNaoSubmitButton').addClass('btn-danger');
        this.ScrollTo(firstInvalidField.JQueryElement);
        return;
    }


    if(formValues.length>0)
        formValues=formValues.substr(1);

    try{

        for(i=0;i<this.JavascriptCodes.length;i++)
        {
            if(typeof this.JavascriptCodes[i].BeforeFormSubmit!='undefined'&&(this.JavascriptCodes[i].BeforeFormSubmit(formValues,this.FormElements)==false))
                return;
        }

    }catch(exception)
    {

    }

    this.RedirectUrl=this.ProcessRedirectUrl();
    if(RedNaoGetValueOrNull(this.client_form_options.Campaign))
        this.SendToSmartDonations(formValues,isUsingAFileUploader);
    else
        this.SendToSmartForms(formValues,isUsingAFileUploader);

    try{
        rnJQuery('body, input[type="submit"]').addClass('redNaoWait');
        this.JQueryForm.find('input[type="submit"],.redNaoMSButton').attr('disabled','disabled');
    }catch(exception)
    {

    }


};

smartFormGenerator.prototype.ScrollTo=function($elementToScrollTo)
{
    var scroll = $elementToScrollTo.offset();
    if (window.pageYOffset>scroll.top)
        rnJQuery('html, body').animate({scrollTop: scroll.top-50}, 200);
};

smartFormGenerator.prototype.SendToSmartForms=function(formValues,isUsingAFileUploader)
{
    var data={
        form_id:this.form_id,
        action:"rednao_smart_forms_save_form_values",
        formString:JSON.stringify(formValues),
        requestUrl:document.URL
    };


    if(this.client_form_options.UsesCaptcha=='y')
    {
        if(this.client_form_options.CaptchaVersion=='1')
            data.captcha={
                version:1,
                challenge:this.JQueryForm.find('[name="recaptcha_challenge_field"]').val(),
                response:this.JQueryForm.find('[name="recaptcha_response_field"]').val()
            };
        else{
            var captchaId="";
            for(var i=0;i<this.FormElements.length;i++)
                if(this.FormElements[i].Id=="captcha2")
                    captchaId=this.FormElements[i].captchaId;
            data.captcha={
                version:2,
                response:grecaptcha.getResponse(captchaId)
            }
        }


    }

    if(isUsingAFileUploader)
        this.SendFilesWithForm(data,formValues);
    else
    {
        var self=this;
        //noinspection JSUnusedLocalSymbols
        RedNaoEventManager.Publish('FormSubmitted',{Generator:this});
        rnJQuery.ajax({
            type:'POST',
            url:ajaxurl,
            dataType:"json",
            data:data,
            success:function(result){self.SaveCompleted(result,formValues)},
            error:function(result){
                rnJQuery('body, input[type="submit"]').removeClass('redNaoWait');
                self.JQueryForm.find('input[type="submit"],.redNaoMSButton').removeAttr('disabled');
                alert('An error occurred, please try again later');}
        });
    }
};

smartFormGenerator.prototype.SendFilesWithForm=function(data,formValues)
{
    data=JSON.stringify(data);
    rnJQuery('#sfTemporalIFrame').remove();
    rnJQuery('body').append('<iframe id="sfTemporalIFrame" name="sfTemporalIFrame"></iframe>');
    var self=this;
    RedNaoEventManager.Publish('FormSubmitted',{Generator:this});
    rnJQuery('#sfTemporalIFrame').on('load',function()
    {
        var response;
        if (this.contentDocument) {
            response = this.contentDocument.body.innerHTML;
        } else {
            response = this.contentWindow.document.body.innerHTML;
        }

        self.SaveCompleted(rnJQuery.parseJSON(response),formValues);
    });
    this.JQueryForm.attr('method','post');
    this.JQueryForm.attr('enctype','multipart/form-data');
    this.JQueryForm.attr('target','sfTemporalIFrame');
    this.JQueryForm.attr('action',smartFormsPath+"smart_forms_uploader.php");
    var dataField=rnJQuery('<input type="hidden" name="data"/> ');
    dataField.val(data);
    this.JQueryForm.append(dataField);
    this.SubmittingThroughIframe=true;
    this.JQueryForm.submit();

};

//noinspection JSUnusedLocalSymbols
smartFormGenerator.prototype.SendToSmartDonations=function(formValues,isUsingAFileUploader)
{
    this.JQueryForm.find('input[name="return"]').val(this.RedirectUrl);
    if(RedNaoPathExists(this.client_form_options,'Formulas.DonationFormula'))
    {
        //noinspection JSUnresolvedVariable
        var formula=new RedNaoFormula(null,this.client_form_options.Formulas.DonationFormula);
        var donationAmount=formula.GetValueFromFormula(RedNaoFormulaManagerVar.Data);


        if(donationAmount<=0)
        {
            this.GetRootContainer().prepend('<p class="redNaoValidationMessage" style="margin:0;padding: 0; font-style: italic; color:red;font-family:Arial,serif;font-size:12px;">*The donation amount should be greater than zero</p>');
            return;
        }

    }



    var self=this;


    var data={
        action:"rednao_smart_donations_save_form_values",
        emailToNotify:this.emailToNotify,
        formString:JSON.stringify(formValues)
    };

    RedNaoEventManager.Publish('FormSubmitted',{Generator:this});
    rnJQuery.post(ajaxurl,data,function(data){
        if(data.status=="success")
        {
            self.JQueryForm.find('.amountToDonate').val(donationAmount);
            self.JQueryForm.find('input[name=custom]').val(encodeURI('type=form&campaign_id='+self.client_form_options.Campaign+"&formId="+data.randomString+'&sformid='+self.form_id));
            if(self.JQueryForm.find('.redNaoRecurrence').length>0&&self.JQueryForm.find('.redNaoRecurrence').find(':selected').val()!='OT')
            {
                self.JQueryForm.find('.amountToDonate').attr('name','a3');
                self.JQueryForm.find('.smartDonationsPaypalCommand').val('_xclick-subscriptions');
                self.JQueryForm.append('<input type="hidden" class="redNaoRecurrenceField" name="src" value="1"><input type="hidden" class="redNaoRecurrenceField" name="p3" value="1"><input type="hidden" name="t3" value="'+self.JQueryForm.find('.redNaoRecurrence').find(':selected').val()+'">');
            }
            self.SubmittingRedNaoDonationForm='y';
            self.JQueryForm.submit();


        }else
        {
            alert("An error occured, please try again");
        }

        },"json");

};

smartFormGenerator.prototype.SaveCompleted=function(result,formValues){
    RedNaoEventManager.Publish('FormSubmittedCompleted',{Generator:this});
    rnJQuery('body, input[type="submit"]').removeClass('redNaoWait');
    this.JQueryForm.find('input[type="submit"],.redNaoMSButton').removeAttr('disabled');
    if(this.RedirectUrl.search('@@formid')>=0)
        this.RedirectUrl=this.RedirectUrl.replace('@@formid',result.insertedValues._formid);
    if(typeof result.AdditionalActions!='undefined')
    {
        for(var i=0;i<result.AdditionalActions.length;i++)
        {
            if(result.AdditionalActions[i].Action=="RedirectTo")
            {
                window.location=result.AdditionalActions[0].Value;
                return;
            }

            if(result.AdditionalActions[i].Action=="ShowMessage")
            {
                alert(result.AdditionalActions[i].Value.Message);
                return;
            }
        }
    }

    if(typeof result.refreshCaptcha!='undefined'&&result.refreshCaptcha=='y')
    {
        alert(result.message);
        Recaptcha.reload();
        return;
    }

    if(result.success=='y')
        this.FireExtensionMethod('FormSubmissionCompleted');

    if((RedNaoGetValueOrEmpty(this.client_form_options.alert_message_cb)!='y'&&RedNaoGetValueOrEmpty(this.client_form_options.redirect_to_cb)!='y')||result.success=='n')
    {
        alert(result.message);
        if(RedNaoGetValueOrEmpty(this.client_form_options.DontClearForm)!='y')
            this.CreateForm();
        return;
    }

    if(RedNaoGetValueOrEmpty(this.client_form_options.alert_message_cb)=='y')
        alert(this.client_form_options.alert_message);

    if(RedNaoGetValueOrEmpty(this.client_form_options.redirect_to_cb)=="y")
    {
        window.location=this.RedirectUrl;
    }
    if(RedNaoGetValueOrEmpty(this.client_form_options.DontClearForm)!='y')
        this.CreateForm();

};

smartFormGenerator.prototype.ProcessRedirectUrl=function()
{
    if(this.client_form_options.redirect_to_cb=='n')
        return '';
    var redirectOptions=this.client_form_options.redirect_to;
    var formValues=this.GetCurrentData();
    var i;
    var url='';
    var redirectToUse=null;
    for(i=0;i<redirectOptions.length;i++)
    {
        if(redirectOptions[i].RCSettings.Redirect=='always'||RedNaoEventManager.Publish('CalculateCondition',{Condition:redirectOptions[i].RCSettings.ConditionSettings ,Values:formValues}))
        {
            url=redirectOptions[i].URL;
        }
    }

        var regEx=/{([^}]+)}/g;
    var matches;

    while(matches=regEx.exec(url))
    {
        regEx.lastIndex=0;
        for(i=0;i<matches.length;i++)
        {
            if(matches[i][0]=='{')
                continue;
            var value='';
            if(typeof formValues[matches[i]]!='undefined')
            {
                value = formValues[matches[i]].label;
                for(var t=0;t<this.RedNaoFormElements.length;t++)
                    if(matches[i]==this.RedNaoFormElements[t].Id&&this.RedNaoFormElements[t].Options.ClassName=="rednaodatepicker")
                        value=formValues[matches[i]].value;
            }
            if(matches[i]=='_formid')
                url=url.replace('{'+matches[i]+'}','@@formid');
            else
                url=url.replace('{'+matches[i]+'}',encodeURIComponent(value));
        }
    }

    return url;
};

smartFormGenerator.prototype.GetRootContainer=function()
{
    return rnJQuery('#'+this.containerName);
};

rnJQuery(function(){
    if( window.smartFormsItemsToLoad)
        for(var i=0;i< window.smartFormsItemsToLoad.length;i++)
            smartFormsLoadForm(window.smartFormsItemsToLoad[i]);
});


var smartFormsLoadedItems=[];

function smartFormsLoadForm(options)
{
    var form=new smartFormGenerator(options);
    smartFormsLoadedItems.push(form);


}