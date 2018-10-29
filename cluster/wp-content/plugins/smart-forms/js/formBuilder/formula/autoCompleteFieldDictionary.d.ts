export declare class SFAutoCompleteFieldDictionary {
    private static dictionary;
    static InitializeDataStore(): void;
    static GetDictionary(datastore: any): DataStoreDictionaryItem;
    private static AddDictionaryItem;
}
export interface DataStoreDictionaryItem {
    type: any;
    availableMethods: DataStoreMethod[];
}
export interface DataStoreMethod {
    label: string;
    label2: string;
    value: string;
    description: string;
}
