<?php
/**
 * Plugin Name: Elementor
 * Description: The most advanced frontend drag & drop page builder. Create high-end, pixel perfect websites at record speeds. Any theme, any page, any design.
 * Plugin URI: https://elementor.com/?utm_source=wp-plugins&utm_campaign=plugin-uri&utm_medium=wp-dash
 * Author: Elementor.com
 * Version: 1.8.12
 * Author URI: https://elementor.com/?utm_source=wp-plugins&utm_campaign=author-uri&utm_medium=wp-dash
 *
 * Text Domain: elementor
 *
 * @package Elementor
 * @category Core
 *
 * Elementor is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * Elementor is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'ELEMENTOR_VERSION', '1.8.12' );
define( 'ELEMENTOR_PREVIOUS_STABLE_VERSION', '1.7.12' );

define( 'ELEMENTOR__FILE__', __FILE__ );
define( 'ELEMENTOR_PLUGIN_BASE', plugin_basename( ELEMENTOR__FILE__ ) );
define( 'ELEMENTOR_PATH', plugin_dir_path( ELEMENTOR__FILE__ ) );

if ( defined( 'ELEMENTOR_TESTS' ) && ELEMENTOR_TESTS ) {
	define( 'ELEMENTOR_URL', 'file://' . ELEMENTOR_PATH );
} else {
	define( 'ELEMENTOR_URL', plugins_url( '/', ELEMENTOR__FILE__ ) );
}

define( 'ELEMENTOR_MODULES_PATH', plugin_dir_path( ELEMENTOR__FILE__ ) . '/modules' );
define( 'ELEMENTOR_ASSETS_URL', ELEMENTOR_URL . 'assets/' );

add_action( 'plugins_loaded', 'elementor_load_plugin_textdomain' );

if ( ! version_compare( PHP_VERSION, '5.4', '>=' ) ) {
	add_action( 'admin_notices', 'elementor_fail_php_version' );
} elseif ( ! version_compare( get_bloginfo( 'version' ), '4.5', '>=' ) ) {
	add_action( 'admin_notices', 'elementor_fail_wp_version' );
} else {
	// Fix language if the `get_user_locale` is difference from the `get_locale
	if ( isset( $_REQUEST['action'] ) && 0 === strpos( $_REQUEST['action'], 'elementor' ) ) {
		add_action( 'set_current_user', function() {
			global $current_user;
			$current_user->locale = get_locale();
		} );

		// Fix for Polylang
		define( 'PLL_AJAX_ON_FRONT', true );

		add_action( 'pll_pre_init', function( $polylang ) {
			$post_language = $polylang->model->post->get_language( $_REQUEST['post'], 'locale' );
			$_REQUEST['lang'] = $post_language->locale;
		} );
	}

	require( ELEMENTOR_PATH . 'includes/plugin.php' );
}

/**
 * Load gettext translate for our text domain.
 *
 * @since 1.0.0
 *
 * @return void
 */
function elementor_load_plugin_textdomain() {
	load_plugin_textdomain( 'elementor' );
}

/**
 * Show in WP Dashboard notice about the plugin is not activated (PHP version).
 *
 * @since 1.0.0
 *
 * @return void
 */
function elementor_fail_php_version() {
	/* translators: %s: PHP version */
	$message = sprintf( esc_html__( 'Elementor requires PHP version %s+, plugin is currently NOT ACTIVE.', 'elementor' ), '5.4' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}

/**
 * Show in WP Dashboard notice about the plugin is not activated (WP version).
 *
 * @since 1.5.0
 *
 * @return void
 */
function elementor_fail_wp_version() {
	/* translators: %s: WP version */
	$message = sprintf( esc_html__( 'Elementor requires WordPress version %s+. Because you are using an earlier version, the plugin is currently NOT ACTIVE.', 'elementor' ), '4.5' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}
