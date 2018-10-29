import { NodeBase } from "./NodeBase";
export declare class BinaryNode extends NodeBase {
    LeftNode: NodeBase;
    RightNode: NodeBase;
    constructor(leftNode: NodeBase, rightNode: NodeBase);
    Visit(): void;
}
