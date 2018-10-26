import { ElementPropertiesBase } from "./ElementPropertiesBase";
export declare class PropertyContainer extends ElementPropertiesBase {
    Id: string;
    Title: string;
    properties: ElementPropertiesBase[];
    _internal_id: string;
    static nextId: number;
    private $accordionGroup;
    private $propertiesContainer;
    static SectionHistory: any;
    constructor(Id: string, Title: string);
    InternalGenerateHtml($fieldContainer: JQuery): void;
    CreateProperty($container: JQuery): any;
    AddProperties(properties: ElementPropertiesBase[]): this;
    Show(): void;
    AddProperty(property: ElementPropertiesBase): void;
}
