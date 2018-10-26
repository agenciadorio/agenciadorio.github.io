export declare class Lexer {
    stringToProcess: string;
    currentIndex: number;
    stringBuffer: string;
    constructor(stringToProcess: string);
    GetNextToken(): any;
    private AnalizeToken();
    private IsWhiteSpace(currentChar);
    private IsQuote(currentChar);
    private CreateQuotedString();
    private IsParentheses(currentChar);
    private IsSymbol(currentChar);
}
export declare enum TokenType {
    Method = 1,
    Whatever = 2,
    Comma = 3,
    LParen = 4,
    RParen = 5,
    String = 6,
    Symbol = 7,
}
