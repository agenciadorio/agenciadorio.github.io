declare class SFConditionDesigner {
    private FormElements;
    Options: any;
    private Table;
    private $Formula;
    Conditions: any[];
    AllowJavascript: boolean;
    Mode: ConditionDesignerMode;
    $Designer: JQuery;
    constructor(FormElements: sfFormElementBase<FieldOptions>[], Options: any);
    SetAllowJavascript(): this;
    GetDesigner(): JQuery;
    FillDefaultValues(): void;
    CreateConditionalRow(formulaOptions: ConditionFormulaOptions): JQuery;
    FieldSelected(row: any, selectedField: any, condition: any): void;
    GetFieldItems(): string;
    CompileCondition(conditions: any): string;
    IsValid(): boolean;
    GetRowsData(): any[];
    GetData(): {
        Conditions: any[];
        CompiledCondition: string;
    };
    private AddFormulaButton($row);
    SetRowMode($row: JQuery, mode: ConditionRowMode): void;
    private UpdateValueFieldStatus($row);
    private UpdatePlaceholderWithFormula($row);
    private GetFriendlyFormula(formula);
    private SetMode(mode);
    private SwitchContainer($containerToShow, $containerToHide);
    private OpenFormulaEditor();
    private RefreshFormulaText();
}
declare let RedNaoFormulaWindowVar: any;
interface ConditionFormulaOptions {
    RowMode: ConditionRowMode;
    Formula?: FormulaData;
}
declare type ConditionDesignerMode = 'Builder' | 'Formula';
declare type ConditionRowMode = 'Formula' | 'FixedValue';
