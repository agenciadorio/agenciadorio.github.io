<?php

/**
 * Fired during plugin activation
 *
 * @link       http://ptheme.com/
 * @since      1.0.0
 *
 * @package    Wp_Sticky_Menu
 * @subpackage Wp_Sticky_Menu/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Sticky_Menu
 * @subpackage Wp_Sticky_Menu/includes
 * @author     PTHEME <support@ptheme.com>
 */
class Wp_Sticky_Menu_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		register_nav_menus( array(
			'wpsm' => __( 'WP Sticky Menu' ),
		) );
	}

}
