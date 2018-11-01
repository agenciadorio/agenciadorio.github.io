jQuery(document).ready(function($) {
	
	// For Slider
	$( '.aigpl-gallery-slider' ).each(function( index ) {
		
		var slider_id   = $(this).attr('id');
		var slider_conf = $.parseJSON( $(this).closest('.aigpl-gallery-slider-wrp').find('.aigpl-gallery-slider-conf').text());
		
		jQuery('#'+slider_id).slick({
			dots			: (slider_conf.dots) == "true" ? true : false,
			infinite		: true,
			arrows			: (slider_conf.arrows) == "true" ? true : false,
			speed			: parseInt(slider_conf.speed),
			autoplay		: (slider_conf.autoplay) == "true" ? true : false,
			autoplaySpeed	: parseInt(slider_conf.autoplay_interval),
			slidesToShow	: parseInt(slider_conf.slidestoshow),
			slidesToScroll	: parseInt(slider_conf.slidestoscroll),
			rtl             : (Aigpl.is_rtl == 1) ? true : false,
			mobileFirst    	: (Aigpl.is_mobile == 1) ? true : false,
			responsive 		: [{
				breakpoint 	: 1023,
				settings 	: {
					slidesToShow 	: (parseInt(slider_conf.slidestoshow) > 3) ? 3 : parseInt(slider_conf.slidestoshow),
					slidesToScroll 	: 1,
					dots 			: (slider_conf.dots) == "true" ? true : false,
				}
			},{
				breakpoint	: 767,	  			
				settings	: {
					slidesToShow 	: (parseInt(slider_conf.slidestoshow) > 2) ? 2 : parseInt(slider_conf.slidestoshow),
					slidesToScroll 	: 1,
					dots 			: (slider_conf.dots) == "true" ? true : false,
				}
			},
			{
				breakpoint	: 479,
				settings	: {
					slidesToShow 	: 1,
					slidesToScroll 	: 1,
					dots 			: false,
				}
			},
			{
				breakpoint	: 319,
				settings	: {
					slidesToShow 	: 1,
					slidesToScroll 	: 1,
					dots 			: false,
				}
			}]
		});
	});
	
	// Popup Gallery
	$( '.aigpl-popup-gallery' ).each(function( index ) {

		var gallery_id = $(this).attr('id');

		if( typeof('gallery_id') !== 'undefined' && gallery_id != '' ) { //.slick-image-slide:not(.slick-cloned) a
			$('#'+gallery_id).magnificPopup({
				delegate: '.aigpl-cnt-wrp:not(.slick-cloned) a.aigpl-img-link',
				type: 'image',
				mainClass: 'aigpl-mfp-popup',
				tLoading: 'Loading image #%curr%...',
				gallery: {
					enabled: true,
					navigateByImgClick: true,
					preload: [0,1] // Will preload 0 - before current, and 1 after the current image
				},
				image: {
					tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
					titleSrc: function(item) {
						return item.el.closest('.aigpl-img-wrp').find('.aigpl-img').attr('title');
					}
				},
				zoom: {
					enabled: true,
					duration: 300, // don't foget to change the duration also in CSS
					opener: function(element) {
						return element.closest('.aigpl-img-wrp').find('.aigpl-img');
					}
				}
			});
		}
	});

});