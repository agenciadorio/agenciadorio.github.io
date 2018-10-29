import {RedNaoIconSelector} from "./RedNaoIconSelector";
import {ElementPropertiesBase} from "./ElementPropertiesBase";

export class IconProperty extends ElementPropertiesBase{

    public InternalGenerateHtml ($fieldContainer:JQuery) {

        let value = this.GetPropertyCurrentValue().ClassName;
        let $input=rnJQuery(
            `<div>
                <span class="${RedNaoEscapeHtml(value)}"></span><button style="margin-left: 2px">Edit</button>
             </div>`);


        $fieldContainer.append($input);


        $input.find('button').click( (e) =>{
            e.preventDefault();
            RedNaoIconSelector.Current.Show('add', this.GetPropertyCurrentValue().ClassName,  (itemClass, orientation)=>{
                this.PropertiesObject[this.PropertyName] = {
                    ClassName: itemClass,
                    Orientation: orientation
                };
                this.RefreshElement();
                $input.find('span').attr('class', itemClass);
            });
        });


    }
}