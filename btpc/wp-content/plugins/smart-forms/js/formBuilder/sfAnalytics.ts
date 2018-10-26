/// <reference path="../typings/sfGlobalTypings.d.ts" />
declare var RedNaoPathExists: any;
declare var RedNaoEscapeHtml: any;
declare var exports:any;

function CreateColumn(options: any) {
    var elementName = options.ClassName;

    if (elementName == 'rednaocurrency')
        return RedNaoTextInputColumn(options);
    if (elementName == 'rednaotextinput')
        return RedNaoTextInputColumn(options);
    if (elementName == 'rednaorating')
        return RedNaoTextInputColumn(options);
    if (elementName == 'rednaoprependedtext')
        return RedNaoTextInputColumn(options);
    if (elementName == 'rednaoappendedtext')
        return RedNaoTextInputColumn(options);
    if (elementName == 'rednaoemail')
        return RedNaoTextInputColumn(options);
    if (elementName == 'rednaodonationrecurrence')
        return RedNaoRecurrenceColumn(options);


    if (elementName == 'rednaoprependedcheckbox')
        return RedNaoCheckboxInputColumn(options);
    if (elementName == 'rednaoappendedcheckbox')
        return RedNaoCheckboxInputColumn(options);


    if (elementName == 'rednaomultiplecheckboxes' || elementName == 'rednaosearchablelist')
        return RedNaoMultipleCheckBoxesColumn(options);
    if (elementName == 'rednaoselectbasic')
        return RedNaoTextOrAmountColumn(options);

    if (elementName == 'rednaotextarea')
        return RedNaoTextInputColumn(options);
    if (elementName == 'rednaomultipleradios')
        return RedNaoTextOrAmountColumn(options);
    if (elementName == 'rednaodatepicker')
        return RedNaoDatePicker(options);
    if (elementName == 'rednaoname')
        return RedNaoName(options);

    if (elementName == 'rednaoaddress')
        return RedNaoAddress(options);
    if (elementName == 'rednaophone')
        return RedNaoPhone(options);
    if (elementName == "rednaonumber")
        return RedNaoTextInputColumn(options);
    if (elementName == "sfFileUpload")
        return RedNaoFileUploadColumn(options);
    if(elementName=='rednaosurveytable')
        return RedNaoSurveyTableColumn(options);
    if (elementName == 'rednaoimagepicker')
        return RedNaoMultipleCheckBoxesColumn(options);
    if(elementName=='rednaosignature')
        return RedNaoSignatureColumn(options);
    if(elementName=='rednaorepeater')
        return RedNaoRepeaterColumn(options);

}
exports.CreateColumn=CreateColumn;


function GetObjectOrNull(rowObject, options) {
    if (!RedNaoPathExists(rowObject, 'data.' + options.colModel.index))
        return null;

    return rowObject.data[options.colModel.index];

}

function RedNaoRepeaterColumn(options) {



    return [{
        "name": options.Id,
        label: options.Label,
        "index": options.Id,
        "editable":true,
        formatter: function (cellvalue, cellOptions, rowObject) {
            try {
                var data = GetObjectOrNull(rowObject, cellOptions);
                if (data == null)
                    return '';
                let table='<table>';
                let index=0;
                for(let row of data.value)
                {
                    index++;
                    for(let propertyName in row)
                    {
                        table+='<tr>';
                        let fieldName=propertyName.replace(/_row_[0-9]*/,'');
                        let fieldOptions=options.FieldOptions.find(x=>x.Id==fieldName);
                        if(fieldOptions==null)
                            return;
                        table+='<th style="text-align: left">'+RedNaoEscapeHtml(fieldOptions.Label)+' #'+index+'</th>';
                        let columnList=CreateColumn(fieldOptions);
                        let value='';
                        for(let column of columnList)
                        {
                            if(value!='')
                                value+=',';
                            let data={data:{}};
                            data.data[propertyName]=row[propertyName];
                            value+=column.formatter(null,{colModel:{index:propertyName}},data)
                        }
                        table+='<th style="font-weight: normal;text-align: right">'+value+'</th>';
                        table+='</tr>';



                    }
                }
                table+='</table>';
                return table;
            } catch (exception) {
                return '';
            }
        }
    }];
}


function RedNaoTextOrAmountColumn(options) {
    return [{
        "name": options.Id,
        label: options.Label,
        "index": options.Id,
        "editable":true,
        'sortable':true,
        formatter: function (cellvalue, cellOptions, rowObject) {
            try {
                var data = GetObjectOrNull(rowObject, cellOptions);
                if (data == null)
                    return '';
                return RedNaoEscapeHtml(data.value);
            } catch (exception) {
                return '';
            }
        },
        sorttype: function (cell,rowObject) {
            var data=rowObject.data[options.Id];
            if(data==null)
                return '';
            return RedNaoEscapeHtml(data.value);
        }
    }];
}

