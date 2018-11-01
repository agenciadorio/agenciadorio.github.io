function SFConditionDesigner(formElements,Options)
{
    this.FormElements=formElements;
    this.Table=null;
    this.Options=Options;
    this.Conditions=[];



}


SFConditionDesigner.prototype.GetDesigner=function()
{
    if(this.Table==null)
    {
        this.Table = rnJQuery("<table>" +
        "<th>(</th>" +
        "<th>Field</th>" +
        "<th>Operation</th>" +
        "<th>Value</th>" +
        "<th>)</th>" +
        "<th>Join</th>" +
        "<th></th>" +
        "<th></th>" +
        "</table>");

        if (typeof this.Options.Conditions != 'undefined' && this.Options.Conditions.length > 0)
            this.FillDefaultValues();
        else
            this.CreateConditionalRow();
    }

    return this.Table;

};



SFConditionDesigner.prototype.FillDefaultValues=function()
{
    var conditions=this.Options.Conditions;
    for(var i=0;i<conditions.length;i++)
    {
        var row=this.CreateConditionalRow();
        var formElements=this.FormElements;
        for(var h=0;h<formElements.length;h++)
            if(formElements[h].Id==conditions[i].Field)
                row.find('.rnConditionField').val(h).change();

        row.find('.rnConditionOper').val(conditions[i].Op);
        row.find('.operType').val(conditions[i].OpType);
        if(typeof conditions[i].SerializationType!='undefined')
            row.find('.serializationType').val(conditions[i].SerializationType);
        if(conditions[i].OpType=="date")
            row.find('.rnConditionVal').datepicker('setDate',conditions[i].Value);
        if(conditions[i].OpType=="text")
            row.find('.rnConditionVal').val(conditions[i].Value);
        else
            row.find('.rnConditionVal').select2('val',conditions[i].Value);

        if(conditions[i].IsOpeningPar=='y')
            row.find('.leftPar').attr('checked','checked');

        if(conditions[i].IsClosingPar=='y')
            row.find('.rightPar').attr('checked','checked');

        row.find('.conditionJoin').val(conditions[i].Join);



    }
};

SFConditionDesigner.prototype.CreateConditionalRow=function()
{
    var condition={};
    this.Conditions.push(condition);
    var row=rnJQuery('<tr class="sfConditionRow">' +
    '   <td><input class="leftPar" type="checkbox" name="condition'+this.Table.find('tr').length+'"/></td>' +
    '   <td><select class="rnConditionField" style="width: 200px;">'+this.GetFieldItems()+'</select></td>' +
    '   <td><select class="rnConditionOper" style="width: 100px;"></select><input type="hidden" class="operType"/><input type="hidden" class="serializationType"/></td>' +
    '   <td class="tdValue"><input type="text" style="width: 200px;"/></td>' +
    '   <td><input  class="rightPar" type="checkbox" name="condition'+this.Table.find('tr').length+'"/></td>' +
    '   <td><select class="conditionJoin"><option></option><option value="and">And</option><option value="or">Or</option></select></td>' +
    '   <td><button class="conditionAdd" value="+">+</button></td>'+
    (this.Table.find('tr').length>1?'   <td><button class="conditionRemove" value="-">-</button></td>':'')+
    '</tr>');

    var self=this;
    row.find('.leftPar').change(function(){
        if(rnJQuery(this).is(':checked'))
            row.find('.rightPar').removeAttr('checked');
    });

    row.find('.rightPar').change(function(){
        if(rnJQuery(this).is(':checked'))
            row.find('.leftPar').removeAttr('checked');
    });
    row.find('.conditionAdd').click(function(e){
        e.preventDefault();
        if(row.find('.conditionJoin').val()=='')
            row.find('.conditionJoin').val('and');
        self.CreateConditionalRow()});
    row.find('.conditionRemove').click(function(e){e.preventDefault();row.remove();});
    row.find('.rnConditionField').change(function(){self.FieldSelected(row,rnJQuery(this).val(), condition)});
    this.Table.append(row);
    return row;
};

