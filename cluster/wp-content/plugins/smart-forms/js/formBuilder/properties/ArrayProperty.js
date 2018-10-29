"use strict";
var __extends = (this && this.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p]; };
        return extendStatics(d, b);
    }
    return function (d, b) {
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
Object.defineProperty(exports, "__esModule", { value: true });
var ElementPropertiesBase_1 = require("./ElementPropertiesBase");
var ArrayProperty = /** @class */ (function (_super) {
    __extends(ArrayProperty, _super);
    function ArrayProperty() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    ArrayProperty.prototype.csvToArray = function (text) {
        var p = '', row = [''], ret = [row], i = 0, r = 0, s = !0, l;
        for (l in text) {
            l = text[l];
            if ('"' === l) {
                if (s && l === p)
                    row[i] += l;
                s = !s;
            }
            else if (',' === l && s)
                l = row[++i] = '';
            else if ('\n' === l && s) {
                if ('\r' === p)
                    row[i] = row[i].slice(0, -1);
                row = ret[++r] = [l = ''];
                i = 0;
            }
            else
                row[i] += l;
            p = l;
        }
        return ret;
    };
    ;
    ArrayProperty.prototype.GetFieldTemplate = function () {
        var _this = this;
        var $field = rnJQuery("<div class=\"col-sm-12\" style=\"vertical-align: top;text-align: left;background-color: #fafafa;border: 1px solid #f0f0f0;\">\n                            <table style=\"width: 98%;position:relative;\">\n                                <tr>\n                                    <td style=\"border-style: none;\">\n                                        <label class=\"checkbox control-group rednao-properties-control-label\" style=\"display: block;vertical-align: top; text-align: left;margin:0;\">\n                                            " + this.PropertyTitle + "\n                                        </label>\n                                        <input type=\"file\" class=\"fileUpload\" accept=\".csv, .txt\" style=\"display: none;\"/>\n                                        <a class=\"import\" href=\"http://www.google.com\" style=\"position: absolute;top:1px;right:5px;\">Import</a>\n                                    </td>\n                                 </tr>\n                                 <tr>\n                                    <td class=\"fieldContainer\" style=\"text-align: left;border-style: none;\">\n                                   \n                                    </td>\n                                 </tr>\n                            </table>\n                          </div>");
        $field.find('.fileUpload').change(function () {
            var file = $field.find('.fileUpload')[0].files[0];
            var fr = new FileReader();
            fr.onload = function (e) {
                var rows = _this.csvToArray(fr.result);
                if (rows.length > 0)
                    rows.shift();
                var firstElement = true;
                for (var _i = 0, rows_1 = rows; _i < rows_1.length; _i++) {
                    var columns = rows_1[_i];
                    var label = '';
                    var amount = '0';
                    if (columns.length == 0)
                        continue;
                    label = columns[0];
                    if (columns.length > 1)
                        amount = columns[1];
                    if (label.trim() == '')
                        continue;
                    if (firstElement)
                        _this.ItemsList.find('tbody').empty();
                    _this.AddItem({ url: '', label: label, value: amount }, firstElement);
                    firstElement = false;
                }
                $field.find('.fileUpload')[0].value = '';
            };
            //fr.readAsText(file);
            fr.readAsText(file);
        });
        $field.find('.import').click(function (e) {
            $field.find('.fileUpload').click();
            e.preventDefault();
        });
        return $field;
    };
    ArrayProperty.prototype.InternalGenerateHtml = function ($fieldContainer) {
        var _this = this;
        var currentValues = this.GetPropertyCurrentValue();
        $fieldContainer.append(this.GetItemList(currentValues));
        $fieldContainer.find('table.listOfItems').append("<tfoot><tr><td style='border-bottom-style: none;'><button class='redNaoPropertyClearButton' value='None'>Clear</button></td></tr></tfoot>");
        $fieldContainer.find('.redNaoPropertyClearButton').click(function (event) {
            event.preventDefault();
            $fieldContainer.find('.itemSel').removeAttr('checked');
            _this.UpdateProperty();
        });
        $fieldContainer.find('.cloneArrayItem').click(function (e) {
            _this.CloneItem(rnJQuery(e.currentTarget));
        });
        $fieldContainer.find('.deleteArrayItem').click(function (e) {
            _this.DeleteItem(rnJQuery(e.currentTarget));
        });
        $fieldContainer.find('input[type=text],input[type=radio],input[type=checkbox]').change(function () {
            _this.UpdateProperty();
        });
        $fieldContainer.find('input[type=text]').keyup(function () {
            _this.UpdateProperty();
        });
        this.ItemsList = $fieldContainer.find('.listOfItems');
    };
    ;
    ArrayProperty.prototype.GetItemList = function (items) {
        var allowImages = typeof this.AdditionalInformation.AllowImages != 'undefined' && this.AdditionalInformation.AllowImages == true;
        var list = '<table style="width: 100%;margin-left: 10px;" class="listOfItems"><tr><th style="text-align: center">Sel</th><th>Label</th>' + (allowImages ? '<th>Image Url</th>' : '') + '<th>Amount</th></tr>';
        var isFirst = true;
        for (var i = 0; i < items.length; i++) {
            list += this.CreateListRow(isFirst, items[i]);
            isFirst = false;
        }
        return list;
    };
    ;
    ArrayProperty.prototype.DeleteItem = function (jQueryElement) {
        var array = this.GetPropertyCurrentValue();
        var index = jQueryElement.parent().parent().index();
        array.splice(index, 1);
        jQueryElement.parent().parent().remove();
        this.UpdateProperty();
    };
    ;
    ArrayProperty.prototype.CloneItem = function (jQueryElement) {
        var jQueryToClone = jQueryElement.parent().parent();
        var data = this.GetRowData(jQueryToClone);
        if (this.AdditionalInformation.SelectorType == 'radio')
            data.sel = 'n';
        var jQueryNewRow = rnJQuery(this.CreateListRow(false, data));
        jQueryToClone.after(jQueryNewRow);
        var self = this;
        jQueryNewRow.find('.cloneArrayItem').click(function () {
            self.CloneItem(rnJQuery(this));
        });
        jQueryNewRow.find('.deleteArrayItem').click(function () {
            self.DeleteItem(rnJQuery(this));
        });
        jQueryNewRow.find('input[type=text],input[type=radio],input[type=checkbox]').change(function () {
            self.UpdateProperty();
        });
        this.UpdateProperty();
    };
    ;
    ArrayProperty.prototype.AddItem = function (item, firstElement) {
        var jQueryNewRow = rnJQuery(this.CreateListRow(firstElement, item));
        this.ItemsList.find('tbody').append(jQueryNewRow);
        var self = this;
        jQueryNewRow.find('.cloneArrayItem').click(function () {
            self.CloneItem(rnJQuery(this));
        });
        jQueryNewRow.find('.deleteArrayItem').click(function () {
            self.DeleteItem(rnJQuery(this));
        });
        jQueryNewRow.find('input[type=text],input[type=radio],input[type=checkbox]').change(function () {
            self.UpdateProperty();
        });
        this.UpdateProperty();
    };
    ;
    ArrayProperty.prototype.CreateListRow = function (isFirst, item) {
        var allowImages = typeof this.AdditionalInformation.AllowImages != 'undefined' && this.AdditionalInformation.AllowImages == true;
        if (allowImages && typeof item.url == 'undefined')
            item.url = '';
        var row = '<tr class="redNaoRowOption">' +
            '       <td style="border-style: none; text-align: center;">' + this.GetSelector(item) + '</td>' +
            '       <td style="border-style: none;width: ' + (allowImages ? "50%" : "100%") + ';"><input style="width: 100%" type="text" class="itemText" value="' + RedNaoEscapeHtml(item.label) + '"/></td>' +
            (allowImages ? '<td style="border-style: none;width:50%;"><input type="text" class="itemUrl" style="text-align: left; width: 100%;" value="' + RedNaoEscapeHtml(item.url) + '"/></td>' : '') +
            '       <td style="border-style: none;"><input type="text" class="itemValue" style="text-align: left; width: 50px;" value="' + RedNaoEscapeHtml(item.value) + '"/></td>' +
            '       <td style="border-style: none; text-align: center;vertical-align: middle;"><img style="cursor: pointer; width:15px;height:15px;" class="cloneArrayItem" src="' + smartFormsRootPath + 'images/clone.png" title="Clone"></td>';
        if (!isFirst)
            row += ' <td style="border-style: none !important;text-align: center;vertical-align: middle;"><img style="cursor: pointer;width:15px;height:15px;" class="deleteArrayItem" src="' + smartFormsRootPath + 'images/delete.png" title="Delete"></td>';
        row += '</tr>';
        return row;
    };
    ;
    ArrayProperty.prototype.GetSelector = function (item) {
        var selected = '';
        if (RedNaoGetValueOrEmpty(item.sel) == 'y')
            selected = 'checked="checked"';
        if (this.AdditionalInformation.SelectorType == 'radio')
            return '<input class="itemSel" type="radio" ' + selected + ' name="propertySelector"/>';
        else
            return '<input class="itemSel" type="checkbox" ' + selected + '/>';
    };
    ;
    ArrayProperty.prototype.UpdateProperty = function () {
        var processedValueArray = [];
        var self = this;
        var rows = this.ItemsList.find('tr.redNaoRowOption').each(function () {
            var jQueryRow = rnJQuery(this);
            var row = self.GetRowData(jQueryRow);
            processedValueArray.push(row);
        });
        this.Manipulator.SetValue(this.PropertiesObject, this.PropertyName, processedValueArray, this.AdditionalInformation);
        this.RefreshElement();
    };
    ;
    ArrayProperty.prototype.GetRowData = function (jQueryRow) {
        var objectToReturn = {
            label: jQueryRow.find('.itemText').val(),
            value: jQueryRow.find('.itemValue').val(),
            sel: (jQueryRow.find('.itemSel').is(':checked') ? 'y' : 'n'),
            url: ''
        };
        if (typeof this.AdditionalInformation.AllowImages != 'undefined' && this.AdditionalInformation.AllowImages == true)
            objectToReturn.url = jQueryRow.find('.itemUrl').val();
        return objectToReturn;
    };
    return ArrayProperty;
}(ElementPropertiesBase_1.ElementPropertiesBase));
exports.ArrayProperty = ArrayProperty;
//# sourceMappingURL=ArrayProperty.js.map