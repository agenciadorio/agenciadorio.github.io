import {SmartFormBasicDataStore} from "./SmartFormBasicDataStore";

export class SmartFormRepeaterDataStore extends SmartFormBasicDataStore {
    public defaultValue: any;
    public rows:any[];
    public label:string;
    public numericalValue:number;
    public OriginalValues:any;
    public value:string;
    constructor() {
        super('value');
        this.rows=[];
    }


    public toString() {
        alert('Sorry a repeater field can not be used in formulas like this');
    }

    public Clone(){
        return  (Object as any).assign( Object.create( Object.getPrototypeOf(this)), this);
    }

    public GetTotal(fieldId:string)
    {
        let data=RedNaoFormulaManagerVar.Data;
        let total=0;
        for(let i=0;i<this.value.length;i++)
        {
            if(typeof data[fieldId+'_row_'+i]!='undefined')
                total+=data[fieldId+'_row_'+i].toString();
        }
        return total;
    }

}



declare let RedNaoFormulaManagerVar:any;
