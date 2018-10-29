import { SmartFormBasicDataStore } from "./SmartFormBasicDataStore";
export declare class SmartFormDateDataStore extends SmartFormBasicDataStore {
    GetTime(): number;
    AddDays(daysToAdd: number): any;
    AddMonths(monthsToAdd: number): any;
    AddYears(yearsToAdd: number): any;
    GetDayOfWeek(): number;
    private UpdateDate;
}
