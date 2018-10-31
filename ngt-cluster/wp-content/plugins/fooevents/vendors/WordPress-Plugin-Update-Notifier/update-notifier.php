<?php if ( ! defined( 'ABSPATH' ) ) exit;
/**************************************************************
 *                                                            *
 *   Provides a notification to the user everytime            *
 *   your WordPress plugin is updated                         *
 *															  *
 *	 Based on the script by Unisphere:						  *
 *   https://github.com/unisphere/unisphere_notifier          *
 *                                                            *
 *   Author: Pippin Williamson                                *
 *   Profile: http://codecanyon.net/user/mordauk              *
 *   Follow me: http://twitter.com/pippinsplugins             *
 *                                                            *
 **************************************************************/
 
/*
	Replace FOOEVENTS and fooevents by your plugin prefix to prevent conflicts between plugins using this script.
*/

// Constants for the plugin name, folder and remote XML url
define( 'FOOEVENTS_NOTIFIER_PLUGIN_NAME', 'FooEvents for WooCommerce' ); // The plugin name
define( 'FOOEVENTS_NOTIFIER_PLUGIN_SHORT_NAME', 'FooEvents for WooCommerce' ); // The plugin short name, only if needed to make the menu item fit. Remove this if not needed
define( 'FOOEVENTS_NOTIFIER_PLUGIN_FOLDER_NAME', 'fooevents' ); // The plugin folder name
define( 'FOOEVENTS_NOTIFIER_PLUGIN_FILE_NAME', 'fooevents.php' ); // The plugin file name
define( 'FOOEVENTS_NOTIFIER_PLUGIN_XML_FILE', 'http://www.fooevents.com/plugin_updates_xml/fooevents.xml' ); // The remote notifier XML file containing the latest version of the plugin and changelog
define( 'FOOEVENTS_PLUGIN_NOTIFIER_CACHE_INTERVAL', 21600 ); // The time interval for the remote XML cache in the database (21600 seconds = 6 hours)
define( 'FOOEVENTS_PLUGIN_NOTIFIER_CODECANYON_USERNAME', 'GrenadeCo' ); // Your Codecanyon username


// Adds an update notification to the WordPress Dashboard menu
function fooevents_update_plugin_notifier_menu() {  
    if(is_admin()) {
        if ( function_exists( 'simplexml_load_string' ) ) { // Stop if simplexml_load_string funtion isn't available
            $xml 			= fooevents_get_latest_plugin_version( FOOEVENTS_PLUGIN_NOTIFIER_CACHE_INTERVAL ); // Get the latest remote XML file on our server
                $plugin_data 	= get_plugin_data( WP_PLUGIN_DIR . '/' . FOOEVENTS_NOTIFIER_PLUGIN_FOLDER_NAME . '/' . FOOEVENTS_NOTIFIER_PLUGIN_FILE_NAME ); // Read plugin current version from the style.css
                
                $xml_version_number = str_replace('.', '', $xml->latest);
                $plugin_version_number = str_replace('.', '', $plugin_data['Version']);
                
                if ( $xml_version_number > $plugin_version_number ) { // Compare current plugin version with the remote XML version
                        if ( defined( 'FOOEVENTS_NOTIFIER_PLUGIN_SHORT_NAME' ) ) {
                                $menu_name = FOOEVENTS_NOTIFIER_PLUGIN_SHORT_NAME;
                        } else {
                                $menu_name = FOOEVENTS_NOTIFIER_PLUGIN_NAME;
                        }
                        add_dashboard_page( FOOEVENTS_NOTIFIER_PLUGIN_NAME . ' Plugin Updates', $menu_name . ' <span class="update-plugins count-1"><span class="update-count">New Updates</span></span>', 'administrator', 'fooevents-plugin-update-notifier', 'fooevents_update_notifier');
                }
        }
    }
}
add_action('admin_menu', 'fooevents_update_plugin_notifier_menu');  



// Adds an update notification to the WordPress 3.1+ Admin Bar
function fooevents_update_notifier_bar_menu() {
    if(is_admin()) {
        if ( function_exists( 'simplexml_load_string' ) ) { // Stop if simplexml_load_string funtion isn't available
                global $wp_admin_bar, $wpdb;

                if ( ! is_super_admin() || ! is_admin_bar_showing() ) // Don't display notification in admin bar if it's disabled or the current user isn't an administrator
                return;

                $xml 		= fooevents_get_latest_plugin_version( FOOEVENTS_PLUGIN_NOTIFIER_CACHE_INTERVAL ); // Get the latest remote XML file on our server
                $plugin_data 	= get_plugin_data( WP_PLUGIN_DIR . '/' . FOOEVENTS_NOTIFIER_PLUGIN_FOLDER_NAME . '/' .FOOEVENTS_NOTIFIER_PLUGIN_FILE_NAME ); // Read plugin current version from the main plugin file

                $xml_version_number = str_replace('.', '', $xml->latest);
                $plugin_version_number = str_replace('.', '', $plugin_data['Version']);
                
                if($xml_version_number > $plugin_version_number) { // Compare current plugin version with the remote XML version
                        $wp_admin_bar->add_menu( array( 'id' => 'fooevents_update_notifier_bar_menu', 'title' => '<span>' . FOOEVENTS_NOTIFIER_PLUGIN_NAME . ' <span id="ab-updates">New Updates</span></span>', 'href' => get_admin_url() . 'index.php?page=fooevents-plugin-update-notifier' ) );
                }
        }
    }
}
add_action( 'admin_bar_menu', 'fooevents_update_notifier_bar_menu', 1000 );



