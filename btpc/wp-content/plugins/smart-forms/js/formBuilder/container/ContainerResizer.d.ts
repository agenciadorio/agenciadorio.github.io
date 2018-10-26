declare namespace SmartFormsModules {
    class ContainerResizer {
        private designer;
        private RESIZER_AREA;
        private MINIMUN_WIDTH;
        private $fieldDisplayingResizer;
        private OriginalX;
        private LeftField;
        private RightField;
        private OriginalRightWidth;
        private OriginalLeftWidth;
        private FieldDragging;
        private ContainerWidth;
        private $LastBindedElement;
        private static ResizerThatStarted;
        constructor(designer: SmartFormsModules.MultipleElementsContainerDesigner);
        readonly Fields: sfFormElementBase<any>[];
        private InitializeResizer();
        private OnMouseMove(e);
        private GetElementDraggedTo(e);
        private IsInResizerArea(fieldDraggedTo, e);
        private ShowResizeCursor(fieldDraggedTo);
        private ClearResizer();
        private StartDrag(fieldDraggedTo, e);
        private DragMove(e);
        private AdjustSize(fieldToIncrease, fieldToIncreaseOriginalWidth, fieldToDecrease, fieldToDecreaseOriginalWidth, changeAmount);
        private EndDrag();
        private ChangeSize(field, changeAmount, originalWidth);
    }
}
