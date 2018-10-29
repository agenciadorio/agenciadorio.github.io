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
var PropertyBase_1 = require("../properties/PropertyBase");
var StyleGroupBase = /** @class */ (function () {
    function StyleGroupBase(fieldToEdit, elementClass, scope, groupName, $container) {
        this.fieldToEdit = fieldToEdit;
        this.elementClass = elementClass;
        this.scope = scope;
        this.groupName = groupName;
        this.$container = $container;
        this.properties = [];
        var id = groupName.replace(' ', '_');
        this.$accordionGroup = rnJQuery("<div class=\"styleGroup\">\n                                            <div class=\"sfStyleTitle\">\n                                                <h5>\n                                                    <a data-toggle=\"collapse\" href=\"#" + id + "\" class=\"collapsed\"><span class=\"sfAccordionIcon glyphicon glyphicon-chevron-right\"></span>" + groupName + "</a>\n                                                </h5>\n                                            </div>\n                                            <div class=\"sfStyleContainer collapse\"  id=\"" + id + "\"><div class=\"clearer\" style=\"clear:both;\"></div></div>                                             \n                                      </div>");
        this.$propertiesContainer = this.$accordionGroup.find('.sfStyleContainer');
        this.$accordionGroup.find('.sfStyleContainer').collapse();
        $container.append(this.$accordionGroup);
    }
    StyleGroupBase.prototype.Show = function () {
        this.$accordionGroup.find('.sfStyleTitle a').removeClass('collapsed');
        this.$accordionGroup.find('.sfStyleContainer').addClass('in');
        return this;
    };
    StyleGroupBase.prototype.AddProperty = function (property) {
        this.properties.push(property);
        property.Generate(this.$propertiesContainer);
        return this;
    };
    return StyleGroupBase;
}());
exports.StyleGroupBase = StyleGroupBase;
var LabelStyleGroup = /** @class */ (function (_super) {
    __extends(LabelStyleGroup, _super);
    function LabelStyleGroup() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    LabelStyleGroup.prototype.Generate = function () {
        this.AddProperty(new PropertyBase_1.SliderProperty(this.fieldToEdit, this.elementClass, this.scope, 'Size', 'font-size'));
        this.AddProperty(new PropertyBase_1.FontFamily(this.fieldToEdit, this.elementClass, this.scope));
        this.AddProperty(new PropertyBase_1.TextDecoration(this.fieldToEdit, this.elementClass, this.scope));
        this.AddProperty(new PropertyBase_1.Capitalization(this.fieldToEdit, this.elementClass, this.scope));
        this.AddProperty(new PropertyBase_1.ColorPicker(this.fieldToEdit, this.elementClass, this.scope, 'Color', 'color'));
        this.AddProperty(new PropertyBase_1.ButtonsProperty(this.fieldToEdit, this.elementClass, this.scope, 'Bold', 'font-weight')
            .AddOption("Yes", "bold")
            .AddOption("No", "normal"));
        this.AddProperty(new PropertyBase_1.ButtonsProperty(this.fieldToEdit, this.elementClass, this.scope, 'Italic', 'font-style')
            .AddOption("Yes", "italic")
            .AddOption("No", "normal"));
    };
    return LabelStyleGroup;
}(StyleGroupBase));
exports.LabelStyleGroup = LabelStyleGroup;
var ControlStyleGroup = /** @class */ (function (_super) {
    __extends(ControlStyleGroup, _super);
    function ControlStyleGroup() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    ControlStyleGroup.prototype.Generate = function () {
        this.AddProperty(new PropertyBase_1.ColorPicker(this.fieldToEdit, this.elementClass, this.scope, 'Background Color', 'background-color'));
        this.AddProperty(new PropertyBase_1.BorderProperty(this.fieldToEdit, this.elementClass, this.scope));
    };
    return ControlStyleGroup;
}(StyleGroupBase));
exports.ControlStyleGroup = ControlStyleGroup;
var DynamicStyleGroup = /** @class */ (function (_super) {
    __extends(DynamicStyleGroup, _super);
    function DynamicStyleGroup() {
        var _this = _super !== null && _super.apply(this, arguments) || this;
        _this._property = [];
        return _this;
    }
    DynamicStyleGroup.prototype.Generate = function () {
        for (var _i = 0, _a = this._property; _i < _a.length; _i++) {
            var property = _a[_i];
            this.AddProperty(property);
        }
    };
    DynamicStyleGroup.prototype.CreateProperty = function (property) {
        this._property.push(property);
        return this;
    };
    return DynamicStyleGroup;
}(StyleGroupBase));
exports.DynamicStyleGroup = DynamicStyleGroup;
//# sourceMappingURL=StyleSetBase.js.map