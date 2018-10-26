import {NodeBase} from "./NodeBase";
import {Token} from "../Token";

export class MethodNode extends NodeBase{
    public MethodToken:Token;
    public SubExpr:NodeBase;
    public Visit() {
        throw new Error("Method not implemented.");
    }

}