import {ElementPropertiesBase} from "./ElementPropertiesBase";

export class CheckBoxProperty  extends ElementPropertiesBase {

    public InternalGenerateHtml ($fieldContainer:JQuery) {
        let $input=rnJQuery(`<input type="checkbox" class="input-inline field" name="checked" id="${this.PropertyId}" ${ (this.GetPropertyCurrentValue() == 'y' ? 'checked="checked"' : '')} '/>`);
        $fieldContainer.append($input);

        $input.change(()=> {
            this.Manipulator.SetValue(this.PropertiesObject, this.PropertyName, ($input.is(':checked') ? 'y' : 'n'), this.AdditionalInformation);
            this.RefreshElement();
        });

    }
}
