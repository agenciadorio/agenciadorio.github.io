<?php
/*
Plugin Name: BunnyCDN
Text Domain: bunnycdn
Description: Speed up your website with BunnyCDN Content Delivery Network. This plugin allows you to easily enable BunnyCDN on your WordPress website and enjoy greatly improved loading times around the world. Even better, it takes just a minute to set up.
Author: BunnyCDN
Author URI: https://bunnycdn.com
License: GPLv2 or later
Version: 1.0.2
*/

/*
Copyright (C)  2017 BunnyCDN

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

defined('ABSPATH') OR die();

// Load the paths
define('BUNNYCDN_PLUGIN_FILE', __FILE__);
define('BUNNYCDN_PLUGIN_DIR', dirname(__FILE__));
define('BUNNYCDN_PLUGIN_BASE', plugin_basename(__FILE__));
define('BUNNYCDN_PULLZONEDOMAIN', "b-cdn.net");
define('BUNNYCDN_DEFAULT_DIRECTORIES', "wp-content,wp-includes");
define('BUNNYCDN_DEFAULT_EXCLUDED', ".php");


// Make sure jQuery is included
function theme_scripts() {
  wp_enqueue_script('jquery');
}
add_action('admin_enqueue_scripts', 'theme_scripts');

// Load everything
spl_autoload_register('BunnyCDNLoad');
function BunnyCDNLoad($class) 
{
	require_once(BUNNYCDN_PLUGIN_DIR.'/inc/bunnycdnSettings.php');
	require_once(BUNNYCDN_PLUGIN_DIR.'/inc/bunnycdnFilter.php');
}

// Register the settings page and menu
add_action("admin_menu", array("BunnyCDNSettings", "initialize"));


add_action("template_redirect", "doRewrite");
function doRewrite() 
{
	$options = BunnyCDN::getOptions();
	if(strlen(trim($options["cdn_domain_name"])) > 0)
	{
		$rewriter = new BunnyCDNFilter($options["site_url"], (is_ssl() ? 'https://' : 'http://') . $options["cdn_domain_name"], $options["directories"], $options["excluded"]);
		$rewriter->startRewrite();
	}
}

?>