"use strict";

function SmartFormsIsIE8OrEarlier()
{
    return navigator.appVersion.indexOf("MSIE 7.")!=-1||navigator.appVersion.indexOf("MSIE 8.")!=-1||navigator.appVersion.indexOf("MSIE 9.")!=-1;
}
/************************************************************************************* Formula Methods ***************************************************************************************************/
var SmartFormsStyleScopeField=1;
var SmartFormsStyleScopeType=2;
var SmartFormsStyleScopeAll=3;

//used in an string that is evaluated as function
//noinspection JSUnusedGlobalSymbols
function RedNaoGetValueFromArray(array)
{
    var value=0;

    for(var i=0;i<array.length;i++)
    {
        var aux=parseFloat(array[i].amount);
        if(isNaN(aux))
           aux=0;
        value+=aux;
    }

    return value;
}


function RedNaoListContainsValue(options,value)
{
    var arrayOfValues;
    if(typeof value.selectedValues !='undefined')
        arrayOfValues=value.selectedValues;
    else
    {
        arrayOfValues=[];
        arrayOfValues.push(value);
    }

    for(var i=0;i<arrayOfValues.length;i++)
    {
        for(var h=0;h<options.length;h++)
            if(rnJQuery.trim(options[h].toLowerCase())==rnJQuery.trim(arrayOfValues[i].label.toLowerCase()))
                return true;
    }

    return false;
}


/************************************************************************************* End or formula methods ***************************************************************************************************/




function RedNaoFormElementEscape(property)
{
    return property.replace(' ','_');
}

function sfRedNaoCreateFormElementByName(elementName,options,serverOptions)
{
    if(elementName=='rednaotextinput')
        return new sfTextInputElement(options,serverOptions);
    if(elementName=='rednaoprependedtext')
        return new sfPrependTexElement(options,serverOptions);
    if(elementName=='rednaoappendedtext')
        return new sfAppendedTexElement(options,serverOptions);
    if(elementName=='rednaoprependedcheckbox')
        return new sfPrependCheckBoxElement(options,serverOptions);
    if(elementName=='rednaoappendedcheckbox')
        return new sfAppendCheckBoxElement(options,serverOptions);
    if(elementName=='rednaobuttondropdown')
        return 'rednaobuttondropdown';
    if(elementName=='tabradioscheckboxes')
        return 'tabradioscheckboxes';
    if(elementName=='rednaomultiplecheckboxes')
        return new sfMultipleCheckBoxElement(options,serverOptions);
    if(elementName=='rednaoselectbasic')
        return new sfSelectBasicElement(options,serverOptions);
    if(elementName=='rednaofilebutton')
        return 'rednaofilebutton';
    if(elementName=='rednaosinglebutton')
        return 'rednaosinglebutton';
    if(elementName=='rednaodoublebutton')
        return 'rednaodoublebutton';
    if(elementName=='rednaotitle')
        return new sfTitleElement(options,serverOptions);
    if(elementName=='rednaotextarea')
        return  new sfTextAreaElement(options,serverOptions);
    if(elementName=='rednaomultipleradios')
        return new sfMultipleRadioElement(options,serverOptions);
    if(elementName=='rednaodonationbutton')
        return new sfDonationButtonElement(options,serverOptions);
    if(elementName=='rednaodonationrecurrence')
        return new sfRecurrenceElement(options,serverOptions);
    if(elementName=='rednaosubmissionbutton')
        return new sfRedNaoSubmissionButton(options,serverOptions);

    if(elementName=='rednaodatepicker')
        return new sfRedNaoDatePicker(options,serverOptions);
    if(elementName=='rednaoname')
        return new sfRedNaoName(options,serverOptions);
    if(elementName=='rednaoaddress')
        return new sfRedNaoAddress(options,serverOptions);
    if(elementName=='rednaophone')
        return new sfRedNaoPhone(options,serverOptions);
    if(elementName=='rednaoemail')
        return new sfRedNaoEmail(options,serverOptions);
    if(elementName=='rednaonumber')
        return new sfRedNaoNumber(options,serverOptions);
    if(elementName=='rednaocaptcha')
        return new sfRedNaoCaptcha(options,serverOptions);
    if(elementName=='rednaohtml')
        return new sfHtmlElement(options,serverOptions);
    if(elementName=='rednaosearchablelist')
        return new sfSearchableList(options,serverOptions);

    for(var i=0;i<sfFormElementBase.Extensions.length;i++)
        if(sfFormElementBase.Extensions[i].Name==elementName)
            return sfFormElementBase.Extensions[i].Create(options,serverOptions);

    throw 'Element Type Not Found';
}

/************************************************************************************* Base Class  ***************************************************************************************************/
function sfFormElementBase(options,serverOptions)
{
    //Variable declared in another javascript;
    //noinspection JSUnresolvedVariable
    this.Translations=SmartFormsElementsTranslation;
    this.StyleTags={};
    this._ignore=false;
    this.Errors={};
    if(options==null)
    {
        this.Options={};
        this.ServerOptions={};
        this.Options.ClassName="";
        this.Options.IsRequired='n';
        this.Options.Formulas={};
        this.Options.Styles={};

        sfFormElementBase.IdCounter++;
        this.Id='rnField'+sfFormElementBase.IdCounter;
        while(!SmartFormsFieldIsAvailable(this.Id))
        {
            sfFormElementBase.IdCounter++;
            this.Id='rnField'+sfFormElementBase.IdCounter;
        }

        this.Options.Id=this.Id;
        this.IsNew=true;
        this.GenerateDefaultStyle();
    }
    else
    {
        this.Id=options.Id;
        this.IsNew=false;
        if(serverOptions=='undefined')
            this.ServerOptions={};
        else
            this.ServerOptions=serverOptions;
        if(typeof options.IsRequired=='undefined')
            options.IsRequired='n';
        this.Options=options;
        if(typeof this.Options.Styles=='undefined')
            this.Options.Styles={};
        if(typeof this.Options.Formulas=='undefined')
            this.Options.Formulas={};
        else{
            if(typeof RedNaoFormulaManagerVar!='undefined')
                for(var property in this.Options.Formulas)
                {
                    RedNaoFormulaManagerVar.AddFormula(this,this.Options.Formulas[property]);
                }
        }


    }
    this.FormId=0;
    this.Properties=null;
    this.amount=0;
    this.SetGlobalProperties();
}
sfFormElementBase.Extensions=[];
sfFormElementBase.IdCounter=0;


sfFormElementBase.prototype.AddError=function(id,errorMessage)
{
    this.Errors[id]=errorMessage;
};

sfFormElementBase.prototype.RemoveError=function(id)
{
    if(typeof this.Errors[id]!='undefined')
        delete this.Errors[id];
};

sfFormElementBase.prototype.GetFirstError=function()
{
    for(var errorId in this.Errors)
        return this.Errors[errorId];

    return null;
};


sfFormElementBase.prototype.SetGlobalProperties=function()
{
    if(this.IsNew)
        this.Options.Spacing='col-sm-12';
    else
        this.SetDefaultIfUndefined('Spacing','col-sm-12');
};

sfFormElementBase.prototype.SetData=function(data)
{

};

sfFormElementBase.prototype.Ignore=function()
{
    this._ignore=true;
    this.FirePropertyChanged();
};

sfFormElementBase.prototype.GetStepId=function()
{
    if(typeof this.Options.StepId=='undefined')
        return '';

    return this.Options.StepId;
};

sfFormElementBase.prototype.SetStepId=function(stepId)
{
    this.Options.StepId=stepId;
};

sfFormElementBase.prototype.UnIgnore=function()
{
    this._ignore=false;
    this.FirePropertyChanged();
};

sfFormElementBase.prototype.IsIgnored=function()
{
    return this._ignore;
};


sfFormElementBase.prototype.FirePropertyChanged=function(){

    this.DestroyPopOver();
    RedNaoEventManager.Publish('formPropertyChanged',{FieldName:this.Id, Value:this.GetValueString(),FormId:this.FormId});

};

sfFormElementBase.prototype.DestroyPopOver=function()
{
    this.JQueryElement.popover('destroy');
};

sfFormElementBase.prototype.SetDefaultIfUndefined=function(propertyName,defaultValue)
{
    if(typeof this.Options[propertyName]=='undefined')
        this.Options[propertyName]=defaultValue;
};


sfFormElementBase.prototype.GenerateDefaultStyle=function()
{
};

sfFormElementBase.prototype.GetRootContainer=function()
{
    return rnJQuery("#"+this.Id);
};

sfFormElementBase.prototype.GetElementByClassName=function(className)
{
    return this.GetRootContainer().find("."+className);
};

sfFormElementBase.prototype.RefreshElement=function()
{
    var element=this.JQueryElement;
   // var labelWidth=element.find('.rednao_label_container').width();
    //var controlWidth=element.find('.redNaoControls').width();
    element.find(".rednao_label_container, .redNaoControls").remove();
    element.find(".redNaoOneColumn").remove();
    var generatedElement=rnJQuery(this.GenerateInlineElement());
    element.append(generatedElement);
    //noinspection JSUnusedGlobalSymbols
    this.JQueryElement=element;
    this.GenerationCompleted(element);
    if(!smartFormsDesignMode)
    {
    //    element.find('.rednao_label_container').width(labelWidth);
    //    element.find('.redNaoControls').width(controlWidth);
    }
    return element;
};
sfFormElementBase.prototype.GenerateHtml=function(jqueryElement)
{

    var newElement=rnJQuery('<div class="'+this.GetElementClasses()+'" id="'+this.Id+'" >'+this.GenerateInlineElement()+'</div>');
    jqueryElement.replaceWith(newElement );
    //noinspection JSUnusedGlobalSymbols
    this.JQueryElement=newElement;
    this.GenerationCompleted(newElement);
    this.ApplyAllStyles();
    return newElement;

};

sfFormElementBase.prototype.AppendElementToContainer=function(jqueryElement)
{
    var JQueryElement=rnJQuery( '<div class="'+this.GetElementClasses()+'" id="'+this.Id+'">'+this.GenerateInlineElement()+'</div>');
    jqueryElement.append(JQueryElement);
    //noinspection JSUnusedGlobalSymbols
    this.JQueryElement=JQueryElement;
    this.GenerationCompleted(JQueryElement);
    this.ApplyAllStyles();

};

sfFormElementBase.prototype.GetElementClasses=function()
{
    var isRequired='';
    if(typeof this.Options.IsRequired!='undefined'&&this.Options.IsRequired=='y')
        isRequired='sfRequired';

    return 'rednao-control-group form-group row '+this.Options.ClassName+' '+this.Options.Spacing+' '+ isRequired+' '+this.Options.CustomCSS;
};

sfFormElementBase.prototype.GetFriendlyName=function()
{
    return this.Options.Label;
};

sfFormElementBase.prototype.StoresInformation=function()
{
    return true;
};


sfFormElementBase.prototype.CreateProperties=function()
{
    throw 'Abstract method';
};

sfFormElementBase.prototype.GenerateInlineElement=function()
{
    throw 'Abstract method';
};

sfFormElementBase.prototype.GetProperties=function()
{
    if(this.Properties==null)
    {
        this.Properties=[];
        this.CreateProperties();
        this.CreateGlobalProperties();
    }

    return this.Properties;
};

sfFormElementBase.prototype.CreateGlobalProperties=function()
{
    var self=this;
    this.Properties.push(new ComboBoxProperty(this,this.Options,"Spacing","Spacing",{ManipulatorType:'basic',Values:[
        {label:'Fill entire row (12 Columns)',value:'col-sm-12'},
        {label:'Use 11 columns',value:'col-sm-11'},
        {label:'Use 10 columns',value:'col-sm-10'},
        {label:'Use 9 columns',value:'col-sm-9'},
        {label:'Use 8 columns',value:'col-sm-8'},
        {label:'Use 7 columns',value:'col-sm-7'},
        {label:'Use 6 columns',value:'col-sm-6'},
        {label:'Use 5 columns',value:'col-sm-5'},
        {label:'Use 4 columns',value:'col-sm-4'},
        {label:'Use 3 columns',value:'col-sm-3'},
        {label:'Use 2 columns',value:'col-sm-2'},
        {label:'Use 1 columns',value:'col-sm-1'}
        ],
        ChangeCallBack:function()
        {
           var previousClasses=self.JQueryElement.attr('class');
            var newClasses=self.GetElementClasses();
            if(previousClasses.indexOf('SmartFormsElementSelected')>=0)
                newClasses+=' SmartFormsElementSelected';
            self.JQueryElement.attr('class',newClasses);
        },
        ToolTip:{Text:"Space that the field will occupy.\r\nNote: All rows have a capacity of 12 columns and will be filled with as much fields as possible, for example, if you sequentially have two fields that use 6 columns each they will be placed in the same row",
            Width:'400px'}
    }));
};

sfFormElementBase.prototype.GetPropertyName=function()
{
    return RedNaoEscapeHtml(RedNaoFormElementEscape(this.Options.Label));
};

sfFormElementBase.prototype.RegisterForFocusEvent=function($element)
{
    $element.focus(function(){
        $element.closest('.form-group').addClass('is-focused');
    });

    $element.focusout(function(){
        $element.closest('.form-group').removeClass('is-focused');
    });

};

sfFormElementBase.prototype.GeneratePropertiesHtml=function(jQueryObject)
{
    var properties=this.GetProperties();

    for(var i=0;i<properties.length;i++)
    {
        properties[i].CreateProperty(jQueryObject);
    }
};


sfFormElementBase.prototype.GetValueString=function()
{

};

sfFormElementBase.prototype.GenerationCompleted=function(jQueryElement)
{

};

sfFormElementBase.prototype.IsValid=function()
{
    return true;
};

sfFormElementBase.prototype.InternalIsValid=function()
{
    var firstError=this.GetFirstError();
    if(firstError==null)
    {
        this.JQueryElement.removeClass('has-error');
        this.JQueryElement.popover('destroy');
        return true;
    }
    this.JQueryElement.addClass('has-error');
    var tooltipPosition=this.ClientOptions.ToolTipPosition;
    if(this.ClientOptions.DocumentWidth<=768)
        tooltipPosition='bottom';


    if(this.ClientOptions.ToolTipPosition!='none')
        this.JQueryElement.popover({
            html : true,
            content :firstError,
            placement:tooltipPosition,
            template:'<div class="popover invalid" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
        });

    if(this.IsIgnored())
        return true;

    this.JQueryElement.popover('show');
    return false;


};

sfFormElementBase.prototype.ClearInvalidStyle=function()
{

};

sfFormElementBase.prototype.Clone=function()
{
   var newObject=  rnJQuery.extend(true, {}, this);
    sfFormElementBase.IdCounter++;
    newObject.Id='rnField'+sfFormElementBase.IdCounter;
    newObject.Options.Id=newObject.Id;
    newObject.Properties=null;


    return newObject;
};

sfFormElementBase.prototype.GetValuePath=function()
{
    return '';
};

sfFormElementBase.prototype.GetLabelPath=function()
{
    return 'formData.'+this.Id+'.label';
};

sfFormElementBase.prototype.GetNumericalValuePath=function()
{
    return 'formData.'+this.Id+'.numericalValue';
};

