<?php
/**
 * Plugin Name: Album and Image Gallery Plus Lightbox
 * Plugin URI: http://www.wponlinesupport.com/
 * Description: Easy to add and display image gallery and gallery slider.
 * Author: WP Online Support
 * Text Domain: album-and-image-gallery-plus-lightbox
 * Domain Path: /languages/
 * Version: 1.1.5
 * Author URI: http://www.wponlinesupport.com/
 *
 * @package WordPress
 * @author WP Online Support
 */

/**
 * Basic plugin definitions
 * 
 * @package Album and Image Gallery Plus Lightbox
 * @since 1.0.0
 */
if( !defined( 'AIGPL_VERSION' ) ) {
	define( 'AIGPL_VERSION', '1.1.5' ); // Version of plugin
}
if( !defined( 'AIGPL_DIR' ) ) {
    define( 'AIGPL_DIR', dirname( __FILE__ ) ); // Plugin dir
}
if( !defined( 'AIGPL_URL' ) ) {
    define( 'AIGPL_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}
if( !defined( 'AIGPL_PLUGIN_BASENAME' ) ) {
	define( 'AIGPL_PLUGIN_BASENAME', plugin_basename( __FILE__ ) ); // plugin base name
}
if( !defined( 'AIGPL_POST_TYPE' ) ) {
    define( 'AIGPL_POST_TYPE', 'aigpl_gallery' ); // Plugin post type
}
if( !defined( 'AIGPL_CAT' ) ) {
    define( 'AIGPL_CAT', 'aigpl_cat' ); // Plugin category name
}
if( !defined( 'AIGPL_META_PREFIX' ) ) {
    define( 'AIGPL_META_PREFIX', '_aigpl_' ); // Plugin metabox prefix
}

/**
 * Load Text Domain
 * This gets the plugin ready for translation
 * 
 * @package Album and Image Gallery Plus Lightbox
 * @since 1.0.0
 */
function aigpl_load_textdomain() {
	load_plugin_textdomain( 'album-and-image-gallery-plus-lightbox', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}
add_action('plugins_loaded', 'aigpl_load_textdomain');

/**
 * Activation Hook
 * 
 * Register plugin activation hook.
 * 
 * @package Album and Image Gallery Plus Lightbox
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'aigpl_install' );

/**
 * Deactivation Hook
 * 
 * Register plugin deactivation hook.
 * 
 * @package Album and Image Gallery Plus Lightbox
 * @since 1.0.0
 */
register_deactivation_hook( __FILE__, 'aigpl_uninstall');

/**
 * Plugin Setup (On Activation)
 * 
 * Does the initial setup,
 * stest default values for the plugin options.
 * 
 * @package Album and Image Gallery Plus Lightbox
 * @since 1.0.0
 */
function aigpl_install() {
    
    // Register post type function
    aigpl_register_post_type();
    aigpl_register_taxonomies();
    
    // Deactivate Pro version
    if( is_plugin_active('album-and-image-gallery-plus-lightbox-pro/album-and-image-gallery.php') ){
        add_action('update_option_active_plugins', 'aigpl_deactivate_pro_version');
    }

    // IMP need to flush rules for custom registered post type
    flush_rewrite_rules();
}

/**
 * Deactivate PRO version when FREE is going to be active
 * 
 * @package Album and Image Gallery Plus Lightbox
 * @since 1.0
 */
function aigpl_deactivate_pro_version() {
   deactivate_plugins('album-and-image-gallery-plus-lightbox-pro/album-and-image-gallery.php',true);
}

/**
 * Display Plugin Notice
 * 
 * @package Album and Image Gallery Plus Lightbox
 * @since 1.0
 */
function aigpl_plugin_admin_notice() {

    global $pagenow;

    $dir                = WP_PLUGIN_DIR . '/album-and-image-gallery-plus-lightbox-pro/album-and-image-gallery.php';
    $notice_link        = add_query_arg( array('message' => 'aigpl-plugin-notice'), admin_url('plugins.php') );
    $notice_transient   = get_transient( 'aigpl_install_notice' );

    if( $notice_transient == false && $pagenow == 'plugins.php' && file_exists( $dir ) && current_user_can( 'install_plugins' ) ) {
        echo '<div class="updated notice" style="position:relative;">
            <p>
                <strong>'.sprintf( __('Thank you for activating %s', 'album-and-image-gallery-plus-lightbox'), 'Album and Image Gallery Plus Lightbox').'</strong>.<br/>
                '.sprintf( __('It looks like you had PRO version %s of this plugin activated. To avoid conflicts the extra version has been deactivated and we recommend you delete it.', 'album-and-image-gallery-plus-lightbox'), '<strong>(<em>Album and Image Gallery Plus Lightbox PRO</em>)</strong>' ).'
            </p>
            <a href="'.esc_url( $notice_link ).'" class="notice-dismiss" style="text-decoration:none;"></a>
        </div>';
    }
}
add_action( 'admin_notices', 'aigpl_plugin_admin_notice');

/**
 * Plugin Setup (On Deactivation)
 * 
 * Delete  plugin options.
 * 
 * @package Album and Image Gallery Plus Lightbox
 * @since 1.0.0
 */
function aigpl_uninstall() {
    
    // IMP need to flush rules for custom registered post type
    flush_rewrite_rules();
}

// Taking some globals
global $aigpl_gallery_render;

// Functions file
require_once( AIGPL_DIR . '/includes/aigpl-functions.php' );

// Plugin Post Type File
require_once( AIGPL_DIR . '/includes/aigpl-post-types.php' );

// Admin Class File
require_once( AIGPL_DIR . '/includes/admin/class-aigpl-admin.php' );

// Script Class File
require_once( AIGPL_DIR . '/includes/class-aigpl-script.php' );

// Shortcode File
require_once( AIGPL_DIR . '/includes/shortcode/aigpl-gallery.php' );
require_once( AIGPL_DIR . '/includes/shortcode/aigpl-gallery-slider.php' );
require_once( AIGPL_DIR . '/includes/shortcode/aigpl-gallery-album.php' );
require_once( AIGPL_DIR . '/includes/shortcode/aigpl-gallery-album-slider.php' );

// How it work file, Load admin files
if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
    require_once( AIGPL_DIR . '/includes/admin/aigpl-how-it-work.php' );
}

/* Plugin Wpos Analytics Data Starts */
function wpos_analytics_anl29_load() {

    require_once dirname( __FILE__ ) . '/wpos-analytics/wpos-analytics.php';

    $wpos_analytics =  wpos_anylc_init_module( array(
                            'id'            => 29,
                            'file'          => plugin_basename( __FILE__ ),
                            'name'          => 'Album and Image Gallery Plus Lightbox',
                            'slug'          => 'album-and-image-gallery-plus-lightbox',
                            'type'          => 'plugin',
                            'menu'          => 'edit.php?post_type=aigpl_gallery',
                            'text_domain'   => 'album-and-image-gallery-plus-lightbox',
                            'promotion'     => array(
                                                    'bundle' => array(
                                                        'name'  => 'Download FREE 50 Plugins, 10+ Themes and Dashboard Plugin',
                                                        'desc'  => 'Download FREE 50 Plugins, 10+ Themes and Dashboard Plugin',
                                                        'file'  => 'https://www.wponlinesupport.com/latest/wpos-free-50-plugins-plus-12-themes.zip'
                                                    )
                                                ),
                            'offers'        => array(
                                                    'trial_premium' => array(
                                                            1 => array(
                                                                    'image' => 'http://analytics.wponlinesupport.com/?anylc_img=29',
                                                                ),
                                                    ),
                                                ),
                        ));

    return $wpos_analytics;
}

// Init Analytics
wpos_analytics_anl29_load();
/* Plugin Wpos Analytics Data Ends */