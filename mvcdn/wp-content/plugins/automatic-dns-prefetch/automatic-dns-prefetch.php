<?php
/*
 * Plugin Name: Automatic DNS Prefetch
 * Version: 1.0
 * Plugin URI: https://mabujo.com/
 * Description: This is your starter template for your next WordPress plugin.
 * Author: mabujo
 * Author URI: https://mabujo.com/
 * Requires at least: 4.0
 * Tested up to: 4.4
 *
 * Text Domain: automatic-dns-prefetch
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author mabujo
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Load plugin class files
require_once( 'includes/class-automatic-dns-prefetch.php' );

/**
 * Returns the main instance of Automatic_DNS_Prefetch to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Automatic_DNS_Prefetch
 */
function Automatic_DNS_Prefetch () {
	$instance = Automatic_DNS_Prefetch::instance( __FILE__, '1.0.0' );

	return $instance;
}

Automatic_DNS_Prefetch();