<?php
/*
Plugin Name: ThemeMove Core
Description: Core functions for ThemeMove theme
Author: ThemeMove
Version: 1.2.2
Author URI: http://thememove.com
*/

/**
 * Current ThemeMove Core version
 */
if ( ! defined( 'TM_CORE_VERSION' ) ) {
	define( 'TM_CORE_VERSION', '1.2.2' );
}

include_once( dirname( __FILE__ ) . '/compatibility.php' );

//include component
include_once( dirname( __FILE__ ) . '/mobile-detect.php' );
include_once( dirname( __FILE__ ) . '/mobble.php' );
include_once( dirname( __FILE__ ) . '/cmb2/init.php' );
include_once( dirname( __FILE__ ) . '/customizer/io.php' );
include_once( dirname( __FILE__ ) . '/shortcodes/init.php' );

// Mega Menu
include_once( dirname( __FILE__ ) . '/mega-menu/mega-menu.php' );

// Customizer Import/Export
include_once( dirname( __FILE__ ) . '/customizer/import/import.php' );
include_once( dirname( __FILE__ ) . '/customizer/export/export.php' );

// One-click
//include_once( dirname( __FILE__ ) . '/export/export.php' );
include_once( dirname( __FILE__ ) . '/import/import.php' );

// Others
include_once( dirname( __FILE__ ) . '/breadcrumb.php' );
include_once( dirname( __FILE__ ) . '/better-menu-widget.php' );
include_once( dirname( __FILE__ ) . '/otf_regen_thumbs.php' );