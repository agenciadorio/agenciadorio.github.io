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
var WhateverNode = /** @class */ (function (_super) {
    __extends(WhateverNode, _super);
    function WhateverNode() {
        var _this = _super !== null && _super.apply(this, arguments) || this;
        _this.ChildTokens = [];
        return _this;
    }
    WhateverNode.prototype.Visit = function () {
        throw new Error("Method not implemented.");
    };
    return WhateverNode;
}(NodeBase_1.NodeBase));
exports.WhateverNode = WhateverNode;
//# sourceMappingURL=WhateverNode.js.map