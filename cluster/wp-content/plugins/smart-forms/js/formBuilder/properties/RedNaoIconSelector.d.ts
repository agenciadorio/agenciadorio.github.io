export declare class RedNaoIconSelector {
    $Dialog: JQuery;
    $Select: JQuery;
    CallBack: any;
    private static _current;
    constructor();
    static readonly Current: RedNaoIconSelector;
    Show(type: any, defaultValue: any, callBack: any): void;
    GetIconOptions(): string;
    InitializeDialog(): void;
    FireAddIconCallBack(orientation: any): void;
}
