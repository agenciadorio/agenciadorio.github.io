<?php

/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


class WPP_Shortcodes {

	public function __construct() {
		add_shortcode('poll_list', array( $this, 'poll_list_display') );
		add_filter( 'the_content', array( $this, 'poll_list_filter_content' ), 99);
		add_shortcode('poll', array( $this, 'poll_display') );
		add_filter( 'widget_text', 'do_shortcode', 20);
	}

	public function poll_display($atts, $content = null ) {
		
		$atts = shortcode_atts( array(
			'id' => ''
		), $atts);
		
		$poll_id = empty( $atts['id'] ) ? '' : $atts['id'];
		
		ob_start();		
		include( WPP_PLUGIN_DIR . 'templates/shortcodes/poll.php');
		// include( WPP_PLUGIN_DIR . 'templates/check.php');
		return ob_get_clean();		
	}

	public function poll_list_filter_content($content) {
		
		$wpp_poll_page = get_option( 'wpp_poll_page' );
		if( empty( $wpp_poll_page ) ) return $content;
		
		if( get_the_ID() == $wpp_poll_page ) {
			$content .= do_shortcode('[poll_list]');
		}
		
		return $content;
	}
	
	public function poll_list_display($atts, $content = null ) {
		
		$atts = shortcode_atts( array(
			
		), $atts);

		ob_start();		
		include( WPP_PLUGIN_DIR . 'templates/poll-list.php');
		return ob_get_clean();		
	}

} new WPP_Shortcodes();
