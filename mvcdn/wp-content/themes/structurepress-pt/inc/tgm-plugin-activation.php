<?php
/**
 * Loading the remote and local plugins when the theme is activated
 *
 * For reference see file vendor/tgm/plugin-activation/example.php
 *
 * @package TGM-Plugin-Activation
 */


/**
 * Register the required plugins for this theme.
 */
function structurepress_register_required_plugins() {

	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

		array(
			'name'               => 'Advanced Custom Fields',
			'slug'               => 'advanced-custom-fields',
			'source'             => get_template_directory() . '/bundled-plugins/advanced-custom-fields.zip',
			'required'           => true,
			'force_activation'   => true,
			'external_url'       => 'https://wordpress.org/plugins/advanced-custom-fields/',
		),
		array(
			'name'               => 'ACF Repeater Field',
			'slug'               => 'acf-repeater',
			'source'             => get_template_directory() . '/bundled-plugins/acf-repeater.zip',
			'required'           => true,
			'version'            => '1.1.1',
			'force_activation'   => true,
			'force_deactivation' => true,
			'external_url'       => 'http://www.advancedcustomfields.com/add-ons/repeater-field/',
		),
		array(
			'name'               => 'ProteusThemes Shortcodes',
			'slug'               => 'pt-shortcodes',
			'source'             => 'https://github.com/proteusthemes/pt-shortcodes/archive/master.zip',
			'required'           => true,
			'version'            => '1.0.0',
			'force_activation'   => true,
			'external_url'       => 'https://github.com/proteusthemes/pt-shortcodes',
		),
		array(
			'name'               => 'Page Builder by SiteOrigin',
			'slug'               => 'siteorigin-panels',
			'required'           => true,
			'version'            => '2.0',
		),
		array(
			'name'               => 'SiteOrigin Widgets Bundle',
			'slug'               => 'so-widgets-bundle',
			'required'           => true,
		),
		array(
			'name'               => 'One Click Demo Import',
			'slug'               => 'one-click-demo-import',
			'required'           => true,
		),
		array(
			'name'               => 'Portfolio Post Type',
			'slug'               => 'portfolio-post-type',
			'required'           => false,
		),
		array(
			'name'               => 'Contact Form 7',
			'slug'               => 'contact-form-7',
			'required'           => false,
		),
		array(
			'name'               => 'WP Featherlight - A Simple jQuery Lightbox',
			'slug'               => 'wp-featherlight',
			'required'           => false,
		),
		array(
			'name'               => 'Breadcrumb NavXT',
			'slug'               => 'breadcrumb-navxt',
			'required'           => true,
		),
		array(
			'name'               => 'Custom Sidebars',
			'slug'               => 'custom-sidebars',
			'required'           => true,
		),
		array(
			'name'               => 'WooCommerce - excelling eCommerce',
			'slug'               => 'woocommerce',
			'required'           => false,
		),
	);

	// Array of configuration settings. See the source example.php file to add it if needed.

	// Let the magic happen!
	tgmpa( $plugins );
}
add_action( 'tgmpa_register', 'structurepress_register_required_plugins' );