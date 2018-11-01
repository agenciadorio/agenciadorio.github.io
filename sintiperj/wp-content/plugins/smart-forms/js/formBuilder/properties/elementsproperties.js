/************************************************************************************* Base  ***************************************************************************************************/

function ElementPropertiesBase(formelement,propertiesObject,propertyName,propertyTitle,additionalInformation)
{
    if(additionalInformation.ManipulatorType=='basic')
        this.Manipulator=RedNaoBasicManipulatorInstance;
    this.FormElement=formelement;
    this.AdditionalInformation=additionalInformation;
    this.PropertiesObject=propertiesObject;
    this.PropertyName=propertyName;
    this.PropertyTitle=propertyTitle;
    this.PropertyId="redNaoFormProperty"+this.PropertyName;
    this.$PropertiesContainer=null;

}

ElementPropertiesBase.prototype.FormulaExists=function(formElement,propertyName)
{
    return RedNaoPathExists(formElement, 'Options.Formulas.' + propertyName + '.Value') && formElement.Options.Formulas[propertyName].Value != "";
};

ElementPropertiesBase.prototype.CreateProperty=function(jQueryObject)
{
    this.$PropertiesContainer=rnJQuery("<tr></tr>");
    this.$PropertiesContainer.append(this.GenerateHtml());
    jQueryObject.append(this.$PropertiesContainer);
};

ElementPropertiesBase.prototype.GenerateHtml=function()
{
    throw 'Abstract Method';
};



ElementPropertiesBase.prototype.RefreshProperty=function()
{
    this.$PropertiesContainer.empty();
    this.$PropertiesContainer.append(this.GenerateHtml());
};

ElementPropertiesBase.prototype.GetPropertyCurrentValue=function()
{
    return this.Manipulator.GetValue(this.PropertiesObject,this.PropertyName,this.AdditionalInformation);
};

ElementPropertiesBase.prototype.UpdateProperty=function()
{
    this.Manipulator.SetValue(this.PropertiesObject,this.PropertyName, rnJQuery("#"+this.PropertyId).val(),this.AdditionalInformation);

};

ElementPropertiesBase.prototype.RefreshElement=function()
{
    var previousClasses=this.FormElement.JQueryElement.attr('class');
    var newClasses=this.FormElement.GetElementClasses();
    if(previousClasses.indexOf('SmartFormsElementSelected')>=0)
        newClasses+=' SmartFormsElementSelected';

    var refreshedElements=this.FormElement.RefreshElement();
    this.FormElement.JQueryElement.attr('class',newClasses);
    refreshedElements.find('input[type=submit],button').click(function(e){e.preventDefault();e.stopPropagation();})

};

/************************************************************************************* Simple Text Property ***************************************************************************************************/


function SimpleTextProperty(formelement,propertiesObject,propertyName,propertyTitle,additionalInformation)
{
    if(typeof additionalInformation.Placeholder=='undefined')
        additionalInformation.Placeholder='Default';
    ElementPropertiesBase.call(this,formelement,propertiesObject,propertyName,propertyTitle,additionalInformation);
}

SimpleTextProperty.prototype=Object.create(ElementPropertiesBase.prototype);

SimpleTextProperty.prototype.GenerateHtml=function()
{
    var input="";
    var tdStyle="";
    if(this.AdditionalInformation.MultipleLine==true)
    {
        input='<textarea style="width:206px;" class="rednao-input-large" data-type="input" name="name" id="'+this.PropertyId+'" placeholder="'+this.AdditionalInformation.Placeholder+'">'+RedNaoEscapeHtml(this.GetPropertyCurrentValue())+'</textarea>';
        tdStyle='vertical-align:top;'
    }
    else
    {
        input='<input style="width: 206px;" class="rednao-input-large" data-type="input" type="text" name="name" id="'+this.PropertyId+'" value="'+RedNaoEscapeHtml(this.GetPropertyCurrentValue())+'" placeholder="'+this.AdditionalInformation.Placeholder+'"/>';
    }

    var tooltip='';
    if(typeof this.AdditionalInformation.Tooltip!='undefined')
        tooltip='<span style="margin-left: 2px;cursor:hand;cursor:pointer;" data-toggle="tooltip" data-placement="right" title="'+RedNaoEscapeHtml(this.AdditionalInformation.Tooltip.Text)+'" class="glyphicon glyphicon-question-sign sfToolTip"></span>';


    var newProperty=rnJQuery( '<td style="text-align: right;'+tdStyle+'"><label class="rednao-properties-control-label"> '+this.PropertyTitle+' </label></td>'+
            '<td  style="text-align: left">'+input+
            '<img style="width:15px;height: 20px; vertical-align: middle;cursor:pointer;cursor:hand;" title="Formula" src="'+ smartFormsRootPath+(this.FormulaExists(this.FormElement,this.PropertyName)?'images/formula_used.png' :'images/formula.png')+'"/>'+tooltip+'</td>');

    newProperty.find('.sfToolTip').tooltip({html:true,'delay': {hide: 3000}});


    var self=this;
    if(typeof this.AdditionalInformation.IconOptions!='undefined')
    {
        var selected='';
        var defaultValue=this.PropertiesObject[this.PropertyName+'_Icon'].ClassName;
        if(defaultValue!='')
            selected='sfSelected';
        var addIconButton=rnJQuery('<a style="margin-left: 5px;" href="#"><span class="glyphicon glyphicon-tags sfAddIcon '+selected+'" title="Add Icon"></span></a>');
        addIconButton.click(function()
        {
            RedNaoIconSelectorVar.Show( self.AdditionalInformation.IconOptions.Type,defaultValue,function(itemClass,orientation){
                defaultValue=itemClass;
                self.IconSelected(itemClass,orientation,addIconButton)});
        });
        rnJQuery(newProperty[1]).append(addIconButton);
    }

    newProperty.keyup(function(){
        self.Manipulator.SetValue(self.PropertiesObject,self.PropertyName, (rnJQuery("#"+self.PropertyId).val()),self.AdditionalInformation);
        self.RefreshElement();

    });
    newProperty.find('img').click(function(){RedNaoEventManager.Publish('FormulaButtonClicked',{"FormElement":self.FormElement,"PropertyName":self.PropertyName,AdditionalInformation:self.AdditionalInformation,Image:newProperty.find('img')})});
    return newProperty;
};

SimpleTextProperty.prototype.IconSelected=function(itemClass,orientation,$addIconButton)
{
    this.PropertiesObject[this.PropertyName+'_Icon']={
        ClassName:itemClass,
        Orientation:orientation
    };

    if(itemClass=='')
        $addIconButton.find('span').removeClass('sfSelected');
    else
        $addIconButton.find('span').addClass('sfSelected');
    this.RefreshElement();
};


/************************************************************************************* Simple Numeric Property ***************************************************************************************************/


function SimpleNumericProperty(formelement,propertiesObject,propertyName,propertyTitle,additionalInformation)
{
    if(typeof additionalInformation.Placeholder=='undefined')
        additionalInformation.Placeholder='Default';
    ElementPropertiesBase.call(this,formelement,propertiesObject,propertyName,propertyTitle,additionalInformation);
}

SimpleNumericProperty.prototype=Object.create(ElementPropertiesBase.prototype);

SimpleNumericProperty.prototype.GenerateHtml=function()
{
    var input="";
    var tdStyle="";
    if(this.AdditionalInformation.MultipleLine==true)
    {
        input='<textarea style="width:206px;" class="rednao-input-large" data-type="input" name="name" id="'+this.PropertyId+'" value="'+RedNaoEscapeHtml(this.GetPropertyCurrentValue())+'" placeholder="'+this.AdditionalInformation.Placeholder+'"/>';
        tdStyle='vertical-align:top;'
    }
    else
    {
        input='<input style="width: 206px;" class="rednao-input-large" data-type="input" type="text" name="name" id="'+this.PropertyId+'" value="'+RedNaoEscapeHtml(this.GetPropertyCurrentValue())+'" placeholder="'+this.AdditionalInformation.Placeholder+'"/>';
    }


    var newProperty=rnJQuery( '<td style="text-align: right;'+tdStyle+'"><label class="rednao-properties-control-label"> '+this.PropertyTitle+' </label></td>\
            <td style="text-align: left">'+input+'\
            <img style="width:15px;height: 20px; vertical-align: middle;cursor:pointer;cursor:hand;" title="Formula" src="'+ smartFormsRootPath+(this.FormulaExists(this.FormElement,this.PropertyName)?'images/formula_used.png' :'images/formula.png')+'"/> </td>');
    newProperty.find('input').ForceNumericOnly();
    var self=this;
    newProperty.keyup(function(){
        var value=parseFloat(rnJQuery("#"+self.PropertyId).val());
        if(isNaN(value))
            value='';
        else
            value=value.toString();
        self.Manipulator.SetValue(self.PropertiesObject,self.PropertyName, value,self.AdditionalInformation);
        self.RefreshElement();

    });
    newProperty.find('img').click(function(){RedNaoEventManager.Publish('FormulaButtonClicked',{"FormElement":self.FormElement,"PropertyName":self.PropertyName,AdditionalInformation:self.AdditionalInformation,Image:newProperty.find('img')})});
    return newProperty;
};







/************************************************************************************* Check Box Property ***************************************************************************************************/



function CheckBoxProperty(formelement,propertiesObject,propertyName,propertyTitle,additionalInformation)
{
    ElementPropertiesBase.call(this,formelement,propertiesObject,propertyName,propertyTitle,additionalInformation);
}

CheckBoxProperty.prototype=Object.create(ElementPropertiesBase.prototype);

CheckBoxProperty.prototype.GenerateHtml=function()
{
    var newProperty=rnJQuery('<td style="text-align: right"><label class="checkbox control-group rednao-properties-control-label" style="display: block;">'+this.PropertyTitle+'</label></td>\
                <td style="text-align: left"><input type="checkbox" class="input-inline field" name="checked" id="'+this.PropertyId+'" '+(this.GetPropertyCurrentValue()=='y'? 'checked="checked"':'')+'/></td>');

    var self=this;
    newProperty.find('#'+this.PropertyId).change(function(){
        self.Manipulator.SetValue(self.PropertiesObject,self.PropertyName, (rnJQuery("#"+self.PropertyId).is(':checked')?'y':'n'),self.AdditionalInformation);
        self.RefreshElement();
    });

    return newProperty;
};





