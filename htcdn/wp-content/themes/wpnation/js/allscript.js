
/* jQuery Visible Plugin Including */

(function($) {

  /**
   * Copyright 2012, Digital Fusion
   * Licensed under the MIT license.
   * http://teamdf.com/jquery-plugins/license/
   *
   * @author Sam Sehnert
   * @desc A small plugin that checks whether elements are within
   *     the user visible viewport of a web browser.
   *     only accounts for vertical position, not horizontal.
   */

  $.fn.visible = function(partial) {
    
      var $t            = $(this),
          $w            = $(window),
          viewTop       = $w.scrollTop(),
          viewBottom    = viewTop + $w.height(),
          _top          = $t.offset().top,
          _bottom       = _top + $t.height(),
          compareTop    = partial === true ? _bottom : _top,
          compareBottom = partial === true ? _top : _bottom;
    
    return ((compareBottom <= viewBottom) && (compareTop >= viewTop));

  };
    
})(jQuery);

/* jQuery Modal window plugin including */

if (!jQuery) { throw new Error("Bootstrap requires jQuery") }

+function ($) { "use strict";

  function transitionEnd() {
    var el = document.createElement('bootstrap')

    var transEndEventNames = {
      'WebkitTransition' : 'webkitTransitionEnd'
    , 'MozTransition'    : 'transitionend'
    , 'OTransition'      : 'oTransitionEnd otransitionend'
    , 'transition'       : 'transitionend'
    }

    for (var name in transEndEventNames) {
      if (el.style[name] !== undefined) {
        return { end: transEndEventNames[name] }
      }
    }
  }

  $.fn.emulateTransitionEnd = function (duration) {
    var called = false, $el = this
    $(this).one($.support.transition.end, function () { called = true })
    var callback = function () { if (!called) $($el).trigger($.support.transition.end) }
    setTimeout(callback, duration)
    return this
  }

  $(function () {
    $.support.transition = transitionEnd()
  })

}(window.jQuery);


/* ========================================================================
 * Bootstrap: modal.js v3.0.0
 * http://twbs.github.com/bootstrap/javascript.html#modals
 * ========================================================================
 * Copyright 2012 Twitter, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ======================================================================== */


+function ($) { "use strict";

  var Modal = function (element, options) {
    this.options   = options
    this.$element  = $(element)
    this.$backdrop =
    this.isShown   = null

    if (this.options.remote) this.$element.load(this.options.remote)
  }

  Modal.DEFAULTS = {
      backdrop: true
    , keyboard: true
    , show: true
  }

  Modal.prototype.toggle = function (_relatedTarget) {
    return this[!this.isShown ? 'show' : 'hide'](_relatedTarget)
  }

  Modal.prototype.show = function (_relatedTarget) {
    var that = this
    var e    = $.Event('show.bs.modal', { relatedTarget: _relatedTarget })
    this.$element.trigger(e)
    if (this.isShown || e.isDefaultPrevented()) return
    this.isShown = true
    this.escape()
    this.$element.on('click.dismiss.modal', '[data-dismiss="modal"]', $.proxy(this.hide, this))
    this.backdrop(function () {
      var transition = $.support.transition && that.$element.hasClass('fade')
      if (!that.$element.parent().length) {
        that.$element.appendTo(document.body) // don't move modals dom position
      }
      that.$element.show()
      if (transition) {
        that.$element[0].offsetWidth // force reflow
      }
      that.$element
        .addClass('in')
        .attr('aria-hidden', false)
      that.enforceFocus()
      var e = $.Event('shown.bs.modal', { relatedTarget: _relatedTarget })
      transition ?
        that.$element.find('.modal-dialog') // wait for modal to slide in
          .one($.support.transition.end, function () {
            that.$element.focus().trigger(e)
          })
          .emulateTransitionEnd(300) :
        that.$element.focus().trigger(e)
    })
  }

  Modal.prototype.hide = function (e) {
    if (e) e.preventDefault()
    e = $.Event('hide.bs.modal')
    this.$element.trigger(e)
    if (!this.isShown || e.isDefaultPrevented()) return
    this.isShown = false
    this.escape()
    $(document).off('focusin.bs.modal')
    this.$element
      .removeClass('in')
      .attr('aria-hidden', true)
      .off('click.dismiss.modal')
    $.support.transition && this.$element.hasClass('fade') ?
      this.$element
        .one($.support.transition.end, $.proxy(this.hideModal, this))
        .emulateTransitionEnd(300) :
      this.hideModal()
  }

  Modal.prototype.enforceFocus = function () {
    $(document)
      .off('focusin.bs.modal') // guard against infinite focus loop
      .on('focusin.bs.modal', $.proxy(function (e) {
        if (this.$element[0] !== e.target && !this.$element.has(e.target).length) {
          this.$element.focus()
        }
      }, this))
  }

  Modal.prototype.escape = function () {
    if (this.isShown && this.options.keyboard) {
      this.$element.on('keyup.dismiss.bs.modal', $.proxy(function (e) {
        e.which == 27 && this.hide()
      }, this))
    } else if (!this.isShown) {
      this.$element.off('keyup.dismiss.bs.modal')
    }
  }

  Modal.prototype.hideModal = function () {
    var that = this
    this.$element.hide()
    this.backdrop(function () {
      that.removeBackdrop()
      that.$element.trigger('hidden.bs.modal')
    })
  }

  Modal.prototype.removeBackdrop = function () {
    this.$backdrop && this.$backdrop.remove()
    this.$backdrop = null
  }

  Modal.prototype.backdrop = function (callback) {
    var that    = this
    var animate = this.$element.hasClass('fade') ? 'fade' : ''
    if (this.isShown && this.options.backdrop) {
      var doAnimate = $.support.transition && animate
      this.$backdrop = $('<div class="modal-backdrop ' + animate + '" />')
        .appendTo(document.body)
      this.$element.on('click.dismiss.modal', $.proxy(function (e) {
        if (e.target !== e.currentTarget) return
        this.options.backdrop == 'static'
          ? this.$element[0].focus.call(this.$element[0])
          : this.hide.call(this)
      }, this))
      if (doAnimate) this.$backdrop[0].offsetWidth // force reflow
      this.$backdrop.addClass('in')
      if (!callback) return
      doAnimate ?
        this.$backdrop
          .one($.support.transition.end, callback)
          .emulateTransitionEnd(150) :
        callback()
    } else if (!this.isShown && this.$backdrop) {
      this.$backdrop.removeClass('in')
      $.support.transition && this.$element.hasClass('fade')?
        this.$backdrop
          .one($.support.transition.end, callback)
          .emulateTransitionEnd(150) :
        callback()
    } else if (callback) {
      callback()
    }
  }


  // MODAL PLUGIN DEFINITION
  // =======================

  var old = $.fn.modal

  $.fn.modal = function (option, _relatedTarget) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('bs.modal')
      var options = $.extend({}, Modal.DEFAULTS, $this.data(), typeof option == 'object' && option)

      if (!data) $this.data('bs.modal', (data = new Modal(this, options)))
      if (typeof option == 'string') data[option](_relatedTarget)
      else if (options.show) data.show(_relatedTarget)
    })
  }

  $.fn.modal.Constructor = Modal


  // MODAL NO CONFLICT
  // =================

  $.fn.modal.noConflict = function () {
    $.fn.modal = old
    return this
  }


  // MODAL DATA-API
  // ==============

  $(document).on('click.bs.modal.data-api', '[data-toggle="modal"]', function (e) {
    var $this   = $(this)
    var href    = $this.attr('href')
    var $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, ''))) //strip for ie7
    var option  = $target.data('modal') ? 'toggle' : $.extend({ remote: !/#/.test(href) && href }, $target.data(), $this.data())

    e.preventDefault()

    $target
      .modal(option, this)
      .one('hide', function () {
        $this.is(':visible') && $this.focus()
      })
  })

  $(document)
    .on('show.bs.modal',  '.modal', function () { $(document.body).addClass('modal-open') })
    .on('hidden.bs.modal', '.modal', function () { $(document.body).removeClass('modal-open') })

}(window.jQuery);


