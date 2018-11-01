"use strict";
var SmartFormsConditionalHandlerArray=[];
function SmartFormsGetConditionalHandlerByType(handlerId,options)
{
    var handlers=SmartFormsGetConditionalHandlerArray();
    for(var i=0;i<handlers.length;i++)
    {
        if(handlers[i].id==handlerId)
        {
            return handlers[i].create(options);
        }
    }
    throw ('Invalid handler');
}
function SmartFormsGetConditionalHandlerArray()
{
    SmartFormsConditionalHandlerArray=[
        {Label:"Show fields depending on a condition",id:"SfShowConditionalHandler",create:function(options){return new SfShowConditionalHandler(options)}},
        {Label:"Make fields invalid depending on a condition",id:"SfMkFieldInvalidHandler",create:function(options){return new SfMkFieldInvalidHandler(options)}}
    ];

    return SmartFormsConditionalHandlerArray;
}

function SmartFormsCalculateCondition(condition,values)
{
    condition=new Function('formData','return '+condition.CompiledCondition);
    return condition(values);
}
RedNaoEventManager.Subscribe('CalculateCondition',function(data){return SmartFormsCalculateCondition(data.Condition,data.Values);});


/************************************************************************************* Base ***************************************************************************************************/
function SfConditionalHandlerBase(options)
{
    this.PreviousActionWas=-1;
    if(options==null)
    {
        this.Options={};
        SfConditionalHandlerBase.prototype.ConditionId++;
        this.Options.Id=SfConditionalHandlerBase.prototype.ConditionId;
        this.IsNew=true;
    }else
        this.Options=options;

    this.Id=this.Options.Id;
}
SfConditionalHandlerBase.prototype.ConditionId=0;

SfConditionalHandlerBase.prototype.GetConditionalSteps=function()
{
    throw "method is abstract";
};

SfConditionalHandlerBase.prototype.GetOptionsToSave=function()
{
    this.Options.Label=this.Options.GeneralInfo.Name;
    return this.Options;

};

//noinspection JSUnusedLocalSymbols
SfConditionalHandlerBase.prototype.Initialize=function(form,data)
{
    throw "method is abstract";
};


SfConditionalHandlerBase.prototype.SubscribeCondition=function(condition,initialData)
{
    var self=this;
    //this.ConditionFunction=new Function('formData','return '+condition.CompiledCondition);
    var fieldsInCondition=[];
    for(var i=0;i<condition.Conditions.length;i++)
        fieldsInCondition.push(condition.Conditions[i].Field);

    RedNaoEventManager.Subscribe('ProcessConditionsAfterValueChanged',function(data){
        if(fieldsInCondition.indexOf(data.FieldName)>-1)
        {
            var action=self.ProcessCondition(data.Data);
            if(action!=null)
                data.Actions.push(action);
        }
    });



};

SfConditionalHandlerBase.prototype.ProcessCondition=function(data)
{
    var self=this;
    if(RedNaoEventManager.Publish('CalculateCondition',{Condition:this.Condition ,Values:data})) //this.ConditionFunction(data))
    {
        if(this.PreviousActionWasTrue!=1)
        {
            return {
                ActionType:'show',
                Execute:function(){self.PreviousActionWasTrue=1;self.ExecuteTrueAction()}
            };
        }
    }
    else
    if(this.PreviousActionWasTrue!=0)
    {
        return{
            ActionType:'hide',
            Execute:function(){self.PreviousActionWasTrue=0;self.ExecuteFalseAction();}
        }
    }

    return null;
};

//noinspection JSUnusedLocalSymbols
SfConditionalHandlerBase.prototype.ExecuteTrueAction=function(form)
{
    throw "method is abstract";
};

//noinspection JSUnusedLocalSymbols
SfConditionalHandlerBase.prototype.ExecuteFalseAction=function(form)
{
    throw "method is abstract";
};
/************************************************************************************* Show Conditional Handler ***************************************************************************************************/
function SfShowConditionalHandler(options)
{
    SfConditionalHandlerBase.call(this,options);
    this.Options.Type="SfShowConditionalHandler";
    this.Fields="";
    this.FormElements=null;
}
SfShowConditionalHandler.prototype=Object.create(SfConditionalHandlerBase.prototype);

SfShowConditionalHandler.prototype.GetConditionalSteps=function()
{
    if(this.IsNew){
        this.Options.GeneralInfo={};
        this.Options.FieldPicker={};
        this.Options.Condition={};
    }
    return [
        {Type:"SfNamePicker",Label:'HowDoYouWantToName',Options:this.Options.GeneralInfo,Id:this.Id},
        {Type:"SfHandlerFieldPicker",Label:'typeOrSelectFieldsToBeShown',Options:this.Options.FieldPicker},
        {Type:"SfHandlerConditionGenerator",Label:'WhenDoYouWantToDisplay',Options:this.Options.Condition}
    ];
};

