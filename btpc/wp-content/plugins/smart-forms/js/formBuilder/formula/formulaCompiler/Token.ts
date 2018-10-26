import {TokenType} from "./Lexer";

export class Token{
    constructor(public Type:TokenType,public Value:string)
    {

    }
}