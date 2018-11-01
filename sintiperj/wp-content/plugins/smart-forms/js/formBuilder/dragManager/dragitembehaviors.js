"use strict";

function DragItemBehaviorBase(formBuilder,draggedElement)
{
    this.DraggedElement=draggedElement;
    this.FormBuilder=formBuilder;
}

DragItemBehaviorBase.prototype.HoverInElement=function(formBuilder,target)
{

};

DragItemBehaviorBase.prototype.HoverInAnything=function(formBuilder,target)
{

};

DragItemBehaviorBase.prototype.DragDrop=function(formBuilder,target)
{

};

DragItemBehaviorBase.prototype.FireEvent=function(eventName,args)
{
    if(typeof this[eventName]!='undefined')
        this[eventName](args);
};

DragItemBehaviorBase.prototype.DisplayDraggedItem=function (draggedElementSource, classOrigin)
{

};

DragItemBehaviorBase.prototype.ElementClicked=function()
{

};

/************************************************************************************New Element Behavior****************************************/

function DragItemBehaviorNewElement(formBuilder,draggedElement)
{
    DragItemBehaviorBase.call(this,formBuilder,draggedElement);
}
DragItemBehaviorNewElement.prototype=Object.create(DragItemBehaviorBase.prototype);

DragItemBehaviorNewElement.prototype.HoverInElement=function(target)
{
    if (target.attr('id') == "redNaoSmartFormsPlaceHolder")
        return;

    rnJQuery('#redNaoSmartFormsPlaceHolder').remove();
    rnJQuery(this.DraggedElement).fadeTo(0.5, 1);
    rnJQuery("<div id='redNaoSmartFormsPlaceHolder' class='redNaoTarget'></div>").insertBefore(target);
};

DragItemBehaviorNewElement.prototype.HoverInAnything=function(target)
{
    rnJQuery('#redNaoSmartFormsPlaceHolder').remove();
};

DragItemBehaviorNewElement.prototype.DragDrop=function(target)
{
    if(this.FormBuilder.RedNaoFormElements.length>=8&&!RedNaoLicensingManagerVar.LicenseIsValid('Sorry, this version only support up to 8 fields'))
    {
        rnJQuery('#redNaoSmartFormsPlaceHolder').remove();
        return;
    }
    var newElement = this.FormBuilder.CreateNewInstanceOfElement(this.DraggedElement);
    if (newElement != null)
    {
        this.FormBuilder.AddFieldInPosition(newElement,target);
        var newElementJQuery= newElement.GenerateHtml(target);
        this.FireEvent("ElementAdded",newElementJQuery);
    }
};

DragItemBehaviorNewElement.prototype.DisplayDraggedItem=function (classOrigin) {
    var tempForm=rnJQuery('<div class="form-horizontal span6 temp ' + classOrigin + ' tempForm" >' + this.DraggedElement.html() + '</div>');
    rnJQuery(".rednaoformbuilder").append(tempForm);
    return tempForm;

};


/************************************************************************************Existing Element Behavior****************************************/


function DragItemBehaviorExistingElement(formBuilder,draggedElement)
{
    DragItemBehaviorBase.call(this,formBuilder,draggedElement);
    this.FormElementIndex=this.FormBuilder.GetFormElementIndexByContainer(draggedElement);
}

DragItemBehaviorExistingElement.prototype=Object.create(DragItemBehaviorBase.prototype);

DragItemBehaviorExistingElement.prototype.HoverInElement=function(target)
{
    if(this.DraggedElement.attr("id")==target.attr("id"))
        return;
    else
        this.DraggedElement.remove();

    if (target.attr('id') == "redNaoSmartFormsPlaceHolder")
        return;

    rnJQuery('#redNaoSmartFormsPlaceHolder').remove();
    rnJQuery(this.DraggedElement).fadeTo(0.5, 1);
    rnJQuery("<div id='redNaoSmartFormsPlaceHolder' class='redNaoTarget'></div>").insertBefore(target);
};


DragItemBehaviorExistingElement.prototype.HoverInAnything=function(target)
{

};

DragItemBehaviorExistingElement.prototype.DragDrop=function(target)
{
    var formElement = this.FormBuilder.RedNaoFormElements[this.FormElementIndex];
    this.FormBuilder.RedNaoFormElements.splice(this.FormElementIndex,1);


    if (formElement != null)
    {
        this.FormBuilder.MoveFieldInPosition(formElement,target);
        var newElementJQuery= formElement.GenerateHtml(target);
        this.FireEvent("ElementAdded",newElementJQuery);
    }
};

DragItemBehaviorExistingElement.prototype.DisplayDraggedItem=function (classOrigin)
{
    var tempForm=rnJQuery('<div class="form-horizontal span6 temp ' + classOrigin + ' tempForm" >' + this.DraggedElement.html() + '</div>');
    rnJQuery(".rednaoformbuilder").append(tempForm);

    this.DraggedElement.replaceWith("<div id='redNaoSmartFormsPlaceHolder' class='redNaoTarget'></div>");
    return tempForm;
};

DragItemBehaviorExistingElement.prototype.ElementClicked=function()
{

    this.FormBuilder.ElementClicked(this.DraggedElement);

};