SfShowConditionalHandler.prototype.Initialize=function(form,data)
{
    this.Form=form;
    var self=this;
    self.PreviousActionWasTrue=-1;
    this.Condition=self.Options.Condition;
    self.SubscribeCondition(self.Options.Condition,data);
    self.ProcessCondition(data).Execute();
};

SfShowConditionalHandler.prototype.HideFields=function()
{
    this.Form.JQueryForm.find(this.GetFieldIds()).css('display','none');
    var formElements=this.GetFormElements();
    for(var i=0;i<formElements.length;i++)
        formElements[i].Ignore();
};

SfShowConditionalHandler.prototype.GetFieldIds=function()
{
    if(this.Fields=="")
        for(var i=0;i<this.Options.FieldPicker.AffectedItems.length;i++)
        {
            if(i>0)
                this.Fields+=",";
            this.Fields+='#'+this.Options.FieldPicker.AffectedItems[i];

        }
    return this.Fields;
};

SfShowConditionalHandler.prototype.GetFormElements=function()
{
    if(this.FormElements==null)
    {
        this.FormElements=[];
        for(var i=0;i<this.Options.FieldPicker.AffectedItems.length;i++)
        {
            var fieldId=this.Options.FieldPicker.AffectedItems[i];
            for(var t=0;t<this.Form.FormElements.length;t++)
                if(this.Form.FormElements[t].Id==fieldId)
                    this.FormElements.push(this.Form.FormElements[t]);


        }
    }
    return this.FormElements;
};


SfShowConditionalHandler.prototype.ExecuteTrueAction=function()
{
    this.Form.JQueryForm.find(this.GetFieldIds()).slideDown();
    var formElements=this.GetFormElements();
    for(var i=0;i<formElements.length;i++)
        formElements[i].UnIgnore();
};

SfShowConditionalHandler.prototype.ExecuteFalseAction=function()
{
    this.Form.JQueryForm.find(this.GetFieldIds()).slideUp();
    var formElements=this.GetFormElements();
    for(var i=0;i<formElements.length;i++)
        formElements[i].Ignore();
};


/************************************************************************************* Make field invalid Conditional Handler ***************************************************************************************************/
function SfMkFieldInvalidHandler(options)
{
    SfConditionalHandlerBase.call(this,options);
    this.Options.Type="SfMkFieldInvalidHandler";
    this.Fields="";
    this.FormElements=null;
}
SfMkFieldInvalidHandler.prototype=Object.create(SfConditionalHandlerBase.prototype);

SfMkFieldInvalidHandler.prototype.GetConditionalSteps=function()
{
    if(this.IsNew){
        this.Options.GeneralInfo={};
        this.Options.FieldPicker={};
        this.Options.Condition={};
        this.Options.ErrorMessage={};
    }
    return [
        {Type:"SfNamePicker",Label:'HowDoYouWantToName',Options:this.Options.GeneralInfo,Id:this.Id},
        {Type:"SfHandlerFieldPicker",Label:'whichFieldYouWantToMakeInvalid',Options:this.Options.FieldPicker},
        {Type:"SfHandlerConditionGenerator",Label:'WhenDoYouWantToMakeInvalid',Options:this.Options.Condition},
        {Type:"SfTextPicker",Label:'WhatMessageWhenInvalid',Options:this.Options.ErrorMessage}
    ];
};

SfMkFieldInvalidHandler.prototype.Initialize=function(form,data)
{
    this.Form=form;
    var self=this;
    this.Condition=self.Options.Condition;
    self.PreviousActionWasTrue=-1;
    RedNaoEventManager.Subscribe('BeforeValidatingForm',function(){
       self.ProcessCondition(self.Form.GetCurrentData()).Execute();
    });

};

SfMkFieldInvalidHandler.prototype.GetFormElements=function()
{
    if(this.FormElements==null)
    {
        this.FormElements=[];
        for(var i=0;i<this.Options.FieldPicker.AffectedItems.length;i++)
        {
            var fieldId=this.Options.FieldPicker.AffectedItems[i];
            for(var t=0;t<this.Form.FormElements.length;t++)
                if(this.Form.FormElements[t].Id==fieldId)
                    this.FormElements.push(this.Form.FormElements[t]);


        }
    }
    return this.FormElements;
};

SfMkFieldInvalidHandler.prototype.ExecuteTrueAction=function()
{
    var formElements=this.GetFormElements();
    var errorId="mfi"+this.Id;
    for(var i=0;i<formElements.length;i++)
        formElements[i].AddError(errorId,this.Options.ErrorMessage.Text);
};

SfMkFieldInvalidHandler.prototype.ExecuteFalseAction=function()
{
    var formElements=this.GetFormElements();
    var errorId="mfi"+this.Id;
    for(var i=0;i<formElements.length;i++)
        formElements[i].RemoveError(errorId);
};

