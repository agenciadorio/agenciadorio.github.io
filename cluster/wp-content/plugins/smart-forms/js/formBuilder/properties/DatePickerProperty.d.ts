import { ElementPropertiesBase } from "./ElementPropertiesBase";
export declare class DatePickerProperty extends ElementPropertiesBase {
    private AllowRelativeDates;
    constructor(formelement: sfFormElementBase<any>, propertiesObject: any, propertyName: string, propertyTitle: string, additionalInformation: any);
    InternalGenerateHtml($fieldContainer: JQuery): void;
    SetAllowRelativeDates(): this;
    private IsRelativeDate;
}
