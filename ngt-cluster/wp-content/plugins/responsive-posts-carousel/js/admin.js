jQuery(document).ready(function($) {
    document.getElementsByClassName("rpc-tab")[0].firstElementChild.click();
	var b_ui_styles = { css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .7, 
            color: '#fff' 
        } };
	$('#wrap_term_ select').select2();
	$('#wrap_posts_ select').select2();
	$('.colorpicker').wpColorPicker();
	
	$('#wrap_post_type select').change(function(event) {
		event.preventDefault();
        $.blockUI(b_ui_styles);
		var data = {
			action: 'rpc_get_posts',
			post_type: $(this).val(),
		}
		$.post(ajaxurl, data, function(resp) {
			// console.log(resp);
			$('#wrap_posts_ select').html(resp);
			$.unblockUI();
		});
	});
    $('#wrap_taxonomy select').change(function(event) {
        event.preventDefault();
        $.blockUI(b_ui_styles);
        var element = jQuery(this);
        jQuery.post(ajaxurl, {action: 'rpc_get_terms' , taxonomy: element.val()}, function(resp) {
			element.closest('table').find('.td_term_').html(resp);
			element.closest('table').find('.wcp-term').select2();
			$.unblockUI();
        });
    });

    if ($('#wrap_display_by select').val() == 'taxonomy') {
    	$('#wrap_post_type').hide();
    	$('#wrap_posts_').hide();
    } else {
    	$('#wrap_taxonomy').hide();
    	$('#wrap_term_').hide();
    }

    $('#wrap_display_by select').change(function(event) {
    	if ($(this).val() == 'taxonomy') {
	    	$('#wrap_post_type').hide();
	    	$('#wrap_posts_').hide();
	    	$('#wrap_taxonomy').show();
	    	$('#wrap_term_').show();
    	} else {
	    	$('#wrap_post_type').show();
	    	$('#wrap_posts_').show();
	    	$('#wrap_taxonomy').hide();
	    	$('#wrap_term_').hide();
    	}
    });
});
function openCarouselTab(evt, tabName) {
	evt.preventDefault();
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("rpc-tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("rpc-tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}