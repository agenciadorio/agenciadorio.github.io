export declare namespace SmartFormsModules {
    class TemplateManager {
        private $templateContainer;
        private $formList;
        private $contactForm;
        private $form;
        constructor();
        ShowTemplateManager(): void;
        private AddForms;
        private GenerateFormPreview;
        private FormClicked;
        private CloseTemplateManager;
        private ExecutePreview;
        OpenPreview(formOptions: any, elementOptions: any, clientFormOptions: any): void;
        private ShowContactForm;
        private initializeForm;
    }
}
