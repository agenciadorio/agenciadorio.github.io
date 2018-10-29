<?php
/*
Plugin Name: NS Ie Compatibility Fixer
Plugin URI: https://wordpress.org/plugins/ns-ie-compatibility-fixer/
Description: This plugin force ie to see compatibility in lastest version
Version: 1.0.4
Author: NsThemes
Author URI: http://www.nsthemes.com
License: GNU General Public License v2.0
License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ns_ie_compatibility_fixer(){
	echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
}
add_action('wp_head', 'ns_ie_compatibility_fixer');
