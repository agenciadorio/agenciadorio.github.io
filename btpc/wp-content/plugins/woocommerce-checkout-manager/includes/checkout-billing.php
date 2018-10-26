<?php
function wooccm_checkout_billing_fields( $fields ) {

	$options = get_option( 'wccs_settings3' );
	$buttons = ( isset( $options['billing_buttons'] ) ? $options['billing_buttons'] : false );

	// Check if we have any fields to process
	if( empty( $buttons ) )
		return $fields;

	$billing = array(
		'address_1',
		'address_2',
		'city',
		'postcode',
		'state',
		'country'
	);

	foreach( $buttons as $btn ) {

		if( !empty( $btn['cow'] ) && empty( $btn['deny_checkout'] ) ) {
			$key = sprintf( 'billing_%s', $btn['cow'] );
			if( $btn['cow'] == 'country' ) {
				// Country override
				$fields[$key]['type'] = 'wooccmcountry';
			} elseif( $btn['cow'] == 'state' ) {
				// State override
				$fields[$key]['type'] = 'wooccmstate';
			} else {
				$fields[$key]['type'] = $btn['type'];
			}

			if( $btn['cow'] !== 'country' || $btn['cow'] !== 'state' ) {
				$fields[$key]['placeholder'] = ( isset( $btn['placeholder'] ) ? $btn['placeholder'] : '' );
			}

			// @mod - Why are we not setting the position here like we do for shipping?

			$fields[$key]['class'] = array( $btn['position'].' '. ( isset( $btn['conditional_tie'] ) ? $btn['conditional_tie'] : '' ) .' '. ( isset( $btn['extra_class'] ) ? $btn['extra_class'] : '' ) );		
			$fields[$key]['label'] =  wooccm_wpml_string( $btn['label'] );
			$fields[$key]['clear'] = ( isset( $btn['clear_row'] ) ? $btn['clear_row'] : '' );
			$fields[$key]['default'] = ( isset( $btn['force_title2'] ) ? $btn['force_title2'] : '' );
			$fields[$key]['options'] = ( isset( $btn['option_array'] ) ? $btn['option_array'] : '' );
			$fields[$key]['user_role'] = ( isset( $btn['user_role'] ) ? $btn['user_role'] : '' );
			$fields[$key]['role_options'] = ( isset( $btn['role_options'] ) ? $btn['role_options'] : '' );
			$fields[$key]['role_options2'] = ( isset( $btn['role_options2'] ) ? $btn['role_options2'] : '' );
			$fields[$key]['required'] = ( isset( $btn['checkbox'] ) ? $btn['checkbox'] : '' );
			$fields[$key]['wooccm_required'] = ( isset( $btn['checkbox'] ) ? $btn['checkbox'] : '' );
			$fields[$key]['cow'] = ( isset( $btn['cow'] ) ? $btn['cow'] : '' );
			$fields[$key]['color'] = ( isset( $btn['colorpickerd'] ) ? $btn['colorpickerd'] : '' );
			$fields[$key]['colorpickertype'] = ( isset( $btn['colorpickertype'] ) ? $btn['colorpickertype'] : '' );
			$fields[$key]['order'] = ( isset( $btn['order'] ) ? $btn['order'] : '' );
			$fields[$key]['fancy'] = ( isset( $btn['fancy'] ) ? $btn['fancy'] : '' );

			// Check if Multi-checkbox has options assigned to it
			if( $btn['type'] == 'multicheckbox' && empty( $btn['option_array'] ) ) {
				$btn['disabled'] = true;
			}

			// Bolt on address-field for address-based fields
			if( in_array( $btn['cow'], $billing ) ) {
				$fields[$key]['class'][] = 'address-field';
			}

			// Override for State fields
			if( $fields[$key]['type'] == 'wooccmstate' ) {
				$country_key = false;
				if( $key == 'billing_state' )
					$country_key = 'billing_country';
				if( $key == 'shipping_state' )
					$country_key = 'shipping_country';
				if( !empty( $country_key ) ) {
					$current_cc  = WC()->checkout->get_value( $country_key );
					$states      = WC()->countries->get_states( $current_cc );
					if( empty( $states ) ) {
						$fields[$key]['required'] = false;
						$fields[$key]['wooccm_required'] = false;
					}
				}
			}

			// Remove disabled fields
			if( !empty( $btn['disabled'] ) ) {
				unset( $fields[$key] );
			}

		}

	}

	// Resort the fields by order
	$fields[] = uasort( $fields, 'wooccm_sort_fields' );

	if( $fields[0] ) {
		unset( $fields[0] );
	}

	return $fields;

}
?>