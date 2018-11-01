function SfMultipleStepsBase(options,$container,formElements,formGenerator)
{
    this.$Container=$container;
    this.FormElements=formElements;
    this.$StepForm=null;
    this.StepCatalog=null;
    this.SortedSteps=null;
    this.FormGenerator=formGenerator;
    this.$CurrentErrorMessage=null;
    if(options==null)
        this.Options={
            Steps:[],
            LatestId:0,
            PreviousText:'Prev',
            NextText:'Next',
            CompleteText:'Complete'

        };
    else
    {
        this.Options=options;
        if(typeof this.Options.PreviousText=='undefined')
            this.Options.PreviousText='Prev';
        if(typeof this.Options.NextText=='undefined')
            this.Options.NextText='Next';
        if(typeof this.Options.CompleteText=='undefined')
            this.Options.CompleteText='Complete';
    }



}

SfMultipleStepsBase.prototype.Generate=function()
{
    this.$Container.empty();
    this.$StepForm=rnJQuery('<div class="fuelux">'+
                        '<div class="wizard">'+
                            '<div class="steps-container">'+
                                '<ul class="steps" style="margin-left: 0">'+
                                '</ul>'+
                            '</div>'+
                            '<div class="step-content">'+
                            '</div>'+
                            '<div class="actions">'+
                            '<button class="btn btn-default btn-prev redNaoMSButton rnPrevButton" disabled="disabled"><span class="glyphicon glyphicon-arrow-left"></span>'+this.Options.PreviousText+'</button>'+
                            '<button class="btn btn-default btn-next redNaoMSButton rnNextButton" data-last="'+this.Options.CompleteText+'">'+this.Options.NextText+'<span class="glyphicon glyphicon-arrow-right"></span></button>'+
                            '</div>'+
                        '</div>'+
                '</div>');
    this.$Container.append(this.$StepForm);

    this.CreateSteps();
    this.CreateFields();

    this.$StepForm.wizard();
    var self=this;
    this.$StepForm.on('finished.fu.wizard',function(e){
        e.preventDefault();
        self.FormCompleted();
    });
    //noinspection SpellCheckingInspection
    this.$StepForm.on('actionclicked.fu.wizard',function(e,data){
        if(data.direction=='next')
        {
            self.MoveToTop();
            if (!self.ProcessCurrentStep())
                e.preventDefault();
            else
                for(var i=0;i<self.FormElements.length;i++)
                    self.FormElements[i].DestroyPopOver();
        }
    });
    this.GenerationCompleted();
};


SfMultipleStepsBase.prototype.MoveToTop=function()
{
   /* try
    {
        var scroll = this.FormGenerator.JQueryForm.offset();
        if ((window.pageYOffset + window.innerHeight) > scroll.top)
            rnJQuery('html, body').animate({scrollTop: scroll.top}, 200);
    }catch(err)
    {

    }*/
};


SfMultipleStepsBase.prototype.FormCompleted=function()
{
    if(!this.ProcessCurrentStep())
        return false;

    this.FormGenerator.JQueryForm.submit();

};

SfMultipleStepsBase.prototype.ProcessCurrentStep=function()
{
    var currentStep=this.GetCurrentStep();

    return this.StepIsValid(currentStep);

};

SfMultipleStepsBase.prototype.StepIsValid=function(step)
{
    this.FormGenerator.GetRootContainer().find('.redNaoValidationMessage').remove();
    this.FormGenerator.GetRootContainer().find('.redNaoInputText,.redNaoRealCheckBox,.redNaoInputRadio,.redNaoInputCheckBox,.redNaoSelect,.redNaoTextArea,.redNaoInvalid,.has-error').removeClass('redNaoInvalid').removeClass('has-error');
    RedNaoEventManager.Publish('BeforeValidatingForm',{Generator:this});
    var firstInvalidField=null;
    var isValid=true;
    for(var i=0;i<step.Fields.length;i++)
    {
        step.Fields[i].ClearInvalidStyle();
        if (!step.Fields[i].IsIgnored() && !step.Fields[i].IsValid())
        {
            isValid = false;
            if (firstInvalidField == null)
                firstInvalidField = step.Fields[i];
        }
    }
    if(this.$CurrentErrorMessage!=null)
    {
        this.$CurrentErrorMessage.slideUp('1000','easeOutQuint');
        this.$CurrentErrorMessage=null;
    }

    if(!isValid)
    {
        this.$CurrentErrorMessage=rnJQuery(
            '<div class="alert alert-danger" role="alert" style="display: none;margin-bottom: 0;clear:both;">'+
                '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>'+
                '<span class="sr-only">Error:</span>'+
                   RedNaoEscapeHtml(this.FormGenerator.client_form_options.InvalidInputMessage)+
            '</div>');
        this.$StepForm.find('#_'+step.Id).append(this.$CurrentErrorMessage);
        this.$CurrentErrorMessage.slideDown('1000','easeOutQuint');
        this.FormGenerator.ScrollTo(firstInvalidField.JQueryElement);
    }
    return isValid;
};