(function($) { "use strict";

/* NATION theme script initializing */
$(document).ready(function() {

	if ($.fn.cssOriginal!=undefined) $.fn.css = $.fn.cssOriginal;
	
	
	/* Initialize Document Smooth Scroll for Chrome */ 
	var ie = (function(){

		var undef,
        v = 3,
        div = document.createElement('div'),
        all = div.getElementsByTagName('i');

		while (
			div.innerHTML = '<!--[if gt IE ' + (++v) + ']><i></i><![endif]-->',
			all[0]
		);

		return v > 4 ? v : undef;

	}());

	
	/* Initialize Document Smooth Scroll for Chrome */ 
	if ( ie > 9 || !ie) { $.smoothScroll(); }
	
	
	var coordinates = nationOption.mapCenter.split(',');
						
	if ( nationOption.showMap ) {
		//Front page google maps initializing ----
		if (document.getElementById("gmaps")) {
			var myOptions1 = { 
				//Coordinates of the map's center
				center: new google.maps.LatLng( coordinates[0], coordinates[1] ), 
				//Zoom level
				zoom: Number(nationOption.mapZoom),
				//Type of map (possible values .ROADMAP, .HYBRID, .SATELLITE, .TERRAIN)
				mapTypeId: google.maps.MapTypeId[nationOption.mapType]
			};
		
			//Define the map and select the element in which it will be displayed
			var map1 = new google.maps.Map(document.getElementById("gmaps"),myOptions1);
			var marketLatLng = [];
			var marker = [];
			
			if (  nationOption.mapMarker && nationOption.mapMarker != "" ) {
				var markercoordinates = nationOption.mapMarker.split(";");
								
				for (var i=0;i<markercoordinates.length;i++) { 
					marketLatLng[i] = markercoordinates[i].split(',');
					marker[i] = new google.maps.Marker({
						//Coordinate of the marker's location
						position: new google.maps.LatLng(marketLatLng[i][0],marketLatLng[i][1]),
						map: map1
					});
				}
			}
			
			var contentString = [];
			
						
			//Contact google maps initializing
			if ( document.getElementById("is-contact-page") && nationOption.findusAddress && 
			nationOption.findusAddress != "" && nationOption.mapTitle && nationOption.mapTitle != ""  ) {
				
				var markeraddress = nationOption.findusAddress.split(";");
				var markertitle = nationOption.mapTitle.split(";");
				
				for (var i=0;i<markeraddress.length;i++) { 
					contentString[i] = "<div id='contact-info-window'>"+markeraddress[i]+"</div>";
					var infowindow = new google.maps.InfoWindow({
						content: contentString[i]
					});
					infowindow.open(map1,marker[i]);
				}
			
			}					
		}
	}	
	
	$(".footer-twitter-feed .twitter-row-divider:last").css("display","none");
	$(".main-blog-posts-wrap:last").css("border-bottom","none");
	
	
	/* Quovolver initialization */
	if ( $('.initialize-rotation').length ) { 
		$(".initialize-rotation").quovolver(200,6000);
	}
	
	
	/* Single page room form validation */
	$("#room-date-form").submit(function(){
		
		var checkin = $("#check-in-date");
		var checkout = $("#check-out-date");
		var adult = $("#single-room-adult-selection");
		var child = $("#single-room-children-selection");
		
		if (checkin.val()=="" ) {
			checkin.addClass("hightlight");
			return false;
		} else {
			checkin.removeClass("hightlight");
		}
		
		if (checkout.val()=="" ) {
			checkout.addClass("hightlight");
			return false;
		} else {
			checkout.removeClass("hightlight");
		}
		
		if ( adult.val() == "" ) {
			adult.addClass("hightlight");
			return false;
		} else {
			adult.removeClass("hightlight");
		}
		
		if ( child.val() == "" ) {
			child.addClass("hightlight");
			return false;
		} else {
			child.removeClass("hightlight");
		}
		
	});
	
	
	//Lightbox plugin for Galleries
	if (document.getElementById("is-gallery")) {
		$(".gallery-wrap .gallery-image-wrap a").lightBox({
			imageLoading: nationOption.templatePath+'/images/lightbox-ico-loading.gif',
			imageBtnClose: nationOption.templatePath+'/images/lightbox-btn-close.gif',
			imageBtnPrev: nationOption.templatePath+'/images/lightbox-btn-prev.gif',
			imageBtnNext: nationOption.templatePath+'/images/lightbox-btn-next.gif',
			imageBlank: nationOption.templatePath+'/images/lightbox-blank.gif'
		});
	}				
		
		
	//Alignment of quote arrow 
	$(".testimonials-author-wrap").each(function() {
		var arrowAlign = 0; 
		var $this = $(this);
		var authorWidth = 0;
		var testimonialsWidth = 0;
		
		//Display element to get his width
		if ($this.parents(".testimonials-content-wrap").css("display") == "none") {
			$this.parents(".testimonials-content-wrap").css("display","block");
			var forceDisplay=true;
		}
		
		//Get width
		authorWidth = $this.width();
		testimonialsWidth = $this.parents(".testimonials-content-wrap").width();
		
		//Hide element if it was shown previously
		if (forceDisplay) {
			$this.parents(".testimonials-content-wrap").css("display","none");
		}
			
		//Calculate arrow alignment
		arrowAlign = testimonialsWidth - authorWidth - 28;
		
		//Apply arrow alignment
		$this.siblings(".testimonials-content").find(".testimonials-arrow").css("left",arrowAlign);
	});
	
	
	//Add arrow for about us
	$( "#main-aboutus-wrap #about-us-content ul li" ).prepend( "<span class='icon-caret-right'></span>" );
	$( "#main-aboutus-wrap a" ).append( "<span class='icon-chevron-right'></span>" );
	
	//Delete last delimiter from footer
	$( ".footer_menu_divider:last").css('display','none');
	
	//Delete padding in review
	$(".reservation-page-wrap .comment-wrap").css('padding-top','14px');
	
	if ( !$('.comment-section').length ) { 
		$(".reservation-page-wrap .comment-respond").css('margin-top','0px');
	}
	
	
	/* Smooth scrolling */
	$("#back-to-top").click(function(event){		
		event.preventDefault();
		
		if ($('#wpadminbar').length) {
			$('html,body').animate({scrollTop:$(this.hash).offset().top-$('#wpadminbar').height()}, 500);
		} else {
			$('html,body').animate({scrollTop:$(this.hash).offset().top}, 500);
		}
	});
		
	//Add > before blog categories 
	$( "<span class='icon-angle-right' style='color:#333'></span>" ).insertBefore( "#blog-categories li a, #archives li a" );
		
	$("#check-in-date, .main-reservation-form #check-in").click(function(e){
		e.stopPropagation();
	});
		
	$("#check-out-date, .main-reservation-form #check-out").click(function(e){
		e.stopPropagation();
	});
		
	
	/* Modal window initializing */
	$("#one-bedroom-check, .overlay-checkavail, .overlay-checkavail2, .overlay-checkavail3, .overlay-checkavail4, .overlay-checkavail5, .overlay-checkavail6, .overlay-checkavail7, .overlay-checkavail8, .overlay-checkavail9").click(function(){
		$(this).parents(".rooms-list-item-wrap").find("#myModal").modal();
	});
	
	$(".room-reservation-pricebreakdown").click(function(){
		$(this).parents(".room-reservation-price").find("#myModal").modal();
	});
	
	$("#price-breakdown").click(function(){
		$(this).parents("#total-price-wrap").find("#myModal").modal();
	});
				
	$("#booking-wrap, .main-reservation-form").on("mouseover",'.DOPFrontendBookingCalendarPRO_Day.curr_month.available',function(){
		$(this).find(".header").attr('style','background-color:#444 !important;border-color:#444 !important;');
		$(this).find(".content").attr('style','border-color:#444 !important;');
	}).on("mouseout",'.DOPFrontendBookingCalendarPRO_Day.curr_month.available',function(){
		$(this).find(".header").attr('style','background-color:#68ba68 !important;border-color:#68ba68 !important;');
		$(this).find(".content").attr('style','border-color:#68ba68 !important;');
	});
				
					
	/* Toggle widget initializing */
	var allPanels = $('.accordion-widget > .accordion-content').hide();
    $('.accordion-widget > .accordion-content.show').show();
	$('.toggle > .accordion-header > a').click(function() {
		if ( $(this).parent().next().is(":hidden") ) {
			$(this).parent().next().slideDown();
			$(this).children('span').attr('class','icon-minus').css('background-color',nationOption.primaryColor);
			$(this).parent().css({"border-bottom":"none"});
			$(this).css("color",nationOption.primaryColor);
			$(this).parent().next().css('border-bottom','1px dotted #ddd');
		} else {
			$(this).parent().next().slideUp();
			$(this).css({"color":"#000"});
			$(this).children('span').attr('class','icon-minus').css('background-color','#000');
			$(this).children('span').attr('class','icon-plus');
			$(this).parent().css('border-bottom','1px dotted #ddd');
			$(this).parent().next().css('border-bottom','none');
		}
		
		return false;
	}); 
	
	$('.toggle > .accordion-header.show > a').click();
	
	
	/* Accordion widget initializing */
	$('.accordion > .accordion-header > a').click(function() {
		if ( $(this).parent().next().is(":hidden") ) {
			$(".accordion > .accordion-content").slideUp();
			$(".accordion > .accordion-header > a ").css('color','#000').children('span').attr('class','icon-plus').css('background-color','#000')
			
			$(this).parent().next().slideDown();
			$(this).children('span').attr('class','icon-minus').css('background-color',nationOption.primaryColor);
			$(this).parent().css({"border-bottom":"none"});
			$(this).css('color',nationOption.primaryColor);
			$(this).parent().next().css('border-bottom','1px dotted #ddd');
		} 		
		return false;
	});
	
	$('.accordion > .accordion-header.show > a').click();
  
  
	/* Tabs widget initializing */
    $("#tabs-content > div").hide(); 
    $("#tabs li:first a").attr("id","current").parent().attr("id","current");
    $("#tabs-content > div:first").fadeIn();
    
    $("#tabs a").on("click",function(e) {
        e.preventDefault();
        if ($(this).attr("id") == "current"){
			return       
        } else {             
			$("#tabs-content > div").hide();
			$("#tabs a").attr("id","").parent().attr("id","");
			$(this).attr("id","current").parent().attr("id","current");
			$($(this).attr('name')).fadeIn();
        }
    }); 
	
	
	/* Navigation menu dropdown initializing */
	$("#top-navigation-menu > li, #top-language-select > .dropdown > li").on("mouseenter",function(){
        $(this).addClass("hover");
        $(this).find(".sub_menu").css('visibility', 'visible');
    }).on("mouseleave",function(){
        $(this).removeClass("hover");
        $(this).find(".sub_menu").css('visibility', 'hidden');
    });
	$("ul.dropdown li ul li:has(ul)").find("a:first").append(" &raquo; ");
	
	
	//If popular post widget don't have image delete left margin
	$(".popular-post-wrap:not(:has(img))").find(".popular-post-header,.popular-post-meta").css('width','100%');
	
	$("#top-navigation-menu .menu-item-has-children").each( function() {
		if ( $(this).find(".top-navigation-content-wrap .under-title").length > 0 ) {
			$(this).find(".top-navigation-content-wrap .under-title").before(" <span class='icon-angle-down'></span>");
		} else {
			$(this).find(".top-navigation-content-wrap").append(" <span class='icon-angle-down'></span>");
		}
	});
	
	$("#top-navigation-menu > li > .sub_menu > li:has(ul)").on("mouseenter",function(){
        $(this).addClass("hover");
        $(this).find(".sub_menu2").css('visibility', 'visible');
    }).on("mouseleave",function(){
        $(this).removeClass("hover");
        $(this).find(".sub_menu2").css('visibility', 'hidden');
    });
	
	$("#top-navigation-menu > li").on("mouseenter",function(){
        $(this).find(".top-navigation-content-wrap").css('color',nationOption.menuColor).find(".icon-angle-down").css('color',nationOption.menuColor);
		$(this).find(".under-title").css('color',nationOption.menuColor)
    }).on("mouseleave",function(){
        $(this).find(".top-navigation-content-wrap").css('color','#767676').find(".icon-angle-down").css('color','#767676');
		$(this).find(".under-title").css('color','#777')
	});
	
	$( "#top-navigation-menu li .top-navigation-content-wrap" ).last().css({"border-right":"0","margin-right":"0","padding-right":"0"});
	
	
	/* Search dropdown initializing */
	$("#top-search .icon-search").on("click",function(e){
		if ( $(this).parent().find("#top-search-window-wrap").css('display')== 'none' ) {
			 e.stopPropagation();
			$(this).parent().find("#top-search-window-wrap").css('display','block');
		} else {
			 e.stopPropagation();
			$(this).parent().find("#top-search-window-wrap").css('display','none').children('input').val("search..");
		}
	});
	$("#top-search-window-wrap input").on("click",function(){ 
		if ($(this).val() == "search..") {
			$(this).val("")
		}
	});
	
	
	/* Show element with animation while scrolling page down */
	var win = $(window);
	var mods = $(".module");
	var sideMods = $(".module-side");
	var bottomMods = $(".module-bottom");
	
	win.scroll(function(event) {
		mods.each(function(i, el) {
			var el = $(el);
			if (el.visible(true)) {
				el.addClass("come-in"); 
			} 
		});
		sideMods.each(function(i, el) {
			var el = $(el);
			if (el.visible(true)) {
				el.addClass("come-side"); 
			} 
		});
		bottomMods.each(function(i, el) {
			var el = $(el);
			if (el.visible(true)) {
				el.addClass("come-bottom"); 
			} 
		});
	});
	
	
	/* Create overlay for room */
	$(".rooms-list-item-image-wrap").on("mouseenter",function() {
		$(this).find(".rooms-list-item-price").addClass('anim-disable').css("display","none");
		$(this).find(".room-main-list-overlay").css("display","block");
		var centeringHeight = $(this).find(".room-main-list-overlay").height()/2-$(this).find(".room-overlay-content").height()/2;
		var centeringWidth = $(this).find(".room-main-list-overlay").width()/2-$(this).find(".room-overlay-content").width()/2;
		$(this).find(".room-overlay-content").css("top",centeringHeight-20).css("left",centeringWidth).animate({"top":centeringHeight},500);
		$(this).find(".room-main-list-overlay").animate({opacity:"0.9"},500);
	}).on("mouseleave", function() {
		$(this).find(".room-main-list-overlay").animate({opacity:"0"},500, function() {
			$(this).find(".room-main-list-overlay").css("display","none");	
		});
		$(this).find(".rooms-list-item-price").css("display","inline-block");
	});
	
	
	/* Create overlay for main news */
	$(".main-blog-post-image-wrap").on("mouseenter",function() {
		$(this).find(".rooms-list-item-price").addClass('anim-disable').css("display","none");
		$(this).find(".room-main-list-overlay").css("display","block");
		var centeringHeight = $(this).find(".room-main-list-overlay").height()/2-$(this).find(".room-overlay-content").height()/2;
		var centeringWidth = $(this).find(".room-main-list-overlay").width()/2-$(this).find(".room-overlay-content").width()/2;
		$(this).find(".room-overlay-content").css("top",centeringHeight-20).css("left",centeringWidth).animate({"top":centeringHeight},500);
		$(this).find(".room-main-list-overlay").animate({opacity:"0.9"},500);
	}).on("mouseleave", function() {
		$(this).find(".room-main-list-overlay").animate({opacity:"0"},500, function() {
			$(this).find(".room-main-list-overlay").css("display","none");	
		});
		$(this).find(".rooms-list-item-price").css("display","inline-block");
	});
	
	
	/* Create overlay for blog post */
	$(".blog-image-wrap").on("mouseenter",function(){
		$(this).find(".blog-overlay").css("display","block");
		var centeringHeight = $(this).find(".blog-overlay").height()/2-$(this).find(".blog-overlay-content").height()/2;
		var centeringWidth = $(this).find(".blog-overlay").width()/2-$(this).find(".blog-overlay-content").width()/2;
		$(this).find(".blog-overlay-content").css("top",centeringHeight-20).css("left",centeringWidth).animate({"top":centeringHeight},500);
		$(this).find(".blog-overlay").animate({opacity:"0.9"},500);
	}).on("mouseleave", function() {
		$(this).find(".blog-overlay").animate({opacity:"0"},500, function() {
			$(this).find(".blog-overlay").css("display","none");	
		});
	});
	
	
	/* Create overlay for gallery */
	$(".gallery-item-wrap").on("mouseenter",function() {
		$(this).find(".room-main-list-overlay").css("display","block");
		var centeringHeight = $(this).find(".room-main-list-overlay").height()/2-$(this).find(".room-overlay-content").height()/2;
		var centeringWidth = $(this).find(".room-main-list-overlay").width()/2-$(this).find(".room-overlay-content").width()/2;
		$(this).find(".room-overlay-content").css("top",centeringHeight-20).css("left",centeringWidth).animate({"top":centeringHeight},500);
		$(this).find(".room-main-list-overlay").animate({opacity:"0.9"},500);
	}).on("mouseleave", function() {
		$(this).find(".room-main-list-overlay").animate({opacity:"0"},500, function() {
			$(this).find(".room-main-list-overlay").css("display","none");	
		});
		$(this).find(".rooms-list-item-price").css("display","inline-block");
	});
	
	
	/* Blog single page form handling */
	$(".blog-single #name-comments-field, .contact-page #name-comments-field, .reservation-page-wrap #name-comments-field").on("focus",function() {
		if ( $(this).val() == 'enter name') $(this).val('');
	}).on("blur", function(){
		if ( $(this).val() == '') $(this).val('enter name');
	});
	$(".blog-single #email-comments-field, .contact-page #email-comments-field, .reservation-page-wrap #email-comments-field").on("focus",function() {
		if ( $(this).val() == 'enter email') $(this).val('');
	}).on("blur", function(){
		if ( $(this).val() == '') $(this).val('enter email');
	});
	$(".blog-single #text-comments-field, .reservation-page-wrap #text-comments-field").on("click",function() {
		if ( $(this).val() == 'enter comment text here') $(this).val('');
	}).on("blur", function(){
		if ( $(this).val() == '') $(this).val('enter comment text here');
	});
	
	
	/* Footer subscribe form handling */
	$("#footer-subscribe-email-field").on("click",function() {
		if ( $(this).val() == 'email address') $(this).val('');
	}).on("blur", function(){
		if ( $(this).val() == '') $(this).val('email address');
	});
		
		
	/* Sidebar search handling */
	$("#searchform input[type='text']").on("click",function() {
		if ( $(this).val() == 'Search ...') $(this).val('');
	}).on("blur", function(){
		if ( $(this).val() == '') $(this).val('Search ...');
	});
	
	
	/* Slider for reservation page */
	if (nationOption.mainSlider) {
		$('.reservation-page-slider').revolution({
			delay:9000,
			startheight:400,
			startwidth:700,
			hideThumbs:0,
			thumbWidth:120,						// Thumb With and Height and Amount (only if navigation Type set to thumb !)
			thumbHeight:60,
			thumbAmount:3,
			navigationType:"bullet",			// bullet, thumb, none
			navigationArrows:"none",	// nexttobullets, solo (old name verticalcentered), none
			navigationStyle:"round-old",			// round,square,navbar,round-old,square-old,navbar-old, or any from the list in the docu (choose between 50+ different item), custom
			navigationHAlign:"center",			// Vertical Align top,center,bottom
			navigationVAlign:"bottom",			// Horizontal Align left,center,right
			navigationHOffset:20,
			navigationVOffset:20,
			touchenabled:"on",					// Enable Swipe Function : on/off
			onHoverStop:"on",					// Stop Banner Timet at Hover on Slide on/off
			shadow:0,
			fullWidth:"off"						// Turns On or Off the Fullwidth Image Centering in FullWidth Modus
		});
	}
		
	function validateEmail($email) {
		var emailcheck = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		if( !emailcheck.test( $email ) ) {
			return false;
		} else {
			return true;
		}
	}

	
	/* Blog single page simple form validation */
	$("#respond #submit-button").click( function() {
		//Get the data from all the fields
		var name = $('#name-comments-field');
		var email = $('#email-comments-field');
			
		//Simple validation to make sure user entered something
		//If error found, add hightlight class to the text field
		
		if ( name.length && email.length ) {
			if (name.val()== '' || name.val()=='enter name') {
				name.addClass('hightlight');
				return false;
			} else name.removeClass('hightlight');
		
			//Simple validation to make sure user entered something
			//If error found, add hightlight class to the text field
			if (email.val()== '' || email.val()=='enter name' || !validateEmail(email.val()) ) {
				email.addClass('hightlight');
				return false;
			} else email.removeClass('hightlight');
		}
	});
	
	
	/* Contact page form validation */
	$('.contact-page #submit-button').click(function () {        

		//Get the data from all the fields
		var name = $('.your-name input');
		var email = $('.your-email input');
		var comment = $('.your-message textarea');
 
		//Simple validation to make sure user entered something
		//If error found, add hightlight class to the text field
		if (name.val()== '' || name.val()=='enter your name') {
			name.addClass('hightlight');
			return false;
		} else name.removeClass('hightlight');
         
		if (email.val()=='' || email.val()=='enter your email' || !validateEmail(email.val())) {
			email.addClass('hightlight');
			return false;
		} else email.removeClass('hightlight');
         
		if (comment.val()=='' || comment.val()=='enter your message here') {
			comment.addClass('hightlight');
			return false;
		} else comment.removeClass('hightlight');
         
		$(".your-name input, .your-email input, .your-message textarea, .phone input, #submit-button").fadeOut();
         
	}); 
	
	function parseDate(input,strip,calendar) {
		if (strip) {
			var parts = input.split('-');
		} else {
			var parts = input.split('/');
		}
		if (calendar) {
			return new Date(parts[0], parts[1]-1, parts[2]);
		} else {
			return new Date(parts[2], parts[0]-1, parts[1] );
		}
	}
	
	
	/* Contact form animation */
	$("#is-contact-page .your-name input").focus(function(){
		if ($(this).val()=='enter your name') $(this).val('');
	});
	$("#is-contact-page .your-email input").focus(function(){
		if ($(this).val()=='enter your email') $(this).val('');
	});
	$("#is-contact-page .phone input").focus(function(){
		if ($(this).val()=='enter your phone') $(this).val('');
	}).on("blur",function(){
		if ($(this).val()=='') $(this).val('enter your phone');
	});
	$("#is-contact-page .your-message textarea").focus(function(){
		if ($(this).val()=='enter your message here') $(this).val('');
	}).on("blur",function(){
		if ($(this).val()=='') $(this).val('enter your message here');
	});
	
	
	// Change order of some elements for mobile device 
	if ( $("#mobile-navigation-menu").css("display")=='block' ) {
		$("#second-par-wrap .right-aboutus-column").insertBefore("#second-par-wrap .left-aboutus-column");
		$("#headcontainer").insertAfter("#logocontainer");
	}
	
	$("#mobile-navigation-menu .icon-reorder").on("click",function(){
		if ($(this).parents("#mobile-navigation-menu").find("#mobile-navigation-menu-list").css("display")=="none") {
			$(this).parents("#mobile-navigation-menu").find("#mobile-navigation-menu-list").css("display","block");
		} else {
			$(this).parents("#mobile-navigation-menu").find("#mobile-navigation-menu-list").css("display","none");
		}
	});
	
	
	/* Reservation Page Scripts */
	
	// Room Number Selection 
	$("#room-number-selection").change(function(){
		var roomNumber = $(this).find(":selected").text();
		$(".room-guest-wrap").css('display','none');
		for(var i=1;i<=roomNumber;i++) {
			$("#room-guest"+i).css('display','block');
		}
	});	
	
	var dateFormat = "";
		
	if ( nationOption.dateformat == "european" ) {
		dateFormat = "dd-mm-yy";
	} else if ( nationOption.dateformat == "american" ) {
		dateFormat = "mm/dd/yy";
	}
	
	// parse a date in yyyy-mm-dd format
	function parseDate(input,format) {
		if ( format == "european" ) {
			var parts = input.split('-');
		} else if ( format == "american" ) {
			var parts = input.split('/');
		}
		// new Date(year, month [, day [, hours[, minutes[, seconds[, ms]]]]])
		if ( format == "european" ) {
			return new Date(parts[2], parts[1]-1, parts[0]); // Note: months are 0-based
		} else if ( format == "american" ) {
			return new Date(parts[2], parts[0]-1, parts[1]); // Note: months are 0-based
		}
	}
	
	// Maintain array of dates
	var dates = new Array();
		
	function addDate(date) {
		if (jQuery.inArray(date, dates) < 0 && dates.length <= 1) { dates.push(date); }
		else { dates.pop(); dates.push(date); }
	}	
	
	function removeDate(index) {dates.splice(index, 1);}

	// Adds a date if we don't have it yet, else remove it
	function addOrRemoveDate(date){
		var index = jQuery.inArray(date, dates); 
		if (index >= 0)
			removeDate(index);
		else 
			addDate(date);
	}

	// Takes a 1-digit number and inserts a zero before it
	function padNumber(number) {
		var ret = new String(number);
		if (ret.length == 1)
			ret = "0" + ret;
		return ret;
	}
	
	var datesBetween = new Array();
	Date.prototype.addDays = function(days) {
		var dat = new Date(this.valueOf())
		dat.setDate(dat.getDate() + days);
		return dat;
	}	

	function getDates(startDate, stopDate) {
	
		var dateArray = new Array();
		var currentDate = startDate;
		while (currentDate <= stopDate) {
			dateArray.push( new Date (currentDate) )
			currentDate = currentDate.addDays(1);
		}
		
		//Delete first element from array
		dateArray.shift();
		
		//Delete last element from array
		dateArray.pop();
		
		var yearb, monthb, dayb;
		
		for (var i=0;i<dateArray.length;i++) {
			yearb = dateArray[i].getFullYear();
			monthb = padNumber(dateArray[i].getMonth() + 1);
            dayb = padNumber(dateArray[i].getDate());
			// This depends on the datepicker's date format
			if ( nationOption.dateformat == "european" ) {
				dateArray[i] = dayb + "-" + monthb + "-" + yearb;
			} else if ( nationOption.dateformat == "american" ) {
				dateArray[i] = monthb + "/" + dayb + "/" + yearb;
			}
		}
		
		return dateArray;
	}
	
	var dateToday = new Date();
		
	var monthLong,monthShort,dayNames,dayNamesShort,dayNamesMin = [];
	
	monthLong = nationOption.monthLongNames.split(",");
	monthShort = nationOption.monthShortNames.split(",");
	dayNames = nationOption.dayLongNames.split(",");
	dayNamesShort = nationOption.dayShortNames.split(",");
	dayNamesMin = nationOption.dayMicroNames.split(",");
	
	// Main reservation selection 
	if ( nationOption.bookingCalendar ) {
		$('#reservation-data-selection').datepicker({ 
			altField: '#Date', 
			numberOfMonths: 2, 
			maxPicks: 2, 
			defaultDate: '',
			dateFormat:dateFormat,
			
			dayNames:dayNames,
			dayNamesShort:dayNamesShort,
			dayNamesMin:dayNamesMin,
			monthNames:monthLong,
			monthNamesShort:monthShort,
			
			minDate:dateToday,
			selectOtherMonths: true, 
			nextText: '<span class="icon-chevron-right"></span>', 
			prevText: '<span class="icon-chevron-left"></span>',
		
			onSelect: function(dateText, inst) { 
				addOrRemoveDate(dateText);				
				if (dates.length==2) {				
					datesBetween = getDates(parseDate(dates[0],nationOption.dateformat),parseDate(dates[1],nationOption.dateformat));
					$("#check-in-date").val(dates[0]);
					$("#check-out-date").val(dates[1]);
				}
			},
		
			beforeShowDay: function ( date ) {
							
				var year = date.getFullYear();
				// months and days are inserted into the array in the form, e.g "01/01/2009", but here the format is "1/1/2009"
				var month = padNumber(date.getMonth() + 1);
				var day = padNumber(date.getDate());
				
				// This depends on the datepicker's date format
				if ( nationOption.dateformat == "european" ) {
					var dateString = day + "-" + month + "-" + year;
				} else if ( nationOption.dateformat == "american" ) {
					var dateString = month + "/" + day + "/" + year;
				}
					
				var gotDate = jQuery.inArray(dateString, dates);
				if (gotDate >= 0) {
					// Enable date so it can be deselected. Set style to be highlighted
					return [true,"ui-state-highlight"]; 
				}
			
				var gotBetweenDate = jQuery.inArray(dateString, datesBetween);
				if (gotBetweenDate >= 0) {
					// Enable date so it can be deselected. Set style to be highlighted
					return [true,"ui-state-highlight"]; 
				}
						
				// Dates not in the array are left enabled, but with no extra style
				return [true, ""];
			}
		});
	}

	// Remove the style for the default selected day (today)
    $('.ui-datepicker-current-day').removeClass('ui-datepicker-current-day');

    // Reset the current selected day
    $('#Date').val('');
	
	// Show reservation info editing form
	$("#edit-reservation").click(function(){
		$(this).parents("#reservation-info").css("display","none");
		$("#resend-step2").css("display","block");
	});
	
	$("#cancel-resend-button").click(function(){
		$(this).parents("#resend-step2").css("display","none");
		$(".step2-reservation-info").css("display","block");
	});
	
	$("#reservation-step1-form").submit(function(){
		var checkin = $("#check-in-date");
		var checkout = $("#check-out-date");
	
		if (checkin.val()=="check-in date") {
			checkin.addClass("hightlight");
			return false;
		}
		if (checkout.val()=="check-out date") {
			checkout.addClass("hightlight");
			return false;
		} 
		if (checkin.val() == checkout.val()) {
			checkin.addClass("hightlight");
			checkout.addClass("hightlight");
			return false;
		}
		
		// parse a date in yyyy/mm/dd format
		function parseDate(input) {
			var parts = input.split('/');
			// new Date(year, month [, day [, hours[, minutes[, seconds[, ms]]]]])
			return new Date(parts[2], parts[0]-1, parts[1]); // Note: months are 0-based
		}
		
		var checkinDate = parseDate(checkin.val());
		var checkoutDate = parseDate(checkout.val());
		
		if (checkinDate > checkoutDate ) {
			checkin.addClass("hightlight");
			checkout.addClass("hightlight");
			return false;
		}
	});
	
	if ( nationOption.bookingCalendar ) {
	
	$("#resend-step2 #check-in-date, .step1 #check-in-date, .room-content-description #check-in-date").datepicker({
		
		dayNames:dayNames,
		dayNamesShort:dayNamesShort,
		dayNamesMin:dayNamesMin,
		monthNames:monthLong,
		monthNamesShort:monthShort,
		
		dateFormat:dateFormat,
			
		nextText: '<span class="icon-chevron-sign-right"></span>',
		prevText: '<span class="icon-chevron-sign-left"></span>',
		minDate: dateToday,
		beforeShow: function(input, inst) {
			$('#ui-datepicker-div').addClass('resend-datepicker');
		}
	});
	
	
	
	$("#resend-step2 #check-out-date, .step1 #check-out-date, .room-content-description #check-out-date").datepicker({
		
		dayNames:dayNames,
		dayNamesShort:dayNamesShort,
		dayNamesMin:dayNamesMin,
		monthNames:monthLong,
		monthNamesShort:monthShort,
		
		dateFormat:dateFormat,
			
		nextText: '<span class="icon-chevron-sign-right"></span>',
		prevText: '<span class="icon-chevron-sign-left"></span>',
		minDate: dateToday,
		beforeShow: function(input, inst) {
			$('#ui-datepicker-div').addClass('resend-datepicker');
		}
	});
	
	}
	
	
	/* Send saved values from step2 to step3 */
	var rentNumber = $("#room-rent-number").html();
	
	$(".room-reservation-wrap").submit(function(){		
		$(this).append("<input type='hidden' name='check-in' value='"+$("#reservation-check-in .reservation-date-value").html()+"'>")
		.append("<input type='hidden' name='check-out' value='"+$("#reservation-check-out .reservation-date-value").html()+"'>")
		.append("<input type='hidden' name='room-number' value='"+rentNumber+"'>");
		for (var i=1;i<=rentNumber;i++) {
			$(this).append("<input type='hidden' name='guests-info"+i+"' value='"+$("#room-guests-wrap"+i).text()+"'>");
			$(this).append("<input type='hidden' name='adult-room"+i+"' value='"+$("#room-guests-wrap"+i).children(".adult").text()+"'>");
			$(this).append("<input type='hidden' name='child-room"+i+"' value='"+$("#room-guests-wrap"+i).children(".children").text()+"'>");
		}
	});
	
	function IsEmail(email) {
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		return regex.test(email);
	}
	
	$("#payment-method-selections #radio-buttons-wrap input").click(function(){
		if ( $(this).val() == "creditcard" ) {
			$("#payment-reservation-form").css("display","block");
			$("#paypal-payment-reservation-form").css("display","none");
			$("#payment-reservation-form input").addClass("required-field");
		} else if ( $(this).val() == "paypal" ) {
			$("#paypal-payment-reservation-form").css("display","block");
			$("#payment-reservation-form").css("display","none");
			$("#payment-reservation-form input").removeClass("required-field");
		}
	});
		
	/* Send saved values from step3 to step4 */
	$("#step3-form").submit(function(){		
		var stopsending = false;
		var contentEmail = [];
		var compare = ($(".email-field").length==2) ? "true" : "false";
		
		//Check does form fields not empty
		$(".required-field").each(function() {
		
			//If current field email check it on validity
			if ( $(this).hasClass("email-field") ) {
				if ( !IsEmail($(this).val()) ) {
					$(this).addClass('hightlight');
					stopsending = true;
					return false;
				} 
				if (compare) {
					contentEmail.push($(this).val());
				}
			}
			
			//If email fields mismatch stop form sending and showing error message
			if ( compare && contentEmail.length==2 && (contentEmail[0] != contentEmail[1]) ) {
				alert("Email that you entered mismatch, please try to enter it again.");
				stopsending = true;
				return false;
			}
			
			if ( $(this).val() == '' ) {
				$(this).addClass('hightlight');
				stopsending = true;
				return false;
			}
			
			$(this).removeClass("hightlight");
		});
		
		if (stopsending) return false;
		
		$(this).append("<input type='hidden' name='check-in' value='"+$("#reservation-check-in .reservation-date-value").html()+"'>")
		.append("<input type='hidden' name='check-out' value='"+$("#reservation-check-out .reservation-date-value").html()+"'>")
		.append("<input type='hidden' name='room-number' value='"+rentNumber+"'>")
		.append("<input type='hidden' name='total-price' value='"+$("#total-price #price").html()+"'>")
		.append("<input type='hidden' name='room-title' value='"+$(".reservation-room-value").html()+"'>");
		for (var i=1;i<=rentNumber;i++) {
			$(this).append("<input type='hidden' name='room-adult"+i+"' value='"+$("#room-guests-wrap"+i).children(".adult").html().trim()+"'>");
			$(this).append("<input type='hidden' name='room-child"+i+"' value='"+$("#room-guests-wrap"+i).children(".children").html().trim()+"'>");
		}
		
		var bdcontent = "";
		$(".breakdown-content").each(function(j){
			bdcontent += "<tr>"+$(this).html()+"</tr>";
		});
		
		$(this).append("<input type='hidden' name='breakdown' value='"+bdcontent+"'>");
		
	});
})

})(window.jQuery);

