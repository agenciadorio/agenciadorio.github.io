<?php

/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class WPP_Shortcode_Poll{
	
	public function __construct(){
		add_shortcode('poll', array( $this, 'wpp_shortcode_poll_display') );
		add_filter( 'widget_text', 'do_shortcode', 20);
	}
	
	public function wpp_shortcode_poll_display($atts, $content = null ) {
		
		$atts = shortcode_atts( array(
			'id' => ''
		), $atts);
		
		$poll_id = empty( $atts['id'] ) ? '' : $atts['id'];
		
		ob_start();		
		include( WPP_PLUGIN_DIR . 'templates/shortcodes/poll.php');
		// include( WPP_PLUGIN_DIR . 'templates/check.php');
		return ob_get_clean();		
	}
	
} new WPP_Shortcode_Poll();