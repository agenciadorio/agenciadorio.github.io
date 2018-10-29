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
var ComboBoxProperty = /** @class */ (function (_super) {
    __extends(ComboBoxProperty, _super);
    function ComboBoxProperty() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    ComboBoxProperty.prototype.InternalGenerateHtml = function ($fieldContainer) {
        var _this = this;
        var value = this.GetPropertyCurrentValue().trim();
        var selectText = '<select class="form-control" id="' + this.PropertyId + '">';
        for (var i = 0; i < this.AdditionalInformation.Values.length; i++) {
            var selected = "";
            if (this.AdditionalInformation.Values[i].value == value)
                selected = 'selected="selected"';
            selectText += '<option value="' + RedNaoEscapeHtml(this.AdditionalInformation.Values[i].value) + '" ' + selected + '>' + RedNaoEscapeHtml(this.AdditionalInformation.Values[i].label) + '</option>';
        }
        selectText += '</select>';
        var $select = rnJQuery(selectText);
        $fieldContainer.append($select);
        $select.change(function () {
            _this.Manipulator.SetValue(_this.PropertiesObject, _this.PropertyName, $select.val(), _this.AdditionalInformation);
            _this.RefreshElement();
        });
    };
    ComboBoxProperty.prototype.AddOption = function (label, value) {
        if (this.AdditionalInformation.Values == null)
            this.AdditionalInformation.Values = [];
        this.AdditionalInformation.Values.push({ label: label, value: value });
        return this;
    };
    return ComboBoxProperty;
}(ElementPropertiesBase_1.ElementPropertiesBase));
exports.ComboBoxProperty = ComboBoxProperty;
//# sourceMappingURL=ComboBoxProperty.js.map