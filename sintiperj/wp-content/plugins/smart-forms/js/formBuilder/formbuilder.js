"use strict";
//used in another javascript
//noinspection JSUnusedGlobalSymbols
var smartDonationsIsDesignMode=true;

//function overwritten by one that use field name
//noinspection JSUnusedLocalSymbols
var SmartFormsFieldIsAvailable=function(fieldName){return true;};
function RedNaoFormBuilder(smartFormsOptions,formElementsOptions,formClientOptions) {

    this.formSettings = rnJQuery('#formSettings');
    this.redNaoWindow = rnJQuery(document);
    this.formSettingsOriginalTop = this.formSettings.offset().top - 35;
    this.RedNaoFormElements = [];
    this.scrollTimeOut = null;
    this.propertiesPanel = rnJQuery("#rednaoPropertiesPanel");
    this.extensions=[];
    this.FormBuilderDisabled=false;
    this.Conditions=[];
    this.MultipleStepsDesigner=null;
    this.FormType='nor';
    if(RedNaoSmartFormLicenseIsValid)
        rnJQuery("#tabpro").empty();
    RedNaoEventManager.Publish('AddNewRegisterElementExtensions');
    if(typeof formClientOptions.Conditions!='undefined')
    {
        this.Conditions=formClientOptions.Conditions;
        for(var i=0;i<this.Conditions.length;i++)
        {
            SfConditionalHandlerBase.prototype.ConditionId=Math.max(this.Conditions[i].Id);
        }
    }

    this.SfConditionalLogicManager=new SfConditionalLogicManager(this);
    RedNaoEventManager.Publish('AddExtendedElements',this.extensions);



    var self = this;
    rnJQuery(window).scroll(function () {
        if (self.scrollTimeOut != null)
            clearTimeout(self.scrollTimeOut);

        self.scrollTimeOut = setTimeout(function () {
            self.ScrollSettings();
        }, 150);

    });
    rnJQuery('#formRadio3').click(function () {
        self.SfConditionalLogicManager.FillSavedConditionList();
    });
    rnJQuery('#rnFormType').change(function(){self.FormTypeChanged()});
    this.DragManager = new RedNaoDragManager(this);
    var fieldServerOptions={};
    if(smartFormsOptions!=null&& typeof smartFormsOptions.FieldServerOptions!='undefined')
        fieldServerOptions=smartFormsOptions.FieldServerOptions;
    this.RecreateExistingFormIfAny(formElementsOptions,fieldServerOptions);

    this.InitializeTabs();
    this.InitializeComponents();

    this.InitializeSplitFormIfNeeded(formClientOptions);
    if(smartFormsOptions!=null)
        sfFormElementBase.IdCounter=smartFormsOptions.LatestId;
    else
        sfFormElementBase.IdCounter=0;

}


RedNaoFormBuilder.prototype.FormTypeChanged=function()
{
    if(rnJQuery('#rnFormType').val()=='sec'&&!RedNaoLicensingManagerVar.LicenseIsValid("Sorry, this version doesn't support multi steps forms"))
    {
        rnJQuery('#rnFormType').val('nor');
        return;
    }

    if(rnJQuery('#rnFormType').val()=='sec')
    {
        rnJQuery('.msfText').css('display','inline');
    }else
        rnJQuery('.msfText').css('display','none');


    this.FormType=rnJQuery('#rnFormType').val();
    this.CreateFormPreview();
};

RedNaoFormBuilder.prototype.InitializeSplitFormIfNeeded=function(clientOptions)
{
    if(clientOptions!=null&&typeof clientOptions.FormType!='undefined'&&clientOptions.FormType=='sec')
    {
        this.InitializeStepDesigner(clientOptions);
        rnJQuery('#rnFormType').val('sec').change();
    }
};

