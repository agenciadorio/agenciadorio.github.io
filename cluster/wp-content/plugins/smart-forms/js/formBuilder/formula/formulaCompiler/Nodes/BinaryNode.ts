import {NodeBase} from "./NodeBase";

export class BinaryNode extends NodeBase{
    public LeftNode:NodeBase;
    public RightNode:NodeBase;

    constructor(leftNode:NodeBase,rightNode:NodeBase)
    {
        super();
        this.LeftNode=leftNode;
        this.RightNode=rightNode;
    }
    Visit() {
    }

}