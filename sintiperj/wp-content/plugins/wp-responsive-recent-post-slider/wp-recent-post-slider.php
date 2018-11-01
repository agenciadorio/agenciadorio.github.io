<?php
/**
 * Plugin Name: WP Responsive Recent Post Slider
 * Plugin URI: http://www.wponlinesupport.com/
 * Text Domain: wp-responsive-recent-post-slider
 * Domain Path: /languages/
 * Description: Easy to add and display Recent Post Slider  
 * Author: WP Online Support
 * Version: 1.4.4
 * Author URI: http://www.wponlinesupport.com/
 *
 * @package WordPress
 * @author WP Online Support
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Basic plugin definitions
 * 
 * @package WP Responsive Recent Post Slider
 * @since 1.0.0
 */
if( !defined( 'WPRPS_VERSION' ) ) {
    define( 'WPRPS_VERSION', '1.4.4' ); // Version of plugin
}
if( !defined( 'WPRPS_DIR' ) ) {
    define( 'WPRPS_DIR', dirname( __FILE__ ) ); // Plugin dir
}
if( !defined( 'WPRPS_URL' ) ) {
    define( 'WPRPS_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}
if( !defined( 'WPRPS_POST_TYPE' ) ) {
    define( 'WPRPS_POST_TYPE', 'post' ); // Plugin post type
}

add_action('plugins_loaded', 'wprps_load_textdomain');
function wprps_load_textdomain() {
    load_plugin_textdomain( 'wp-responsive-recent-post-slider', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}

register_activation_hook( __FILE__, 'wprps_install' );
function wprps_install() {
    if( is_plugin_active('wp-responsive-recent-post-slider-pro/wp-recent-post-slider.php') ) {
        add_action('update_option_active_plugins', 'wprps_deactivate_pro_version');
    }
}

function wprps_deactivate_pro_version() {
   deactivate_plugins('wp-responsive-recent-post-slider-pro/wp-recent-post-slider.php',true);
}

add_action( 'admin_notices', 'wprps_plugin_admin_notice');
function wprps_plugin_admin_notice() {

    global $pagenow;

    $dir                = WP_PLUGIN_DIR . '/wp-responsive-recent-post-slider-pro/wp-recent-post-slider.php';
    $notice_link        = add_query_arg( array('message' => 'wprps-plugin-notice'), admin_url('plugins.php') );
    $notice_transient   = get_transient( 'wprps_install_notice' );

    if( $notice_transient == false && $pagenow == 'plugins.php' && file_exists( $dir ) && current_user_can( 'install_plugins' ) ) {
                echo '<div class="updated notice" style="position:relative;">
                    <p>
                        <strong>'.sprintf( __('Thank you for activating %s', 'wp-responsive-recent-post-slider'), 'WP Responsive Recent Post Slider').'</strong>.<br/>
                        '.sprintf( __('It looks like you had PRO version %s of this plugin activated. To avoid conflicts the extra version has been deactivated and we recommend you delete it.', 'wp-responsive-recent-post-slider'), '<strong>(<em>WP Responsive Recent Post Slider PRO</em>)</strong>' ).'
                    </p>
                    <a href="'.esc_url( $notice_link ).'" class="notice-dismiss" style="text-decoration:none;"></a>
                </div>';
    }
}

// Function file
require_once( WPRPS_DIR . '/includes/wppsac-function.php' );

// Script Fils
require_once( WPRPS_DIR . '/includes/class-wppsac-script.php' );

// Admin class
require_once( WPRPS_DIR . '/includes/admin/class-wprps-admin.php' );

// Shortcodes
require_once( WPRPS_DIR . '/includes/shortcodes/wppsac-slider.php' );

// How it work file, Load admin files
if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
    require_once( WPRPS_DIR . '/includes/admin/wprps-how-it-work.php' );
}

/* Plugin Wpos Analytics Data Starts */
function wpos_wprps_analytics_load() {

    require_once dirname( __FILE__ ) . '/wpos-analytics/wpos-analytics.php';

    $wpos_analytics =  wpos_anylc_init_module( array(
                            'id'            => 18,
                            'file'          => plugin_basename( __FILE__ ),
                            'name'          => 'WP Responsive Recent Post Slider',
                            'slug'          => 'wprps-post-slider',
                            'type'          => 'plugin',
                            'menu'          => 'wprps-about',
                            'text_domain'   => 'wp-responsive-recent-post-slider',
                            'offers'         => array(
                                                    'trial_premium' => array(
                                                            'image' => 'http://analytics.wponlinesupport.com/?anylc_img=18',
                                                            'link'  => 'https://www.wponlinesupport.com/plugins-plus-themes-powerpack-combo-offer/?ref=blogeditor'
                                                        ),
                                                    ),
                        ));

    return $wpos_analytics;
}

// Init Analytics
wpos_wprps_analytics_load();
/* Plugin Wpos Analytics Data Ends */