function RedNaoTextInputColumn(options) {
    return [{
        "name": options.Id,
        label: options.Label,
        "index": options.Id,
        "editable":true,
        'sortable':true,
        formatter: function (cellvalue, cellOptions, rowObject) {
            try {
                var data = GetObjectOrNull(rowObject, cellOptions);
                if (data == null)
                    return '';
                return RedNaoEscapeHtml(data.value);
            } catch (exception) {
                return '';
            }
        },
        sorttype: function (cell,rowObject) {
            var data=rowObject.data[options.Id];
            if(data==null)
                return '';
            return RedNaoEscapeHtml(data.value);
        }
    }];
}


function RedNaoSignatureColumn(options) {
    return [{
        "name": options.Id,
        label: options.Label,
        "index": options.Id,
        "editable":true,
        formatter: function (cellvalue, cellOptions, rowObject) {
            try {
                var data = GetObjectOrNull(rowObject, cellOptions);
                if (data == null)
                    return '';
                if(typeof data.native!='undefined'&&data.native!='')
                    return '<img style="width:100%" src="data:image/svg+xml;base64,'+RedNaoEscapeHtml(data.value)+'"></img>';
                return '';
            } catch (exception) {
                return '';
            }
        }
    }];
}


function RedNaoSurveyTableColumn(options) {
    var columns=[];
    for(var i=0;i<options.Rows.length;i++){
        columns.push({
            "name": options.Id+'_'+i,
            label: options.Rows[i].label,
            "index": options.Id+'_'+i,
            "surveyRowId":i,
            "editable":true,
            "fieldId":options.Id,
            formatter: function (cellvalue, cellOptions, rowObject) {
                try {
                    var rowIndex=cellOptions.colModel.surveyRowId;
                    var data=rowObject.data[cellOptions.colModel.fieldId];
                    if(data.values.length>rowIndex)
                    {
                        return RedNaoEscapeHtml(data.values[rowIndex].ValueLabel);
                    }
                } catch (exception) {
                    return '';
                }
            }
        },);
    }
    return columns;
}

function RedNaoRecurrenceColumn(options) {
    return [{
        "name": options.Id,
        label: options.Label,
        "index": options.Id,
        "editable":true,
        formatter: function (cellvalue, cellOptions, rowObject) {
            try {
                var data = GetObjectOrNull(rowObject, cellOptions);
                if (data == null)
                    return '';

                switch (data.value) {
                    case 'OT':
                        return 'One time';
                    case 'D':
                        return 'Daily';
                    case 'W':
                        return 'Weekly';
                    case 'M':
                        return 'Monthly';
                    case 'Y':
                        return 'Yearly';


                }
                return data.value;
            } catch (exception) {
                return '';
            }
        }
    }];
}


function RedNaoCheckboxInputColumn(options) {
    return [{
        "name": options.Id,
        label: options.Label,
        "index": options.Id,
        "editable":true,
        formatter: function (cellvalue, cellOptions, rowObject) {
            try {
                var data = GetObjectOrNull(rowObject, cellOptions);
                if (data == null)
                    return '';
                return RedNaoEscapeHtml(data.checked) + ". " + RedNaoEscapeHtml(data.value);
            } catch (exception) {
                return '';
            }
        }
    }];
}

function RedNaoMultipleCheckBoxesColumn(options) {
    return [{
        "name": options.Id,
        label: options.Label,
        "index": options.Id,
        "editable":true,
        formatter: function (cellvalue, cellOptions, rowObject) {
            try {
                var data = GetObjectOrNull(rowObject, cellOptions);
                if (data == null)
                    return '';
                var values = "";

                for (var i = 0; i < data.selectedValues.length; i++)
                    values += data.selectedValues[i].value.trim() + ";";
                return RedNaoEscapeHtml(values);
            } catch (exception) {
                return '';
            }
        }
    }];
}

