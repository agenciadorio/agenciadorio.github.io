var smartFormsFormulaCustomActions=[];

function sfCustomActionBase(options)
{
    this.Label=options.Label;
    this.Type=options.Type;
    this.ToolTip=options.ToolTip;

}


/*-----------------------------------------------------------------------------------------------------Fixed Field-----------------------------------------------------------------------------------------------------------------------------------------*/
function sfCustomActionFixedField()
{
    var options={};
    options.Label="Round Amount";
    options.Type="text";
    options.ToolTip="Round an amount by a defined number of decimals \r\nExample: RNFRound( [rnfield1] + [rnfield2] , 2 )";

    sfCustomActionBase.call(this,options);
}
sfCustomActionFixedField.prototype=Object.create(sfCustomActionBase.prototype);

sfCustomActionFixedField.prototype.GetText=function()
{
    return 'RNFRound(your_formula,number_of_decimals)';
};


smartFormsFormulaCustomActions.push(new sfCustomActionFixedField());

/*-----------------------------------------------------------------------------------------------------If Condition-----------------------------------------------------------------------------------------------------------------------------------------*/
function sfCustomActionCondition()
{
    var options={};
    options.Label="Condition";
    options.Type="text";
    options.ToolTip="Create a condition, if the condition is true the 'trueValue' is shown otherwise the 'falseValue' is shown\r\nExample: RNIf([rnField1]>1 , 'rnfield1 is greater than 1' , 'rnfield1 is smaller than 1')'";

    sfCustomActionBase.call(this,options);
}
sfCustomActionCondition.prototype=Object.create(sfCustomActionBase.prototype);

sfCustomActionCondition.prototype.GetText=function()
{
    return 'RNIf(condition,trueValue,falseValue)';
};


smartFormsFormulaCustomActions.push(new sfCustomActionCondition());