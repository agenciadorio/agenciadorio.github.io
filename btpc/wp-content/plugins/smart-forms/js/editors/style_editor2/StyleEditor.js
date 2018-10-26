"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var StyleSetBase_1 = require("./set/StyleSetBase");
var PropertyBase_1 = require("./properties/PropertyBase");
var StyleEditor = /** @class */ (function () {
    function StyleEditor() {
        var _this = this;
        this.fieldToEdit = null;
        this.targetScope = 'af';
        this.styleGroups = [];
        this.$styleEditorContainer = rnJQuery('#formStylesContainer');
        rnJQuery('#sfSettingTabs').on('show.bs.tab', function (e) {
            if (rnJQuery(e.target).attr('id') == 'formRadio4')
                _this.RefreshEditor();
        });
    }
    StyleEditor.prototype.RefreshEditor = function () {
        this.fieldToEdit = this.GetSelectedField();
        this.$styleEditorContainer.empty();
        this.CreateSelectionCombo(this.fieldToEdit);
        this.CreateStyleProperties(this.fieldToEdit);
        for (var _i = 0, _a = this.styleGroups; _i < _a.length; _i++) {
            var group = _a[_i];
            group.Generate();
        }
    };
    StyleEditor.prototype.GetSelectedField = function () {
        var fieldId = rnJQuery('.SmartFormsElementSelected').attr('id');
        for (var _i = 0, _a = SmartFormsAddNewVar.FormBuilder.RedNaoFormElements; _i < _a.length; _i++) {
            var field = _a[_i];
            if (field.Id == fieldId)
                return field;
        }
        return null;
    };
    StyleEditor.prototype.CreateSelectionCombo = function (field) {
        var _this = this;
        var $combo = rnJQuery('<select></select>');
        $combo.append("<option value=\"fc\" " + (this.targetScope == 'fc' ? "selected='selected'" : '') + ">Form Container</option>");
        $combo.append("<option value=\"af\" " + (this.targetScope == 'af' ? "selected='selected'" : '') + ">All fields</option>");
        if (this.fieldToEdit != null) {
            $combo.append("<option value=\"sfo\" " + (this.targetScope == 'sfo' ? "selected='selected'" : '') + ">Selected field only</option>");
            $combo.append("<option value=\"afsttso\" " + (this.targetScope == 'afost' ? "selected='selected'" : '') + ">All fields similar to the selected one</option>");
        }
        var $container = rnJQuery('<div class="row" style="text-align: right;"><label style="padding-right: 5px;font-weight: normal;">Apply the style to</label></div>');
        $combo.change(function () {
            _this.ScopeChanged($combo.val());
        });
        $container.append($combo);
        this.$styleEditorContainer.append($container);
    };
    StyleEditor.prototype.CreateStyleProperties = function (fieldToEdit) {
        var $stylePropertiesContainer = rnJQuery('<div></div>');
        this.$styleEditorContainer.append($stylePropertiesContainer);
        this.CreatePropertiesForField(fieldToEdit, $stylePropertiesContainer);
    };
    StyleEditor.prototype.CreatePropertiesForField = function (fieldToEdit, $container) {
        console.log(this.targetScope);
        var $accordion = rnJQuery('<div id="sfStyleAccordion"></div>');
        this.$styleEditorContainer.append($accordion);
        this.styleGroups = [];
        if (this.targetScope != 'fc')
            this.styleGroups.push(new StyleSetBase_1.LabelStyleGroup(fieldToEdit, '.rednao_control_label', this.targetScope, "Label Styles", $accordion).Show());
        this.styleGroups.push(new StyleSetBase_1.ControlStyleGroup(fieldToEdit, '', this.targetScope, "Field Styles", $accordion).Show());
        if (fieldToEdit.Options.ClassName == 'rednaosubmissionbutton')
            this.styleGroups.push(new StyleSetBase_1.DynamicStyleGroup(fieldToEdit, '', this.targetScope, "Button Styles", $accordion)
                .CreateProperty(new PropertyBase_1.ColorPicker(fieldToEdit, '.redNaoSubmitButton', this.targetScope, 'Text Color', 'color')
                .SetDimensions(6, 6, 6))
                .CreateProperty(new PropertyBase_1.ColorPicker(fieldToEdit, '.redNaoSubmitButton', this.targetScope, 'Background Color', 'background-color')
                .SetDimensions(6, 6, 6)));
    };
    StyleEditor.prototype.ScopeChanged = function (scope) {
        this.targetScope = scope;
        this.RefreshEditor();
    };
    return StyleEditor;
}());
exports.StyleEditor = StyleEditor;
//# sourceMappingURL=StyleEditor.js.map