/************************************************************************************* Array Property ***************************************************************************************************/



function ArrayProperty(formelement,propertiesObject,propertyName,propertyTitle,additionalInformation)
{
    ElementPropertiesBase.call(this,formelement,propertiesObject,propertyName,propertyTitle,additionalInformation);
}

ArrayProperty.prototype=Object.create(ElementPropertiesBase.prototype);

ArrayProperty.prototype.GenerateHtml=function()
{
    var currentValues=this.GetPropertyCurrentValue();
    var self=this;
    var newProperty=rnJQuery('<td style="vertical-align: top;text-align: right;"><label class="checkbox control-group rednao-properties-control-label" style="display: block;vertical-align: top;">'+this.PropertyTitle+'</label></td><td style="text-align: left">'+this.GetItemList(currentValues)+'</td>');
    newProperty.find('table').append("<tr><td style='border-bottom-style: none;'><button class='redNaoPropertyClearButton' value='None'>Clear</button></td></tr></table>");
    newProperty.find('.redNaoPropertyClearButton').click(function(event)
    {
        event.preventDefault();
        newProperty.find('.itemSel').removeAttr('checked');
        self.UpdateProperty();
    });

    newProperty.find('.cloneArrayItem').click(function(){self.CloneItem(rnJQuery(this))});
    newProperty.find('.deleteArrayItem').click(function(){self.DeleteItem(rnJQuery(this))});
    newProperty.find('input[type=text],input[type=radio],input[type=checkbox]').change(function(){self.UpdateProperty();});
    newProperty.find('input[type=text]').keyup(function(){self.UpdateProperty();});


    this.ItemsList=newProperty.find('.listOfItems');
    return newProperty;
};

ArrayProperty.prototype.GetItemList=function(items)
{
    var allowImages=typeof this.AdditionalInformation.AllowImages!='undefined'&&this.AdditionalInformation.AllowImages==true;

    var list= '<table class="listOfItems"><tr><th style="text-align: right">Sel</th><th>Label</th><th>Amount</th>'+(allowImages?'<th>Image Url</th>':'')+'</tr>';

    var isFirst=true;
    for(var i=0;i<items.length;i++)
    {
        list+=this.CreateListRow(isFirst,items[i]);
        isFirst=false;
    }
    return list;

};

ArrayProperty.prototype.DeleteItem=function(jQueryElement)
{
    var array=this.GetPropertyCurrentValue();
    var index=jQueryElement.parent().parent().index();

    array.splice(index,1);
    jQueryElement.parent().parent().remove();
    this.UpdateProperty();
};

ArrayProperty.prototype.CloneItem=function(jQueryElement)
{
    var jQueryToClone=jQueryElement.parent().parent();
    var data=this.GetRowData(jQueryToClone);

    if(this.AdditionalInformation.SelectorType=='radio')
        data.sel='n';

    var jQueryNewRow=rnJQuery(this.CreateListRow(false,data));
    jQueryToClone.after(jQueryNewRow);

    var self=this;
    jQueryNewRow.find('.cloneArrayItem').click(function(){self.CloneItem(rnJQuery(this))});
    jQueryNewRow.find('.deleteArrayItem').click(function(){self.DeleteItem(rnJQuery(this))});
    jQueryNewRow.find('input[type=text],input[type=radio],input[type=checkbox]').change(function(){self.UpdateProperty();});

    this.UpdateProperty();

};


ArrayProperty.prototype.CreateListRow=function(isFirst,item)
{

    var allowImages=typeof this.AdditionalInformation.AllowImages!='undefined'&&this.AdditionalInformation.AllowImages==true;
    if(allowImages&&typeof item.url=='undefined')
        item.url='';
    var row= '<tr class="redNaoRowOption">' +
            '       <td style="text-align: right;">'+this.GetSelector(item)+'</td>' +
            '       <td><input type="text" class="itemText" value="'+RedNaoEscapeHtml(item.label)+'"/></td>' +
            '       <td><input type="text" class="itemValue" style="text-align: right; width: 50px;" value="'+RedNaoEscapeHtml(item.value)+'"/></td>' +
        (allowImages?'<td><input type="text" class="itemUrl" style="text-align: right; width: 50px;" value="'+RedNaoEscapeHtml(item.url)+'"/></td>':'')+
            '       <td style="text-align: center;vertical-align: middle;"><img style="cursor: hand;cursor: pointer; width:15px;height:15px;" class="cloneArrayItem" src="'+smartFormsRootPath+'images/clone.png" title="Clone"></td>';
            if(!isFirst)
                row+=' <td style="text-align: center;vertical-align: middle;"><img style="cursor: hand; cursor: pointer;width:15px;height:15px;" class="deleteArrayItem" src="'+smartFormsRootPath+'images/delete.png" title="Delete"></td>';
            row+='</tr>';
    return row;
};

ArrayProperty.prototype.GetSelector=function(item)
{
    var selected='';
    if(RedNaoGetValueOrEmpty(item.sel)=='y')
        selected='checked="checked"';
    if(this.AdditionalInformation.SelectorType=='radio')
        return '<input class="itemSel" type="radio" '+selected+' name="propertySelector"/>';
    else
        return '<input class="itemSel" type="checkbox" '+selected+'/>';
};

ArrayProperty.prototype.UpdateProperty=function()
{
		var processedValueArray=new Array();
    var self=this;
    var rows=this.ItemsList.find('tr.redNaoRowOption').each(
        function()
        {
            var jQueryRow=rnJQuery(this);
            var row=self.GetRowData(jQueryRow);
            processedValueArray.push(row);
        }
    );
    this.Manipulator.SetValue(this.PropertiesObject,this.PropertyName, processedValueArray,this.AdditionalInformation);
    this.RefreshElement();
};


ArrayProperty.prototype.GetRowData=function(jQueryRow)
{
    var objectToReturn= {label:jQueryRow.find('.itemText').val(),value:jQueryRow.find('.itemValue').val(),sel:(jQueryRow.find('.itemSel').is(':checked')?'y':'n')};

    if(typeof this.AdditionalInformation.AllowImages!='undefined'&&this.AdditionalInformation.AllowImages==true)
        objectToReturn.url=jQueryRow.find('.itemUrl').val();
    return objectToReturn;
};

/************************************************************************************* Id Property ***************************************************************************************************/


function IdProperty(formelement,propertiesObject)
{
    ElementPropertiesBase.call(this,formelement,propertiesObject,"Id","Id",{ManipulatorType:'basic'});
}

IdProperty.prototype=Object.create(ElementPropertiesBase.prototype);

IdProperty.prototype.GenerateHtml=function()
{
    this.PreviousId=this.FormElement.Id;

    var value=this.PreviousId;
    var newProperty=rnJQuery( '<td style="text-align: right"><label class="rednao-properties-control-label"> '+this.PropertyTitle+' </label></td>\
            <td style="text-align: left"><input style="width: 206px;" class="rednao-input-large" data-type="input" maxlength="50" type="text" name="name" id="'+this.PropertyId+'" value="'+value+'" placeholder="Default"/></td>');


    var self=this;
    newProperty.change(function(){

        var jqueryElement=rnJQuery(this).find('#'+self.PropertyId);
        var fieldName=jqueryElement.val().trim();

        if(!fieldName.match(/^[a-zA-Z][\w:.-]*$/))
        {
            alert("Invalid field name, it should start with a letter and not contain spaces or symbols");
            jqueryElement.val(self.PreviousId);
            return;
        }

        var formElements=SmartFormsAddNewVar.FormBuilder.RedNaoFormElements;
        for(var i=0;i<formElements.length;i++)
        {
            if(fieldName.toLowerCase()==formElements[i].Id.toLowerCase())
            {
                alert("The field "+fieldName+" already exists");
                jqueryElement.val(self.PreviousId);
                return;
            }
        }

        self.FormElement.Id=fieldName;
        self.PropertiesObject.Id=fieldName;

        var jQueryElement=rnJQuery('#'+self.PreviousId);
        jQueryElement.attr('id',fieldName);


        var refreshedElements=self.FormElement.RefreshElement();
        refreshedElements.find('input[type=submit],button').click(function(e){e.preventDefault();e.stopPropagation();});
        self.RefreshElement();

    });
    return newProperty;
};




/************************************************************************************* Combo Property ***************************************************************************************************/


function ComboBoxProperty(formelement,propertiesObject,propertyName,propertyTitle,additionalInformation)
{
    ElementPropertiesBase.call(this,formelement,propertiesObject,propertyName,propertyTitle,additionalInformation);
}

ComboBoxProperty.prototype=Object.create(ElementPropertiesBase.prototype);

ComboBoxProperty.prototype.GenerateHtml=function()
{
    var value=this.GetPropertyCurrentValue().trim();
    var selectText='<select id="'+this.PropertyId+'">';
    for(var i=0;i<this.AdditionalInformation.Values.length;i++)
    {
        var selected="";
        if(this.AdditionalInformation.Values[i].value==value)
            selected='selected="selected"';

        selectText+='<option value="'+RedNaoEscapeHtml(this.AdditionalInformation.Values[i].value)+'" '+selected+'>'+RedNaoEscapeHtml(this.AdditionalInformation.Values[i].label)+'</option>';
    }
    selectText+='</select>';

    var tooltip="";
    if(typeof this.AdditionalInformation.ToolTip !='undefined')
    {
        tooltip='<span style="margin-left: 2px;cursor:hand;cursor:pointer;" data-toggle="tooltip" data-placement="right" title="'+this.AdditionalInformation.ToolTip.Text+'" class="sfToolTip glyphicon glyphicon-question-sign"></span>';
    }

    var newProperty=rnJQuery( '<td style="text-align: right"><label class="rednao-properties-control-label"> '+RedNaoEscapeHtml(this.PropertyTitle)+' </label></td>'+
            '<td style="text-align: left">'+selectText+' '+tooltip+' </td>');

    var self=this;
    newProperty.find('select').change(function(){
        self.Manipulator.SetValue(self.PropertiesObject,self.PropertyName, (rnJQuery("#"+self.PropertyId).val()),self.AdditionalInformation);
        self.RefreshElement();

    });

    var tooltipOptions={};
    if(typeof this.AdditionalInformation.ToolTip !='undefined'&&typeof this.AdditionalInformation.ToolTip.Width!=undefined)
        tooltipOptions.Width= this.AdditionalInformation.ToolTip.Width;
    newProperty.find('.sfToolTip').tooltip(tooltipOptions);




    newProperty.find('img').click(function(){RedNaoEventManager.Publish('FormulaButtonClicked',{"FormElement":self.FormElement,"PropertyName":self.PropertyName,AdditionalInformation:self.AdditionalInformation,Image:null})});
    return newProperty;
};



