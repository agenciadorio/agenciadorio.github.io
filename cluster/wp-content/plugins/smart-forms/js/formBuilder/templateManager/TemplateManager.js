"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var SmartFormsModules;
(function (SmartFormsModules) {
    var TemplateManager = /** @class */ (function () {
        function TemplateManager() {
            this.$form = null;
            this.$templateContainer = rnJQuery('<div class="sfTemplateContainer" style="padding: 5px;"><h1 style="margin-left:2px;">Select a form template </h1><hr/><div class="formList" style="margin:5px;"></div></div>');
            this.$formList = this.$templateContainer.find('.formList');
            this.ShowTemplateManager();
        }
        TemplateManager.prototype.ShowTemplateManager = function () {
            var _this = this;
            this.$templateContainer.css('transform-origin', 'top left');
            rnJQuery('#sfMainContainer').css('overflow', 'hidden');
            this.$templateContainer.velocity({ rotateZ: '-90deg' }, 1, "easeInExp", function () {
                rnJQuery('#loadingScreen').after(_this.$templateContainer);
                _this.$templateContainer.velocity({ rotateZ: '0deg' }, 600, "easeInExp", function () {
                    _this.$templateContainer.removeAttr('style');
                    _this.AddForms();
                    setTimeout(function () {
                        _this.$contactForm = rnJQuery("<div style='margin-top:50px;visibility:hidden;position:relative;' class='bootstrap-wrapper'><h2 style='margin-left:2px;'><a class='contactFormClick' style='cursor: pointer;'>Didn't find the right template? Do you have a suggestion or question? Let us know!</a></h2></div>");
                        _this.$contactForm.find('.contactFormClick').click(function () { return _this.ShowContactForm(); });
                        _this.$templateContainer.append(_this.$contactForm);
                        var width = _this.$contactForm.outerWidth();
                        _this.$contactForm.css('left', -width);
                        _this.$contactForm.css('visibility', 'visible');
                        _this.$contactForm.velocity({ left: 0 }, 300, "easeInExp");
                    }, 700);
                });
            });
        };
        TemplateManager.prototype.AddForms = function () {
            this.GenerateFormPreview('BlankForm', 'Empty Form');
            this.GenerateFormPreview('BasicContactForm', 'Basic Contact Form');
            this.GenerateFormPreview('ServicePriceCalculation', 'Service Price Calculation');
            this.GenerateFormPreview('ReservationForm', 'Reservation Form');
            this.GenerateFormPreview('SurveyForm', 'Survey Form');
            this.GenerateFormPreview('Review', 'Review Form');
        };
        TemplateManager.prototype.GenerateFormPreview = function (id, title) {
            var _this = this;
            var $preview = rnJQuery("<div class=\"sfTemplateItem\"><div class=\"sfImage\"><img src=\"" + smartFormsRootPath + "js/formBuilder/templateManager/templates/" + id + ".png\"></div><hr style=\"margin:1px 0 0 0 ;\"/><div class=\"sfText\"><h2>" + title + "</h2></div></div>");
            if (id != 'BlankForm') {
                var $previewButton = rnJQuery('<div class="sfPreviewButton bootstrap-wrapper"><span class="fa fa-search"></span> Click here to preview</div>');
                $previewButton.click(function (e) { _this.ExecutePreview(e, id); });
                $preview.append($previewButton);
            }
            $preview.data('form-type', id);
            $preview.click(function () { _this.FormClicked($preview); });
            $preview.velocity({ scale: '0' }, 0, "easeInExp", function () {
                _this.$formList.append($preview);
                $preview.velocity({ scale: '1' }, 300, "easeInExp", function () { });
            });
            /*$preview.addClass('sfHidden');
            $preview.removeClass('sfHidden');*/
        };
        TemplateManager.prototype.FormClicked = function ($preview) {
            var type = $preview.data('form-type');
            if (type == 'BlankForm') {
                SmartFormsAddNewVar.LoadForm();
                rnJQuery('#smartFormsLoadingLogo').remove();
                window.SmartFormsAddNewTutorial.Initialize(SmartFormsAddNewVar, rnJQuery('.sfTemplateContainer'));
                //this.CloseTemplateManager();
                return;
            }
            rnJQuery.getJSON(smartFormsRootPath + 'js/formBuilder/templateManager/templates/' + type + '.json', function (e) {
                window.smartFormId = 0;
                window.smartFormsOptions = rnJQuery.parseJSON(e.form_options);
                window.smartFormsElementOptions = rnJQuery.parseJSON(e.element_options);
                window.smartFormClientOptions = rnJQuery.parseJSON(e.client_form_options);
                SmartFormsAddNewVar.LoadForm();
                rnJQuery('#smartFormsLoadingLogo').remove();
                window.SmartFormsAddNewTutorial.Initialize(SmartFormsAddNewVar, rnJQuery('.sfTemplateContainer'));
                //this.CloseTemplateManager();
            });
        };
        TemplateManager.prototype.CloseTemplateManager = function () {
            var _this = this;
            rnJQuery('#rootContentDiv').removeClass('OpHidden');
            rnJQuery('#loadingScreen').remove();
            this.$templateContainer.velocity({ opacity: 0 }, "easeInExp", function () { _this.$templateContainer.remove(); });
        };
        TemplateManager.prototype.ExecutePreview = function (e, type) {
            var _this = this;
            e.preventDefault();
            e.stopImmediatePropagation();
            window.preview = null;
            var preview = window.open(smartFormsPreviewUrl);
            rnJQuery.getJSON(smartFormsRootPath + 'js/formBuilder/templateManager/templates/' + type + '.json', function (e) {
                window.smartFormId = 0;
                window.smartFormsOptions = rnJQuery.parseJSON(e.form_options);
                window.smartFormsElementOptions = rnJQuery.parseJSON(e.element_options);
                window.smartFormClientOptions = rnJQuery.parseJSON(e.client_form_options);
                var self = _this;
                if (window.sfIsIE()) {
                    var ieIsLoaded = function () {
                        var body = preview.document.getElementsByTagName('body');
                        if (body[0] == null) {
                            //page not yet ready
                            setTimeout(ieIsLoaded, 10);
                        }
                        else {
                            preview.onload = function () {
                                window.preview = preview;
                                self.OpenPreview(e.form_options, e.element_options, e.client_form_options);
                            };
                        }
                    };
                    ieIsLoaded();
                    return;
                }
                preview[preview.addEventListener ? 'addEventListener' : 'attachEvent']((preview.attachEvent ? 'on' : '') + 'load', function () {
                    window.preview = preview;
                    self.OpenPreview(e.form_options, e.element_options, e.client_form_options);
                }, false);
            });
        };
        TemplateManager.prototype.OpenPreview = function (formOptions, elementOptions, clientFormOptions) {
            window.preview.LoadPreview({ 'form_id': 0, 'elements': rnJQuery.parseJSON(elementOptions), 'client_form_options': rnJQuery.parseJSON(clientFormOptions), 'container': 'formContainersfpreviewcontainer' }, false);
        };
        TemplateManager.prototype.ShowContactForm = function () {
            if (this.$form == null) {
                this.$form = rnJQuery('<div id="redNaoContactForm" style="margin:0 20px 0 5px;overflow:hidden;visibility:hidden;" class="formelements bootstrap-wrapper exptop"><div class="rednao-control-group form-group row rednaotextarea col-sm-12 sfRequired" id="rnField5"><div class="rednao_label_container col-sm-3"><label class="rednao_control_label" for="textarea">Breefly describe us what we can do to help</label></div>                <div class="redNaoControls col-sm-9">                <textarea placeholder="I want to do a survey form that has..." style="" name="textarea" class="form-control redNaoTextAreaInput "></textarea></div></div><div class="rednao-control-group form-group row rednaotextinput col-sm-12 sfRequired" id="rnField6"><div class="rednao_label_container col-sm-3"><label class="rednao_control_label">If you have an example of a form you want to replicate, send us a link to that form</label></div><div class="redNaoControls col-sm-9"><input style="" name="Or_if you have an example, send us a link to the form that you want to replicate" type="text" placeholder="http://ExampleOfTheAwesomeForm.com" class="form-control redNaoInputText " value=""></div></div><div class="rednao-control-group form-group row rednaoemail col-sm-12 sfRequired" id="rnField7"><div class="rednao_label_container col-sm-3"><label class="rednao_control_label ">Lastly, where can we reach you?</label></div><div class="redNaoControls col-sm-9"><input name="Lastly,_where can we reach you?" type="text" placeholder="Your@Email.com" class="form-control redNaoInputText redNaoEmail"></div></div><div class="rednao-control-group form-group row rednaosubmissionbutton col-sm-12" id="rnField3"><div class="rednao_label_container col-sm-3"></div><div class="redNaoControls col-sm-9"><button class="redNaoSubmitButton btn btn-normal ladda-button"><span class="glyphicon glyphicon-send "></span><span class="ladda-label">Send</span></button></div></div></div>');
                this.initializeForm();
                this.$contactForm.append(this.$form);
                var height = this.$form.outerHeight();
                this.$form.css('height', 0);
                this.$form.css('visibility', 'visible');
                this.$form.velocity({ 'height': height }, 300, "easeInExp");
            }
        };
        TemplateManager.prototype.initializeForm = function () {
            var _this = this;
            this.$form.find('.redNaoSubmitButton').click(function () {
                var description = _this.$form.find('textarea').val();
                var url = _this.$form.find('#rnField6 input[type="text"]').val();
                var email = _this.$form.find('#rnField7 input[type="text"]').val();
                if (email == "") {
                    alert("Email is required, please don't forget to fill it.");
                    return;
                }
                if (description == "" && url == "") {
                    alert('Either a description of the form or a link to an example is required, please fill it.');
                    return;
                }
                _this.$form.find('.redNaoSubmitButton').html('<span class="glyphicon glyphicon-send "></span><span class="ladda-label">Sending form</span>').attr('disabled', 'disabled');
                rnJQuery.post('https://smartforms.rednao.com/templateformrequest.php', { 'description': description, 'url': url, 'email': email }, function (res) {
                    if (res == '1') {
                        alert('Request submitted successfully! we will contact you soon');
                        _this.$form.find('.redNaoSubmitButton').removeAttr('disabled');
                    }
                    else {
                        alert('Sorry an error ocurred, please try again later');
                        _this.$form.find('.redNaoSubmitButton').removeAttr('disabled');
                    }
                });
            });
        };
        return TemplateManager;
    }());
    SmartFormsModules.TemplateManager = TemplateManager;
})(SmartFormsModules = exports.SmartFormsModules || (exports.SmartFormsModules = {}));
//# sourceMappingURL=TemplateManager.js.map