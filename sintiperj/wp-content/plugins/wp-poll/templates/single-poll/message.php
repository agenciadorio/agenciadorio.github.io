<?php
/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/


if ( ! defined('ABSPATH')) exit;  // if direct access 

	if( empty( $poll_id ) ) $poll_id = get_the_ID();
	
	$poll_deadline = get_post_meta( $poll_id, 'poll_deadline', true );
	if( empty( $poll_deadline ) ) return;
	
	$current_time 	= current_time('timestamp');
	$poll_time 		= strtotime( $poll_deadline . " +1 day" );
	
	
	// Color section
	$wpp_color_message_text_normal = get_option( 'wpp_color_message_text_normal' );
	if( empty( $wpp_color_message_text_normal ) ) $wpp_color_message_text_normal = '#757575';
	
	$wpp_color_message_background_normal = get_option( 'wpp_color_message_background_normal' );
	if( empty( $wpp_color_message_background_normal ) ) $wpp_color_message_background_normal = '#EEEEEE';
	
	$wpp_color_message_text_error = get_option( 'wpp_color_message_text_error' );
	if( empty( $wpp_color_message_text_error ) ) $wpp_color_message_text_error = '#fff';
	
	$wpp_color_message_background_error = get_option( 'wpp_color_message_background_error' );
	if( empty( $wpp_color_message_background_error ) ) $wpp_color_message_background_error = '#DE746C';
	
	
	global $wpp_css;
	$wpp_css .= ".wpp_message{ color: $wpp_color_message_text_normal; background-color:$wpp_color_message_background_normal; }";
	$wpp_css .= ".wpp_message.wpp_poll_closed { color: $wpp_color_message_text_error; background-color:$wpp_color_message_background_error; }";
		
		
		
	if( $current_time > $poll_time ):
		
		$time_ago = human_time_diff( $poll_time, $current_time ); 
		
		echo
		sprintf(
			'<p class="wpp_message wpp_poll_closed">%s</p>',
			apply_filters(
				'wpp_filter_message_poll_closed_html', 
				"<i class='fa fa-envelope'></i> ".__('Sorry, This poll is completed on', WPP_TEXT_DOMAIN)." <b><i>$poll_deadline</i></b> - 
				about <b><i>$time_ago</i></b> ago" 
			)
		);
		
		$GLOBALS['wpp_status'] = 'closed';
	else:
		
		$time_remaining = human_time_diff( $poll_time, $current_time ); 
		
		echo
		sprintf(
			'<p class="wpp_message">%s</p>',
			apply_filters(
				'wpp_filter_message_poll_open_html', 
				"<i class='fa fa-envelope'></i> ".__("This poll is open till ", WPP_TEXT_DOMAIN)." $poll_deadline<b><i></i></b> - 
				".__('remaining only', WPP_TEXT_DOMAIN)." <b><i>$time_remaining</i></b> " 
			)
			
		);
		$GLOBALS['wpp_status'] = 'open';
	endif;

	
	