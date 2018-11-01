jQuery(document).ready(function($) {

	$("#poll_deadline").datepicker({ minDate: new Date, dateFormat: 'dd-mm-yy' });
	$(function() { $( ".poll_option_container" ).sortable({ handle: ".poll_option_single_sorter" }); });
	$(function() { $( ".wpp_td" ).sortable({ 
		handle: ".wpp_td_single_sorter",
		revert: true
	}); });
	
	$(document).on('click', '.wpp_td .wpp_td_add_section', function() {
		
		section_key = $(this).attr('section_key');
		label = $(this).parent().find('.wpp_td_label').html();
		
		__DATA__ = "<li class='wpp_td_single "+section_key+"'><span class='wpp_td_label'>"+label+"</span>" +
		"<div class='wpp_td_icon wpp_td_single_remove'><i class='fa fa-times'></i></div>" +
		"<div class='wpp_td_icon wpp_td_single_sorter'><i class='fa fa-sort'></i></div>" +
		"<input type='hidden' name='wpp_poll_template[]' value='"+section_key+"' /></li>";
		
		$('.wpp_td_templates').append( __DATA__ ).find("."+section_key).hide().fadeIn();
	})
	
	$(document).on('click', '.wpp_td_templates .wpp_td_single .wpp_td_single_remove', function() {
		
		step = $(this).attr('step');

		if( step == 'f' ){
			$(this).html( "<i class='fa fa-check'></i>" );
			$(this).attr('step','l');
		}
		else $(this).parent().remove();
	})
	
	
	
	$(document).on('click', '.wp_poll_shortcode_copy', function() {
		
		__COPY_TEXT__ = $('#wp_poll_shortcode').val();
		
		try {
			$('#wp_poll_shortcode').select();
            document.execCommand('copy');
        } catch(e) {
            alert(e);
        }
	})
	
	
	$(document).on('change', '#wpp_report_form select', function() {
		
		$(this).closest('form').trigger('submit');
	})
	
	
	$(document).on('click', '.poll_meta_box .poll_option_remove', function() {
		
		__STATUS__ = $(this).attr('status');
		
		if( __STATUS__ == 0 ) {
			
			$(this).attr('status',1);
			$(this).html('<i class="fa fa-check" aria-hidden="true"></i>');
		} else {
			
			$(this).parent().remove();
		}
		
		
		
	})
	
	$(document).on('click', '.poll_meta_box .add_new_option', function() {
		
		__TIME__ = $.now();
		
		__NEW_OPTION__ = 
		'<li class="poll_option_single">'+
			'<span>Option Value</span>'+
			'<input type="text" name="poll_meta_options['+__TIME__+']" value=""/>'+
			'<div class="poll_option_remove" status=0><i class="fa fa-times" aria-hidden="true"></i></div>'+
			'<div class="poll_option_single_sorter"><i class="fa fa-sort" aria-hidden="true"></i></div>'+
		'</li>';
		
		$('.poll_option_container').append( __NEW_OPTION__ );
	})
	
		// var chart_booking = new CanvasJS.Chart("vb_stat_by_booking", {
			
				// theme: "theme2",//theme1
				// zoomEnabled: true,
				// title:{
					// text: stat_title             
				// },
				// animationEnabled: true,   // change to true
				// axisX:{    
					// valueFormatString:  "#,#",
				// },
				// data: [              {
					// type: "bar",
					// dataPoints: arr_data
				// }]
			// });
			// chart_booking.render();
			
			
	

});	


	function copyToClipboard(element) {
	  var $temp = jQuery("<input>");
	  jQuery("body").append($temp);
	  $temp.val(jQuery(element).val()).select();
	  document.execCommand("copy");
	  $temp.remove();
	  
	  jQuery('#wp_shortcode_notice').remove();
	  jQuery(element).parent().append('<span id="wp_shortcode_notice">Copied to Clipboard.</span>');
	}	




