/**
 * jQuery easing function: easeInOutQuad,
 * extends jQuery easing methods
 * https://github.com/gdsmith/jquery.easing/blob/master/jquery.easing.js#L25
 */
define(['jquery'], function ($) {
	return function () {
		$.extend( $.easing, {
			easeInOutQuad: function (x, t, b, c, d) {
				if ((t/=d/2) < 1) {
					return c/2*t*t + b;
				}
				return -c/2 * ((--t)*(t-2) - 1) + b;
			}
		});
	};
});