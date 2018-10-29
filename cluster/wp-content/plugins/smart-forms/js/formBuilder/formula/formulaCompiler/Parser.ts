import {Lexer, TokenType} from "./Lexer";
import {Token} from "./Token";
import {NodeBase} from "./Nodes/NodeBase";
import {BinaryNode} from "./Nodes/BinaryNode";
import {WhateverNode} from "./Nodes/WhateverNode";
import {MethodNode} from "./Nodes/MethodNode";
import {SubExpressionNode} from "./Nodes/SubExpressionNode";
/*
expr:(Method|String|Whatever)*
method:Remote.Get lparen (expr |,expr) rparent;
String: "|` cadena "|
whatever:todo lo que not tenga espacios
 */
export class Parser{
    private lexer:Lexer;
    private currentToken:Token;
    private code:string='';
    private header:string='';
    private footer:string='';
    private topCode:string;
    private variableCount:number=0;
    constructor(public stringToProcess:string){
        this.lexer=new Lexer(this.stringToProcess);
        this.currentToken=this.lexer.GetNextToken();
    }

    public eat(tokenType:TokenType)
    {
        if(this.currentToken.Type!=tokenType)
            throw ("Invalid Formula");
        else
            this.currentToken=this.lexer.GetNextToken();
    }

    Parse() {
        return this.Expr();
    }

    public Expr(): string {
        while(this.currentToken!=null)
        {
            if(this.currentToken.Type!=TokenType.Method)
            {
                this.code+=this.currentToken.Value;
            }else
                this.code+=this.Method();
            this.eat(this.currentToken.Type);
        }
        return this.CreateRootPromise();

    }

    public Method(){
        let methodCode=this.currentToken.Value;
        this.eat(TokenType.Method);
        let parenthesesCount=-1;
        while(this.currentToken!=null&&(this.currentToken.Type!=TokenType.RParen||parenthesesCount>0))
        {
            if((this.currentToken as any).Type==TokenType.RParen)
                parenthesesCount--;
            if(this.currentToken.Type==TokenType.LParen)
                parenthesesCount++;
           if(this.currentToken.Type==TokenType.Method)
               methodCode+=this.Method();
           else
               methodCode+=this.currentToken.Value;

           this.eat(this.currentToken.Type);
        }
        methodCode+=')';


        let variableName='result'+this.variableCount;
        this.header+=`${methodCode}.then(function(${variableName}){`;
        this.variableCount++;
        this.footer+='})';
        return variableName;
    }


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
    private CreateRootPromise() {
        return `new Promise(function(sfInternalResolve){
            ${this.header}
                sfInternalResolve(${this.code});
            ${this.footer}
        });`
    }
}