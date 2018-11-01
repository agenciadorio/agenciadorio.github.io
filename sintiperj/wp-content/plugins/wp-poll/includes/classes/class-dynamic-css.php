<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

$wpp_css = '';

class class_wpp_dynamic_css { 
	
	protected $global_css;
	
	public function __construct(){
	
		global $wpp_css;
		$this->global_css = &$wpp_css;
		
		add_action('wp_footer', array( $this, 'wpp_dynamic_css_loading' ) );
	}
	
	public function wpp_dynamic_css_loading() {
		echo '<style>'.$this->global_css.'</style>';
	}
	
	
} new class_wpp_dynamic_css();