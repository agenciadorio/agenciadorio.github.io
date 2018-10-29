"use strict";
var SfConditionalHandlerBase = /** @class */ (function () {
    function SfConditionalHandlerBase(options) {
        this._remote = null;
        this.PreviousActionWas = -1;
        if (options == null) {
            this.Options = {};
            SfConditionalHandlerBase.ConditionId++;
            this.Options.Id = SfConditionalHandlerBase.ConditionId;
            this.IsNew = true;
        }
        else
            this.Options = options;
        this.Id = this.Options.Id;
    }
    SfConditionalHandlerBase.prototype.GetOptionsToSave = function () {
        this.Options.Label = this.Options.GeneralInfo.Name;
        return this.Options;
    };
    ;
    SfConditionalHandlerBase.prototype.SubscribeCondition = function (condition, initialData) {
        var self = this;
        //this.ConditionFunction=new Function('formData','return '+condition.CompiledCondition);
        var fieldsInCondition = [];
        if (condition.Mode == 'Formula') {
            for (var _i = 0, _a = condition.Formula.FieldsUsed; _i < _a.length; _i++) {
                var field = _a[_i];
                fieldsInCondition.push(field);
            }
        }
        else
            for (var i = 0; i < condition.Conditions.length; i++) {
                fieldsInCondition.push(condition.Conditions[i].Field);
                if (typeof condition.Conditions[i].Formula != 'undefined' && condition.Conditions[i].Formula.RowMode == "Formula") {
                    for (var _b = 0, _c = condition.Conditions[i].Formula.Formula.FieldsUsed; _b < _c.length; _b++) {
                        var fieldInFormula = _c[_b];
                        fieldsInCondition.push(fieldInFormula);
                    }
                }
            }
        RedNaoEventManager.Subscribe('ProcessConditionsAfterValueChanged', function (data) {
            if (fieldsInCondition.indexOf(data.FieldName) > -1) {
                var action = self.ProcessCondition(data.Data);
                if (action != null)
                    data.Actions.push(action);
            }
        });
    };
    ;
    SfConditionalHandlerBase.prototype.GetRemote = function () {
        if (this._remote == null)
            this._remote = new SmartFormsRemote();
        return this._remote;
    };
    SfConditionalHandlerBase.prototype.ProcessCondition = function (data) {
        var _this = this;
        var result = RedNaoEventManager.Publish('CalculateCondition', { Condition: this.Condition, Values: data, Instance: this });
        if (result instanceof Promise) {
            this.ExecutingPromise();
            return result.then(function (result) { return _this.ProcessResult(result); });
        }
        else
            return new Promise(function (resolve) { resolve(_this.ProcessResult(result)); });
    };
    ;
    SfConditionalHandlerBase.prototype.ProcessResult = function (result) {
        var _this = this;
        if (result) //this.ConditionFunction(data))
         {
            if (this.PreviousActionWas != 1) {
                return {
                    ActionType: 'show',
                    Execute: function () {
                        _this.PreviousActionWas = 1;
                        _this.ExecuteTrueAction();
                    }
                };
            }
        }
        else if (this.PreviousActionWas != 0) {
            return {
                ActionType: 'hide',
                Execute: function () {
                    _this.PreviousActionWas = 0;
                    _this.ExecuteFalseAction(null);
                }
            };
        }
        return null;
    };
    SfConditionalHandlerBase.ConditionId = 0;
    return SfConditionalHandlerBase;
}());
window.SfConditionalHandlerBase = SfConditionalHandlerBase;
//# sourceMappingURL=SfConditionalHandlerBase.js.map