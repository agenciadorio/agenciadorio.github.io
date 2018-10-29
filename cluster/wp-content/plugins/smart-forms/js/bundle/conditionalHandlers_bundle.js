/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 19);
/******/ })
/************************************************************************/
/******/ ({

/***/ 19:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(20);
__webpack_require__(21);
__webpack_require__(22);
__webpack_require__(23);
var SmartFormsConditionalHandlerArray = [];
function SmartFormsGetConditionalHandlerByType(handlerId, options) {
    var handlers = SmartFormsGetConditionalHandlerArray();
    for (var i = 0; i < handlers.length; i++) {
        if (handlers[i].id == handlerId) {
            return handlers[i].create(options);
        }
    }
    throw ('Invalid handler');
}
function SmartFormsGetConditionalHandlerArray() {
    SmartFormsConditionalHandlerArray = [
        { Label: "Show fields depending on a condition", id: "SfShowConditionalHandler", create: function (options) { return new SfShowConditionalHandler(options); }, ShouldShow: function (builder) { return true; } },
        { Label: "Make fields invalid depending on a condition", id: "SfMkFieldInvalidHandler", create: function (options) { return new SfMkFieldInvalidHandler(options); }, ShouldShow: function (builder) { return true; } },
        { Label: "Show a multiple step tab depending on a condition", id: "SfShowStepHandler", create: function (options) { return new SfShowStepHandler(options); }, ShouldShow: function (builder) { return builder.FormType == "sec"; } },
    ];
    return SmartFormsConditionalHandlerArray;
}
function SmartFormsCalculateCondition(condition, values, instance) {
    var compiledCondition = condition.CompiledCondition;
    if (typeof condition.Mode != 'undefined' && condition.Mode == 'Formula')
        compiledCondition = condition.Formula.CompiledFormula;
    var Remote = null;
    if (instance == null)
        Remote = new SmartFormsRemote();
    else
        Remote = instance.GetRemote();
    condition = new Function('formData,Remote', 'return ' + compiledCondition);
    return condition(values, Remote);
}
RedNaoEventManager.Subscribe('CalculateCondition', function (data) { return SmartFormsCalculateCondition(data.Condition, data.Values, data.Instance); });
window.SmartFormsGetConditionalHandlerByType = SmartFormsGetConditionalHandlerByType;
window.SmartFormsGetConditionalHandlerArray = SmartFormsGetConditionalHandlerArray;
window.SmartFormsCalculateCondition = SmartFormsCalculateCondition;


/***/ }),

