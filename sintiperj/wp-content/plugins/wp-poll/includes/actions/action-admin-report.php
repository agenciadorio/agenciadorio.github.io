<?php

/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


	if ( ! function_exists( 'wpp_admin_action_report_function' ) ) {
		function wpp_admin_action_report_function($poll_id) {
			
			include( WPP_PLUGIN_DIR. 'templates/admin/reports/report-simple.php');			
		}
	}
	add_action( 'wpp_admin_action_report', 'wpp_admin_action_report_function', 10, 1 );
	
	if ( ! function_exists( 'wpp_admin_action_before_report_function' ) ) {
		function wpp_admin_action_before_report_function($poll_id) {
			
			include( WPP_PLUGIN_DIR. 'templates/admin/reports/report-adon-notice.php');			
		}
	}
	add_action( 'wpp_admin_action_before_report', 'wpp_admin_action_before_report_function', 10, 1 );
	
	
	
	