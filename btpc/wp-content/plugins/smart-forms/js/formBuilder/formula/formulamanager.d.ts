declare class RedNaoFormulaManager {
    Formulas: RedNaoFormula[];
    Data: any;
    constructor();
    private CalculateFormula(instance, formula, values);
    PropertyChanged(data: any): void;
    ExecuteAfterComplete(actionData: any, type: 'hide' | 'show'): void;
    SetFormulaValue(field: any, fieldName: any, data: any): void;
    UpdateFormulaFieldsIfNeeded(fieldName: any): void;
    RefreshAllFormulas: () => void;
    AddFormula(formElement: any, formula: any): void;
}