RedNaoFormBuilder.prototype.InitializeStepDesigner=function(options)
{
    if(options!=null)
        this.MultipleStepsDesigner=new SfMultipleStepsDesigner(options.SplitSteps,rnJQuery("#redNaoElementlist"),this.RedNaoFormElements);
    else
        this.MultipleStepsDesigner=new SfMultipleStepsDesigner(null,rnJQuery("#redNaoElementlist"),this.RedNaoFormElements);

    var self=this;
    this.MultipleStepsDesigner.GenerationCompletedCallBack=function(){
        rnJQuery("#redNaoElementlist").find('.step-pane').append('<div class="formelement last" style="height:77px;width:100%"><p>Drop new fields here</p></div>');
        self.DragManager.MakeAlreadySelectedElementsDraggable();
    };
};

//used in drag manager
//noinspection JSUnusedGlobalSymbols
RedNaoFormBuilder.prototype.SmartDonationsPrepareDraggableItems = function () {
    rnJQuery(".rednaoformbuilder .component,#redNaoElementlist .rednao-control-group").unbind('mousedown');
    //noinspection JSUnresolvedVariable
    rnJQuery(".rednaoformbuilder .component,#redNaoElementlist .rednao-control-group").mousedown(SmartDonationsFormMouseDownFired);

    rnJQuery(".redNaoDonationButton").unbind('click');
    rnJQuery(".redNaoDonationButton").click(function () {
        return false;
    })
};

RedNaoFormBuilder.prototype.AddFieldInPosition=function(formElement,target)
{

    if(this.FormType=='sec')
    {
        this.MultipleStepsDesigner.AddFormElement(formElement,target);
    }else
        this.RedNaoFormElements.splice(target.index(), 0, formElement);
};

RedNaoFormBuilder.prototype.MoveFieldInPosition=function(formElement,target)
{

    if(this.FormType=='sec')
    {
        this.MultipleStepsDesigner.MoveFormElement(formElement,target);
    }else
        this.RedNaoFormElements.splice(target.index(), 0, formElement);
};



RedNaoFormBuilder.prototype.GetFormElementByContainer=function(container)
{
    return this.RedNaoFormElements[this.GetFormElementIndexByContainer(container)];
};


RedNaoFormBuilder.prototype.GetFormElementIndexByContainer=function(container)
{
    var fieldId=container.attr('id');
    for(var i=0;i<this.RedNaoFormElements.length;i++)
    {
        if(this.RedNaoFormElements[i].Id==fieldId)
        {
            return i;
        }
    }

    throw 'Field not found';
};


RedNaoFormBuilder.prototype.RecreateExistingFormIfAny=function(elementOptions,fieldServerOptions)
{

    for(var i=0;i<elementOptions.length;i++)
    {
        var fieldId=elementOptions[i].Id;
        var serverOptions={};
        if(typeof fieldServerOptions[fieldId]!='undefined')
            serverOptions=fieldServerOptions[fieldId];
        var element=sfRedNaoCreateFormElementByName(elementOptions[i].ClassName,elementOptions[i],serverOptions);
        this.RedNaoFormElements.push(element);
    }

    this.CreateFormPreview();
};


RedNaoFormBuilder.prototype.CreateFormPreview=function()
{
    var form=rnJQuery("#redNaoElementlist");
    form.empty();

    if(this.FormType=='nor') {
        for (var i = 0; i < this.RedNaoFormElements.length; i++) {
            this.RedNaoFormElements[i].AppendElementToContainer(form);
        }
        form.append('<div class="formelement last" style="clear:both;height:77px;width:100%"><p>Drop new fields here</p></div>');
        this.DragManager.MakeAlreadySelectedElementsDraggable();
    }
    else
    {
        if(this.MultipleStepsDesigner==null)
        {
            this.InitializeStepDesigner(null);
        }
        this.MultipleStepsDesigner.Generate();


    }

};

/************************************************************************************* Tabs ***************************************************************************************************/





RedNaoFormBuilder.prototype.OpenProperties = function (element) {
    rnJQuery('#formRadio2').click();
    this.FillPropertiesPanel(this.GetFormElementByContainer(element));
};

RedNaoFormBuilder.prototype.FillPropertiesPanel = function (element) {


    var tableProperties = rnJQuery('#smartFormPropertiesTable');
    tableProperties.empty();


    this.propertiesPanel.find('.popover-title').text(element.Title);
    element.GeneratePropertiesHtml(tableProperties);
};