SFConditionDesigner.prototype.FieldSelected=function(row,selectedField,condition)
{
    row.find('.rnConditionOper').empty();
    if(selectedField==-1)
    {
        condition.Field="";
        return;
    }

    selectedField=this.FormElements[selectedField];
    condition.Field=selectedField.Id;
    var options="";
    if(selectedField.Options.ClassName=='rednaodatepicker')
    {
        row.find('.operType').val('date');
        row.find('.serializationType').val('date');
        options=
            "<option value='eq'>equal</option>" +
            "<option value='neq'>not equal</option>" +
            "<option value='gt'>Greater than</option>" +
            "<option value='get'>Greater or equal than</option>" +
            "<option value='lt'>Less than</option>" +
            "<option value='let'>Less or equal than</option>";
        var datePicker=rnJQuery('<input class="rnConditionVal" type="text" style="width: 200px;"/>');
        row.find('.tdValue').empty().append(datePicker);
        datePicker.datepicker({
            dateFormat:'yy-mm-dd',
            beforeShow: function() {
                rnJQuery('#ui-datepicker-div').wrap('<div class="smartFormsSlider"></div>');
            },
            onClose: function() {
                rnJQuery('#ui-datepicker-div').unwrap();
            }
        });
    }else
    if(typeof selectedField.Options.Options=='undefined')
    {
        row.find('.operType').val('text');
        if(selectedField.Options.ClassName=="rednaodatepicker")
            row.find('.serializationType').val('date');
        else
            row.find('.serializationType').val('text');
        options=
            "<option value='eq'>equal</option>" +
            "<option value='neq'>not equal</option>" +
            "<option value='contains'>contains</option>" +
            "<option value='ncontains'>not contains</option>"+
            "<option value='gt'>Greater than</option>" +
            "<option value='get'>Greater or equal than</option>" +
            "<option value='lt'>Less than</option>" +
            "<option value='let'>Less or equal than</option>";/*+
     "<option value='empty'>Is Empty</option>"+
     "<option value='nempty'>Is Not Empty</option>"*/

        row.find('.tdValue').empty().append('<input class="rnConditionVal" type="text" style="width: 200px;"/>');
    }else
    {
        row.find('.operType').val('list');
        row.find('.serializationType').val('list');
        if(selectedField.Options.ClassName=="rednaomultiplecheckboxes")
            row.find('.serializationType').val('list');
        options="<option value='contains'>contains</option>" +
        "<option value='ncontains'>not contains</option>"/*+
         "<option value='empty'>Is Empty</option>"+
         "<option value='nempty'>Is Not Empty</option>"*/;

        var fieldAvailableOptions="";
        for(var i=0;i<selectedField.Options.Options.length;i++)
        {
            fieldAvailableOptions+="<option value='"+RedNaoEscapeHtml(selectedField.Options.Options[i].label)+"'>"+RedNaoEscapeHtml(selectedField.Options.Options[i].label)+"</option>";
        }

        var select=rnJQuery('<select class="rnConditionVal" multiple="multiple" style="width: 200px;">'+fieldAvailableOptions+'</select>');
        row.find('.tdValue').empty().append(select);
        select.select2();
    }


    row.find('.rnConditionOper').append(options);



};


SFConditionDesigner.prototype.GetFieldItems=function()
{
    var formElements=this.FormElements;
    var options="<option value='-1'></option>";
    for(var i=0;i<formElements.length;i++)
        if(formElements[i].StoresInformation())
            options+="<option value='"+ i.toString()+"'>"+formElements[i].GetFriendlyName()+"</option>";

    return options;
};



