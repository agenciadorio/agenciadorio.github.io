declare class SfFormulaAutoComplete {
    process(editor: any, token: any): ListGenerator;
    private GenerateList(editor);
    private IsFieldNerby(cursor, line);
    private IsMathNerby(cursor, line);
    private IsRemoteNerby(cursor, line);
    private GetFieldById(id);
    private GetIntellisenceforField(editor, cursor, line);
}
declare class ListGenerator {
    data: any;
    constructor(editor: any);
    AddItem(label: string, label2: string, value: string, description: string): ListGenerator;
    private StylizeText(colorDictionary, label);
}
interface CodeMirrorCursor {
    ch: number;
    line: number;
}
declare let SFAutoCompleteFieldDictionary: any;
