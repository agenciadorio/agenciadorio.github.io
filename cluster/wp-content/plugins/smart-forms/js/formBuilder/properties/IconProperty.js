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
var RedNaoIconSelector_1 = require("./RedNaoIconSelector");
var ElementPropertiesBase_1 = require("./ElementPropertiesBase");
var IconProperty = /** @class */ (function (_super) {
    __extends(IconProperty, _super);
    function IconProperty() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    IconProperty.prototype.InternalGenerateHtml = function ($fieldContainer) {
        var _this = this;
        var value = this.GetPropertyCurrentValue().ClassName;
        var $input = rnJQuery("<div>\n                <span class=\"" + RedNaoEscapeHtml(value) + "\"></span><button style=\"margin-left: 2px\">Edit</button>\n             </div>");
        $fieldContainer.append($input);
        $input.find('button').click(function (e) {
            e.preventDefault();
            RedNaoIconSelector_1.RedNaoIconSelector.Current.Show('add', _this.GetPropertyCurrentValue().ClassName, function (itemClass, orientation) {
                _this.PropertiesObject[_this.PropertyName] = {
                    ClassName: itemClass,
                    Orientation: orientation
                };
                _this.RefreshElement();
                $input.find('span').attr('class', itemClass);
            });
        });
    };
    return IconProperty;
}(ElementPropertiesBase_1.ElementPropertiesBase));
exports.IconProperty = IconProperty;
//# sourceMappingURL=IconProperty.js.map