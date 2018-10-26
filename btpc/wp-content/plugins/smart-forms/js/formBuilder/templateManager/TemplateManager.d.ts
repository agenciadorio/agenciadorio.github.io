export declare namespace SmartFormsModules {
    class TemplateManager {
        private $templateContainer;
        private $formList;
        private $contactForm;
        private $form;
        constructor();
        ShowTemplateManager(): void;
        private AddForms();
        private GenerateFormPreview(id, title);
        private FormClicked($preview);
        private CloseTemplateManager();
        private ExecutePreview(e, type);
        OpenPreview(formOptions: any, elementOptions: any, clientFormOptions: any): void;
        private ShowContactForm();
        private initializeForm();
    }
}
