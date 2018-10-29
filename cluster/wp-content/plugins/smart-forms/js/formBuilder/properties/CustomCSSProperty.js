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
var CustomCSSProperty = /** @class */ (function (_super) {
    __extends(CustomCSSProperty, _super);
    function CustomCSSProperty(formelement, propertiesObject) {
        var _this = _super.call(this, formelement, propertiesObject, "CustomCSS", "Custom CSS", { ManipulatorType: 'basic' }) || this;
        _this.tooltip = { Text: 'Add all the custom class styles separated by space, e.g. button blue' };
        return _this;
    }
    CustomCSSProperty.prototype.InternalGenerateHtml = function ($fieldContainer) {
        var _this = this;
        var $input = rnJQuery('<input style="width: 206px;" class="rednao-input-large form-control" data-type="input" type="text" name="name" id="' + this.PropertyId + '" value="' + RedNaoEscapeHtml(this.GetPropertyCurrentValue()) + '" placeholder="None"/>');
        $fieldContainer.append($input);
        $input.keyup(function () {
            _this.Manipulator.SetValue(_this.PropertiesObject, _this.PropertyName, $input.val(), _this.AdditionalInformation);
            _this.RefreshElement();
        });
    };
    return CustomCSSProperty;
}(ElementPropertiesBase_1.ElementPropertiesBase));
exports.CustomCSSProperty = CustomCSSProperty;
//# sourceMappingURL=CustomCSSProperty.js.map