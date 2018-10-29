declare var jscolor:any;
export abstract class PropertyBase{
    protected $propertiesContainer:JQuery=null;
    protected $property:JQuery=null;
    private containerSize:number=12;
    private labelSize:number=3;
    private controlSize:number=9;
    constructor(public fieldToEdit:sfFormElementBase<any>,public elemeentClass:string,public scope:string, public label:string,public styleName:string){

    }

    public Generate($propertiesContainer: JQuery){
        this.$propertiesContainer=$propertiesContainer;
        this.$property=rnJQuery(`<div class="row col-sm-${this.containerSize}" style="padding:0;">
                                    <div class="col-sm-${this.labelSize}"><label>${this.label}</label></div>                                         
                                  </div>`);
        this.$property.insertBefore(this.$propertiesContainer.find('.clearer'));
        //this.$propertiesContainer.find('.clearer')..append(this.$property);

        let $control=rnJQuery(`<div class="col-sm-${this.controlSize}"></div>`);
        $control.append(this.InternalGenerate());
        this.$property.append($control);
        this.AppendCompleted();
    }

    public SetDimensions(containerSize:number,labelSize:number,controlSize:number):PropertyBase{
        this.containerSize=containerSize;
        this.labelSize=labelSize;
        this.controlSize=containerSize;
        return this;
    }

    protected abstract InternalGenerate():JQuery;

    protected AppendCompleted() {

    }

    protected PropertyChanged(value:string) {
        if(value=='')
            this.ClearValue();
        else
            this.SetValue(value);
        SmartFormsAddNewVar.ApplyCustomCSS();

    }

    protected GetValue(styleName:string=null):string{
        if(styleName==null)
            styleName=this.styleName;
        let id:string=this.GetId();
        var regexp=new RegExp(id+'\\{[\\s\\S]*'+styleName+'[^:]*:([^;|!]*)','i');
        var match=regexp.exec(rnJQuery('#smartFormsCSSText').val());
        if(match!=null&&match.length==2)
            return rnJQuery.trim(match[1]);
        return '';
    }

    protected GetId():string{
        var id= '.bootstrap-wrapper.SfFormElementContainer';
        switch(this.scope){
            case "af":
                id+= " .rednao-control-group";
                break;
            case "afsttso":
                id+=" ."+this.fieldToEdit.Options.ClassName;
            break;
            case "sfo":
                id+=" #"+this.fieldToEdit.Id;
                break;
        }

        return id+" "+this.elemeentClass;


    }


    protected ClearValue(styleName:string=null) {
        if(styleName==null)
            styleName=this.styleName;
        let id=this.GetId();
        let elementStylesRegexp=new RegExp(id+'\\{([^\\}]*)}','i');
        let match=elementStylesRegexp.exec(rnJQuery('#smartFormsCSSText').val());
        if(match.length<2)
            return;
        let fullStyle=match[0];
        let elementsInside=match[1];
        let newText=elementsInside.replace(new RegExp(styleName+'[^;]*;','i'),'');



        if(rnJQuery.trim(newText)=='')
        {
            rnJQuery('#smartFormsCSSText').val(rnJQuery('#smartFormsCSSText').val().replace(fullStyle,''));

        }else{
            rnJQuery('#smartFormsCSSText').val(rnJQuery('#smartFormsCSSText').val().replace(fullStyle,id+'{\r\n'+rnJQuery.trim(newText)+"\r\n}\r\n"));
        }
        console.log(rnJQuery('#smartFormsCSSText').val());

    }

    protected SetValue(value: string,styleName:string=null) {
        if(styleName==null)
            styleName=this.styleName;
        let id=this.GetId();
        let elementStylesRegexp=new RegExp(id+'\\{([^\\}]*)}','i');
        let match=elementStylesRegexp.exec(rnJQuery('#smartFormsCSSText').val());
        if(match==null||match.length<2)
        {
            let previousValue=rnJQuery.trim(rnJQuery('#smartFormsCSSText').val());
            if(previousValue.length!=0)
                previousValue+="\r\n";
            rnJQuery('#smartFormsCSSText').val(previousValue+id+'{\r\n'+styleName+":"+value+" !important;\r\n}\r\n");
        }else{
            let styleValue=styleName+":"+value+" !important;";
            let fullStyle=match[0];
            let selectedStyle=new RegExp(styleName+'[^;]*;','i').exec(fullStyle);
            let newStyle="";
            if(selectedStyle==null)
                newStyle=fullStyle.replace('}',styleValue+"\r\n}");
            else
                newStyle=fullStyle.replace(selectedStyle[0],styleValue);

            rnJQuery('#smartFormsCSSText').val(rnJQuery('#smartFormsCSSText').val().replace(fullStyle,newStyle));

        }

    }
}