function RedNaoIconSelector()
{
    this.$Dialog=null;
    this.$Select=null;
}

RedNaoIconSelector.prototype.Show=function(type,defaultValue,callBack)
{
    this.CallBack=callBack;
    if(this.$Dialog==null)
        this.InitializeDialog();

    if(type=='leftAndRight')
    {
        this.$Dialog.find('.rnBtnAddLeft,.rnBtnAddRight').show();
        this.$Dialog.find('.rnBtnAdd').hide();
    }else{
        this.$Dialog.find('.rnBtnAddLeft,.rnBtnAddRight').hide();
        this.$Dialog.find('.rnBtnAdd').show();
    }
    this.$Dialog.modal('show');

    this.$Select.select2("val",defaultValue);

};

RedNaoIconSelector.prototype.GetIconOptions=function()
{
    return '<option value="fa fa-500px">500px</option>'+
    '<option value="fa fa-adjust">Adjust</option>'+
    '<option value="glyphicon glyphicon-adjust">Adjust</option>'+
    '<option value="fa fa-adn">Adn</option>'+
    '<option value="fa fa-align-center">Align Center</option>'+
    '<option value="glyphicon glyphicon-align-center">Align Center</option>'+
    '<option value="fa fa-align-justify">Align Justify</option>'+
    '<option value="glyphicon glyphicon-align-justify">Align Justify</option>'+
    '<option value="fa fa-align-left">Align Left</option>'+
    '<option value="glyphicon glyphicon-align-left">Align Left</option>'+
    '<option value="fa fa-align-right">Align Right</option>'+
    '<option value="glyphicon glyphicon-align-right">Align Right</option>'+
    '<option value="fa fa-amazon">Amazon</option>'+
    '<option value="fa fa-ambulance">Ambulance</option>'+
    '<option value="fa fa-anchor">Anchor</option>'+
    '<option value="fa fa-android">Android</option>'+
    '<option value="fa fa-angellist">Angellist</option>'+
    '<option value="fa fa-angle-double-down">Angle Double Down</option>'+
    '<option value="fa fa-angle-double-left">Angle Double Left</option>'+
    '<option value="fa fa-angle-double-right">Angle Double Right</option>'+
    '<option value="fa fa-angle-double-up">Angle Double Up</option>'+
    '<option value="fa fa-angle-down">Angle Down</option>'+
    '<option value="fa fa-angle-left">Angle Left</option>'+
    '<option value="fa fa-angle-right">Angle Right</option>'+
    '<option value="fa fa-angle-up">Angle Up</option>'+
    '<option value="fa fa-apple">Apple</option>'+
    '<option value="fa fa-archive">Archive</option>'+
    '<option value="fa fa-area-chart">Area Chart</option>'+
    '<option value="fa fa-arrow-circle-down">Arrow Circle Down</option>'+
    '<option value="fa fa-arrow-circle-o-down">Arrow Circle Down</option>'+
    '<option value="fa fa-arrow-circle-left">Arrow Circle Left</option>'+
    '<option value="fa fa-arrow-circle-o-left">Arrow Circle Left</option>'+
    '<option value="fa fa-arrow-circle-o-right">Arrow Circle Right</option>'+
    '<option value="fa fa-arrow-circle-right">Arrow Circle Right</option>'+
    '<option value="fa fa-arrow-circle-o-up">Arrow Circle Up</option>'+
    '<option value="fa fa-arrow-circle-up">Arrow Circle Up</option>'+
    '<option value="fa fa-arrow-down">Arrow Down</option>'+
    '<option value="glyphicon glyphicon-arrow-down">Arrow Down</option>'+
    '<option value="fa fa-arrow-left">Arrow Left</option>'+
    '<option value="glyphicon glyphicon-arrow-left">Arrow Left</option>'+
    '<option value="fa fa-arrow-right">Arrow Right</option>'+
    '<option value="glyphicon glyphicon-arrow-right">Arrow Right</option>'+
    '<option value="fa fa-arrow-up">Arrow Up</option>'+
    '<option value="glyphicon glyphicon-arrow-up">Arrow Up</option>'+
    '<option value="fa fa-arrows">Arrows</option>'+
    '<option value="fa fa-arrows-alt">Arrows Alt</option>'+
    '<option value="fa fa-arrows-h">Arrows H</option>'+
    '<option value="fa fa-arrows-v">Arrows V</option>'+
    '<option value="fa fa-asterisk">Asterisk</option>'+
    '<option value="glyphicon glyphicon-asterisk">Asterisk</option>'+
    '<option value="fa fa-at">At</option>'+
    '<option value="fa fa-backward">Backward</option>'+
    '<option value="glyphicon glyphicon-backward">Backward</option>'+
    '<option value="fa fa-balance-scale">Balance Scale</option>'+
    '<option value="fa fa-ban">Ban</option>'+
    '<option value="glyphicon glyphicon-ban-circle">Ban Circle</option>'+
    '<option value="fa fa-bar-chart">Bar Chart</option>'+
    '<option value="fa fa-barcode">Barcode</option>'+
    '<option value="glyphicon glyphicon-barcode">Barcode</option>'+
    '<option value="fa fa-bars">Bars</option>'+
    '<option value="fa fa-battery-empty">Battery Empty</option>'+
    '<option value="fa fa-battery-full">Battery Full</option>'+
    '<option value="fa fa-battery-half">Battery Half</option>'+
    '<option value="fa fa-battery-quarter">Battery Quarter</option>'+
    '<option value="fa fa-battery-three-quarters">Battery Three Quarters</option>'+
    '<option value="fa fa-bed">Bed</option>'+
    '<option value="fa fa-beer">Beer</option>'+
    '<option value="fa fa-behance">Behance</option>'+
    '<option value="fa fa-behance-square">Behance Square</option>'+
    '<option value="fa fa-bell">Bell</option>'+
    '<option value="fa fa-bell-o">Bell</option>'+
    '<option value="glyphicon glyphicon-bell">Bell</option>'+
    '<option value="fa fa-bell-slash">Bell Slash</option>'+
    '<option value="fa fa-bell-slash-o">Bell Slash</option>'+
    '<option value="fa fa-bicycle">Bicycle</option>'+
    '<option value="fa fa-binoculars">Binoculars</option>'+
    '<option value="fa fa-birthday-cake">Birthday Cake</option>'+
    '<option value="fa fa-bitbucket">Bitbucket</option>'+
    '<option value="fa fa-bitbucket-square">Bitbucket Square</option>'+
    '<option value="fa fa-black-tie">Black Tie</option>'+
    '<option value="fa fa-bold">Bold</option>'+
    '<option value="glyphicon glyphicon-bold">Bold</option>'+
    '<option value="fa fa-bolt">Bolt</option>'+
    '<option value="fa fa-bomb">Bomb</option>'+
    '<option value="fa fa-book">Book</option>'+
    '<option value="glyphicon glyphicon-book">Book</option>'+
    '<option value="fa fa-bookmark">Bookmark</option>'+
    '<option value="fa fa-bookmark-o">Bookmark</option>'+
    '<option value="glyphicon glyphicon-bookmark">Bookmark</option>'+
    '<option value="fa fa-briefcase">Briefcase</option>'+
    '<option value="glyphicon glyphicon-briefcase">Briefcase</option>'+
    '<option value="fa fa-btc">Btc</option>'+
    '<option value="fa fa-bug">Bug</option>'+
    '<option value="fa fa-building">Building</option>'+
    '<option value="fa fa-building-o">Building</option>'+
    '<option value="fa fa-bullhorn">Bullhorn</option>'+
    '<option value="glyphicon glyphicon-bullhorn">Bullhorn</option>'+
    '<option value="fa fa-bullseye">Bullseye</option>'+
    '<option value="fa fa-bus">Bus</option>'+
    '<option value="fa fa-buysellads">Buysellads</option>'+
    '<option value="fa fa-calculator">Calculator</option>'+
    '<option value="fa fa-calendar">Calendar</option>'+
    '<option value="fa fa-calendar-o">Calendar</option>'+
    '<option value="glyphicon glyphicon-calendar">Calendar</option>'+
    '<option value="fa fa-calendar-check-o">Calendar Check</option>'+
    '<option value="fa fa-calendar-minus-o">Calendar Minus</option>'+
    '<option value="fa fa-calendar-plus-o">Calendar Plus</option>'+
    '<option value="fa fa-calendar-times-o">Calendar Times</option>'+
    '<option value="fa fa-camera">Camera</option>'+
    '<option value="glyphicon glyphicon-camera">Camera</option>'+
    '<option value="fa fa-camera-retro">Camera Retro</option>'+
    '<option value="fa fa-car">Car</option>'+
    '<option value="fa fa-caret-down">Caret Down</option>'+
    '<option value="fa fa-caret-left">Caret Left</option>'+
    '<option value="fa fa-caret-right">Caret Right</option>'+
    '<option value="fa fa-caret-square-o-down">Caret Square Down</option>'+
    '<option value="fa fa-caret-square-o-left">Caret Square Left</option>'+
    '<option value="fa fa-caret-square-o-right">Caret Square Right</option>'+
    '<option value="fa fa-caret-square-o-up">Caret Square Up</option>'+
    '<option value="fa fa-caret-up">Caret Up</option>'+
    '<option value="fa fa-cart-arrow-down">Cart Arrow Down</option>'+
    '<option value="fa fa-cart-plus">Cart Plus</option>'+
    '<option value="fa fa-cc">Cc</option>'+
    '<option value="fa fa-cc-amex">Cc Amex</option>'+
    '<option value="fa fa-cc-diners-club">Cc Diners Club</option>'+
    '<option value="fa fa-cc-discover">Cc Discover</option>'+
    '<option value="fa fa-cc-jcb">Cc Jcb</option>'+
    '<option value="fa fa-cc-mastercard">Cc Mastercard</option>'+
    '<option value="fa fa-cc-paypal">Cc Paypal</option>'+
    '<option value="fa fa-cc-stripe">Cc Stripe</option>'+
    '<option value="fa fa-cc-visa">Cc Visa</option>'+
    '<option value="fa fa-certificate">Certificate</option>'+
    '<option value="glyphicon glyphicon-certificate">Certificate</option>'+
    '<option value="fa fa-chain-broken">Chain Broken</option>'+
    '<option value="fa fa-check">Check</option>'+
    '<option value="glyphicon glyphicon-check">Check</option>'+
    '<option value="fa fa-check-circle">Check Circle</option>'+
    '<option value="fa fa-check-circle-o">Check Circle</option>'+
    '<option value="fa fa-check-square">Check Square</option>'+
    '<option value="fa fa-check-square-o">Check Square</option>'+
    '<option value="fa fa-chevron-circle-down">Chevron Circle Down</option>'+
    '<option value="fa fa-chevron-circle-left">Chevron Circle Left</option>'+
    '<option value="fa fa-chevron-circle-right">Chevron Circle Right</option>'+
    '<option value="fa fa-chevron-circle-up">Chevron Circle Up</option>'+
    '<option value="fa fa-chevron-down">Chevron Down</option>'+
    '<option value="glyphicon glyphicon-chevron-down">Chevron Down</option>'+
    '<option value="fa fa-chevron-left">Chevron Left</option>'+
    '<option value="glyphicon glyphicon-chevron-left">Chevron Left</option>'+
    '<option value="fa fa-chevron-right">Chevron Right</option>'+
    '<option value="glyphicon glyphicon-chevron-right">Chevron Right</option>'+
    '<option value="fa fa-chevron-up">Chevron Up</option>'+
    '<option value="glyphicon glyphicon-chevron-up">Chevron Up</option>'+
    '<option value="fa fa-child">Child</option>'+
    '<option value="fa fa-chrome">Chrome</option>'+
    '<option value="fa fa-circle">Circle</option>'+
    '<option value="fa fa-circle-o">Circle</option>'+
    '<option value="glyphicon glyphicon-circle-arrow-down">Circle Arrow Down</option>'+
    '<option value="glyphicon glyphicon-circle-arrow-left">Circle Arrow Left</option>'+
    '<option value="glyphicon glyphicon-circle-arrow-right">Circle Arrow Right</option>'+
    '<option value="glyphicon glyphicon-circle-arrow-up">Circle Arrow Up</option>'+
    '<option value="fa fa-circle-o-notch">Circle Notch</option>'+
    '<option value="fa fa-circle-thin">Circle Thin</option>'+
    '<option value="fa fa-clipboard">Clipboard</option>'+
    '<option value="fa fa-clock-o">Clock</option>'+
    '<option value="fa fa-clone">Clone</option>'+
    '<option value="fa fa-cloud">Cloud</option>'+
    '<option value="glyphicon glyphicon-cloud">Cloud</option>'+
    '<option value="fa fa-cloud-download">Cloud Download</option>'+
    '<option value="glyphicon glyphicon-cloud-download">Cloud Download</option>'+
    '<option value="fa fa-cloud-upload">Cloud Upload</option>'+
    '<option value="glyphicon glyphicon-cloud-upload">Cloud Upload</option>'+
    '<option value="fa fa-code">Code</option>'+
    '<option value="fa fa-code-fork">Code Fork</option>'+
    '<option value="fa fa-codepen">Codepen</option>'+
    '<option value="fa fa-coffee">Coffee</option>'+
    '<option value="fa fa-cog">Cog</option>'+
    '<option value="glyphicon glyphicon-cog">Cog</option>'+
    '<option value="fa fa-cogs">Cogs</option>'+
    '<option value="glyphicon glyphicon-collapse-down">Collapse Down</option>'+
    '<option value="glyphicon glyphicon-collapse-up">Collapse Up</option>'+
    '<option value="fa fa-columns">Columns</option>'+
    '<option value="fa fa-comment">Comment</option>'+
    '<option value="fa fa-comment-o">Comment</option>'+
    '<option value="glyphicon glyphicon-comment">Comment</option>'+
    '<option value="fa fa-commenting">Commenting</option>'+
    '<option value="fa fa-commenting-o">Commenting</option>'+
    '<option value="fa fa-comments">Comments</option>'+
    '<option value="fa fa-comments-o">Comments</option>'+
    '<option value="fa fa-compass">Compass</option>'+
    '<option value="fa fa-compress">Compress</option>'+
    '<option value="glyphicon glyphicon-compressed">Compressed</option>'+
    '<option value="fa fa-connectdevelop">Connectdevelop</option>'+
    '<option value="fa fa-contao">Contao</option>'+
    '<option value="fa fa-copyright">Copyright</option>'+
    '<option value="glyphicon glyphicon-copyright-mark">Copyright Mark</option>'+
    '<option value="fa fa-creative-commons">Creative Commons</option>'+
    '<option value="fa fa-credit-card">Credit Card</option>'+
    '<option value="glyphicon glyphicon-credit-card">Credit Card</option>'+
    '<option value="fa fa-crop">Crop</option>'+
    '<option value="fa fa-crosshairs">Crosshairs</option>'+
    '<option value="fa fa-css3">Css3</option>'+
    '<option value="fa fa-cube">Cube</option>'+
    '<option value="fa fa-cubes">Cubes</option>'+
    '<option value="fa fa-cutlery">Cutlery</option>'+
    '<option value="glyphicon glyphicon-cutlery">Cutlery</option>'+
    '<option value="glyphicon glyphicon-dashboard">Dashboard</option>'+
    '<option value="fa fa-dashcube">Dashcube</option>'+
    '<option value="fa fa-database">Database</option>'+
    '<option value="fa fa-delicious">Delicious</option>'+
    '<option value="fa fa-desktop">Desktop</option>'+
    '<option value="fa fa-deviantart">Deviantart</option>'+
    '<option value="fa fa-diamond">Diamond</option>'+
    '<option value="fa fa-digg">Digg</option>'+
    '<option value="fa fa-dot-circle-o">Dot Circle</option>'+
    '<option value="fa fa-download">Download</option>'+
    '<option value="glyphicon glyphicon-download">Download</option>'+
    '<option value="glyphicon glyphicon-download-alt">Download Alt</option>'+
    '<option value="fa fa-dribbble">Dribbble</option>'+
    '<option value="fa fa-dropbox">Dropbox</option>'+
    '<option value="fa fa-drupal">Drupal</option>'+
    '<option value="glyphicon glyphicon-earphone">Earphone</option>'+
    '<option value="glyphicon glyphicon-edit">Edit</option>'+
    '<option value="fa fa-eject">Eject</option>'+
    '<option value="glyphicon glyphicon-eject">Eject</option>'+
    '<option value="fa fa-ellipsis-h">Ellipsis H</option>'+
    '<option value="fa fa-ellipsis-v">Ellipsis V</option>'+
    '<option value="glyphicon glyphicon-envelope">Email</option>'+
    '<option value="fa fa-empire">Empire</option>'+
    '<option value="fa fa-envelope">Envelope</option>'+
    '<option value="fa fa-envelope-o">Envelope</option>'+
    '<option value="fa fa-envelope-square">Envelope Square</option>'+
    '<option value="fa fa-eraser">Eraser</option>'+
    '<option value="fa fa-eur">Eur</option>'+
    '<option value="glyphicon glyphicon-euro">Euro</option>'+
    '<option value="fa fa-exchange">Exchange</option>'+
    '<option value="fa fa-exclamation">Exclamation</option>'+
    '<option value="fa fa-exclamation-circle">Exclamation Circle</option>'+
    '<option value="glyphicon glyphicon-exclamation-sign">Exclamation Sign</option>'+
    '<option value="fa fa-exclamation-triangle">Exclamation Triangle</option>'+
    '<option value="fa fa-expand">Expand</option>'+
    '<option value="glyphicon glyphicon-expand">Expand</option>'+
    '<option value="fa fa-expeditedssl">Expeditedssl</option>'+
    '<option value="glyphicon glyphicon-export">Export</option>'+
    '<option value="fa fa-external-link">External Link</option>'+
    '<option value="fa fa-external-link-square">External Link Square</option>'+
    '<option value="fa fa-eye">Eye</option>'+
    '<option value="glyphicon glyphicon-eye-close">Eye Close</option>'+
    '<option value="glyphicon glyphicon-eye-open">Eye Open</option>'+
    '<option value="fa fa-eye-slash">Eye Slash</option>'+
    '<option value="fa fa-eyedropper">Eyedropper</option>'+
    '<option value="fa fa-facebook">Facebook</option>'+
    '<option value="fa fa-facebook-square">Facebook Square</option>'+
    '<option value="fa fa-facebook-official">Facebookfficial</option>'+
    '<option value="glyphicon glyphicon-facetime-video">Facetime Video</option>'+
    '<option value="fa fa-fast-backward">Fast Backward</option>'+
    '<option value="glyphicon glyphicon-fast-backward">Fast Backward</option>'+
    '<option value="fa fa-fast-forward">Fast Forward</option>'+
    '<option value="glyphicon glyphicon-fast-forward">Fast Forward</option>'+
    '<option value="fa fa-fax">Fax</option>'+
    '<option value="fa fa-female">Female</option>'+
    '<option value="fa fa-fighter-jet">Fighter Jet</option>'+
    '<option value="fa fa-file">File</option>'+
    '<option value="fa fa-file-o">File</option>'+
    '<option value="glyphicon glyphicon-file">File</option>'+
    '<option value="fa fa-file-archive-o">File Archive</option>'+
    '<option value="fa fa-file-audio-o">File Audio</option>'+
    '<option value="fa fa-file-code-o">File Code</option>'+
    '<option value="fa fa-file-excel-o">File Excel</option>'+
    '<option value="fa fa-file-image-o">File Image</option>'+
    '<option value="fa fa-file-pdf-o">File Pdf</option>'+
    '<option value="fa fa-file-powerpoint-o">File Powerpoint</option>'+
    '<option value="fa fa-file-text">File Text</option>'+
    '<option value="fa fa-file-text-o">File Text</option>'+
    '<option value="fa fa-file-video-o">File Video</option>'+
    '<option value="fa fa-file-word-o">File Word</option>'+
    '<option value="fa fa-files-o">Files</option>'+
    '<option value="fa fa-film">Film</option>'+
    '<option value="glyphicon glyphicon-film">Film</option>'+
    '<option value="fa fa-filter">Filter</option>'+
    '<option value="glyphicon glyphicon-filter">Filter</option>'+
    '<option value="fa fa-fire">Fire</option>'+
    '<option value="glyphicon glyphicon-fire">Fire</option>'+
    '<option value="fa fa-fire-extinguisher">Fire Extinguisher</option>'+
    '<option value="fa fa-firefox">Firefox</option>'+
    '<option value="fa fa-flag">Flag</option>'+
    '<option value="fa fa-flag-o">Flag</option>'+
    '<option value="glyphicon glyphicon-flag">Flag</option>'+
    '<option value="fa fa-flag-checkered">Flag Checkered</option>'+
    '<option value="glyphicon glyphicon-flash">Flash</option>'+
    '<option value="fa fa-flask">Flask</option>'+
    '<option value="fa fa-flickr">Flickr</option>'+
    '<option value="fa fa-floppy-o">Floppy</option>'+
    '<option value="glyphicon glyphicon-floppy-disk">Floppy Disk</option>'+
    '<option value="glyphicon glyphicon-floppy-open">Floppy Open</option>'+
    '<option value="glyphicon glyphicon-floppy-remove">Floppy Remove</option>'+
    '<option value="glyphicon glyphicon-floppy-save">Floppy Save</option>'+
    '<option value="glyphicon glyphicon-floppy-saved">Floppy Saved</option>'+
    '<option value="fa fa-folder">Folder</option>'+
    '<option value="fa fa-folder-o">Folder</option>'+
    '<option value="glyphicon glyphicon-folder-close">Folder Close</option>'+
    '<option value="glyphicon glyphicon-folder-open">Folder Open</option>'+
    '<option value="fa fa-folder-open">Folderpen</option>'+
    '<option value="fa fa-folder-open-o">Folderpen</option>'+
    '<option value="fa fa-font">Font</option>'+
    '<option value="glyphicon glyphicon-font">Font</option>'+
    '<option value="fa fa-fonticons">Fonticons</option>'+
    '<option value="fa fa-forumbee">Forumbee</option>'+
    '<option value="fa fa-forward">Forward</option>'+
    '<option value="glyphicon glyphicon-forward">Forward</option>'+
    '<option value="fa fa-foursquare">Foursquare</option>'+
    '<option value="fa fa-frown-o">Frown</option>'+
    '<option value="glyphicon glyphicon-fullscreen">Fullscreen</option>'+
    '<option value="fa fa-futbol-o">Futbol</option>'+
    '<option value="fa fa-gamepad">Gamepad</option>'+
    '<option value="fa fa-gavel">Gavel</option>'+
    '<option value="fa fa-gbp">Gbp</option>'+
    '<option value="glyphicon glyphicon-gbp">Gbp</option>'+
    '<option value="fa fa-genderless">Genderless</option>'+
    '<option value="fa fa-get-pocket">Get Pocket</option>'+
    '<option value="fa fa-gg">Gg</option>'+
    '<option value="fa fa-gg-circle">Gg Circle</option>'+
    '<option value="fa fa-gift">Gift</option>'+
    '<option value="glyphicon glyphicon-gift">Gift</option>'+
    '<option value="fa fa-git">Git</option>'+
    '<option value="fa fa-git-square">Git Square</option>'+
    '<option value="fa fa-github">Github</option>'+
    '<option value="fa fa-github-alt">Github Alt</option>'+
    '<option value="fa fa-github-square">Github Square</option>'+
    '<option value="fa fa-glass">Glass</option>'+
    '<option value="glyphicon glyphicon-glass">Glass</option>'+
    '<option value="fa fa-globe">Globe</option>'+
    '<option value="glyphicon glyphicon-globe">Globe</option>'+
    '<option value="fa fa-google">Google</option>'+
    '<option value="fa fa-google-plus">Google Plus</option>'+
    '<option value="fa fa-google-plus-square">Google Plus Square</option>'+
    '<option value="fa fa-google-wallet">Google Wallet</option>'+
    '<option value="fa fa-graduation-cap">Graduation Cap</option>'+
    '<option value="fa fa-gratipay">Gratipay</option>'+
    '<option value="fa fa-h-square">H Square</option>'+
    '<option value="fa fa-hacker-news">Hacker News</option>'+
    '<option value="fa fa-hand-o-down">Hand Down</option>'+
    '<option value="glyphicon glyphicon-hand-down">Hand Down</option>'+
    '<option value="fa fa-hand-o-left">Hand Left</option>'+
    '<option value="glyphicon glyphicon-hand-left">Hand Left</option>'+
    '<option value="fa fa-hand-lizard-o">Hand Lizard</option>'+
    '<option value="fa fa-hand-paper-o">Hand Paper</option>'+
    '<option value="fa fa-hand-peace-o">Hand Peace</option>'+
    '<option value="fa fa-hand-pointer-o">Hand Pointer</option>'+
    '<option value="fa fa-hand-o-right">Hand Right</option>'+
    '<option value="glyphicon glyphicon-hand-right">Hand Right</option>'+
    '<option value="fa fa-hand-rock-o">Hand Rock</option>'+
    '<option value="fa fa-hand-scissors-o">Hand Scissors</option>'+
    '<option value="fa fa-hand-spock-o">Hand Spock</option>'+
    '<option value="fa fa-hand-o-up">Hand Up</option>'+
    '<option value="glyphicon glyphicon-hand-up">Hand Up</option>'+
    '<option value="glyphicon glyphicon-hd-video">Hd Video</option>'+
    '<option value="fa fa-hdd-o">Hdd</option>'+
    '<option value="glyphicon glyphicon-hdd">Hdd</option>'+
    '<option value="fa fa-header">Header</option>'+
    '<option value="glyphicon glyphicon-header">Header</option>'+
    '<option value="fa fa-headphones">Headphones</option>'+
    '<option value="glyphicon glyphicon-headphones">Headphones</option>'+
    '<option value="fa fa-heart">Heart</option>'+
    '<option value="fa fa-heart-o">Heart</option>'+
    '<option value="glyphicon glyphicon-heart">Heart</option>'+
    '<option value="glyphicon glyphicon-heart-empty">Heart Empty</option>'+
    '<option value="fa fa-heartbeat">Heartbeat</option>'+
    '<option value="fa fa-history">History</option>'+
    '<option value="fa fa-home">Home</option>'+
    '<option value="glyphicon glyphicon-home">Home</option>'+
    '<option value="fa fa-hospital-o">Hospital</option>'+
    '<option value="fa fa-hourglass-o">Hourglass</option>'+
    '<option value="fa fa-hourglass">Hourglass</option>'+
    '<option value="fa fa-hourglass-end">Hourglass End</option>'+
    '<option value="fa fa-hourglass-half">Hourglass Half</option>'+
    '<option value="fa fa-hourglass-start">Hourglass Start</option>'+
    '<option value="fa fa-houzz">Houzz</option>'+
    '<option value="fa fa-html5">Html5</option>'+
    '<option value="fa fa-i-cursor">I Cursor</option>'+
    '<option value="fa fa-ils">Ils</option>'+
    '<option value="glyphicon glyphicon-import">Import</option>'+
    '<option value="fa fa-inbox">Inbox</option>'+
    '<option value="glyphicon glyphicon-inbox">Inbox</option>'+
    '<option value="fa fa-indent">Indent</option>'+
    '<option value="glyphicon glyphicon-indent-left">Indent Left</option>'+
    '<option value="glyphicon glyphicon-indent-right">Indent Right</option>'+
    '<option value="fa fa-industry">Industry</option>'+
    '<option value="fa fa-info">Info</option>'+
    '<option value="fa fa-info-circle">Info Circle</option>'+
    '<option value="glyphicon glyphicon-info-sign">Info Sign</option>'+
    '<option value="fa fa-inr">Inr</option>'+
    '<option value="fa fa-instagram">Instagram</option>'+
    '<option value="fa fa-internet-explorer">Internet Explorer</option>'+
    '<option value="fa fa-ioxhost">Ioxhost</option>'+
    '<option value="fa fa-italic">Italic</option>'+
    '<option value="glyphicon glyphicon-italic">Italic</option>'+
    '<option value="fa fa-joomla">Joomla</option>'+
    '<option value="fa fa-jpy">Jpy</option>'+
    '<option value="fa fa-jsfiddle">Jsfiddle</option>'+
    '<option value="fa fa-key">Key</option>'+
    '<option value="fa fa-keyboard-o">Keyboard</option>'+
    '<option value="fa fa-krw">Krw</option>'+
    '<option value="fa fa-language">Language</option>'+
    '<option value="fa fa-laptop">Laptop</option>'+
    '<option value="fa fa-lastfm">Lastfm</option>'+
    '<option value="fa fa-lastfm-square">Lastfm Square</option>'+
    '<option value="fa fa-leaf">Leaf</option>'+
    '<option value="glyphicon glyphicon-leaf">Leaf</option>'+
    '<option value="fa fa-leanpub">Leanpub</option>'+
    '<option value="fa fa-lemon-o">Lemon</option>'+
    '<option value="fa fa-level-down">Level Down</option>'+
    '<option value="fa fa-level-up">Level Up</option>'+
    '<option value="fa fa-life-ring">Life Ring</option>'+
    '<option value="fa fa-lightbulb-o">Lightbulb</option>'+
    '<option value="fa fa-line-chart">Line Chart</option>'+
    '<option value="fa fa-link">Link</option>'+
    '<option value="glyphicon glyphicon-link">Link</option>'+
    '<option value="fa fa-linkedin">Linkedin</option>'+
    '<option value="fa fa-linkedin-square">Linkedin Square</option>'+
    '<option value="fa fa-linux">Linux</option>'+
    '<option value="fa fa-list">List</option>'+
    '<option value="glyphicon glyphicon-list">List</option>'+
    '<option value="fa fa-list-alt">List Alt</option>'+
    '<option value="glyphicon glyphicon-list-alt">List Alt</option>'+
    '<option value="fa fa-list-ul">List Ul</option>'+
    '<option value="fa fa-list-ol">Listl</option>'+
    '<option value="fa fa-location-arrow">Location Arrow</option>'+
    '<option value="fa fa-lock">Lock</option>'+
    '<option value="glyphicon glyphicon-lock">Lock</option>'+
    '<option value="glyphicon glyphicon-log-in">Log In</option>'+
    '<option value="glyphicon glyphicon-log-out">Log Out</option>'+
    '<option value="fa fa-long-arrow-down">Long Arrow Down</option>'+
    '<option value="fa fa-long-arrow-left">Long Arrow Left</option>'+
    '<option value="fa fa-long-arrow-right">Long Arrow Right</option>'+
    '<option value="fa fa-long-arrow-up">Long Arrow Up</option>'+
    '<option value="fa fa-magic">Magic</option>'+
    '<option value="fa fa-magnet">Magnet</option>'+
    '<option value="glyphicon glyphicon-magnet">Magnet</option>'+
    '<option value="fa fa-male">Male</option>'+
    '<option value="fa fa-map-o">Map</option>'+
    '<option value="fa fa-map">Map</option>'+
    '<option value="fa fa-map-marker">Map Marker</option>'+
    '<option value="glyphicon glyphicon-map-marker">Map Marker</option>'+
    '<option value="fa fa-map-pin">Map Pin</option>'+
    '<option value="fa fa-map-signs">Map Signs</option>'+
    '<option value="fa fa-mars">Mars</option>'+
    '<option value="fa fa-mars-double">Mars Double</option>'+
    '<option value="fa fa-mars-stroke">Mars Stroke</option>'+
    '<option value="fa fa-mars-stroke-h">Mars Stroke H</option>'+
    '<option value="fa fa-mars-stroke-v">Mars Stroke V</option>'+
    '<option value="fa fa-maxcdn">Maxcdn</option>'+
    '<option value="fa fa-meanpath">Meanpath</option>'+
    '<option value="fa fa-medium">Medium</option>'+
    '<option value="fa fa-medkit">Medkit</option>'+
    '<option value="fa fa-meh-o">Meh</option>'+
    '<option value="fa fa-mercury">Mercury</option>'+
    '<option value="fa fa-microphone">Microphone</option>'+
    '<option value="fa fa-microphone-slash">Microphone Slash</option>'+
    '<option value="fa fa-minus">Minus</option>'+
    '<option value="glyphicon glyphicon-minus">Minus</option>'+
    '<option value="fa fa-minus-circle">Minus Circle</option>'+
    '<option value="glyphicon glyphicon-minus-sign">Minus Sign</option>'+
    '<option value="fa fa-minus-square">Minus Square</option>'+
    '<option value="fa fa-minus-square-o">Minus Square</option>'+
    '<option value="fa fa-mobile">Mobile</option>'+
    '<option value="fa fa-money">Money</option>'+
    '<option value="fa fa-moon-o">Moon</option>'+
    '<option value="fa fa-motorcycle">Motorcycle</option>'+
    '<option value="fa fa-mouse-pointer">Mouse Pointer</option>'+
    '<option value="glyphicon glyphicon-move">Move</option>'+
    '<option value="fa fa-music">Music</option>'+
    '<option value="glyphicon glyphicon-music">Music</option>'+
    '<option value="fa fa-neuter">Neuter</option>'+
    '<option value="glyphicon glyphicon-new-window">New Window</option>'+
    '<option value="fa fa-newspaper-o">Newspaper</option>'+
    '<option value="fa fa-object-group">Object Group</option>'+
    '<option value="fa fa-object-ungroup">Object Ungroup</option>'+
    '<option value="fa fa-odnoklassniki">Odnoklassniki</option>'+
    '<option value="fa fa-odnoklassniki-square">Odnoklassniki Square</option>'+
    '<option value="glyphicon glyphicon-off">Off</option>'+
    '<option value="glyphicon glyphicon-ok">Ok</option>'+
    '<option value="glyphicon glyphicon-ok-circle">Ok Circle</option>'+
    '<option value="glyphicon glyphicon-ok-sign">Ok Sign</option>'+
    '<option value="glyphicon glyphicon-open">Open</option>'+
    '<option value="fa fa-opencart">Opencart</option>'+
    '<option value="fa fa-openid">Openid</option>'+
    '<option value="fa fa-opera">Opera</option>'+
    '<option value="fa fa-optin-monster">Optin Monster</option>'+
    '<option value="fa fa-outdent">Outdent</option>'+
    '<option value="fa fa-pagelines">Pagelines</option>'+
    '<option value="fa fa-paint-brush">Paint Brush</option>'+
    '<option value="fa fa-paper-plane">Paper Plane</option>'+
    '<option value="fa fa-paper-plane-o">Paper Plane</option>'+
    '<option value="fa fa-paperclip">Paperclip</option>'+
    '<option value="glyphicon glyphicon-paperclip">Paperclip</option>'+
    '<option value="fa fa-paragraph">Paragraph</option>'+
    '<option value="fa fa-pause">Pause</option>'+
    '<option value="glyphicon glyphicon-pause">Pause</option>'+
    '<option value="fa fa-paw">Paw</option>'+
    '<option value="fa fa-paypal">Paypal</option>'+
    '<option value="fa fa-pencil">Pencil</option>'+
    '<option value="glyphicon glyphicon-pencil">Pencil</option>'+
    '<option value="fa fa-pencil-square">Pencil Square</option>'+
    '<option value="fa fa-pencil-square-o">Pencil Square</option>'+
    '<option value="fa fa-phone">Phone</option>'+
    '<option value="glyphicon glyphicon-phone">Phone</option>'+
    '<option value="glyphicon glyphicon-phone-alt">Phone Alt</option>'+
    '<option value="fa fa-phone-square">Phone Square</option>'+
    '<option value="fa fa-picture-o">Picture</option>'+
    '<option value="glyphicon glyphicon-picture">Picture</option>'+
    '<option value="fa fa-pie-chart">Pie Chart</option>'+
    '<option value="fa fa-pied-piper">Pied Piper</option>'+
    '<option value="fa fa-pied-piper-alt">Pied Piper Alt</option>'+
    '<option value="fa fa-pinterest">Pinterest</option>'+
    '<option value="fa fa-pinterest-p">Pinterest P</option>'+
    '<option value="fa fa-pinterest-square">Pinterest Square</option>'+
    '<option value="fa fa-plane">Plane</option>'+
    '<option value="glyphicon glyphicon-plane">Plane</option>'+
    '<option value="fa fa-play">Play</option>'+
    '<option value="glyphicon glyphicon-play">Play</option>'+
    '<option value="fa fa-play-circle">Play Circle</option>'+
    '<option value="fa fa-play-circle-o">Play Circle</option>'+
    '<option value="glyphicon glyphicon-play-circle">Play Circle</option>'+
    '<option value="fa fa-plug">Plug</option>'+
    '<option value="fa fa-plus">Plus</option>'+
    '<option value="glyphicon glyphicon-plus">Plus</option>'+
    '<option value="fa fa-plus-circle">Plus Circle</option>'+
    '<option value="glyphicon glyphicon-plus-sign">Plus Sign</option>'+
    '<option value="fa fa-plus-square-o">Plus Square</option>'+
    '<option value="fa fa-plus-square">Plus Square</option>'+
    '<option value="fa fa-power-off">Powerff</option>'+
    '<option value="fa fa-print">Print</option>'+
    '<option value="glyphicon glyphicon-print">Print</option>'+
    '<option value="glyphicon glyphicon-pushpin">Pushpin</option>'+
    '<option value="fa fa-puzzle-piece">Puzzle Piece</option>'+
    '<option value="fa fa-qq">Qq</option>'+
    '<option value="fa fa-qrcode">Qrcode</option>'+
    '<option value="glyphicon glyphicon-qrcode">Qrcode</option>'+
    '<option value="fa fa-question">Question</option>'+
    '<option value="fa fa-question-circle">Question Circle</option>'+
    '<option value="glyphicon glyphicon-question-sign">Question Sign</option>'+
    '<option value="fa fa-quote-left">Quote Left</option>'+
    '<option value="fa fa-quote-right">Quote Right</option>'+
    '<option value="fa fa-random">Random</option>'+
    '<option value="glyphicon glyphicon-random">Random</option>'+
    '<option value="fa fa-rebel">Rebel</option>'+
    '<option value="glyphicon glyphicon-record">Record</option>'+
    '<option value="fa fa-recycle">Recycle</option>'+
    '<option value="fa fa-reddit">Reddit</option>'+
    '<option value="fa fa-reddit-square">Reddit Square</option>'+
    '<option value="fa fa-refresh">Refresh</option>'+
    '<option value="glyphicon glyphicon-refresh">Refresh</option>'+
    '<option value="fa fa-registered">Registered</option>'+
    '<option value="glyphicon glyphicon-registration-mark">Registration Mark</option>'+
    '<option value="glyphicon glyphicon-remove">Remove</option>'+
    '<option value="glyphicon glyphicon-remove-circle">Remove Circle</option>'+
    '<option value="glyphicon glyphicon-remove-sign">Remove Sign</option>'+
    '<option value="fa fa-renren">Renren</option>'+
    '<option value="fa fa-repeat">Repeat</option>'+
    '<option value="glyphicon glyphicon-repeat">Repeat</option>'+
    '<option value="fa fa-reply">Reply</option>'+
    '<option value="fa fa-reply-all">Reply All</option>'+
    '<option value="glyphicon glyphicon-resize-full">Resize Full</option>'+
    '<option value="glyphicon glyphicon-resize-horizontal">Resize Horizontal</option>'+
    '<option value="glyphicon glyphicon-resize-small">Resize Small</option>'+
    '<option value="glyphicon glyphicon-resize-vertical">Resize Vertical</option>'+
    '<option value="fa fa-retweet">Retweet</option>'+
    '<option value="glyphicon glyphicon-retweet">Retweet</option>'+
    '<option value="fa fa-road">Road</option>'+
    '<option value="glyphicon glyphicon-road">Road</option>'+
    '<option value="fa fa-rocket">Rocket</option>'+
    '<option value="fa fa-rss">Rss</option>'+
    '<option value="fa fa-rss-square">Rss Square</option>'+
    '<option value="fa fa-rub">Rub</option>'+
    '<option value="fa fa-safari">Safari</option>'+
    '<option value="glyphicon glyphicon-save">Save</option>'+
    '<option value="glyphicon glyphicon-saved">Saved</option>'+
    '<option value="fa fa-scissors">Scissors</option>'+
    '<option value="glyphicon glyphicon-screenshot">Screenshot</option>'+
    '<option value="glyphicon glyphicon-sd-video">Sd Video</option>'+
    '<option value="fa fa-search">Search</option>'+
    '<option value="glyphicon glyphicon-search">Search</option>'+
    '<option value="fa fa-search-minus">Search Minus</option>'+
    '<option value="fa fa-search-plus">Search Plus</option>'+
    '<option value="fa fa-sellsy">Sellsy</option>'+
    '<option value="glyphicon glyphicon-send">Send</option>'+
    '<option value="fa fa-server">Server</option>'+
    '<option value="fa fa-share">Share</option>'+
    '<option value="glyphicon glyphicon-share">Share</option>'+
    '<option value="fa fa-share-alt">Share Alt</option>'+
    '<option value="glyphicon glyphicon-share-alt">Share Alt</option>'+
    '<option value="fa fa-share-alt-square">Share Alt Square</option>'+
    '<option value="fa fa-share-square">Share Square</option>'+
    '<option value="fa fa-share-square-o">Share Square</option>'+
    '<option value="fa fa-shield">Shield</option>'+
    '<option value="fa fa-ship">Ship</option>'+
    '<option value="fa fa-shirtsinbulk">Shirtsinbulk</option>'+
    '<option value="fa fa-shopping-cart">Shopping Cart</option>'+
    '<option value="glyphicon glyphicon-shopping-cart">Shopping Cart</option>'+
    '<option value="fa fa-sign-in">Sign In</option>'+
    '<option value="fa fa-signal">Signal</option>'+
    '<option value="glyphicon glyphicon-signal">Signal</option>'+
    '<option value="fa fa-sign-out">Signut</option>'+
    '<option value="fa fa-simplybuilt">Simplybuilt</option>'+
    '<option value="fa fa-sitemap">Sitemap</option>'+
    '<option value="fa fa-skyatlas">Skyatlas</option>'+
    '<option value="fa fa-skype">Skype</option>'+
    '<option value="fa fa-slack">Slack</option>'+
    '<option value="fa fa-sliders">Sliders</option>'+
    '<option value="fa fa-slideshare">Slideshare</option>'+
    '<option value="fa fa-smile-o">Smile</option>'+
    '<option value="fa fa-sort">Sort</option>'+
    '<option value="glyphicon glyphicon-sort">Sort</option>'+
    '<option value="fa fa-sort-alpha-asc">Sort Alpha Asc</option>'+
    '<option value="fa fa-sort-alpha-desc">Sort Alpha Desc</option>'+
    '<option value="fa fa-sort-amount-asc">Sort Amount Asc</option>'+
    '<option value="fa fa-sort-amount-desc">Sort Amount Desc</option>'+
    '<option value="fa fa-sort-asc">Sort Asc</option>'+
    '<option value="glyphicon glyphicon-sort-by-alphabet">Sort By Alphabet</option>'+
    '<option value="glyphicon glyphicon-sort-by-alphabet-alt">Sort By Alphabet Alt</option>'+
    '<option value="glyphicon glyphicon-sort-by-attributes">Sort By Attributes</option>'+
    '<option value="glyphicon glyphicon-sort-by-attributes-alt">Sort By Attributes Alt</option>'+
    '<option value="glyphicon glyphicon-sort-by-order">Sort By Order</option>'+
    '<option value="glyphicon glyphicon-sort-by-order-alt">Sort By Order Alt</option>'+
    '<option value="fa fa-sort-desc">Sort Desc</option>'+
    '<option value="fa fa-sort-numeric-asc">Sort Numeric Asc</option>'+
    '<option value="fa fa-sort-numeric-desc">Sort Numeric Desc</option>'+
    '<option value="glyphicon glyphicon-sound-5-1">Sound 5 1</option>'+
    '<option value="glyphicon glyphicon-sound-6-1">Sound 6 1</option>'+
    '<option value="glyphicon glyphicon-sound-7-1">Sound 7 1</option>'+
    '<option value="glyphicon glyphicon-sound-dolby">Sound Dolby</option>'+
    '<option value="glyphicon glyphicon-sound-stereo">Sound Stereo</option>'+
    '<option value="fa fa-soundcloud">Soundcloud</option>'+
    '<option value="fa fa-space-shuttle">Space Shuttle</option>'+
    '<option value="fa fa-spinner">Spinner</option>'+
    '<option value="fa fa-spoon">Spoon</option>'+
    '<option value="fa fa-spotify">Spotify</option>'+
    '<option value="fa fa-square">Square</option>'+
    '<option value="fa fa-square-o">Square</option>'+
    '<option value="fa fa-stack-exchange">Stack Exchange</option>'+
    '<option value="fa fa-stack-overflow">Stackverflow</option>'+
    '<option value="fa fa-star">Star</option>'+
    '<option value="fa fa-star-o">Star</option>'+
    '<option value="glyphicon glyphicon-star">Star</option>'+
    '<option value="glyphicon glyphicon-star-empty">Star Empty</option>'+
    '<option value="fa fa-star-half">Star Half</option>'+
    '<option value="fa fa-star-half-o">Star Half</option>'+
    '<option value="glyphicon glyphicon-stats">Stats</option>'+
    '<option value="fa fa-steam">Steam</option>'+
    '<option value="fa fa-steam-square">Steam Square</option>'+
    '<option value="fa fa-step-backward">Step Backward</option>'+
    '<option value="glyphicon glyphicon-step-backward">Step Backward</option>'+
    '<option value="fa fa-step-forward">Step Forward</option>'+
    '<option value="glyphicon glyphicon-step-forward">Step Forward</option>'+
    '<option value="fa fa-stethoscope">Stethoscope</option>'+
    '<option value="fa fa-sticky-note">Sticky Note</option>'+
    '<option value="fa fa-sticky-note-o">Sticky Note</option>'+
    '<option value="fa fa-stop">Stop</option>'+
    '<option value="glyphicon glyphicon-stop">Stop</option>'+
    '<option value="fa fa-street-view">Street View</option>'+
    '<option value="fa fa-strikethrough">Strikethrough</option>'+
    '<option value="fa fa-stumbleupon">Stumbleupon</option>'+
    '<option value="fa fa-stumbleupon-circle">Stumbleupon Circle</option>'+
    '<option value="fa fa-subscript">Subscript</option>'+
    '<option value="glyphicon glyphicon-subtitles">Subtitles</option>'+
    '<option value="fa fa-subway">Subway</option>'+
    '<option value="fa fa-suitcase">Suitcase</option>'+
    '<option value="fa fa-sun-o">Sun</option>'+
    '<option value="fa fa-superscript">Superscript</option>'+
    '<option value="fa fa-table">Table</option>'+
    '<option value="fa fa-tablet">Tablet</option>'+
    '<option value="fa fa-tachometer">Tachometer</option>'+
    '<option value="fa fa-tag">Tag</option>'+
    '<option value="glyphicon glyphicon-tag">Tag</option>'+
    '<option value="fa fa-tags">Tags</option>'+
    '<option value="glyphicon glyphicon-tags">Tags</option>'+
    '<option value="fa fa-tasks">Tasks</option>'+
    '<option value="glyphicon glyphicon-tasks">Tasks</option>'+
    '<option value="fa fa-taxi">Taxi</option>'+
    '<option value="fa fa-television">Television</option>'+
    '<option value="fa fa-tencent-weibo">Tencent Weibo</option>'+
    '<option value="fa fa-terminal">Terminal</option>'+
    '<option value="fa fa-text-height">Text Height</option>'+
    '<option value="glyphicon glyphicon-text-height">Text Height</option>'+
    '<option value="fa fa-text-width">Text Width</option>'+
    '<option value="glyphicon glyphicon-text-width">Text Width</option>'+
    '<option value="fa fa-th">Th</option>'+
    '<option value="glyphicon glyphicon-th">Th</option>'+
    '<option value="fa fa-th-large">Th Large</option>'+
    '<option value="glyphicon glyphicon-th-large">Th Large</option>'+
    '<option value="fa fa-th-list">Th List</option>'+
    '<option value="glyphicon glyphicon-th-list">Th List</option>'+
    '<option value="fa fa-thumb-tack">Thumb Tack</option>'+
    '<option value="fa fa-thumbs-down">Thumbs Down</option>'+
    '<option value="fa fa-thumbs-o-down">Thumbs Down</option>'+
    '<option value="glyphicon glyphicon-thumbs-down">Thumbs Down</option>'+
    '<option value="fa fa-thumbs-o-up">Thumbs Up</option>'+
    '<option value="fa fa-thumbs-up">Thumbs Up</option>'+
    '<option value="glyphicon glyphicon-thumbs-up">Thumbs Up</option>'+
    '<option value="fa fa-ticket">Ticket</option>'+
    '<option value="glyphicon glyphicon-time">Time</option>'+
    '<option value="fa fa-times">Times</option>'+
    '<option value="fa fa-times-circle">Times Circle</option>'+
    '<option value="fa fa-times-circle-o">Times Circle</option>'+
    '<option value="fa fa-tint">Tint</option>'+
    '<option value="glyphicon glyphicon-tint">Tint</option>'+
    '<option value="fa fa-toggle-off">Toggleff</option>'+
    '<option value="fa fa-toggle-on">Togglen</option>'+
    '<option value="glyphicon glyphicon-tower">Tower</option>'+
    '<option value="fa fa-trademark">Trademark</option>'+
    '<option value="fa fa-train">Train</option>'+
    '<option value="glyphicon glyphicon-transfer">Transfer</option>'+
    '<option value="fa fa-transgender">Transgender</option>'+
    '<option value="fa fa-transgender-alt">Transgender Alt</option>'+
    '<option value="fa fa-trash">Trash</option>'+
    '<option value="fa fa-trash-o">Trash</option>'+
    '<option value="glyphicon glyphicon-trash">Trash</option>'+
    '<option value="fa fa-tree">Tree</option>'+
    '<option value="glyphicon glyphicon-tree-conifer">Tree Conifer</option>'+
    '<option value="glyphicon glyphicon-tree-deciduous">Tree Deciduous</option>'+
    '<option value="fa fa-trello">Trello</option>'+
    '<option value="fa fa-tripadvisor">Tripadvisor</option>'+
    '<option value="fa fa-trophy">Trophy</option>'+
    '<option value="fa fa-truck">Truck</option>'+
    '<option value="fa fa-try">Try</option>'+
    '<option value="fa fa-tty">Tty</option>'+
    '<option value="fa fa-tumblr">Tumblr</option>'+
    '<option value="fa fa-tumblr-square">Tumblr Square</option>'+
    '<option value="fa fa-twitch">Twitch</option>'+
    '<option value="fa fa-twitter">Twitter</option>'+
    '<option value="fa fa-twitter-square">Twitter Square</option>'+
    '<option value="fa fa-umbrella">Umbrella</option>'+
    '<option value="glyphicon glyphicon-unchecked">Unchecked</option>'+
    '<option value="fa fa-underline">Underline</option>'+
    '<option value="fa fa-undo">Undo</option>'+
    '<option value="fa fa-university">University</option>'+
    '<option value="fa fa-unlock">Unlock</option>'+
    '<option value="fa fa-unlock-alt">Unlock Alt</option>'+
    '<option value="fa fa-upload">Upload</option>'+
    '<option value="glyphicon glyphicon-upload">Upload</option>'+
    '<option value="fa fa-usd">Usd</option>'+
    '<option value="glyphicon glyphicon-usd">Usd</option>'+
    '<option value="fa fa-user">User</option>'+
    '<option value="glyphicon glyphicon-user">User</option>'+
    '<option value="fa fa-user-md">User Md</option>'+
    '<option value="fa fa-user-plus">User Plus</option>'+
    '<option value="fa fa-user-secret">User Secret</option>'+
    '<option value="fa fa-user-times">User Times</option>'+
    '<option value="fa fa-users">Users</option>'+
    '<option value="fa fa-venus">Venus</option>'+
    '<option value="fa fa-venus-double">Venus Double</option>'+
    '<option value="fa fa-venus-mars">Venus Mars</option>'+
    '<option value="fa fa-viacoin">Viacoin</option>'+
    '<option value="fa fa-video-camera">Video Camera</option>'+
    '<option value="fa fa-vimeo">Vimeo</option>'+
    '<option value="fa fa-vimeo-square">Vimeo Square</option>'+
    '<option value="fa fa-vine">Vine</option>'+
    '<option value="fa fa-vk">Vk</option>'+
    '<option value="fa fa-volume-down">Volume Down</option>'+
    '<option value="glyphicon glyphicon-volume-down">Volume Down</option>'+
    '<option value="glyphicon glyphicon-volume-off">Volume Off</option>'+
    '<option value="fa fa-volume-up">Volume Up</option>'+
    '<option value="glyphicon glyphicon-volume-up">Volume Up</option>'+
    '<option value="fa fa-volume-off">Volumeff</option>'+
    '<option value="glyphicon glyphicon-warning-sign">Warning Sign</option>'+
    '<option value="fa fa-weibo">Weibo</option>'+
    '<option value="fa fa-weixin">Weixin</option>'+
    '<option value="fa fa-whatsapp">Whatsapp</option>'+
    '<option value="fa fa-wheelchair">Wheelchair</option>'+
    '<option value="fa fa-wifi">Wifi</option>'+
    '<option value="fa fa-wikipedia-w">Wikipedia W</option>'+
    '<option value="fa fa-windows">Windows</option>'+
    '<option value="fa fa-wordpress">Wordpress</option>'+
    '<option value="fa fa-wrench">Wrench</option>'+
    '<option value="glyphicon glyphicon-wrench">Wrench</option>'+
    '<option value="fa fa-xing">Xing</option>'+
    '<option value="fa fa-xing-square">Xing Square</option>'+
    '<option value="fa fa-y-combinator">Y Combinator</option>'+
    '<option value="fa fa-yahoo">Yahoo</option>'+
    '<option value="fa fa-yelp">Yelp</option>'+
    '<option value="fa fa-youtube">Youtube</option>'+
    '<option value="fa fa-youtube-play">Youtube Play</option>'+
    '<option value="fa fa-youtube-square">Youtube Square</option>'+
    '<option value="glyphicon glyphicon-zoom-in">Zoom In</option>'+
    '<option value="glyphicon glyphicon-zoom-out">Zoom Out</option>'
    ;
};