/***/ 20:
/***/ (function(module, exports, __webpack_require__) {

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
        if (result) {
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


/***/ }),

/***/ 21:
/***/ (function(module, exports) {

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
var SfShowConditionalHandler = /** @class */ (function (_super) {
    __extends(SfShowConditionalHandler, _super);
    function SfShowConditionalHandler(options) {
        var _this = _super.call(this, options) || this;
        _this.Options.Type = "SfShowConditionalHandler";
        _this.Fields = "";
        _this.FormElements = null;
        return _this;
    }
    SfShowConditionalHandler.prototype.ExecutingPromise = function () {
    };
    SfShowConditionalHandler.prototype.GetConditionalSteps = function () {
        if (this.IsNew) {
            this.Options.GeneralInfo = {};
            this.Options.FieldPicker = {};
            this.Options.Condition = {};
        }
        return [
            { Type: "SfNamePicker", Label: 'HowDoYouWantToName', Options: this.Options.GeneralInfo, Id: this.Id },
            { Type: "SfHandlerFieldPicker", Label: 'typeOrSelectFieldsToBeShown', Options: this.Options.FieldPicker },
            { Type: "SfHandlerConditionGenerator", Label: 'WhenDoYouWantToDisplay', Options: this.Options.Condition }
        ];
    };
    ;
    SfShowConditionalHandler.prototype.Initialize = function (form, data) {
        this.Form = form;
        this.PreviousActionWas = -1;
        this.Condition = this.Options.Condition;
        this.SubscribeCondition(this.Options.Condition, data);
        this.ProcessCondition(data).then(function (result) { if (result != null)
            result.Execute(); });
    };
    ;
    SfShowConditionalHandler.prototype.HideFields = function () {
        this.Form.JQueryForm.find(this.GetFieldIds()).css('display', 'none');
        var formElements = this.GetFormElements();
        for (var i = 0; i < formElements.length; i++)
            formElements[i].Ignore();
    };
    ;
    SfShowConditionalHandler.prototype.GetFieldIds = function () {
        if (this.Fields == "")
            for (var i = 0; i < this.Options.FieldPicker.AffectedItems.length; i++) {
                if (i > 0)
                    this.Fields += ",";
                this.Fields += '#' + this.Options.FieldPicker.AffectedItems[i];
            }
        return this.Fields;
    };
    ;
    SfShowConditionalHandler.prototype.GetFormElements = function () {
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
    SfShowConditionalHandler.prototype.ExecuteTrueAction = function () {
        this.Form.JQueryForm.find(this.GetFieldIds()).slideDown();
        var formElements = this.GetFormElements();
        for (var i = 0; i < formElements.length; i++)
            formElements[i].UnIgnore();
    };
    ;
    SfShowConditionalHandler.prototype.ExecuteFalseAction = function () {
        this.Form.JQueryForm.find(this.GetFieldIds()).slideUp();
        var formElements = this.GetFormElements();
        for (var i = 0; i < formElements.length; i++)
            formElements[i].Ignore();
    };
    ;
    return SfShowConditionalHandler;
}(SfConditionalHandlerBase));
window.SfShowConditionalHandler = SfShowConditionalHandler;


/***/ }),

/***/ 22:
/***/ (function(module, exports) {

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


/***/ }),

/***/ 23:
/***/ (function(module, exports) {

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
var SfShowStepHandler = /** @class */ (function (_super) {
    __extends(SfShowStepHandler, _super);
    function SfShowStepHandler(options) {
        var _this = _super.call(this, options) || this;
        _this.$StepList = [];
        _this.Options.Type = "SfShowStepHandler";
        _this.Fields = "";
        _this.FormElements = null;
        return _this;
    }
    SfShowStepHandler.prototype.ExecutingPromise = function () {
    };
    SfShowStepHandler.prototype.GetConditionalSteps = function () {
        if (this.IsNew) {
            this.Options.GeneralInfo = {};
            this.Options.StepPicker = {};
            this.Options.Condition = {};
        }
        return [
            { Type: "SfNamePicker", Label: 'HowDoYouWantToName', Options: this.Options.GeneralInfo, Id: this.Id },
            { Type: "SfStepPicker", Label: 'SelectTheStepsToBeShow', Options: this.Options.StepPicker },
            { Type: "SfHandlerConditionGenerator", Label: 'WhenDoYouWantToDisplay', Options: this.Options.Condition }
        ];
    };
    ;
    SfShowStepHandler.prototype.Initialize = function (form, data) {
        var _this = this;
        this.Form = form;
        var stepPickerOptions = this.Options.StepPicker;
        this.Form.JQueryForm.find('.rnMLStep').each(function (index, value) {
            if (stepPickerOptions.StepsToShow.find(function (x) { return x == rnJQuery(value).data('step-id'); }))
                _this.$StepList.push({ $element: rnJQuery(value), id: rnJQuery(value).data('step-id') });
        });
        this.PreviousActionWas = -1;
        this.Condition = this.Options.Condition;
        this.SubscribeCondition(this.Options.Condition, data);
        this.ProcessCondition(data).then(function (result) { if (result != null)
            result.Execute(); });
    };
    ;
    SfShowStepHandler.prototype.GetFormElements = function () {
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
    SfShowStepHandler.prototype.ExecuteTrueAction = function () {
        var _loop_1 = function (step) {
            if (typeof step.OriginalWidth == "undefined")
                return "continue";
            this_1.Form.MultipleStepsManager.UnIgnoreStep(step.id);
            step.$element.css('display', 'block');
            step.$element.velocity({ width: step.OriginalWidth, 'padding-left': 30, 'padding-right': 20 }, 200, 'easeInExp', function () { step.$element.css('width', ''); });
        };
        var this_1 = this;
        for (var _i = 0, _a = this.$StepList; _i < _a.length; _i++) {
            var step = _a[_i];
            _loop_1(step);
        }
        if (this.Form != null && this.Form.MultipleStepsManager != null)
            this.Form.MultipleStepsManager.RefreshNextButtonText();
    };
    ;
    SfShowStepHandler.prototype.ExecuteFalseAction = function () {
        var _loop_2 = function (step) {
            this_2.Form.MultipleStepsManager.IgnoreStep(step.id);
            step.OriginalWidth = step.$element.width();
            step.$element.velocity({ width: 0, 'padding-left': 0, 'padding-right': 0 }, 200, 'easeOutExp', function () { step.$element.css('display', 'none'); });
        };
        var this_2 = this;
        for (var _i = 0, _a = this.$StepList; _i < _a.length; _i++) {
            var step = _a[_i];
            _loop_2(step);
        }
        if (this.Form != null && this.Form.MultipleStepsManager != null)
            this.Form.MultipleStepsManager.RefreshNextButtonText();
    };
    ;
    return SfShowStepHandler;
}(SfConditionalHandlerBase));
window.SfShowStepHandler = SfShowStepHandler;


/***/ })

/******/ });
//# sourceMappingURL=conditionalHandlers_bundle.js.map