sfFormElementBase.prototype.GetStyleTagForElement=function(elementName)
{
    if(typeof this.StyleTags[elementName]=='undefined')
    {
        this.StyleTags[elementName]=rnJQuery("<style type='text/css'></style>");
        rnJQuery("head").append(this.StyleTags[elementName]);
    }

    return this.StyleTags[elementName];
};

sfFormElementBase.prototype.ApplyAllStyles=function()
{
    for(var elements in this.Options.Styles)
        this.ApplyTagStyleForElement(elements);
};

sfFormElementBase.prototype.ApplyTagStyleForElement=function(elementName)
{
    if(typeof this.Options.Styles[elementName]=='undefined'||SmartFormsIsIE8OrEarlier())
        return;

    var style="";
    var elementProperties=this.Options.Styles[elementName];
    if(typeof elementProperties.Properties !='undefined')
        for(var styleName in elementProperties.Properties)
        {
            //noinspection JSUnfilteredForInLoop
            style+=styleName+":"+elementProperties.Properties[styleName]+" !important;";
        }

    if(typeof elementProperties.CustomCSS!='undefined')
        style+=elementProperties.CustomCSS.CSS;

    //noinspection JSUnresolvedVariable
    var selector=this.GetSelectorByScope(elementProperties.Scope,elementName);

    var tag=this.GetStyleTagForElement(elementName);
    tag.empty();
    tag.append( selector+' {'+style+'}')


};

sfFormElementBase.prototype.GetSelectorByScope=function(scope,elementName)
{
    if(scope==SmartFormsStyleScopeField)
        return '#'+this.Id + " ."+elementName;

    if(scope==SmartFormsStyleScopeType)
        return '.'+this.Options.ClassName+' .'+elementName;

    if(scope==SmartFormsStyleScopeAll)
        return '.'+elementName;

    throw ("Undefined scope");
};


sfFormElementBase.prototype.LoadPlaceHolderIcon=function($element,$offsetLeft,$offsetRight,options)
{
    rnJQuery.RNLoadLibrary([smartFormsPath+'js/utilities/rnPlaceHolderIcons.js'],null,function(){
        SfLoadPlaceHolderIcon($element,$offsetLeft,$offsetRight,options);
    });
};

/************************************************************************************* Title Element ***************************************************************************************************/

function sfTitleElement(options,serverOptions)
{
    sfFormElementBase.call(this,options,serverOptions);
    this.Title="Title";
    if(this.IsNew)
    {
        this.Options.ClassName="rednaotitle";
        this.Options.Title="Title";
        this.Options.CustomCSS='';
    }else{
        this.SetDefaultIfUndefined('CustomCSS','');
    }
}
sfTitleElement.prototype=Object.create(sfFormElementBase.prototype);

sfTitleElement.prototype.GetFriendlyName=function()
{
    return this.Options.Title;
};


sfTitleElement.prototype.CreateProperties=function()
{

    this.Properties.push(new SimpleTextProperty(this,this.Options,"Title","Title",{ManipulatorType:'basic'}));
    this.Properties.push(new CustomCSSProperty(this,this.Options));
};

sfTitleElement.prototype.GenerateInlineElement=function()
{
    return '<div class="col-sm-12"><legend class="redNaoLegend redNaoOneColumn">'+RedNaoEscapeHtml(this.Options.Title)+'</legend></div>';
};




sfTitleElement.prototype.GetValueString=function()
{
    return '';

};

sfTitleElement.prototype.StoresInformation=function()
{
    return false;
};

/************************************************************************************* Text Element ***************************************************************************************************/

function sfTextInputElement(options,serverOptions)
{
    sfFormElementBase.call(this,options,serverOptions);
    this.Title="Text Input";
    if(this.IsNew)
    {
        this.Options.ClassName="rednaotextinput";
        this.Options.Label="Text Input";
        this.Options.Placeholder="Placeholder";
        this.Options.Value="";
        this.Options.ReadOnly='n';
        this.Options.Width="";
        this.Options.Icon={ClassName:''};
        this.Options.CustomCSS='';
        this.Options.Placeholder_Icon={ClassName:'',Orientation:''};
    }else{
        this.SetDefaultIfUndefined('Value','');
        this.SetDefaultIfUndefined('ReadOnly','n');
        this.SetDefaultIfUndefined('Width','');
        this.SetDefaultIfUndefined('Icon',{ClassName:''});
        this.SetDefaultIfUndefined('CustomCSS','');
        this.SetDefaultIfUndefined('Placeholder_Icon',{ClassName:''});
    }





}

sfTextInputElement.prototype=Object.create(sfFormElementBase.prototype);

sfTextInputElement.prototype.CreateProperties=function()
{
    this.Properties.push(new IdProperty(this,this.Options));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Label","Label",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Placeholder","Placeholder",{ManipulatorType:'basic',IconOptions:{Type:'leftAndRight'}}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Width","Width",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Value","Value",{ManipulatorType:'basic',RefreshFormData:true}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"IsRequired","Required",{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"ReadOnly","Read Only",{ManipulatorType:'basic'}));
    this.Properties.push(new CustomCSSProperty(this,this.Options));
    this.Properties.push(new IconProperty(this,this.Options,'Icon','Icon',{ManipulatorType:'basic'}));
};

sfTextInputElement.prototype.GenerateInlineElement=function()
{
    var additionalStyle='';
    if(!isNaN(parseFloat(this.Options.Width)))
        additionalStyle='width:'+this.Options.Width+'px'+' !important;';

    var startOfInput='';
    var endOfInput='';

    if(this.Options.Icon.ClassName!='')
    {
        startOfInput='<div class="rednao-input-prepend input-group"><span class="redNaoPrepend input-group-addon prefix '+RedNaoEscapeHtml(this.Options.Icon.ClassName)+' "></span>';
        endOfInput='</div>';
    }

    return '<div class="rednao_label_container col-sm-3"><label class="rednao_control_label" >'+RedNaoEscapeHtml(this.Options.Label)+'</label></div>'+
                '<div class="redNaoControls col-sm-9">'+
                     startOfInput+
                    '<input style="'+additionalStyle+'" '+(this.Options.ReadOnly=='y'?'disabled="disabled"':"")+' name="'+this.GetPropertyName()+'" type="text" placeholder="'+RedNaoEscapeHtml(this.Options.Placeholder)+'" class="form-control redNaoInputText '+(this.Options.ReadOnly=='y'?'redNaoDisabledElement':"")+'" value="'+RedNaoEscapeHtml(this.Options.Value)+'">'+
                    endOfInput+
                '</div>';
};


sfTextInputElement.prototype.GetValueString=function()
{
    if(this.IsIgnored())
        return {value:''};
    return {value:this.JQueryElement.find('.redNaoInputText').val()};
};

sfTextInputElement.prototype.SetData=function(data)
{
    this.JQueryElement.find('.redNaoInputText').val(data.value);
};

sfTextInputElement.prototype.GetValuePath=function()
{
    return 'formData.'+this.Id+'.value';
};


sfTextInputElement.prototype.IsValid=function()
{
    if(this.JQueryElement.find('.redNaoInputText').val()==""&&this.Options.IsRequired=='y')
        this.AddError('root',this.InvalidInputMessage);
    else
        this.RemoveError('root');
    return this.InternalIsValid();
};

//noinspection JSUnusedLocalSymbols
sfTextInputElement.prototype.GenerationCompleted=function(jQueryElement)
{
    var self=this;
    this.JQueryElement.find( '.redNaoInputText').change(function(){self.FirePropertyChanged();});
    this.RegisterForFocusEvent(this.JQueryElement.find( '.redNaoInputText'));
    if(this.Options.Placeholder_Icon.ClassName!='')
    {
        this.LoadPlaceHolderIcon(jQueryElement.find('.redNaoInputText'),null,null,this.Options.Placeholder_Icon);
    }


};

/************************************************************************************* Prepend Text Element ***************************************************************************************************/

function sfPrependTexElement(options,serverOptions)
{
    sfFormElementBase.call(this,options,serverOptions);
    this.Title="Prepend Text";

    if(this.IsNew)
    {
        this.Options.Label="Prepend Text";
        this.Options.ClassName="rednaoprependedtext";
        this.Options.Placeholder="Placeholder";
        this.Options.Prepend="Prepend";
        this.Options.Value='';
        this.Options.Checked='y';
        this.Options.Width='';
        this.Options.Icon={ClassName:''};
        this.Options.CustomCSS='';
        this.Options.ReadOnly='n';
        this.Options.Placeholder_Icon={ClassName:'',Orientation:''};
    }else{
        this.SetDefaultIfUndefined('Value','');
        this.SetDefaultIfUndefined('Width','');
        this.SetDefaultIfUndefined('Icon',{ClassName:''});
        this.SetDefaultIfUndefined('CustomCSS','');
        this.SetDefaultIfUndefined('Placeholder_Icon',{ClassName:''});
        this.SetDefaultIfUndefined('ReadOnly','n');

    }



}

sfPrependTexElement.prototype=Object.create(sfFormElementBase.prototype);

sfPrependTexElement.prototype.CreateProperties=function()
{
    this.Properties.push(new IdProperty(this,this.Options));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Label","Label",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Prepend","Prepend",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Placeholder","Placeholder",{ManipulatorType:'basic',IconOptions:{Type:'leftAndRight'}}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Width","Width",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Value","Value",{ManipulatorType:'basic',RefreshFormData:true}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"IsRequired","Required",{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"ReadOnly","Read Only",{ManipulatorType:'basic'}));
    this.Properties.push(new CustomCSSProperty(this,this.Options));
    this.Properties.push(new IconProperty(this,this.Options,'Icon','Icon',{ManipulatorType:'basic'}));
};

sfPrependTexElement.prototype.GenerateInlineElement=function()
{
    var additionalStyle='';
    if(!isNaN(parseFloat(this.Options.Width)))
        additionalStyle='width:'+this.Options.Width+'px'+' !important;';



    return '<div class="rednao_label_container col-sm-3">'+
            '<label class="rednao_control_label" for="prependedtext">'+RedNaoEscapeHtml(this.Options.Label)+'</label>' +
            '</div>'+
            '<div class="redNaoControls col-sm-9">'+
               '<div class="rednao-input-prepend input-group">'+
                    (this.Options.Icon.ClassName!=''?'<span class="redNaoPrepend input-group-addon prefix '+RedNaoEscapeHtml(this.Options.Icon.ClassName)+' "></span>'
                                                            :'<span class="redNaoPrepend input-group-addon prefix">'+RedNaoEscapeHtml(this.Options.Prepend)+'</span>')+
                    '<input style="'+additionalStyle+'"  name="prependedtext" '+(this.Options.ReadOnly=='y'?'disabled="disabled"':"")+' class="redNaoInputText form-control" placeholder="'+RedNaoEscapeHtml(this.Options.Placeholder)+'" type="text" value="'+RedNaoEscapeHtml(this.Options.Value)+'">'+
                '</div>'+
            '</div>';
};




sfPrependTexElement.prototype.GetValueString=function()
{
    if(this.IsIgnored())
        return {value:''};
    return {value:rnJQuery('#'+this.Id+ ' .redNaoInputText').val()};
};

sfPrependTexElement.prototype.SetData=function(data)
{
    this.JQueryElement.find('.redNaoInputText').val(data.value);
};


sfPrependTexElement.prototype.GetValuePath=function()
{
    return 'formData.'+this.Id+'.value';
};


sfPrependTexElement.prototype.IsValid=function()
{
    if(rnJQuery('#'+this.Id+ ' .redNaoInputText').val()==""&&this.Options.IsRequired=='y')
    {
        rnJQuery('#'+this.Id).addClass('has-error');
        this.AddError('root',this.InvalidInputMessage);
    }
    else
        this.RemoveError('root');

    return this.InternalIsValid();
};

//noinspection JSUnusedLocalSymbols
sfPrependTexElement.prototype.GenerationCompleted=function(jQueryElement)
{
    var self=this;
    rnJQuery('#'+this.Id+ ' .redNaoInputText').change(function(){self.FirePropertyChanged();});
    this.RegisterForFocusEvent(this.JQueryElement.find( '.redNaoInputText'));
    if(this.Options.Placeholder_Icon.ClassName!='')
    {
        this.LoadPlaceHolderIcon(jQueryElement.find('.redNaoInputText'),jQueryElement.find('.redNaoPrepend'),null,this.Options.Placeholder_Icon);
    }
};

/************************************************************************************* Appended Text Element ***************************************************************************************************/

function sfAppendedTexElement(options,serverOptions)
{
    sfFormElementBase.call(this,options,serverOptions);
    this.Title="Appended Text";

    if(this.IsNew)
    {
        this.Options.Label="Appended Text";
        this.Options.ClassName="rednaoappendedtext";
        this.Options.Placeholder="Placeholder";
        this.Options.Append="Append";
        this.Options.Value="";
        this.Options.Width='';
        this.Options.Icon={ClassName:''};
        this.Options.CustomCSS='';
        this.Options.ReadOnly='n';

        this.Options.Placeholder_Icon={ClassName:'',Orientation:''};
    }else{
        this.SetDefaultIfUndefined('Value','');
        this.SetDefaultIfUndefined('Width','');
        this.SetDefaultIfUndefined('Icon',{ClassName:""});
        this.SetDefaultIfUndefined('CustomCSS','');
        this.SetDefaultIfUndefined('Placeholder_Icon',{ClassName:''});
        this.SetDefaultIfUndefined('ReadOnly','n');

    }
}

sfAppendedTexElement.prototype=Object.create(sfFormElementBase.prototype);

sfAppendedTexElement.prototype.CreateProperties=function()
{
    this.Properties.push(new IdProperty(this,this.Options));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Label","Label",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Append","Append",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Placeholder","Placeholder",{ManipulatorType:'basic',IconOptions:{Type:'leftAndRight'}}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Width","Width",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Value","Value",{ManipulatorType:'basic',RefreshFormData:true}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"IsRequired","Required",{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"ReadOnly","Read Only",{ManipulatorType:'basic'}));
    this.Properties.push(new CustomCSSProperty(this,this.Options));
    this.Properties.push(new IconProperty(this,this.Options,'Icon','Icon',{ManipulatorType:'basic'}));
};

sfAppendedTexElement.prototype.GenerateInlineElement=function()
{
    var additionalStyle='';
    if(!isNaN(parseFloat(this.Options.Width)))
        additionalStyle='width:'+this.Options.Width+'px'+' !important;';


    return '<div class="rednao_label_container col-sm-3"><label class="rednao_control_label" for="appendedtext">'+RedNaoEscapeHtml(this.Options.Label)+'</label></div>'+
            '<div class="redNaoControls col-sm-9 ">'+
                '<div class="rednao-input-append input-group">'+
                    '<input style="'+additionalStyle+'" name="appendedtext" '+(this.Options.ReadOnly=='y'?'disabled="disabled"':"")+'  placeholder="'+RedNaoEscapeHtml(this.Options.Placeholder)+'" type="text" class="redNaoInputText form-control" value="'+RedNaoEscapeHtml(this.Options.Value)+'">'+
                    (this.Options.Icon.ClassName!=''?'<span class="redNaoAppend input-group-addon '+RedNaoEscapeHtml(this.Options.Icon.ClassName)+' "></span>'
                                                            :'<span class="redNaoAppend input-group-addon">'+RedNaoEscapeHtml(this.Options.Append)+'</span>')+
                '</div>'+
            '</div>';


};




