import {ElementPropertiesBase} from "./ElementPropertiesBase";
import {RedNaoIconSelector} from "./RedNaoIconSelector";

export class SimpleTextProperty extends ElementPropertiesBase {

    constructor(formelement, propertiesObject, propertyName, propertyTitle, additionalInformation) {
        super(formelement, propertiesObject, propertyName, propertyTitle, additionalInformation);

        if (typeof additionalInformation.Placeholder == 'undefined')
            additionalInformation.Placeholder = 'Default';

        if (typeof additionalInformation.Width == 'undefined')
            additionalInformation.Width = '100%';


    }


    public InternalGenerateHtml($fieldContainer:JQuery) {
        let $input:JQuery;
        if (this.AdditionalInformation.MultipleLine == true) {
            $input =rnJQuery('<textarea style="width:' + this.AdditionalInformation.Width + ';" class="rednao-input-large form-control" data-type="input" name="name" id="' + this.PropertyId + '" placeholder="' + this.AdditionalInformation.Placeholder + '">' + RedNaoEscapeHtml(this.GetPropertyCurrentValue()) + '</textarea>');

        }
        else {
             $input =rnJQuery('<input style="width: ' + this.AdditionalInformation.Width + ';" class="rednao-input-large form-control" data-type="input" type="text" name="name" id="' + this.PropertyId + '" value="' + RedNaoEscapeHtml(this.GetPropertyCurrentValue()) + '" placeholder="' + this.AdditionalInformation.Placeholder + '"/>');
        }

        $fieldContainer.append($input);

        $input.on( 'input',()=> {
            this.Manipulator.SetValue(this.PropertiesObject, this.PropertyName, (rnJQuery("#" + this.PropertyId).val()), this.AdditionalInformation);
            this.RefreshElement();

        });
    }


}
