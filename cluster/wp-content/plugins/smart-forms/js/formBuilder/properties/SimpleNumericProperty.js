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
var SimpleNumericProperty = /** @class */ (function (_super) {
    __extends(SimpleNumericProperty, _super);
    function SimpleNumericProperty(formelement, propertiesObject, propertyName, propertyTitle, additionalInformation) {
        var _this = _super.call(this, formelement, propertiesObject, propertyName, propertyTitle, additionalInformation) || this;
        if (typeof additionalInformation.Placeholder == 'undefined')
            additionalInformation.Placeholder = 'Default';
        return _this;
    }
    SimpleNumericProperty.prototype.InternalGenerateHtml = function ($fieldContainer) {
        var _this = this;
        var $input = rnJQuery("<input  style=\"width: 206px;\" class=\"rednao-input-large form-control\" data-type=\"input\" type=\"number\" name=\"name\" id=\"" + this.PropertyId + "\" value=\"" + RedNaoEscapeHtml(this.GetPropertyCurrentValue()) + "\" placeholder=\"" + this.AdditionalInformation.Placeholder + "\"/>");
        $fieldContainer.append($input);
        $input.ForceNumericOnly();
        $input.on('input', function () {
            var value = parseFloat(rnJQuery("#" + _this.PropertyId).val());
            if (isNaN(value))
                value = '';
            else
                value = value.toString();
            _this.Manipulator.SetValue(_this.PropertiesObject, _this.PropertyName, value, _this.AdditionalInformation);
            _this.RefreshElement();
        });
    };
    return SimpleNumericProperty;
}(ElementPropertiesBase_1.ElementPropertiesBase));
exports.SimpleNumericProperty = SimpleNumericProperty;
//# sourceMappingURL=SimpleNumericProperty.js.map