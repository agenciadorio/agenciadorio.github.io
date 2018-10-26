
import {Token} from "./Token";

export class Lexer{
    public currentIndex:number;
    public stringBuffer:string='';
    constructor(public stringToProcess:string)
    {
        this.currentIndex=-1;
    }
    public GetNextToken(){
        this.currentIndex++;
        if(this.stringToProcess.length<=this.currentIndex)
            return this.AnalizeToken();

        let currentChar=this.stringToProcess[this.currentIndex];


        if(this.IsWhiteSpace(currentChar))
        {
            if(this.stringBuffer.length>0) {
                this.currentIndex--;
                return this.AnalizeToken();
            }
            else {
                this.stringBuffer+=' ';
                return this.AnalizeToken();
            }
        }else if(this.IsQuote(currentChar))
        {
            let token= this.CreateQuotedString();
            this.stringBuffer='';
            return token;
        }else if(this.IsParentheses(currentChar))
        {
            if(this.stringBuffer.length>0) {
                this.currentIndex--;
                return this.AnalizeToken();
            }
            else
            {
                if(currentChar=='(')
                    return new Token(TokenType.LParen,'(');
                else
                    return new Token(TokenType.RParen,')');
            }

        }else if(this.IsSymbol(currentChar))
        {
            if(this.stringBuffer.length>0) {
                this.currentIndex--;
                return this.AnalizeToken();
            }
            else
                return new Token(TokenType.Symbol,currentChar);
        }
        else{
            this.stringBuffer+=currentChar;
            return this.GetNextToken();
        }


    }

    private AnalizeToken():Token {
        if(this.stringBuffer.length==0)
            return null;

        let token:Token={Type:TokenType.Whatever,Value:this.stringBuffer};
        if(this.stringBuffer=='Remote.Get'||this.stringBuffer=='Remote.Post')
            token.Type=TokenType.Method;
        this.stringBuffer="";
        return token;
    }

    private IsWhiteSpace(currentChar: string) {
        return currentChar==' '||currentChar=='\r'||currentChar=='\t'||currentChar=='\n';
    }

    private IsQuote(currentChar: string) {
        return currentChar=="'"||currentChar=="\"";
    }

    private CreateQuotedString() {
        let quote=this.stringToProcess[this.currentIndex];
        this.stringBuffer+=quote;
        this.currentIndex++;

        while(this.currentIndex<this.stringToProcess.length&&(this.stringToProcess[this.currentIndex]!=quote||this.stringToProcess[this.currentIndex-1]=='\\'))
        {
            this.stringBuffer+=this.stringToProcess[this.currentIndex];
            this.currentIndex++;
        }

        if(this.stringToProcess[this.currentIndex]==quote)
        {
            this.stringBuffer+=quote;
        }

        return new Token(TokenType.String,this.stringBuffer);


    }

    private IsParentheses(currentChar: string) {
        return currentChar=='('||currentChar==")";
    }

    private IsSymbol(currentChar: string) {
        let symbolList=[',','&','|',';','+','-','/','*'];
        return symbolList.indexOf(currentChar)>=0;
    }
}



export enum TokenType{
    Method=1,
    Whatever=2,
    Comma=3,
    LParen=4,
    RParen=5,
    String=6,
    Symbol=7
}