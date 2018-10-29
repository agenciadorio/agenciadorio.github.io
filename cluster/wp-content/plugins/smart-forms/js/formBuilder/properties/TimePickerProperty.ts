import {ElementPropertiesBase} from "./ElementPropertiesBase";

export class TimePickerProperty extends ElementPropertiesBase{

    InternalGenerateHtml($fieldContainer:JQuery) {
        let currentValue=this.GetPropertyCurrentValue();
        let date=null;
        date = new Date(new Date().setHours(0, 0, 0, currentValue));

        let $input=rnJQuery(`<input class="form-control" type="text" />`);
        let options:any={
            minuteStep:1
        };


        $fieldContainer.append($input);
        $input.timepicker(options);
        $input.timepicker('setTime', date);
        $input.on('changeTime.timepicker',(e)=>{
            this.Manipulator.SetValue(this.PropertiesObject, this.PropertyName, this.TimeToMilliseconds(e),this.AdditionalInformation);
            this.RefreshElement();
        });



    }

    private TimeToMilliseconds(e){
        let milliseconds=0;
        milliseconds+=(e.time.hours+(e.time.meridian=='PM'?12:0))*60*60*1000;
        milliseconds+=e.time.minutes*60*1000;
        milliseconds+=e.time.seconds*1000;
        return milliseconds;
    }


}