<?php
if ( ! function_exists( 'thememove_file_contents' ) ) {
	function thememove_file_contents( $path ) {
		$qode_content = '';
		if ( function_exists( 'realpath' ) ) {
			$filepath = realpath( $path );
		}
		if ( ! $filepath || ! @is_file( $filepath ) ) {
			return '';
		}

		if ( ini_get( 'allow_url_fopen' ) ) {
			$qode_file_method = 'fopen';
		} else {
			$qode_file_method = 'file_get_contents';
		}
		if ( $qode_file_method == 'fopen' ) {
			$qode_handle = fopen( $filepath, 'rb' );

			if ( $qode_handle !== false ) {
				while ( ! feof( $qode_handle ) ) {
					$qode_content .= fread( $qode_handle, 8192 );
				}
				fclose( $qode_handle );
			}

			return $qode_content;
		} else {
			return file_get_contents( $filepath );
		}
	}
}

if ( ! function_exists( 'thememove_json_encode' ) ) {
	function thememove_json_encode( $data ) {
		if ( function_exists( 'wp_json_encode' ) ) {
			return wp_json_encode( $data );
		} else {
			return json_encode( $data );
		}
	}
}

function thememove_import() {
	$options = @unserialize( base64_decode( thememove_file_contents( $_FILES['import-file']['tmp_name'] ) ) );

	if ( is_array( $options ) ) {
		foreach ( $options as $key => $val ) {
			set_theme_mod( $key, $val );
		}
	}

	echo thememove_json_encode( array( 'status' => 1, 'message' => __( 'Import is successful!', 'thememove' ) ) );
	die();
}

add_action( 'wp_ajax_thememove_customizer_options_import', 'thememove_import' );