function RedNaoDatePicker(options) {
    return [{
        "name": options.Id,
        label: options.Label,
        "index": options.Id,
        "editable":true,
        'sortable':true,
        formatter: function (cellvalue, cellOptions, rowObject) {
            try {
                var data = GetObjectOrNull(rowObject, cellOptions);
                if (data == null || data.value == "")
                    return '';
                var dateParts = data.value.split("-");
                if (dateParts.length != 3)
                    return '';

                var date = new Date(dateParts[0], parseInt(dateParts[1]) - 1, dateParts[2]);

                return RedNaoEscapeHtml(rnJQuery.datepicker.formatDate(options.DateFormat, date));
            } catch (exception) {
                return '';
            }
        },
        sorttype: function (cell,rowObject) {
            var data=rowObject.data[options.Id];
            if (data == null || data.value == "")
                return '';
            var dateParts = data.value.split("-");
            if (dateParts.length != 3)
                return '';

            var date = new Date(dateParts[0], parseInt(dateParts[1]) - 1, dateParts[2]);

            return RedNaoEscapeHtml(rnJQuery.datepicker.formatDate(options.DateFormat, date));
        }
    }];
}

function RedNaoName(options) {
    return [{
        "name": options.Id,
        label: options.Label,
        "index": options.Id,
        "editable":true,
        'sortable':true,
        formatter: function (cellvalue, cellOptions, rowObject) {
            try {
                var data = GetObjectOrNull(rowObject, cellOptions);
                if (data == null)
                    return '';
                return RedNaoEscapeHtml(data.firstName + ' ' + data.lastName);
            } catch (exception) {
                return '';
            }
        }, sorttype: function (cell,rowObject) {
            var data = rowObject.data[options.Id];
            if(data==null)
                return '';
            if (data == null)
                return '';
            return RedNaoEscapeHtml(data.firstName + ' ' + data.lastName);
        }
    }];
}


function RedNaoPhone(options) {
    return [{
        "name": options.Id,
        label: options.Label,
        "index": options.Id,
        "editable":true,
        'sortable':true,
        formatter: function (cellvalue, cellOptions, rowObject) {
            try {
                var data = GetObjectOrNull(rowObject, cellOptions);
                if (data == null)
                    return '';
                return RedNaoEscapeHtml(data.area + '-' + data.phone);
            } catch (exception) {
                return '';
            }
        }, sorttype: function (cell,rowObject) {
            var data = rowObject.data[options.Id];
            if(data==null)
                return '';
            if (data == null)
                return '';
            return RedNaoEscapeHtml(data.area + '-' + data.phone);
        }
    }];
}


function RedNaoAddress(options) {
    return [{
        "name": options.Id,
        label: options.Label,
        "index": options.Id,
        "editable":true,
        'sortable':true,
        formatter: function (cellvalue, cellOptions, rowObject) {
            try {
                var data = GetObjectOrNull(rowObject, cellOptions);
                if (data == null)
                    return '';

                var appendAddressElement = function (address, element) {
                    if (element == "" || typeof element == 'undefined')
                        return address;

                    if (address == "")
                        address = element;
                    else
                        address += ", " + element;

                    return address;

                }

                var address = "";
                address = appendAddressElement(address, data.streetAddress1);
                address = appendAddressElement(address, data.streetAddress2);
                address = appendAddressElement(address, data.city);
                address = appendAddressElement(address, data.state);
                address = appendAddressElement(address, data.zip);
                address = appendAddressElement(address, data.country);

                return RedNaoEscapeHtml(address);
            } catch (exception) {
                return '';
            }
        }, sorttype: function (cell,rowObject) {
            var data = rowObject.data[options.Id];
            if(data==null)
                return '';
            if (data == null)
                return '';

            var appendAddressElement = function (address, element) {
                if (element == "" || typeof element == 'undefined')
                    return address;

                if (address == "")
                    address = element;
                else
                    address += ", " + element;

                return address;

            };

            var address = "";
            address = appendAddressElement(address, data.streetAddress1);
            address = appendAddressElement(address, data.streetAddress2);
            address = appendAddressElement(address, data.city);
            address = appendAddressElement(address, data.state);
            address = appendAddressElement(address, data.zip);
            address = appendAddressElement(address, data.country);

            return RedNaoEscapeHtml(address);
        }
    }];

}

function RedNaoFileUploadColumn(options) {
    return [{
        "name": options.Id,
        label: options.Label,
        "index": options.Id,
        "editable":true,
        formatter: function (cellvalue, cellOptions, rowObject) {
            try {
                var data = GetObjectOrNull(rowObject, cellOptions);
                if (data == null)
                    return '';

                var firstRow = true;
                var html = "";
                for (var i = 0; i < data.length; i++) {
                    if (firstRow)
                        firstRow = false
                    else
                        html += "<br/>";
                    html += '<a target="_blank" href="' + data[i].path + '">' + data[i].path + '</a>';
                }
                return html;
            } catch (exception) {
                return '';
            }
        }
    }];
}