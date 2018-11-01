/// <reference path="../typings/sfGlobalTypings.d.ts" />
declare var RedNaoPathExists: any;
declare var RedNaoEscapeHtml: any;
declare var exports: any;
declare function CreateColumn(options: any): {
    "name": any;
    label: any;
    "index": any;
    "editable": boolean;
    formatter: (cellvalue: any, cellOptions: any, rowObject: any) => any;
}[];
declare function GetObjectOrNull(rowObject: any, options: any): any;
declare function RedNaoTextOrAmountColumn(options: any): {
    "name": any;
    label: any;
    "index": any;
    "editable": boolean;
    formatter: (cellvalue: any, cellOptions: any, rowObject: any) => any;
}[];
declare function RedNaoTextInputColumn(options: any): {
    "name": any;
    label: any;
    "index": any;
    "editable": boolean;
    formatter: (cellvalue: any, cellOptions: any, rowObject: any) => any;
}[];
declare function RedNaoRecurrenceColumn(options: any): {
    "name": any;
    label: any;
    "index": any;
    "editable": boolean;
    formatter: (cellvalue: any, cellOptions: any, rowObject: any) => any;
}[];
declare function RedNaoCheckboxInputColumn(options: any): {
    "name": any;
    label: any;
    "index": any;
    "editable": boolean;
    formatter: (cellvalue: any, cellOptions: any, rowObject: any) => string;
}[];
declare function RedNaoMultipleCheckBoxesColumn(options: any): {
    "name": any;
    label: any;
    "index": any;
    "editable": boolean;
    formatter: (cellvalue: any, cellOptions: any, rowObject: any) => any;
}[];
declare function RedNaoDatePicker(options: any): {
    "name": any;
    label: any;
    "index": any;
    "editable": boolean;
    formatter: (cellvalue: any, cellOptions: any, rowObject: any) => any;
}[];
declare function RedNaoName(options: any): {
    "name": any;
    label: any;
    "index": any;
    "editable": boolean;
    formatter: (cellvalue: any, cellOptions: any, rowObject: any) => any;
}[];
declare function RedNaoPhone(options: any): {
    "name": any;
    label: any;
    "index": any;
    "editable": boolean;
    formatter: (cellvalue: any, cellOptions: any, rowObject: any) => any;
}[];
declare function RedNaoAddress(options: any): {
    "name": any;
    label: any;
    "index": any;
    "editable": boolean;
    formatter: (cellvalue: any, cellOptions: any, rowObject: any) => any;
}[];
declare function RedNaoFileUploadColumn(options: any): {
    "name": any;
    label: any;
    "index": any;
    "editable": boolean;
    formatter: (cellvalue: any, cellOptions: any, rowObject: any) => string;
}[];