RedNaoIconSelector.prototype.InitializeDialog=function()
{
    this.$Dialog=rnJQuery(
        '<div class="modal fade"  tabindex="-1">'+
            '<div class="modal-dialog">'+
            '<div class="modal-content">'+
            '<div class="modal-header">'+
            '<h4 style="display: inline" class="modal-title">Select one icon from the list</h4>'+
            '</div>'+
            '<div class="modal-body">'+
            '<select id="rnIconSelect" style="display: block;margin: 0 auto;">'+
            '<option value="">None</option>'+
            this.GetIconOptions()+
            '</select>'+
            '</div>'+
            '<div class="modal-footer">'+
            '<button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>Cancel</button>'+
            '<button type="button" class="btn btn-success rnBtnAddLeft"><span class="glyphicon glyphicon-hand-left"></span>Add to left</button>'+
            '<button type="button" class="btn btn-success rnBtnAddRight">Add to right <span class="glyphicon glyphicon-hand-right"></span></button>'+
            '<button type="button" class="btn btn-success rnBtnAdd"><span class="glyphicon glyphicon-plus"></span>Add</button>'+
            '</div>'+
            '</div>'+
            '</div>'+
            '</div>');



    var $container=rnJQuery('<div class="bootstrap-wrapper"></div>');
    $container.append(this.$Dialog);
    rnJQuery('body').append($container);
    var formattingFunction=function(state)
    {
        return '<span style="display: inline;margin-right: 5px;" class="'+state.id+'"></span><span>'+state.text+'</span>'
    };
    this.$Select=rnJQuery('#rnIconSelect').select2({
        width:'300px',
        formatResult:formattingFunction,
        formatSelection:formattingFunction
    });
    var self=this;
    rnJQuery('.select2-results').addClass('bootstrap-wrapper');
    rnJQuery('.rnBtnAddLeft').click(function(){self.FireAddIconCallBack('Left')});
    rnJQuery('.rnBtnAddRight').click(function(){self.FireAddIconCallBack('Right')});
    rnJQuery('.rnBtnAdd').click(function(){self.FireAddIconCallBack('Add')});
};

