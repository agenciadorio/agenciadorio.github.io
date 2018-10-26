import { TokenType } from "./Lexer";
export declare class Token {
    Type: TokenType;
    Value: string;
    constructor(Type: TokenType, Value: string);
}
