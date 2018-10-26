(function($){
$(document).ready(function() {

	function setCookie (name, value, expires, path, domain, secure) {
	      document.cookie = name + "=" + escape(value) +
	        ((expires) ? "; expires=" + expires : "") +
	        ((path) ? "; path=" + path : "") +
	        ((domain) ? "; domain=" + domain : "") +
	        ((secure) ? "; secure" : "");
	}
	function getCookie(name) {
		var cookie = " " + document.cookie;
		var search = " " + name + "=";
		var setStr = null;
		var offset = 0;
		var end = 0;
		if (cookie.length > 0) {
			offset = cookie.indexOf(search);
			if (offset != -1) {
				offset += search.length;
				end = cookie.indexOf(";", offset)
				if (end == -1) {
					end = cookie.length;
				}
				setStr = unescape(cookie.substring(offset, end));
			}
		}
		return(setStr);
	}

	if(!getCookie("uwchidemessage")) {
		$('.uwc-message').css("display","block");
	}

	$('.uwc-message button.notice-dismiss').click(
		function() {
			setCookie("uwchidemessage", 1);
			$('.uwc-message').hide();
		}
	);
});
})(jQuery);