RedNaoIconSelector.prototype.FireAddIconCallBack=function(orientation)
{
    this.CallBack(this.$Select.val(),orientation);
    this.$Dialog.modal('hide');
};


var RedNaoIconSelectorVar=new RedNaoIconSelector();



/************************************************************************************* Icon Property ***************************************************************************************************/


function IconProperty(formelement,propertiesObject,propertyName,propertyTitle,additionalInformation)
{
    ElementPropertiesBase.call(this,formelement,propertiesObject,propertyName,propertyTitle,additionalInformation);
}

IconProperty.prototype=Object.create(ElementPropertiesBase.prototype);


IconProperty.prototype.GenerateHtml=function()
{

    var value=this.GetPropertyCurrentValue().ClassName;
    var newProperty=rnJQuery( '<td style="text-align: right"><label class="rednao-properties-control-label"> '+this.PropertyTitle+' </label></td>\
            <td style="text-align: left"><span class="'+RedNaoEscapeHtml(value)+'"></span><button style="margin-left: 2px">Edit</button></td>');

    var self=this;
    newProperty.find('button').click(function(e)
    {
        e.preventDefault();
        RedNaoIconSelectorVar.Show( 'add',self.GetPropertyCurrentValue().ClassName,function(itemClass,orientation){
            self.PropertiesObject[self.PropertyName]={
                ClassName:itemClass,
                Orientation:orientation
            };
            self.RefreshElement();
            newProperty.find('span').attr('class',itemClass);
        });
    });

    return newProperty;
};


