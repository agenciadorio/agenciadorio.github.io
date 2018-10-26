"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var Parser_1 = require("./Parser");
var FormulaCompiler = /** @class */ (function () {
    function FormulaCompiler(stringToProcess) {
        this.stringToProcess = stringToProcess;
        this.parser = new Parser_1.Parser(this.stringToProcess);
    }
    FormulaCompiler.prototype.Compile = function () {
        return this.parser.Parse();
    };
    return FormulaCompiler;
}());
exports.FormulaCompiler = FormulaCompiler;
window.FormulaCompiler = FormulaCompiler;
//# sourceMappingURL=FormulaCompiler.js.map