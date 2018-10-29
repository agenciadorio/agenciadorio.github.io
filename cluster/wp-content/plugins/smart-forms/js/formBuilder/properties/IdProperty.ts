import {ElementPropertiesBase} from "./ElementPropertiesBase";

export class IdProperty extends ElementPropertiesBase {

    public PreviousId:string;
    constructor(formelement, propertiesObject) {
        super(formelement, propertiesObject, "Id", "Id", {ManipulatorType: 'basic'});


    }


    public InternalGenerateHtml($fieldContainer:JQuery) {
        this.PreviousId = this.FormElement.Id;

        let value = this.PreviousId;
        let $input=rnJQuery(`<input style="width: 206px;" class="rednao-input-large form-control" data-type="input" maxlength="50" type="text" name="name" id="${this.PropertyId}" value="${value}" placeholder="Default"/>`);

        $fieldContainer.append($input);

        $input.change( ()=> {

            let jqueryElement = $input;
            let fieldName = jqueryElement.val().trim();

            if (!fieldName.match(/^[a-zA-Z]([a-zA-Z]|[0-9])*$/)) {
                alert("Invalid field name, it should start with a letter and not contain spaces or symbols");
                jqueryElement.val(this.PreviousId);
                return;
            }

            let formElements = SmartFormsAddNewVar.FormBuilder.RedNaoFormElements;
            for (let i = 0; i < formElements.length; i++) {
                if (fieldName.toLowerCase() == formElements[i].Id.toLowerCase()) {
                    alert("The field " + fieldName + " already exists");
                    jqueryElement.val(this.PreviousId);
                    return;
                }
            }

            this.FormElement.Id = fieldName;
            this.PropertiesObject.Id = fieldName;

            let jQueryElement = rnJQuery('#' + this.PreviousId);
            jQueryElement.attr('id', fieldName);


            let refreshedElements = this.FormElement.RefreshElement();
            refreshedElements.find('input[type=submit],button').click(function (e) {
                e.preventDefault();
                e.stopPropagation();
            });
            this.RefreshElement();

        });

    };

}