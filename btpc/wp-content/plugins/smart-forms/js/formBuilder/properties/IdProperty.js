"use strict";
var __extends = (this && this.__extends) || (function () {
    var extendStatics = Object.setPrototypeOf ||
        ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
        function (d, b) { for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p]; };
    return function (d, b) {
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
Object.defineProperty(exports, "__esModule", { value: true });
var ElementPropertiesBase_1 = require("./ElementPropertiesBase");
var IdProperty = /** @class */ (function (_super) {
    __extends(IdProperty, _super);
    function IdProperty(formelement, propertiesObject) {
        return _super.call(this, formelement, propertiesObject, "Id", "Id", { ManipulatorType: 'basic' }) || this;
    }
    IdProperty.prototype.InternalGenerateHtml = function ($fieldContainer) {
        var _this = this;
        this.PreviousId = this.FormElement.Id;
        var value = this.PreviousId;
        var $input = rnJQuery("<input style=\"width: 206px;\" class=\"rednao-input-large form-control\" data-type=\"input\" maxlength=\"50\" type=\"text\" name=\"name\" id=\"" + this.PropertyId + "\" value=\"" + value + "\" placeholder=\"Default\"/>");
        $fieldContainer.append($input);
        $input.change(function () {
            var jqueryElement = $input;
            var fieldName = jqueryElement.val().trim();
            if (!fieldName.match(/^[a-zA-Z]([a-zA-Z]|[0-9])*$/)) {
                alert("Invalid field name, it should start with a letter and not contain spaces or symbols");
                jqueryElement.val(_this.PreviousId);
                return;
            }
            var formElements = SmartFormsAddNewVar.FormBuilder.RedNaoFormElements;
            for (var i = 0; i < formElements.length; i++) {
                if (fieldName.toLowerCase() == formElements[i].Id.toLowerCase()) {
                    alert("The field " + fieldName + " already exists");
                    jqueryElement.val(_this.PreviousId);
                    return;
                }
            }
            _this.FormElement.Id = fieldName;
            _this.PropertiesObject.Id = fieldName;
            var jQueryElement = rnJQuery('#' + _this.PreviousId);
            jQueryElement.attr('id', fieldName);
            var refreshedElements = _this.FormElement.RefreshElement();
            refreshedElements.find('input[type=submit],button').click(function (e) {
                e.preventDefault();
                e.stopPropagation();
            });
            _this.RefreshElement();
        });
    };
    ;
    return IdProperty;
}(ElementPropertiesBase_1.ElementPropertiesBase));
exports.IdProperty = IdProperty;
//# sourceMappingURL=IdProperty.js.map