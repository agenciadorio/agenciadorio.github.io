import { StyleGroupBase } from "./set/StyleSetBase";
export declare class StyleEditor {
    $styleEditorContainer: JQuery;
    fieldToEdit: sfFormElementBase<any>;
    targetScope: string;
    styleGroups: StyleGroupBase[];
    constructor();
    RefreshEditor(): void;
    private GetSelectedField;
    private CreateSelectionCombo;
    private CreateStyleProperties;
    private CreatePropertiesForField;
    private ScopeChanged;
}
