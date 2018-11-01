"use strict";
function RedNaoDragManager(formBuilder) {
    this.FormBuilder = formBuilder;
    this.moveFunction=null;

    this.MakeFieldsCatalogDraggable();

}

RedNaoDragManager.prototype.MakeFieldsCatalogDraggable=function()
{
    var items= rnJQuery(".rednaoformbuilder .component");
    for(var i=0;i<items.length;i++)
    {
        this.MakeItemDraggable(rnJQuery(items[i]));

    }
};

RedNaoDragManager.prototype.MakeAlreadySelectedElementsDraggable=function()
{
    var items= rnJQuery("#redNaoElementlist .rednao-control-group");
    for(var i=0;i<items.length;i++)
    {
        this.MakeItemDraggable(rnJQuery(items[i]));
    }
};


RedNaoDragManager.prototype.MakeItemDraggable=function(jQueryElement)
{
    var self = this;
    jQueryElement.mousedown(function(e){self.SmartDonationsFormMouseDownFired(e, rnJQuery(this))});
    jQueryElement.find('input[type=submit],button,input[type=image]').click(function(e){e.preventDefault();e.stopPropagation();});

    jQueryElement.find('.redNaoCheckBox').unbind('mouseover');
    jQueryElement.find('.redNaoCheckBox').unbind('click');
    jQueryElement.find('.iCheck-helper').unbind('mouseover');
    jQueryElement.find('.iCheck-helper').unbind('click');

    jQueryElement.find('.redNaoRadio').unbind('mouseover');
    jQueryElement.find('.redNaoRadio').unbind('click');

    jQueryElement.find('.select2-choice').unbind('mousedown');


};

RedNaoDragManager.prototype.SmartDonationsFormMouseDownFired = function (e,draggedElement) {
    e.stopPropagation();
    e.preventDefault();
    if(typeof this.FormBuilder=='undefined')//formbuilder is undefined when called from the style editor
        return;
    if(this.FormBuilder.FormBuilderDisabled)
        return;
    var draggedItemDisplayed = false;

    if (rnJQuery(this).hasClass('last'))
        return;

    var classOrigin = "";
    if (draggedElement.parent().hasClass('formelements')||draggedElement.parent().hasClass('step-pane'))//step-pane for splitted forms;
        this.DragBehavior=new DragItemBehaviorExistingElement(this.FormBuilder, draggedElement);
    else
        this.DragBehavior=new DragItemBehaviorNewElement(this.FormBuilder,draggedElement);

    var self=this;
    this.DragBehavior.ElementAdded=function(newElementJQuery)
    {
        self.MakeItemDraggable(newElementJQuery);
    };

    var offset = draggedElement.offset();



    var pageX = e.pageX;
    var pageY = e.pageY;
    var deltaX = pageX - offset.left;
    var deltaY = pageY - offset.top;
   // this.UnbindMovefunction();

    var self=this;
    this.moveFunction = function (e) {
        if (e.pageX > pageX - 10 && e.pageX < pageX + 10 && e.pageY > pageY - 10 && e.pageY < pageY + 10)
            return;

        if (!draggedItemDisplayed) {
            self.displayedDraggedElement=self.DragBehavior.DisplayDraggedItem(classOrigin);
            draggedItemDisplayed = true;
            self.MakeItemDraggable(self.displayedDraggedElement);
        }

        self.displayedDraggedElement.offset({ top:e.pageY - deltaY, left:e.pageX - deltaX });
        var list = rnJQuery("#redNaoElementlist .rednao-control-group,#redNaoElementlist .last,#redNaoSmartFormsPlaceHolder");
        for (var i = 0; i < list.length; i++) {
            var currentElement = rnJQuery(list[i]);
            var offset = currentElement.offset();
            var width = currentElement.width();
            var height = currentElement.height();
            if (e.pageY > offset.top && e.pageX > offset.left && e.pageY < offset.top + height && e.pageX < offset.left + width)
            {
                self.DragBehavior.HoverInElement( rnJQuery(list[i]));
                return;
            }

        }

        self.DragBehavior.HoverInAnything(rnJQuery(list[i]));
    };

    this.UnbindMoveFunction = function () {
        if (self.moveFunction != null) {
            rnJQuery("body").unbind('mousemove', self.moveFunction);
            rnJQuery("body").unbind('mouseup',self.UnbindMovefunction)
            self.moveFunction = null;
        }

        if(self.displayedDraggedElement==null)
            self.DragBehavior.ElementClicked();
        else
        {
            self.displayedDraggedElement.remove();
            var target=rnJQuery(".redNaoTarget");
            if(target.length>0)
                self.DragBehavior.DragDrop(target);
        }

        self.displayedDraggedElement=null;
    };


    rnJQuery("body").mouseup(self.UnbindMoveFunction);
    rnJQuery("body").mousemove(self.moveFunction);


};

RedNaoDragManager.prototype.SwitchFormElements = function (draggedElementSource, target) {
    if (target.hasClass('last')) {
        return;
    }
    var clonedSource = draggedElementSource.clone();
    var clonedTarget = target.clone();

    var sourceId = draggedElementSource.attr('id');
    var targetId = target.attr('id');
    var targetIndex = -1;
    var sourceIndex = -1;


    for (var i = 0; i < RedNaoFormElements.length; i++) {
        if (RedNaoFormElements[i].Id == targetId)
            targetIndex = i;

        if (RedNaoFormElements[i].Id == sourceId)
            sourceIndex = i;
    }


    if (sourceIndex >= 0 && targetIndex >= 0) {

        if (targetIndex > sourceIndex)
            targetIndex--;
        var aux = RedNaoFormElements[sourceIndex];

        RedNaoFormElements.splice(sourceIndex, 1);
        RedNaoFormElements.splice(targetIndex, 0, aux);


       // RefreshForm();
    }
    /*  target.replaceWith(clonedSource);
     draggedElementSource.replaceWith(clonedTarget);
     SmartDonationsPrepareDraggableItems();*/

};