/************************************************************************************* General Methods ***************************************************************************************************/
RedNaoFormBuilder.prototype.CreateNewInstanceOfElement = function (element) {
    var componentType = this.GetComponentType(element);
    return sfRedNaoCreateFormElementByName(componentType);
};

RedNaoFormBuilder.prototype.GetComponentType = function (element) {
    if (rnJQuery(element).children().first().hasClass('rednaotextinput'))
        return 'rednaotextinput';
    if (rnJQuery(element).children().first().hasClass('rednaopasswordinput'))
        return 'rednaopasswordinput';
    if (rnJQuery(element).children().first().hasClass('rednaosearchinput'))
        return 'rednaosearchinput';
    if (rnJQuery(element).children().first().hasClass('rednaoprependedtext'))
        return 'rednaoprependedtext';
    if (rnJQuery(element).children().first().hasClass('rednaoappendedtext'))
        return 'rednaoappendedtext';
    if (rnJQuery(element).children().first().hasClass('rednaoprependedcheckbox'))
        return 'rednaoprependedcheckbox';
    if (rnJQuery(element).children().first().hasClass('rednaoappendedcheckbox'))
        return 'rednaoappendedcheckbox';
    if (rnJQuery(element).children().first().hasClass('rednaobuttondropdown'))
        return 'rednaobuttondropdown';
    if (rnJQuery(element).children().first().hasClass('tabradioscheckboxes'))
        return 'tabradioscheckboxes';
    if (rnJQuery(element).children().first().hasClass('rednaomultiplecheckboxes'))
        return 'rednaomultiplecheckboxes';
    if (rnJQuery(element).children().first().hasClass('rednaoselectbasic'))
        return 'rednaoselectbasic';
    if (rnJQuery(element).children().first().hasClass('rednaofilebutton'))
        return 'rednaofilebutton';
    if (rnJQuery(element).children().first().hasClass('rednaosinglebutton'))
        return 'rednaosinglebutton';
    if (rnJQuery(element).children().first().hasClass('rednaodoublebutton'))
        return 'rednaodoublebutton';
    if (rnJQuery(element).children().first().hasClass('rednaotitle'))
        return 'rednaotitle';
    if (rnJQuery(element).children().first().hasClass('rednaotextarea'))
        return 'rednaotextarea';
    if (rnJQuery(element).children().first().hasClass('rednaomultipleradios'))
        return 'rednaomultipleradios';
    if (rnJQuery(element).children().first().hasClass('rednaodonationbutton'))
        return 'rednaodonationbutton';
    if (rnJQuery(element).children().first().hasClass('rednaodonationrecurrence'))
        return 'rednaodonationrecurrence';
    if (rnJQuery(element).children().first().hasClass('rednaosubmissionbutton'))
        return 'rednaosubmissionbutton';
    if (rnJQuery(element).children().first().hasClass('rednaodatepicker'))
        return 'rednaodatepicker';
    if (rnJQuery(element).children().first().hasClass('rednaoname'))
        return 'rednaoname';
    if (rnJQuery(element).children().first().hasClass('rednaoaddress'))
        return 'rednaoaddress';
    if (rnJQuery(element).children().first().hasClass('rednaophone'))
        return 'rednaophone';
    if (rnJQuery(element).children().first().hasClass('rednaoemail'))
        return 'rednaoemail';
    if (rnJQuery(element).children().first().hasClass('rednaonumber'))
        return 'rednaonumber';
    if (rnJQuery(element).children().first().hasClass('rednaocaptcha'))
        return 'rednaocaptcha';
    if (rnJQuery(element).children().first().hasClass('rednaohtml'))
        return 'rednaohtml';
    if (rnJQuery(element).children().first().hasClass('rednaosearchablelist'))
        return 'rednaosearchablelist';


    for(var i=0;i<this.extensions.length;i++)
        if(rnJQuery(element).children().first().hasClass(this.extensions[i]))
            return this.extensions[i];

    throw "Invalid element type";
};


/************************************************************************************* Initialization ***************************************************************************************************/