SfMultipleStepsBase.prototype.GetCurrentStep=function()
{
    var id=this.$StepForm.find('.step-pane.active').data('step-id');
    return this.StepCatalog[id];
};

SfMultipleStepsBase.prototype.GenerationCompleted=function()
{


};

SfMultipleStepsBase.prototype.CreateSteps=function()
{
    if(this.Options.Steps.length==0)
    {
       this.Options.LatestId++;
       this.Options.Steps.push({
           Text:'Default',
           Icon:'def',
           Id:'s'+this.Options.LatestId,
           Index:this.Options.Steps.length
       })
    }

    var $stepsContainer=this.$StepForm.find('.steps');
    for(var i=0;i<this.Options.Steps.length;i++)
    {
        var iconHtml;
        switch(this.Options.Steps[i].Icon)
        {
            case 'def':
                iconHtml='<span class="badge badge-info">'+(i+1)+'</span> ';
                break;
            case '':
                iconHtml='';
                break;
            default:
                iconHtml='<span class="'+this.Options.Steps[i].Icon+'"></span> ';
        }
        $stepsContainer.append('<li data-step="'+(i+1)+'" data-step-id="'+this.Options.Steps[i].Id+'" class="rnMLStep'+(i==0?' active':'')+'">'+iconHtml+RedNaoEscapeHtml(this.Options.Steps[i].Text)+'<span class="chevron"></span></li>');
    }
};

SfMultipleStepsBase.prototype.ProcessStepInfo=function()
{
    var i;
    var t;
    this.StepCatalog={};
    for(i=0;i<this.Options.Steps.length;i++)
    {
        var currentStepOptions=this.Options.Steps[i];
        this.StepCatalog[currentStepOptions.Id]={};
        this.StepCatalog[currentStepOptions.Id].Index=currentStepOptions.Index;
        this.StepCatalog[currentStepOptions.Id].Id=currentStepOptions.Id;
        this.StepCatalog[currentStepOptions.Id].Text=currentStepOptions.Text;
        this.StepCatalog[currentStepOptions.Id].Fields=[];
    }
    this.SortSteps();
};

SfMultipleStepsBase.prototype.SortSteps=function()
{
    this.SortedSteps=[];
    for(var stepId in this.StepCatalog)
    {
        var currentStep=this.StepCatalog[stepId];
        for(t=0;t<this.SortedSteps.length;t++)
            if(currentStep.Index<this.SortedSteps[t].Index)
                break;
        this.SortedSteps.splice(t,0,currentStep);
    }
};


SfMultipleStepsBase.prototype.CreateFields=function()
{
    this.ProcessStepInfo();

    var i;
    var t;
    for(i=0;i<this.FormElements.length;i++)
    {
        var id=this.FormElements[i].GetStepId();
        var currentStepOfField=this.StepCatalog[id];
        if(typeof currentStepOfField=='undefined')
        {
            currentStepOfField=this.SortedSteps[0];
        }

        currentStepOfField.Fields.push(this.FormElements[i]);
    }

    var dataStepIndex=1;
    for(var stepName in this.StepCatalog)
    {
        var currentStepInfo=this.StepCatalog[stepName];
        var $stepContainer=rnJQuery('<div class="step-pane active" data-step="'+dataStepIndex+'" id="_'+currentStepInfo.Id+'" data-step-id="'+currentStepInfo.Id+'"></div>');
        dataStepIndex++;
        this.$StepForm.find('.step-content').append($stepContainer);
        for(t=0;t<currentStepInfo.Fields.length;t++)
            currentStepInfo.Fields[t].AppendElementToContainer($stepContainer);
    }
};
