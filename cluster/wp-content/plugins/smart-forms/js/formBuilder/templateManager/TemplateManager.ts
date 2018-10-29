export namespace SmartFormsModules {
    declare var window:any;
    declare var smartFormsPreviewUrl;
    declare var sfIsIE:()=>any;
    export class TemplateManager{
        private $templateContainer:JQuery;
        private $formList:JQuery;
        private $contactForm:JQuery;
        private $form:JQuery=null;
        constructor(){
            this.$templateContainer=rnJQuery('<div class="sfTemplateContainer" style="padding: 5px;"><h1 style="margin-left:2px;">Select a form template </h1><hr/><div class="formList" style="margin:5px;"></div></div>');
            this.$formList=this.$templateContainer.find('.formList');
            this.ShowTemplateManager();
        }

        public ShowTemplateManager() {

            this.$templateContainer.css('transform-origin','top left');
            rnJQuery('#sfMainContainer').css('overflow','hidden');
            this.$templateContainer.velocity({rotateZ:'-90deg'},1,"easeInExp",()=>{
                rnJQuery('#loadingScreen').after(this.$templateContainer);
                this.$templateContainer.velocity({rotateZ:'0deg'},600,"easeInExp", () => {
                    this.$templateContainer.removeAttr('style');
                    this.AddForms();
                    setTimeout(()=>{
                        this.$contactForm=rnJQuery("<div style='margin-top:50px;visibility:hidden;position:relative;' class='bootstrap-wrapper'><h2 style='margin-left:2px;'><a class='contactFormClick' style='cursor: pointer;'>Didn't find the right template? Do you have a suggestion or question? Let us know!</a></h2></div>");
                        this.$contactForm.find('.contactFormClick').click(()=>this.ShowContactForm());
                        this.$templateContainer.append(this.$contactForm);
                        let width=this.$contactForm.outerWidth();
                        this.$contactForm.css('left',-width);
                        this.$contactForm.css('visibility','visible');
                        this.$contactForm.velocity({left:0},300,"easeInExp");

                    },700);
                });
            });






        }

        private AddForms() {
            this.GenerateFormPreview('BlankForm','Empty Form');
            this.GenerateFormPreview('BasicContactForm','Basic Contact Form');
            this.GenerateFormPreview('ServicePriceCalculation','Service Price Calculation');
            this.GenerateFormPreview('ReservationForm','Reservation Form');
            this.GenerateFormPreview('SurveyForm','Survey Form');
            this.GenerateFormPreview('Review','Review Form');


        }

        private GenerateFormPreview(id:string,title:string):void {
            let $preview:JQuery=rnJQuery(`<div class="sfTemplateItem"><div class="sfImage"><img src="${smartFormsRootPath}js/formBuilder/templateManager/templates/${id}.png"></div><hr style="margin:1px 0 0 0 ;"/><div class="sfText"><h2>${title}</h2></div></div>`);
            if(id!='BlankForm')
            {
                let $previewButton=rnJQuery('<div class="sfPreviewButton bootstrap-wrapper"><span class="fa fa-search"></span> Click here to preview</div>');
                $previewButton.click((e:JQueryMouseEventObject)=>{this.ExecutePreview(e,id);});
                $preview.append($previewButton);
            }
            $preview.data('form-type',id);

            $preview.click(()=>{this.FormClicked($preview)});
            $preview.velocity({scale:'0'},0,"easeInExp",()=>{
                this.$formList.append($preview);
                $preview.velocity({scale:'1'},300,"easeInExp",()=>{});
            });


            /*$preview.addClass('sfHidden');
            $preview.removeClass('sfHidden');*/
        }

        private FormClicked($preview:JQuery) {
            var type=$preview.data('form-type');
            if(type=='BlankForm')
            {
                SmartFormsAddNewVar.LoadForm();
                rnJQuery('#smartFormsLoadingLogo').remove();
                window.SmartFormsAddNewTutorial.Initialize(SmartFormsAddNewVar,rnJQuery('.sfTemplateContainer'));
                //this.CloseTemplateManager();
                return;
            }

            rnJQuery.getJSON(smartFormsRootPath+'js/formBuilder/templateManager/templates/'+type+'.json',(e)=>{
                window.smartFormId=0;
                window.smartFormsOptions=rnJQuery.parseJSON(e.form_options);
                window.smartFormsElementOptions=rnJQuery.parseJSON(e.element_options);
                window.smartFormClientOptions=rnJQuery.parseJSON(e.client_form_options);
                SmartFormsAddNewVar.LoadForm();
                rnJQuery('#smartFormsLoadingLogo').remove();
                window.SmartFormsAddNewTutorial.Initialize(SmartFormsAddNewVar,rnJQuery('.sfTemplateContainer'));
                //this.CloseTemplateManager();
            })
        }

        private CloseTemplateManager() {
            rnJQuery('#rootContentDiv').removeClass('OpHidden');
            rnJQuery('#loadingScreen').remove();
            this.$templateContainer.velocity({opacity:0},"easeInExp",()=>{this.$templateContainer.remove();});
        }

        private ExecutePreview(e:JQueryMouseEventObject,type) {
            e.preventDefault();
            e.stopImmediatePropagation();
            window.preview=null;
            var preview=window.open(smartFormsPreviewUrl);

            rnJQuery.getJSON(smartFormsRootPath+'js/formBuilder/templateManager/templates/'+type+'.json',(e)=>{
                window.smartFormId=0;
                window.smartFormsOptions=rnJQuery.parseJSON(e.form_options);
                window.smartFormsElementOptions=rnJQuery.parseJSON(e.element_options);
                window.smartFormClientOptions=rnJQuery.parseJSON(e.client_form_options);



                var self=this;
                if(window.sfIsIE())
                {
                    var ieIsLoaded = function ()
                    {
                        var body = preview.document.getElementsByTagName('body');
                        if (body[0] == null)
                        {
                            //page not yet ready
                            setTimeout(ieIsLoaded, 10);
                        } else
                        {
                            preview.onload = function ()
                            {
                                window.preview=preview;
                                self.OpenPreview(e.form_options,e.element_options,e.client_form_options);
                            }
                        }
                    };
                    ieIsLoaded();
                    return;
                }

                preview[preview.addEventListener ? 'addEventListener' : 'attachEvent'](
                    (preview.attachEvent ? 'on' : '') + 'load',function ()
                    {
                        window.preview=preview;
                        self.OpenPreview(e.form_options,e.element_options,e.client_form_options);
                    }, false);

            });
        }

        OpenPreview(formOptions:any,elementOptions:any,clientFormOptions:any) {
            window.preview.LoadPreview({ 'form_id':0,  'elements':rnJQuery.parseJSON(elementOptions),'client_form_options':rnJQuery.parseJSON(clientFormOptions),'container':'formContainersfpreviewcontainer'},false);
        }

        private ShowContactForm() {
            if(this.$form==null)
            {
                this.$form=rnJQuery('<div id="redNaoContactForm" style="margin:0 20px 0 5px;overflow:hidden;visibility:hidden;" class="formelements bootstrap-wrapper exptop"><div class="rednao-control-group form-group row rednaotextarea col-sm-12 sfRequired" id="rnField5"><div class="rednao_label_container col-sm-3"><label class="rednao_control_label" for="textarea">Breefly describe us what we can do to help</label></div>                <div class="redNaoControls col-sm-9">                <textarea placeholder="I want to do a survey form that has..." style="" name="textarea" class="form-control redNaoTextAreaInput "></textarea></div></div><div class="rednao-control-group form-group row rednaotextinput col-sm-12 sfRequired" id="rnField6"><div class="rednao_label_container col-sm-3"><label class="rednao_control_label">If you have an example of a form you want to replicate, send us a link to that form</label></div><div class="redNaoControls col-sm-9"><input style="" name="Or_if you have an example, send us a link to the form that you want to replicate" type="text" placeholder="http://ExampleOfTheAwesomeForm.com" class="form-control redNaoInputText " value=""></div></div><div class="rednao-control-group form-group row rednaoemail col-sm-12 sfRequired" id="rnField7"><div class="rednao_label_container col-sm-3"><label class="rednao_control_label ">Lastly, where can we reach you?</label></div><div class="redNaoControls col-sm-9"><input name="Lastly,_where can we reach you?" type="text" placeholder="Your@Email.com" class="form-control redNaoInputText redNaoEmail"></div></div><div class="rednao-control-group form-group row rednaosubmissionbutton col-sm-12" id="rnField3"><div class="rednao_label_container col-sm-3"></div><div class="redNaoControls col-sm-9"><button class="redNaoSubmitButton btn btn-normal ladda-button"><span class="glyphicon glyphicon-send "></span><span class="ladda-label">Send</span></button></div></div></div>');
                this.initializeForm();
                this.$contactForm.append(this.$form);
                var height=this.$form.outerHeight();
                this.$form.css('height',0);
                this.$form.css('visibility','visible');
                this.$form.velocity({'height':height},300,"easeInExp");
            }


        }

        private initializeForm() {
            this.$form.find('.redNaoSubmitButton').click(()=>{
                var description=this.$form.find('textarea').val();
                var url=this.$form.find('#rnField6 input[type="text"]').val();
                var email=this.$form.find('#rnField7 input[type="text"]').val();

                if(email=="")
                {
                    alert("Email is required, please don't forget to fill it.");
                    return;
                }

                if(description==""&&url=="") {
                    alert('Either a description of the form or a link to an example is required, please fill it.');
                    return;
                }

                this.$form.find('.redNaoSubmitButton').html('<span class="glyphicon glyphicon-send "></span><span class="ladda-label">Sending form</span>').attr('disabled','disabled');
                rnJQuery.post('https://smartforms.rednao.com/templateformrequest.php',{'description':description,'url':url,'email':email},(res)=>{
                   if(res=='1')
                   {
                       alert('Request submitted successfully! we will contact you soon');
                       this.$form.find('.redNaoSubmitButton').removeAttr('disabled');
                   }else{
                       alert('Sorry an error ocurred, please try again later');
                       this.$form.find('.redNaoSubmitButton').removeAttr('disabled');
                   }
                });


            });
        }
    }
}