import {RedNaoIconSelector} from "./RedNaoIconSelector";

declare let RedNaoBasicManipulatorInstance:any;
declare let RedNaoEventManager:any;
export abstract class ElementPropertiesBase{
    public FormElement:sfFormElementBase<any>;
    public Manipulator:any;
    public AdditionalInformation:any;
    public PropertiesObject:any;
    public PropertyName:string;
    public PropertyTitle:string;
    public PropertyId:string;
    public $PropertiesContainer:JQuery;
    public tooltip:{Text:string}=null;
    public IconOptions:any=null;
    protected enableFormula:boolean;
    constructor(formelement:sfFormElementBase<any>,propertiesObject:any,propertyName:string,propertyTitle:string,additionalInformation:any)
    {
        this.enableFormula=false;
        if(additionalInformation.ManipulatorType=='basic')
            this.Manipulator=RedNaoBasicManipulatorInstance;
        this.FormElement=formelement;
        this.AdditionalInformation=additionalInformation;
        this.PropertiesObject=propertiesObject;
        this.PropertyName=propertyName;
        this.PropertyTitle=propertyTitle;
        this.PropertyId="redNaoFormProperty"+this.PropertyName;
        this.$PropertiesContainer=null;

        if (typeof this.AdditionalInformation.ToolTip != 'undefined')
            this.tooltip=this.AdditionalInformation.ToolTip;
        if(typeof this.AdditionalInformation.IconOptions != 'undefined')
            this.IconOptions=this.AdditionalInformation.IconOptions;

    }

    public SetTooltip(text:string)
    {
        this.tooltip={Text:text};
        return this;
    }
    public  FormulaExists(formElement,propertyName)
    {
        return RedNaoPathExists(formElement, 'Options.Formulas.' + propertyName + '.Value') && formElement.Options.Formulas[propertyName].Value != "";
    };

    public SetEnableFormula(){
        this.enableFormula=true;
        return this;
    }

    public CreateProperty(jQueryObject:JQuery)
    {
        this.$PropertiesContainer=rnJQuery('<div class="row col-sm-12"></div>');
        jQueryObject.append(this.$PropertiesContainer);
        this.GenerateHtml();
    };

    public abstract InternalGenerateHtml($fieldContainer:JQuery);


    public GeneratePropertyContainer():void{


    }

    public GetFieldTemplate(){
        return rnJQuery(
            `<div class="col-sm-4">
                    <label class="rednao-properties-control-label"> ${this.PropertyTitle}</label>
             </div>
             <div class="col-sm-8">
                <div style="padding:0;margin:0;display: inline-block; width:90%" class="fieldContainer"  ></div>
                <div  class="iconArea" style="width: 10%;padding:0;margin:0;display: inline-block;float:right;"></div>
             </div>`);
    }

    public GenerateHtml(){
        this.$PropertiesContainer.append(this.GetFieldTemplate());

        let $fieldContainer=this.$PropertiesContainer.find('.fieldContainer');
        this.InternalGenerateHtml($fieldContainer);


        if(this.enableFormula)
            this.AppendFormulaIcon();

        if(this.tooltip!=null)
            this.AddToolTip();

        if(this.IconOptions!=null)
            this.AddIconSelector();
    }


    public RefreshProperty()
    {
        this.$PropertiesContainer.empty();
        this.GenerateHtml();
    };

    public GetPropertyCurrentValue()
    {
        return this.Manipulator.GetValue(this.PropertiesObject,this.PropertyName,this.AdditionalInformation);
    };

    public UpdateProperty()
    {
        this.Manipulator.SetValue(this.PropertiesObject,this.PropertyName, rnJQuery("#"+this.PropertyId).val(),this.AdditionalInformation);

    };

    public RefreshElement()
    {

        let previousClasses=this.FormElement.JQueryElement.attr('class');
        let newClasses=this.FormElement.GetElementClasses();
        if(previousClasses.indexOf('SmartFormsElementSelected')>=0)
            newClasses+=' SmartFormsElementSelected';

        let refreshedElements=this.FormElement.RefreshElement();
        this.FormElement.JQueryElement.attr('class',newClasses);
        refreshedElements.find('input[type=submit],button').click(function(e){e.preventDefault();e.stopPropagation();});
        RedNaoEventManager.Publish('FormDesignUpdated');

    };

    private AppendFormulaIcon() {

        let $formulaImg=rnJQuery(`<img class="formulaIcon" style="width:15px;height: 20px; vertical-align: middle;cursor:pointer;margin-left:2px;" title="Formula" src="${smartFormsRootPath+(this.FormulaExists(this.FormElement,this.PropertyName)?'images/formula_used.png' :'images/formula.png')}"/></td>'`);
        this.$PropertiesContainer.find('.iconArea').append($formulaImg);

        $formulaImg.click(()=>{
            RedNaoEventManager.Publish('FormulaButtonClicked', {
                "FormElement": this.FormElement,
                "PropertyName": this.PropertyName,
                AdditionalInformation: this.AdditionalInformation,
                Image: $formulaImg
            })
        })

    }

    private AddToolTip() {
        this.$PropertiesContainer.find('.rednao-properties-control-label').append(
            `<span style="color:black; margin-left: 2px;cursor:pointer;" data-toggle="tooltip" data-placement="right" title="${RedNaoEscapeHtml(this.tooltip.Text)}" class="glyphicon glyphicon-question-sign sfToolTip"></span>`
        );
        this.$PropertiesContainer.find('.sfToolTip').tooltip({html: true});
    }

    private AddIconSelector() {
        let selected = '';
        let defaultValue = this.PropertiesObject[this.PropertyName + '_Icon'].ClassName;
        if (defaultValue != '')
            selected = 'sfSelected';
        let addIconButton = rnJQuery('<a style="margin-left: 2px;font-size: 20px;line-height:20px;" href="#"><span class="fa fa-smile-o sfAddIcon ' + selected + '" title="Add Icon"></span></a>');
        addIconButton.click( ()=> {
            RedNaoIconSelector.Current.Show(this.IconOptions.Type, defaultValue,  (itemClass, orientation)=> {
                defaultValue = itemClass;
                this.IconSelected(itemClass, orientation, addIconButton)
            });
        });
        this.$PropertiesContainer.find('.iconArea').append(addIconButton);
    }

    public IconSelected(itemClass, orientation, $addIconButton) {
        this.PropertiesObject[this.PropertyName + '_Icon'] = {
            ClassName: itemClass,
            Orientation: orientation
        };

        if (itemClass == '')
            $addIconButton.find('span').removeClass('sfSelected');
        else
            $addIconButton.find('span').addClass('sfSelected');
        this.RefreshElement();
    }
}