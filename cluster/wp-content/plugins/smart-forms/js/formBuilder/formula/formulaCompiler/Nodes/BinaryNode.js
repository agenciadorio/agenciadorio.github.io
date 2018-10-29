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
var NodeBase_1 = require("./NodeBase");
var BinaryNode = /** @class */ (function (_super) {
    __extends(BinaryNode, _super);
    function BinaryNode(leftNode, rightNode) {
        var _this = _super.call(this) || this;
        _this.LeftNode = leftNode;
        _this.RightNode = rightNode;
        return _this;
    }
    BinaryNode.prototype.Visit = function () {
    };
    return BinaryNode;
}(NodeBase_1.NodeBase));
exports.BinaryNode = BinaryNode;
//# sourceMappingURL=BinaryNode.js.map