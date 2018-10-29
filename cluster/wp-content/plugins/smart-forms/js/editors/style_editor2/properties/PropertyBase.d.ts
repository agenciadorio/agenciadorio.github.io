export declare abstract class PropertyBase {
    fieldToEdit: sfFormElementBase<any>;
    elemeentClass: string;
    scope: string;
    label: string;
    styleName: string;
    protected $propertiesContainer: JQuery;
    protected $property: JQuery;
    private containerSize;
    private labelSize;
    private controlSize;
    constructor(fieldToEdit: sfFormElementBase<any>, elemeentClass: string, scope: string, label: string, styleName: string);
    Generate($propertiesContainer: JQuery): void;
    SetDimensions(containerSize: number, labelSize: number, controlSize: number): PropertyBase;
    protected abstract InternalGenerate(): JQuery;
    protected AppendCompleted(): void;
    protected PropertyChanged(value: string): void;
    protected GetValue(styleName?: string): string;
    protected GetId(): string;
    protected ClearValue(styleName?: string): void;
    protected SetValue(value: string, styleName?: string): void;
}
export declare class ColorPicker extends PropertyBase {
    fieldToEdit: sfFormElementBase<any>;
    elemeentClass: string;
    scope: string;
    label: string;
    styleName: string;
    $colorPicker: JQuery;
    constructor(fieldToEdit: sfFormElementBase<any>, elemeentClass: string, scope: string, label: string, styleName: string);
    protected InternalGenerate(): JQuery;
    protected AppendCompleted(): void;
}
export declare class ButtonsProperty extends PropertyBase {
    fieldToEdit: sfFormElementBase<any>;
    elemeentClass: string;
    scope: string;
    label: string;
    styleName: string;
    options: any[];
    protected $buttonToolbar: JQuery;
    constructor(fieldToEdit: sfFormElementBase<any>, elemeentClass: string, scope: string, label: string, styleName: string);
    protected InternalGenerate(): JQuery;
    protected AppendCompleted(): void;
    AddOption(label: string, value: string): ButtonsProperty;
}
export declare class SliderProperty extends PropertyBase {
    fieldToEdit: sfFormElementBase<any>;
    elemeentClass: string;
    scope: string;
    label: string;
    styleName: string;
    options: any[];
    protected $slider: JQuery;
    protected $textBox: JQuery;
    protected min: number;
    protected max: number;
    constructor(fieldToEdit: sfFormElementBase<any>, elemeentClass: string, scope: string, label: string, styleName: string);
    protected InternalGenerate(): JQuery;
    protected AppendCompleted(): void;
}
export declare class ComboProperty extends PropertyBase {
    fieldToEdit: sfFormElementBase<any>;
    elemeentClass: string;
    scope: string;
    label: string;
    styleName: string;
    options: any[];
    protected $combo: JQuery;
    constructor(fieldToEdit: sfFormElementBase<any>, elemeentClass: string, scope: string, label: string, styleName: string);
    protected InternalGenerate(): JQuery;
    protected AppendCompleted(): void;
    AddOption(label: string, value: string): ComboProperty;
}
export declare class TextDecoration extends ComboProperty {
    fieldToEdit: sfFormElementBase<any>;
    elemeentClass: string;
    scope: string;
    constructor(fieldToEdit: sfFormElementBase<any>, elemeentClass: string, scope: string);
}
export declare class Capitalization extends ComboProperty {
    fieldToEdit: sfFormElementBase<any>;
    elemeentClass: string;
    scope: string;
    constructor(fieldToEdit: sfFormElementBase<any>, elemeentClass: string, scope: string);
}
export declare class FontFamily extends ComboProperty {
    fieldToEdit: sfFormElementBase<any>;
    elemeentClass: string;
    scope: string;
    constructor(fieldToEdit: sfFormElementBase<any>, elemeentClass: string, scope: string);
    protected AppendCompleted(): any;
}
export declare class BorderProperty extends PropertyBase {
    fieldToEdit: sfFormElementBase<any>;
    elemeentClass: string;
    scope: string;
    options: any[];
    protected $container: JQuery;
    protected $buttonToolbar: JQuery;
    protected $colorPicker: JQuery;
    protected $slider: JQuery;
    protected $style: JQuery;
    protected borderLeftEnabled: boolean;
    protected borderRightEnabled: boolean;
    protected borderTopEnabled: boolean;
    protected borderBottomEnabled: boolean;
    protected color: string;
    protected style: string;
    protected width: string;
    constructor(fieldToEdit: sfFormElementBase<any>, elemeentClass: string, scope: string);
    protected InternalGenerate(): JQuery;
    protected AppendCompleted(): void;
    private CreateBorderButtons;
    private CreateColor;
    private CreateBorderStyle;
    private CreateSlider;
    private AddLabel;
    private GetDefaultValues;
    private RefreshStyles;
    AddBorder(border: string): void;
    RemoveBorder(border: string): void;
}
