export declare abstract class ElementPropertiesBase {
    FormElement: sfFormElementBase<any>;
    Manipulator: any;
    AdditionalInformation: any;
    PropertiesObject: any;
    PropertyName: string;
    PropertyTitle: string;
    PropertyId: string;
    $PropertiesContainer: JQuery;
    tooltip: {
        Text: string;
    };
    IconOptions: any;
    protected enableFormula: boolean;
    constructor(formelement: sfFormElementBase<any>, propertiesObject: any, propertyName: string, propertyTitle: string, additionalInformation: any);
    SetTooltip(text: string): this;
    FormulaExists(formElement: any, propertyName: any): boolean;
    SetEnableFormula(): this;
    CreateProperty(jQueryObject: JQuery): void;
    abstract InternalGenerateHtml($fieldContainer: JQuery): any;
    GeneratePropertyContainer(): void;
    GetFieldTemplate(): JQuery;
    GenerateHtml(): void;
    RefreshProperty(): void;
    GetPropertyCurrentValue(): any;
    UpdateProperty(): void;
    RefreshElement(): void;
    private AppendFormulaIcon;
    private AddToolTip;
    private AddIconSelector;
    IconSelected(itemClass: any, orientation: any, $addIconButton: any): void;
}
