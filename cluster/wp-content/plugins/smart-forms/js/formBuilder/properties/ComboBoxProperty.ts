import {ElementPropertiesBase} from "./ElementPropertiesBase";

export class ComboBoxProperty extends ElementPropertiesBase {


    public InternalGenerateHtml($fieldContainer:JQuery) {
        let value = this.GetPropertyCurrentValue().trim();
        let selectText = '<select class="form-control" id="' + this.PropertyId + '">';
        for (let i = 0; i < this.AdditionalInformation.Values.length; i++) {
            let selected = "";
            if (this.AdditionalInformation.Values[i].value == value)
                selected = 'selected="selected"';

            selectText += '<option value="' + RedNaoEscapeHtml(this.AdditionalInformation.Values[i].value) + '" ' + selected + '>' + RedNaoEscapeHtml(this.AdditionalInformation.Values[i].label) + '</option>';
        }
        selectText += '</select>';

        let $select=rnJQuery(selectText);
        $fieldContainer.append($select);




        $select.change(()=> {
            this.Manipulator.SetValue(this.PropertiesObject, this.PropertyName, $select.val(), this.AdditionalInformation);
            this.RefreshElement();

        });



    }

    public AddOption(label:string, value:string){
        if(this.AdditionalInformation.Values==null)
            this.AdditionalInformation.Values=[];

        this.AdditionalInformation.Values.push({label:label,value:value});
        return this;
    }


}