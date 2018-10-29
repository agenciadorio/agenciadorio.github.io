<?php
/**
 * Compatibility hooks for StructurePress WP theme.
 *
 * For 3rd party plugins/features.
 *
 * @package StructurePress
 */

class StructurePressCompat {

	function __construct() {
		add_action( 'activate_breadcrumb-navxt/breadcrumb-navxt.php', array( $this, 'custom_hseparator' ) );
		add_action( 'activate_custom-sidebars/customsidebars.php', array( $this, 'detect_custom_sidebar_plugin_activation' ) );
		add_filter( 'portfolioposttype_args', array( $this, 'portfolioposttype_args' ) );
	}

	function custom_hseparator() {
		add_option( 'bcn_options', array( 'hseparator' => '' ) );
	}

	function detect_custom_sidebar_plugin_activation() {
		// Get existing sidebars (if any exist)
		$custom_sidebars_options = get_option( 'cs_sidebars', array() );

		// Only add the custom sidebar (Our Services) if the Custom Sidebar plugin option cs_sidebars is empty
		if ( empty( $custom_sidebars_options ) ) {
			update_option( 'cs_sidebars', array(
				array(
					'id'            => 'cs-1',
					'name'          => 'Our Services',
					'description'   => '',
					'before_widget' => '',
					'after_widget'  => '',
					'before_title'  => '',
					'after_title'   => '',
				),
			) );
		}
	}

	/**
	 * Change post type labels and arguments for Portfolio Post Type plugin.
	 *
	 * @param array $args Existing arguments.
	 *
	 * @return array
	 */
	function portfolioposttype_args( array $args ) {
		$args['rewrite'] = array( 'slug' => get_theme_mod( 'portfolio_slug', 'portfolio' ) );

		return $args;
	}

}

// Single instance
$structurepress_compat = new StructurePressCompat();