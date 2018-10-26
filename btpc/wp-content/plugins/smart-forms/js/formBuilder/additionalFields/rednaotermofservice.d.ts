declare namespace SmartFormsFields {
    class rednaotermofservice extends sfFormElementBase<any> {
        GetValueString(): any;
        SetData(data: any): void;
        IsValid(): boolean;
        GenerationCompleted($element: any): void;
        GenerateInlineElement(): string;
        CreateProperties(): void;
    }
}
