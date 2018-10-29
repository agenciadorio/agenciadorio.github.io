<?php
/*
Plugin Name: PricingTable
Plugin URI: http://pickplugins.com/items/pricing-table/
Description: Long waited pricing table plugin for WordPress published to display pricing grid on your WordPress site.
Version: 1.12
Author: paratheme
Author URI: http://pickplugins.com/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined('ABSPATH')) exit;  // if direct access	


//define('pricingtable_plugin_url', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );
define('pricingtable_plugin_url', plugins_url('/', __FILE__)  );
define('pricingtable_plugin_dir', plugin_dir_path( __FILE__ ) );
define('pricingtable_wp_url', 'https://wordpress.org/plugins/pricingtable/' );
define('pricingtable_wp_reviews', 'https://wordpress.org/support/view/plugin-reviews/pricingtable/' );
define('pricingtable_pro_url','http://pickplugins.com/items/pricing-table/' );
define('pricingtable_demo_url', 'http://pickplugins.com/demo/pricingtable/' );
define('pricingtable_conatct_url', 'http://pickplugins.com/contact' );
define('pricingtable_qa_url', 'http://www.pickplugins.com/question-archive/' );
define('pricingtable_donate_url', 'http://pickplugins.com/donate-us/' );
define('pricingtable_plugin_name', 'PricingTable' );
define('pricingtable_share_url', 'https://wordpress.org/plugins/pricingtable/' );
define('pricingtable_tutorial_video_url', '//www.youtube.com/embed/h3StmDVu5tE?rel=0' );





require_once( plugin_dir_path( __FILE__ ) . 'includes/meta.php');
require_once( plugin_dir_path( __FILE__ ) . 'includes/functions.php');


//require_once( plugin_dir_path( __FILE__ ) . 'includes/class-functions.php');



//Themes php files

require_once( plugin_dir_path( __FILE__ ) . 'themes/flat/index.php');
require_once( plugin_dir_path( __FILE__ ) . 'themes/rounded/index.php');
require_once( plugin_dir_path( __FILE__ ) . 'themes/sonnet/index.php');


function pricingtable_init_scripts(){
	
	
		wp_enqueue_script('jquery');
		wp_enqueue_script('pricingtable_js', plugins_url( '/js/scripts.js' , __FILE__ ) , array( 'jquery' ));
		
		wp_localize_script('pricingtable_js', 'pricingtable_ajax', array( 'pricingtable_ajaxurl' => admin_url( 'admin-ajax.php')));
		wp_enqueue_style('pricingtable_style', pricingtable_plugin_url.'css/style.css');
		wp_enqueue_style('pricingtable_style_common', pricingtable_plugin_url.'css/style-common.css');
		wp_enqueue_style('animate', pricingtable_plugin_url.'css/animate.css');		
		
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'pricingtable_color_picker', plugins_url('/js/color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
		
		//ParaAdmin
		wp_enqueue_style('ParaAdmin', pricingtable_plugin_url.'ParaAdmin/css/ParaAdmin.css');
		//wp_enqueue_style('ParaIcons', pricingtable_plugin_url.'ParaAdmin/css/ParaIcons.css');		
		wp_enqueue_script('ParaAdmin', plugins_url( 'ParaAdmin/js/ParaAdmin.js' , __FILE__ ) , array( 'jquery' ));
		
		
		// Style for themes
		wp_enqueue_style('pricingtable-style-flat', pricingtable_plugin_url.'themes/flat/style.css');			
		wp_enqueue_style('pricingtable-style-rounded', pricingtable_plugin_url.'themes/rounded/style.css');
		wp_enqueue_style('pricingtable-style-sonnet', pricingtable_plugin_url.'themes/sonnet/style.css');		

		
		//Style for font
		
		
		wp_register_style( 'Raleway', 'http://fonts.googleapis.com/css?family=Raleway:900'); 
		wp_enqueue_style( 'Raleway' );
		
		
		wp_register_style( 'ahronbd', pricingtable_plugin_url.'fonts/ahronbd.ttf'); 
		wp_enqueue_style( 'ahronbd' );
		
		wp_register_style( 'Bellerose', pricingtable_plugin_url.'fonts/Bellerose.ttf'); 
		wp_enqueue_style( 'Bellerose' );		
		

		
	}
add_action("init","pricingtable_init_scripts");

function pricingtable_init_admin_scripts(){
	
	wp_enqueue_style('pricingtable_style-admin', pricingtable_plugin_url.'css/style-admin.css');
	
	}

add_action("init","pricingtable_init_admin_scripts");



register_activation_hook(__FILE__, 'pricingtable_activation');


function pricingtable_activation()
	{
		$pricingtable_version= "1.12";
		update_option('pricingtable_version', $pricingtable_version); //update plugin version.
		
		$pricingtable_customer_type= "free"; //customer_type "free"
		update_option('pricingtable_customer_type', $pricingtable_customer_type); //update plugin version.

	}


function pricingtable_display($atts, $content = null ) {
		$atts = shortcode_atts(
			array(
				'id' => "",

				), $atts);


			$post_id = $atts['id'];

			$pricingtable_themes = get_post_meta( $post_id, 'pricingtable_themes', true );

			$pricingtable_display ="";

			if($pricingtable_themes== "flat")
				{
					$pricingtable_display.= pricingtable_body_flat($post_id);
				}
				
			else if($pricingtable_themes=="rounded")
				{
					$pricingtable_display.= pricingtable_body_rounded($post_id);
				}

			else if($pricingtable_themes=="sonnet")
				{
					$pricingtable_display.= pricingtable_body_sonnet($post_id);
				}

			else
				{
					$pricingtable_display.= pricingtable_body_flat($post_id);
				}
				
				
				
				return $pricingtable_display;
			
	
				




}

add_shortcode('pricingtable', 'pricingtable_display');









add_action('admin_menu', 'pricingtable_menu_init');


	


function pricingtable_menu_settings(){
	include('pricingtable-settings.php');	
}




function pricingtable_menu_init() {


	add_submenu_page('edit.php?post_type=pricingtable', __('Settings','license'), __('Settings','license'), 'manage_options', 'pricingtable_menu_settings', 'pricingtable_menu_settings');	

	
	

	
	
	
	
}


