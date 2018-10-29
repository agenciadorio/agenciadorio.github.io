"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var SFAutoCompleteFieldDictionary = /** @class */ (function () {
    function SFAutoCompleteFieldDictionary() {
    }
    SFAutoCompleteFieldDictionary.InitializeDataStore = function () {
        SFAutoCompleteFieldDictionary.AddDictionaryItem(window.SmartFormDateDataStore, [
            { label: 'AddDays:', label2: 'AddDays($$DaysToAdd$$)', value: 'AddDays(daysToAdd)', description: 'Get the field date and add the specified number of days, you can use negative numbers to subtract days' },
            { label: 'AddMonths:', label2: 'AddMonths($$MonthsToAdd$$)', value: 'AddMonths(monthsToAdd)', description: 'Get the field date and add the specified number of months, you can use negative numbers to subtract months' },
            { label: 'AddYears:', label2: 'AddYears($$YearsToAdd$$)', value: 'AddYears(yearsToAdd)', description: 'Get the field date and add the specified number of years, you can use negative numbers to subtract years' }
        ]);
        SFAutoCompleteFieldDictionary.AddDictionaryItem(window.SmartFormRepeaterDataStore, [
            { label: 'GetTotal:', label2: 'GetTotal($$FieldId$$)', value: 'GetTotal(\'FieldId\')', description: 'Calculate the total of the given field' },
            { label: 'GetField:', label2: 'GetField($$Row number or current$$,$$FieldId$$)', value: 'GetField(current,\'FieldId\')', description: 'Get an specific field from a repeater you can select an specific row or the current one if you are using it in a field of the repeater)' }
        ]);
    };
    SFAutoCompleteFieldDictionary.GetDictionary = function (datastore) {
        return SFAutoCompleteFieldDictionary.dictionary.find(function (x) { return datastore instanceof x.type; });
    };
    SFAutoCompleteFieldDictionary.AddDictionaryItem = function (type, methods) {
        var dataStoreItem = {
            type: type,
            availableMethods: methods
        };
        SFAutoCompleteFieldDictionary.dictionary.push(dataStoreItem);
    };
    SFAutoCompleteFieldDictionary.dictionary = [];
    return SFAutoCompleteFieldDictionary;
}());
exports.SFAutoCompleteFieldDictionary = SFAutoCompleteFieldDictionary;
window.SFAutoCompleteFieldDictionary = SFAutoCompleteFieldDictionary;
//# sourceMappingURL=autoCompleteFieldDictionary.js.map