<?php
/*
Plugin Name: Tabs
Plugin URI: http://paratheme.com/items/tabs-html-css3-responsive-tabs-for-wordpress/
Description: Fully responsive and mobile ready content tabs grid for wordpress.
Version: 1.3.2
Author: pickplugins
Author URI: http://pickplugins.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

class Tabs{
	
	public function __construct(){

		define('tabs_plugin_url', plugins_url('/', __FILE__)  );
		define('tabs_plugin_dir', plugin_dir_path( __FILE__ ) );
		
		define('tabs_wp_url', 'http://wordpress.org/plugins/tabs' );
		define('tabs_wp_reviews', 'http://wordpress.org/support/view/plugin-reviews/tabs' );
		define('tabs_pro_url', 'http://www.pickplugins.com/item/tabs-html-css3-responsive-tabs-for-wordpress/' );
		define('tabs_demo_url', 'http://www.pickplugins.com/demo/tabs/' );
		define('tabs_conatct_url', 'http://pickplugins.com/contact' );
		define('tabs_qa_url', 'http://pickplugins.com/questions/' );
		define('tabs_plugin_name', 'Tabs' );
		define('tabs_plugin_version', '1.3.2' );
		define('tabs_customer_type', 'free' );	 // free	
		define('tabs_share_url', 'https://wordpress.org/plugins/tabs/' );
		define('tabs_tutorial_video_url', '//www.youtube.com/embed/8OiNCDavSQg?rel=0' );
		
		require_once( plugin_dir_path( __FILE__ ) . 'includes/meta.php');
		require_once( plugin_dir_path( __FILE__ ) . 'includes/functions.php');
		
		require_once( plugin_dir_path( __FILE__ ) . 'includes/class-shortcode.php');
		require_once( plugin_dir_path( __FILE__ ) . 'includes/class-settings.php');				
		
		
		add_action( 'wp_enqueue_scripts', array( $this, 'tabs_front_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'tabs_admin_scripts' ) );
		
		add_action( 'plugins_loaded', array( $this, 'tabs_load_textdomain' ));
		
	}
	
	public function tabs_load_textdomain() {
	  load_plugin_textdomain( 'tabs', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' ); 
	}
	
	
	public function tabs_front_scripts(){
			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery.responsiveTabs', plugins_url( '/assets/front/js/jquery.responsiveTabs.js' , __FILE__ ) , array( 'jquery' ));
			
			wp_enqueue_style('tabs_style', tabs_plugin_url.'assets/front/css/style.css');
			wp_enqueue_style('tabs.themes', tabs_plugin_url.'assets/global/css/tabs.themes.css');			
			wp_enqueue_style('responsive-tabs', tabs_plugin_url.'assets/front/css/responsive-tabs.css');	
			wp_enqueue_style('font-awesome', tabs_plugin_url.'assets/global/css/font-awesome.css');

		}
		
		
	public function tabs_admin_scripts(){
			
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('tabs_js', plugins_url( '/assets/admin/js/scripts.js' , __FILE__ ) , array( 'jquery' ));
		
		wp_enqueue_style('tabs-admin_style', tabs_plugin_url.'assets/admin/css/style.css');
		
		
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'tabs_color_picker', plugins_url('/assets/admin/js/color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
		
		wp_enqueue_style('font-awesome', tabs_plugin_url.'assets/global/css/font-awesome.css');
		
		wp_enqueue_style('ParaAdmin', tabs_plugin_url.'assets/admin/ParaAdmin/css/ParaAdmin.css');
		wp_enqueue_script('ParaAdmin', plugins_url( 'assets/admin/ParaAdmin/js/ParaAdmin.js' , __FILE__ ) , array( 'jquery' ));
		
		}	
	
	
}

new Tabs();
