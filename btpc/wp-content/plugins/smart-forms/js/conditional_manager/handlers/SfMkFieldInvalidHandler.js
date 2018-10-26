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
var SfMkFieldInvalidHandler = /** @class */ (function (_super) {
    __extends(SfMkFieldInvalidHandler, _super);
    function SfMkFieldInvalidHandler(options) {
        var _this = _super.call(this, options) || this;
        _this.Options.Type = "SfMkFieldInvalidHandler";
        _this.Fields = "";
        _this.FormElements = null;
        return _this;
    }
    SfMkFieldInvalidHandler.prototype.ExecutingPromise = function () {
        //this.ExecuteFalseAction();
    };
    SfMkFieldInvalidHandler.prototype.GetConditionalSteps = function () {
        if (this.IsNew) {
            this.Options.GeneralInfo = {};
            this.Options.FieldPicker = {};
            this.Options.Condition = {};
            this.Options.ErrorMessage = {};
        }
        return [
            { Type: "SfNamePicker", Label: 'HowDoYouWantToName', Options: this.Options.GeneralInfo, Id: this.Id },
            { Type: "SfHandlerFieldPicker", Label: 'whichFieldYouWantToMakeInvalid', Options: this.Options.FieldPicker },
            { Type: "SfHandlerConditionGenerator", Label: 'WhenDoYouWantToMakeInvalid', Options: this.Options.Condition },
            { Type: "SfTextPicker", Label: 'WhatMessageWhenInvalid', Options: this.Options.ErrorMessage }
        ];
    };
    ;
    SfMkFieldInvalidHandler.prototype.Initialize = function (form, data) {
        var _this = this;
        this.Form = form;
        this.Condition = this.Options.Condition;
        this.PreviousActionWas = -1;
        RedNaoEventManager.Subscribe('BeforeValidatingForm', function (args) {
            args.Promises.push(_this.ProcessCondition(_this.Form.GetCurrentData()).then(function (result) { if (result != null)
                result.Execute(); }));
        });
    };
    ;
    SfMkFieldInvalidHandler.prototype.GetFormElements = function () {
        if (this.FormElements == null) {
            this.FormElements = [];
            for (var i = 0; i < this.Options.FieldPicker.AffectedItems.length; i++) {
                var fieldId = this.Options.FieldPicker.AffectedItems[i];
                for (var t = 0; t < this.Form.FormElements.length; t++)
                    if (this.Form.FormElements[t].Id == fieldId)
                        this.FormElements.push(this.Form.FormElements[t]);
            }
        }
        return this.FormElements;
    };
    ;
    SfMkFieldInvalidHandler.prototype.ExecuteTrueAction = function () {
        var formElements = this.GetFormElements();
        var errorId = "mfi" + this.Id;
        for (var i = 0; i < formElements.length; i++)
            formElements[i].AddError(errorId, this.Options.ErrorMessage.Text);
    };
    ;
    SfMkFieldInvalidHandler.prototype.ExecuteFalseAction = function () {
        var formElements = this.GetFormElements();
        var errorId = "mfi" + this.Id;
        for (var i = 0; i < formElements.length; i++)
            formElements[i].RemoveError(errorId);
    };
    ;
    return SfMkFieldInvalidHandler;
}(SfConditionalHandlerBase));
window.SfMkFieldInvalidHandler = SfMkFieldInvalidHandler;
//# sourceMappingURL=SfMkFieldInvalidHandler.js.map