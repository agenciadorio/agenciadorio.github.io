function SmartFormsAddOns()
{
    this.LoadAddOns();
}

SmartFormsAddOns.prototype.LoadAddOns=function()
{
    var self=this;
    rnJQuery.post('http://smartforms.rednao.com/get-product-lists.php',function(result){self.ProductsLoaded(result);});
};

SmartFormsAddOns.prototype.ProductsLoaded=function(result)
{
    rnJQuery('.progress').remove();
    for(var i=0;i<result.addons.length;i++)
    {
        this.LoadAddOn(result.addons[i]);

    }
};

SmartFormsAddOns.prototype.LoadAddOn=function(addOnData)
{
    var panel=rnJQuery('<div class=" col-sm-3 sfPanel">\
                            <div class="panel panel-default">\
                                <div class="panel-heading">'+RedNaoEscapeHtml(addOnData.Title)+'</div>\
                                <div class="panel-body">'+
        (rnJQuery.trim(addOnData.Image)!=''?'<img src="'+RedNaoEscapeHtml(addOnData.Image)+'"/>':'')+
                                    RedNaoEscapeHtml(addOnData.Description)+'</div>\
                                <div class="panel-footer"><span class="sfLearnMore">&nbsp;</span></div>\
                                <input type="hidden" class="sfUrl" value="'+RedNaoEscapeHtml(addOnData.URL)+'"/>\
                            </div>\
                        </div>');

    panel.on('mouseenter',function(){
        rnJQuery(this).find('.sfLearnMore').text('Click to learn more');
        rnJQuery(this).find('.panel').addClass('panel-primary');
    });
    panel.on('mouseleave',function(){
        rnJQuery(this).find('.sfLearnMore').html('&nbsp;');
        rnJQuery(this).find('.panel').removeClass('panel-primary');
    });
    panel.click(function()
    {
        var url=rnJQuery(this).find('.sfUrl').val();
        window.open(url, '_blank');
    });
    rnJQuery('.addOnsContainer').append(panel);
};

var sfAddOns=null;
rnJQuery(function()
{
    sfAddOns=new SmartFormsAddOns();
});