import {Parser} from "./Parser";

export class FormulaCompiler{
    private parser:Parser;

    constructor(public stringToProcess:string){
        this.parser=new Parser(this.stringToProcess);
    }

    public Compile():string{
        return this.parser.Parse();
    }


}

(window as any).FormulaCompiler=FormulaCompiler;