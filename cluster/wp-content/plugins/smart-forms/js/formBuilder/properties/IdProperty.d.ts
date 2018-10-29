import { ElementPropertiesBase } from "./ElementPropertiesBase";
export declare class IdProperty extends ElementPropertiesBase {
    PreviousId: string;
    constructor(formelement: any, propertiesObject: any);
    InternalGenerateHtml($fieldContainer: JQuery): void;
}
