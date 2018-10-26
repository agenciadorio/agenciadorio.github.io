function SfGetConditionalStep(formBuilder, stepConfiguration) {
    if (stepConfiguration.Type == "SfHandlerFieldPicker")
        return new SfHandlerFieldPicker(smartFormsTranslation, formBuilder, stepConfiguration);
    if (stepConfiguration.Type == "SfHandlerConditionGenerator")
        return new SfHandlerConditionGenerator(smartFormsTranslation, formBuilder, stepConfiguration);
    if (stepConfiguration.Type == "SfNamePicker")
        return new SfNamePicker(smartFormsTranslation, formBuilder, stepConfiguration);
    if (stepConfiguration.Type == "SfTextPicker")
        return new SfTextPicker(smartFormsTranslation, formBuilder, stepConfiguration);
    if (stepConfiguration.Type == "SfStepPicker")
        return new SfStepPicker(smartFormsTranslation, formBuilder, stepConfiguration);
    throw 'invalid conditional step';
}
window.SfGetConditionalStep = SfGetConditionalStep;
require("./ConditionalLogicManager");
require("./steps/SfConditionalStepBase");
require("./steps/SfHandlerConditionGenerator");
require("./steps/SfHandlerFieldPicker");
require("./steps/SfNamePicker");
require("./steps/SfTextPicker");
require("./steps/SfStepPicker");
//# sourceMappingURL=ConditionalManagerBootstrap.js.map