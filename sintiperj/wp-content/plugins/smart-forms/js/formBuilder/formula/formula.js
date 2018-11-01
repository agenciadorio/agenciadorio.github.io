function RedNaoFormula(formElement,formula)
{
    this.FormElement=formElement;
    this.Formula=formula;
}

RedNaoFormula.prototype.FieldUsedInFormula=function(fieldName)
{
    for(var i=0;i<this.Formula.FieldsUsed.length;i++)
    {
        if(fieldName==this.Formula.FieldsUsed[i])
            return true;
    }

    return false;
};

RedNaoFormula.prototype.UpdateFieldWithValue=function(value)
{
    try{
        var calculatedValue=this.GetValueFromFormula(value);

        RedNaoBasicManipulatorInstance.SetValue(this.FormElement.Options,this.Formula.PropertyName,calculatedValue,this.Formula.additionalInformation);
        this.FormElement.RefreshElement();
        this.FormElement.FirePropertyChanged();
    }catch(exception)
    {

    }
};

RedNaoFormula.prototype.GetValueFromFormula=function(values)
{

    var calculatedValue=RedNaoEventManager.Publish('CalculateFormula',{Formula:this.Formula,Values:values});

    if(typeof calculatedValue=='number'&&isNaN(calculatedValue))
        calculatedValue=0;

    return calculatedValue;
};

function RNFRound(value,decimals)
{
    return value.toFixed(decimals);
}

function RNUserName()
{
    return smartFormsUserName;
}

function RNIf(condition,trueValue,falseValue)
{
    if(condition)
        return trueValue;

    return falseValue;
}