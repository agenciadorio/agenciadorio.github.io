<?php
/**
 * Plugin Name: Smooth Scroll
 * Plugin URI: http://nguyendien.com/
 * Description: Plugin to make browser scroll smooth
 * Version: 1.0
 * Author: Nguyen Khanh Dien
 * Author URI: http://nguyendien.com
 * License: GPL12
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

add_action('wp_footer', 'add_smooth_script');
function add_smooth_script(){
    if (!is_admin()) {
        wp_enqueue_script('smooth-scroll', plugin_dir_url(__FILE__) . 'plugins-scroll.js');
    }
}