import { StyleGroupBase } from "./set/StyleSetBase";
export declare class StyleEditor {
    $styleEditorContainer: JQuery;
    fieldToEdit: sfFormElementBase<any>;
    targetScope: string;
    styleGroups: StyleGroupBase[];
    constructor();
    RefreshEditor(): void;
    private GetSelectedField();
    private CreateSelectionCombo(field);
    private CreateStyleProperties(fieldToEdit);
    private CreatePropertiesForField(fieldToEdit, $container);
    private ScopeChanged(scope);
}
