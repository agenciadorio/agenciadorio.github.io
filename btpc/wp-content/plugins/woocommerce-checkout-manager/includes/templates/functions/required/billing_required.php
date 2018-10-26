<?php
function wooccm_billing_custom_checkout_process() {

	global $woocommerce;

	$options = get_option( 'wccs_settings3' );
	$buttons = ( isset( $options['buttons'] ) ? $options['billing_buttons'] : array() );

	// Check if we have any buttons
	if( empty( $buttons ) )
		return;

	$categoryarraycm = array();
	$productsarraycm = array();

	foreach( $buttons as $btn ) {

		$btn['checkbox'] = ( isset( $btn['checkbox'] ) ? $btn['checkbox'] : false );
		if( $btn['checkbox'] === 'true' ) {
			// without checkbox
			if(
				empty( $btn['single_px_cat'] ) && 
				empty( $btn['single_p_cat'] ) && 
				empty( $btn['single_px'] ) && 
				empty( $btn['single_p'] ) && 
				empty( $btn['disabled'] ) && 
				!empty( $btn['label'] ) && 
				$btn['type'] !== 'changename' && 
				$btn['type'] !== 'heading'
			) {
				if( empty( $_POST[sprintf( 'billing_%s', $btn['cow'] )] ) ) {
					$message = sprintf( __( '%s is a required field.44', 'woocommerce' ), '<strong>' . wooccm_wpml_string( $btn['label'] ) . '</strong>' );
					wc_add_notice( $message, 'error' );
				}
			}

			// checkbox
			if(
				empty( $btn['single_px_cat'] ) && 
				empty( $btn['single_p_cat'] ) && 
				empty( $btn['single_px'] ) && 
				empty( $btn['single_p'] ) && 
				$btn['type'] == 'checkbox' && 
				!empty( $btn['label'] ) && 
				$btn['type'] !== 'changename' && 
				$btn['type'] !== 'heading'
			) {
				if( ( sanitize_text_field( $_POST[sprintf( 'billing_%s', $btn['cow'] )] ) == $btn['check_2'] ) && ( !empty( $btn['checkbox'] ) ) ) {
					$message = sprintf( __( '%s is a required field.999', 'woocommerce' ), '<strong>' . wooccm_wpml_string( $btn['label'] ) . '</strong>' );
					wc_add_notice( $message, 'error');
				}
			}
		}

		foreach( $woocommerce->cart->cart_contents as $key => $values ) {

			$multiproductsx = ( isset( $btn['single_p'] ) ? $btn['single_p'] : '' );
			$show_field_single = ( isset( $btn['single_px'] ) ? $btn['single_px'] : '' );
			$multiproductsx_cat = ( isset( $btn['single_p_cat'] ) ? $btn['single_p_cat'] : '' );
			$show_field_single_cat = ( isset( $btn['single_px_cat'] ) ? $btn['single_px_cat'] : '' );

			$productsarraycm[] = $values['product_id'];

			// Products 
			// hide field

			// show field without more
			if( !empty( $btn['single_px'] ) && empty( $btn['more_content'] ) ) {
				$show_field_array = explode( '||', $show_field_single );
				if( in_array( $values['product_id'], $show_field_array ) && ( count( $woocommerce->cart->cart_contents ) < 2 ) ) {
					if( !empty( $btn['checkbox'] ) && !empty( $btn['label'] ) && ( $btn['type'] !== 'changename' ) ) {
						if( empty( $_POST[sprintf( 'billing_%s', $btn['cow'] )] ) ) {
							$message = sprintf( __( '%s is a required field.000', 'woocommerce' ), '<strong>' . wooccm_wpml_string( $btn['label'] ) . '</strong>' );
							wc_add_notice( $message, 'error' );
						}
					}
				}
			}

			// Category
			// hide field
			$terms = get_the_terms( $values['product_id'], 'product_cat' );
			if( !empty( $terms ) ) {
				foreach( $terms as $term ) {

					$categoryarraycm[] = $term->slug;

					// without more

					// show field without more
					if( !empty( $btn['single_px_cat'] ) && empty( $btn['more_content'] ) ) {
						$show_field_array_cat = explode( '||' , $show_field_single_cat );
						if( in_array( $term->slug, $show_field_array_cat ) && ( count( $woocommerce->cart->cart_contents ) < 2 ) ) {
							if( !empty( $btn['checkbox'] ) && !empty( $btn['label'] ) && ( $btn['type'] !== 'changename' ) ) {
								if( empty( $_POST[sprintf( 'billing_%s', $btn['cow'] )] ) ) {
									$message = sprintf( __( '%s is a required field.11', 'woocommerce' ), '<strong>' . wooccm_wpml_string( $btn['label'] ) . '</strong>' );
									wc_add_notice( $message, 'error' );
								}
							}
						}
					}

				}
			}

		}
		// end cart

// ===========================================================================================

		// Products
		// hide field

		// show field with more
		if( !empty( $btn['single_px'] ) && !empty( $btn['more_content'] ) ) {
			$show_field_array = explode( '||', $show_field_single );
			if( array_intersect( $productsarraycm, $show_field_array ) ) {
				if( !empty( $btn['checkbox'] ) && !empty( $btn['label'] ) && ( $btn['type'] !== 'changename' ) ) {
					if( empty( $_POST[sprintf( 'billing_%s', $btn['cow'] )] ) ) {
						$message = sprintf( __( '%s is a required field.22', 'woocommerce' ), '<strong>' . wooccm_wpml_string( $btn['label'] ) . '</strong>' );
						wc_add_notice( $message, 'error' );
					}
				}
			}
		}

		// Category
		// hide field

		// with more

		// show field with more
		if( !empty( $btn['single_px_cat'] ) && !empty( $btn['more_content'] ) ) {
			$show_field_array_cat = explode( '||', $show_field_single_cat );
			if( array_intersect( $categoryarraycm, $show_field_array_cat ) ) {
				if( !empty( $btn['checkbox'] ) && !empty( $btn['label'] ) && ( $btn['type'] !== 'changename' ) ) {
					if( empty( $_POST[sprintf( 'billing_%s', $btn['cow'] )] ) ) {
						$message = sprintf( __( '%s is a required field.33', 'woocommerce' ), '<strong>' . wooccm_wpml_string( $btn['label'] ) . '</strong>' );
						wc_add_notice( $message, 'error' );
					}
				}
			}
		}

		$categoryarraycm = array();
		$productsarraycm = array();

	}

}
?>