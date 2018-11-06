jQuery(document).ready(function($) {
	$(".wcp-slick").each(function(index, el) {
		var slick_ob = {
		  	infinite: true,
			dots: ($(this).data('dots') == 'on') ? true : false,		  
			arrows: ($(this).data('arrows') == 'on') ? true : false,		  
			autoplay: ($(this).data('autoplay') == 'on') ? true : false,
			autoplaySpeed: $(this).data('autoplayspeed'),
			draggable: true,
			// swipeToSlide: true,
			speed: $(this).data('speed'),
			slidesToShow: $(this).data('slidestoshow'),
			slidesToScroll: $(this).data('slidestoscroll'),
			slidesPerRow: $(this).data('slidesperrow'),
			rows: $(this).data('rows'),
		  	responsive: [{
		      breakpoint: 768,
		      settings: {
		        slidesToShow: $(this).data('slidestoshowtab'),
		        slidesToScroll: 1,
		      }
		    },
		    {
		      breakpoint: 480,
		      settings: {
		        slidesToShow: $(this).data('slidestoshowmob'),
		        slidesToScroll: 1,
		      }
		    }]			
		};
		$(this).slick(slick_ob);
	});

	if ($('.fixed-height-image').length) {
		$('.fixed-height-image').imagefill();
		setTimeout(function() {$('.fixed-height-image').imagefill();}, 50);
	}
});