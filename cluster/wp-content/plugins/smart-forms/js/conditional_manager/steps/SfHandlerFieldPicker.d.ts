declare class SfHandlerFieldPicker extends SfConditionalStepBase<any> {
    private Select;
    constructor(translations: any, formBuilder: any, stepConfiguration: any);
    InitializeScreen(container: any): void;
    Exit(): void;
    Commit(): boolean;
    FormElementClicked(elementClickedJQuery: any): void;
    SelectChanged(): void;
}
