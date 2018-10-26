var RedNaoEmailEditor = /** @class */ (function () {
    function RedNaoEmailEditor() {
        var _this = this;
        this.AdvancedOptionsShown = false;
        this.ElementToShow = 'label';
        this.AddEmailIfValid = function (text, select, multiple) {
            if (text.trim() == "")
                return;
            if (!multiple && select.select2('val').length > 0) {
                alert('You can only have one email in this field');
                return;
            }
            if (sfRedNaoEmail.prototype.EmailIsValid(text))
                this.AddEmail(text, select);
            else
                alert('Please type a valid email');
        };
        this.SelectedEmail = null;
        this.FocusEventsInitialized = false;
        this.LastFocus = 'Body';
        this.FirstTimeLoaded = true;
        var self = this;
        //noinspection JSUnusedLocalSymbols
        rnJQuery('.sfEmailShowAdvancedOptions').click(function () {
            _this.ToggleAdvancedOptions();
        });
        this.Dialog = rnJQuery('#redNaoEmailEditor');
        this.Dialog.on('hide.bs.modal', function (e) {
            self.UpdateSelectedEmail();
            if (!self.EmailConfigurationIsValid()) {
                e.preventDefault();
                e.stopImmediatePropagation();
                return false;
            }
        });
        rnJQuery('.sfEmailElementToShow').change(function () {
            _this.ElementToShow = rnJQuery('.sfEmailElementToShow:checked').val();
            _this.RefreshElements();
        });
        /*
        this.Dialog = rnJQuery("#redNaoEmailEditor").dialog(
            {
                width: "800",
                height: "815",
                modal: true,
                autoOpen: false,
                create: function (event, ui) {
                    rnJQuery(event.target).closest('.ui-dialog').wrap('<div class="smartFormsSlider" />');

                },
                open: function (event, ui) {
                    rnJQuery('.ui-widget-overlay').wrap('<div class="smartFormsSlider" />');
                },
                beforeClose: function (event, ui) {
                    self.UpdateSelectedEmail();
                    return self.EmailConfigurationIsValid();

                }


            });*/
        eventManager.publishEvent('InitializeEmailDialog', this.Dialog);
    }
    RedNaoEmailEditor.prototype.InitializeFocusEvents = function () {
        var self = this;
        rntinymce.get('redNaoTinyMCEEditor').on('click', function (e) {
            self.LastFocus = 'Body';
        });
        rnJQuery('#redNaoFromName').focus(function () {
            self.LastFocus = 'FromName';
        });
        rnJQuery('#redNaoEmailSubject').focus(function () {
            self.LastFocus = 'Subject';
        });
        self.FocusEventsInitialized = true;
    };
    ;
    RedNaoEmailEditor.prototype.EmailConfigurationIsValid = function () {
        for (var i = 0; i < this.Emails.length; i++) {
            if (this.Emails[i].ToEmail.indexOf("[field") >= 0 || this.Emails[i].FromEmail.indexOf("[field") >= 0 || this.Emails[i].ReplyTo.indexOf("[field") >= 0) {
                if (!RedNaoLicensingManagerVar.LicenseIsValid('Sorry, you can\'t add fields to the "To Email", "From Email" or "Reply To" box in this version, please use only emails ')) {
                    return false;
                }
            }
        }
        return true;
    };
    ;
    RedNaoEmailEditor.prototype.SetUpFixedFields = function ($container, tinyMCEID) {
        for (var i = 0; i < smartFormsFixedFields.length; i++) {
            var button = this.CreateFixedFieldButton(smartFormsFixedFields[i], tinyMCEID);
            $container.append(button.wrap('<li></li>'));
        }
        // rnJQuery('#rnEmailCurrentDate').click(function(){RedNaoEmailEditorVar.AddFieldToEmail('{"Op":"CurrentDate", "Format":"m/d/y"}')});
    };
    ;
    RedNaoEmailEditor.prototype.CreateFixedFieldButton = function (buttonProperties, tinyMCEID) {
        var self = this;
        var button = rnJQuery("<a href=\"#\" class=\"list-group-item\"><strong>" + buttonProperties.Label + "</strong></a>" /*'<button>' + buttonProperties.Label + '</button>'*/);
        button.click(function () {
            self.ExecuteFixedFieldButton(buttonProperties, tinyMCEID);
        });
        return button;
    };
    ;
    RedNaoEmailEditor.prototype.ExecuteFixedFieldButton = function (buttonProperties, tinyMCEID) {
        var op = {};
        op.Op = buttonProperties.Op;
        //noinspection JSUnresolvedVariable
        for (var param in buttonProperties.Parameters) {
            op[param] = buttonProperties.Parameters[param];
        }
        RedNaoEmailEditorVar.AddFieldToEmail(JSON.stringify(op), tinyMCEID);
    };
    ;
    /*
        public UpdateFromEmail () {
            var selectedToEmails = rnJQuery('#redNaoFromEmail').select2('val');
            alert('adfasdfasdfdasf');
            var selectedEmailsString = "";
            for (var i = 0; i < selectedToEmails.length; i++) {
                if (selectedToEmails[i].indexOf("[field") == 0) {
                    if (!RedNaoLicensingManagerVar.LicenseIsValid('Sorry, you can\'t add fields to the "To Email" box in this version, please use only emails ')) {
                        return false;
                    }
                }
                selectedEmailsString = selectedToEmails[i];
            }
            this.Emails[0].FromEmail = selectedEmailsString;
    
            var selectedToEmails = rnJQuery('#redNaoFromEmail').select2('val');
    
            var selectedEmailsString = "";
            for (var i = 0; i < selectedToEmails.length; i++) {
                if (selectedToEmails[i].indexOf("[field") == 0) {
                    if (!RedNaoLicensingManagerVar.LicenseIsValid('Sorry, you can\'t add fields to the "To Email" box in this version, please use only emails ')) {
                        return false;
                    }
                }
                selectedEmailsString = selectedToEmails[i];
            }
            this.Emails[0].FromEmail = selectedEmailsString;
            return true;
        };*/
    RedNaoEmailEditor.prototype.UpdateSelectedEmail = function () {
        eventManager.publishEvent('UpdateSelectedEmail', { formElements: this.FormElements, email: this.SelectedEmail });
        var selectedToEmails = rnJQuery('#redNaoToEmail').select2('val');
        selectedToEmails = rnJQuery.unique(selectedToEmails);
        var selectedEmailsString = "";
        for (var i = 0; i < selectedToEmails.length; i++) {
            selectedEmailsString += selectedToEmails[i] + ",";
        }
        this.SelectedEmail.ToEmail = selectedEmailsString;
        selectedToEmails = rnJQuery('#redNaoFromEmail').select2('val');
        selectedEmailsString = "";
        for (var i = 0; i < selectedToEmails.length; i++) {
            selectedEmailsString = selectedToEmails[i];
        }
        var replyToEmails = rnJQuery('#redNaoReplyTo').select2('val');
        var replyToEmailString = "";
        for (var i = 0; i < replyToEmails.length; i++) {
            replyToEmailString = replyToEmails[i];
        }
        var bccEmails = rnJQuery('#redNaoBccEmail').select2('val');
        var bccEmailsEmailString = "";
        for (var i = 0; i < bccEmails.length; i++) {
            bccEmailsEmailString += bccEmails[i] + ',';
        }
        this.SelectedEmail.FromEmail = selectedEmailsString;
        this.SelectedEmail.ReplyTo = replyToEmailString;
        this.SelectedEmail.Bcc = bccEmailsEmailString;
        this.SelectedEmail.FromName = rnJQuery('#redNaoFromName').val();
        this.SelectedEmail.EmailSubject = rnJQuery('#redNaoEmailSubject').val();
        this.SelectedEmail.EmailText = this.SanitizeText(this.DecodeText(rntinymce.get('redNaoTinyMCEEditor').getContent()));
    };
    RedNaoEmailEditor.prototype.SetupEmailTo = function (emailToOptions, alreadySelectedEmails, jQuerySelect, callBack, multiple) {
        var selectOptions = '<optgroup label="' + smartFormsTranslation.SelectAField + '">';
        selectOptions += emailToOptions;
        for (var i = 0; i < alreadySelectedEmails.length; i++) {
            if (alreadySelectedEmails[i] == "") {
                alreadySelectedEmails.splice(i, 1);
                i--;
                continue;
            }
            if (alreadySelectedEmails[i].indexOf("[field") != 0) {
                selectOptions += '<option value="' + alreadySelectedEmails[i] + '">' + alreadySelectedEmails[i] + '</option>';
            }
        }
        selectOptions += '</optgroup>';
        var select2Options = {
            placeholder: "Type email or field (e.g. example@gmail.com)",
            allowClear: true
        };
        if (!multiple)
            select2Options.maximumSelectionSize = 1;
        var self = this;
        select2Options.formatSelection = function (state) {
            if (rnJQuery(state.element[0]).data('type') == 'multiple') {
                var id_1 = rnJQuery(state.element[0]).data('field-id');
                var $field = rnJQuery('<span style="color:blue;text-decoration: underline;cursor:pointer;">' + RedNaoEscapeHtml(state.text) + '</span>');
                $field.click(function () {
                    self.OpenMultipleOptionsFieldDialog(id_1);
                });
                return $field;
            }
            return state.text;
        };
        jQuerySelect.empty();
        jQuerySelect.append(selectOptions);
        jQuerySelect.select2(select2Options).unbind("dropdown-closed")
            .off("dropdown-closed")
            .on("dropdown-closed", function (event) {
            callBack(event.val);
        })
            .off('select2-selecting')
            .on('select2-selecting', function (event) {
            var $selectedOption = rnJQuery(event.object.element[0]);
            if ($selectedOption.data('type') == 'multiple') {
                var id = $selectedOption.data('field-id');
                event.preventDefault();
                jQuerySelect.select2('close');
                self.OpenMultipleOptionsFieldDialog(id);
            }
        });
        jQuerySelect.select2('val', alreadySelectedEmails);
        rnJQuery('#redNaoEmailEditor .select2-input').on('keyup', function (e) {
            if (e.which == 13 || e.which == 32) {
                var text = rnJQuery(this).val().trim();
                callBack(text);
            }
        });
    };
    ;
    RedNaoEmailEditor.prototype.OpenMultipleOptionsFieldDialog = function (fieldId) {
        var selectedMultipleOptions = this.SelectedEmail.MultipleOptionsToEmails[fieldId];
        if (typeof selectedMultipleOptions == 'undefined')
            selectedMultipleOptions = [];
        var fieldOptions = [];
        var selectedField;
        for (var i = 0; i < this.FormElements.length; i++) {
            if (this.FormElements[i].Id == fieldId) {
                selectedField = this.FormElements[i];
                fieldOptions = selectedField.Options.Options;
                break;
            }
        }
        var table = '<table class="table table-striped""><thead><tr><th>When this option is selected</th><th>Email To (If multiple emails, separate them with a comma)</th></tr></thead><tbody>';
        for (var i = 0; i < fieldOptions.length; i++) {
            var emails = '';
            for (var t = 0; t < selectedMultipleOptions.length; t++) {
                if (selectedMultipleOptions[t].Label == fieldOptions[i].label)
                    emails = selectedMultipleOptions[t].EmailTo;
            }
            table += ' <tr class="sfOptionRow">' +
                '        <td><label class="sfOptionLabel">' + RedNaoEscapeHtml(fieldOptions[i].label) + '</label></td>' +
                '        <td><input  class="sfEmailTo form-control" value="' + RedNaoEscapeHtml(emails) + '"/> </td>' +
                '       </tr>';
        }
        table += '</tbody></table>';
        var self = this;
        var $dialog = rnJQuery(table).RNDialog({
            ButtonClick: function (action, button) {
                if (action == 'accept')
                    self.AddMultipleOptionsEmail(fieldId, selectedField.Options.Label, $dialog);
            },
            Width: '750px',
            Buttons: [
                {
                    Label: 'Cancel',
                    Id: 'dialogCancel',
                    Style: 'danger',
                    Icon: 'glyphicon glyphicon-remove',
                    Action: 'cancel'
                },
                { Label: 'Apply', Id: 'dialogAccept', Style: 'success', Icon: 'glyphicon glyphicon-ok', Action: 'accept' }
            ]
        });
        $dialog.RNDialog('Show');
        $dialog.css('cssText', 'z-index:10007 !important');
        $dialog.parent().next().css('z-index', 10006);
    };
    ;
    RedNaoEmailEditor.prototype.AddMultipleOptionsEmail = function (fieldId, label, $dialog) {
        var optionRows = $dialog.find('.sfOptionRow');
        var options = [];
        for (var i = 0; i < optionRows.length; i++) {
            var $row = rnJQuery(optionRows[i]);
            options.push({
                Label: $row.find('.sfOptionLabel').text(),
                EmailTo: $row.find('.sfEmailTo').val()
            });
        }
        this.SelectedEmail.MultipleOptionsToEmails[fieldId] = options;
        $dialog.RNDialog('Hide');
        var field = '[field ' + fieldId + ']';
        var select = rnJQuery('#redNaoToEmail');
        var selectedValues = select.select2('val');
        selectedValues.push(field);
        select.select2('val', selectedValues);
    };
    ;
    RedNaoEmailEditor.prototype.AddEmail = function (email, select) {
        select.append(rnJQuery('<option>', { value: email, text: email }));
        var selectedValues = select.select2('val');
        selectedValues.push(email);
        select.select2('val', selectedValues);
    };
    ;
    RedNaoEmailEditor.prototype.OpenEmailEditor = function (redNaoFormElements, emails) {
        var _this = this;
        var self = this;
        this.Emails = emails;
        this.InitializeEmailEditor(rnJQuery('#redNaoEmailEditorComponent'), 'redNaoTinyMCEEditor', function () { _this.InitializeFocusEvents(); _this.OpenEmailEditor(redNaoFormElements, emails); }, redNaoFormElements);
        if (self.FirstTimeLoaded) {
            rnJQuery('#replyToTooltip').tooltip();
            rnJQuery('#fromEmailAddressTooltip').tooltip();
            self.FirstTimeLoaded = false;
            this.InitializeTabs(emails);
            return;
        }
        this.EmailToOptions = "";
        this.MultiSelectOptions = "";
        for (var i = 0; i < redNaoFormElements.length; i++) {
            if (redNaoFormElements[i].StoresInformation() && !redNaoFormElements[i].IsHandledByAnotherField() && !redNaoFormElements[i].IsFieldContainer) {
                if (redNaoFormElements[i].Options.ClassName == "rednaoemail")
                    this.EmailToOptions += '<option value="[field ' + redNaoFormElements[i].Options.Id + ']">' + redNaoFormElements[i].Options.Label + '</option>';
                if (redNaoFormElements[i].Options.ClassName == "rednaomultipleradios" || redNaoFormElements[i].Options.ClassName == "rednaomultiplecheckboxes"
                    || redNaoFormElements[i].Options.ClassName == "rednaoselectbasic" || redNaoFormElements[i].Options.ClassName == "rednaosearchablelist")
                    this.MultiSelectOptions += '<option data-type="multiple" data-field-id="' + redNaoFormElements[i].Options.Id + '" value="[field ' + redNaoFormElements[i].Options.Id + ']">' + redNaoFormElements[i].Options.Label + '</option>';
            }
        }
        RedNaoEventManager.Publish('ContextTutorialRequested', 1);
        this.SelectedEmail = null;
        this.Dialog.modal('show');
        if (typeof emails[0].Name == 'undefined') {
            emails[0].Name = 'Default';
        }
        rnJQuery('#redNaoEmailEditor .emailTabItem').first().find('a').click();
    };
    ;
    RedNaoEmailEditor.prototype.EmailSelected = function (email) {
        rnJQuery('#sfNotReceivingEmail').unbind('click');
        var self = this;
        rnJQuery('#sfNotReceivingEmail').click(function () {
            var emailIndex = 0;
            for (var i = 0; i < self.Emails.length; i++) {
                if (email == self.Emails[i])
                    emailIndex = i;
            }
            alert('Please make sure to save your form before using this feature, as the next page will use the latest saved information.');
            var url = smartFormsEmailDoctorUrl + '&action=debugemail&form_id=' + smartFormId + '&email_index=' + emailIndex;
            window.open(url, '_blank');
        });
        if (this.SelectedEmail != null)
            this.UpdateSelectedEmail();
        eventManager.publishEvent('EmailSelected', { formElements: this.FormElements, email: email });
        this.SelectedEmail = email;
        if (typeof this.SelectedEmail.MultipleOptionsToEmails == 'undefined')
            this.SelectedEmail.MultipleOptionsToEmails = {};
        rnJQuery('#redNaoFromName').val(email.FromName);
        rnJQuery('#redNaoEmailSubject').val(email.EmailSubject);
        this.SetupEmailTo(this.EmailToOptions + this.MultiSelectOptions, RedNaoGetValueOrEmpty(email.ToEmail).split(','), rnJQuery('#redNaoToEmail'), function (text) {
            self.AddEmailIfValid(text, rnJQuery('#redNaoToEmail'), true);
        }, true);
        this.SetupEmailTo(this.EmailToOptions, RedNaoGetValueOrEmpty(email.FromEmail).split(','), rnJQuery('#redNaoFromEmail'), function (text) {
            self.AddEmailIfValid(text, rnJQuery('#redNaoFromEmail'), false);
        }, false);
        this.SetupEmailTo(this.EmailToOptions, RedNaoGetValueOrEmpty(email.ReplyTo).split(','), rnJQuery('#redNaoReplyTo'), function (text) {
            self.AddEmailIfValid(text, rnJQuery('#redNaoReplyTo'), false);
        }, false);
        this.SetupEmailTo(this.EmailToOptions, RedNaoGetValueOrEmpty(email.Bcc).split(','), rnJQuery('#redNaoBccEmail'), function (text) {
            self.AddEmailIfValid(text, rnJQuery('#redNaoBccEmail'), true);
        }, true);
        rntinymce.get('redNaoTinyMCEEditor').setContent(this.EncodeText(email.EmailText));
    };
    ;
    RedNaoEmailEditor.prototype.CloseEmailEditor = function () {
        this.Dialog.modal('hide');
    };
    ;
    RedNaoEmailEditor.prototype.AddFieldToEmail = function (id, tinyMCEID) {
        var field = "[field " + id.trim() + "]";
        if (this.LastFocus == 'Body' || tinyMCEID != 'redNaoTinyMCEEditor') {
            if (id.indexOf('{') > -1) {
                rntinymce.get(tinyMCEID).execCommand('mceInsertContent', false, this.GetCalculatedFieldHtml(id));
            }
            else {
                var fieldOptions = this.GetFieldById(id);
                var label = fieldOptions.Options.Label;
                if (label.trim() == '')
                    label = fieldOptions.Options.Id;
                field = this.GetFieldHtml(id, label);
                rntinymce.get(tinyMCEID).execCommand('mceInsertContent', false, field);
            }
        }
        else {
            if (!RedNaoLicensingManagerVar.LicenseIsValid('Sorry, you can\'t add fields to the "subject", "From Name" or "Reply To" box in this version, please type the subject or From Name that you want to use')) {
                return false;
            }
            if (this.LastFocus == "Subject")
                rnJQuery('#redNaoEmailSubject').val(rnJQuery('#redNaoEmailSubject').val() + field).focus();
            else
                rnJQuery('#redNaoFromName').val(rnJQuery('#redNaoFromName').val() + field).focus();
        }
    };
    ;
    RedNaoEmailEditor.prototype.InitializeTabs = function (emails) {
        var _this = this;
        this.$tabsContainer = rnJQuery('#emailTabs');
        var addButton = rnJQuery("<li role=\"presentation tabItem\" id=\"sfAddTab\"><a href=\"#\">+</a></li>");
        addButton.find('a').click(function () {
            var newEmail = {
                FromName: '',
                FromEmail: '',
                Bcc: '',
                ToEmail: '',
                ReplyTo: '',
                EmailSubject: '',
                EmailText: '',
                MultipleOptionsToEmails: {},
                Name: 'New Email',
                Condition: { Use: "always", ConditionSettings: {} }
            };
            _this.Emails.push(newEmail);
            _this.AddEmailTab(newEmail, true);
        });
        this.$tabsContainer.append(addButton);
        for (var _i = 0, emails_1 = emails; _i < emails_1.length; _i++) {
            var email = emails_1[_i];
            if (typeof email.ReplyTo == "undefined") {
                if (email.FromEmail != '')
                    email.ReplyTo = email.FromEmail;
            }
            this.AddEmailTab(email, false);
        }
        this.$tabsContainer.find('li').first().addClass('active');
    };
    RedNaoEmailEditor.prototype.AddEmailTab = function (emailInfo, goToTab) {
        var _this = this;
        if (goToTab === void 0) { goToTab = false; }
        var hasCondition = typeof emailInfo.Condition != 'undefined' && emailInfo.Condition.Use == "condition";
        var tab = rnJQuery("<li role=\"presentation tabItem\" class=\"emailTabItem\">\n                                " + (this.Emails.length > 1 ? '<span class="sfCloseButton glyphicon glyphicon-remove"></span>' : '') + "\n                                <a href=\"#\">\n                                    <span class=\"emailConditionalLogic glyphicon glyphicon-link " + (hasCondition ? "sfHasCondition" : "") + "\"></span>\n                                    <span class=\"sfTabName\">" + emailInfo.Name + "</span>\n                                </a>\n                           </li>");
        tab.find('.sfCloseButton').click(function () {
            if (_this.Emails.length == 1) {
                alert('Sorry, this email can not be deleted');
                return;
            }
            if (confirm('Do you want to delete this email?')) {
                _this.Emails.splice(_this.Emails.indexOf(emailInfo), 1);
                rnJQuery('#redNaoEmailEditor .emailTabItem').first().find('a').click();
                tab.remove();
            }
        });
        tab.find('a').click(function () {
            _this.TabClicked(tab, emailInfo);
        });
        tab.find('a').dblclick(function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            _this.EditName(tab, emailInfo);
        });
        tab.find('.emailConditionalLogic').click(function () {
            _this.ExecuteConditionalLogic(emailInfo, tab);
        });
        tab.insertBefore(this.$tabsContainer.find('#sfAddTab'));
        if (goToTab)
            this.TabClicked(tab, emailInfo);
    };
    RedNaoEmailEditor.prototype.TabClicked = function (tab, emailInfo) {
        var _this = this;
        if (emailInfo == this.SelectedEmail)
            return;
        this.$tabsContainer.find('.active').removeClass('active');
        tab.addClass('active');
        var $emailContainer = rnJQuery('#emailContainer');
        $emailContainer.velocity({ opacity: 0 }, 200, 'easeOutExp', function () {
            _this.EmailSelected(emailInfo);
            $emailContainer.velocity({ opacity: 1 }, 200, 'easeInExp');
        });
    };
    RedNaoEmailEditor.prototype.EditName = function (tab, emailInfo) {
        tab.find('a,.sfCloseButton').css('display', 'none');
        var input = rnJQuery("<input style=\"max-width: 200px;\"/>");
        input.val(emailInfo.Name);
        var commitChanges = function () {
            emailInfo.Name = input.val();
            input.remove();
            tab.find('.sfTabName').text(emailInfo.Name);
            tab.find('a,.sfCloseButton').css('display', 'block');
        };
        input.change(commitChanges);
        input.focusout(commitChanges);
        tab.append(input);
        input.focus();
        input.select();
    };
    RedNaoEmailEditor.prototype.ExecuteConditionalLogic = function (emailInfo, tab) {
        if (typeof emailInfo.Condition == 'undefined')
            emailInfo.Condition = { Use: "always", ConditionSettings: {} };
        var pop = new SmartFormsPopUpWizard({
            Steps: [new SFEmailWizardCondition(this.FormElements)],
            SavedData: emailInfo,
            OnFinish: function (data) {
                if (data.Condition.Use == "always")
                    tab.find('.emailConditionalLogic').removeClass('sfHasCondition');
                else
                    tab.find('.emailConditionalLogic').addClass('sfHasCondition');
                console.log(data);
            }
        });
        pop.Show();
        pop.$Dialog.css('cssText', 'z-index:10007 !important');
        pop.$Dialog.parent().next().css('z-index', 10006);
    };
    RedNaoEmailEditor.prototype.ToggleAdvancedOptions = function () {
        if (this.AdvancedOptionsShown) {
            rnJQuery('.sfEmailShowAdvancedOptions').text('Show advanced options');
            rnJQuery('.sfEmailAdvancedOptions').velocity({ height: 0 }, 300, 'easeOutExp', function () { rnJQuery('.sfEmailAdvancedOptions').css('display', 'none'); });
        }
        else {
            rnJQuery('.sfEmailShowAdvancedOptions').text('Hide advanced options');
            rnJQuery('.sfEmailAdvancedOptions').css('opacity', '0');
            rnJQuery('.sfEmailAdvancedOptions').css('height', 'auto');
            rnJQuery('.sfEmailAdvancedOptions').css('position', 'absolute');
            rnJQuery('.sfEmailAdvancedOptions').css('display', 'block');
            var height = rnJQuery('.sfEmailAdvancedOptions').height();
            rnJQuery('.sfEmailAdvancedOptions').css('height', '0');
            rnJQuery('.sfEmailAdvancedOptions').css('opacity', '1');
            rnJQuery('.sfEmailAdvancedOptions').css('position', 'static');
            rnJQuery('.sfEmailAdvancedOptions').velocity({ height: height }, 300, 'easeInExp');
        }
        this.AdvancedOptionsShown = !this.AdvancedOptionsShown;
    };
    RedNaoEmailEditor.prototype.SanitizeText = function (content) {
        var reg = /\{\@((.(?!\@\}))*.)/g;
        var m;
        while (m = reg.exec(content)) {
            if (m[1].indexOf('&nbsp;') > -1) {
                content = content.replace(m[1], m[1].replace(/&nbsp;/g, ' '));
                reg.lastIndex = 0;
            }
        }
        return content;
    };
    RedNaoEmailEditor.prototype.EncodeText = function (text) {
        var re = /\[field ([^\]]*)\]/g;
        var m;
        while (m = re.exec(text)) {
            var id = m[1];
            var label = m[1];
            if (label.indexOf('{') > -1) {
                text = text.replace(m[0], this.GetCalculatedFieldHtml(label));
                re.lastIndex = 0;
                continue;
            }
            var field = this.GetFieldById(m[1]);
            if (field != null)
                label = field.Options.Label;
            if (label.trim() == '')
                label = field.Options.Id;
            text = text.replace(m[0], this.GetFieldHtml(m[1], label));
            re.lastIndex = 0;
            //text=text.replace(,this.CreateFieldTag(label,id));
        }
        text = text.replace(/\{\@/g, '<span contentEditable="false" class="SmartyDelimiter">{@</span>');
        text = text.replace(/\@\}/g, '<span contentEditable="false" class="SmartyDelimiter">@}</span>');
        return text;
    };
    RedNaoEmailEditor.prototype.GetCalculatedFieldHtml = function (options) {
        try {
            var fixedFieldOptions = rnJQuery.parseJSON(options);
            return "<span contentEditable='false' data-field-options=\"" + options.replace(/"/g, "&quot;") + "\" class='sfFixedField'>" + this.GetLabelByOPId(fixedFieldOptions.Op) + "</span>";
        }
        catch (exception) {
            return "<span contentEditable='false' data-field-options=\"" + options.replace(/"/g, "&quot;") + "\" class='sfFixedField'></span>";
            ;
        }
    };
    RedNaoEmailEditor.prototype.GetFieldHtml = function (fieldId, fieldLabel) {
        return "<span contentEditable='false' data-field-id=\"" + fieldId + "\" data-field-label=\"" + fieldLabel + "\" class='sfField'>" + (this.ElementToShow == 'label' ? fieldLabel : fieldId) + "</span>";
    };
    RedNaoEmailEditor.prototype.DecodeText = function (content) {
        var regEx = /<span [^\>]*class="sfField"[^>]*data-field-id="([^"]*)"(.(?!<\/span>))*.<\/span>/g;
        var m;
        while (m = regEx.exec(content)) {
            content = content.replace(m[0], '[field ' + m[1] + ']');
            regEx.lastIndex = 0;
        }
        regEx = /<span [^\>]*class="sfFixedField"[^>]*data-field-options="([^"]*)"(.(?!<\/span>))*.<\/span>/g;
        while (m = regEx.exec(content)) {
            content = content.replace(m[0], '[field ' + m[1].replace(/&quot;/g, "\"") + ']');
            regEx.lastIndex = 0;
        }
        regEx = /<span [^\>]*class="SmartyDelimiter"[^>]*>{@<\/span>/g;
        while (m = regEx.exec(content)) {
            content = content.replace(m[0], '{@');
            regEx.lastIndex = 0;
        }
        regEx = /<span [^\>]*class="SmartyDelimiter"[^>]*>@}<\/span>/g;
        while (m = regEx.exec(content)) {
            content = content.replace(m[0], '@}');
            regEx.lastIndex = 0;
        }
        console.log(content);
        return content;
    };
    RedNaoEmailEditor.prototype.GetFieldById = function (id) {
        for (var _i = 0, _a = this.FormElements; _i < _a.length; _i++) {
            var field = _a[_i];
            if (field.Id == id)
                return field;
        }
        return null;
    };
    RedNaoEmailEditor.prototype.GetLabelByOPId = function (opId) {
        for (var _i = 0, smartFormsFixedFields_1 = smartFormsFixedFields; _i < smartFormsFixedFields_1.length; _i++) {
            var operation = smartFormsFixedFields_1[_i];
            if (operation.Op == opId)
                return operation.Label;
        }
        return 'Unknown Operation';
    };
    RedNaoEmailEditor.prototype.RefreshElements = function () {
        var self = this;
        rnJQuery('#redNaoEmailEditor #redNaoTinyMCEEditor_ifr').contents().find('body').find('.sfField').each(function () {
            if (self.ElementToShow == 'label')
                rnJQuery(this).text(rnJQuery(this).data('field-label'));
            else
                rnJQuery(this).text(rnJQuery(this).data('field-id'));
        });
    };
    RedNaoEmailEditor.prototype.InitializeConditions = function ($container, emailEditorId) {
        this.AddCondition($container, emailEditorId, 'If', 'When you receive the email and hit reply your reply will be send to this email', this.EncodeText('{@if &lt;Put_Your_Field_Here&gt; eq \'value\'@}<br/>Here goes whatever you whatever you want to add when the condition is true<br/>{@/if@}'));
        //<a href="#" class="list-group-item"><strong>If <span id="replyToTooltip" style="margin-left: 2px;cursor:hand;cursor:pointer;" data-toggle="tooltip" data-placement="right" title="" class="glyphicon glyphicon-question-sign" data-original-title=" When you receive the email and hit reply your reply will be send to this email"></span></strong></a>
    };
    RedNaoEmailEditor.prototype.AddCondition = function ($container, emailEditorId, label, tooltip, code) {
        var $item = rnJQuery("<a href=\"#\" class=\"list-group-item\">\n                                <strong>" + label + " </strong>\n                                \n                           </a>");
        $item.insertBefore($container.find('.clearer'));
        $item.find('.glyphicon-question-sign').tooltip();
        $item.click(function () {
            rntinymce.get(emailEditorId).execCommand('mceInsertContent', false, code);
        });
    };
    RedNaoEmailEditor.prototype.InitializeEmailEditor = function ($container, tinyMCEID, onLoad, redNaoFormElements) {
        if (!$container.data('loaded')) {
            $container.data('loaded', true);
            $container.append("<div id=\"redNaoEmailEditorComponent\" style=\"min-width: 760px;margin-top:15px;\">\n                                        <div style=\"width:80%;float:left;\">\n                                            <button type=\"button\" class=\"button rnAddMedia\"><span class=\"wp-media-buttons-icon\"></span> Add Media</button>\n                                            <textarea id=\"" + tinyMCEID + "\"></textarea>\n                                        </div>\n\n                                        <div class=\"smartFormsSlider\" style=\"float:right;width: 20%;max-height: 500px;overflow: auto;\">\n                                            <div class=\"styleGroup\">\n                                                <div class=\"sfStyleTitle\">\n                                                    <h5>\n                                                        <a data-toggle=\"collapse\" href=\"#redNaoEmailFormFields_" + tinyMCEID + "\" ><span class=\"sfAccordionIcon glyphicon glyphicon-chevron-right\"></span>Form Fields</a>\n                                                    </h5>\n                                                </div>\n                                                <div class=\"sfStyleContainer sfFormFields collapse in\" style=\"padding:0;\"  id=\"redNaoEmailFormFields_" + tinyMCEID + "\"><div  style=\"padding:0;margin:0;\"  class=\"list-group redNaoEmailFormFields\" ></div><div class=\"clearer\" style=\"clear:both;\"></div></div>\n                                            </div>\n                                            <div class=\"styleGroup\">\n                                                <div class=\"sfStyleTitle\">\n                                                    <h5>\n                                                        <a data-toggle=\"collapse\" href=\"#redNaoEmailFormFixedFields_" + tinyMCEID + "\" class=\"collapsed\"><span class=\"sfAccordionIcon glyphicon glyphicon-chevron-right\"></span>Fixed Values</a>\n                                                    </h5>\n                                                </div>\n                                                <div class=\"sfStyleContainer collapse sfFormFields redNaoEmailFormFixedFields\" style=\"padding:0;\"  id=\"redNaoEmailFormFixedFields_" + tinyMCEID + "\"><div class=\"clearer\" style=\"clear:both;\"></div></div>\n                                            </div>\n                                            <div class=\"styleGroup\">\n                                                <div class=\"sfStyleTitle\">\n                                                    <h5>\n                                                        <a data-toggle=\"collapse\" href=\"#redNaoEmailConditions_" + tinyMCEID + "\" class=\"collapsed\"><span class=\"sfAccordionIcon glyphicon glyphicon-chevron-right\"></span>Conditions</a>\n                                                    </h5>\n                                                </div>\n                                                <div class=\"sfStyleContainer collapse sfFormFields redNaoEmailConditions\" id=\"redNaoEmailConditions_" + tinyMCEID + "\" style=\"padding:0;\" >\n                                                    <div class=\"clearer\" style=\"clear:both;\"></div>\n                                                </div>\n                                            </div>                                       \n                                        </div>\n                                    </div>");
            rntinymce.init({
                content_css: smartFormsRootPath + 'css/tinymce/emailEditor.css',
                relative_urls: false,
                convert_urls: false,
                selector: '#' + tinyMCEID,
                menubar: false,
                plugins: "code link table textcolor",
                toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table | fontsizeselect | code link forecolor ",
                setup: function (ed) {
                    ed.on('init', function (args) {
                        onLoad();
                        //self.OpenEmailEditor(redNaoFormElements, emails);
                    });
                }
            });
            this.InitializeConditions(rnJQuery('.redNaoEmailConditions'), tinyMCEID);
            this.SetUpFixedFields(rnJQuery('.redNaoEmailFormFixedFields'), tinyMCEID);
            $container.find('.rnAddMedia').click(function () {
                if (wp.media.frames.sfmediaPost == undefined) {
                    wp.media.frames.sfmediaPost = wp.media({
                        title: "Select a file to add",
                        multiple: false,
                        button: {
                            text: 'Insert file'
                        }
                    });
                    wp.media.frames.sfmediaPost.on('select', function () {
                        var selection = wp.media.frames.sfmediaPost.state().get('selection');
                        if (selection == null)
                            return;
                        selection.each(function (attachment) {
                            rntinymce.get(tinyMCEID).execCommand('mceInsertContent', false, '<img src="' + attachment.attributes.url + '">');
                        });
                    });
                }
                wp.media.frames.sfmediaPost.open();
            });
        }
        var formList = $container.find('.redNaoEmailFormFields');
        formList.empty();
        this.FormElements = redNaoFormElements;
        for (var i = 0; i < redNaoFormElements.length; i++) {
            if (redNaoFormElements[i].StoresInformation() && !redNaoFormElements[i].IsHandledByAnotherField()) {
                var label = redNaoFormElements[i].Options.Label;
                if (label.trim() == '')
                    label = redNaoFormElements[i].Options.Id;
                formList.append("<a href=\"#\" class=\"list-group-item\" onclick=\"RedNaoEmailEditorVar.AddFieldToEmail('" + redNaoFormElements[i].Options.Id + "','" + tinyMCEID + "')\"  title=\"" + redNaoFormElements[i].Options.Id + "\" ><strong>" + label + "</strong></a>" /*'<li><button onclick="RedNaoEmailEditorVar.AddFieldToEmail(\'' + redNaoFormElements[i].Options.Id + '\');">' + redNaoFormElements[i].Options.Label + '</button></li>'*/);
            }
        }
    };
    return RedNaoEmailEditor;
}());
window.RedNaoEmailEditorVar = null;
rnJQuery(function () {
    window.RedNaoEmailEditorVar = new RedNaoEmailEditor();
});
//# sourceMappingURL=email-editor.js.map