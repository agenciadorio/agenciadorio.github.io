<?php

/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/

if ( ! defined('ABSPATH')) exit;  // if direct access

class class_wpp_settings{

	public function __construct(){
		
		add_action('admin_menu', array( $this, 'wpp_menu_init' ));
	}

	public function wpp_menu_reports(){	
		include( WPP_PLUGIN_DIR. 'includes/menus/reports.php');			
	}
	
	public function wpp_menu_settings(){	
		include( WPP_PLUGIN_DIR. 'includes/menus/settings.php');			
	}

	public function wpp_help(){	
		include( WPP_PLUGIN_DIR. 'includes/menus/help.php');			
	}

	public function wpp_menu_init() {
		add_submenu_page('edit.php?post_type=poll', __('Reports',WPP_TEXT_DOMAIN), __('Reports',WPP_TEXT_DOMAIN), 'manage_options', 'wpp_reports', array( $this, 'wpp_menu_reports' ));	
		add_submenu_page('edit.php?post_type=poll', __('Settings',WPP_TEXT_DOMAIN), __('Settings',WPP_TEXT_DOMAIN), 'manage_options', 'wpp_menu_settings', array( $this, 'wpp_menu_settings' ));	
		add_submenu_page('edit.php?post_type=poll', __('Help',WPP_TEXT_DOMAIN), __('Help',WPP_TEXT_DOMAIN), 'manage_options', 'wpp_help', array( $this, 'wpp_help' ));	
	}
}
	
new class_wpp_settings();