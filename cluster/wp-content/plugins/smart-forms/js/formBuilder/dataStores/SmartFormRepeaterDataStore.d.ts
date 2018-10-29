import { SmartFormBasicDataStore } from "./SmartFormBasicDataStore";
import rednaorepeater = SmartFormsFields.rednaorepeater;
export declare class SmartFormRepeaterDataStore extends SmartFormBasicDataStore {
    instance: rednaorepeater;
    defaultValue: any;
    rows: any[];
    label: string;
    numericalValue: number;
    OriginalValues: any;
    value: string;
    constructor(instance: rednaorepeater);
    toString(): void;
    Clone(): any;
    GetTotal(fieldId: string): number;
    GetField(rowIndex: any, fieldId: string): any;
}
