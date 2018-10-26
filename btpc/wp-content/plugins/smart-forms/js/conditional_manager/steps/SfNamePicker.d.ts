declare class SfNamePicker extends SfConditionalStepBase<any> {
    protected Title: JQuery;
    constructor(translations: any, formBuilder: any, stepConfiguration: any);
    InitializeScreen(container: any): void;
    Exit(): void;
    Commit(): boolean;
}
