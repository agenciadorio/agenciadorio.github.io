"use strict";
function SfGetConditionalStep(formBuilder,stepConfiguration)
{
    if(stepConfiguration.Type=="SfHandlerFieldPicker")
        return new SfHandlerFieldPicker(smartFormsTranslation,formBuilder,stepConfiguration);
    if(stepConfiguration.Type=="SfHandlerConditionGenerator")
        return new SfHandlerConditionGenerator(smartFormsTranslation,formBuilder,stepConfiguration);
    if(stepConfiguration.Type=="SfNamePicker")
        return new SfNamePicker(smartFormsTranslation,formBuilder,stepConfiguration);
    if(stepConfiguration.Type=="SfTextPicker")
        return new SfTextPicker(smartFormsTranslation,formBuilder,stepConfiguration);
    throw 'invalid conditional step';
}

/************************************************************************************* Conditional Designer Base ***************************************************************************************************/
function SfConditionalStepBase(translations,formBuilder,stepConfiguration)
{
    this.FormBuilder=formBuilder;
    this.Translations=translations;
    this.StepConfiguration=stepConfiguration;
    this.Width=550;
}



//noinspection JSUnusedLocalSymbols
SfConditionalStepBase.prototype.InitializeScreen=function(container)
{
    throw 'Method is abstract';
};


SfConditionalStepBase.prototype.Exit=function()
{
    throw 'Method is abstract';
};


SfConditionalStepBase.prototype.Commit=function()
{
    throw 'Method is abstract';
};


/*************************************************************************************SfNamePicker***************************************************************************************************/
function SfNamePicker(translations,formBuilder,stepConfiguration)
{
    SfConditionalStepBase.call(this,translations,formBuilder,stepConfiguration);
}
SfNamePicker.prototype=Object.create(SfConditionalStepBase.prototype);

SfNamePicker.prototype.InitializeScreen=function(container)
{
    container.css('text-align','left');
    container.css('padding-left','5px');
    container.css('padding-right','5px');

    container.append('<h2 style="text-align: left">'+this.Translations[this.StepConfiguration.Label]+'</h2>');

    var name=this.Translations["MyNewCondition"]+" "+this.StepConfiguration.Id;
    if(!this.StepConfiguration.IsNew)
    {
        name=this.StepConfiguration.Options.Name;
    }
    this.Title=rnJQuery('<input type="text" style="width: 100%;height: 40px;font-size: 20px;padding: 10px;">');
    this.Title.val(name);
    container.append(this.Title);

};

SfNamePicker.prototype.Exit=function()
{

};

SfNamePicker.prototype.Commit=function()
{
    if(this.Title.val().trim()=="")
    {
        alert(this.Translations["TheTitleCantBeEmpty"]);
        return false;
    }

    this.StepConfiguration.Options.Name=this.Title.val();
    return true;
};

/*************************************************************************************SfTextPicker***************************************************************************************************/
function SfTextPicker(translations,formBuilder,stepConfiguration)
{
    SfConditionalStepBase.call(this,translations,formBuilder,stepConfiguration);

}
SfTextPicker.prototype=Object.create(SfConditionalStepBase.prototype);

SfTextPicker.prototype.InitializeScreen=function(container)
{
    container.css('text-align','left');
    container.css('padding-left','5px');
    container.css('padding-right','5px');

    container.append('<h2 style="text-align: left">'+this.Translations[this.StepConfiguration.Label]+'</h2>');

    var name='Invalid value';
    this.Title=rnJQuery('<input type="text" style="width: 100%;height: 40px;font-size: 20px;padding: 10px;">');
    this.Title.val(name);
    container.append(this.Title);

};

SfTextPicker.prototype.Exit=function()
{

};

SfTextPicker.prototype.Commit=function()
{

    this.StepConfiguration.Options.Text=this.Title.val();
    return true;
};


/*************************************************************************************Field Picker***************************************************************************************************/
function SfHandlerFieldPicker(translations,formBuilder,stepConfiguration)
{
    SfConditionalStepBase.call(this,translations,formBuilder,stepConfiguration);
}
SfHandlerFieldPicker.prototype=Object.create(SfConditionalStepBase.prototype);

