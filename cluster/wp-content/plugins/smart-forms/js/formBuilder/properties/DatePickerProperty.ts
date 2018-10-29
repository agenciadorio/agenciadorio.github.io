import {ElementPropertiesBase} from "./ElementPropertiesBase";

export class DatePickerProperty extends ElementPropertiesBase{
    private AllowRelativeDates:boolean=false;
    constructor(formelement:sfFormElementBase<any>,propertiesObject:any,propertyName:string,propertyTitle:string,additionalInformation:any)
    {
        super(formelement,propertiesObject,propertyName,propertyTitle,additionalInformation);
        this.SetTooltip(
            `<p style="margin:0;padding:0;">Permitted values:</p>
                    <p style="margin:0;padding:0;font-weight: normal;"><strong>A date:</strong> In the format of MM/DD/YYYYY</p>
                    <p style="margin:0;padding:0;font-weight: normal;"><strong>A formula:</strong> The formula must return a date</p>
                    <p style="margin:0;padding:0;font-weight: normal;"><strong>A number:</strong> Number of days after today or before for negative numbers</p>
                    <p style="margin:0;padding:0;font-weight: normal;"><strong>An string:</strong>Example: +1w +1d for one week plus one day after today. Available strings: w (week), m(month), d(day), y(year)</p>
                    
                `)
    }

    InternalGenerateHtml($fieldContainer:JQuery) {
        let $input=rnJQuery(`<input class="form-control" type="text" />`);
        $fieldContainer.append($input);
        $input.datepicker({
            beforeShow: ()=> {
                rnJQuery('#ui-datepicker-div').wrap('<div class="smartFormsSlider"></div>');
                $input.addClass('is-focused');
            },
            onClose: function() {
                rnJQuery('#ui-datepicker-div').unwrap();
                $input.removeClass('is-focused');
            },
            changeMonth:true,
            changeYear:true,
            yearRange:"-200:+200"
        });
        let currentValue=this.GetPropertyCurrentValue();
        if(currentValue!=null&&currentValue!='')
            if(this.IsRelativeDate(currentValue))
                $input.val(currentValue);
            else
                $input.datepicker('setDate',new Date(parseInt(currentValue)));

        if(this.AllowRelativeDates)
            $input.unbind('keypress');

        $input.change(()=>{
            let value=$input.val();
            if(!this.IsRelativeDate(value))
                value=$input.datepicker('getDate').getTime();

           this.Manipulator.SetValue(this.PropertiesObject, this.PropertyName, value,this.AdditionalInformation);
        });


    }

    public SetAllowRelativeDates(){
        this.AllowRelativeDates=true;
        return this;
    }

    private IsRelativeDate(value: string) {

        if(!isNaN(Number(value))&&parseInt(value)<100000000)
            return true;
        value=value.toString();
        return value.indexOf('w')>0 || value.indexOf('d')>0 || value.indexOf('y')>0;
    }
}