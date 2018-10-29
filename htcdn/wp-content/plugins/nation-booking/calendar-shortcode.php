<?php 

function nation_booking_add_booking_script() {
	global $wpdb; 
	
	$currency = $wpdb->get_var( "SELECT currency_symbol FROM ".$wpdb->prefix."nation_booking_settings");
	
	wp_register_script('custom-calendar', plugins_url('includes/js/calendar-script.js', __FILE__), array('jquery'), false, true);
	wp_enqueue_script('custom-calendar');
	
	//Pass some variables to custom-calendar 
	$passVar = array( 'ajaxurl' => admin_url('admin-ajax.php'), 'currency' => $currency, 'available' => __('available','nation'), 'booked' => __('booked','nation') );
    wp_localize_script( 'custom-calendar', 'bookingOption', $passVar );

	wp_register_style('nation-calendar-style', plugins_url('includes/css/calendar-style.css', __FILE__));
	wp_enqueue_style('nation-calendar-style');
	
}
add_action('wp_enqueue_scripts', 'nation_booking_add_booking_script');


function shortcodeInit( $atts, $content = null ) {
	global $wp_locale;
	global $wpdb;
		
	$dayNames =	__("['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday']","nation");
	$dayShort = __("['Sun','Mon','Tue','Wed','Thu','Fri','Sat']","nation");
	$dayMin = __("['Su','Mo','Tu','We','Th','Fr','Sa']","nation");
	$monthShort = __("['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']","nation");
	$monthLong = __("['January','February','March','April','May','June','July','August','September','October','November','December']","nation");	
	
	extract(shortcode_atts(array(
		'id' => '',
	), $atts, 'error'));

	$content = "
	
	<script>
	(function($) {
		$(document).ready(function() { 
			$('#nation-calendar-view-$id').nationcalendar({
				
				dayNames:".$dayNames.",
				dayNamesShort:".$dayShort.",
				dayNamesMin:".$dayMin.",
				monthNames:".$monthLong.",
				monthNamesShort:".$monthShort.",
				
				calID:$id,		
				beforeShowDay: function (date) {return [false, ''];}
			});
		})
	})(jQuery);
	</script>	
	<div id='nation-calendar-view-$id' class='nation-datepicker-initialize'><span class='calendar-loading'>&nbsp;</span></div>";
	
	return $content;
	
}
add_shortcode('nation_calendar', 'shortcodeInit');

?>