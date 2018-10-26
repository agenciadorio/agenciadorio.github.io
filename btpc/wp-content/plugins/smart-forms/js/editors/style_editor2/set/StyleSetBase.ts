import {
    BorderProperty,
    ButtonsProperty,
    Capitalization, ColorPicker, ComboProperty, FontFamily, PropertyBase, SliderProperty,
    TextDecoration
} from "../properties/PropertyBase";
export abstract class StyleGroupBase{
    private $accordionGroup:JQuery;
    private $propertiesContainer:JQuery;
    private properties:PropertyBase[]=[];

    constructor(public fieldToEdit:sfFormElementBase<any>,public elementClass:string,public scope:string, public groupName:string,public $container:JQuery){
        let id=groupName.replace(' ','_');
        this.$accordionGroup=rnJQuery(`<div class="styleGroup">
                                            <div class="sfStyleTitle">
                                                <h5>
                                                    <a data-toggle="collapse" href="#${id}" class="collapsed"><span class="sfAccordionIcon glyphicon glyphicon-chevron-right"></span>${groupName}</a>
                                                </h5>
                                            </div>
                                            <div class="sfStyleContainer collapse"  id="${id}"><div class="clearer" style="clear:both;"></div></div>                                             
                                      </div>`);
        this.$propertiesContainer=this.$accordionGroup.find('.sfStyleContainer');
        this.$accordionGroup.find('.sfStyleContainer').collapse();
        $container.append(this.$accordionGroup);
    }

    public Show():StyleGroupBase{
        this.$accordionGroup.find('.sfStyleTitle a').removeClass('collapsed');
        this.$accordionGroup.find('.sfStyleContainer').addClass('in');
        return this;
    }

    protected AddProperty(property:PropertyBase):StyleGroupBase{
        this.properties.push(property);
        property.Generate(this.$propertiesContainer);
        return this;
    }

    public abstract Generate();

}


export class LabelStyleGroup extends StyleGroupBase{
    public Generate() {
        this.AddProperty(new SliderProperty(this.fieldToEdit,this.elementClass,this.scope,'Size','font-size'));
        this.AddProperty(new FontFamily(this.fieldToEdit,this.elementClass,this.scope));
        this.AddProperty(new TextDecoration(this.fieldToEdit,this.elementClass,this.scope));
        this.AddProperty(new Capitalization(this.fieldToEdit,this.elementClass,this.scope));
        this.AddProperty(new ColorPicker(this.fieldToEdit,this.elementClass,this.scope,'Color','color'));
        this.AddProperty(new ButtonsProperty(this.fieldToEdit,this.elementClass,this.scope,'Bold','font-weight')
            .AddOption("Yes","bold")
            .AddOption("No","normal")
        );
        this.AddProperty(new ButtonsProperty(this.fieldToEdit,this.elementClass,this.scope,'Italic','font-style')
            .AddOption("Yes","italic")
            .AddOption("No","normal")
        );
    }


}


export class ControlStyleGroup extends StyleGroupBase{
    public Generate() {
        this.AddProperty(new ColorPicker(this.fieldToEdit,this.elementClass,this.scope,'Background Color','background-color'));
        this.AddProperty(new BorderProperty(this.fieldToEdit,this.elementClass,this.scope));

    }
}

export class DynamicStyleGroup extends StyleGroupBase{
    private _property:PropertyBase[]=[];
    public Generate()
    {
        for(var property of this._property)
            this.AddProperty(property);
    }

    public CreateProperty(property:PropertyBase):DynamicStyleGroup{
        this._property.push(property);
        return this;
    }
}