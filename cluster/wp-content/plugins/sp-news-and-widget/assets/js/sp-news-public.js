jQuery( document ).ready(function($) {

	/* Initialize for widget */
	$( '.sp-news-scrolling-slider' ).each(function( index ) {
		var slider_id   = $(this).attr('id');
		var slider_conf = $.parseJSON( $(this).attr('data-conf') );

		jQuery('#'+slider_id).vTicker({
			speed     	: parseInt(slider_conf.speed),
			height 		: parseInt(slider_conf.height),
			padding 	:10,
			pause 		: parseInt(slider_conf.pause)
		});

	});

});