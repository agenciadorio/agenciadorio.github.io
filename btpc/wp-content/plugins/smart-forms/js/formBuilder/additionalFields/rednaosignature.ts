
namespace SmartFormsFields {
    export class rednaosignature extends sfFormElementBase<any>{
        private IsDynamicField=true;
        private amount:number=0;
        constructor(options:any,serverOptions:any)
        {
            super(options,serverOptions);
            if(this.IsNew)
            {
                this.Options.ClassName='rednaosignature';
                this.Options.Label='Signature';
                this.Options.CustomCSS='';
                this.Options.Height=150;
            }

            rnJQuery(window).resize(()=>{
                this.ExecuteResize();
            });

        }

        GetValueString():any {
            var result=this.JQueryElement.find('.signatureContainer').jSignature('getData','svgbase64')[1];
            let image=this.JQueryElement.find('.signatureContainer').jSignature('getData','image');
            if(image!=null&&image.length>1)
                image=image[1];
            return {value:result,native:this.JQueryElement.find('.signatureContainer').jSignature('getData','base30')[1],image:image};
        }

        SetData(data: any) {
            if(typeof data.native!='undefined'&&data.native!='')
            {
                this.JQueryElement.find('.signatureContainer').jSignature('setData',"data:image/jsignature;base30,"+data.native);
            }
        }

        IsValid(): boolean {

            if(this.Options.IsRequired=='y'&&typeof rnJQuery('.signatureContainer').jSignature('getData','native')[0]=='undefined')
            {
                rnJQuery('#'+this.Id).addClass('has-error');
                this.AddError('root',this.InvalidInputMessage);
            }
            else
                this.RemoveError('root');

            return this.InternalIsValid();
        }

        ExecuteResize(){
            let width:number=this.JQueryElement.find('.redNaoControls').width();
            let data=this.JQueryElement.find('.signatureContainer').jSignature('getData','base30');
            this.JQueryElement.find('.signatureContainer').empty().jSignature({width:width,height:this.Options.Height});
            this.JQueryElement.find('.signatureContainer').jSignature('setData',"data:image/jsignature;base30,"+data[1]);
        }

        GenerationCompleted($element: any) {
            let width:number=this.JQueryElement.find('.redNaoControls').width();
            this.JQueryElement.find('.signatureContainer').jSignature({width:width,height:this.Options.Height});
            this.JQueryElement.find('.sfClearSignature').click(()=>{
                this.JQueryElement.find('.signatureContainer').jSignature('reset');
            });

        }

        GenerateInlineElement():string {
            var select=`<div class="rednao_label_container col-sm-3">
                            <label class="rednao_control_label " >${RedNaoEscapeHtml(this.Options.Label)}</label>
                         </div>
                         <div class="redNaoControls col-sm-9">
                            <div class="signatureAndClearContainer">
                                 <a href="#" class="btn btn-danger sfClearSignature"><span class="glyphicon glyphicon-trash" title="Clear"></span></a>
                                 <div class="signatureContainer"></div>
                            </div>
                        </div>
                        `;
            return select;
        }

        CreateProperties() {


            this.Properties.push(new PropertyContainer('general','General').AddProperties([
                new SimpleTextProperty(this,this.Options,"Label","Label",{ManipulatorType:'basic'}),
                new CheckBoxProperty(this,this.Options,"IsRequired","Required",{ManipulatorType:'basic'})
            ]));

            this.Properties.push(new PropertyContainer('icons','Icons and Tweaks').AddProperties([
                new SimpleTextProperty(this,this.Options,"Height","Height",{ManipulatorType:'basic'})
            ]));


            this.Properties.push(new PropertyContainer('advanced','Advanced').AddProperties([
                new CustomCSSProperty(this,this.Options)
            ]));
        }


    }


}