SfHandlerFieldPicker.prototype.InitializeScreen=function(container)
{
    this.FormBuilder.Disable();
    container.css('text-align','left');
    container.css('padding-left','5px');
    container.css('padding-right','5px');
    var jQueryDocument=rnJQuery(document);
    var self=this;
    rnJQuery('#redNaoElementlist').on("click.FieldPicker",'.rednao-control-group',function(){self.FormElementClicked(rnJQuery(this));});
    rnJQuery('body').append('<div class="smartFormsSlider smartFormsFieldPickerOverlay"><div class="ui-widget-overlay" style="z-index: 1001;width:'+jQueryDocument.width()+'px;height:'+jQueryDocument.height()+'" ></div></div>');
    rnJQuery('.rednaoformbuilder').addClass('smartFormsFieldPick');
    var pickerInterface=rnJQuery('<div class="fieldPickContainer" style="margin:10px;"></div>');

    var options="";
    var selectedFields=[];
    if(!this.StepConfiguration.IsNew)
        selectedFields=this.StepConfiguration.Options.AffectedItems;


    for(var i=0;i<this.FormBuilder.RedNaoFormElements.length;i++)
    {
            options+='<option '+(selectedFields.indexOf(this.FormBuilder.RedNaoFormElements[i].Options.Id)>=0?'selected="selected"':'')+'  value="'+this.FormBuilder.RedNaoFormElements[i].Options.Id+'">'+this.FormBuilder.RedNaoFormElements[i].GetFriendlyName()+'</option>';
    }
    this.Select=rnJQuery('<select size="margin-left:10px;" multiple="multiple" id="redNaoFieldPicked" style="width:100%">'+options+'</select>');
    pickerInterface.append(this.Select);
    this.Select.select2({
        allowClear: true
    }).on("change", function() {
        self.SelectChanged();
    });
    container.append('<h2 style="text-align: left">'+this.Translations[this.StepConfiguration.Label]+'</h2>');
    container.append(pickerInterface);



};

SfHandlerFieldPicker.prototype.Exit=function()
{
    this.FormBuilder.Enable();
    rnJQuery('#redNaoElementlist').off("click.FieldPicker");
    rnJQuery('.fieldPickerSelected').removeClass('fieldPickerSelected');
    rnJQuery('.rednaoformbuilder').removeClass('smartFormsFieldPick');
    rnJQuery('.smartFormsFieldPickerOverlay').remove();
};

SfHandlerFieldPicker.prototype.Commit=function()
{
    var selectedValues=this.Select.select2('val');
    if(selectedValues.length==0)
    {
        alert(this.Translations["SelectAtLeastOneField"]);
        return false;
    }
    this.StepConfiguration.Options.AffectedItems=selectedValues;
    return true;
};

SfHandlerFieldPicker.prototype.FormElementClicked=function(elementClickedJQuery)
{
    var fieldId=this.FormBuilder.GetFormElementByContainer(elementClickedJQuery).Id;
    var selectedFields=this.Select.select2('val');
    if(rnJQuery.inArray(fieldId,selectedFields)>=0)
        return;
    selectedFields.push(fieldId);
    this.Select.select2('val',selectedFields).change();
};

SfHandlerFieldPicker.prototype.SelectChanged=function()
{
    var selectedFields=this.Select.select2('val');
    rnJQuery('.fieldPickerSelected').removeClass('fieldPickerSelected');
    for(var i=0;i<selectedFields.length;i++)
    {
        rnJQuery('#'+selectedFields[i]).addClass('fieldPickerSelected');
    }
};


/*************************************************************************************Condition Generator ***************************************************************************************************/
function SfHandlerConditionGenerator(translations,formBuilder,stepConfiguration)
{
    SfConditionalStepBase.call(this,translations,formBuilder,stepConfiguration);

    this.StepConfiguration.Options.IsNew=this.StepConfiguration.IsNew;
    this.ConditionDesigner=new SFConditionDesigner(this.FormBuilder.RedNaoFormElements, this.StepConfiguration.Options);
    this.Width=700;
}
SfHandlerConditionGenerator.prototype=Object.create(SfConditionalStepBase.prototype);

SfHandlerConditionGenerator.prototype.InitializeScreen=function(container)
{
    container.css('padding-left','5px');
    container.css('padding-right','5px');

    container.append('<h2 style="text-align: left">'+this.Translations[this.StepConfiguration.Label]+'</h2>');
    container.append(this.ConditionDesigner.GetDesigner());
};





SfHandlerConditionGenerator.prototype.Exit=function()
{

};

SfHandlerConditionGenerator.prototype.Commit=function()
{
    if(this.ConditionDesigner.IsValid())
    {
        var data=this.ConditionDesigner.GetData();
        this.StepConfiguration.Options.Conditions=data.Conditions;
        this.StepConfiguration.Options.CompiledCondition=data.CompiledCondition;
        return true;
    }
    return false;
};




