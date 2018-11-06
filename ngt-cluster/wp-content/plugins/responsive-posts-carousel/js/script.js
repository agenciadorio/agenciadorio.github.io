jQuery(document).ready(function($) {

	var width = 200;
	// var margin = 5;
	var slideshowSpeed = 3000;
	var animationSpeed = 1000;
	var looping = false;
	var playpause = false;
	var slideshow = false;
	var smoothHeight = false;
	var controlnav = true;
	var directionnav = true;

	if (carousel.width != '') { width = parseInt(carousel.width); }
	// if (carousel.margin != '') { margin = parseInt(carousel.margin); }
	if (carousel.slideshowSpeed != '') { slideshowSpeed = parseInt(carousel.slideshowSpeed); }
	if (carousel.animationSpeed != '') { animationSpeed = parseInt(carousel.animationSpeed); }

	if (carousel.looping == 'true') { looping = true; }
	if (carousel.playpause == 'true') { playpause = true; }
	if (carousel.slideshow == 'true') { slideshow = true; }
	if (carousel.smoothHeight == 'true') { smoothHeight = true; }
	if (carousel.controlnav != 'true') { controlnav = false; }
	if (carousel.directionnav != 'true') { directionnav = false; }
	
	jQuery('.flexslider').flexslider({
		animation: "slide",
		animationLoop: looping,
		itemWidth: width,
		itemMargin: 5,
		pausePlay: playpause,
		slideshowSpeed: slideshowSpeed,
		animationSpeed: animationSpeed,
		smoothHeight: smoothHeight,
		controlNav: controlnav,
		directionNav: directionnav
	});

});
