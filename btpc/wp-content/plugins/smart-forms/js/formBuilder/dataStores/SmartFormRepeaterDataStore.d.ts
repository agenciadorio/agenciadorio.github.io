import { SmartFormBasicDataStore } from "./SmartFormBasicDataStore";
export declare class SmartFormRepeaterDataStore extends SmartFormBasicDataStore {
    defaultValue: any;
    rows: any[];
    label: string;
    numericalValue: number;
    OriginalValues: any;
    value: string;
    constructor();
    toString(): void;
    Clone(): any;
    GetTotal(fieldId: string): number;
}
