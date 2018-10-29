/*!
 * myStickymenu by m.r.d.a
 * v1.8.7
 */

(function( $ ) {
	'use strict';

//jQuery(window).on("load", function($){
//jQuery(document).ready(function($){

	//disable at small screen sizes
	var myfixed_disable_small = parseInt(mysticky_name.mysticky_disable_at_width_string);
	var mybodyWidth = parseInt(document.body.clientWidth);
	
	if (mybodyWidth >= myfixed_disable_small) {
		
		
	// select mysticky class
		var mysticky_navbar = document.querySelector(mysticky_name.mysticky_string);

	// add mysticky_wrap div around selected mysticky class
		var parentmysticky = mysticky_navbar.parentNode;
	
		var wrappermysticky = document.createElement('div');
		var position = 0;
		for(var i = 0; i < parentmysticky.childNodes.length; i++) {
            if(parentmysticky.childNodes[i] == mysticky_navbar) {
                position = i;
                break;
            }
        }
		wrappermysticky.id = 'mysticky-wrap';
		wrappermysticky.appendChild(mysticky_navbar);
		parentmysticky.insertBefore(wrappermysticky, parentmysticky.childNodes[position]);

		// add mysticky_nav div inside selected mysticky class
		var parentnav = mysticky_navbar.parentNode;
		var wrappernav = document.createElement('div');
		wrappernav.id = 'mysticky-nav';
		parentnav.replaceChild(wrappernav, mysticky_navbar);
		wrappernav.appendChild(mysticky_navbar);

		// add myfixed and wrapfixed class to divs while scroll
		var mysticky_active_on_height = parseInt(mysticky_name.mysticky_active_on_height_string) ;
		var origOffsetY = mysticky_active_on_height ;
		
		var hasScrollY = 'scrollY' in window;	
	
	function onScroll(e) {
		var mydivHeight = ((mysticky_navbar.offsetHeight) + 'px');
		var mydivWidth = ((mysticky_navbar.offsetWidth) + 'px');
		var mydivReset = '';
    
		var y = hasScrollY ? window.scrollY : document.documentElement.scrollTop;
			y >= origOffsetY  ? mysticky_navbar.classList.add('myfixed') : mysticky_navbar.classList.remove('myfixed');
			y >= origOffsetY  ? wrappernav.classList.add('wrapfixed') : wrappernav.classList.remove('wrapfixed');
			y >= origOffsetY  ? mysticky_navbar.style.width = mydivWidth : mysticky_navbar.style.width = mydivReset;
			y >= origOffsetY  ? wrappermysticky.style.height = mydivHeight : wrappermysticky.style.height = mydivReset;
	}

	document.addEventListener('scroll', onScroll);
	
	function OnResizeDocument () {
		mysticky_navbar.style.removeProperty("width");
		mysticky_navbar.classList.remove('myfixed');
		wrappernav.classList.remove('wrapfixed');
		wrappermysticky.style.removeProperty("width");
		parentnav.style.removeProperty("height");
		
	}

	window.addEventListener('resize', OnResizeDocument);
}	
//});

})(jQuery);