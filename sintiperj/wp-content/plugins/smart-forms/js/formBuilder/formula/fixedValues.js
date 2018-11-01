var smartFormsFormulaFixedValues=[];

function sfFixedValuesBase(options)
{
    this.Label=options.Label;
    this.Type=options.Type;
    this.ToolTip=options.ToolTip;

}


/*-----------------------------------------------------------------------------------------------------Fixed Field-----------------------------------------------------------------------------------------------------------------------------------------*/
function sfLoggedInUser()
{
    var options={};
    options.Label="User Name";
    options.Type="text";
    options.ToolTip="The logged in user";

    sfFixedValuesBase.call(this,options);
}
sfLoggedInUser.prototype=Object.create(sfFixedValuesBase.prototype);

sfLoggedInUser.prototype.GetText=function()
{
    return 'RNUserName()';
};


smartFormsFormulaFixedValues.push(new sfLoggedInUser());
