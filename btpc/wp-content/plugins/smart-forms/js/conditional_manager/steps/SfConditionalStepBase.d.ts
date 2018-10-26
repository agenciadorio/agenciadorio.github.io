declare abstract class SfConditionalStepBase<T extends SfConditionalStepOptions> {
    protected FormBuilder: any;
    protected Translations: any;
    protected StepConfiguration: StepConfiguration<T>;
    protected Width: number;
    constructor(translations: any, formBuilder: any, stepConfiguration: any);
    abstract InitializeScreen(container: any): any;
    abstract Exit(): any;
    abstract Commit(): any;
}
interface StepConfiguration<T extends SfConditionalStepOptions> {
    IsNew: boolean;
    Label: string;
    Id: number;
    Options: T;
}
interface SfConditionalStepOptions {
}
