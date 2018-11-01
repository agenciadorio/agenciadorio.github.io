function RedNaoFormulaManager()
{
    this.Formulas=[];
    this.Data={};

    var self=this;
    RedNaoEventManager.Subscribe('formPropertyChanged',function(data){self.PropertyChanged(data)});
    RedNaoEventManager.Subscribe('CalculateFormula',function(data){return self.CalculateFormula(data.Formula,data.Values);})
}


RedNaoFormulaManager.prototype.CalculateFormula=function (formula,values)
{
    formula=new Function('formData','return '+formula.CompiledFormula);
    return formula(values);
};

RedNaoFormulaManager.prototype.PropertyChanged=function(data)
{
    this.SetFormulaValue(data.FieldName,data.Value);
    this.UpdateFormulaFieldsIfNeeded(data.FieldName,data.Value);
    RedNaoEventManager.Publish('FormValueChanged',{FieldName:data.FieldName,Data:this.Data});

    var actionData={FieldName:data.FieldName,Data:this.Data,Actions:[]};
    RedNaoEventManager.Publish('ProcessConditionsAfterValueChanged',actionData);

    actionData=actionData.Actions;
    var i;
    for(i=0;i<actionData.length;i++)
    {
        if(actionData[i].ActionType=='hide')
            actionData[i].Execute();
    }

    for(i=0;i<actionData.length;i++)
    {
        if(actionData[i].ActionType=='show')
            actionData[i].Execute();
    }

};


RedNaoFormulaManager.prototype.SetFormulaValue=function(fieldName,data)
{
    var fieldData={};
    fieldData.OriginalValues=data;
    if(typeof data.value!='undefined')
        fieldData.value=data.value;

    if(typeof data.selectedValues!='undefined')
        fieldData.selectedValues=data.selectedValues;
    if(typeof data.amount!='undefined')
        fieldData.amount=data.amount;
    if(RedNaoPathExists(fieldData,'value'))
    {
        fieldData.label=fieldData.value.toString();
        fieldData.numericalValue=0;
        if(fieldData.value=='')
            fieldData.value=0;
        else
            if(!isNaN(fieldData.value))
            {
                fieldData.value=parseFloat(data.value);
                fieldData.numericalValue=data.value;
            }
        if(typeof data.numericalValue!='undefined')
            fieldData.numericalValue=data.numericalValue;

    }else
    {
        if(typeof fieldData.selectedValues!='undefined')
        {
            fieldData.label="";
            for(var i=0;i<fieldData.selectedValues.length;i++)
            {
                fieldData.label+=";"+fieldData.selectedValues[i].label;
            }
            if(fieldData.label.length>0)
                fieldData.label=fieldData.label.substring(1);

        }else
        {
            fieldData.label='';
            fieldData.numericalValue=0;
        }

    }

    this.Data[fieldName]=fieldData;
};

RedNaoFormulaManager.prototype.UpdateFormulaFieldsIfNeeded=function(fieldName)
{
    for(var i=0;i<this.Formulas.length;i++)
    {
        if(this.Formulas[i].FieldUsedInFormula(fieldName))
           this.Formulas[i].UpdateFieldWithValue(this.Data);
    }


};

RedNaoFormulaManager.prototype.RefreshAllFormulas=function()
{
    for(var i=0;i<this.Formulas.length;i++)
        this.Formulas[i].UpdateFieldWithValue(this.Data);



};

RedNaoFormulaManager.prototype.AddFormula=function(formElement,formula)
{
    this.Formulas.push(new RedNaoFormula(formElement,formula))
};

var RedNaoFormulaManagerVar=new RedNaoFormulaManager();