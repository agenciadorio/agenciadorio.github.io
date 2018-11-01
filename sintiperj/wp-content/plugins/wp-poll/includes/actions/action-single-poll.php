<?php

/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


	if ( ! function_exists( 'wpp_action_single_poll_main_function' ) ) {
		function wpp_action_single_poll_main_function() {
			
			$WPP_Functions = new WPP_Functions();
			$template_sections = $WPP_Functions->wpp_poll_template_sections();
			
			$wpp_poll_template = get_option( 'wpp_poll_template' );
			if( empty( $wpp_poll_template ) ) $wpp_poll_template = array();
			
			$wpp_use_customized_template = get_option( 'wpp_use_customized_template' );
			if( empty( $wpp_use_customized_template ) ) $wpp_use_customized_template = 'no';
			
			if( $wpp_use_customized_template == 'yes' ) {
				
				foreach( $wpp_poll_template as $section_key ){
				
					$callable = isset( $template_sections[$section_key]['callable'] ) ? $template_sections[$section_key]['callable'] : '';
					if( empty( $callable ) ) continue;

					do_action( "wpp_action_single_poll_$callable" );
				}
			}
			else {
				
				foreach( $template_sections as $section_key => $section ){
				
					$callable = isset( $section['callable'] ) ? $section['callable'] : '';
					if( empty( $callable ) ) continue;

					do_action( "wpp_action_single_poll_$callable" );
				}
			}			
		}
	}
	add_action( 'wpp_action_single_poll_main', 'wpp_action_single_poll_main_function', 10 );
	
	
	
	// Nested Actions 
	
	if ( ! function_exists( 'wpp_action_single_poll_title_function' ) ) {
		function wpp_action_single_poll_title_function($poll_id) {
			include( WPP_PLUGIN_DIR. 'templates/single-poll/title.php');			
		}
	}
	add_action( 'wpp_action_single_poll_title', 'wpp_action_single_poll_title_function', 10, 1 );
	
	if ( ! function_exists( 'wpp_action_single_poll_thumb_function' ) ) {
		function wpp_action_single_poll_thumb_function($poll_id) {
			include( WPP_PLUGIN_DIR. 'templates/single-poll/thumb.php');			
		}
	}
	add_action( 'wpp_action_single_poll_thumb', 'wpp_action_single_poll_thumb_function', 10, 1 );
	
	if ( ! function_exists( 'wpp_action_single_poll_content_function' ) ) {
		function wpp_action_single_poll_content_function($poll_id) {
			include( WPP_PLUGIN_DIR. 'templates/single-poll/content.php');			
		}
	}
	add_action( 'wpp_action_single_poll_content', 'wpp_action_single_poll_content_function', 10, 1 );
	
	
	if ( ! function_exists( 'wpp_action_single_poll_options_function' ) ) {
		function wpp_action_single_poll_options_function($poll_id) {
			include( WPP_PLUGIN_DIR. 'templates/single-poll/options.php');			
		}
	}
	add_action( 'wpp_action_single_poll_options', 'wpp_action_single_poll_options_function', 10, 1 );
	
	if ( ! function_exists( 'wpp_action_single_poll_notice_function' ) ) {
		function wpp_action_single_poll_notice_function($poll_id) {
			include( WPP_PLUGIN_DIR. 'templates/single-poll/notice.php');			
		}
	}
	add_action( 'wpp_action_single_poll_notice', 'wpp_action_single_poll_notice_function', 10, 1 );
	
	if ( ! function_exists( 'wpp_action_single_poll_message_function' ) ) {
		function wpp_action_single_poll_message_function($poll_id) {
			include( WPP_PLUGIN_DIR. 'templates/single-poll/message.php');			
		}
	}
	add_action( 'wpp_action_single_poll_message', 'wpp_action_single_poll_message_function', 10, 1 );
	
	if ( ! function_exists( 'wpp_action_single_poll_buttons_function' ) ) {
		function wpp_action_single_poll_buttons_function($poll_id) {
			include( WPP_PLUGIN_DIR. 'templates/single-poll/buttons.php');			
		}
	}
	add_action( 'wpp_action_single_poll_buttons', 'wpp_action_single_poll_buttons_function', 10, 1 );
	
	if ( ! function_exists( 'wpp_action_single_poll_results_function' ) ) {
		function wpp_action_single_poll_results_function($poll_id) {
			include( WPP_PLUGIN_DIR. 'templates/single-poll/results.php');			
		}
	}
	add_action( 'wpp_action_single_poll_results', 'wpp_action_single_poll_results_function', 10, 1 );
	
	
	if ( ! function_exists( 'wpp_action_single_poll_comments_function' ) ) {
		function wpp_action_single_poll_comments_function($poll_id) {
			include( WPP_PLUGIN_DIR. 'templates/single-poll/comments.php');			
		}
	}
	add_action( 'wpp_action_single_poll_comments', 'wpp_action_single_poll_comments_function', 10, 1 );
	
	
	
	
	