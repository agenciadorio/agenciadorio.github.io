jQuery(document).ready(function($){
    $('.cnb-color-field').wpColorPicker();
	$("#cnb_settings").click(function() {
		$("#settings").slideDown();
		$("#cnb_settings").remove();
	});
	$("span.check-settings").click(function() {
		if($("#settings").is(":hidden")) {
			$("#settings").slideDown('fast');
			$("div#cnb_settings").remove();
		}
		$("tr.appearance input").addClass("red-background").focus();
		$('html, body').animate({
        	scrollTop: $("tr.appearance").offset().top
    	}, 500);
    	$("span.check-settings").remove();
	});
	$(".cnb-switch-back").click(function() {		
		if($("#settings").is(":hidden")) {
			$("#settings").slideDown('fast');
			$("div#cnb_settings").remove();
		}
		$("tr.classic ").addClass("red-background").focus();
		$('html, body').animate({
        	scrollTop: $("tr.classic").offset().top
    	}, 500);
	});
});

const textButtonField = document.querySelector("#buttonTextField");

textFieldChars();

textButtonField.addEventListener("keyup", textFieldChars);

function textFieldChars() {
	chars = textButtonField.value.length;
	if(chars>0) {
		toggleVisibility('.appearance-options', 'none');
		toggleVisibility('.classic', 'none');
		toggleVisibility('.appearanceDesc', 'block');
		toggleVisibility('.notempty', 'block')
	} else {
		toggleVisibility('.appearance-options', 'block');
		toggleVisibility('.classic', 'table-row');
		toggleVisibility('.appearanceDesc', 'none');
		toggleVisibility('.notempty', 'none')
	}
	if( chars > 15 ) {
		document.querySelector('.lengthwarning').innerHTML = "Your text is " + chars + " characters.";
		document.querySelector('.lengthwarning').style.color = '#C00';
	} else if(chars > 0) {
		document.querySelector('.lengthwarning').innerHTML = "Your text is " + chars + " character(s).";
		document.querySelector('.lengthwarning').style.color = '#444';
	} else {
		document.querySelector('.lengthwarning').innerHTML = "Leave blank to only show the button with icon.";
		document.querySelector('.lengthwarning').style.color = '#444';
	}
}

function toggleVisibility(selector, value) {
	document.querySelector(selector).style.display = value;
}