// The notifier page
function fooevents_update_notifier() { 
	$xml 			= fooevents_get_latest_plugin_version( FOOEVENTS_PLUGIN_NOTIFIER_CACHE_INTERVAL ); // Get the latest remote XML file on our server
	$plugin_data 	= get_plugin_data( WP_PLUGIN_DIR . '/' . FOOEVENTS_NOTIFIER_PLUGIN_FOLDER_NAME . '/' .FOOEVENTS_NOTIFIER_PLUGIN_FILE_NAME ); // Read plugin current version from the main plugin file ?>

	<style>
		.update-nag { display: none; }
		#instructions {max-width: 670px;}
		h3.title {margin: 30px 0 0 0; padding: 30px 0 0 0; border-top: 1px solid #ddd;}
	</style>

	<div class="wrap">

		<div id="icon-tools" class="icon32"></div>
		<h2><?php echo FOOEVENTS_NOTIFIER_PLUGIN_NAME ?> Plugin Updates</h2>
	    <div id="message" class="updated below-h2"><p><strong>There is a new version of the <?php echo FOOEVENTS_NOTIFIER_PLUGIN_NAME; ?> plugin available.</strong> You have version <?php echo $plugin_data['Version']; ?> installed. Update to version <?php echo $xml->latest; ?>.</p></div>
		
		<div id="instructions">
		    <h3>Download and Update Instructions</h3>
                    <p><strong>NB: Please backup your existing files and database before updating!</strong></p>
                    <p>The updated plugin is available from your account profile on either <a href="https://codecanyon.net" target="_BLANK">CodeCanyon</a> or <a href="http://www.fooevents.com" targe="_BLANK">FooEvents.com</a> depending on where you purchased the plugin. </p>
                    <p>Extract the contents of the ZIP file and upload the extracted files via FTP to the <strong>/wp-content/plugins/<?php echo FOOEVENTS_NOTIFIER_PLUGIN_FOLDER_NAME; ?>/</strong> folder and overwrite the existing files (remember to first backup the existing files before overwriting them).</p>
		</div>
	    
	    <h3 class="title">Changelog</h3>
	    <?php echo $xml->changelog; ?>

	</div>
    
<?php } 



// Get the remote XML file contents and return its data (Version and Changelog)
// Uses the cached version if available and inside the time interval defined
function fooevents_get_latest_plugin_version( $interval ) {
	$notifier_file_url = FOOEVENTS_NOTIFIER_PLUGIN_XML_FILE;	
	$db_cache_field = 'foo-notifier-cache';
	$db_cache_field_last_updated = 'foo-notifier-cache-last-updated';
	$last = get_option( $db_cache_field_last_updated );
	$now = time();
	// check the cache
	if ( ! $last || ( ( $now - $last ) > $interval ) ) {
		// cache doesn't exist, or is old, so refresh it
		if( function_exists( 'curl_init' ) ) { // if cURL is available, use it...
			$ch = curl_init( $notifier_file_url );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_HEADER, 0 );
			curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
			$cache = curl_exec( $ch );
			curl_close( $ch );
		} else {
			$cache = file_get_contents( $notifier_file_url ); // ...if not, use the common file_get_contents()
		}

		if ( $cache ) {			
			// we got good results	
			update_option( $db_cache_field, $cache );
			update_option( $db_cache_field_last_updated, time() );
		} 
		// read from the cache file
		$notifier_data = get_option( $db_cache_field );
	}
	else {
		// cache file is fresh enough, so read from it
		$notifier_data = get_option( $db_cache_field );
	}

	// Let's see if the $xml data was returned as we expected it to.
	// If it didn't, use the default 1.0 as the latest version so that we don't have problems when the remote server hosting the XML file is down
	if( strpos( (string) $notifier_data, '<notifier>' ) === false ) {
		$notifier_data = '<?xml version="1.0" encoding="UTF-8"?><notifier><latest>1.0</latest><changelog></changelog></notifier>';
	}

	// Load the remote XML data into a variable and return it
	$xml = simplexml_load_string( $notifier_data ); 

	return $xml;
}