RedNaoFormBuilder.prototype.InitializeTabs = function () {
    rnJQuery(".rednaoformbuilder .formtab").click(function () {

        var thisJQuery = rnJQuery(this);
        var tabName = thisJQuery.attr("id");
        tabName = tabName.substr(1);

        rnJQuery('#navtab').find(".selectedTab").removeClass("selectedTab");
        thisJQuery.addClass("selectedTab");

        rnJQuery(".rednaoformbuilder .rednaotablist").css("display", "none");
        rnJQuery(".rednaoformbuilder #" + tabName).css("display", "block");

    });
};

RedNaoFormBuilder.prototype.InitializeComponents = function () {
    sfRedNaoCreateFormElementByName('rednaotitle', null).GenerateHtml(rnJQuery("#components .rednaotitle"));
    sfRedNaoCreateFormElementByName('rednaotextinput', null).GenerateHtml(rnJQuery("#components .rednaotextinput"));
    sfRedNaoCreateFormElementByName('rednaoprependedtext', null).GenerateHtml(rnJQuery("#components .rednaoprependedtext"));
    sfRedNaoCreateFormElementByName('rednaoappendedtext', null).GenerateHtml(rnJQuery("#components .rednaoappendedtext"));
    sfRedNaoCreateFormElementByName('rednaoprependedcheckbox', null).GenerateHtml(rnJQuery("#components .rednaoprependedcheckbox"));
    sfRedNaoCreateFormElementByName('rednaoappendedcheckbox', null).GenerateHtml(rnJQuery("#components .rednaoappendedcheckbox"));
    sfRedNaoCreateFormElementByName('rednaotextarea', null).GenerateHtml(rnJQuery("#components .rednaotextarea"));
    sfRedNaoCreateFormElementByName('rednaomultipleradios', null).GenerateHtml(rnJQuery("#components .rednaomultipleradios"));
    sfRedNaoCreateFormElementByName('rednaomultiplecheckboxes', null).GenerateHtml(rnJQuery("#components .rednaomultiplecheckboxes"));
    sfRedNaoCreateFormElementByName('rednaoselectbasic', null).GenerateHtml(rnJQuery("#components .rednaoselectbasic"));
    sfRedNaoCreateFormElementByName('rednaodonationbutton', null).GenerateHtml(rnJQuery("#components .rednaodonationbutton"));
    sfRedNaoCreateFormElementByName('rednaodonationrecurrence', null).GenerateHtml(rnJQuery("#components .rednaodonationrecurrence"));
    sfRedNaoCreateFormElementByName('rednaosubmissionbutton', null).GenerateHtml(rnJQuery("#components .rednaosubmissionbutton"));
    sfRedNaoCreateFormElementByName('rednaodatepicker', null).GenerateHtml(rnJQuery("#components .rednaodatepicker"));
    sfRedNaoCreateFormElementByName('rednaoname', null).GenerateHtml(rnJQuery("#components .rednaoname"));
    sfRedNaoCreateFormElementByName('rednaoaddress', null).GenerateHtml(rnJQuery("#components .rednaoaddress"));
    sfRedNaoCreateFormElementByName('rednaophone', null).GenerateHtml(rnJQuery("#components .rednaophone"));
    sfRedNaoCreateFormElementByName('rednaoemail', null).GenerateHtml(rnJQuery("#components .rednaoemail"));
    sfRedNaoCreateFormElementByName('rednaonumber', null).GenerateHtml(rnJQuery("#components .rednaonumber"));
    sfRedNaoCreateFormElementByName('rednaohtml', null).GenerateHtml(rnJQuery("#components .rednaohtml"));
    sfRedNaoCreateFormElementByName('rednaosearchablelist', null).GenerateHtml(rnJQuery("#components .rednaosearchablelist"));

    for(var i=0;i<this.extensions.length;i++)
        sfRedNaoCreateFormElementByName(this.extensions[i], null).GenerateHtml(rnJQuery("#components ."+this.extensions[i]));

    var self=this;
    SmartFormsFieldIsAvailable=function(fieldName)
    {
        for(var i=0;i<self.RedNaoFormElements.length;i++)
            if(self.RedNaoFormElements[i].Id==fieldName)
                return false;

        return true;
    }


};


/************************************************************************************* Move Windows On Scroll ***************************************************************************************************/

