function SmartFormsAddNewExtension()
{
    rnJQuery("#tabpro").append('<div class="component">\
        <div class="control-group sfFileUpload">\
    </div>\
    </div>');

    var self=this;
    RedNaoEventManager.Subscribe('AddExtendedElements',self.AddExtendedElements);
}

SmartFormsAddNewExtension.prototype.AddExtendedElements=function(extensionArray)
{
    extensionArray.push('sfFileUpload');
}

var SmartFormsAddNewExtensionVar=null;
RedNaoEventManager.Subscribe('AddNewRegisterElementExtensions',function(){
        SmartFormsAddNewExtensionVar=new SmartFormsAddNewExtension();
    }
);
