import { ElementPropertiesBase } from "./ElementPropertiesBase";
export declare class ArrayProperty extends ElementPropertiesBase {
    ItemsList: JQuery;
    csvToArray(text: any): string[][];
    GetFieldTemplate(): JQuery;
    InternalGenerateHtml($fieldContainer: any): void;
    GetItemList(items: any): string;
    DeleteItem(jQueryElement: any): void;
    CloneItem(jQueryElement: any): void;
    AddItem(item: {
        url: string;
        label: string;
        value: string;
    }, firstElement: boolean): void;
    CreateListRow(isFirst: any, item: {
        url: string;
        label: string;
        value: string;
    }): string;
    GetSelector(item: any): string;
    UpdateProperty(): void;
    GetRowData(jQueryRow: any): {
        label: any;
        value: any;
        sel: string;
        url: string;
    };
}
