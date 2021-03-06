"use strict";
abstract class SfConditionalHandlerBase {

    public PreviousActionWas: number;
    public Options: any;
    public IsNew: boolean;
    public Id: number;
    public static ConditionId: number = 0;
    public Condition:any;
    public Form:any;
    public _remote:any=null;
    public Container:JQuery;
    constructor(options) {
        this.PreviousActionWas = -1;
        if (options == null) {
            this.Options = {};
            SfConditionalHandlerBase.ConditionId++;
            this.Options.Id = SfConditionalHandlerBase.ConditionId;
            this.IsNew = true;
        } else
            this.Options = options;

        this.Id = this.Options.Id;
    }


    public abstract GetConditionalSteps();
    public abstract Initialize(form, data);
    public abstract ExecuteTrueAction();
    public abstract ExecuteFalseAction(form)
    public abstract ExecutingPromise();

    public GetOptionsToSave() {
        this.Options.Label = this.Options.GeneralInfo.Name;
        return this.Options;

    };

    public SubscribeCondition(condition, initialData) {
        let self = this;
        //this.ConditionFunction=new Function('formData','return '+condition.CompiledCondition);
        let fieldsInCondition = [];
        if(condition.Mode=='Formula')
        {
            for(let field of condition.Formula.FieldsUsed)
                fieldsInCondition.push(field);
        }else
        for (let i = 0; i < condition.Conditions.length; i++) {
            fieldsInCondition.push(condition.Conditions[i].Field);
            if(typeof condition.Conditions[i].Formula!='undefined'&&condition.Conditions[i].Formula.RowMode=="Formula")
            {
                for(let fieldInFormula of condition.Conditions[i].Formula.Formula.FieldsUsed)
                    fieldsInCondition.push(fieldInFormula);
            }
        }

        RedNaoEventManager.Subscribe('ProcessConditionsAfterValueChanged', function (data) {
            if (fieldsInCondition.indexOf(data.FieldName) > -1) {
                let action = self.ProcessCondition(data.Data);
                if (action != null)
                    data.Actions.push(action);
            }
        });


    };

    public GetRemote(){
        if(this._remote==null)
            this._remote=new SmartFormsRemote();
        return this._remote;
    }

    public ProcessCondition(data):Promise<{ActionType:string,Execute:()=>void}> {

        let result = RedNaoEventManager.Publish('CalculateCondition', {Condition: this.Condition, Values: data,Instance:this});
        if(result instanceof Promise) {
            this.ExecutingPromise();
            return result.then((result) => this.ProcessResult(result));
        }
        else
            return new Promise((resolve)=>{resolve(this.ProcessResult(result))});

    };


    private ProcessResult(result: any) {
        if (result) //this.ConditionFunction(data))
        {
            if (this.PreviousActionWas != 1) {
                return {
                    ActionType: 'show',
                    Execute:  ()=> {
                        this.PreviousActionWas = 1;
                        this.ExecuteTrueAction()
                    }
                };
            }
        }
        else if (this.PreviousActionWas != 0) {
            return {
                ActionType: 'hide',
                Execute:  ()=> {
                    this.PreviousActionWas = 0;
                    this.ExecuteFalseAction(null);
                }
            }
        }
        return null;
    }
}

(window as any).SfConditionalHandlerBase=SfConditionalHandlerBase;