export class ColorPicker extends PropertyBase{
    public $colorPicker:JQuery;
    constructor(public fieldToEdit:sfFormElementBase<any>,public elemeentClass:string,public scope:string, public label:string,public styleName:string){
        super(fieldToEdit,elemeentClass,scope,label,styleName);

    }
    protected InternalGenerate(): JQuery {
        this.$colorPicker=rnJQuery('<input placeholder="asdf"/>');
        this.$colorPicker.change(()=>{
            this.PropertyChanged(this.$colorPicker.spectrum("get"));
        });
        return this.$colorPicker;
    }

    protected AppendCompleted() {
        this.$colorPicker.spectrum({
            preferredFormat:"hex",
            showInput: true,
            allowEmpty:true,
            showAlpha:true,
            showInitial: true
        });
        rnJQuery('.sp-input').attr('placeholder','Default');

        let value=this.GetValue();
        if(value!='')
        {
            this.$colorPicker.spectrum('set',value);
        }
    }
}



export class ButtonsProperty extends PropertyBase{
    public options=[];
    protected $buttonToolbar:JQuery;

    constructor(public fieldToEdit:sfFormElementBase<any>,public elemeentClass:string,public scope:string, public label:string,public styleName:string){
        super(fieldToEdit,elemeentClass,scope,label,styleName);
        this.AddOption('Default','');
    }

    protected InternalGenerate(): JQuery {
        this.$buttonToolbar= rnJQuery('<div class="btn-group" role="group" data-toggle="buttons" style="width: 100%"></div>');
        //this.$buttonToolbar.change(()=>this.PropertyChanged(this.$combo.val()));
        let value=this.GetValue();
        for(let option of this.options){
            let $button=rnJQuery(`<button  data-toggle="button" type="button" class="btn btn-default ${option.value==value?'active':''}" data-value="${option.value}">${option.label}</button >`);
            $button.click(()=>{
                $button.parent().find('button').removeClass('active');
                $button.addClass('active');
                this.PropertyChanged(option.value);
            });
            this.$buttonToolbar.append($button);
        }

        return this.$buttonToolbar;
    }


    protected AppendCompleted() {
        this.$buttonToolbar.find('button').button();
    }

    public AddOption(label:string,value:string):ButtonsProperty{
        this.options.push({'label':label,'value':value});
        return this;
    }


}



export class SliderProperty extends PropertyBase{
    public options=[];
    protected $slider:JQuery;
    protected $textBox:JQuery;
    protected min:number=9;
    protected max:number=30;

    constructor(public fieldToEdit:sfFormElementBase<any>,public elemeentClass:string,public scope:string, public label:string,public styleName:string){
        super(fieldToEdit,elemeentClass,scope,label,styleName);
    }

    protected InternalGenerate(): JQuery {
        let $container=rnJQuery('<div style="width: 100%"></div>');
        this.$slider= rnJQuery(`<input  style="width: 80%;" id="ex1" data-slider-id='ex1Slider' type="text" data-slider-min="0" data-slider-max="20" data-slider-step="1" data-slider-value="14"/>`);
        this.$textBox=rnJQuery('<input class="form-control" type="text" disabled="disabled" style="font-size: 13px;text-align: center; display: inline; width: calc(20% - 10px);margin-left: 8px;"/>');
        $container.append(this.$slider);
        $container.append(this.$textBox);
        let value=this.GetValue().replace('px','');
        var label=value;
        if(label=='')
            label='Default';
        this.$textBox.val(label);
        return $container;
    }


