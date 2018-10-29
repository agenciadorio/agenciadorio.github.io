<?php
/*
Plugin Name: Buttons X Lite
Plugin URI: https://www.button.sx/lite/
Description: WordPress button builder plugin. Create unlimited CSS3 buttons. The only complete call to action button builder for WordPress.
Version: 0.8.6
Author: Gautam Thapar
Author URI: http://www.gautamthapar.me/
License: GPLv2 or later
Text Domain: buttons-x
Domain Path: /languages/
*/

// Make sure we don't expose any info if called directly
if ( !defined( 'ABSPATH' ) ){
	exit;
}

define( 'BTNSX__VERSION', '0.8.6' );
define( 'BTNSX__MIN_WP_VERSION', '4.0' );
define( 'BTNSX__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'BTNSX__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

global $wp_version;
if ( $wp_version >= BTNSX__MIN_WP_VERSION ) {
    register_activation_hook( __FILE__, 'btnsx_activate' );
    register_activation_hook( __FILE__, 'network_activate' );
    add_action( 'wpmu_new_blog', 'btnsx_blog_activate', 100, 2 );
    add_action( 'activated_plugin', 'welcome_redirect', 10, 2 );
}
/**
 * Function to set the default settings
 * @since  0.1
 * @return
 */
function btnsx_activate() {
    $value = array(
        'css' => 'inline'
    );
    add_option( 'btnsx_settings', $value );
}
/**
 * Redirect to welcome screen when plugin is activated
 * @since  0.1
 * @return
 */
function welcome_redirect($plugin,$network_activate) {
    if( $network_activate === false ){
        if( $plugin == plugin_basename( __FILE__ ) ) {
            wp_redirect( admin_url('admin.php?page=btnsx') );
            exit;
        }
    }
}
/**
 * Function to set default options for every blog when olugin is network activated
 * @since  0.3
 * @param  boolean    $sitewide
 * @return
 */
function network_activate( $sitewide ) {
    do_multisite( $sitewide, 'btnsx_activate' );
}
function do_multisite( $sitewide, $method, $args = array() ) {
    if ( is_multisite() && $sitewide ) {
        global $wpdb, $blog_id;
        $dbquery = 'SELECT blog_id FROM '.$wpdb->blogs;
        $ids = $wpdb->get_col( $dbquery );
        foreach ( $ids as $id ) {
            switch_to_blog( $id );
            call_user_func_array( $method, array( $args ) );
        }
        switch_to_blog( $blog_id );
    } else call_user_func_array( $method, array( $args ) );
}

/**
 * Function to set default options when a new blog is created
 * @since  0.3
 * @param  int    $blog_id
 * @param  int    $user_id
 * @return
 */
function btnsx_blog_activate($blog_id, $user_id) {
    switch_to_blog( $blog_id );
    $value = array(
        'css' => 'inline'
    );
    add_option( 'btnsx_settings', $value );
}

$btnsx_settings = get_option( 'btnsx_settings' );

/**
 * Include classes
 */
require_once( BTNSX__PLUGIN_DIR . 'includes/class.btnsx.php' );
require_once( BTNSX__PLUGIN_DIR . 'includes/class.clone.php' );
require_once( BTNSX__PLUGIN_DIR . 'includes/class.portation.php' );
require_once( BTNSX__PLUGIN_DIR . 'includes/class.widget.php' );
require_once( BTNSX__PLUGIN_DIR . 'includes/class.mce.php' );
require_once( BTNSX__PLUGIN_DIR . 'includes/class.ajax.php' );
require_once( BTNSX__PLUGIN_DIR . 'includes/class.wapi.php' );

/**
 * Solves ACF script conflict
 * @since  0.8.2
 * @return string
 */
function btnsx_acf() {
    if( class_exists( 'acf' ) ){
        $screen = get_current_screen();
        if ( in_array( $screen->id, array( 'buttons-x', 'edit-buttons-x', 'buttons-x_page_buttons-x-settings', 'buttons-x_page_buttons-x-import' ) ) ) {
        ?>
            <script type="text/javascript">var acf = {}; acf.postbox = {}; acf.postbox.render = function(){}; acf.media = {}; acf.media.init = function(){}; acf.fields = {}; acf.fields.wysiwyg = {}; acf.do_action = function(){}; acf.conditional_logic = {}; acf.conditional_logic.init = function(){}; acf.o = {}; acf.o.post_id = '1'; acf.screen = {}; acf.screen.post_id = '2'; acf.fields.tab = {}; acf.fields.tab.refresh = function(){}; acf.helpers = {}; acf.helpers.get_atts = function(){};</script>
        <?php
        }
    }
}
add_action( 'admin_head', 'btnsx_acf', 99 );
