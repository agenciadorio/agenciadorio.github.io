<?php

/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_wpp_shortcodes{
	
    public function __construct()
	{
		add_shortcode( wpp_short_code, array( $this, 'wpp_display_for_today' ) );		
		add_shortcode( 'wpp_single', array( $this, 'wpp_single' ) );		
		add_shortcode( 'wpp_list', array( $this, 'wpp_list_display' ) );	
		
		add_filter( 'widget_text', 'do_shortcode', 11);
	}

	
	public function wpp_display_for_today($atts, $content = null ) 
	{
			$atts = shortcode_atts(
				array(
					'themes' => 'flat',
					
				), $atts);
	
			$html = '';
			$themes = $atts['themes'];
			$wpp_to_be_shown = $atts['id'];
					
			$class_wpp_functions = new class_wpp_functions();
			$wpp_themes_dir = $class_wpp_functions->wpp_themes_dir();
			$wpp_themes_url = $class_wpp_functions->wpp_themes_url();

			echo '<link  type="text/css" media="all" rel="stylesheet"  href="'.$wpp_themes_url[$themes].'/style.css" >';				
			include $wpp_themes_dir[$themes].'/index.php';		
							
			return $html;
	}
	
	public function wpp_single($atts, $content = null ) 
	{
			$atts = shortcode_atts(
				array(
					'themes' => 'flat',
					'id' => '',
					
				), $atts);
	
			$html = '';
			$themes = $atts['themes'];
			$wpp_to_be_shown = $atts['id'];
			
			$class_wpp_functions = new class_wpp_functions();
			$wpp_single_themes_dir = $class_wpp_functions->wpp_single_themes_dir();
			$wpp_single_themes_url = $class_wpp_functions->wpp_single_themes_url();

			echo '<link  type="text/css" media="all" rel="stylesheet"  href="'.$wpp_single_themes_url[$themes].'/style.css" >';				
			include $wpp_single_themes_dir[$themes].'/index.php';		
							
			return $html;
	}
	
	public function wpp_list_display($atts, $content = null ) 
	{
			$atts = shortcode_atts(
				array(
					'themes' => 'flat',
					), $atts);
	
			$html = '';
			$themes = $atts['themes'];
					
			$class_wpp_functions = new class_wpp_functions();
			$wpp_list_themes_dir = $class_wpp_functions->wpp_list_themes_dir();
			$wpp_list_themes_url = $class_wpp_functions->wpp_list_themes_url();

			echo '<link  type="text/css" media="all" rel="stylesheet"  href="'.$wpp_list_themes_url[$themes].'/style.css" >';				

			include $wpp_list_themes_dir[$themes].'/index.php';				
			return $html;
	}
	
} new class_wpp_shortcodes();