sfAppendedTexElement.prototype.GetValueString=function()
{
    if(this.IsIgnored())
        return {value:''};
    return  {value:rnJQuery('#'+this.Id+ ' .redNaoInputText').val()};
};

sfAppendedTexElement.prototype.SetData=function(data)
{
    this.JQueryElement.find('.redNaoInputText').val(data.value);
};



sfAppendedTexElement.prototype.GetValuePath=function()
{
    return 'formData.'+this.Id+'.value';
};

sfAppendedTexElement.prototype.IsValid=function()
{
    if(rnJQuery('#'+this.Id+ ' .redNaoInputText').val()==""&&this.Options.IsRequired=='y')
    {
        rnJQuery('#'+this.Id).addClass('has-error');
        this.AddError('root',this.InvalidInputMessage);
    }else
        this.RemoveError('root');

    return this.InternalIsValid();
};

//noinspection JSUnusedLocalSymbols
sfAppendedTexElement.prototype.GenerationCompleted=function(jQueryElement)
{
    var self=this;
    rnJQuery('#'+this.Id+ ' .redNaoInputText').change(function(){self.FirePropertyChanged();});
    this.RegisterForFocusEvent(this.JQueryElement.find( '.redNaoInputText'));
    if(this.Options.Placeholder_Icon.ClassName!='')
    {
        this.LoadPlaceHolderIcon(jQueryElement.find('.redNaoInputText'),null,jQueryElement.find('.redNaoAppend'),this.Options.Placeholder_Icon);
    }
};


/************************************************************************************* Prepend Checkbox Element ***************************************************************************************************/

function sfPrependCheckBoxElement(options,serverOptions)
{
    sfFormElementBase.call(this,options,serverOptions);
    this.Title="Prepend Checkbox";

    if(this.IsNew)
    {
        this.Options.Label="Prepend Checkbox";
        this.Options.ClassName="rednaoprependedcheckbox";
        this.Options.Placeholder="Placeholder";
        this.Options.IsChecked='n';
        this.Options.Value="";
        this.Options.Width='';
        this.Options.CustomCSS='';
        this.Options.Placeholder_Icon={ClassName:'',Orientation:''};
    }else{
        this.SetDefaultIfUndefined('Value','');
        this.SetDefaultIfUndefined('Width','');
        this.SetDefaultIfUndefined('CustomCSS','');
        this.SetDefaultIfUndefined('Placeholder_Icon',{ClassName:''});

    }
}

sfPrependCheckBoxElement.prototype=Object.create(sfFormElementBase.prototype);

sfPrependCheckBoxElement.prototype.CreateProperties=function()
{
    this.Properties.push(new IdProperty(this,this.Options));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Label","Label",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Placeholder","Placeholder",{ManipulatorType:'basic',IconOptions:{Type:'leftAndRight'}}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Width","Width",{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"IsChecked","Is Checked",{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"IsRequired","Required",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Value","Value",{ManipulatorType:'basic',RefreshFormData:true}));
    this.Properties.push(new CustomCSSProperty(this,this.Options));

};

sfPrependCheckBoxElement.prototype.GenerateInlineElement=function()
{
    var additionalStyle='';
    if(!isNaN(parseFloat(this.Options.Width)))
        additionalStyle='width:'+this.Options.Width+'px'+' !important;';


    return '<div class="rednao_label_container col-sm-3"><label class="rednao_control_label" for="prependedcheckbox">'+RedNaoEscapeHtml(this.Options.Label)+'</label></div>\
                <div class="redNaoControls col-sm-9">\
                    <div class="input-prepend input-group">\
                        <span class="redNaoPrepend input-group-addon">\
                                <input  type="checkbox" class="redNaoRealCheckBox "  '+(this.Options.IsChecked=='y'? 'checked="checked"':'')+'/>\
                        </span>\
                        <input style="'+additionalStyle+'" id="prependedcheckbox" name="prependedcheckbox"  class="redNaoInputText form-control" type="text" placeholder="'+RedNaoEscapeHtml(this.Options.Placeholder)+'" value="'+RedNaoEscapeHtml(this.Options.Value)+'"/>\
                    </div>\
                </div>';


};



sfPrependCheckBoxElement.prototype.GetValueString=function()
{
    if(this.IsIgnored())
        return {checked:'n',value:''};
    return  {checked:(rnJQuery('#'+this.Id).find('.redNaoRealCheckBox').is(':checked')?'Yes':'No'),value:rnJQuery('#'+this.Id+ ' .redNaoInputText').val()};
};

sfPrependCheckBoxElement.prototype.SetData=function(data)
{
    if(data.checked=='Yes')
        this.JQueryElement.find('.redNaoRealCheckBox').attr('checked','checked');
    else
        this.JQueryElement.find('.redNaoRealCheckBox').removeAttr('checked');


    this.JQueryElement.find('.redNaoInputText').val(data.value);
};



sfPrependCheckBoxElement.prototype.GetValuePath=function()
{
    return 'formData.'+this.Id+'.value';
};


sfPrependCheckBoxElement.prototype.IsValid=function()
{
    if(rnJQuery('#'+this.Id+ ' .redNaoInputText').val()==""&&this.Options.IsRequired=='y')
    {
        rnJQuery('#'+this.Id).addClass('has-error');
        this.AddError('root',this.InvalidInputMessage);
    }else
        this.RemoveError('root');

    return this.InternalIsValid();

};

//noinspection JSUnusedLocalSymbols
sfPrependCheckBoxElement.prototype.GenerationCompleted=function(jQueryElement)
{
    var self=this;
    rnJQuery('#'+this.Id+ ' .redNaoInputText,#'+this.Id+' .redNaoRealCheckBox').change(function(){self.FirePropertyChanged();});
    this.RegisterForFocusEvent(this.JQueryElement.find( '.redNaoInputText'));
    if(this.Options.Placeholder_Icon.ClassName!='')
    {
        this.LoadPlaceHolderIcon(jQueryElement.find('.redNaoInputText'),jQueryElement.find('.redNaoPrepend'),null,this.Options.Placeholder_Icon);
    }


};
/************************************************************************************* Append Checkbox Element ***************************************************************************************************/

function sfAppendCheckBoxElement(options,serverOptions)
{
    sfFormElementBase.call(this,options,serverOptions);
    this.Title="Append Checkbox";

    if(this.IsNew)
    {
        this.Options.Label="Append Checkbox";
        this.Options.ClassName="rednaoappendedcheckbox";
        this.Options.Placeholder="Placeholder";
        this.Options.IsChecked='n';
        this.Options.Value="";
        this.Options.Width='';
        this.Options.CustomCSS='';
        this.Options.Placeholder_Icon={ClassName:'',Orientation:''};
    }else{
        this.SetDefaultIfUndefined('Value','');
        this.SetDefaultIfUndefined('Width','');
        this.SetDefaultIfUndefined('CustomCSS','');
        this.SetDefaultIfUndefined('Placeholder_Icon',{ClassName:''});

    }
}

sfAppendCheckBoxElement.prototype=Object.create(sfFormElementBase.prototype);

sfAppendCheckBoxElement.prototype.CreateProperties=function()
{
    this.Properties.push(new IdProperty(this,this.Options));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Label","Label",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Placeholder","Placeholder",{ManipulatorType:'basic',IconOptions:{Type:'leftAndRight'}}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Width","Width",{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"IsChecked","Is Checked",{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"IsRequired","Required",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Value","Value",{ManipulatorType:'basic',RefreshFormData:true}));
    this.Properties.push(new CustomCSSProperty(this,this.Options));

};

sfAppendCheckBoxElement.prototype.GenerateInlineElement=function()
{
    var additionalStyle='';
    if(!isNaN(parseFloat(this.Options.Width)))
        additionalStyle='width:'+this.Options.Width+'px'+' !important;';


    return '<div class="rednao_label_container col-sm-3"><label class="rednao_control_label" for="appendedcheckbox">'+RedNaoEscapeHtml(this.Options.Label)+'</label></div>\
            <div class="redNaoControls col-sm-9">\
                <div class="rednao-input-append input-group">\
                    <input style="'+additionalStyle+'" id="appendedcheckbox" class="form-control redNaoInputText" name="appendedcheckbox" class="form-control span2" type="text" placeholder="'+RedNaoEscapeHtml(this.Options.Placeholder)+'" value="'+RedNaoEscapeHtml(this.Options.Value)+'"/>\
                        <span class="redNaoAppend input-group-addon">\
                            <input type="checkbox" class="redNaoRealCheckBox "   '+(this.Options.IsChecked=='y'? 'checked="checked"':'')+'/>\
                        </span>\
                </div>\
            </div>';


};



sfAppendCheckBoxElement.prototype.GetValueString=function()
{
    if(this.IsIgnored())
        return {checked:'n',value:''};
    return  {checked:(rnJQuery('#'+this.Id).find('.redNaoRealCheckBox').is(':checked')?'Yes':'No'),value:rnJQuery('#'+this.Id+ ' .redNaoInputText').val()};
};


sfAppendCheckBoxElement.prototype.SetData=function(data)
{
    if(data.checked=='Yes')
        this.JQueryElement.find('.redNaoRealCheckBox').attr('checked','checked');
    else
        this.JQueryElement.find('.redNaoRealCheckBox').removeAttr('checked');


    this.JQueryElement.find('.redNaoInputText').val(data.value);
};


sfAppendCheckBoxElement.prototype.GetValuePath=function()
{
    return 'formData.'+this.Id+'.value';
};

sfAppendCheckBoxElement.prototype.IsValid=function()
{
    if(rnJQuery('#'+this.Id+ ' .redNaoInputText').val()==""&&this.Options.IsRequired=='y')
    {
        rnJQuery('#'+this.Id).addClass('has-error');
        this.AddError('root',this.InvalidInputMessage);
    }
    else
        this.RemoveError('root');

    return this.InternalIsValid();
};

//noinspection JSUnusedLocalSymbols
sfAppendCheckBoxElement.prototype.GenerationCompleted=function(jQueryElement)
{
    var self=this;
    rnJQuery('#'+this.Id+ ' .redNaoInputText,#'+this.Id+' .redNaoRealCheckBox').change(function(){self.FirePropertyChanged();});
    this.RegisterForFocusEvent(this.JQueryElement.find( '.redNaoInputText'));
    if(this.Options.Placeholder_Icon.ClassName!='')
    {
        this.LoadPlaceHolderIcon(jQueryElement.find('.redNaoInputText'),null,jQueryElement.find('.redNaoAppend'),this.Options.Placeholder_Icon);
    }


};
/************************************************************************************* Text Area Element ***************************************************************************************************/

function sfTextAreaElement(options,serverOptions)
{
    sfFormElementBase.call(this,options,serverOptions);
    this.Title="Text Area";

    if(this.IsNew)
    {
        this.Options.Label="Text Area";
        this.Options.DefaultText="";
        this.Options.ClassName="rednaotextarea";
        this.Options.Value="";
        this.Options.Width='';
        this.Options.Height='';
        this.Options.Placeholder='Placeholder';
        this.Options.Disabled="n";
        this.Options.MaxLength='';
        this.Options.CustomCSS='';
        this.Options.Placeholder_Icon={ClassName:'',Orientation:''};
    }else{
        this.SetDefaultIfUndefined('Value','');
        this.SetDefaultIfUndefined('Width','');
        this.SetDefaultIfUndefined('Height','');
        this.SetDefaultIfUndefined('Placeholder','');
        this.SetDefaultIfUndefined('Disabled','n');
        this.SetDefaultIfUndefined('MaxLength','');
        this.SetDefaultIfUndefined('CustomCSS','');
        this.SetDefaultIfUndefined('Placeholder_Icon',{ClassName:''});
    }

    this.MaxLength=parseFloat(this.Options.MaxLength);
}

sfTextAreaElement.prototype=Object.create(sfFormElementBase.prototype);

