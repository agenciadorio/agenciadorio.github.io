/// <reference path="../typings/sfGlobalTypings.d.ts" />
function CreateColumn(options) {
    var elementName = options.ClassName;
    if (elementName == 'rednaotextinput')
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
}
exports.CreateColumn = CreateColumn;
function GetObjectOrNull(rowObject, options) {
    if (!RedNaoPathExists(rowObject, 'data.' + options.colModel.index))
        return null;
    return rowObject.data[options.colModel.index];
}
function RedNaoTextOrAmountColumn(options) {
    return [{
            "name": options.Id,
            label: options.Label,
            "index": options.Id,
            "editable": true,
            formatter: function (cellvalue, cellOptions, rowObject) {
                try {
                    var data = GetObjectOrNull(rowObject, cellOptions);
                    if (data == null)
                        return '';
                    return RedNaoEscapeHtml(data.value);
                }
                catch (exception) {
                    return '';
                }
            }
        }];
}
function RedNaoTextInputColumn(options) {
    return [{
            "name": options.Id,
            label: options.Label,
            "index": options.Id,
            "editable": true,
            formatter: function (cellvalue, cellOptions, rowObject) {
                try {
                    var data = GetObjectOrNull(rowObject, cellOptions);
                    if (data == null)
                        return '';
                    return RedNaoEscapeHtml(data.value);
                }
                catch (exception) {
                    return '';
                }
            }
        }];
}
function RedNaoRecurrenceColumn(options) {
    return [{
            "name": options.Id,
            label: options.Label,
            "index": options.Id,
            "editable": true,
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
                }
                catch (exception) {
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
            "editable": true,
            formatter: function (cellvalue, cellOptions, rowObject) {
                try {
                    var data = GetObjectOrNull(rowObject, cellOptions);
                    if (data == null)
                        return '';
                    return RedNaoEscapeHtml(data.checked) + ". " + RedNaoEscapeHtml(data.value);
                }
                catch (exception) {
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
            "editable": true,
            formatter: function (cellvalue, cellOptions, rowObject) {
                try {
                    var data = GetObjectOrNull(rowObject, cellOptions);
                    if (data == null)
                        return '';
                    var values = "";
                    for (var i = 0; i < data.selectedValues.length; i++)
                        values += data.selectedValues[i].value.trim() + ";";
                    return RedNaoEscapeHtml(values);
                }
                catch (exception) {
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
            "editable": true,
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
                }
                catch (exception) {
                    return '';
                }
            }
        }];
}
function RedNaoName(options) {
    return [{
            "name": options.Id,
            label: options.Label,
            "index": options.Id,
            "editable": true,
            formatter: function (cellvalue, cellOptions, rowObject) {
                try {
                    var data = GetObjectOrNull(rowObject, cellOptions);
                    if (data == null)
                        return '';
                    return RedNaoEscapeHtml(data.firstName + ' ' + data.lastName);
                }
                catch (exception) {
                    return '';
                }
            }
        }];
}
function RedNaoPhone(options) {
    return [{
            "name": options.Id,
            label: options.Label,
            "index": options.Id,
            "editable": true,
            formatter: function (cellvalue, cellOptions, rowObject) {
                try {
                    var data = GetObjectOrNull(rowObject, cellOptions);
                    if (data == null)
                        return '';
                    return RedNaoEscapeHtml(data.area + '-' + data.phone);
                }
                catch (exception) {
                    return '';
                }
            }
        }];
}
function RedNaoAddress(options) {
    return [{
            "name": options.Id,
            label: options.Label,
            "index": options.Id,
            "editable": true,
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
                catch (exception) {
                    return '';
                }
            }
        }];
}
function RedNaoFileUploadColumn(options) {
    return [{
            "name": options.Id,
            label: options.Label,
            "index": options.Id,
            "editable": true,
            formatter: function (cellvalue, cellOptions, rowObject) {
                try {
                    var data = GetObjectOrNull(rowObject, cellOptions);
                    if (data == null)
                        return '';
                    var firstRow = true;
                    var html = "";
                    for (var i = 0; i < data.length; i++) {
                        if (firstRow)
                            firstRow = false;
                        else
                            html += "<br/>";
                        html += '<a target="_blank" href="' + data[i].path + '">' + data[i].path + '</a>';
                    }
                    return html;
                }
                catch (exception) {
                    return '';
                }
            }
        }];
}
//# sourceMappingURL=sfAnalytics.js.map