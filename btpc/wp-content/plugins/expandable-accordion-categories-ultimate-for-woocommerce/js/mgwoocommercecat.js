(function($){
$(document).ready(function() {

	// Categories menu opening
	$('.woocommerce.widget_product_categories .product-categories li.cat-parent').prepend('<div class="cat-menu-close"></div>');

	$( document ).on( "click", ".woocommerce.widget_product_categories .product-categories li.cat-parent:not(.opened) > .cat-menu-close", function(e) {
		$(this).parent().addClass('opened');
		
		$(this).next().next('ul.children').slideDown();

	});
	

});
})(jQuery);