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
var PropertyContainer = /** @class */ (function (_super) {
    __extends(PropertyContainer, _super);
    function PropertyContainer(Id, Title) {
        var _this = _super.call(this, null, null, null, null, {}) || this;
        _this.Id = Id;
        _this.Title = Title;
        _this.properties = [];
        _this._internal_id = 'property_' + PropertyContainer.nextId++;
        return _this;
    }
    PropertyContainer.prototype.InternalGenerateHtml = function ($fieldContainer) {
        throw 'Not needed';
    };
    PropertyContainer.prototype.CreateProperty = function ($container) {
        var _this = this;
        this.$accordionGroup = rnJQuery("<div class=\"propertyGroup\">\n                                            <div class=\"sfPropertyTytle\">\n                                                <h5>\n                                                    <a class=\"sfColapseLink collapsed\" data-toggle=\"collapse\" href=\"#" + this._internal_id + "\" class=\"collapsed\"><span class=\"sfAccordionIcon glyphicon glyphicon-chevron-right\"></span>" + this.Title + "</a>\n                                                </h5>\n                                            </div>\n                                            <div class=\"sfPropertyContainer collapse\"  id=\"" + this._internal_id + "\"></div>                                             \n                                      </div>");
        this.$propertiesContainer = this.$accordionGroup.find('.sfPropertyContainer');
        this.$accordionGroup.find('.sfStyleContainer').collapse();
        $container.append(this.$accordionGroup);
        for (var _i = 0, _a = this.properties; _i < _a.length; _i++) {
            var property = _a[_i];
            property.CreateProperty(this.$propertiesContainer);
        }
        this.$propertiesContainer.append('<div class="clearer" style="clear:both;"></div>');
        if (PropertyContainer.SectionHistory[this.Id])
            this.Show();
        this.$accordionGroup.find('.sfPropertyContainer').on('hidden.bs.collapse', function (e) {
            PropertyContainer.SectionHistory[_this.Id] = false;
        });
        this.$accordionGroup.find('.sfPropertyContainer').on('shown.bs.collapse', function (e) {
            PropertyContainer.SectionHistory[_this.Id] = true;
        });
    };
    PropertyContainer.prototype.AddProperties = function (properties) {
        for (var _i = 0, properties_1 = properties; _i < properties_1.length; _i++) {
            var property = properties_1[_i];
            this.properties.push(property);
        }
        return this;
    };
    PropertyContainer.prototype.Show = function () {
        this.$accordionGroup.find('.sfPropertyTytle a').removeClass('collapsed');
        this.$accordionGroup.find('.sfPropertyContainer').addClass('in');
    };
    PropertyContainer.prototype.AddProperty = function (property) {
        this.properties.push(property);
    };
    PropertyContainer.nextId = 1;
    PropertyContainer.SectionHistory = { general: true };
    return PropertyContainer;
}(ElementPropertiesBase_1.ElementPropertiesBase));
exports.PropertyContainer = PropertyContainer;
//# sourceMappingURL=PropertyContainer.js.map