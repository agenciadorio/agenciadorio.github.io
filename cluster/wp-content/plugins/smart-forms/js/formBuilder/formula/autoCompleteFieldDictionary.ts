export class SFAutoCompleteFieldDictionary{

    private static dictionary:DataStoreDictionaryItem[]=[];
    public static InitializeDataStore(){
       SFAutoCompleteFieldDictionary.AddDictionaryItem((window as any).SmartFormDateDataStore,[
           {label:'AddDays:',label2:'AddDays($$DaysToAdd$$)',value:'AddDays(daysToAdd)',description:'Get the field date and add the specified number of days, you can use negative numbers to subtract days'},
           {label:'AddMonths:',label2:'AddMonths($$MonthsToAdd$$)',value:'AddMonths(monthsToAdd)',description:'Get the field date and add the specified number of months, you can use negative numbers to subtract months'},
           {label:'AddYears:',label2:'AddYears($$YearsToAdd$$)',value:'AddYears(yearsToAdd)',description:'Get the field date and add the specified number of years, you can use negative numbers to subtract years'}
           ]);

        SFAutoCompleteFieldDictionary.AddDictionaryItem((window as any).SmartFormRepeaterDataStore,[
            {label:'GetTotal:',label2:'GetTotal($$FieldId$$)',value:'GetTotal(\'FieldId\')',description:'Calculate the total of the given field'},
            {label:'GetField:',label2:'GetField($$Row number or current$$,$$FieldId$$)',value:'GetField(current,\'FieldId\')',description:'Get an specific field from a repeater you can select an specific row or the current one if you are using it in a field of the repeater)'}
        ])


    }

    public static GetDictionary(datastore:any)
    {
        return SFAutoCompleteFieldDictionary.dictionary.find(x=>datastore instanceof x.type);
    }

    private static AddDictionaryItem(type:any,methods:DataStoreMethod[])
    {
        let dataStoreItem:DataStoreDictionaryItem={
            type:type,
            availableMethods:methods
        };
        SFAutoCompleteFieldDictionary.dictionary.push(dataStoreItem);
    }
}

(window as any).SFAutoCompleteFieldDictionary=SFAutoCompleteFieldDictionary;

export interface DataStoreDictionaryItem{
    type:any;
    availableMethods:DataStoreMethod[];
}

export interface DataStoreMethod{
    label:string,
    label2:string,
    value:string,
    description:string;

}