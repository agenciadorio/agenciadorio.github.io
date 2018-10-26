import {NodeBase} from "./NodeBase";
import {Token} from "../Token";

export class WhateverNode extends NodeBase{
    public ChildTokens:Token[]=[];
    public Visit() {
        throw new Error("Method not implemented.");
    }

}