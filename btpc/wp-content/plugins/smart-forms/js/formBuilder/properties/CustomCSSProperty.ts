import {ElementPropertiesBase} from "./ElementPropertiesBase";

export class CustomCSSProperty extends ElementPropertiesBase {

    constructor(formelement, propertiesObject) {
        super(formelement, propertiesObject, "CustomCSS", "Custom CSS", {ManipulatorType: 'basic'});
        this.tooltip={Text:'Add all the custom class styles separated by space, e.g. button blue'};
    }


    public InternalGenerateHtml($fieldContainer:JQuery) {

        let $input =rnJQuery('<input style="width: 206px;" class="rednao-input-large form-control" data-type="input" type="text" name="name" id="' + this.PropertyId + '" value="' + RedNaoEscapeHtml(this.GetPropertyCurrentValue()) + '" placeholder="None"/>');
        $fieldContainer.append($input);
        $input.keyup(() =>{
            this.Manipulator.SetValue(this.PropertiesObject, this.PropertyName, $input.val(), this.AdditionalInformation);
            this.RefreshElement();

        });

    }
}
