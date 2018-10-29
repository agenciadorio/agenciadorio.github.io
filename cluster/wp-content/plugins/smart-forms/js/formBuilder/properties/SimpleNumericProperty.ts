import {ElementPropertiesBase} from "./ElementPropertiesBase";

export class SimpleNumericProperty extends ElementPropertiesBase {

    constructor(formelement, propertiesObject, propertyName, propertyTitle, additionalInformation) {
        super(formelement, propertiesObject, propertyName, propertyTitle, additionalInformation);
        if (typeof additionalInformation.Placeholder == 'undefined')
            additionalInformation.Placeholder = 'Default';

    }


    public InternalGenerateHtml($fieldContainer:JQuery) {
        let $input=rnJQuery(`<input  style="width: 206px;" class="rednao-input-large form-control" data-type="input" type="number" name="name" id="${this.PropertyId}" value="${RedNaoEscapeHtml(this.GetPropertyCurrentValue())}" placeholder="${this.AdditionalInformation.Placeholder}"/>`);
        $fieldContainer.append($input);
        $input.ForceNumericOnly();
        $input.on('input', ()=> {
            let value:any = parseFloat(rnJQuery("#" + this.PropertyId).val());
            if (isNaN(value))
                value = '';
            else
                value = value.toString();
            this.Manipulator.SetValue(this.PropertiesObject, this.PropertyName, value, this.AdditionalInformation);
            this.RefreshElement();

        });
    }
}