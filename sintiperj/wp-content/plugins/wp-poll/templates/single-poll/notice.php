<?php
/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/


if ( ! defined('ABSPATH')) exit;  // if direct access 


// Color section
	$wpp_color_notice_text_success = get_option( 'wpp_color_notice_text_success' );
	if( empty( $wpp_color_notice_text_success ) ) $wpp_color_notice_text_success = '#fff';
	
	$wpp_color_notice_background_success = get_option( 'wpp_color_notice_background_success' );
	if( empty( $wpp_color_notice_background_success ) ) $wpp_color_notice_background_success = '#17A15E';
	
	$wpp_color_notice_text_error = get_option( 'wpp_color_notice_text_error' );
	if( empty( $wpp_color_notice_text_error ) ) $wpp_color_notice_text_error = '#fff';
	
	$wpp_color_notice_background_error = get_option( 'wpp_color_notice_background_error' );
	if( empty( $wpp_color_notice_background_error ) ) $wpp_color_notice_background_error = '#DE746C';
	
	
	global $wpp_css;
	$wpp_css .= ".wpp_notice.wpp_success{ color: $wpp_color_notice_text_success; background-color:$wpp_color_notice_background_success; }";
	$wpp_css .= ".wpp_notice.wpp_error{ color: $wpp_color_notice_text_error; background-color:$wpp_color_notice_background_error; }";
		
	
	
?>

<p class="wpp_notice"></p>
