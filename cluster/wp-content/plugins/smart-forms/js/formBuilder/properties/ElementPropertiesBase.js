"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var RedNaoIconSelector_1 = require("./RedNaoIconSelector");
var ElementPropertiesBase = /** @class */ (function () {
    function ElementPropertiesBase(formelement, propertiesObject, propertyName, propertyTitle, additionalInformation) {
        this.tooltip = null;
        this.IconOptions = null;
        this.enableFormula = false;
        if (additionalInformation.ManipulatorType == 'basic')
            this.Manipulator = RedNaoBasicManipulatorInstance;
        this.FormElement = formelement;
        this.AdditionalInformation = additionalInformation;
        this.PropertiesObject = propertiesObject;
        this.PropertyName = propertyName;
        this.PropertyTitle = propertyTitle;
        this.PropertyId = "redNaoFormProperty" + this.PropertyName;
        this.$PropertiesContainer = null;
        if (typeof this.AdditionalInformation.ToolTip != 'undefined')
            this.tooltip = this.AdditionalInformation.ToolTip;
        if (typeof this.AdditionalInformation.IconOptions != 'undefined')
            this.IconOptions = this.AdditionalInformation.IconOptions;
    }
    ElementPropertiesBase.prototype.SetTooltip = function (text) {
        this.tooltip = { Text: text };
        return this;
    };
    ElementPropertiesBase.prototype.FormulaExists = function (formElement, propertyName) {
        return RedNaoPathExists(formElement, 'Options.Formulas.' + propertyName + '.Value') && formElement.Options.Formulas[propertyName].Value != "";
    };
    ;
    ElementPropertiesBase.prototype.SetEnableFormula = function () {
        this.enableFormula = true;
        return this;
    };
    ElementPropertiesBase.prototype.CreateProperty = function (jQueryObject) {
        this.$PropertiesContainer = rnJQuery('<div class="row col-sm-12"></div>');
        jQueryObject.append(this.$PropertiesContainer);
        this.GenerateHtml();
    };
    ;
    ElementPropertiesBase.prototype.GeneratePropertyContainer = function () {
    };
    ElementPropertiesBase.prototype.GetFieldTemplate = function () {
        return rnJQuery("<div class=\"col-sm-4\">\n                    <label class=\"rednao-properties-control-label\"> " + this.PropertyTitle + "</label>\n             </div>\n             <div class=\"col-sm-8\">\n                <div style=\"padding:0;margin:0;display: inline-block; width:90%\" class=\"fieldContainer\"  ></div>\n                <div  class=\"iconArea\" style=\"width: 10%;padding:0;margin:0;display: inline-block;float:right;\"></div>\n             </div>");
    };
    ElementPropertiesBase.prototype.GenerateHtml = function () {
        this.$PropertiesContainer.append(this.GetFieldTemplate());
        var $fieldContainer = this.$PropertiesContainer.find('.fieldContainer');
        this.InternalGenerateHtml($fieldContainer);
        if (this.enableFormula)
            this.AppendFormulaIcon();
        if (this.tooltip != null)
            this.AddToolTip();
        if (this.IconOptions != null)
            this.AddIconSelector();
    };
    ElementPropertiesBase.prototype.RefreshProperty = function () {
        this.$PropertiesContainer.empty();
        this.GenerateHtml();
    };
    ;
    ElementPropertiesBase.prototype.GetPropertyCurrentValue = function () {
        return this.Manipulator.GetValue(this.PropertiesObject, this.PropertyName, this.AdditionalInformation);
    };
    ;
    ElementPropertiesBase.prototype.UpdateProperty = function () {
        this.Manipulator.SetValue(this.PropertiesObject, this.PropertyName, rnJQuery("#" + this.PropertyId).val(), this.AdditionalInformation);
    };
    ;
    ElementPropertiesBase.prototype.RefreshElement = function () {
        var previousClasses = this.FormElement.JQueryElement.attr('class');
        var newClasses = this.FormElement.GetElementClasses();
        if (previousClasses.indexOf('SmartFormsElementSelected') >= 0)
            newClasses += ' SmartFormsElementSelected';
        var refreshedElements = this.FormElement.RefreshElement();
        this.FormElement.JQueryElement.attr('class', newClasses);
        refreshedElements.find('input[type=submit],button').click(function (e) { e.preventDefault(); e.stopPropagation(); });
        RedNaoEventManager.Publish('FormDesignUpdated');
    };
    ;
    ElementPropertiesBase.prototype.AppendFormulaIcon = function () {
        var _this = this;
        var $formulaImg = rnJQuery("<img class=\"formulaIcon\" style=\"width:15px;height: 20px; vertical-align: middle;cursor:pointer;margin-left:2px;\" title=\"Formula\" src=\"" + (smartFormsRootPath + (this.FormulaExists(this.FormElement, this.PropertyName) ? 'images/formula_used.png' : 'images/formula.png')) + "\"/></td>'");
        this.$PropertiesContainer.find('.iconArea').append($formulaImg);
        $formulaImg.click(function () {
            RedNaoEventManager.Publish('FormulaButtonClicked', {
                "FormElement": _this.FormElement,
                "PropertyName": _this.PropertyName,
                AdditionalInformation: _this.AdditionalInformation,
                Image: $formulaImg
            });
        });
    };
    ElementPropertiesBase.prototype.AddToolTip = function () {
        this.$PropertiesContainer.find('.rednao-properties-control-label').append("<span style=\"color:black; margin-left: 2px;cursor:pointer;\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"" + RedNaoEscapeHtml(this.tooltip.Text) + "\" class=\"glyphicon glyphicon-question-sign sfToolTip\"></span>");
        this.$PropertiesContainer.find('.sfToolTip').tooltip({ html: true });
    };
    ElementPropertiesBase.prototype.AddIconSelector = function () {
        var _this = this;
        var selected = '';
        var defaultValue = this.PropertiesObject[this.PropertyName + '_Icon'].ClassName;
        if (defaultValue != '')
            selected = 'sfSelected';
        var addIconButton = rnJQuery('<a style="margin-left: 2px;font-size: 20px;line-height:20px;" href="#"><span class="fa fa-smile-o sfAddIcon ' + selected + '" title="Add Icon"></span></a>');
        addIconButton.click(function () {
            RedNaoIconSelector_1.RedNaoIconSelector.Current.Show(_this.IconOptions.Type, defaultValue, function (itemClass, orientation) {
                defaultValue = itemClass;
                _this.IconSelected(itemClass, orientation, addIconButton);
            });
        });
        this.$PropertiesContainer.find('.iconArea').append(addIconButton);
    };
    ElementPropertiesBase.prototype.IconSelected = function (itemClass, orientation, $addIconButton) {
        this.PropertiesObject[this.PropertyName + '_Icon'] = {
            ClassName: itemClass,
            Orientation: orientation
        };
        if (itemClass == '')
            $addIconButton.find('span').removeClass('sfSelected');
        else
            $addIconButton.find('span').addClass('sfSelected');
        this.RefreshElement();
    };
    return ElementPropertiesBase;
}());
exports.ElementPropertiesBase = ElementPropertiesBase;
//# sourceMappingURL=ElementPropertiesBase.js.map