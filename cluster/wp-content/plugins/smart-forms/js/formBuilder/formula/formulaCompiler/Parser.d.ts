import { TokenType } from "./Lexer";
export declare class Parser {
    stringToProcess: string;
    private lexer;
    private currentToken;
    private code;
    private header;
    private footer;
    private topCode;
    private variableCount;
    constructor(stringToProcess: string);
    eat(tokenType: TokenType): void;
    Parse(): string;
    Expr(): string;
    Method(): string;
    private CreateRootPromise;
}
