declare let eventManager: any;
declare let rntinymce:any;
declare let smartFormsFixedFields:any;
declare let RedNaoEmailEditorVar:any;
declare let smartFormsTranslation:any;
declare let sfRedNaoEmail;
declare let wp:any;
declare let smartFormsEmailDoctorUrl:any;
declare let smartFormId:any;
declare let SmartFormsPopUpWizard:any;
declare let SFEmailWizardCondition:any;
declare function RedNaoGetValueOrEmpty(any);
class RedNaoEmailEditor
{
    public SelectedEmail:EmailInfo;
    public FocusEventsInitialized:boolean;
    public LastFocus:string;
    public FirstTimeLoaded:boolean;
    public Dialog:JQuery;
    public Emails:EmailInfo[];
    public FormElements:sfFormElementBase<any>[];
    public MultiSelectOptions:any;
    public EmailToOptions:any;

    public $tabsContainer:JQuery;
    public AdvancedOptionsShown:boolean=false;
    public ElementToShow:string='label';

    constructor()
    {
        this.SelectedEmail = null;
        this.FocusEventsInitialized = false;
        this.LastFocus = 'Body';
        this.FirstTimeLoaded = true;
        let self = this;
        //noinspection JSUnusedLocalSymbols
        rnJQuery('.sfEmailShowAdvancedOptions').click(()=>{
            this.ToggleAdvancedOptions();
        });
        this.Dialog=rnJQuery('#redNaoEmailEditor');
        this.Dialog.on('hide.bs.modal',(e)=>{
            self.UpdateSelectedEmail();
            if(!self.EmailConfigurationIsValid()){
                e.preventDefault();
                e.stopImmediatePropagation();
                return false;
            }
        });

        rnJQuery('.sfEmailElementToShow').change(()=>{
            this.ElementToShow=rnJQuery('.sfEmailElementToShow:checked').val();
            this.RefreshElements();
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

    public  InitializeFocusEvents() {
        let self = this;
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

    public EmailConfigurationIsValid () {
        for (let i = 0; i < this.Emails.length; i++) {
            if (this.Emails[i].ToEmail.indexOf("[field") >= 0 || this.Emails[i].FromEmail.indexOf("[field") >= 0|| this.Emails[i].ReplyTo.indexOf("[field") >= 0) {
                if (!RedNaoLicensingManagerVar.LicenseIsValid('Sorry, you can\'t add fields to the "To Email", "From Email" or "Reply To" box in this version, please use only emails ')) {
                    return false;
                }
            }
        }
        return true;

    };

    public SetUpFixedFields($container,tinyMCEID) {
        for (let i = 0; i < smartFormsFixedFields.length; i++) {
            let button = this.CreateFixedFieldButton(smartFormsFixedFields[i],tinyMCEID);
            $container.append(button.wrap('<li></li>'));
        }
        // rnJQuery('#rnEmailCurrentDate').click(function(){RedNaoEmailEditorVar.AddFieldToEmail('{"Op":"CurrentDate", "Format":"m/d/y"}')});

    };

    public CreateFixedFieldButton(buttonProperties,tinyMCEID) {
        let self = this;
        let button = rnJQuery(`<a href="#" class="list-group-item"><strong>${ buttonProperties.Label}</strong></a>` /*'<button>' + buttonProperties.Label + '</button>'*/);
        button.click(function () {
            self.ExecuteFixedFieldButton(buttonProperties,tinyMCEID)
        });
        return button;
    };

    public ExecuteFixedFieldButton(buttonProperties,tinyMCEID) {
        let op:any = {};
        op.Op = buttonProperties.Op;
        //noinspection JSUnresolvedVariable
        for (let param in buttonProperties.Parameters) {
            op[param] = buttonProperties.Parameters[param];
        }
        RedNaoEmailEditorVar.AddFieldToEmail(JSON.stringify(op),tinyMCEID);
    };
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


    public UpdateSelectedEmail  () {
        eventManager.publishEvent('UpdateSelectedEmail', {formElements: this.FormElements, email: this.SelectedEmail});
        let selectedToEmails = rnJQuery('#redNaoToEmail').select2('val');
        selectedToEmails = rnJQuery.unique(selectedToEmails);
        let selectedEmailsString = "";
        for (let i = 0; i < selectedToEmails.length; i++) {
            selectedEmailsString += selectedToEmails[i] + ",";
        }
        this.SelectedEmail.ToEmail = selectedEmailsString;


        selectedToEmails = rnJQuery('#redNaoFromEmail').select2('val');
        selectedEmailsString = "";
        for (let i = 0; i < selectedToEmails.length; i++) {
            selectedEmailsString = selectedToEmails[i];
        }

        let replyToEmails = rnJQuery('#redNaoReplyTo').select2('val');
        let replyToEmailString = "";
        for (let i = 0; i < replyToEmails.length; i++) {
            replyToEmailString = replyToEmails[i];
        }

        let bccEmails = rnJQuery('#redNaoBccEmail').select2('val');
        let bccEmailsEmailString = "";
        for (let i = 0; i < bccEmails.length; i++) {
            bccEmailsEmailString += bccEmails[i]+',';
        }

        this.SelectedEmail.FromEmail = selectedEmailsString;
        this.SelectedEmail.ReplyTo=replyToEmailString;
        this.SelectedEmail.Bcc=bccEmailsEmailString;
        this.SelectedEmail.FromName = rnJQuery('#redNaoFromName').val();
        this.SelectedEmail.EmailSubject = rnJQuery('#redNaoEmailSubject').val();
        this.SelectedEmail.EmailText = this.SanitizeText(this.DecodeText(rntinymce.get('redNaoTinyMCEEditor').getContent()));
    }

    public SetupEmailTo  (emailToOptions, alreadySelectedEmails, jQuerySelect, callBack, multiple) {
        let selectOptions = '<optgroup label="' + smartFormsTranslation.SelectAField + '">';
        selectOptions += emailToOptions;

        for (let i = 0; i < alreadySelectedEmails.length; i++) {
            if (alreadySelectedEmails[i] == "") {
                alreadySelectedEmails.splice(i, 1);
                i--;
                continue;
            }
            if (alreadySelectedEmails[i].indexOf("[field") != 0) {
                selectOptions += '<option value="' + alreadySelectedEmails[i] + '">' + alreadySelectedEmails[i] + '</option>'
            }
        }

        selectOptions += '</optgroup>';


        let select2Options:any = {
            placeholder: "Type email or field (e.g. example@gmail.com)",
            allowClear: true
        };

        if (!multiple)
            select2Options.maximumSelectionSize = 1;

        let self = this;
        select2Options.formatSelection = function (state) {
            if (rnJQuery(state.element[0]).data('type') == 'multiple') {
                let id = rnJQuery(state.element[0]).data('field-id');
                let $field = rnJQuery('<span style="color:blue;text-decoration: underline;cursor:pointer;">' + RedNaoEscapeHtml(state.text) + '</span>');
                $field.click(function () {
                    self.OpenMultipleOptionsFieldDialog(id);
                });

                return $field;

            }
            return state.text;
        };

        jQuerySelect.empty();
        jQuerySelect.append(selectOptions);
        jQuerySelect.select2(
            select2Options
        ).unbind("dropdown-closed")
            .off("dropdown-closed")
            .on("dropdown-closed", function (event) {
                callBack(event.val);

            })
            .off('select2-selecting')
            .on('select2-selecting', function (event) {
                let $selectedOption = rnJQuery(event.object.element[0]);
                if ($selectedOption.data('type') == 'multiple') {
                    let id = $selectedOption.data('field-id');
                    event.preventDefault();
                    jQuerySelect.select2('close');
                    self.OpenMultipleOptionsFieldDialog(id);
                }
            });

        jQuerySelect.select2('val', alreadySelectedEmails);
        rnJQuery('#redNaoEmailEditor .select2-input').on('keyup', function (e) {
            if (e.which == 13 || e.which == 32) {

                let text = rnJQuery(this).val().trim();
                callBack(text);

            }
        });
    };

    public OpenMultipleOptionsFieldDialog  (fieldId) {
        let selectedMultipleOptions = this.SelectedEmail.MultipleOptionsToEmails[fieldId];
        if (typeof selectedMultipleOptions == 'undefined')
            selectedMultipleOptions = [];

        let fieldOptions = [];
        let selectedField;
        for (let i = 0; i < this.FormElements.length; i++) {
            if (this.FormElements[i].Id == fieldId) {
                selectedField = this.FormElements[i];
                fieldOptions = selectedField.Options.Options;
                break;
            }
        }

        let table = '<table class="table table-striped""><thead><tr><th>When this option is selected</th><th>Email To (If multiple emails, separate them with a comma)</th></tr></thead><tbody>';
        for (let i = 0; i < fieldOptions.length; i++) {
            let emails = '';
            for (let t = 0; t < selectedMultipleOptions.length; t++) {
                if (selectedMultipleOptions[t].Label == fieldOptions[i].label)
                    emails = selectedMultipleOptions[t].EmailTo;
            }
            table += ' <tr class="sfOptionRow">' +
                '        <td><label class="sfOptionLabel">' + RedNaoEscapeHtml(fieldOptions[i].label) + '</label></td>' +
                '        <td><input  class="sfEmailTo form-control" value="' + RedNaoEscapeHtml(emails) + '"/> </td>' +
                '       </tr>';
        }
        table += '</tbody></table>';
        let self = this;
        let $dialog = rnJQuery(table).RNDialog({
            ButtonClick: function (action, button) {
                if (action == 'accept') self.AddMultipleOptionsEmail(fieldId, selectedField.Options.Label, $dialog);
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
                {Label: 'Apply', Id: 'dialogAccept', Style: 'success', Icon: 'glyphicon glyphicon-ok', Action: 'accept'}
            ]

        });

        $dialog.RNDialog('Show');
        $dialog.css('cssText','z-index:10007 !important');
        $dialog.parent().next().css('z-index',10006)

    };

    public AddMultipleOptionsEmail  (fieldId, label, $dialog) {
        let optionRows = $dialog.find('.sfOptionRow');
        let options = [];
        for (let i = 0; i < optionRows.length; i++) {
            let $row = rnJQuery(optionRows[i]);
            options.push({
                Label: $row.find('.sfOptionLabel').text(),
                EmailTo: $row.find('.sfEmailTo').val()

            });
        }

        this.SelectedEmail.MultipleOptionsToEmails[fieldId] = options;

        $dialog.RNDialog('Hide');
        let field = '[field ' + fieldId + ']';

        let select = rnJQuery('#redNaoToEmail');
        let selectedValues = select.select2('val');
        selectedValues.push(field);
        select.select2('val', selectedValues);

    };

    public AddEmailIfValid = function (text, select, multiple) {
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

    public AddEmail(email, select) {
        select.append(rnJQuery('<option>', {value: email, text: email}));
        let selectedValues = select.select2('val');
        selectedValues.push(email);
        select.select2('val', selectedValues);

    };

    public OpenEmailEditor(redNaoFormElements, emails) {
        let self = this;
        this.Emails = emails;
        this.InitializeEmailEditor(rnJQuery('#redNaoEmailEditorComponent'),'redNaoTinyMCEEditor',()=>{this.InitializeFocusEvents();this.OpenEmailEditor(redNaoFormElements,emails)},redNaoFormElements);
        if (self.FirstTimeLoaded) {
            rnJQuery('#replyToTooltip').tooltip();
            rnJQuery('#fromEmailAddressTooltip').tooltip();
            self.FirstTimeLoaded = false;
            this.InitializeTabs(emails);
            return;
        }

        this.EmailToOptions = "";
        this.MultiSelectOptions = "";

        for (let i = 0; i < redNaoFormElements.length; i++) {
            if (redNaoFormElements[i].StoresInformation()&&!redNaoFormElements[i].IsHandledByAnotherField()&&!redNaoFormElements[i].IsFieldContainer) {
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

    public EmailSelected(email) {

        rnJQuery('#sfNotReceivingEmail').unbind('click');
        let self = this;
        rnJQuery('#sfNotReceivingEmail').click(function () {
            let emailIndex = 0;
            for (let i = 0; i < self.Emails.length; i++) {
                if (email == self.Emails[i])
                    emailIndex = i;
            }

            alert('Please make sure to save your form before using this feature, as the next page will use the latest saved information.');
            let url = smartFormsEmailDoctorUrl + '&action=debugemail&form_id=' + smartFormId + '&email_index=' + emailIndex;
            window.open(url, '_blank');

        });

        if (this.SelectedEmail != null)
            this.UpdateSelectedEmail();
        eventManager.publishEvent('EmailSelected', {formElements: this.FormElements, email: email});
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

    public CloseEmailEditor() {
        this.Dialog.modal('hide');
    };

    public AddFieldToEmail (id,tinyMCEID) {

        let field = "[field " + id.trim() + "]";

        if (this.LastFocus == 'Body' || tinyMCEID != 'redNaoTinyMCEEditor') {
            if (id.indexOf('{') > -1) {
                rntinymce.get(tinyMCEID).execCommand('mceInsertContent', false, this.GetCalculatedFieldHtml(id));
            } else {
                let fieldOptions = this.GetFieldById(id);
                let label:string=fieldOptions.Options.Label;
                if(label.trim()=='')
                    label=fieldOptions.Options.Id;
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

    private InitializeTabs(emails: EmailInfo[]) {
        this.$tabsContainer=rnJQuery('#emailTabs');
        let addButton:JQuery=rnJQuery(`<li role="presentation tabItem" id="sfAddTab"><a href="#">+</a></li>`);
        addButton.find('a').click(()=>{
            let newEmail:EmailInfo={
                FromName : '',
                FromEmail : '',
                Bcc:'',
                ToEmail : '',
                ReplyTo:'',
                EmailSubject : '',
                EmailText : '',
                MultipleOptionsToEmails : {},
                Name:'New Email',
                Condition:{Use:"always",ConditionSettings:{}}
            };
            this.Emails.push(newEmail);
            this.AddEmailTab(newEmail,true);

        });
        this.$tabsContainer.append(addButton);
        for(let email of emails) {
            if(typeof email.ReplyTo=="undefined")
            {
                if(email.FromEmail!='')
                    email.ReplyTo=email.FromEmail;
            }
            this.AddEmailTab(email, false);
        }


        this.$tabsContainer.find('li').first().addClass('active');

    }

    public AddEmailTab(emailInfo:EmailInfo,goToTab:boolean=false)
    {
        let hasCondition=typeof emailInfo.Condition!='undefined'&&emailInfo.Condition.Use=="condition";
        let tab=rnJQuery(`<li role="presentation tabItem" class="emailTabItem">
                                ${(this.Emails.length>1?'<span class="sfCloseButton glyphicon glyphicon-remove"></span>':'')}
                                <a href="#">
                                    <span class="emailConditionalLogic glyphicon glyphicon-link ${hasCondition?"sfHasCondition":""}"></span>
                                    <span class="sfTabName">${emailInfo.Name}</span>
                                </a>
                           </li>`);
        tab.find('.sfCloseButton').click(()=>{
           if(this.Emails.length==1)
           {
               alert('Sorry, this email can not be deleted');
               return;
           }
           if(confirm('Do you want to delete this email?'))
           {
               this.Emails.splice(this.Emails.indexOf(emailInfo),1);
               rnJQuery('#redNaoEmailEditor .emailTabItem').first().find('a').click();
               tab.remove();
           }


        });
        tab.find('a').click(()=>{
           this.TabClicked(tab,emailInfo);
        });
        tab.find('a').dblclick((e)=>{
            e.preventDefault();
            e.stopImmediatePropagation();
           this.EditName(tab,emailInfo);
        });

        tab.find('.emailConditionalLogic').click(()=>{
            this.ExecuteConditionalLogic(emailInfo,tab);
        });

        tab.insertBefore(this.$tabsContainer.find('#sfAddTab'));
        if(goToTab)
            this.TabClicked(tab,emailInfo);


    }

    private TabClicked(tab:JQuery,emailInfo: EmailInfo) {
        if(emailInfo==this.SelectedEmail)
            return;
        this.$tabsContainer.find('.active').removeClass('active');
        tab.addClass('active');
        let $emailContainer=rnJQuery('#emailContainer');
        $emailContainer.velocity({opacity:0},200,'easeOutExp',()=>{
            this.EmailSelected(emailInfo);
            $emailContainer.velocity({opacity:1},200,'easeInExp');
        });



    }

    private EditName(tab: JQuery, emailInfo: EmailInfo) {
        tab.find('a,.sfCloseButton').css('display','none');
        let input=rnJQuery(`<input style="max-width: 200px;"/>`);
        input.val(emailInfo.Name);
        let commitChanges=()=>{
            emailInfo.Name=input.val();
            input.remove();
            tab.find('.sfTabName').text(emailInfo.Name);
            tab.find('a,.sfCloseButton').css('display','block');
        };
        input.change(commitChanges);
        input.focusout(commitChanges);
        tab.append(input);
        input.focus();
        input.select();


    }

    private ExecuteConditionalLogic(emailInfo: EmailInfo, tab: JQuery) {
        if(typeof emailInfo.Condition=='undefined')
            emailInfo.Condition={Use:"always",ConditionSettings:{}};
        let pop=new SmartFormsPopUpWizard(
            {
                Steps:[new SFEmailWizardCondition(this.FormElements)],
                SavedData:emailInfo,
                OnFinish:function(data:EmailInfo){
                    if(data.Condition.Use=="always")
                        tab.find('.emailConditionalLogic').removeClass('sfHasCondition');
                    else
                        tab.find('.emailConditionalLogic').addClass('sfHasCondition');
                    console.log(data);
                }
            });
        pop.Show();
        pop.$Dialog.css('cssText','z-index:10007 !important');
        pop.$Dialog.parent().next().css('z-index',10006)
    }

    private ToggleAdvancedOptions() {

        if(this.AdvancedOptionsShown){
            rnJQuery('.sfEmailShowAdvancedOptions').text('Show advanced options');
            rnJQuery('.sfEmailAdvancedOptions').velocity({height:0},300,'easeOutExp',()=>{rnJQuery('.sfEmailAdvancedOptions').css('display','none');})
        }else{
            rnJQuery('.sfEmailShowAdvancedOptions').text('Hide advanced options');
            rnJQuery('.sfEmailAdvancedOptions').css('opacity','0');
            rnJQuery('.sfEmailAdvancedOptions').css('height','auto');
            rnJQuery('.sfEmailAdvancedOptions').css('position','absolute');
            rnJQuery('.sfEmailAdvancedOptions').css('display','block');
            let height=rnJQuery('.sfEmailAdvancedOptions').height();
            rnJQuery('.sfEmailAdvancedOptions').css('height','0');
            rnJQuery('.sfEmailAdvancedOptions').css('opacity','1');
            rnJQuery('.sfEmailAdvancedOptions').css('position','static');
            rnJQuery('.sfEmailAdvancedOptions').velocity({height:height},300,'easeInExp')
        }

        this.AdvancedOptionsShown=!this.AdvancedOptionsShown;


    }



    private SanitizeText(content: string):string {
        let reg=/\{\@((.(?!\@\}))*.)/g;
        let m;
        while(m=reg.exec(content))
        {
            if(m[1].indexOf('&nbsp;')>-1)
            {
                content=content.replace(m[1],m[1].replace(/&nbsp;/g,' '));
                reg.lastIndex=0;
            }

        }
        return content;
    }

    private EncodeText(text: string | string) {
        let re=/\[field ([^\]]*)\]/g;
        let m;
        while(m=re.exec(text))
        {
            let id=m[1];
            let label=m[1];

            if(label.indexOf('{')>-1)
            {
                text=text.replace(m[0],this.GetCalculatedFieldHtml(label));
                re.lastIndex=0;
                continue;
            }
            let field=this.GetFieldById(m[1]);
            if(field!=null)
                label=field.Options.Label;

            if(label.trim()=='')
                label=field.Options.Id;

            text=text.replace(m[0],this.GetFieldHtml(m[1],label));
            re.lastIndex=0;
            //text=text.replace(,this.CreateFieldTag(label,id));

        }


        text=text.replace(/\{\@/g,'<span contentEditable="false" class="SmartyDelimiter">{@</span>');
        text=text.replace(/\@\}/g,'<span contentEditable="false" class="SmartyDelimiter">@}</span>');

        return text;
    }

    private GetCalculatedFieldHtml(options:any):string{
        try{
            let fixedFieldOptions=rnJQuery.parseJSON(options);
            return `<span contentEditable='false' data-field-options="${options.replace(/"/g, "&quot;")}" class='sfFixedField'>${this.GetLabelByOPId(fixedFieldOptions.Op)}</span>`;
        }catch (exception){
            return `<span contentEditable='false' data-field-options="${options.replace(/"/g, "&quot;")}" class='sfFixedField'></span>`;;
        }
    }

    private GetFieldHtml(fieldId:string,fieldLabel:string):string{
        return `<span contentEditable='false' data-field-id="${fieldId}" data-field-label="${fieldLabel}" class='sfField'>${this.ElementToShow=='label'?fieldLabel:fieldId}</span>`
    }

    private DecodeText(content: string):string {
        let regEx=/<span [^\>]*class="sfField"[^>]*data-field-id="([^"]*)"(.(?!<\/span>))*.<\/span>/g;
        let m;
        while(m=regEx.exec(content)){
            content=content.replace(m[0],'[field '+m[1]+']');
            regEx.lastIndex=0;
        }

        regEx=/<span [^\>]*class="sfFixedField"[^>]*data-field-options="([^"]*)"(.(?!<\/span>))*.<\/span>/g;
        while(m=regEx.exec(content)){
            content=content.replace(m[0],'[field '+m[1].replace(/&quot;/g,"\"")+']');
            regEx.lastIndex=0;
        }

        regEx=/<span [^\>]*class="SmartyDelimiter"[^>]*>{@<\/span>/g;
        while(m=regEx.exec(content)){
            content=content.replace(m[0],'{@');
            regEx.lastIndex=0;
        }

        regEx=/<span [^\>]*class="SmartyDelimiter"[^>]*>@}<\/span>/g;
        while(m=regEx.exec(content)){
            content=content.replace(m[0],'@}');
            regEx.lastIndex=0;
        }

        console.log(content);
        return content;

    }

    private GetFieldById(id:string):sfFormElementBase<any> {
        for(let field of this.FormElements)
            if(field.Id==id)
                return field;
        return null;
    }

    private GetLabelByOPId(opId: string) {
        for(let operation of smartFormsFixedFields)
        {
            if(operation.Op==opId)
                return operation.Label;
        }
        return 'Unknown Operation';
    }

    private RefreshElements() {
        let self=this;
        rnJQuery('#redNaoEmailEditor #redNaoTinyMCEEditor_ifr').contents().find('body').find('.sfField').each(function(){
           if(self.ElementToShow=='label')
               rnJQuery(this).text(rnJQuery(this).data('field-label'));
            else
               rnJQuery(this).text(rnJQuery(this).data('field-id'));
        });

    }

    private InitializeConditions($container,emailEditorId) {
        this.AddCondition($container,emailEditorId,'If','When you receive the email and hit reply your reply will be send to this email',this.EncodeText('{@if &lt;Put_Your_Field_Here&gt; eq \'value\'@}<br/>Here goes whatever you whatever you want to add when the condition is true<br/>{@/if@}'));
        //<a href="#" class="list-group-item"><strong>If <span id="replyToTooltip" style="margin-left: 2px;cursor:hand;cursor:pointer;" data-toggle="tooltip" data-placement="right" title="" class="glyphicon glyphicon-question-sign" data-original-title=" When you receive the email and hit reply your reply will be send to this email"></span></strong></a>
    }

    private AddCondition($container,emailEditorId,label:string,tooltip:string,code:string) {
        let $item=rnJQuery(`<a href="#" class="list-group-item">
                                <strong>${label} </strong>
                                
                           </a>`);
        $item.insertBefore($container.find('.clearer'));
        $item.find('.glyphicon-question-sign').tooltip();
        $item.click(()=>{
            rntinymce.get(emailEditorId).execCommand('mceInsertContent', false, code);
        });
    }

    private InitializeEmailEditor($container: JQuery,tinyMCEID:string,onLoad:()=>void,redNaoFormElements:sfFormElementBase<any>[]) {
        if(!$container.data('loaded')) {
            $container.data('loaded',true);
            $container.append(`<div id="redNaoEmailEditorComponent" style="min-width: 760px;margin-top:15px;">
                                        <div style="width:80%;float:left;">
                                            <button type="button" class="button rnAddMedia"><span class="wp-media-buttons-icon"></span> Add Media</button>
                                            <textarea id="${tinyMCEID}"></textarea>
                                        </div>

                                        <div class="smartFormsSlider" style="float:right;width: 20%;max-height: 500px;overflow: auto;">
                                            <div class="styleGroup">
                                                <div class="sfStyleTitle">
                                                    <h5>
                                                        <a data-toggle="collapse" href="#redNaoEmailFormFields_${tinyMCEID}" ><span class="sfAccordionIcon glyphicon glyphicon-chevron-right"></span>Form Fields</a>
                                                    </h5>
                                                </div>
                                                <div class="sfStyleContainer sfFormFields collapse in" style="padding:0;"  id="redNaoEmailFormFields_${tinyMCEID}"><div  style="padding:0;margin:0;"  class="list-group redNaoEmailFormFields" ></div><div class="clearer" style="clear:both;"></div></div>
                                            </div>
                                            <div class="styleGroup">
                                                <div class="sfStyleTitle">
                                                    <h5>
                                                        <a data-toggle="collapse" href="#redNaoEmailFormFixedFields_${tinyMCEID}" class="collapsed"><span class="sfAccordionIcon glyphicon glyphicon-chevron-right"></span>Fixed Values</a>
                                                    </h5>
                                                </div>
                                                <div class="sfStyleContainer collapse sfFormFields redNaoEmailFormFixedFields" style="padding:0;"  id="redNaoEmailFormFixedFields_${tinyMCEID}"><div class="clearer" style="clear:both;"></div></div>
                                            </div>
                                            <div class="styleGroup">
                                                <div class="sfStyleTitle">
                                                    <h5>
                                                        <a data-toggle="collapse" href="#redNaoEmailConditions_${tinyMCEID}" class="collapsed"><span class="sfAccordionIcon glyphicon glyphicon-chevron-right"></span>Conditions</a>
                                                    </h5>
                                                </div>
                                                <div class="sfStyleContainer collapse sfFormFields redNaoEmailConditions" id="redNaoEmailConditions_${tinyMCEID}" style="padding:0;" >
                                                    <div class="clearer" style="clear:both;"></div>
                                                </div>
                                            </div>                                       
                                        </div>
                                    </div>`);

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
            this.InitializeConditions(rnJQuery('.redNaoEmailConditions'),tinyMCEID);
            this.SetUpFixedFields(rnJQuery('.redNaoEmailFormFixedFields'),tinyMCEID);


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
                        let selection = wp.media.frames.sfmediaPost.state().get('selection');
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


        let formList = $container.find('.redNaoEmailFormFields');
        formList.empty();
        this.FormElements = redNaoFormElements;
        for (let i = 0; i < redNaoFormElements.length; i++) {
            if (redNaoFormElements[i].StoresInformation()&&!redNaoFormElements[i].IsHandledByAnotherField()) {
                let label:string=redNaoFormElements[i].Options.Label;
                if(label.trim()=='')
                    label=redNaoFormElements[i].Options.Id;
                formList.append(`<a href="#" class="list-group-item" onclick="RedNaoEmailEditorVar.AddFieldToEmail('${redNaoFormElements[i].Options.Id }','${tinyMCEID}')"  title="${redNaoFormElements[i].Options.Id}" ><strong>${label}</strong></a>`/*'<li><button onclick="RedNaoEmailEditorVar.AddFieldToEmail(\'' + redNaoFormElements[i].Options.Id + '\');">' + redNaoFormElements[i].Options.Label + '</button></li>'*/);
            }
        }
    }

}

interface EmailInfo{
    ToEmail:string;
    Bcc:string;
    FromEmail:string;
    FromName:string;
    EmailSubject:string;
    EmailText:string;
    ReplyTo:string;
    MultipleOptionsToEmails:any;
    Name:string;
    Condition:EmailCondition
}

interface EmailCondition{
    Use:'always'|'condition';
    ConditionSettings:{};
}

(<any>window).RedNaoEmailEditorVar=null;
rnJQuery(function(){
    (<any>window).RedNaoEmailEditorVar=new RedNaoEmailEditor();
});