RedNaoFormBuilder.prototype.GetFormInformation=function()
{
    var arrayOfOptions=[];

    for(var i=0;i<this.RedNaoFormElements.length;i++)
    {
        arrayOfOptions.push(this.RedNaoFormElements[i].Options);
    }
   return arrayOfOptions;
};

RedNaoFormBuilder.prototype.Disable=function()
{
    this.FormBuilderDisabled=true;
};

RedNaoFormBuilder.prototype.Enable=function()
{
    this.FormBuilderDisabled=false;
};

RedNaoFormBuilder.prototype.ScrollSettings = function () {
    var documentScroll = this.redNaoWindow.scrollTop();
    var newPosition = Math.max(0, documentScroll - this.formSettingsOriginalTop);

    var previousPosition=parseFloat(this.formSettings.css('top'));
    if(isNaN(previousPosition))
        previousPosition=0;


    if(newPosition>previousPosition&&(this.formSettings.height())>rnJQuery(window).height())
        return;


    this.formSettings.animate({
        top:newPosition
    }, 500);
};


RedNaoFormBuilder.prototype.CloneFormElement=function(jQueryElement){
    if(this.RedNaoFormElements.length>=7&&!RedNaoLicensingManagerVar.LicenseIsValid('Sorry, this version only support up to 8 fields'))
    {
        return;
    }
    var formObject=this.GetFormElementByContainer(jQueryElement);
    var newElement= formObject.Clone();

   // this.RedNaoFormElements.splice(this.GetFormElementIndexByContainer(jQueryElement)+1,0,newElement);

    var container=rnJQuery("<div></div>");
    container.insertAfter(jQueryElement);

    if(this.FormType=='sec')
    {
        this.MultipleStepsDesigner.AddFormElement(newElement,container);
    }else
        this.RedNaoFormElements.splice(container.index(), 0, newElement);


    container=newElement.GenerateHtml(container);


    this.DragManager.MakeItemDraggable(container);
    this.ElementClicked(container);
    this.OpenProperties(container);

};

RedNaoFormBuilder.prototype.ElementClicked=function(jQueryElement)
{
    rnJQuery('#redNaoElementlist').find('.SmartFormsElementSelected').removeClass('SmartFormsElementSelected');
    rnJQuery('.smartFormsActionMenu').remove();

    jQueryElement.addClass('SmartFormsElementSelected');
    this.OpenProperties(jQueryElement);

    //noinspection JSUnresolvedVariable variable loaded in another file
    var actionElement=rnJQuery('<div class="smartFormsActionMenu" ><img id="editStyleElement" src="'+smartFormsRootPath+'images/edit_style.png" title="Edit Style" /><img id="cloneFormElement" src="'+smartFormsRootPath+'images/clone.png" title="Clone" /><img id="deleteFormElement" src="'+smartFormsRootPath+'images/delete.png" title="Delete"/></div>');
    var self=this;


    jQueryElement.prepend(actionElement);

    actionElement.find('#cloneFormElement').mousedown(function(e){e.preventDefault();e.stopPropagation(); self.CloneFormElement(jQueryElement);});
    actionElement.find('#deleteFormElement').mousedown(function(e){e.preventDefault();e.stopPropagation(); self.DeleteFormElement(jQueryElement);});
    actionElement.find('#editStyleElement').mousedown(function(e){e.preventDefault();e.stopPropagation();self.EditStyle(jQueryElement)});
};

RedNaoFormBuilder.prototype.EditStyle=function(jQueryElement){
    var formElement=this.GetFormElementByContainer(jQueryElement);
    RedNaoStyleEditorVar.OpenStyleEditor(formElement,jQueryElement);
};

RedNaoFormBuilder.prototype.DeleteFormElement=function(jQueryElement){
    var index=this.GetFormElementIndexByContainer(jQueryElement);
    this.RedNaoFormElements.splice(index,1);
    jQueryElement.remove();
};

RedNaoFormBuilder.prototype.GetMultipleStepsOptions=function()
{
    if(this.FormType!='nor')
        return this.MultipleStepsDesigner.Options;
    else
        return {};
};


