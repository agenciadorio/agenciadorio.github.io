"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var Token_1 = require("./Token");
var Lexer = /** @class */ (function () {
    function Lexer(stringToProcess) {
        this.stringToProcess = stringToProcess;
        this.stringBuffer = '';
        this.currentIndex = -1;
    }
    Lexer.prototype.GetNextToken = function () {
        this.currentIndex++;
        if (this.stringToProcess.length <= this.currentIndex)
            return this.AnalizeToken();
        var currentChar = this.stringToProcess[this.currentIndex];
        if (this.IsWhiteSpace(currentChar)) {
            if (this.stringBuffer.length > 0) {
                this.currentIndex--;
                return this.AnalizeToken();
            }
            else {
                this.stringBuffer += ' ';
                return this.AnalizeToken();
            }
        }
        else if (this.IsQuote(currentChar)) {
            var token = this.CreateQuotedString();
            this.stringBuffer = '';
            return token;
        }
        else if (this.IsParentheses(currentChar)) {
            if (this.stringBuffer.length > 0) {
                this.currentIndex--;
                return this.AnalizeToken();
            }
            else {
                if (currentChar == '(')
                    return new Token_1.Token(TokenType.LParen, '(');
                else
                    return new Token_1.Token(TokenType.RParen, ')');
            }
        }
        else if (this.IsSymbol(currentChar)) {
            if (this.stringBuffer.length > 0) {
                this.currentIndex--;
                return this.AnalizeToken();
            }
            else
                return new Token_1.Token(TokenType.Symbol, currentChar);
        }
        else {
            this.stringBuffer += currentChar;
            return this.GetNextToken();
        }
    };
    Lexer.prototype.AnalizeToken = function () {
        if (this.stringBuffer.length == 0)
            return null;
        var token = { Type: TokenType.Whatever, Value: this.stringBuffer };
        if (this.stringBuffer == 'Remote.Get' || this.stringBuffer == 'Remote.Post')
            token.Type = TokenType.Method;
        this.stringBuffer = "";
        return token;
    };
    Lexer.prototype.IsWhiteSpace = function (currentChar) {
        return currentChar == ' ' || currentChar == '\r' || currentChar == '\t' || currentChar == '\n';
    };
    Lexer.prototype.IsQuote = function (currentChar) {
        return currentChar == "'" || currentChar == "\"";
    };
    Lexer.prototype.CreateQuotedString = function () {
        var quote = this.stringToProcess[this.currentIndex];
        this.stringBuffer += quote;
        this.currentIndex++;
        while (this.currentIndex < this.stringToProcess.length && (this.stringToProcess[this.currentIndex] != quote || this.stringToProcess[this.currentIndex - 1] == '\\')) {
            this.stringBuffer += this.stringToProcess[this.currentIndex];
            this.currentIndex++;
        }
        if (this.stringToProcess[this.currentIndex] == quote) {
            this.stringBuffer += quote;
        }
        return new Token_1.Token(TokenType.String, this.stringBuffer);
    };
    Lexer.prototype.IsParentheses = function (currentChar) {
        return currentChar == '(' || currentChar == ")";
    };
    Lexer.prototype.IsSymbol = function (currentChar) {
        var symbolList = [',', '&', '|', ';', '+', '-', '/', '*'];
        return symbolList.indexOf(currentChar) >= 0;
    };
    return Lexer;
}());
exports.Lexer = Lexer;
var TokenType;
(function (TokenType) {
    TokenType[TokenType["Method"] = 1] = "Method";
    TokenType[TokenType["Whatever"] = 2] = "Whatever";
    TokenType[TokenType["Comma"] = 3] = "Comma";
    TokenType[TokenType["LParen"] = 4] = "LParen";
    TokenType[TokenType["RParen"] = 5] = "RParen";
    TokenType[TokenType["String"] = 6] = "String";
    TokenType[TokenType["Symbol"] = 7] = "Symbol";
})(TokenType = exports.TokenType || (exports.TokenType = {}));
//# sourceMappingURL=Lexer.js.map