    protected AppendCompleted() {
        let value:any=this.GetValue().replace('px','');;
        if(value=='')
            value=this.min;
        this.$slider.bootstrapSlider({
            min:this.min,
            max:this.max,
            value:value,
            formatter: (value)=> {
                return value==this.min?"Default":value;
            }
        });

        this.$slider.on('slide',(e:any)=>{
            let value=e.value;
            let label=e.value;
            if(value==this.min) {
                value = '';
                label='Default';
            }

            this.$textBox.val(label);
        });

        this.$slider.on('slideStop',(e:any)=>{
            let value=e.value;
            if(value==this.min)
                value='';
            else
                value=value+'px';
            this.PropertyChanged(value);
        });
    }



}





/*--------------------------------------------------Combos---------------------------------------------------------*/



export class ComboProperty extends PropertyBase{
    public options=[];
    protected $combo:JQuery;

    constructor(public fieldToEdit:sfFormElementBase<any>,public elemeentClass:string,public scope:string, public label:string,public styleName:string){
        super(fieldToEdit,elemeentClass,scope,label,styleName);
        this.AddOption('Default','');
    }

    protected InternalGenerate(): JQuery {
        this.$combo= rnJQuery('<select style="width: 100%"></select>');
        this.$combo.change(()=>this.PropertyChanged(this.$combo.val()));
        let value=this.GetValue();
        for(var option of this.options){
            var op=new Option(option.label,option.value);
            op.selected=op.value==value;
            this.$combo.append(op);
        }

        return this.$combo;
    }


    protected AppendCompleted() {
        this.$combo.select2();
    }

    public AddOption(label:string,value:string):ComboProperty{
        this.options.push({'label':label,'value':value});
        return this;
    }


}

export class TextDecoration extends ComboProperty{
    constructor(public fieldToEdit:sfFormElementBase<any>,public elemeentClass:string,public scope:string){
        super(fieldToEdit,elemeentClass,scope,"Text Decoration","text-decoration");
        this.AddOption('Overline','overline');
        this.AddOption('Line Through','line-through');
        this.AddOption('Underline','underline');
    }

}

export class Capitalization extends ComboProperty{
    constructor(public fieldToEdit:sfFormElementBase<any>,public elemeentClass:string,public scope:string){
        super(fieldToEdit,elemeentClass,scope,"Capitalization","text-transform");
        this.AddOption('Capitalize','capitalize');
        this.AddOption('Uppercase','uppercase');
        this.AddOption('Lowercase','lowercase');
    }

}

export class FontFamily extends ComboProperty{
    constructor(public fieldToEdit:sfFormElementBase<any>,public elemeentClass:string,public scope:string){
        super(fieldToEdit,elemeentClass,scope,"Font Family","Font-Family");
        this.AddOption('Arial','Arial');
        this.AddOption('ArialBlack','ArialBlack');
        this.AddOption('Comic Sans MS','Comic Sans MS');
        this.AddOption('Courier New','Courier New');
        this.AddOption('Georgia','Georgia');
        this.AddOption('Impact','Impact');
        this.AddOption('Lucida Console','Lucida Console');
        this.AddOption('Lucida Sans Unicode','Lucida Sans Unicode');
        this.AddOption('Platino Linotype','Platino Linotype');
        this.AddOption('Tahoma','Tahoma');
        this.AddOption('Times New Roman','Times New Roman');
        this.AddOption('Trebuchet MS','Trebuchet MS');
        this.AddOption('Verdana','Verdana');
    }


    protected AppendCompleted(): any {
        let formatResult=(state)=>{
          return "<span style='font-family: "+state.text+"'>"+state.text+"</span>"
        };
        this.$combo.select2({
            'formatResult':formatResult,
            'formatSelection':formatResult
        });
    }
}

/*-----------------------------Border-------------------------------*/


export class BorderProperty extends PropertyBase{
    public options=[];
    protected $container:JQuery;
    protected $buttonToolbar:JQuery;
    protected $colorPicker:JQuery;
    protected $slider:JQuery;
    protected $style:JQuery;
    protected borderLeftEnabled:boolean=false;
    protected borderRightEnabled:boolean=false;
    protected borderTopEnabled:boolean=false;
    protected borderBottomEnabled:boolean=false;
    protected color:string="#000000";
    protected style:string="solid";
    protected width:string="1px";

