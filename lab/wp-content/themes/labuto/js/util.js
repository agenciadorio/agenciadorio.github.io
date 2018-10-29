jQuery(document).ready(function() { // inicio jquery

    //ADICIONAR AOS FAVORITOS
    jQuery("a.link-favorito").click(function(e) {
        e.preventDefault();
        // aqui deve definir o endereço do site
        var url = 'http://www.labutoassessoria.com.br/';
        // aqui deve definir o titulo do site
        var title = 'Labuto Assessoria';

        // mozilla firefox          
        if(jQuery.browser.mozilla == true) {
            window.sidebar.addPanel(title, url, '');
            return false;
            // internet explorer
        } else if(jQuery.browser.msie == true) {
            window.external.AddFavorite(url, title);
            return false;
            // outros navegadores
        } else {
            alert('Pressione as teclas CTRL + D para adicionar aos favoritos.');
        }
    });


    //DROP DOWN
	jQuery("#menu ul.sub-menu").parents('li').addClass('menu-parent');
	jQuery("#menu .sub-menu .menu-parent").addClass('sub-menu-parent').removeClass('menu-parent');
	jQuery("#menu .sub-menu-parent .sub-menu").addClass('sub-sub-menu').removeClass('sub-menu');
	jQuery("#menu .menu-parent a:nth-child(1)").addClass('nolink');
        jQuery("#menu .menu-parent ul a").removeClass('nolink');
	jQuery("#menu .sub-menu-parent a:nth-child(1)").addClass('nolink');
        jQuery("#menu .sub-menu-parent ul a").removeClass('nolink');
	jQuery("#menu .menu-parent").hoverIntent(function(){
		//var largura = parseInt(jQuery(this).css("width")) - 15;
		//var largura = 115;
		//jQuery(this).find(".sub-menu li").css("width",largura+'px');
		jQuery(this).find(".sub-menu").show();
	}, function(){
		jQuery(this).find(".sub-menu").delay(1000).hide();
        });
	jQuery("#menu .sub-menu-parent").hoverIntent(function(){
		//var largura = parseInt(jQuery(this).css("width")) - 15;
		//var largura = 115;
		//jQuery(this).find(".sub-menu li").css("width",largura+'px');
		jQuery(this).find(".sub-sub-menu").show();
	}, function(){
		jQuery(this).find(".sub-sub-menu").delay(1000).hide();
        });
// FIM EFEITO DROPDOWN DO MENU

// NO LINK
	jQuery("a.nolink").css('cursor','default');
	jQuery("a.nolink").click(function(){
		return false;
	});
}); // fim jquery