sfTextAreaElement.prototype.CreateProperties=function()
{
    this.Properties.push(new IdProperty(this,this.Options));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Label","Label",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"DefaultText","Value",{ManipulatorType:'basic',RefreshFormData:true,MultipleLine:true}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Placeholder","Placeholder",{ManipulatorType:'basic',RefreshFormData:true,IconOptions:{Type:'leftAndRight'}}));
    this.Properties.push(new SimpleNumericProperty(this,this.Options,"MaxLength","Max Character",{ManipulatorType:'basic',Placeholder:"No Limit"}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Width","Width",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Height","Height",{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"IsRequired","Required",{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"Disabled","Disabled",{ManipulatorType:'basic'}));
    this.Properties.push(new CustomCSSProperty(this,this.Options));



};

sfTextAreaElement.prototype.GenerateInlineElement=function()
{
    var additionalStyle='';
    if(!isNaN(parseFloat(this.Options.Width)))
        additionalStyle='width:'+this.Options.Width+'px'+' !important;';

    if(!isNaN(parseFloat(this.Options.Height)))
        additionalStyle+='height:'+this.Options.Height+'px'+' !important;';

    var disabled="";
    if(this.Options.Disabled=='y')
        disabled='disabled="disabled"';


    var html=  '<div class="rednao_label_container col-sm-3"><label class="rednao_control_label" for="textarea">'+RedNaoEscapeHtml(this.Options.Label)+'</label></div>\
                <div class="redNaoControls col-sm-9">\
                <textarea '+(!isNaN(this.MaxLength)?'maxlength="'+this.MaxLength.toString()+'"':'')+'  '+disabled+' placeholder="'+RedNaoEscapeHtml(this.Options.Placeholder)+'" style="'+additionalStyle+'" name="textarea" class="form-control redNaoTextAreaInput '+(!isNaN(this.MaxLength)?'redNaoTextAreaInputWordCount':'')+'">'+RedNaoEscapeHtml(this.Options.DefaultText)+'</textarea>';
    if(!isNaN(this.MaxLength))
        html+='<span class="smartFormsCharacterCount">'+this.MaxLength.toString()+'</span>';
    html+='</div>';

    return html;
};



sfTextAreaElement.prototype.GetValueString=function()
{
    if(this.IsIgnored())
        return {value:''};
    return  {value:this.JQueryElement.find('.redNaoTextAreaInput').val()};
};

sfTextAreaElement.prototype.SetData=function(data)
{
    this.JQueryElement.find('.redNaoTextAreaInput').val(data.value);
};

sfTextAreaElement.prototype.GetValuePath=function()
{
    return 'formData.'+this.Id+'.value';
};


sfTextAreaElement.prototype.IsValid=function()
{
    if(this.JQueryElement.find('.redNaoTextAreaInput').val()==""&&this.Options.IsRequired=='y')
    {
        this.JQueryElement.addClass('has-error');
        this.AddError('root',this.InvalidInputMessage);
    }
    else
        this.RemoveError('root');
    return this.InternalIsValid();
};

//noinspection JSUnusedLocalSymbols
sfTextAreaElement.prototype.GenerationCompleted=function(jQueryElement)
{
    var self=this;
    this.JQueryElement.find('.redNaoTextAreaInput').change(function(){self.FirePropertyChanged();});
    this.RegisterForFocusEvent(this.JQueryElement.find( '.redNaoTextAreaInput'));
    if(!isNaN(this.MaxLength))
        this.JQueryElement.find(' .redNaoTextAreaInput').bind('keyup keydown',function(){
            var length=rnJQuery(this).val().length;
            var charactersRemaining=self.MaxLength-length;
            var wordCounter=self.GetElementByClassName('smartFormsCharacterCount');
            wordCounter.text(charactersRemaining.toString());

            if(charactersRemaining<=20)
                wordCounter.addClass("smartFormsAlmostFull");
            else
                wordCounter.removeClass("smartFormsAlmostFull");

        });



    if(this.Options.Placeholder_Icon.ClassName!='')
    {
        this.LoadPlaceHolderIcon(jQueryElement.find('.redNaoTextAreaInput'),null,null,this.Options.Placeholder_Icon);
    }
};

/*************************************************************************************Multiple Radio Element ***************************************************************************************************/

function sfMultipleRadioElement(options,serverOptions)
{
    sfFormElementBase.call(this,options,serverOptions);
    this.Title="Multiple Radio";
    var i=undefined;
    if(this.IsNew)
    {
        this.Options.Label="Multiple Radio";
        this.Options.ClassName="rednaomultipleradios";
        this.Options.Orientation='v';
        this.Options.Options=[{label:'Option 1',value:0,sel:'n'},{label:'Option 2',value:0,sel:'n'},{label:'Option 3',value:0,sel:'n'}];
        this.Options.CustomCSS='';
    }else
    {
        this.SetDefaultIfUndefined('CustomCSS','');
        if(RedNaoGetValueOrNull(this.Options.Orientation)==null)
            this.Options.Orientation='v';
        if(this.Options.Options.length>0&&typeof this.Options.Options[0].sel=='undefined')
        {
            this.Options.Options[0].sel='y';
            for(i=1;i<this.Options.Options.length;i++)
                this.Options.Options[i].sel='n';
        }
        if(this.Options.Options.length>0&&typeof this.Options.Options[i]=='string')
        {
            var aux=[];
            for(i=0;i<this.Options.Options.length;i++)
            {
                aux.push({label:this.Options.Options[i]});
            }

            this.Options.Options=aux;
        }
    }


}

sfMultipleRadioElement.prototype=Object.create(sfFormElementBase.prototype);

sfMultipleRadioElement.prototype.CreateProperties=function()
{
    this.Properties.push(new IdProperty(this,this.Options));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Label","Label",{ManipulatorType:'basic'}));
    this.Properties.push(new ArrayProperty(this,this.Options,"Options","Options",{ManipulatorType:'basic',SelectorType:'radio'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"IsRequired","Required",{ManipulatorType:'basic'}));
    this.Properties.push(new ComboBoxProperty(this,this.Options,"Orientation","Orientation",{ManipulatorType:'basic',Values:[{label:'Vertical',value:'v'},{label:'Horizontal',value:'h'}]}));
    this.Properties.push(new CustomCSSProperty(this,this.Options));


};

sfMultipleRadioElement.prototype.GenerateInlineElement=function()
{
    var orientationClass='';
    if(this.Options.Orientation=='h')
        orientationClass='-inline';

    var html=  '<div class="rednao_label_container col-sm-3"><label class="rednao_control_label">'+RedNaoEscapeHtml(this.Options.Label)+'</label></div>\
        <div class="redNaoControls col-sm-9">';

    var checked='';
    for(var i=0;i<this.Options.Options.length;i++)
    {
        if(this.Options.Options[i].sel=='y')
            checked='checked="checked"';
        else
            checked='';
        var itemId=this.Id+"_"+i;
        html+='<div class="radio'+orientationClass+'">\
                    <input id="'+itemId+'" '+checked+' class="redNaoInputRadio" type="radio" name="'+this.Id+'"  value="'+RedNaoEscapeHtml(this.Options.Options[i].value)+'" '+checked+'></input>\
                    <label class="redNaoRadio '+orientationClass+'" for="'+itemId+'">'+RedNaoEscapeHtml(rnJQuery.trim(this.Options.Options[i].label))+
                '</label></div>';

        checked="";

    }

    html+='</div>';
    return html;
};





sfMultipleRadioElement.prototype.GetValueString=function()
{
    if(this.IsIgnored())
    {
        this.amount=0;
        return {value:'',amount:0};
    }
    var jQueryElement=rnJQuery('#'+this.Id).find(':checked');
    if(jQueryElement.length>0)
        this.amount=parseFloat(jQueryElement.val());
    if(isNaN(this.amount))
        this.amount=0;
    return  {value:rnJQuery.trim(jQueryElement.parent().find('label').text()),amount:this.amount};
};

sfMultipleRadioElement.prototype.SetData=function(data)
{
    if(data.value!='')
    {
        var labels=this.JQueryElement.find('.redNaoRadio');
        for(var i=0;i<labels.length;i++)
        {
            if (rnJQuery.trim(rnJQuery(labels[i]).text()) == data.value)
            {
                rnJQuery(labels[i]).parent().find('input:radio').attr('checked','checked').click();
            }
        }
    }
};



sfMultipleRadioElement.prototype.GetValuePath=function()
{
    return 'formData.'+this.Id+'.amount';
};


sfMultipleRadioElement.prototype.IsValid=function()
{
    if(rnJQuery('#'+this.Id).find(':checked').length<=0&&this.Options.IsRequired=='y')
    {
        this.AddError('root',this.InvalidInputMessage);
    }
    else
        this.RemoveError('root');

    return this.InternalIsValid();
};

sfMultipleRadioElement.prototype.ClearInvalidStyle=function()
{

};


//noinspection JSUnusedLocalSymbols
sfMultipleRadioElement.prototype.GenerationCompleted=function(jQueryElement)
{
    var self=this;
    jQueryElement.find('.redNaoInputRadio').change(function(){self.FirePropertyChanged();});
};

/*************************************************************************************Multiple Checkbox Element ***************************************************************************************************/

function sfMultipleCheckBoxElement(options,serverOptions)
{
    sfFormElementBase.call(this,options,serverOptions);
    this.Title="Multiple Checkboxes";
    var i=undefined;
    if(this.IsNew)
    {
        this.Options.Label="Multiple Checkbox";
        this.Options.ClassName="rednaomultiplecheckboxes";
        this.Options.Orientation='v';
        this.Options.Options=[{label:'Check 1',value:0,sel:'n'},{label:'Check 2',value:0,sel:'n'},{label:'Check 3',value:0,sel:'n'}];
        this.Options.CustomCSS='';
    }else
    {
        this.SetDefaultIfUndefined('CustomCSS','');
        if(RedNaoGetValueOrNull(this.Options.Orientation)==null)
            this.Options.Orientation='v';
        if(this.Options.Options.length>0&&typeof this.Options.Options[0].sel=='undefined')
        {
            for(i=0;i<this.Options.Options.length;i++)
                this.Options.Options[i].sel='n';
        }

        if(this.Options.Options.length>0&&typeof this.Options.Options[i]=='string')
        {
            var aux=[];
            for(i=0;i<this.Options.Options.length;i++)
            {
                aux.push({label:this.Options.Options[i]});
            }

            this.Options.Options=aux;
        }
    }


}

sfMultipleCheckBoxElement.prototype=Object.create(sfFormElementBase.prototype);

sfMultipleCheckBoxElement.prototype.CreateProperties=function()
{
    this.Properties.push(new IdProperty(this,this.Options));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Label","Label",{ManipulatorType:'basic'}));
    this.Properties.push(new ArrayProperty(this,this.Options,"Options","Options",{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"IsRequired","Required",{ManipulatorType:'basic'}));
    this.Properties.push(new ComboBoxProperty(this,this.Options,"Orientation","Orientation",{ManipulatorType:'basic',Values:[{label:'Vertical',value:'v'},{label:'Horizontal',value:'h'}]}));
    this.Properties.push(new CustomCSSProperty(this,this.Options));
};

sfMultipleCheckBoxElement.prototype.GenerateInlineElement=function()
{
    var orientationClass='';
    if(this.Options.Orientation=='h')
        orientationClass='-inline';

    var html=  '<div class="rednao_label_container col-sm-3"><label class="rednao_control_label">'+RedNaoEscapeHtml(this.Options.Label)+'</label></div>\
        <div class="redNaoControls col-sm-9">';

    var checked='';
    for(var i=0;i<this.Options.Options.length;i++)
    {
        if(this.Options.Options[i].sel=='y')
            checked='checked="checked"';
        else
            checked='';
        var itemId=this.Id+"_"+i;
        html+='<div class="checkbox'+orientationClass+'">\
                    <input type="checkbox" class="redNaoInputCheckBox" id="'+itemId+'" name="'+this.Id+'"  value="'+RedNaoEscapeHtml(this.Options.Options[i].value)+'" '+checked+'/>\
                    <label class="redNaoCheckBox redNaoCheckBox'+orientationClass+'" for="'+itemId+'">'+RedNaoEscapeHtml(this.Options.Options[i].label)+'\
                </label></div>';

        checked="";

    }

    html+='</div>';
    return html;
};






sfMultipleCheckBoxElement.prototype.GetValueString=function()
{
    this.amount=0;
    if(this.IsIgnored())
        return {selectedValues:[]};
    var jQueryElement=rnJQuery('#'+this.Id).find(':checked');
    var data={};
    data.selectedValues=[];
    if(jQueryElement.length>0)
    {
        for(var i=0;i<jQueryElement.length;i++)
        {
            if(jQueryElement.length>0)
                this.amount=parseFloat(rnJQuery(jQueryElement[i]).val());
            if(isNaN(this.amount))
                this.amount=0;
            data.selectedValues.push({value:rnJQuery.trim(rnJQuery(jQueryElement[i]).parent().find('label').text()),amount:this.amount,label:rnJQuery.trim(rnJQuery(jQueryElement[i]).parent().find('label').text())});
        }
    }


    return data;

};


sfMultipleCheckBoxElement.prototype.SetData=function(data)
{
    if(typeof data.selectedValues=='undefined')
        return;

    this.JQueryElement.find('input:checkbox').removeAttr('checked');

    var labels=this.JQueryElement.find('.redNaoCheckBox');
    for(var i=0;i<data.selectedValues.length;i++)
    {
        for(var t=0;t<labels.length;t++)
        {
            if (rnJQuery.trim(rnJQuery(labels[t]).text()) == data.selectedValues[i].value)
            {
                rnJQuery(labels[t]).parent().find('input:checkbox').attr('checked','checked').click();
            }
        }
    }
};



sfMultipleCheckBoxElement.prototype.GetValuePath=function()
{
    return 'RedNaoGetValueFromArray(formData.'+this.Id+'.selectedValues)';
};


sfMultipleCheckBoxElement.prototype.IsValid=function()
{
    if(rnJQuery('#'+this.Id).find(':checked').length<=0&&this.Options.IsRequired=='y')
    {
        this.AddError('root',this.InvalidInputMessage);
    }else
        this.RemoveError('root');
    return this.InternalIsValid();
};

sfMultipleCheckBoxElement.prototype.ClearInvalidStyle=function()
{

};



//noinspection JSUnusedLocalSymbols
sfMultipleCheckBoxElement.prototype.GenerationCompleted=function(jQueryElement)
{
    var self=this;
    jQueryElement.find('.redNaoInputCheckBox').change(function(){
        self.FirePropertyChanged();
    });
};


/*************************************************************************************Select Basic Element ***************************************************************************************************/

function sfSelectBasicElement(options,serverOptions)
{
    sfFormElementBase.call(this,options,serverOptions);
    this.Title="Select Basic";
    var i=undefined;
    if(this.IsNew)
    {
        this.Options.Label="Select Basic";
        this.Options.ClassName="rednaoselectbasic";
        this.Options.DefaultText="Select a value";
        this.Options.Options=[{label:'Option 1',value:0,sel:'n'},{label:'Option 2',value:0,sel:'n'},{label:'Option',value:0,sel:'n'}];
        this.SetDefaultIfUndefined('Width','');
        this.Options.CustomCSS='';
        this.Options.DefaultText_Icon={ClassName:'',Orientation:''};

    }else
    {
        this.SetDefaultIfUndefined('CustomCSS','');
        this.SetDefaultIfUndefined('Width','');
        this.SetDefaultIfUndefined("DefaultText","");
        this.SetDefaultIfUndefined('DefaultText_Icon',{ClassName:''});
        if(this.Options.Options.length>0&&typeof this.Options.Options[0].sel=='undefined')
        {
            this.Options.Options[0].sel='y';
            for(i=1;i<this.Options.Options.length;i++)
                this.Options.Options[i].sel='n';
        }
        if(this.Options.Options.length>0&&typeof this.Options.Options[i]=='string')
        {
            var aux=[];
            for(i=0;i<this.Options.Options.length;i++)
            {
                aux.push({label:this.Options.Options[i]});
            }

            this.Options.Options=aux;
        }
    }


}

sfSelectBasicElement.prototype=Object.create(sfFormElementBase.prototype);

sfSelectBasicElement.prototype.CreateProperties=function()
{
    this.Properties.push(new IdProperty(this,this.Options));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Label","Label",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"DefaultText","Default text",{ManipulatorType:'basic',IconOptions:{Type:'leftAndRight'}}));
    this.Properties.push(new ArrayProperty(this,this.Options,"Options","Options",{ManipulatorType:'basic',SelectorType:'radio'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Width","Width",{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"IsRequired","Required",{ManipulatorType:'basic'}));
    this.Properties.push(new CustomCSSProperty(this,this.Options));



};

sfSelectBasicElement.prototype.GenerateInlineElement=function()
{
    var additionalStyle='';
    if(!isNaN(parseFloat(this.Options.Width)))
        additionalStyle='width:'+this.Options.Width+'px'+' !important;';


    var html=  '<div class="rednao_label_container col-sm-3"><label class="rednao_control_label">'+RedNaoEscapeHtml(this.Options.Label)+'</label></div>\
        <div class="redNaoControls col-sm-9">\
        <select style="'+additionalStyle+'" name="'+this.GetPropertyName()+'" class="redNaoSelect form-control">';

    var selected='';
    var i=undefined;
    if(this.Options.DefaultText!="")
    {
        selected='selected="selected"';
        for(i=0;i<this.Options.Options.length;i++)
        {
            if(this.Options.Options[i].sel=='y')
                selected='';
        }
        html+='<option   value="redNaoNone" '+selected+'>'+RedNaoEscapeHtml(this.Options.DefaultText)+'</opton>';
    }



    selected='';
    for(i=0;i<this.Options.Options.length;i++)
    {
        if(this.Options.Options[i].sel=='y')
            selected='selected="selected"';
        else
            selected='';

        html+='<option   value="'+RedNaoEscapeHtml(this.Options.Options[i].value)+'" '+selected+'>'+RedNaoEscapeHtml(this.Options.Options[i].label)+'</opton>';

        selected="";

    }
    html+='</select></div>';
    return html;
};



sfSelectBasicElement.prototype.GetValueString=function()
{
    if(this.IsIgnored())
    {
        this.amount=0;
        return {value:'',amount:0};
    }
    var jQueryElement=rnJQuery('#'+this.Id+ ' .redNaoSelect option:selected');
    if(jQueryElement.length>0)
        this.amount=parseFloat(jQueryElement.val());
    if(isNaN(this.amount))
        this.amount=0;

    if(jQueryElement.text()==this.Options.DefaultText)
    {
        this.amount=0;
        return {value:'',amount:0};
    }
    return  {value:jQueryElement.text(),amount:this.amount};
};

sfSelectBasicElement.prototype.SetData=function(data)
{
    var options=this.JQueryElement.find('option');
    for(var i=0;i<options.length;i++)
    {
        var $option=rnJQuery(options[i]);
        $option.removeAttr('selected');
        if($option.text()==data.value)
            $option.attr('selected','selected');
    }
};



sfSelectBasicElement.prototype.GetValuePath=function()
{
    return 'formData.'+this.Id+'.amount';
};


sfSelectBasicElement.prototype.IsValid=function()
{
    if(this.Options.IsRequired=='y'&&(this.GetValueString().value==''||rnJQuery('#'+this.Id+ ' .redNaoSelect option:selected').length==0))
    {
        rnJQuery('#'+this.Id).addClass('has-error');
        this.AddError('root',this.InvalidInputMessage);
    }
    else
        this.RemoveError('root');

    return this.InternalIsValid();
};


//noinspection JSUnusedLocalSymbols
sfSelectBasicElement.prototype.GenerationCompleted=function(jQueryElement)
{
    var self=this;
    rnJQuery('#'+this.Id+ ' .redNaoSelect').change(function(){self.FirePropertyChanged();});
    this.RegisterForFocusEvent(this.JQueryElement.find( '.redNaoSelect'));
    if(this.Options.DefaultText_Icon.ClassName!='')
    {
        this.LoadPlaceHolderIcon(jQueryElement.find('.redNaoSelect'),null,null,this.Options.DefaultText_Icon);
    }



};

/*************************************************************************************Donation Button***************************************************************************************************/



function sfDonationButtonElement(options,serverOptions)
{
    sfFormElementBase.call(this,options,serverOptions);
    this.Title="Donation Button";

    if(this.IsNew)
    {
        this.Options.Label="Donation Button";
        this.Options.ClassName="rednaodonationbutton";
        this.Options.Image='https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif';
        this.Options.CustomCSS='';
    }else
    {
        this.SetDefaultIfUndefined('CustomCSS','');
        if(typeof this.Options.Image=='undefined')
            this.Options.Image='https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif';
    }


}

sfDonationButtonElement.prototype=Object.create(sfFormElementBase.prototype);

sfDonationButtonElement.prototype.CreateProperties=function()
{
    this.Properties.push(new IdProperty(this,this.Options));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Label","Label",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Image","Image Url",{ManipulatorType:'basic'}));
    this.Properties.push(new CustomCSSProperty(this,this.Options));
};

sfDonationButtonElement.prototype.GenerateInlineElement=function()
{
    return '<div class="rednao_label_container col-sm-3"></div>'+
            '<div class="redNaoControls col-sm-9">' +
                '<input type="image" class="redNaoDonationButton" src="'+this.Options.Image+'" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">' +
           '</div>';
};


sfDonationButtonElement.prototype.GetValueString=function()
{
    return '';

};

sfDonationButtonElement.prototype.StoresInformation=function()
{
    return false;
};


/************************************************************************************* Recurrence Element  ***************************************************************************************************/



function sfRecurrenceElement(options,serverOptions)
{
    sfFormElementBase.call(this,options,serverOptions);
    this.Title="Donation Button";

    if(this.IsNew)
    {
        this.Options.Label="Recurrence";
        this.Options.ClassName="rednaodonationrecurrence";
        this.Options.ShowOneTime='y';
        this.Options.ShowDaily='y';
        this.Options.ShowWeekly='y';
        this.Options.ShowMonthly='y';
        this.Options.ShowYearly='y';
        this.Options.CustomCSS='';
    }else
    {
        this.SetDefaultIfUndefined('CustomCSS','');
    }


}

sfRecurrenceElement.prototype=Object.create(sfFormElementBase.prototype);

sfRecurrenceElement.prototype.CreateProperties=function()
{
    this.Properties.push(new IdProperty(this,this.Options));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Label","Label",{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"ShowOneTime","Show one time option",{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"ShowDaily","Show daily option",{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"ShowWeekly","Show weekly option",{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"ShowMonthly","Show monthly option",{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"ShowYearly","Show yearly option",{ManipulatorType:'basic'}));
    this.Properties.push(new CustomCSSProperty(this,this.Options));
};

sfRecurrenceElement.prototype.GenerateInlineElement=function()
{
    var html= '<div class="rednao_label_container col-sm-3">' +
                    '<label class="rednao_control_label">'+RedNaoEscapeHtml(this.Options.Label)+'</label>' +
                    '</div>' +
              '<div class="redNaoControls col-sm-9">' +
              '<select class="redNaoSelect redNaoRecurrence">';
    var selected='selected="selected"';

    if(this.Options.ShowOneTime=='y')
    {
         html+='<option value="OT" '+selected+'>One Time</option>';
        selected='';
    }

    if(this.Options.ShowDaily=='y')
    {
        html+='<option value="D" '+selected+'>Daily</option>';
        selected='';
    }

    if(this.Options.ShowWeekly=='y')
    {
        html+='<option value="W" '+selected+'>Weekly</option>';
        selected='';
    }

    if(this.Options.ShowMonthly=='y')
    {
        html+='<option value="M" '+selected+'>Monthly</option>';
        selected='';
    }

    if(this.Options.ShowYearly=='y')
    {
        html+='<option value="Y" '+selected+'>Yearly</option>';
        selected='';
    }

    html+='</select></div>';

    return html;
};



sfRecurrenceElement.prototype.GetValueString=function()
{
    if(this.IsIgnored())
        return {value:''};
    var jQueryElement=rnJQuery('#'+this.Id+ ' .redNaoSelect option:selected');
    return {value:jQueryElement.val()};

};

sfRecurrenceElement.prototype.SetData=function(data)
{
   this.JQueryElement.find('.redNaoSelect').val(data.value);
};


sfRecurrenceElement.prototype.GetValuePath=function()
{
    return 'formData.'+this.Id+'.value';
};


//noinspection JSUnusedLocalSymbols
sfRecurrenceElement.prototype.GenerationCompleted=function(jQueryElement)
{
    var self=this;
    this.JQueryElement.find('.redNaoSelect').change(function(){self.FirePropertyChanged();});
    this.RegisterForFocusEvent(this.JQueryElement.find( '.redNaoSelect'));
};


/************************************************************************************* Submission Button ***************************************************************************************************/



function sfRedNaoSubmissionButton(options,serverOptions)
{
    sfFormElementBase.call(this,options,serverOptions);
    this.Title="Submit Button";

    if(this.IsNew)
    {
        this.Options.ClassName="rednaosubmissionbutton";
        this.Options.ButtonText="Submit";
        this.Options.CustomCSS='';
        this.Options.Icon={ClassName:''};
        this.Options.Animated='y';

    }else{
        this.SetDefaultIfUndefined('CustomCSS','');
        this.SetDefaultIfUndefined('Icon',{ClassName:''});
        this.SetDefaultIfUndefined('Animated','n');
    }

    this.RegisterForSubmitEvents();

}

sfRedNaoSubmissionButton.prototype=Object.create(sfFormElementBase.prototype);

sfRedNaoSubmissionButton.prototype.RegisterForSubmitEvents=function()
{

    var self=this;
    RedNaoEventManager.Subscribe('FormSubmitted',function(data)
    {
        if(data.Generator.form_id!=self.FormId)
            return;


        if(self.Options.Animated=='y')
            self.JQueryElement.find('button').RNWait('start');
        else
            self.JQueryElement.find('button').attr('disabled','disabled');
    });

    RedNaoEventManager.Subscribe('FormSubmittedCompleted',function(data)
    {
        if(data.Generator.form_id!=self.FormId)
            return;


        if(self.Options.Animated=='y')
            self.JQueryElement.find('button').RNWait('stop');
        else
            self.JQueryElement.find('button').removeAttr('disabled','disabled');
    });
};

sfRedNaoSubmissionButton.prototype.GetFriendlyName=function()
{
    return this.Options.ButtonText;
};

sfRedNaoSubmissionButton.prototype.CreateProperties=function()
{
    this.Properties.push(new SimpleTextProperty(this,this.Options,"ButtonText","Button Text",{ManipulatorType:'basic'}));
    this.Properties.push(new CustomCSSProperty(this,this.Options));
    this.Properties.push(new IconProperty(this,this.Options,'Icon','Icon',{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"Animated","Animated",{ManipulatorType:'basic'}));
};

sfRedNaoSubmissionButton.prototype.GenerateInlineElement=function()
{
    var icon='';
    if(this.Options.Icon.ClassName!='')
        icon='<span class="'+RedNaoEscapeHtml(this.Options.Icon.ClassName)+' "></span>';

    return '<div class="rednao_label_container col-sm-3"></div><div class="redNaoControls col-sm-9"><button class="redNaoSubmitButton btn btn-normal ladda-button" >'+
                icon+
                '<span class="ladda-label">'+RedNaoEscapeHtml(this.Options.ButtonText)+'</span>'+
            '</button></div>';
};


sfRedNaoSubmissionButton.prototype.GetValueString=function()
{
    return '';

};

sfRedNaoSubmissionButton.prototype.StoresInformation=function()
{
    return false;
};



/************************************************************************************* Date Picker ***************************************************************************************************/



function sfRedNaoDatePicker(options,serverOptions)
{
    sfFormElementBase.call(this,options,serverOptions);
    this.Title="Date Picker";

    if(this.IsNew)
    {
        this.Options.ClassName="rednaodatepicker";
        this.Options.Label="Date";
        this.Options.DateFormat="MM-dd-yy";
        this.Options.Value='';
        this.Options.Icon={ClassName:''};
        this.Options.ReadOnly='n';
        this.Options.Value='';
        this.Options.CustomCSS='';
        this.Options.IsRequired='n';
        this.Options.ShowMonthPicker='n';
        this.Options.ShowYearPicker='n';
    }else{
        this.SetDefaultIfUndefined('Icon',{ClassName:''});
        this.SetDefaultIfUndefined('ReadOnly','n');
        this.SetDefaultIfUndefined('Value','');
        this.SetDefaultIfUndefined('CustomCSS','');
        this.SetDefaultIfUndefined('IsRequired','n');
        this.SetDefaultIfUndefined('ShowMonthPicker','n');
        this.SetDefaultIfUndefined('ShowYearPicker','n');
    }

}

sfRedNaoDatePicker.prototype=Object.create(sfFormElementBase.prototype);

sfRedNaoDatePicker.prototype.CreateProperties=function()
{
    this.Properties.push(new IdProperty(this,this.Options));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Label","Label",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"DateFormat","Date Format",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Value","Value",{ManipulatorType:'basic',RefreshFormData:true}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"ShowMonthPicker","Show Month Picker",{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"ShowYearPicker","Show Year Picker",{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"ReadOnly","Read Only",{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"IsRequired","Required",{ManipulatorType:'basic'}));
    this.Properties.push(new CustomCSSProperty(this,this.Options));
    this.Properties.push(new IconProperty(this,this.Options,'Icon','Icon',{ManipulatorType:'basic'}));
};

sfRedNaoDatePicker.prototype.GenerateInlineElement=function()
{
    var startOfInput='';
    var endOfInput='';

    if(this.Options.Icon.ClassName!='')
    {
        startOfInput='<div class="rednao-input-prepend input-group"><span class="redNaoPrepend input-group-addon prefix '+RedNaoEscapeHtml(this.Options.Icon.ClassName)+' "></span>';
        endOfInput='</div>';
    }


    return '<div class="rednao_label_container col-sm-3">' +
                '<label class="rednao_control_label ">'+RedNaoEscapeHtml(this.Options.Label)+'</label>' +
            '</div>' +
            '<div class="redNaoControls col-sm-9">' +
                startOfInput+
                '<input type="text" class="form-control redNaoDatePicker"  />' +
                endOfInput+
            '</div>';

};


sfRedNaoDatePicker.prototype.GetValueString=function()
{
    if(this.IsIgnored())
        return {value:'',formattedValue:''};
    var selectedDate= this.JQueryElement.find('.redNaoDatePicker').datepicker('getDate');
    var dateLabel='';
    var numericalValues=0;
    if(selectedDate==null)
        selectedDate ="";
    else
    {
        selectedDate=selectedDate.getFullYear()+'-'+(selectedDate.getMonth()+1)+'-'+selectedDate.getDate();
        dateLabel=this.JQueryElement.find('.redNaoDatePicker').datepicker({dateFormat: this.Options.DateFormat}).val();
        numericalValues=new Date(selectedDate).getTime();
    }
    return {value:selectedDate,formattedValue:dateLabel,numericalValue:numericalValues};

};



sfRedNaoDatePicker.prototype.SetData=function(data)
{
    this.JQueryElement.find('.redNaoDatePicker').datepicker('setDate',data.formattedValue);
};


sfRedNaoDatePicker.prototype.GetValuePath=function()
{
    return 'formData.'+this.Id+'.value';
};



sfRedNaoDatePicker.prototype.StoresInformation=function()
{
    return true;
};

//noinspection JSUnusedLocalSymbols
sfRedNaoDatePicker.prototype.GenerationCompleted=function(jQueryElement)
{
    var self=this;
    this.JQueryElement.find('.redNaoDatePicker').datepicker({
        dateFormat:this.Options.DateFormat,
        beforeShow: function() {
            rnJQuery('#ui-datepicker-div').wrap('<div class="smartFormsSlider"></div>');
            self.JQueryElement.addClass('is-focused');
        },
        onClose: function() {
            rnJQuery('#ui-datepicker-div').unwrap();
            self.JQueryElement.removeClass('is-focused');
        },
        changeMonth:self.Options.ShowMonthPicker=="y",
        changeYear:self.Options.ShowYearPicker=="y"
    });
    rnJQuery('#ui-datepicker-div').css('display','none');

    if(rnJQuery.trim(this.Options.Value)!='')
        this.JQueryElement.find('.redNaoDatePicker').datepicker('setDate',this.Options.Value);


    if(this.Options.ReadOnly=='y')
        this.JQueryElement.find('.redNaoDatePicker').datepicker('disable');

    var self=this;
    this.JQueryElement.change(function(){self.FirePropertyChanged();});


};

sfRedNaoDatePicker.prototype.IsValid=function()
{
    var selectedDate= this.JQueryElement.find('.redNaoDatePicker').datepicker('getDate');
    if(selectedDate==null&&this.Options.IsRequired=='y')
        this.AddError('root',this.InvalidInputMessage);
    else
        this.RemoveError('root');

    return this.InternalIsValid();
};

/************************************************************************************* Name ***************************************************************************************************/

function sfRedNaoName(options,serverOptions)
{
    sfFormElementBase.call(this,options,serverOptions);
    this.Title="Text Input";
    if(this.IsNew)
    {
        this.Options.ClassName="rednaoname";
        this.Options.Label="Name";
        this.Options.FirstNamePlaceholder="First Name";
        this.Options.LastNamePlaceholder="Last Name";
        this.Options.FirstNameValue="";
        this.Options.LastNameValue="";
        this.Options.ReadOnly='n';
        this.Options.Icon={ClassName:''};
        this.Options.CustomCSS='';
        this.Options.IsRequired='n';
    }else
    {
        this.SetDefaultIfUndefined('Icon',{ClassName:''});
        this.SetDefaultIfUndefined('CustomCSS','');
        this.SetDefaultIfUndefined('IsRequired','n');
    }





}

sfRedNaoName.prototype=Object.create(sfFormElementBase.prototype);

sfRedNaoName.prototype.CreateProperties=function()
{
    this.Properties.push(new IdProperty(this,this.Options));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Label","Label",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"FirstNamePlaceholder","First name placeholder",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"LastNamePlaceholder","Last name placeholder",{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"IsRequired","Required",{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"ReadOnly","Read Only",{ManipulatorType:'basic'}));
    this.Properties.push(new CustomCSSProperty(this,this.Options));
    this.Properties.push(new IconProperty(this,this.Options,'Icon','Icon',{ManipulatorType:'basic'}));
};

sfRedNaoName.prototype.GenerateInlineElement=function()
{

    var startOfInput='';
    var endOfInput='';

    if(this.Options.Icon.ClassName!='')
    {
        startOfInput='<div class="rednao-input-prepend input-group"><span class="redNaoPrepend input-group-addon prefix '+RedNaoEscapeHtml(this.Options.Icon.ClassName)+' "></span>';
        endOfInput='</div>';
    }

    var firstNameLabel='';
    var lastNameLabel='';

    if(this.Options.FirstNamePlaceholder!='')
        firstNameLabel='<br/><label class="redNaoHelper">'+this.Options.FirstNamePlaceholder+'</label>';
    if(this.Options.LastNamePlaceholder!='')
        lastNameLabel='<br/><label class="redNaoHelper">'+this.Options.LastNamePlaceholder+'</label>';

    return '<div class="rednao_label_container col-sm-3"><label class="rednao_control_label" >'+RedNaoEscapeHtml(this.Options.Label)+'</label></div>'+
                 '<div class="redNaoControls col-sm-9">'+
                    '<div class="form-inline firstAndLastName">'+
                        '<div class="redNaoFirstNameDiv redNaoTwoColumnsDiv form-group col-sm-6">'+
                            startOfInput+
                            '<input '+(this.Options.ReadOnly=='y'?'disabled="disabled"':"")+' name="'+this.GetPropertyName()+'_firstname" type="text" placeholder="'+RedNaoEscapeHtml(this.Options.FirstNamePlaceholder)+'" class="form-control redNaoInputText redNaoTwoColumns redNaoInputFirstName '+(this.Options.ReadOnly=='y'?'redNaoDisabledElement':"")+'"/>'+
                            endOfInput+ /*firstNameLabel+*/
                        '</div>    '+
                        '<div class="redNaoLastNameDiv redNaoTwoColumnsDiv form-group col-sm-6">'+
                            '<input '+(this.Options.ReadOnly=='y'?'disabled="disabled"':"")+' name="'+this.GetPropertyName()+'_lastname" type="text" placeholder="'+RedNaoEscapeHtml(this.Options.LastNamePlaceholder)+'" class="form-control redNaoInputText redNaoTwoColumns redNaoInputLastName '+(this.Options.ReadOnly=='y'?'redNaoDisabledElement':"")+'">'+ /*lastNameLabel+*/
                        '</div>'+
                     '</div>   '+
               '<div>     '+
    '</div>';
};


sfRedNaoName.prototype.GetValueString=function()
{
    if(this.IsIgnored())
        return {firstName:'',lastName:''};
    return {
        firstName:rnJQuery('#'+this.Id+ ' .redNaoInputFirstName').val(),
        lastName:rnJQuery('#'+this.Id+ ' .redNaoInputLastName').val()

    };


};


sfRedNaoName.prototype.SetData=function(data)
{
    this.JQueryElement.find('.redNaoInputFirstName').val(data.firstName);
    this.JQueryElement.find('.redNaoInputLastName').val(data.lastName);
};



sfRedNaoName.prototype.GetValuePath=function()
{
    return 'formData.'+this.Id+'.firstName+" "'+'formData.'+this.Id+'.lastName';
};


sfRedNaoName.prototype.IsValid=function()
{
    if(this.Options.IsRequired=='y'&&(rnJQuery('#'+this.Id+ ' .redNaoInputFirstName').val()==""||rnJQuery('#'+this.Id+ ' .redNaoInputLastName').val()==""))
    {
        var firstNameJQuery=rnJQuery('#'+this.Id+ ' .redNaoInputFirstName');
        var lastNameJQuery=rnJQuery('#'+this.Id+ ' .redNaoInputLastName');

        if(firstNameJQuery.val()=="")
            firstNameJQuery.parent().addClass('has-error');

        if(lastNameJQuery.val()=="")
            lastNameJQuery.parent().addClass('has-error');

        this.AddError('root',this.InvalidInputMessage);
    }
    else
        this.RemoveError('root');

    return this.InternalIsValid();
};

//noinspection JSUnusedLocalSymbols
sfRedNaoName.prototype.GenerationCompleted=function(jQueryElement)
{
    var self=this;
    rnJQuery('#'+this.Id+ ' .redNaoInputFirstName,#'+this.Id+ ' .redNaoInputLastName').change(function(){self.FirePropertyChanged();});
    this.RegisterForFocusEvent(this.JQueryElement.find( '.redNaoInputFirstName'));
    this.RegisterForFocusEvent(this.JQueryElement.find( '.redNaoInputLastName'));
};



/************************************************************************************* Address ***************************************************************************************************/

function sfRedNaoAddress(options,serverOptions)
{
    sfFormElementBase.call(this,options,serverOptions);
    this.Title="Text Input";
    this.Countries=["Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burma", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros", "Congo, Democratic Republic", "Congo, Republic of the", "Costa Rica", "Cote d'Ivoire", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Fiji", "Finland", "France", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Greenland", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, North", "Korea, South", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", "Moldova", "Mongolia", "Morocco", "Monaco", "Mozambique", "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Norway", "Oman", "Pakistan", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Qatar", "Romania", "Russia", "Rwanda", "Samoa", "San Marino", " Sao Tome", "Saudi Arabia", "Senegal", "Serbia and Montenegro", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "Spain", "Sri Lanka", "Sudan", "Suriname", "Swaziland", "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Togo", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"];
    if(this.IsNew)
    {
        this.Options.ClassName="rednaoaddress";
        this.Options.Label="Address";
        this.Options.StreetAddress1Label="Street Address";
        this.Options.StreetAddress2Label="Street Address 2";
        this.Options.CityLabel="City";
        this.Options.StateLabel="State";
        this.Options.ZipLabel='Zip';
        this.Options.CountryLabel='Country';
        this.Options.DefaultCountry="United States";

        this.Options.ShowStreetAddress1="y";
        this.Options.ShowStreetAddress2="y";
        this.Options.ShowCity="y";
        this.Options.ShowState="y";
        this.Options.ShowZip='y';
        this.Options.ShowCountry='y';
        this.Options.Icon={ClassName:''};
        this.Options.CustomCSS='';
    }else
    {
        this.SetDefaultIfUndefined("DefaultCountry","United States");
        this.SetDefaultIfUndefined('Icon',{ClassName:''});
        this.SetDefaultIfUndefined('CustomCSS','');
    }
}

sfRedNaoAddress.prototype=Object.create(sfFormElementBase.prototype);

sfRedNaoAddress.prototype.GetCountriesForPropertyConfiguration = function () {
    var countriesToReturn=new Array(this.Countries.length);
    for(var i=0;i<this.Countries.length;i++)
    {
        countriesToReturn[i]={value:this.Countries[i],label:this.Countries[i]};
    }

    return countriesToReturn;
};
sfRedNaoAddress.prototype.CreateProperties=function()
{
    this.Properties.push(new IdProperty(this,this.Options));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Label","Label",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"StreetAddress1Label","Street Address Label",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"StreetAddress2Label","Street Address 2 Label",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"CityLabel","City Label",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"StateLabel","State Label",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"ZipLabel","Zip Label",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"CountryLabel","Country Label",{ManipulatorType:'basic'}));
    this.Properties.push(new ComboBoxProperty(this,this.Options,"DefaultCountry",this.Translations.DefaultCountry,{ManipulatorType:'basic',Values:this.GetCountriesForPropertyConfiguration()}));

    this.Properties.push(new CheckBoxProperty(this,this.Options,"ShowStreetAddress1","Show Street Address",{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"ShowStreetAddress2","Show Street Address 2",{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"ShowCity","Show City",{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"ShowState","Show State",{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"ShowZip","Show Zip",{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"ShowCountry","Show Country",{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"IsRequired","Required",{ManipulatorType:'basic'}));
    this.Properties.push(new CustomCSSProperty(this,this.Options));
    this.Properties.push(new IconProperty(this,this.Options,'Icon','Icon',{ManipulatorType:'basic'}));
};

sfRedNaoAddress.prototype.GenerateInlineElement=function()
{
    var StreetAddress1Label='';
    var StreetAddress2Label='';
    var CityLabel='';
    var StateLabel='';
    var ZipLabel='';
    var CountryLabel='';
    var isFirstElement=true;

    var startOfInput='';
    var endOfInput='';
    if(this.Options.Icon.ClassName!='')
    {
        startOfInput='<div class="rednao-input-prepend input-group"><span class="redNaoPrepend input-group-addon prefix '+RedNaoEscapeHtml(this.Options.Icon.ClassName)+' "></span>';
        endOfInput='</div>';
    }


    if(this.Options.StreetAddress1Label!='')
        StreetAddress1Label='<label class="redNaoHelper">'+RedNaoEscapeHtml(this.Options.StreetAddress1Label)+'</label>';

    if(this.Options.StreetAddress2Label!='')
        StreetAddress2Label='<label class="redNaoHelper">'+RedNaoEscapeHtml(this.Options.StreetAddress2Label)+'</label>';

    if(this.Options.CityLabel!='')
        CityLabel='<br/><label class="redNaoHelper">'+RedNaoEscapeHtml(this.Options.CityLabel)+'</label>';

    if(this.Options.StateLabel!='')
        StateLabel='<br/><label class="redNaoHelper">'+RedNaoEscapeHtml(this.Options.StateLabel)+'</label>';

    if(this.Options.ZipLabel!='')
        ZipLabel='<br/><label class="redNaoHelper">'+RedNaoEscapeHtml(this.Options.ZipLabel)+'</label>';

    if(this.Options.CountryLabel!='')
        CountryLabel='<br/><label class="redNaoHelper">'+RedNaoEscapeHtml(this.Options.CountryLabel)+'</label>';




    var html= '<div class="rednao_label_container col-sm-3"><label class="rednao_control_label" >'+RedNaoEscapeHtml(this.Options.Label)+'</label></div>\
                <div class="redNaoControls col-sm-9">';
                if(this.Options.ShowStreetAddress1=='y')
                    html+='<div class="redNaoStreetAddress1Div form-group">'+
                        startOfInput+'<input '+(this.Options.ReadOnly=='y'?'disabled="disabled"':"")+' name="'+this.GetPropertyName()+'_streetaddress1" type="text" placeholder="'+RedNaoEscapeHtml(this.Options.StreetAddress1Label)+'" class="form-control redNaoInputText redNaoOneColumn redNaoStreetAddress1 '+(this.Options.ReadOnly=='y'?'redNaoDisabledElement':"")+'"/>'+endOfInput+ /* StreetAddress1Label+'\*/
                    '</div>';
                if(this.Options.ShowStreetAddress2=='y')
                    html+='<div class="redNaoStreetAddress2Div form-group">\
                        <input '+(this.Options.ReadOnly=='y'?'disabled="disabled"':"")+' name="'+this.GetPropertyName()+'_streetaddress2" type="text" placeholder="'+RedNaoEscapeHtml(this.Options.StreetAddress2Label)+'" class="form-control redNaoInputText redNaoOneColumn redNaoStreetAddress2 '+(this.Options.ReadOnly=='y'?'redNaoDisabledElement':"")+'"/>'+/*+StreetAddress2Label+'\*/
                    '</div>';

                html+="<div><div class='form-inline cityAndState'>";
                if(this.Options.ShowCity=='y')
                    html+='<div class="form-group col-sm-6">\
                        <input '+(this.Options.ReadOnly=='y'?'disabled="disabled"':"")+' name="'+this.GetPropertyName()+'_city" type="text" placeholder="'+RedNaoEscapeHtml(this.Options.CityLabel)+'" class="form-control redNaoInputText redNaoTwoColumns redNaoCity '+(this.Options.ReadOnly=='y'?'redNaoDisabledElement':"")+'"/>'+/*'+CityLabel+'\*/
                    '</div>';
                if(this.Options.ShowState=='y')
                    html+='<div class="form-group col-sm-6">\
                          <input '+(this.Options.ReadOnly=='y'?'disabled="disabled"':"")+' name="'+this.GetPropertyName()+'state" type="text" placeholder="'+RedNaoEscapeHtml(this.Options.StateLabel)+'" class="form-control redNaoInputText redNaoTwoColumns redNaoState '+(this.Options.ReadOnly=='y'?'redNaoDisabledElement':"")+'"/>'+/*+StateLabel+'\*/
                    '</div>';
                html+='</div></div>';

                html+="<div class='form-inline zipAndCountry'>";
                if(this.Options.ShowZip=='y')
                    html+='<div class="form-group col-sm-6">\
                                    <input '+(this.Options.ReadOnly=='y'?'disabled="disabled"':"")+' name="'+this.GetPropertyName()+'zip" type="text" placeholder="'+RedNaoEscapeHtml(this.Options.ZipLabel)+'" class="form-control redNaoInputText redNaoTwoColumns redNaoZip '+(this.Options.ReadOnly=='y'?'redNaoDisabledElement':"")+'"/>'+/*+ZipLabel+'\*/
                                '</div>';

                if(this.Options.ShowCountry=='y')
                {
                    html+='<div class="form-group col-sm-6">\
                        <select '+(this.Options.ReadOnly=='y'?'disabled="disabled"':"")+' name="'+this.GetPropertyName()+'_country"  class="form-control redNaoSelect redNaoCountry '+(this.Options.ReadOnly=='y'?'redNaoDisabledElement':"")+'">';
                        for(var i=0;i<this.Countries.length;i++)
                        {
                            html+='<option '+((this.Countries[i]==this.Options.DefaultCountry)?'selected="selected"':'')+'  value="'+RedNaoEscapeHtml(this.Countries[i])+'">'+RedNaoEscapeHtml(this.Countries[i])+'</option>';
                            isFirstElement=false;
                        }
                html+="</select>"+/*CountryLabel+'\*/
                    "</div>";
                }

    html+='</div></div>';
    return html;
};


sfRedNaoAddress.prototype.GetValueString=function()
{
    if(this.IsIgnored())
        return {streetAddress1:'',
                streetAddress2:'',
                city:'',
                state:'',
                zip:'',
                country:''
        };
    return {
        streetAddress1:rnJQuery('#'+this.Id+ ' .redNaoStreetAddress1').val(),
        streetAddress2:rnJQuery('#'+this.Id+ ' .redNaoStreetAddress2').val(),
        city:rnJQuery('#'+this.Id+ ' .redNaoCity').val(),
        state:rnJQuery('#'+this.Id+ ' .redNaoState').val(),
        zip:rnJQuery('#'+this.Id+ ' .redNaoZip').val(),
        country:rnJQuery('#'+this.Id+ ' .redNaoCountry').val()

    };


};


sfRedNaoAddress.prototype.SetData=function(data)
{
    this.JQueryElement.find('.redNaoStreetAddress1').val(data.streetAddress1);
    this.JQueryElement.find('.redNaoStreetAddress2').val(data.streetAddress2);
    this.JQueryElement.find('.redNaoCity').val(data.city);
    this.JQueryElement.find('.redNaoState').val(data.state);
    this.JQueryElement.find('.redNaoZip').val(data.zip);
    this.JQueryElement.find('.redNaoCountry').val(data.country);
};

sfRedNaoAddress.prototype.GetValuePath=function()
{
    return  "formData."+this.Id+'.streetAddress1'+
            " "+'formData.'+this.Id+'.streetAddress2'+
            " "+'formData.'+this.Id+'.city'+
            " "+'formData.'+this.Id+'.state'+
            " "+'formData.'+this.Id+'.zip'+
            " "+'formData.'+this.Id+'.country';
};


sfRedNaoAddress.prototype.IsValid=function()
{
    if(this.Options.IsRequired=='n')
        return true;



    var streetAddress1JQuery=rnJQuery('#'+this.Id+ ' .redNaoStreetAddress1');
    var streetAddress2JQuery=rnJQuery('#'+this.Id+ ' .redNaoStreetAddress2');
    var cityJQuery=rnJQuery('#'+this.Id+ ' .redNaoCity');
    var stateJQuery=rnJQuery('#'+this.Id+ ' .redNaoState');
    var zipJQuery=rnJQuery('#'+this.Id+ ' .redNaoZip');
    var countryJQuery=rnJQuery('#'+this.Id+ ' .redNaoCountry');

    var isValid=true;
    if(this.Options.ShowStreetAddress1&&streetAddress1JQuery.val()=='')
    {
        isValid=false;
        streetAddress1JQuery.parent().addClass('has-error');
    }

    if(this.Options.ShowStreetAddress2&&streetAddress2JQuery.val()=='')
    {
        isValid=false;
        streetAddress2JQuery.parent().addClass('has-error');
    }

    if(this.Options.ShowCity&&cityJQuery.val()=='')
    {
        isValid=false;
        cityJQuery.parent().addClass('has-error');
    }

    if(this.Options.ShowState&&stateJQuery.val()=='')
    {
        isValid=false;
        stateJQuery.parent().addClass('has-error');
    }

    if(this.Options.ShowZip&&zipJQuery.val()=='')
    {
        isValid=false;
        zipJQuery.parent().addClass('has-error');
    }

    if(this.Options.ShowCountry&&countryJQuery.val()=='')
    {
        isValid=false;
        countryJQuery.parent().addClass('has-error');
    }

    if(!isValid)
        this.AddError('root',this.InvalidInputMessage);
    else
        this.RemoveError('root');


    return this.InternalIsValid();

};

//noinspection JSUnusedLocalSymbols
sfRedNaoAddress.prototype.GenerationCompleted=function(jQueryElement)
{
    var self=this;
    rnJQuery('#'+this.Id+ ' .redNaoStreetAddress1,#'+this.Id+ ' .redNaoStreetAddress2,#'+this.Id+ ' .redNaoCity,#'+this.Id+ ' .redNaoState,#'+this.Id+ ' .redNaoZip,#'+this.Id+ ' .redNaoCountry').change(function(){self.FirePropertyChanged();});
    this.RegisterForFocusEvent(this.JQueryElement.find( '.redNaoStreetAddress1'));
    this.RegisterForFocusEvent(this.JQueryElement.find( '.redNaoStreetAddress2'));
    this.RegisterForFocusEvent(this.JQueryElement.find( '.redNaoCity'));
    this.RegisterForFocusEvent(this.JQueryElement.find( '.redNaoState'));
    this.RegisterForFocusEvent(this.JQueryElement.find( '.redNaoZip'));
    this.RegisterForFocusEvent(this.JQueryElement.find( '.redNaoCountry'));
};


/************************************************************************************* Phone ***************************************************************************************************/

function sfRedNaoPhone(options,serverOptions)
{
    sfFormElementBase.call(this,options,serverOptions);
    this.Title="Phone";
    if(this.IsNew)
    {
        this.Options.ClassName="rednaophone";
        this.Options.Label="Phone";
        this.Options.AreaLabel="Area";
        this.Options.PhoneLabel="Phone";
        this.Options.Icon={ClassName:''};
        this.Options.CustomCSS='';
    }else{
        this.SetDefaultIfUndefined('Icon',{ClassName:''});
        this.SetDefaultIfUndefined('CustomCSS','');
    }
}

sfRedNaoPhone.prototype=Object.create(sfFormElementBase.prototype);

sfRedNaoPhone.prototype.CreateProperties=function()
{
    this.Properties.push(new IdProperty(this,this.Options));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Label","Label",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"AreaLabel","Area",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"PhoneLabel","Phone",{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"IsRequired","Required",{ManipulatorType:'basic'}));
    this.Properties.push(new CustomCSSProperty(this,this.Options));
    this.Properties.push(new IconProperty(this,this.Options,'Icon','Icon',{ManipulatorType:'basic'}));
};

sfRedNaoPhone.prototype.GenerateInlineElement=function()
{
    var startOfInput='';
    var endOfInput='';
    if(this.Options.Icon.ClassName!='')
    {
        startOfInput='<div class="rednao-input-prepend input-group"><span class="redNaoPrepend input-group-addon prefix '+RedNaoEscapeHtml(this.Options.Icon.ClassName)+' "></span>';
        endOfInput='</div>';
    }

    var areaLabel='';
    var phoneLabel='';

    if(this.Options.AreaLabel!='')
        areaLabel='<br/><label class="redNaoHelper">'+RedNaoEscapeHtml(this.Options.AreaLabel)+'</label>';
    if(this.Options.PhoneLabel!='')
        phoneLabel='<br/><label class="redNaoHelper">'+RedNaoEscapeHtml(this.Options.PhoneLabel)+'</label>';

    return '<div class="rednao_label_container col-sm-3"><label class="rednao_control_label" >'+RedNaoEscapeHtml(this.Options.Label)+'</label></div>'+
                '<div class="redNaoControls col-sm-9">'+
                    '<div class="form-inline">'+
                        '<div class="form-group col-sm-3">'+
                            startOfInput+'<input '+(this.Options.ReadOnly=='y'?'disabled="disabled"':"")+' name="'+this.GetPropertyName()+'_area" type="text" placeholder="'+RedNaoEscapeHtml(this.Options.AreaLabel)+'" class="form-control redNaoInputText redNaoTwoColumns redNaoInputArea '+(this.Options.ReadOnly=='y'?'redNaoDisabledElement':"")+'"/>'+endOfInput+ /*areaLabel+''+*/
                        '</div>    '+
                        '<div class="form-group col-sm-6">'+
                            '<input '+(this.Options.ReadOnly=='y'?'disabled="disabled"':"")+' name="'+this.GetPropertyName()+'_phone" type="text" placeholder="'+RedNaoEscapeHtml(this.Options.PhoneLabel)+'" class="form-control redNaoInputText redNaoTwoColumns redNaoInputPhone '+(this.Options.ReadOnly=='y'?'redNaoDisabledElement':"")+'">'+ /*phoneLabel+''+*/
                        '</div>'+
                    '</div>'+
               '<div>     '+
    '</div>';
};


sfRedNaoPhone.prototype.GetValueString=function()
{
    if(this.IsIgnored())
        return {area:'',phone:''};
    return {
        area:rnJQuery('#'+this.Id+ ' .redNaoInputArea').val(),
        phone:rnJQuery('#'+this.Id+ ' .redNaoInputPhone').val()

    };


};

sfRedNaoPhone.prototype.SetData=function(data)
{
    this.JQueryElement.find('.redNaoInputArea').val(data.area);
    this.JQueryElement.find('.redNaoInputPhone').val(data.phone);
};

sfRedNaoPhone.prototype.GetValuePath=function()
{
    return 'formData.'+this.Id+'.area+" "'+'formData.'+this.Id+'.phone';
};


sfRedNaoPhone.prototype.IsValid=function()
{
    if(this.Options.IsRequired=='n')
        return true;

    if(rnJQuery('#'+this.Id+ ' .redNaoInputArea').val()==""||rnJQuery('#'+this.Id+ ' .redNaoInputPhone').val()=="")
    {
        var areaJQuery=rnJQuery('#'+this.Id+ ' .redNaoInputArea');
        var phoneJQuery=rnJQuery('#'+this.Id+ ' .redNaoInputPhone');

        if(areaJQuery.val()=="")
            areaJQuery.parent().addClass('has-error');

        if(phoneJQuery.val()=="")
            phoneJQuery.parent().addClass('has-error');

        this.AddError('root',this.InvalidInputMessage);
    }
    else
        this.RemoveError('root');

    return this.InternalIsValid();
};


//noinspection JSUnusedLocalSymbols
sfRedNaoPhone.prototype.GenerationCompleted=function(jQueryElement)
{
    var self=this;
    rnJQuery('#'+this.Id+ ' .redNaoInputArea,#'+this.Id+ ' .redNaoInputPhone').change(function(){
        self.FirePropertyChanged();
    });

    rnJQuery('#'+this.Id+ ' .redNaoInputArea,#'+this.Id+ ' .redNaoInputPhone').ForceNumericOnly();
    this.RegisterForFocusEvent(this.JQueryElement.find( '.redNaoInputArea'));
    this.RegisterForFocusEvent(this.JQueryElement.find( '.redNaoInputPhone'));

};


/************************************************************************************* Email Element ***************************************************************************************************/

function sfRedNaoEmail(options,serverOptions)
{
    sfFormElementBase.call(this,options,serverOptions);
    this.Title="Text Input";
    if(this.IsNew)
    {
        this.Options.ClassName="rednaoemail";
        this.Options.Label="Email";
        this.Options.Placeholder="Placeholder";
        this.Options.Icon={ClassName:''};
        this.Options.CustomCSS='';
        this.Options.Placeholder_Icon={ClassName:'',Orientation:''};
    }else{
        this.SetDefaultIfUndefined('Icon',{ClassName:''});
        this.SetDefaultIfUndefined('CustomCSS','');
        this.SetDefaultIfUndefined('Placeholder_Icon',{ClassName:''});
    }
}

sfRedNaoEmail.prototype=Object.create(sfFormElementBase.prototype);

sfRedNaoEmail.prototype.CreateProperties=function()
{
    this.Properties.push(new IdProperty(this,this.Options));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Label","Label",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Placeholder","Placeholder",{ManipulatorType:'basic',IconOptions:{Type:'leftAndRight'}}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"IsRequired","Required",{ManipulatorType:'basic'}));
    this.Properties.push(new CustomCSSProperty(this,this.Options));
    this.Properties.push(new IconProperty(this,this.Options,'Icon','Icon',{ManipulatorType:'basic'}));
};

sfRedNaoEmail.prototype.GenerateInlineElement=function()
{
    var startOfInput='';
    var endOfInput='';
    if(this.Options.Icon.ClassName!='')
    {
        startOfInput='<div class="rednao-input-prepend input-group"><span class="redNaoPrepend input-group-addon prefix '+RedNaoEscapeHtml(this.Options.Icon.ClassName)+' "></span>';
        endOfInput='</div>';
    }


    return '<div class="rednao_label_container col-sm-3"><label class="rednao_control_label " >'+RedNaoEscapeHtml(this.Options.Label)+'</label></div>'+
                '<div class="redNaoControls col-sm-9">'+
                    startOfInput+'<input '+(this.Options.ReadOnly=='y'?'disabled="disabled"':"")+' name="'+this.GetPropertyName()+'" type="text" placeholder="'+RedNaoEscapeHtml(this.Options.Placeholder)+'" class="form-control redNaoInputText redNaoEmail'+(this.Options.ReadOnly=='y'?'redNaoDisabledElement':"")+'">'+endOfInput+
    '</div>';
};


sfRedNaoEmail.prototype.GetValueString=function()
{
    if(this.IsIgnored())
        return {value:''};
    return {value:this.JQueryElement.find('.redNaoEmail').val()};
};

sfRedNaoEmail.prototype.SetData=function(data)
{
    this.JQueryElement.find('.redNaoEmail').val(data.value);
};


sfRedNaoEmail.prototype.GetValuePath=function()
{
    return 'formData.'+this.Id+'.value';
};


sfRedNaoEmail.prototype.IsValid=function()
{
    var email=this.JQueryElement.find('.redNaoEmail').val();
    if(email==""&&this.Options.IsRequired=='y')
    {
        this.JQueryElement.addClass('has-error');
        this.AddError('root',this.InvalidInputMessage);
    }else
        if(email!=''&&!this.EmailIsValid(email))
        {
            this.JQueryElement.addClass('has-error');
            this.AddError('root',this.InvalidInputMessage);
        }
        else
            this.RemoveError('root');
    return this.InternalIsValid();
};

sfRedNaoEmail.prototype.EmailIsValid=function(email)
{
    var reg=/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return reg.test(email);
};

//noinspection JSUnusedLocalSymbols
sfRedNaoEmail.prototype.GenerationCompleted=function(jQueryElement)
{
    var self=this;
    this.JQueryElement.find('.redNaoEmail').change(function(){self.FirePropertyChanged();});
    this.RegisterForFocusEvent(this.JQueryElement.find( '.redNaoEmail'));
    if(this.Options.Placeholder_Icon.ClassName!='')
    {
        this.LoadPlaceHolderIcon(jQueryElement.find('.redNaoInputText'),null,null,this.Options.Placeholder_Icon);
    }

};


/************************************************************************************* Number Element ***************************************************************************************************/

function sfRedNaoNumber(options,serverOptions)
{
    sfFormElementBase.call(this,options,serverOptions);
    this.Title="Text Input";
    if(this.IsNew)
    {
        this.Options.ClassName="rednaonumber";
        this.Options.Label="Number";
        this.Options.Placeholder="Placeholder";
        this.Options.NumberOfDecimals=0;
        this.Options.MaximumValue="";
        this.Options.MinimumValue="";
        this.Options.Icon={ClassName:''};
        this.Options.CustomCSS='';
        this.Options.Placeholder_Icon={ClassName:'',Orientation:''};
    }else
    {
        this.SetDefaultIfUndefined("MaximumValue","");
        this.SetDefaultIfUndefined("MinimumValue","");
        this.SetDefaultIfUndefined('Icon',{ClassName:''});
        this.SetDefaultIfUndefined('CustomCSS','');
        this.SetDefaultIfUndefined('Placeholder_Icon',{ClassName:''});
    }
}

sfRedNaoNumber.prototype=Object.create(sfFormElementBase.prototype);

sfRedNaoNumber.prototype.CreateProperties=function()
{
    this.Properties.push(new IdProperty(this,this.Options));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Label","Label",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Placeholder","Placeholder",{ManipulatorType:'basic',IconOptions:{Type:'leftAndRight'}}));
    //this.Properties.push(new SimpleTextProperty(this,this.Options,"NumberOfDecimals","Number of decimals",{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"IsRequired","Required",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"MinimumValue","Minimum Value",{ManipulatorType:'basic',Placeholder:'No Minimum'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"MaximumValue","Maximum Value",{ManipulatorType:'basic',Placeholder:'No Maximum'}));
    this.Properties.push(new CustomCSSProperty(this,this.Options));
    this.Properties.push(new IconProperty(this,this.Options,'Icon','Icon',{ManipulatorType:'basic'}));
};

sfRedNaoNumber.prototype.GenerateInlineElement=function()
{
    var startOfInput='';
    var endOfInput='';
    if(this.Options.Icon.ClassName!='')
    {
        startOfInput='<div class="rednao-input-prepend input-group"><span class="redNaoPrepend input-group-addon prefix '+RedNaoEscapeHtml(this.Options.Icon.ClassName)+' "></span>';
        endOfInput='</div>';
    }



    return '<div class="rednao_label_container col-sm-3"><label class="rednao_control_label" >'+RedNaoEscapeHtml(this.Options.Label)+'</label></div>'+
                '<div class="redNaoControls col-sm-9">'+
                    startOfInput+'<input '+(this.Options.ReadOnly=='y'?'disabled="disabled"':"")+' name="'+this.GetPropertyName()+'" type="text" placeholder="'+this.Options.Placeholder+'" class="form-control redNaoInputText redNaoNumber'+(this.Options.ReadOnly=='y'?'redNaoDisabledElement':"")+'">'+endOfInput+
    '</div>';
};


sfRedNaoNumber.prototype.GetValueString=function()
{
    if(this.IsIgnored())
        return {value:''};
    return {value:rnJQuery('#'+this.Id+ ' .redNaoNumber').val()};
};

sfRedNaoNumber.prototype.SetData=function(data)
{
    this.JQueryElement.find('.redNaoNumber').val(data.value);
};

sfRedNaoNumber.prototype.GetValuePath=function()
{
    return 'formData.'+this.Id+'.value';
};


sfRedNaoNumber.prototype.IsValid=function()
{
    var number=rnJQuery('#'+this.Id+ ' .redNaoNumber').val();
    if(number==""&&this.Options.IsRequired=='y')
    {
        rnJQuery('#'+this.Id).addClass('has-error');
        this.AddError('root',this.InvalidInputMessage);
    }
    else
        this.RemoveError('root');

    return this.InternalIsValid();
};

sfRedNaoNumber.prototype.InputIsValid=function()
{
    var inputText=rnJQuery('#'+this.Id+ ' .redNaoNumber').val();

    if(isNaN(inputText))
        return false;

    var inputNumber=parseFloat(inputText);
    if(!isNaN(parseInt(this.Options.MaximumValue)))
    {
        var maximumValue=parseFloat(this.Options.MaximumValue);
        if(inputNumber>maximumValue)
            return false;
    }

    if(!isNaN(parseInt(this.Options.MinimumValue)))
    {
        var minimumValue=parseFloat(this.Options.MinimumValue);
        if(inputNumber<minimumValue)
            return false;
    }
    return true;
};

//noinspection JSUnusedLocalSymbols
sfRedNaoNumber.prototype.GenerationCompleted=function(jQueryElement)
{
    var self=this;
    rnJQuery('#'+this.Id+ ' .redNaoNumber').change(function(){
        if(!self.InputIsValid())
            rnJQuery('#'+self.Id+ ' .redNaoNumber').val('');

        self.FirePropertyChanged();});
    rnJQuery('#'+this.Id+ ' .redNaoNumber').ForceNumericOnly();
    this.RegisterForFocusEvent(this.JQueryElement.find( '.redNaoNumber'));
    if(this.Options.Placeholder_Icon.ClassName!='')
    {
        this.LoadPlaceHolderIcon(jQueryElement.find('.redNaoInputText'),null,null,this.Options.Placeholder_Icon);
    }
};


/************************************************************************************* Recaptcha Element ***************************************************************************************************/

function sfRedNaoCaptcha(options,serverOptions)
{
    sfFormElementBase.call(this,options,serverOptions);
    this.Title="captcha";
    if(this.IsNew)
    {
        this.Options.ClassName="rednaocaptcha";
        this.Options.Label="Captcha";
        this.Options.Theme="red";
        this.Options.CustomCSS='';
    }else{
        this.SetDefaultIfUndefined('CustomCSS','');
    }

    this.Options.Id="captcha";
    this.Id="captcha";
}

sfRedNaoCaptcha.prototype=Object.create(sfFormElementBase.prototype);

sfRedNaoCaptcha.prototype.CreateProperties=function()
{
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Label","Label",{ManipulatorType:'basic'}));
    this.Properties.push(new CustomCSSProperty(this,this.Options));

};

sfRedNaoCaptcha.prototype.GenerateInlineElement=function()
{

    return '<div class="rednao_label_container col-sm-3"><label class="rednao_control_label" >'+RedNaoEscapeHtml(this.Options.Label)+'</label></div>\
                <div class="redNaoControls row redNaoCaptcha col-sm-9" id="captchaComponent">\
    </div>';
};
sfRedNaoCaptcha.prototype.StoresInformation=function()
{
    return false;
};


//noinspection JSUnusedLocalSymbols
sfRedNaoCaptcha.prototype.GenerationCompleted=function(jQueryElement)
{
    var url='';
    if(location.protocol == 'https:')
        url="https://www.google.com/recaptcha/api/js/recaptcha_ajax.js";
    else
        url="http://www.google.com/recaptcha/api/js/recaptcha_ajax.js";

    rnJQuery.getScript(url, function(){
        //noinspection JSUnresolvedVariable
        Recaptcha.create("6Lf2J-wSAAAAACCijq50oACQRuvrsmNt9DeUsE-7",
            'captchaComponent',
            {
                theme: "red"
            }
        );
    });
};

/************************************************************************************* Html Element ***************************************************************************************************/

function sfHtmlElement(options,serverOptions)
{
    sfFormElementBase.call(this,options,serverOptions);
    this.Title="HTML Element";

    if(this.IsNew)
    {
        this.Options.ClassName="rednaohtml";
        this.Options.Label="Html";
        this.Options.HTML='';
        this.Options.CustomCSS='';
    }else{
        this.SetDefaultIfUndefined('CustomCSS','');
    }
}
sfHtmlElement.prototype=Object.create(sfFormElementBase.prototype);

sfHtmlElement.prototype.CreateProperties=function()
{
    this.Properties.push(new IdProperty(this,this.Options));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Label","Label",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"HTML","HTML",{ManipulatorType:'basic',RefreshFormData:true,MultipleLine:true}));
    this.Properties.push(new CustomCSSProperty(this,this.Options));
};

sfHtmlElement.prototype.StoresInformation=function()
{
    return false;
};

sfHtmlElement.prototype.GenerateInlineElement=function()
{
    return  '<div class="rednao_label_container col-sm-3"><label class="rednao_control_label" for="textarea">'+RedNaoEscapeHtml(this.Options.Label)+'</label></div>'+
                '<div class="redNaoControls col-sm-9">'+
                this.Options.HTML+
    '</div>';


};


/*************************************************************************************Searchable list ***************************************************************************************************/

function sfSearchableList(options,serverOptions)
{
    sfFormElementBase.call(this,options,serverOptions);
    this.Title="Searchable List";
    this.DataToLoad=null;
    if(this.IsNew)
    {
        this.Options.Label="Searchable List";
        this.Options.ClassName="rednaosearchablelist";
        this.Options.DefaultText="Select a value";
        this.Options.Options=[{label:'Option 1',value:0,sel:'n',url:''},{label:'Option 2',value:0,sel:'n',url:''},{label:'Option',value:0,sel:'n',url:''}];
        this.Options.Multiple='n';
        this.Options.CustomCSS='';
    }else{
        this.SetDefaultIfUndefined('CustomCSS','');
    }
}

sfSearchableList.prototype=Object.create(sfFormElementBase.prototype);

sfSearchableList.prototype.CreateProperties=function()
{
    var self=this;
    this.Properties.push(new IdProperty(this,this.Options));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"Label","Label",{ManipulatorType:'basic'}));
    this.Properties.push(new SimpleTextProperty(this,this.Options,"DefaultText","Default text",{ManipulatorType:'basic'}));
    this.OptionsProperty=new ArrayProperty(this,this.Options,"Options","Options",{ManipulatorType:'basic',SelectorType:(this.Options.Multiple=='n'?'radio':'checkbox'),AllowImages:true});
    this.Properties.push(this.OptionsProperty);
    this.Properties.push(new CheckBoxProperty(this,this.Options,"IsRequired","Required",{ManipulatorType:'basic'}));
    this.Properties.push(new CheckBoxProperty(this,this.Options,"Multiple","Enable Select Multiple Items",{ManipulatorType:'basic',ChangeCallBack:function(newValue,oldValue){
        if(newValue=='y')
            self.OptionsProperty.AdditionalInformation.SelectorType='checkbox';
        else
            self.OptionsProperty.AdditionalInformation.SelectorType='radio';

        self.OptionsProperty.RefreshProperty();
    }}));
    this.Properties.push(new CustomCSSProperty(this,this.Options));




};

sfSearchableList.prototype.GenerateInlineElement=function()
{
    var additionalStyle='';
    if(!isNaN(parseFloat(this.Options.Width)))
        additionalStyle='width:'+this.Options.Width+'px'+' !important;';

    var multiple='';
    if(this.Options.Multiple=='y')
        multiple='multiple="multiple"';

    var html=  '<div class="rednao_label_container col-sm-3"><label class="rednao_control_label">'+RedNaoEscapeHtml(this.Options.Label)+'</label></div>\
        <div class="redNaoControls col-sm-9">\
        <select data-placeholder="'+RedNaoEscapeHtml(this.Options.DefaultText)+'" style="'+additionalStyle+'" name="'+this.GetPropertyName()+'" class="redNaoSelect" '+multiple+'>';

    var selected='';



    if(this.Options.Multiple=='n')
        html+='<option></option>';


    for(var i=0;i<this.Options.Options.length;i++)
    {
        if(this.Options.Options[i].sel=='y')
            selected='selected="selected"';
        else
            selected='';

        var imgSrc='';
        if(typeof this.Options.Options[i].url!='undefined')
            imgSrc=rnJQuery.trim(this.Options.Options[i].url);
        html+='<option  data-imgsrc="'+imgSrc+'"   value="'+i+'" '+selected+'>'+RedNaoEscapeHtml(this.Options.Options[i].label)+'</opton>';

        selected="";

    }
    html+='</select></div>';
    return html;
};

sfSearchableList.prototype.GetSelectedValuesFromNormalSelect=function()
{
    var selectedOptionIndexes=[];
    var $selectedOptions=this.GetRootContainer().find('.redNaoSelect option:selected');

    for(var i=0;i<$selectedOptions.length;i++)
    {
        var optionIndex=parseInt(rnJQuery($selectedOptions[i]).val());
        if(!isNaN(optionIndex))
            selectedOptionIndexes.push(optionIndex);
    }

    return selectedOptionIndexes;
};

sfSearchableList.prototype.GetValueString=function()
{
    this.amount=0;
    if(this.IsIgnored())
        return {selectedValues:[]};
    var data={};
    data.selectedValues=[];
    var select2SelectedValues;
    if(this.Select2.hasClass('select2-offscreen'))
    {
        select2SelectedValues = this.Select2.select2('val');
        if(select2SelectedValues==null)
            select2SelectedValues=[];
        if(!rnJQuery.isArray(select2SelectedValues))
        {
            var aux=select2SelectedValues;
            select2SelectedValues=[];
            if(rnJQuery.trim(aux)!="")
                select2SelectedValues.push(aux);
        }
    }
    else
        select2SelectedValues=this.GetSelectedValuesFromNormalSelect();



    for(var i=0;i<select2SelectedValues.length;i++)
    {
        var option=this.Options.Options[select2SelectedValues[i]];
        this.amount=parseFloat(option.value);
        if(isNaN(this.amount))
            this.amount=0;

        data.selectedValues.push(
            {
                value:rnJQuery.trim(option.label),
                amount:this.amount,
                label:rnJQuery.trim(option.label)
            }
        );
    }

    return data;
};

sfSearchableList.prototype.SetData=function(data)
{
    var values=[];


    for(var i=0;i<data.selectedValues.length;i++)
    {
        for(var t=0;t<this.Options.Options.length;t++)
        {
            if(data.selectedValues[i].value==rnJQuery.trim(this.Options.Options[t].label))
            {
                values.push(t);
                break;
            }

        }
    }
    if(typeof this.Select2.select2=='undefined')
        this.DataToLoad=values;
    else
        this.Select2.select2('val',values);
};

sfSearchableList.prototype.GetValuePath=function()
{
    return 'RedNaoGetValueFromArray(formData.'+this.Id+'.selectedValues)';
};


sfSearchableList.prototype.IsValid=function()
{
    if(this.Options.IsRequired=='y'&&(this.GetValueString().selectedValues.length==0))
    {
        rnJQuery('#'+this.Id).addClass('has-error');
        this.AddError('root',this.InvalidInputMessage);
    }
    else
        this.RemoveError('root');
    return this.InternalIsValid();
};


//noinspection JSUnusedLocalSymbols
sfSearchableList.prototype.GenerationCompleted=function(jQueryElement)
{
    var self=this;
    this.Select2=this.GetRootContainer().find('.redNaoSelect');
    rnJQuery.RNLoadLibrary([smartFormsPath+'js/utilities/select2/select2.js'],[smartFormsPath+'js/utilities/select2/select2.css'],function(){self.LoadSelect2()});
    rnJQuery('#'+this.Id+ ' .redNaoSelect').change(function(){self.FirePropertyChanged();});
};

sfSearchableList.prototype.LoadSelect2=function()
{
    this.Select2.select2({
        width:'100%',
        formatResult:sfFormatResult,
        formatSelection:sfFormatSelection
    });

    if(this.DataToLoad!=null)
    {
        this.Select2.select2('val', this.DataToLoad);
        this.DataToLoad=null;
        this.FirePropertyChanged();
    }

};

function sfFormatResult(state)
{
    var imageSrc=rnJQuery(state.element).data('imgsrc').toString();
    if(imageSrc!='')
        return '<img style="vertical-align:middle" src="'+imageSrc+'"/><span style="margin-left:2px;vertical-align:middle">'+RedNaoEscapeHtml(state.text)+'</span>';
    return '<span>'+RedNaoEscapeHtml(state.text)+'</span>';
}

function sfFormatSelection(state)
{
    var imageSrc=rnJQuery(state.element).data('imgsrc').toString();
    if(imageSrc!='')
        return '<img style="vertical-align:middle;max-height:19px;display:inline;" src="'+imageSrc+'"/><span style="margin-left:2px;vertical-align:middle">'+RedNaoEscapeHtml(state.text)+'</span>';
    return '<span>'+RedNaoEscapeHtml(state.text)+'</span>';
}