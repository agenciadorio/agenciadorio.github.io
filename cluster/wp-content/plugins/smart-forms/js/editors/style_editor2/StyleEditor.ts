import {ControlStyleGroup, DynamicStyleGroup, LabelStyleGroup, StyleGroupBase} from "./set/StyleSetBase";
import {ColorPicker, SliderProperty} from "./properties/PropertyBase";
export class StyleEditor {
    public $styleEditorContainer:JQuery;
    public fieldToEdit:sfFormElementBase<any>=null;
    public targetScope:string='af';
    public styleGroups:StyleGroupBase[]=[];
    constructor(){
        this.$styleEditorContainer=rnJQuery('#formStylesContainer');
        rnJQuery('#sfSettingTabs').on('show.bs.tab',(e)=>{
            if(rnJQuery(e.target).attr('id')=='formRadio4')
                this.RefreshEditor();
        })
    }

    public RefreshEditor() {
        this.fieldToEdit=this.GetSelectedField();
        this.$styleEditorContainer.empty();
        this.CreateSelectionCombo(this.fieldToEdit);
        this.CreateStyleProperties(this.fieldToEdit);
        for(var group of this.styleGroups)
            group.Generate();

    }

    private GetSelectedField() {
        var fieldId=rnJQuery('.SmartFormsElementSelected').attr('id');
        for(var field of SmartFormsAddNewVar.FormBuilder.RedNaoFormElements)
            if(field.Id==fieldId)
                return field;

        return null;
    }

    private CreateSelectionCombo(field: sfFormElementBase<any>) {
        let $combo=rnJQuery('<select></select>');
        $combo.append(`<option value="fc" ${this.targetScope=='fc'?"selected='selected'":''}>Form Container</option>`);
        $combo.append(`<option value="af" ${this.targetScope=='af'?"selected='selected'":''}>All fields</option>`);
        if(this.fieldToEdit!=null)
        {
            $combo.append(`<option value="sfo" ${this.targetScope=='sfo'?"selected='selected'":''}>Selected field only</option>`);
            $combo.append(`<option value="afsttso" ${this.targetScope=='afost'?"selected='selected'":''}>All fields similar to the selected one</option>`);
        }


        let $container=rnJQuery('<div class="row" style="text-align: right;"><label style="padding-right: 5px;font-weight: normal;">Apply the style to</label></div>');
        $combo.change(()=>{
            this.ScopeChanged($combo.val());
        });
        $container.append($combo);
        this.$styleEditorContainer.append($container);

    }

    private CreateStyleProperties(fieldToEdit: sfFormElementBase<any>) {
        let $stylePropertiesContainer=rnJQuery('<div></div>');
        this.$styleEditorContainer.append($stylePropertiesContainer);
        this.CreatePropertiesForField(fieldToEdit,$stylePropertiesContainer);

    }

    private CreatePropertiesForField(fieldToEdit: sfFormElementBase<any>,$container:JQuery) {
        console.log(this.targetScope);
        let $accordion=rnJQuery('<div id="sfStyleAccordion"></div>');
        this.$styleEditorContainer.append($accordion);
        this.styleGroups=[];
        if(this.targetScope!='fc')
            this.styleGroups.push(new LabelStyleGroup(fieldToEdit,'.rednao_control_label',this.targetScope,"Label Styles", $accordion).Show());
        this.styleGroups.push(new ControlStyleGroup(fieldToEdit,'',this.targetScope,"Field Styles", $accordion).Show());

        if(fieldToEdit.Options.ClassName=='rednaosubmissionbutton')
            this.styleGroups.push(
                new DynamicStyleGroup(fieldToEdit,'',this.targetScope,"Button Styles", $accordion)
                    .CreateProperty(new ColorPicker(fieldToEdit,'.redNaoSubmitButton',this.targetScope,'Text Color','color')
                                        .SetDimensions(6,6,6)
                                    )
                    .CreateProperty(new ColorPicker(fieldToEdit,'.redNaoSubmitButton',this.targetScope,'Background Color','background-color')
                                        .SetDimensions(6,6,6)
                                   )
            );
    }

    private ScopeChanged(scope: string) {
        this.targetScope=scope;
        this.RefreshEditor();
    }
}