<?php
/*
 * Plugin Name: Cloudflare SSL by Weslink
 * Plugin URI: https://weslink.de
 * Description: Adds Support of CloudFlare Flexibles SSL for WordPress
 * Version: 1.0.8
 * Text Domain: wl-ssl-for-cloudflare
 * Author: Weslink
 * Author URI: https://weslink.de
 */

class CTW_Cloudflare_SSL {

	public function __construct() {}

	public function ensureSSL() {
		
		// for cloudflare
		if ( isset( $_SERVER[ 'HTTP_CF_VISITOR' ] ) && ( strpos( $_SERVER[ 'HTTP_CF_VISITOR' ], 'https' ) !== false ) ) {
			$_SERVER[ 'HTTPS' ] = 'on';
		}

		// for other...
		if(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'){
		    $_SERVER['HTTPS']='on'; 
		}	
		
	}

	/*
		force this plugin to load as first plugin, to ensure everything is routed well
		thx to: https://wordpress.org/support/topic/how-to-change-plugins-load-order
	*/

	function this_plugin_first() {
	// ensure path to this file is via main wp plugin path
	$wp_path_to_this_file = preg_replace('/(.*)plugins\/(.*)$/', WP_PLUGIN_DIR."/$2", __FILE__);
	$this_plugin = plugin_basename(trim($wp_path_to_this_file));
	$active_plugins = get_option('active_plugins');
	$this_plugin_key = array_search($this_plugin, $active_plugins);
	if ($this_plugin_key) { // if it's 0 it's the first plugin already, no need to continue
		array_splice($active_plugins, $this_plugin_key, 1);
		array_unshift($active_plugins, $this_plugin);
		update_option('active_plugins', $active_plugins);
	}
	}

}

$ctw_fix_ssl = new CTW_Cloudflare_SSL();


if ( is_admin() ) {
			$ctw_fix_ssl->this_plugin_first();
		}

$ctw_fix_ssl->ensureSSL();