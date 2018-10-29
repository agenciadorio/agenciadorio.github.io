(function($){
	'use strict';
	$(function(){
		new WOW().init();
		//Slider
		$('.owl').each( function() {
			var $carousel = $(this);
			$carousel.owlCarousel({
				items : $carousel.attr('data-items'),
				itemsDesktop : [1199,$carousel.attr('data-itemsDesktop')],
				itemsDesktopSmall : [979,$carousel.attr('data-itemsDesktopSmall')],
				itemsTablet:  [797,$carousel.attr('data-itemsTablet')],
				itemsMobile :  [479,$carousel.attr('data-itemsMobile')],
				navigation : JSON.parse($carousel.attr('data-buttons')),
				pagination: JSON.parse($carousel.attr('data-pag')),
				slideSpeed: 1000,
				paginationSpeed : 1000,
				loop:true
			});
		 });
		$(window).load(function()
		{
			$('.preloader p').fadeOut();
			$('.preloader').delay(300).fadeOut('slow');
			$('body').delay(400).css({'overflow':'visible'});

			// project detail gallery
			if( $('#project-media-gallrey').length ){
				var _width = $('#project-media-gallrey').hasClass('active_sidebar') ? 247 : 300;
				$('#project-carousel').flexslider({
					animation: "slide",
					controlNav: false,
					animationLoop: false,
					slideshow: false,
					itemWidth: _width,
					// itemMargin: 3,
					asNavFor: '#project-media-gallrey'
				});

				$('#project-media-gallrey').flexslider({
					animation: "slide",
					controlNav: false,
					animationLoop: false,
					slideshow: false,
					sync: "#project-carousel"
				});
			}

			if( $('.blog-article-gallery').length ){
				$('.blog-article-gallery').each(function(){
					$(this).flexslider({
						animation: "slide",
						controlNav: false
					})
				})
			}

			// fix height video
			fix_height_video();
		});

		function fix_height_video(){
			var _videos = $('.video-media-type');
			if( _videos.length === 0 ) return;
			_videos.each(function(){
				var _orsize = $(this).data('origin');
				$(this).children('iframe').height( $(this).width() * _orsize[1] / _orsize[0]);
			})
		}
		// Counterup
		if( $('.counter').length ){
			$('.counter').each(function(){
				$(this).counterUp({
					delay: 10,
					time: $(this).data('speed')
				});
			})
		}

		//Menu
		$('.navbar-toggle').on('click',function(){
			height_w();
		});
		function height_w()
		{
			$('.navbar-nav').css('max-height',$(window).height()-65);
		}
		window.onresize = function()
		{
			height_w();

			// fix height video
			fix_height_video();
		}
		//Search
		$('.search-box i').on('click',function(){
			var $ev=$(this).parent().find('form');
			if($ev.hasClass('open'))
			{
				$ev.removeClass('open');
				$ev.fadeOut();
				$(this).addClass('fa-search');
				$(this).removeClass('fa-close');
			}
			else
			{
				$ev.addClass('open');
				$ev.fadeIn();
				$(this).removeClass('fa-search');
				$(this).addClass('fa-close');
			}
		});
	});
})(window.jQuery)