SFConditionDesigner.prototype.CompileCondition=function(conditions)
{
    conditions=this.GetRowsData();
    var conditionTxt="";
    for(var i=0;i<conditions.length;i++){
        var formElement=null;
        for(var h=0;h<this.FormElements.length;h++)
        {
            if(this.FormElements[h].Id==conditions[i].Field)
                formElement=this.FormElements[h];
        }
        if(formElement==null)
            continue;

        if(conditions[i].IsOpeningPar=='y')
            conditionTxt+='(';

        if(conditions[i].OpType=='list')
        {

            conditionTxt+=(conditions[i].Op=="contains"?"":"!")+"RedNaoListContainsValue("+JSON.stringify(conditions[i].Value)+",formData."+formElement.Id+") ";

        }else{
            var amount=parseFloat(conditions[i].Value);
            if(isNaN(amount))
                amount=0;
            if(conditions[i].OpType=='date')
            {
                amount=rnJQuery.datepicker.parseDate('yy-mm-dd',conditions[i].Value).getTime();
                switch (conditions[i].Op)
                {
                    case 'eq':
                        conditionTxt += formElement.GetNumericalValuePath() + "==" + amount.toString()+ " && "+ formElement.GetNumericalValuePath()+"!=0";
                        break;
                    case 'neq':
                        conditionTxt += formElement.GetNumericalValuePath() + "!=" + amount.toString();
                        break;
                    case 'gt':
                        conditionTxt += formElement.GetNumericalValuePath() + ">" + amount.toString() + " && "+ formElement.GetNumericalValuePath()+"!=0";
                        break;
                    case 'get':
                        conditionTxt += formElement.GetNumericalValuePath() + ">=" + amount.toString() + " && "+ formElement.GetNumericalValuePath()+"!=0";
                        break;
                    case 'lt':
                        conditionTxt += formElement.GetNumericalValuePath() + "<" + amount.toString() + " && "+ formElement.GetNumericalValuePath()+"!=0";
                        break;
                    case 'let':
                        conditionTxt += formElement.GetNumericalValuePath() + "<=" + amount.toString() + " && "+ formElement.GetNumericalValuePath()+"!=0";
                        break;
                }
            }else
            {
                switch (conditions[i].Op)
                {
                    case 'eq':
                        conditionTxt += formElement.GetLabelPath() + ".toLowerCase()=='" + conditions[i].Value.toLowerCase() + "' ";
                        break;
                    case 'neq':
                        conditionTxt += formElement.GetLabelPath() + ".toLowerCase()!='" + conditions[i].Value.toLowerCase() + "' ";
                        break;
                    case 'contains':
                        conditionTxt += formElement.GetLabelPath() + ".toLowerCase().indexOf('" + conditions[i].Value.toLowerCase() + "')>-1 ";
                        break;
                    case 'ncontains':
                        conditionTxt += formElement.GetLabelPath() + ".toLowerCase().indexOf('" + conditions[i].Value.toLowerCase() + "')==-1 ";
                        break;
                    case 'gt':
                        conditionTxt += formElement.GetNumericalValuePath() + ">" + amount.toString() + " ";
                        break;
                    case 'get':
                        conditionTxt += formElement.GetNumericalValuePath() + ">=" + amount.toString() + " ";
                        break;
                    case 'lt':
                        conditionTxt += formElement.GetNumericalValuePath() + "<" + amount.toString() + " ";
                        break;
                    case 'let':
                        conditionTxt += formElement.GetNumericalValuePath() + "<=" + amount.toString() + " ";
                        break;
                }
            }
        }

        if(conditions[i].IsClosingPar=='y')
            conditionTxt+=") ";

        if(conditions.length-1>i)
            conditionTxt+=' '+(conditions[i].Join=='and'?'&&':'||')+' ';
    }

    // alert(conditionTxt);
    return conditionTxt;
};


SFConditionDesigner.prototype.IsValid=function()
{
    var data=this.GetRowsData();

    var openPar=0;
    var closePar=0;

    for(var i=0;i<data.length;i++)
    {
        if(data[i].Field.trim()==""||data[i].Op.trim()==""||data[i].OpType.trim()==""||(data[i].OpType=="text"&&data[i].Value.trim()=="")||(data[i].OpType=="list"&&data[i].Value.length<=0)||
            (i<(data[i].length-1)&&data[i].Join.trim()==""))
        {
            alert('Please fill all fields');
            return false;
        }

        if(data[i].IsOpeningPar=='y')
            openPar++;

        if(data[i].IsClosingPar=='y')
        {
            if(closePar>=openPar)
            {
                alert('You are closing one parenthesis when there is no open parenthesis');
                return false;
            }
            closePar++;
        }
    }

    if(openPar!=closePar)
    {
        alert('The open parenthesis count doesn\'t match the close parenthesis count');
        return false;
    }

    return true;


};

SFConditionDesigner.prototype.GetRowsData=function()
{
    var rows=this.Table.find('.sfConditionRow');

    var data=[];
    for(var i=0;i<rows.length;i++)
    {
        var row=rnJQuery(rows[i]);
        data.push(
            {
                Field:(row.find('.rnConditionField').val()>=0? this.FormElements[row.find('.rnConditionField').val()].Id:""),
                Op:row.find('.rnConditionOper').val(),
                OpType:row.find('.operType').val(),
                SerializationType:row.find('.serializationType').val(),
                Value:(row.find('.operType').val()=='list'?row.find('.rnConditionVal').select2('val'):row.find('.rnConditionVal').val()),
                IsOpeningPar:(row.find('.leftPar').is(':checked')?'y':'n'),
                IsClosingPar:(row.find('.rightPar').is(':checked')?'y':'n'),
                Join:row.find('.conditionJoin').val(),
                SerializationType:row.find('.serializationType').val()
            }
        );
    }
    return data;
};

SFConditionDesigner.prototype.GetData=function()
{
    var rowData=this.GetRowsData();
    return {
          Conditions:rowData,
          CompiledCondition:this.CompileCondition(rowData)
        }
};