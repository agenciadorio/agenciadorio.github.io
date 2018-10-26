import { PropertyBase } from "../properties/PropertyBase";
export declare abstract class StyleGroupBase {
    fieldToEdit: sfFormElementBase<any>;
    elementClass: string;
    scope: string;
    groupName: string;
    $container: JQuery;
    private $accordionGroup;
    private $propertiesContainer;
    private properties;
    constructor(fieldToEdit: sfFormElementBase<any>, elementClass: string, scope: string, groupName: string, $container: JQuery);
    Show(): StyleGroupBase;
    protected AddProperty(property: PropertyBase): StyleGroupBase;
    abstract Generate(): any;
}
export declare class LabelStyleGroup extends StyleGroupBase {
    Generate(): void;
}
export declare class ControlStyleGroup extends StyleGroupBase {
    Generate(): void;
}
export declare class DynamicStyleGroup extends StyleGroupBase {
    private _property;
    Generate(): void;
    CreateProperty(property: PropertyBase): DynamicStyleGroup;
}
