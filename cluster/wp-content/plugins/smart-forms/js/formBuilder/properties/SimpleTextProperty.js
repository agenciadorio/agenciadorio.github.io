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
var SimpleTextProperty = /** @class */ (function (_super) {
    __extends(SimpleTextProperty, _super);
    function SimpleTextProperty(formelement, propertiesObject, propertyName, propertyTitle, additionalInformation) {
        var _this = _super.call(this, formelement, propertiesObject, propertyName, propertyTitle, additionalInformation) || this;
        if (typeof additionalInformation.Placeholder == 'undefined')
            additionalInformation.Placeholder = 'Default';
        if (typeof additionalInformation.Width == 'undefined')
            additionalInformation.Width = '100%';
        return _this;
    }
    SimpleTextProperty.prototype.InternalGenerateHtml = function ($fieldContainer) {
        var _this = this;
        var $input;
        if (this.AdditionalInformation.MultipleLine == true) {
            $input = rnJQuery('<textarea style="width:' + this.AdditionalInformation.Width + ';" class="rednao-input-large form-control" data-type="input" name="name" id="' + this.PropertyId + '" placeholder="' + this.AdditionalInformation.Placeholder + '">' + RedNaoEscapeHtml(this.GetPropertyCurrentValue()) + '</textarea>');
        }
        else {
            $input = rnJQuery('<input style="width: ' + this.AdditionalInformation.Width + ';" class="rednao-input-large form-control" data-type="input" type="text" name="name" id="' + this.PropertyId + '" value="' + RedNaoEscapeHtml(this.GetPropertyCurrentValue()) + '" placeholder="' + this.AdditionalInformation.Placeholder + '"/>');
        }
        $fieldContainer.append($input);
        $input.on('input', function () {
            _this.Manipulator.SetValue(_this.PropertiesObject, _this.PropertyName, (rnJQuery("#" + _this.PropertyId).val()), _this.AdditionalInformation);
            _this.RefreshElement();
        });
    };
    return SimpleTextProperty;
}(ElementPropertiesBase_1.ElementPropertiesBase));
exports.SimpleTextProperty = SimpleTextProperty;
//# sourceMappingURL=SimpleTextProperty.js.map