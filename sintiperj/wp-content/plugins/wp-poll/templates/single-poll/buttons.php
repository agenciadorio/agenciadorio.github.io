<?php
/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/


if ( ! defined('ABSPATH')) exit;  // if direct access 
	
	if( empty( $poll_id ) ) $poll_id = get_the_ID();
	
	$wpp_status = isset( $GLOBALS['wpp_status'] ) ? $GLOBALS['wpp_status'] : '';
	if( $wpp_status == 'closed' ) $disabled = 'disabled';
	else $disabled = '';
	
	$submit_button_text = get_option( 'wpp_btn_text_submit' );
	if( empty( $submit_button_text ) ) 
	$submit_button_text = __('Submit', WPP_TEXT_DOMAIN);
	$submit_button_text = apply_filters( 'wpp_filter_submit_button_text', $submit_button_text );
	
	$results_button_text = get_option( 'wpp_btn_text_results' );
	if( empty( $results_button_text ) ) 
	$results_button_text = __('Results', WPP_TEXT_DOMAIN);
	$results_button_text = apply_filters( 'wpp_filter_results_button_text', $results_button_text );
	
	// Color section
	$wpp_color_submit = get_option( 'wpp_color_submit' );
	if( empty( $wpp_color_submit ) ) $wpp_color_submit = '#787878';
	
	$wpp_color_results = get_option( 'wpp_color_results' );
	if( empty( $wpp_color_results ) ) $wpp_color_results = '#009D91';
	
	$wpp_color_submit_dark = wpp_dark_color($wpp_color_submit);
	$wpp_color_results_dark = wpp_dark_color($wpp_color_results);
	
	global $wpp_css;
	$wpp_css .= ".wpp_submit{ background-color: $wpp_color_submit; }";
	$wpp_css .= ".wpp_submit:hover{ background-color: $wpp_color_submit_dark; }";
	$wpp_css .= ".wpp_result{ background-color: $wpp_color_results; }";
	$wpp_css .= ".wpp_result:hover{ background-color: $wpp_color_results_dark; }";
		
	

	echo "<div class='button wpp_submit' wpp_status='$disabled' poll_id=".$poll_id.">$submit_button_text</div>";
	echo "<div class='button wpp_result' poll_id=".$poll_id.">$results_button_text</div>";
	