<?php
/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/


if ( ! defined('ABSPATH')) exit;  // if direct access 
	
	if( empty( $poll_id ) ) $poll_id = get_the_ID();
	
	$poll_meta_options 	= get_post_meta( $poll_id, 'poll_meta_options', true );
	$poll_meta_multiple	= get_post_meta( $poll_id, 'poll_meta_multiple', true );
		
	if( $poll_meta_multiple == 'yes' ) $input_type_style = 'checkbox';
	else $input_type_style = 'radio';
	
	$wpp_status = isset( $GLOBALS['wpp_status'] ) ? $GLOBALS['wpp_status'] : '';
	if( $wpp_status == 'closed' ) $disabled = 'disabled';
	else $disabled = '';
	
	if( empty( $poll_meta_options ) ) $poll_meta_options = array();
	
	echo "<ul class='wpp_option_list'>";
	foreach( $poll_meta_options as $option_id => $option_value ) {
		
		if( empty( $option_value ) ) continue;
		
		echo "
		<li class='wpp_option_single' option_id='$option_id'>
			<input type='$input_type_style' $disabled value='$option_id' name='submit_poll_option' class='submit_poll_option' id='$option_id' />
			<label for='$option_id' class='option_title'>$option_value</span>
		</li>";
	}
	echo "</ul>";
	
	
	// Visitors Option
	$poll_meta_new_option	= get_post_meta( $poll_id, 'poll_meta_new_option', true );
	if( empty( $poll_meta_new_option ) ) $poll_meta_new_option = 'no';
	
	$wpp_new_option_save_text = __('Confirm Add', WPP_TEXT_DOMAIN);
	
	if( $poll_meta_new_option == 'yes' ) {
		
		$add_option_button_text = get_option( 'wpp_btn_text_new_option' );
		if( empty( $add_option_button_text ) ) 
		$add_option_button_text = __('New Option', WPP_TEXT_DOMAIN);
		$add_option_button_text = apply_filters( 'wpp_filter_results_button_text', $add_option_button_text );
		
		echo "
		<div class='wpp_visitor_option'>
			<div poll_id=$poll_id class='button wpp_new_option wpp_visitor_option_new'>$add_option_button_text</div>
			<div class='wpp_new_option_box_container'>
				<div class='woc_alert_box'>
					<span class='alert_box_close'><i class='fa fa-times-circle-o'></i></span>
					<p class='fa fa-plus-square'></p>
					<p><input type='text' placeholder='Option Name' class='wpp_new_option_input'/></p>
					<p poll_id=$poll_id class='button wpp_visitor_option_new_confirm'>$wpp_new_option_save_text</p>
				</div>
			</div>
		</div><br>";
	}
	
	
	
	// Color section
	$wpp_color_new_option = get_option( 'wpp_color_new_option' );
	if( empty( $wpp_color_new_option ) ) $wpp_color_new_option = '#6C2EB9';
	
	$wpp_color_options = get_option( 'wpp_color_options' );
	if( empty( $wpp_color_options ) ) $wpp_color_options = '#2D2D2D';
	
	$wpp_color_new_option_dark = wpp_dark_color( $wpp_color_new_option );
	
	global $wpp_css;
	$wpp_css .= ".wpp_new_option{ background-color: $wpp_color_new_option; }";
	$wpp_css .= ".wpp_new_option:hover{ background-color: $wpp_color_new_option_dark; }";
	$wpp_css .= ".wpp_option_single .option_title{ color: $wpp_color_options; }";
		
	