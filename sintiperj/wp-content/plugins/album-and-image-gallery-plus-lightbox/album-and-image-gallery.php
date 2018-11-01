<?php
/**
 * Plugin Name: Album and Image Gallery Plus Lightbox
 * Plugin URI: http://www.wponlinesupport.com/
 * Description: Easy to add and display image gallery and gallery slider.
 * Author: WP Online Support
 * Text Domain: album-and-image-gallery-plus-lightbox
 * Domain Path: /languages/
 * Version: 1.1.2
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
	define( 'AIGPL_VERSION', '1.1.2' ); // Version of plugin
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
    
    // IMP need to flush rules for custom registered post type
    flush_rewrite_rules();
}

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