    constructor(public fieldToEdit:sfFormElementBase<any>,public elemeentClass:string,public scope:string){
        super(fieldToEdit,elemeentClass,scope,"Borders","");
    }

    protected InternalGenerate(): JQuery {
        this.GetDefaultValues();

        this.$container= rnJQuery('<div style="width: 100%"></div>');
        this.CreateBorderButtons();
        this.CreateColor();
        this.CreateBorderStyle();
        this.CreateSlider();

        return this.$container;
    }


    protected AppendCompleted() {
        this.$buttonToolbar.find('button').button();
        this.$colorPicker.spectrum({
            preferredFormat:"hex",
            showInput: true,
            allowEmpty:true,
            showAlpha:true,
            showInitial: true
        });


        let formatResult=(state)=>{
            return `<div style='border-style: ${state.text};border-width: 5px;border-color:#c3c3c3;width:100%;height: 20px;margin-top:2px;'>&nbsp;</div>`;
        };
        this.$style.select2({
            'formatResult':formatResult,
            'formatSelection':formatResult
        });


        let value=parseInt(this.width.replace('px',''));
        if(isNaN(value))
            value=1;

        this.$slider.bootstrapSlider({
            min:1,
            max:20,
            value:value
        });
        this.$slider.on('slideStop',(e:any)=>{
            let value=e.value+'px';
            this.width=value;
            this.RefreshStyles();
        });
        
        
        this.$slider.parent().find('.slider').css('margin-top','5px');

        this.$colorPicker.spectrum('set',this.color);
        
        

    }


    private CreateBorderButtons() {
        let $container=rnJQuery('<div style="display: inline-block;width: 50%;margin-right: 5px;"></div>');
        this.$buttonToolbar= rnJQuery(`<div class="btn-group" role="group" data-toggle="buttons" style="display: inline-block;">
                                            <button  data-toggle="button" type="button" class="btn btn-default sfBorder ${this.borderTopEnabled?'active':''}" data-value="top"><img src="${smartFormsRootPath}images/border_top.png"/></button >
                                            <button  data-toggle="button" type="button" class="btn btn-default sfBorder ${this.borderRightEnabled?'active':''}" data-value="right"><img src="${smartFormsRootPath}images/border_right.png"/></button >
                                            <button  data-toggle="button" type="button" class="btn btn-default sfBorder ${this.borderBottomEnabled?'active':''}" data-value="bottom"><img src="${smartFormsRootPath}images/border_bottom.png"/></button >
                                            <button  data-toggle="button" type="button" class="btn btn-default sfBorder ${this.borderLeftEnabled?'active':''}" data-value="left"><img src="${smartFormsRootPath}images/border_left.png"/></button >
                                        </div>`);

        var self=this;
        this.$buttonToolbar.find('button').click(function(){
            let $button=rnJQuery(this);
            let value=$button.data('value');

            if(!$button.hasClass('active')) {
                $button.addClass('active');
                self.AddBorder(value);
            }
            else {
                $button.removeClass('active');
                self.RemoveBorder(value);
            }

        });

/*        let $button=rnJQuery();
        $button.click(()=>{
            $button.parent().find('button').removeClass('active');
            $button.addClass('active');
            this.PropertyChanged(option.value);
        });
        this.$buttonToolbar.append($button);*/


        $container.append(this.$buttonToolbar);
        this.$container.append($container);
        this.AddLabel($container,'Borders to show')
    }

    private CreateColor() {

        this.$colorPicker=rnJQuery('<input placeholder="" style=""/>');
        this.$colorPicker.change(()=>{
            this.color=this.$colorPicker.spectrum("get");
            this.RefreshStyles();
        });
        let $container=rnJQuery('<div style="width:70px;margin-top:10px;display: inline-block;text-align: center;"></div>')
        $container.append(this.$colorPicker);
        this.$container.append($container);
        this.AddLabel($container,'Color');
    }

