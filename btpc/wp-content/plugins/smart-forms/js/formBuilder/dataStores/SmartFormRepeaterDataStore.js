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
var SmartFormBasicDataStore_1 = require("./SmartFormBasicDataStore");
var SmartFormRepeaterDataStore = /** @class */ (function (_super) {
    __extends(SmartFormRepeaterDataStore, _super);
    function SmartFormRepeaterDataStore() {
        var _this = _super.call(this, 'value') || this;
        _this.rows = [];
        return _this;
    }
    SmartFormRepeaterDataStore.prototype.toString = function () {
        alert('Sorry a repeater field can not be used in formulas like this');
    };
    SmartFormRepeaterDataStore.prototype.Clone = function () {
        return Object.assign(Object.create(Object.getPrototypeOf(this)), this);
    };
    SmartFormRepeaterDataStore.prototype.GetTotal = function (fieldId) {
        var data = RedNaoFormulaManagerVar.Data;
        var total = 0;
        for (var i = 0; i < this.value.length; i++) {
            if (typeof data[fieldId + '_row_' + i] != 'undefined')
                total += data[fieldId + '_row_' + i].toString();
        }
        return total;
    };
    return SmartFormRepeaterDataStore;
}(SmartFormBasicDataStore_1.SmartFormBasicDataStore));
exports.SmartFormRepeaterDataStore = SmartFormRepeaterDataStore;
//# sourceMappingURL=SmartFormRepeaterDataStore.js.map