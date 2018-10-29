<?php
// Spinner from WP 4.2 https://make.wordpress.org/core/2015/04/23/spinners-and-dismissible-admin-notices-in-4-2/

if ( version_compare( get_bloginfo( 'version' ), '4.2', '<' ) ) {
	add_action( 'admin_head', 'thememove_spinner_css' );

	function thememove_spinner_css() {
		echo '<style type="text/css">.spinner.is-active { display: inline-block; }</style>';
	}
}