    private CreateBorderStyle() {
        let $container=rnJQuery('<div style="width:50%;display: inline-block;"></div>');
        this.$style=rnJQuery('<select style="width:100%; margin-top:10px;"></select>');
        this.$style.append(new Option('Dotted','dotted',false,this.style=='dotted'));
        this.$style.append(new Option('Dashed','dashed',false,this.style=='dashed'));
        this.$style.append(new Option('Solid','solid',true,this.style=='solid'));
        this.$style.append(new Option('Double','double',false,this.style=='double'));
        this.$style.append(new Option('Groove','groove',false,this.style=='groove'));
        this.$style.append(new Option('Ridge','ridge',false,this.style=='ridge'));
        this.$style.append(new Option('Inset','inset',false,this.style=='inset'));
        this.$style.append(new Option('Outset','outset',false,this.style=='outset'));

        this.$style.change(()=>{
            this.style=this.$style.val();
            this.RefreshStyles();
        });

        $container.append(this.$style);
        this.$container.append($container);
        this.AddLabel($container,"Style");
    }

    private CreateSlider() {
        this.$slider= rnJQuery(`<input  style="width: 100%;" id="ex1" data-slider-id='ex1Slider' type="text" data-slider-min="0" data-slider-max="20" data-slider-step="1" data-slider-value="14"/>`);
        let $container=rnJQuery('<div style="width:calc(50% - 11px);display: inline-block;margin-left: 11px;"></div>');

        $container.append(this.$slider);
        this.AddLabel($container,'Size');
        this.$container.append($container);
    }

    private AddLabel($container: JQuery, label: string) {
        $container.append(`<div style="width:100%;text-align: center;"><img style="width: 100%;height: 20px;" src="${smartFormsRootPath}images/curlyBracket.png"><span style="color:#ddd;">${label}</span></div>`)
    }

    private GetDefaultValues() {
        this.borderLeftEnabled=this.GetValue('border-left')!='';
        this.borderRightEnabled=this.GetValue('border-right')!='';
        this.borderTopEnabled=this.GetValue('border-up')!='';
        this.borderBottomEnabled=this.GetValue('border-down')!='';

        let borderToGetDefaults='';
        if(this.borderLeftEnabled)
            borderToGetDefaults='border-left';
        if(this.borderBottomEnabled)
            borderToGetDefaults='border-bottom';
        if(this.borderRightEnabled)
            borderToGetDefaults='border-right';
        if(this.borderTopEnabled)
            borderToGetDefaults='border-top';

        if(borderToGetDefaults=='')
            return;

        let value=this.GetValue(borderToGetDefaults);
        let splittedValues=value.split(' ');
        if(splittedValues.length!=3)
            return;

        this.width=splittedValues[0];
        this.style=splittedValues[1];
        this.color=splittedValues[2];

    }

    private RefreshStyles() {
        let value=this.width+' '+this.style+' '+this.color;
        if(this.borderTopEnabled)
            this.SetValue(value,'border-top');
        if(this.borderRightEnabled)
            this.SetValue(value,'border-right');
        if(this.borderLeftEnabled)
            this.SetValue(value,'border-left');
        if(this.borderBottomEnabled)
            this.SetValue(value,'border-bottom');
        SmartFormsAddNewVar.ApplyCustomCSS();
        
    }


    AddBorder(border:string) {
        let value=this.width+' '+this.style+' '+this.color;
        switch(border){
            case 'top':
                this.borderTopEnabled=true;
                this.SetValue(value,'border-top');
                break;
            case 'right':
                this.borderRightEnabled=true;
                this.SetValue(value,'border-right');
                break;
            case 'bottom':
                this.borderBottomEnabled=true;
                this.SetValue(value,'border-bottom');
                break;
            case 'left':
                this.borderLeftEnabled=true;
                this.SetValue(value,'border-left');
                break;
        }

        SmartFormsAddNewVar.ApplyCustomCSS();

    }

    RemoveBorder(border:string) {
        switch(border){
            case 'top':
                this.ClearValue('border-top');
                break;
            case 'right':
                this.ClearValue('border-right');
                break;
            case 'bottom':
                this.ClearValue('border-bottom');
                break;
            case 'left':
                this.ClearValue('border-left');
                break;
        }
        SmartFormsAddNewVar.ApplyCustomCSS();
    }
}

