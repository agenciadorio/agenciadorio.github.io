<?php
/**
 * Filters and include calls for the ACF plugin, so it is available within the theme
 *
 * @see http://www.advancedcustomfields.com/resources/including-acf-in-a-plugin-theme/
 */

if ( 'yes' !== get_theme_mod( 'show_acf', 'no' ) ) {
	// hide ACF field group menu depending on the setting in customizer
	define( 'ACF_LITE', true );
}

// load acf field groups from PHP file
if ( ! STRUCTUREPRESS_DEVELOPMENT ) {
	StructurePressHelpers::load_file( '/inc/acf-field-groups.php' );
}


/**
 * Fix if the ACF is not activated yet
 */
add_action( 'get_header', function () {
	if ( ! function_exists( 'get_field' ) ) {
		function get_field( $setting, $default = false ) {
			return $default;
		}

		function have_rows( $value = false ) {
			return false;
		}
	}
} );