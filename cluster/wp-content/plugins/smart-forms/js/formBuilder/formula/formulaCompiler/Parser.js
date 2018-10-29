"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var Lexer_1 = require("./Lexer");
/*
expr:(Method|String|Whatever)*
method:Remote.Get lparen (expr |,expr) rparent;
String: "|` cadena "|
whatever:todo lo que not tenga espacios
 */
var Parser = /** @class */ (function () {
    function Parser(stringToProcess) {
        this.stringToProcess = stringToProcess;
        this.code = '';
        this.header = '';
        this.footer = '';
        this.variableCount = 0;
        this.lexer = new Lexer_1.Lexer(this.stringToProcess);
        this.currentToken = this.lexer.GetNextToken();
    }
    Parser.prototype.eat = function (tokenType) {
        if (this.currentToken.Type != tokenType)
            throw ("Invalid Formula");
        else
            this.currentToken = this.lexer.GetNextToken();
    };
    Parser.prototype.Parse = function () {
        return this.Expr();
    };
    Parser.prototype.Expr = function () {
        while (this.currentToken != null) {
            if (this.currentToken.Type != Lexer_1.TokenType.Method) {
                this.code += this.currentToken.Value;
            }
            else
                this.code += this.Method();
            this.eat(this.currentToken.Type);
        }
        return this.CreateRootPromise();
    };
    Parser.prototype.Method = function () {
        var methodCode = this.currentToken.Value;
        this.eat(Lexer_1.TokenType.Method);
        var parenthesesCount = -1;
        while (this.currentToken != null && (this.currentToken.Type != Lexer_1.TokenType.RParen || parenthesesCount > 0)) {
            if (this.currentToken.Type == Lexer_1.TokenType.RParen)
                parenthesesCount--;
            if (this.currentToken.Type == Lexer_1.TokenType.LParen)
                parenthesesCount++;
            if (this.currentToken.Type == Lexer_1.TokenType.Method)
                methodCode += this.Method();
            else
                methodCode += this.currentToken.Value;
            this.eat(this.currentToken.Type);
        }
        methodCode += ')';
        var variableName = 'result' + this.variableCount;
        this.header += methodCode + ".then(function(" + variableName + "){";
        this.variableCount++;
        this.footer += '})';
        return variableName;
    };
    /*
        public SubExpr():NodeBase{
            let token = this.currentToken;
            let node = null;
    
            let newNode = null;
            if (token.Type == TokenType.Method) {
                newNode = this.Method();
            }
            else if(token.Type==TokenType.LParen)
            {
                this.eat(TokenType.LParen);
                newNode=new SubExpressionNode();
                newNode.SubExpr=this.SubExpr();
                this.eat(TokenType.RParen);
            }else if(token.Type==TokenType.RParen)
                return null;
            else
                newNode = this.Whatever();
    
            return newNode;
        }
    
        private Method():NodeBase {
            let methodNode=new MethodNode();
            methodNode.MethodToken=this.currentToken;
            this.eat(TokenType.Method);
            this.eat(TokenType.LParen);
            methodNode.SubExpr=this.SubExpr();
            this.eat(TokenType.RParen);
            return methodNode;
    
        }
    
    
    
        private Whatever():NodeBase {
            let whateverNode: WhateverNode = new WhateverNode();
    
            while (true) {
                if (this.currentToken.Type == TokenType.Method || this.currentToken == null||this.currentToken.Type==TokenType.RParen)
                    break;
    
                if(this.currentToken.Type==TokenType.LParen)
                {
                    return new BinaryNode(whateverNode,this.SubExpr());
                }
    
                whateverNode.ChildTokens.push(this.currentToken);
                this.eat(this.currentToken.Type);
            }
    
            return whateverNode;
        }*/
    Parser.prototype.CreateRootPromise = function () {
        return "new Promise(function(sfInternalResolve){\n            " + this.header + "\n                sfInternalResolve(" + this.code + ");\n            " + this.footer + "\n        });";
    };
    return Parser;
}());
exports.Parser = Parser;
//# sourceMappingURL=Parser.js.map