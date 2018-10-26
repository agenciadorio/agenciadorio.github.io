<?php
/*
Plugin Name: Login Widget With Shortcode
Plugin URI: https://wordpress.org/plugins/login-sidebar-widget/
Description: This is a simple login form in the widget. just install the plugin and add the login widget in the sidebar. Thats it. :)
Version: 5.7.7
Text Domain: login-sidebar-widget
Domain Path: /languages
Author: aviplugins.com
Author URI: https://www.aviplugins.com/
*/

/**
	  |||||   
	<(`0_0`)> 	
	()(afo)()
	  ()-()
**/

// CONFIG

define( 'LSW_DIR_NAME', 'login-sidebar-widget' );

include_once dirname( __FILE__ ) . '/config/config_emails.php';
include_once dirname( __FILE__ ) . '/config/config_default_fields.php';

// CONFIG

function plug_install_lsw(){
	include_once dirname( __FILE__ ) . '/includes/class_settings.php';
	include_once dirname( __FILE__ ) . '/includes/class_form.php';
	include_once dirname( __FILE__ ) . '/includes/class_forgot_password.php';
	include_once dirname( __FILE__ ) . '/includes/class_message.php';
	include_once dirname( __FILE__ ) . '/includes/class_login_log_adds.php';
	include_once dirname( __FILE__ ) . '/includes/class_security.php';
	include_once dirname( __FILE__ ) . '/includes/class_login_log.php';
	include_once dirname( __FILE__ ) . '/includes/class_paginate.php';
	include_once dirname( __FILE__ ) . '/login_afo_widget.php';
	include_once dirname( __FILE__ ) . '/process.php';
	include_once dirname( __FILE__ ) . '/login_afo_widget_shortcode.php';
	include_once dirname( __FILE__ ) . '/functions.php';
}

class lsw_init_load {
	function __construct() {
		plug_install_lsw();
	}
}
new lsw_init_load;

new login_settings;
new afo_login_log;

add_action( 'widgets_init', function(){ register_widget( 'login_wid' ); } );

add_action( 'init', 'login_validate' );
add_action( 'init', 'forgot_pass_validate' );

add_shortcode( 'login_widget', 'login_widget_afo_shortcode' );
add_shortcode( 'forgot_password', 'forgot_password_afo_shortcode' );

add_action('admin_init', 'login_log_ip_data');

add_action('plugins_loaded', 'security_init');

add_action( 'plugins_loaded', 'login_widget_afo_text_domain' );

function lsw_setup_init() {
	global $wpdb, $forgot_password_link_mail_subject, $forgot_password_link_mail_body, $new_password_mail_subject, $new_password_mail_body;
	
	// log tables //
	$wpdb->query("CREATE TABLE IF NOT EXISTS `".$wpdb->base_prefix."login_log` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `ip` varchar(50) NOT NULL,
	  `msg` varchar(255) NOT NULL,
	  `l_added` datetime NOT NULL,
	  `l_status` enum('success','failed','blocked') NOT NULL,
  	  `l_type` enum('new','old') NOT NULL,
	  PRIMARY KEY (`id`)
	)");
	// log tables //
		
	update_option( 'forgot_password_link_mail_subject', $forgot_password_link_mail_subject );
    update_option( 'forgot_password_link_mail_body', $forgot_password_link_mail_body );
	update_option( 'new_password_mail_subject', $new_password_mail_subject );
    update_option( 'new_password_mail_body', $new_password_mail_body );
	
	$lss = new login_settings;
	if( get_option('custom_style_afo') == '' ){
		update_option( 'custom_style_afo', $lss->default_style );
	}
	
}

register_activation_hook( __FILE__, 'lsw_setup_init' );
