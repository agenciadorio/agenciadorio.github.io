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
var CheckBoxProperty = /** @class */ (function (_super) {
    __extends(CheckBoxProperty, _super);
    function CheckBoxProperty() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    CheckBoxProperty.prototype.InternalGenerateHtml = function ($fieldContainer) {
        var _this = this;
        var $input = rnJQuery("<input type=\"checkbox\" class=\"input-inline field\" name=\"checked\" id=\"" + this.PropertyId + "\" " + (this.GetPropertyCurrentValue() == 'y' ? 'checked="checked"' : '') + " '/>");
        $fieldContainer.append($input);
        $input.change(function () {
            _this.Manipulator.SetValue(_this.PropertiesObject, _this.PropertyName, ($input.is(':checked') ? 'y' : 'n'), _this.AdditionalInformation);
            _this.RefreshElement();
        });
    };
    return CheckBoxProperty;
}(ElementPropertiesBase_1.ElementPropertiesBase));
exports.CheckBoxProperty = CheckBoxProperty;
//# sourceMappingURL=CheckBoxProperty.js.map