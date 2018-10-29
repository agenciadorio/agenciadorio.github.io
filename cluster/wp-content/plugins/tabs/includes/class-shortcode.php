<?php
/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 




class class_tabs_shortcodes{
	
	
    public function __construct(){
		
		add_shortcode( 'tabs', array( $this, 'tabs_display' ) );
	

    }
	

	public function tabs_display($atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'id' => '',
	
					), $atts);
	
				$html = '';
				$post_id = $atts['id'];
	
				$tabs_themes = get_post_meta( $post_id, 'tabs_themes', true );
	
				
	
				include( tabs_plugin_dir . 'templates/variables.php');
				include( tabs_plugin_dir . 'templates/scripts.php');
				include( tabs_plugin_dir . 'templates/custom-css.php');			
				
				//var_dump($tabs_themes);
				
				$html.= '<div id="responsiveTabs-'.$post_id.'"  class="tabs-container tabs-'.$tabs_themes.'" style="background-image:url('.$tabs_bg_img.')">';
				include( tabs_plugin_dir . 'templates/header.php');
				
				include( tabs_plugin_dir . 'templates/content.php');						
				$html.= '</div>';
	
				return $html;
	
	
	
	}

	
	

}


new class_tabs_shortcodes();