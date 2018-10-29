import { NodeBase } from "./NodeBase";
import { Token } from "../Token";
export declare class MethodNode extends NodeBase {
    MethodToken: Token;
    SubExpr: NodeBase;
    Visit(): void;
}
