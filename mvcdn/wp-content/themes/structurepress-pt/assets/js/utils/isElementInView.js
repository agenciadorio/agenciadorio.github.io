/**
 * Returns true if at least one part of the element is in viewport
 */
define(['jquery'], function ($) {
	return function ($elm) {
		var $window = $(window);

		var docViewTop = $window.scrollTop();
		var docViewBottom = docViewTop + $window.height();

		var elemTop = $elm.offset().top;
		var elemBottom = elemTop + $elm.height();

		return ((elemBottom > docViewTop) && (elemTop < docViewBottom));
	};
});