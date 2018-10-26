declare class SfHandlerConditionGenerator extends SfConditionalStepBase<any> {
    private ConditionDesigner;
    constructor(translations: any, formBuilder: any, stepConfiguration: any);
    InitializeScreen(container: any): void;
    Exit(): void;
    Commit(): boolean;
}
