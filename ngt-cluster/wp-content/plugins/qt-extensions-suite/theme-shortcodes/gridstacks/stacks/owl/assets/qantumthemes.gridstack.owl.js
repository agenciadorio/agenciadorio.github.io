
(function($){

	
    $(document).ready(function() {
     
      $(".owl-carousel").each(function(i,c){

      		var that = $(this);


      		var	attributes = {
	      		items: parseInt(that.attr("data-items")),
	      		nav: (that.attr("data-nav") === "true"),
	      		margin: parseInt(that.attr("data-margin")),
	      		dots: (that.attr("data-dots")  === "true"),
	      		navRewind: (that.attr("data-navRewind") === "true"),
	      		autoplayHoverPause: true,
	      		animateIn: true,
	      		animateOut: true,
	      		loop: true,//(that.attr("data-loop") === "true"),
	      		center: (that.attr("data-center") === "true"),
	      		mouseDrag: (that.attr("data-mouseDrag") === "true"),
	      		touchDrag: (that.attr("data-touchDrag") === "true"),
	      		pullDrag: (that.attr("data-pullDrag") === "true"),
	      		freeDrag: (that.attr("data-freeDrag") === "true"),
	      		stagePadding: parseInt(that.attr("data-stagePadding")),
	      		mergeFit: (that.attr("data-mergeFit") === "true"),
	      		autoWidth: (that.attr("data-autoWidth") === "true"),
	      		

	      		autoplay: (that.attr("data-autoplay") === "true"),
	      		URLhashListener: (that.attr("data-URLhashListener") === "true"),
	      		
	      		video: (that.attr("data-video") === "true"),
	      		videoHeight: (that.attr("data-videoHeight") === "true"),
	      		videoWidth: (that.attr("data-videoWidth") === "true"),
	      		autoplayTimeout: parseInt(that.attr("data-autoplay")),
	      		responsive:{
			        0:{
			            items:2,
			            nav:false
			        },
			        768:{
			            items:3
			        },
			        1000:{
			            items: parseInt(that.attr("data-items"))
			        }
			    },

	      		navText: ['<span class="qt-btn-rhombus btn"><i class="fa fa-chevron-left"></i></span>','<span class="qt-btn-rhombus btn" data-tooltip="Vice Podcast #04 by Johnatan Bell"><i class="fa fa-chevron-right"></i></span>']

	      	};
	      	that.owlCarousel(attributes);
      });

      
     
    });
})(jQuery)