/************************************************************************************* Custom CSS Property ***************************************************************************************************/


function CustomCSSProperty(formelement,propertiesObject)
{
    ElementPropertiesBase.call(this,formelement,propertiesObject,"CustomCSS","Custom CSS",{ManipulatorType:'basic'});
}

CustomCSSProperty.prototype=Object.create(ElementPropertiesBase.prototype);

CustomCSSProperty.prototype.GenerateHtml=function()
{
    var tdStyle="";
    var input='<input style="width: 206px;" class="rednao-input-large" data-type="input" type="text" name="name" id="'+this.PropertyId+'" value="'+RedNaoEscapeHtml(this.GetPropertyCurrentValue())+'" placeholder="None"/><span style="margin-left: 2px;cursor:hand;cursor:pointer;" data-toggle="tooltip" data-placement="right" title="Add all the custom class styles separated by space, e.g. button blue" class="glyphicon glyphicon-question-sign"></span>';

    var newProperty=rnJQuery( '<td style="text-align: right;'+tdStyle+'"><label class="rednao-properties-control-label"> '+this.PropertyTitle+' </label></td>'+
    '<td  style="text-align: left">'+input+'</td>');
    newProperty.find('span').tooltip();
    var self=this;
    newProperty.keyup(function(){
        self.Manipulator.SetValue(self.PropertiesObject,self.PropertyName, (rnJQuery("#"+self.PropertyId).val()),self.AdditionalInformation);
        self.RefreshElement();

    });
    return newProperty;
};

