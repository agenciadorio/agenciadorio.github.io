
import {ElementPropertiesBase} from "./ElementPropertiesBase";
import {PropertyBase} from "../../editors/style_editor2/properties/PropertyBase";

export class PropertyContainer extends ElementPropertiesBase{
    public properties:ElementPropertiesBase[]=[];
    public _internal_id:string;
    public static nextId:number=1;
    private $accordionGroup:JQuery;
    private $propertiesContainer:JQuery;

    public static SectionHistory:any={general:true};
    constructor(public Id:string,public Title:string){
        super(null,null,null,null,{});
        this._internal_id='property_'+PropertyContainer.nextId++;
    }

    InternalGenerateHtml($fieldContainer: JQuery) {
        throw 'Not needed';
    }


    public CreateProperty($container: JQuery): any {
        this.$accordionGroup=rnJQuery(`<div class="propertyGroup">
                                            <div class="sfPropertyTytle">
                                                <h5>
                                                    <a class="sfColapseLink collapsed" data-toggle="collapse" href="#${this._internal_id}" class="collapsed"><span class="sfAccordionIcon glyphicon glyphicon-chevron-right"></span>${this.Title}</a>
                                                </h5>
                                            </div>
                                            <div class="sfPropertyContainer collapse"  id="${this._internal_id}"></div>                                             
                                      </div>`);
        this.$propertiesContainer=this.$accordionGroup.find('.sfPropertyContainer');
        this.$accordionGroup.find('.sfStyleContainer').collapse();
        $container.append(this.$accordionGroup);

        for(let property of this.properties)
            property.CreateProperty(this.$propertiesContainer);

        this.$propertiesContainer.append('<div class="clearer" style="clear:both;"></div>');



        if(PropertyContainer.SectionHistory[this.Id])
            this.Show();


        this.$accordionGroup.find('.sfPropertyContainer').on('hidden.bs.collapse', (e) =>{
            PropertyContainer.SectionHistory[this.Id]=false;
        });

        this.$accordionGroup.find('.sfPropertyContainer').on('shown.bs.collapse',  (e)=> {
            PropertyContainer.SectionHistory[this.Id]=true;
        });
    }

    public AddProperties(properties:ElementPropertiesBase[])
    {
        for(let property of properties)
            this.properties.push(property);
        return this;
    }

    public Show(){
        this.$accordionGroup.find('.sfPropertyTytle a').removeClass('collapsed');
        this.$accordionGroup.find('.sfPropertyContainer').addClass('in');

    }

    public AddProperty(property:ElementPropertiesBase)
    {
        this.properties.push(property);
    }
}