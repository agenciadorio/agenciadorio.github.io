<?php
/**
 * WooCommerce Checkout Manager
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// WooCommerce Checkout field - Text Input
function wooccm_checkout_field_text_handler( $field = '', $key, $args, $value ) {

	global $current_user;

	$user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);

	if( !empty($args['user_role']) && (!empty($args['role_options']) || !empty($args['role_options2'])) ) {
		$rolekeys = explode('||',$args['role_options']);
		$rolekeys2 = explode('||',$args['role_options2']);
		if( !empty($args['role_options']) && !in_array($user_role, $rolekeys) ) {
			return;
		}
		if( !empty($args['role_options2']) && in_array($user_role, $rolekeys2) ) {
			return;
		}
	}	

	if( !empty( $args['clear'] ) )
		$after = '<div class="clear"></div>';
	else
		$after = '';

	$required = false;
	if( $args['wooccm_required'] ) {
		$args['class'][] = 'validate-required';
		$required = ' <abbr class="required" title="' . esc_attr( 'required', 'woocommerce-checkout-manager' ) . '">*</abbr>';
	}

	$args['maxlength'] = ( $args['maxlength'] ) ? 'maxlength="' . absint( $args['maxlength'] ) . '"' : '';

	if( is_string( $args['label_class'] ) ) {
		$args['label_class'] = array( $args['label_class'] );
	}

	if( is_null( $value ) ) {
		$value = $args['default'];
	}

	// Custom attribute handling
	$custom_attributes = array();

	if( !empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
		foreach( $args['custom_attributes'] as $attribute => $attribute_value ) {
			$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
		}
	}

	if( !empty( $args['validate'] ) ) {
		foreach( $args['validate'] as $validate ) {
			$args['class'][] = 'validate-' . $validate;
		}
	}

	$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $args['id'] ) . '_field">';

	if( $args['label'] || $required ) {
		$field .= '<label for="' . esc_attr( $args['id'] ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) .'">' . $args['label'] . $required . '</label>';
	}

	$field .= '<input type="text" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '" '.$args['maxlength'].' value="' . esc_attr( $value ) . '" ' . implode( ' ', $custom_attributes ) . ' />';

	if( $args['description'] ) {
		$field .= '<span class="description">' . esc_attr( $args['description'] ) . '</span>';
	}

	$field .= '</p>' . $after;

	return $field;

}
add_filter( 'woocommerce_form_field_wooccmtext', 'wooccm_checkout_field_text_handler', 10, 4 );

// WooCommerce Checkout field - Textarea
function wooccm_checkout_field_textarea_handler( $field = '', $key, $args, $value ) {

	global $current_user;

	$user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);
	
	if( !empty($args['user_role']) && (!empty($args['role_options']) || !empty($args['role_options2'])) ) {
		$rolekeys = explode('||',$args['role_options']);
		$rolekeys2 = explode('||',$args['role_options2']);
		if( !empty($args['role_options']) && !in_array($user_role, $rolekeys) ) {
			return;
		}
		if( !empty($args['role_options2']) && in_array($user_role, $rolekeys2) ) {
			return;
		}
	}
	
	if( !empty( $args['clear'] ) )
		$after = '<div class="clear"></div>';
	else
		$after = '';

	if( $args['wooccm_required'] ) {
		$args['class'][] = 'validate-required';
		$required = ' <abbr class="required" title="' . esc_attr( 'required', 'woocommerce-checkout-manager' ) . '">*</abbr>';
	} else {
		$required = '';
	}

	$args['maxlength'] = ( $args['maxlength'] ) ? 'maxlength="' . absint( $args['maxlength'] ) . '"' : '';

	if( is_string( $args['label_class'] ) ) {
		$args['label_class'] = array( $args['label_class'] );
	}

	if( is_null( $value ) ) {
		$value = $args['default'];
	}

	// Custom attribute handling
	$custom_attributes = array();

	if( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
		foreach( $args['custom_attributes'] as $attribute => $attribute_value ) {
			$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
		}
	}

	if( !empty( $args['validate'] ) ) {
		foreach( $args['validate'] as $validate ) {
			$args['class'][] = 'validate-' . $validate;
		}
	}

	$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $args['id'] ) . '_field">';

	if( $args['label'] ) {
		$field .= '<label for="' . esc_attr( $args['id'] ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) .'">' . $args['label']. $required  . '</label>';
	}

	// WordPress Filters to override default row and column counts
	$rows = apply_filters( 'wooccm_checkout_field_texarea_rows', 2, $key, $args );
	$columns = apply_filters( 'wooccm_checkout_field_texarea_columns', 5, $key, $args );

	$field .= '<textarea name="' . esc_attr( $key ) . '" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'" id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '" ' . $args['maxlength'] . ' ' . ( empty( $args['custom_attributes']['rows'] ) ? ' rows="' . $rows . '"' : '' ) . ( empty( $args['custom_attributes']['cols'] ) ? ' cols="' . $columns . '"' : '' ) . implode( ' ', $custom_attributes ) . '>'. esc_textarea( $value  ) .'</textarea>';

	if( $args['description'] ) {
		$field .= '<span class="description">' . esc_attr( $args['description'] ) . '</span>';
	}

	$field .= '</p>' . $after;

	return $field;

}
add_filter( 'woocommerce_form_field_wooccmtextarea', 'wooccm_checkout_field_textarea_handler', 10, 4 );

// WooCommerce Checkout field - Password
function wooccm_checkout_field_password_handler( $field = '', $key, $args, $value ) {

	global $current_user;

	$user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);

	if( !empty($args['user_role']) && (!empty($args['role_options']) || !empty($args['role_options2'])) ) {
		$rolekeys = explode('||',$args['role_options']);
		$rolekeys2 = explode('||',$args['role_options2']);
		if( !empty($args['role_options']) && !in_array($user_role, $rolekeys) ) {
			return;
		}
		if( !empty($args['role_options2']) && in_array($user_role, $rolekeys2) ) {
			return;
		}
	}
	
	if( !empty( $args['clear'] ) )
		$after = '<div class="clear"></div>';
	else
		$after = '';

	if( $args['wooccm_required'] ) {
		$args['class'][] = 'validate-required';
		$required = ' <abbr class="required" title="' . esc_attr( 'required', 'woocommerce-checkout-manager' ) . '">*</abbr>';
	} else {
		$required = '';
	}

	$args['maxlength'] = ( $args['maxlength'] ) ? 'maxlength="' . absint( $args['maxlength'] ) . '"' : '';

	if( is_string( $args['label_class'] ) ) {
		$args['label_class'] = array( $args['label_class'] );
	}

	if( is_null( $value ) ) {
		$value = $args['default'];
	}

	// Custom attribute handling
	$custom_attributes = array();

	if( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
		foreach( $args['custom_attributes'] as $attribute => $attribute_value ) {
			$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
		}
	}

	if( !empty( $args['validate'] ) ) {
		foreach( $args['validate'] as $validate ) {
			$args['class'][] = 'validate-' . $validate;
		}
	}

	$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $args['id'] ) . '_field">';

	if( $args['label'] ) {
		$field .= '<label for="' . esc_attr( $args['id'] ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) .'">' . $args['label']. $required . '</label>';
	}

	$field .= '<input type="password" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '" value="' . esc_attr( $value ) . '" ' . implode( ' ', $custom_attributes ) . ' />';

	if( $args['description'] ) {
		$field .= '<span class="description">' . esc_attr( $args['description'] ) . '</span>';
	}

	$field .= '</p>' . $after;

	return $field;

}
add_filter( 'woocommerce_form_field_wooccmpassword', 'wooccm_checkout_field_password_handler', 10, 4 );

// WooCommerce Checkout field - Radio Buttons
function wooccm_checkout_field_radio_handler( $field = '', $key, $args, $value ) {

	global $current_user;

	$user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);

	if( !empty($args['user_role']) && (!empty($args['role_options']) || !empty($args['role_options2'])) ) {
		$rolekeys = explode('||',$args['role_options']);
		$rolekeys2 = explode('||',$args['role_options2']);
		if( !empty($args['role_options']) && !in_array($user_role, $rolekeys) ) {
			return;
		}
		if( !empty($args['role_options2']) && in_array($user_role, $rolekeys2) ) {
			return;
		}
	}

	if( !empty( $args['clear'] ) )
		$after = '<div class="clear"></div>';
	else
		$after = '';

	if( $args['wooccm_required'] ) {
		$args['class'][] = 'validate-required';
		$required = ' <abbr class="required" title="' . esc_attr( 'required', 'woocommerce-checkout-manager' ) . '">*</abbr>';
	} else {
		$required = '';
	}

	$args['maxlength'] = ( $args['maxlength'] ) ? 'maxlength="' . absint( $args['maxlength'] ) . '"' : '';

	$field = '<div class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">';

	$field .= '<fieldset><legend>' . $args['label'] . $required . '</legend>';

	if( !empty( $args['options'] ) ) {
		foreach( explode('||',$args['options']) as $option_key => $option_text ) {
			$field .= '<label><input type="radio" ' . checked( $value, wooccm_wpml_string( esc_attr( $option_text ) ), false ) . ' name="' . esc_attr( $key ) . '" value="' . wooccm_wpml_string( esc_attr( $option_text ) ). '" /> ' . wooccm_wpml_string( esc_html( $option_text ) ). '</label>';
		}
	}

	$field .= '</fieldset></div>' . $after;

	return $field;

}
add_filter( 'woocommerce_form_field_wooccmradio', 'wooccm_checkout_field_radio_handler', 10, 4 );

// WooCommerce Checkout field - Select Options
function wooccm_checkout_field_select_handler( $field = '', $key, $args, $value ) {

	global $current_user;

	$user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);

	if( !empty($args['user_role']) && (!empty($args['role_options']) || !empty($args['role_options2'])) ) {
		$rolekeys = explode('||',$args['role_options']);
		$rolekeys2 = explode('||',$args['role_options2']);
		if( !empty($args['role_options']) && !in_array($user_role, $rolekeys) ) {
			return;
		}
		if( !empty($args['role_options2']) && in_array($user_role, $rolekeys2) ) {
			return;
		}
	}	

	if( !empty( $args['clear'] ) )
		$after = '<div class="clear"></div>';
	else
		$after = '';

	if( $args['wooccm_required'] ) {
		$args['class'][] = 'validate-required';
		$required = ' <abbr class="required" title="' . esc_attr( 'required', 'woocommerce-checkout-manager' ) . '">*</abbr>';
	} else {
		$required = '';
	}

	$args['maxlength'] = ( $args['maxlength'] ) ? 'maxlength="' . absint( $args['maxlength'] ) . '"' : '';

	$options = '';

	if( !empty( $args['options'] ) )
		$options .= ($args['default']) ?'<option value="">' . $args['default'] .'</option>': '';
		foreach (explode('||',$args['options']) as $option_key => $option_text )
			$options .= '<option '. selected( $value, $option_key, false ) . '>' . wooccm_wpml_string( esc_attr( $option_text ) ) .'</option>';

	$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">';

	if( $args['label'] )
		$field .= '<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required . '</label>';

	$field .= '
		<select class="' . esc_attr( $args['fancy'] ) .'" data-placeholder="' . __( $args['default'], 'wc_checkout_fields' ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" >
			' . $options . '
		</select>
	</p>' . $after;

	return $field;

}
add_filter( 'woocommerce_form_field_wooccmselect', 'wooccm_checkout_field_select_handler', 10, 4 );

// WooCommerce Checkout field - Check Box
function wooccm_checkout_field_checkbox_handler( $field = '', $key, $args, $value ) {

	global $current_user;

	$user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);

	if( !empty($args['user_role']) && (!empty($args['role_options']) || !empty($args['role_options2'])) ) {
		$rolekeys = explode('||',$args['role_options']);
		$rolekeys2 = explode('||',$args['role_options2']);
		if( !empty($args['role_options']) && !in_array($user_role, $rolekeys) ) {
			return;
		}
		if( !empty($args['role_options2']) && in_array($user_role, $rolekeys2) ) {
			return;
		}
	}

	$args['options'] = explode('||',$args['options']);

	if( !empty( $args['clear'] ) )
		$after = '<div class="clear"></div>';
	else
		$after = '';

	if( $args['wooccm_required'] ) {
		$args['class'][] = 'validate-required';
		$required = ' <abbr class="required" title="' . esc_attr( 'required', 'woocommerce-checkout-manager' ) . '">*</abbr>';
	} else {
		$required = '';
	}

	$field = '
<p class="form-row ' . implode( ' ', $args['class'] ) .'" id="' . $key . '_field">
	<label for="' . $key . '_checkbox" class="checkbox ' . implode( ' ', $args['label_class'] ) .'">
		<input type="checkbox" id="' . $key . '_checkbox" name="' . $key . '" class="input-checkbox" value="1" />' . $args['label'] . $required . '
	</label>
</p>' . $after;

	return $field;

}
add_filter( 'woocommerce_form_field_checkbox_wccm', 'wooccm_checkout_field_checkbox_handler', 10, 4 );

// WooCommerce Checkout field - State
function wooccm_checkout_field_state_handler( $field = '', $key, $args, $value ) {

	global $current_user;

	$user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);

	if( !empty( $args['user_role'] ) && (!empty( $args['role_options'] ) || !empty ($args['role_options2'] )) ) {
		$rolekeys = explode( '||',$args['role_options'] );
		$rolekeys2 = explode( '||',$args['role_options2'] );
		if( !empty($args['role_options']) && !in_array($user_role, $rolekeys) ) {
			return;
		}
		if( !empty($args['role_options2']) && in_array($user_role, $rolekeys2) ) {
			return;
		}
	}

	if( !empty( $args['clear'] ) )
		$after = '<div class="clear"></div>';
	else
		$after = '';

	$args['class'][] = 'address-field';

	$country_key = $key == 'billing_state' ? 'billing_country' : 'shipping_country';
	$current_cc  = WC()->checkout->get_value( $country_key );
	$states      = WC()->countries->get_states( $current_cc );

	if( $args['wooccm_required'] ) {
		if( !empty( $states ) ) {
			if( !in_array( 'validate-required', $args['class'] ) )
				$args['class'][] = 'validate-required';
			$required = ' <abbr class="required" title="' . esc_attr( 'required', 'woocommerce-checkout-manager' ) . '">*</abbr>';
		} else {
			$args['class'][] = 'woocommerce-validated';
		}
	} else {
		$required = '';
		$args['class'][] = 'woocommerce-validated';
	}

	$args['maxlength'] = ( $args['maxlength'] ) ? 'maxlength="' . absint( $args['maxlength'] ) . '"' : '';

	if( is_string( $args['label_class'] ) ) {
		$args['label_class'] = array( $args['label_class'] );
	}

	if( is_null( $value ) ) {
		$value = $args['default'];
	}

	// Custom attribute handling
	$custom_attributes = array();

	if( !empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
		foreach ( $args['custom_attributes'] as $attribute => $attribute_value ) {
			$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
		}
	}

	if( !empty( $states ) && !empty( $args['validate'] ) ) {
		foreach( $args['validate'] as $validate ) {
			$args['class'][] = 'validate-' . $validate;
		}
	}

	if( is_array( $states ) && empty( $states ) ) {

		$field  = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $args['id'] ) . '_field" style="display: none">';

		if( $args['label'] ) {
			$field .= '<label for="' . esc_attr( $args['id'] ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) .'">' . $args['label'] . $required . '</label>';
		}
		$field .= '<input type="hidden" class="hidden" name="' . esc_attr( $key )  . '" id="' . esc_attr( $args['id'] ) . '" value="" ' . implode( ' ', $custom_attributes ) . ' placeholder="' . esc_attr( $args['placeholder'] ) . '" />';

		if( $args['description'] ) {
			$field .= '<span class="description">' . esc_attr( $args['description'] ) . '</span>';
		}

		$field .= '</p>' . $after;

	} elseif ( is_array( $states ) ) {

		$field  = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $args['id'] ) . '_field">';

		if( $args['label'] )
			$field .= '<label for="' . esc_attr( $args['id'] ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) .'">' . $args['label']. $required . '</label>';
		$field .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="state_select ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'" ' . implode( ' ', $custom_attributes ) . ' placeholder="' . esc_attr( $args['placeholder'] ) . '">
			<option value="">' . __( 'Select a state&hellip;', 'woocommerce-checkout-manager' ) . '</option>';

		foreach( $states as $ckey => $cvalue ) {
			$field .= '<option value="' . esc_attr( $ckey ) . '" '.selected( $value, $ckey, false ) .'>'.__( $cvalue, 'woocommerce-checkout-manager' ) .'</option>';
		}

		$field .= '</select>';

		if( $args['description'] ) {
			$field .= '<span class="description">' . esc_attr( $args['description'] ) . '</span>';
		}

		$field .= '</p>' . $after;

	} else {

		$field  = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $args['id'] ) . '_field">';

		if( $args['label'] ) {
			$field .= '<label for="' . esc_attr( $args['id'] ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) .'">' . $args['label']. $required . '</label>';
		}
		$field .= '<input type="text" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'" value="' . esc_attr( $value ) . '"  placeholder="' . esc_attr( $args['placeholder'] ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" ' . implode( ' ', $custom_attributes ) . ' />';

		if( $args['description'] ) {
			$field .= '<span class="description">' . esc_attr( $args['description'] ) . '</span>';
		}

		$field .= '</p>' . $after;
	}

	return $field;

}
add_filter( 'woocommerce_form_field_wooccmstate', 'wooccm_checkout_field_state_handler', 10, 4 );

// WooCommerce Checkout field - Country
function wooccm_checkout_field_country_handler( $field = '', $key, $args, $value ) {

	global $current_user;

	$user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);

	if( !empty($args['user_role']) && (!empty($args['role_options']) || !empty($args['role_options2'])) ) {
		$rolekeys = explode('||',$args['role_options']);
		$rolekeys2 = explode('||',$args['role_options2']);
		if( !empty($args['role_options']) && !in_array($user_role, $rolekeys) ) {
			return;
		}
		if( !empty($args['role_options2']) && in_array($user_role, $rolekeys2) ) {
			return;
		}
	}

	if( ( !empty( $args['clear'] ) ) ) {
		$after = '<div class="clear"></div>';
	} else {
		$after = '';
	}

	$args['class'][] = 'address-field';

	if( $args['wooccm_required'] ) {
		$args['class'][] = 'validate-required';
		$required = ' <abbr class="required" title="' . esc_attr( 'required', 'woocommerce-checkout-manager'  ) . '">*</abbr>';
	} else {
		$required = '';
	}

	$args['maxlength'] = ( $args['maxlength'] ) ? 'maxlength="' . absint( $args['maxlength'] ) . '"' : '';

	if( is_string( $args['label_class'] ) ) {
		$args['label_class'] = array( $args['label_class'] );
	}

	if( is_null( $value ) ) {
		$value = $args['default'];
	}

	// Custom attribute handling
	$custom_attributes = array();

	if( !empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
		foreach( $args['custom_attributes'] as $attribute => $attribute_value ) {
			$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
		}
	}

	if( !empty( $args['validate'] ) ) {
		foreach( $args['validate'] as $validate ) {
			$args['class'][] = 'validate-' . $validate;
		}
	}

	$countries = $key == 'shipping_country' ? WC()->countries->get_shipping_countries() : WC()->countries->get_allowed_countries();

	if( sizeof( $countries ) == 1 ) {

		$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $args['id'] ) . '_field">';

		if( $args['label'] ) {
			$field .= '<label class="' . esc_attr( implode( ' ', $args['label_class'] ) ) .'">' . $args['label']  . '</label>';
		}

		$field .= '<strong>' . current( array_values( $countries ) ) . '</strong>';

		$field .= '<input type="hidden" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="' . current( array_keys($countries ) ) . '" ' . implode( ' ', $custom_attributes ) . ' class="country_to_state" />';

		if( $args['description'] ) {
			$field .= '<span class="description">' . esc_attr( $args['description'] ) . '</span>';
		}

		$field .= '</p>' . $after;

	} else {

		$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $args['id'] ) . '_field">'
				. '<label for="' . esc_attr( $args['id'] ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) .'">' . $args['label'] . $required  . '</label>'
				. '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="country_to_state country_select ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'" ' . implode( ' ', $custom_attributes ) . '>'
				. '<option value="">'.__( 'Select a country&hellip;', 'woocommerce-checkout-manager' ) .'</option>';

		foreach( $countries as $ckey => $cvalue ) {
			$field .= '<option value="' . esc_attr( $ckey ) . '" '.selected( $value, $ckey, false ) .'>'.__( $cvalue, 'woocommerce-checkout-manager' ) .'</option>';
		}

		$field .= '</select>';

		$field .= '<noscript><input type="submit" name="woocommerce_checkout_update_totals" value="' . __( 'Update country', 'woocommerce-checkout-manager' ) . '" /></noscript>';

		if( $args['description'] ) {
			$field .= '<span class="description">' . esc_attr( $args['description'] ) . '</span>';
		}

		$field .= '</p>' . $after;

	}

	return $field;

}
add_filter( 'woocommerce_form_field_wooccmcountry', 'wooccm_checkout_field_country_handler', 10, 4 );

// WooCommerce Checkout field - Multi-Select
function wooccm_checkout_field_multiselect_handler( $field = '', $key, $args, $value ) {

	global $current_user;

	$user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);

	if( !empty($args['user_role']) && (!empty($args['role_options']) || !empty($args['role_options2'])) ) {
		$rolekeys = explode('||',$args['role_options']);
		$rolekeys2 = explode('||',$args['role_options2']);
		if( !empty($args['role_options']) && !in_array($user_role, $rolekeys) ) {
			return;
		}
		if( !empty($args['role_options2']) && in_array($user_role, $rolekeys2) ) {
			return;
		}
	}

	if ( ( ! empty( $args['clear'] ) ) )
		$after = '<div class="clear"></div>';
	else
		$after = '';

	if( $args['wooccm_required'] ) {
		$args['class'][] = 'validate-required';
		$required = ' <abbr class="required" title="' . esc_attr( 'required', 'woocommerce-checkout-manager'  ) . '">*</abbr>';
	} else {
		$required = '';
	}

	$args['maxlength'] = ( $args['maxlength'] ) ? 'maxlength="' . absint( $args['maxlength'] ) . '"' : '';

	$options = '';

	if( !empty( $args['options'] ) )
		foreach (explode('||',$args['options']) as $option_key => $option_text )
			$options .= '<option value="'.wooccm_wpml_string( esc_attr( $option_text ) ).'" '. selected( $value, $option_key, false ) . '>' . wooccm_wpml_string( esc_attr( $option_text ) ) .'</option>';

	$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">';

	if ( $args['label'] )
		$field .= '<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required . '</label>';

	$field .= '<select data-placeholder="' . __( 'Select some options', 'wc_checkout_fields' ) . '" multiple="multiple" name="' . esc_attr( $key ) . '[]" id="' . esc_attr( $key ) . '" class="checkout_chosen_select select">
			' . $options . '
		</select>
	</p>' . $after;

	return $field;

}
add_filter( 'woocommerce_form_field_multiselect', 'wooccm_checkout_field_multiselect_handler', 10, 4 );

// WooCommerce Checkout field - Multi-Checkbox
function wooccm_checkout_field_multicheckbox_handler( $field = '', $key, $args, $value ) {

	global $current_user;

	$user_roles = $current_user->roles;
	$user_role = array_shift( $user_roles );

	if( !empty($args['user_role']) && (!empty($args['role_options']) || !empty($args['role_options2'])) ) {
		$rolekeys = explode('||',$args['role_options']);
		$rolekeys2 = explode('||',$args['role_options2']);
		if( !empty($args['role_options']) && !in_array($user_role, $rolekeys) ) {
			return;
		}
		if( !empty($args['role_options2']) && in_array($user_role, $rolekeys2) ) {
			return;
		}
	}

	if( ( !empty( $args['clear'] ) ) )
		$after = '<div class="clear"></div>';
	else
		$after = '';

	if( $args['wooccm_required'] ) {
		$args['class'][] = 'validate-required';
		$required = ' <abbr class="required" title="' . esc_attr( 'required', 'woocommerce-checkout-manager'  ) . '">*</abbr>';
	} else {
		$required = '';
	}

	$args['maxlength'] = ( $args['maxlength'] ) ? 'maxlength="' . absint( $args['maxlength'] ) . '"' : '';

	$options = '';

	if( !empty( $args['options'] ) ) {
		foreach( explode('||',$args['options']) as $option_key => $option_text ) {
			$options .= '<label><input type="checkbox" name="' . esc_attr( $key ) . '[]" value="'.wooccm_wpml_string( esc_attr( $option_text ) ).'"'. selected( $value, $option_key, false ) . ' /> ' . wooccm_wpml_string( esc_attr( $option_text ) ) . '</label>';
		}
	}

	$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">';

	if( $args['label'] )
		$field .= '<label class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required . '</label>';

	$field .= $options . '
	</p>' . $after;

	return $field;

}
add_filter( 'woocommerce_form_field_multicheckbox', 'wooccm_checkout_field_multicheckbox_handler', 10, 4 );

// WooCommerce Checkout field - Color Picker
function wooccm_checkout_field_colorpicker_handler( $field = '', $key, $args, $value ) {

	global $current_user;

	$user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);
	
	if( !empty($args['user_role']) && (!empty($args['role_options']) || !empty($args['role_options2'])) ) {
		$rolekeys = explode('||',$args['role_options']);
		$rolekeys2 = explode('||',$args['role_options2']);
		if( !empty($args['role_options']) && !in_array($user_role, $rolekeys) ) {
			return;
		}
		if( !empty($args['role_options2']) && in_array($user_role, $rolekeys2) ) {
			return;
		}
	}
	
	if( ( !empty( $args['clear'] ) ) )
		$after = '<div class="clear"></div>';
	else
		$after = '';

	if( $args['wooccm_required'] ) {
		$args['class'][] = 'validate-required';
		$required = ' <abbr class="required" title="' . esc_attr( 'required', 'woocommerce-checkout-manager'  ) . '">*</abbr>';
	} else {
		$required = '';
	}
		
	//if ( isset($value) ) {
	$value = $args['color'];
	//}

	$field = '
<p class="form-row ' . implode( ' ', $args['class'] ) .' wccs_colorpicker" id="' . $key . '_field">
	<label for="' . $key . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label'] . $required . '</label>
	<input type="text" class="input-text" maxlength="7" size="6" name="' . $key . '" id="' . $key . '_colorpicker" placeholder="' . $args['placeholder'] . '" value="'.$value.'" />
	<span id="' . $key . '_colorpickerdiv" class="spec_shootd"></span>
</p>' . $after;

	return $field;

}
add_filter( 'woocommerce_form_field_colorpicker', 'wooccm_checkout_field_colorpicker_handler', 10, 4 );

// WooCommerce Checkout field - Date Picker
function wooccm_checkout_field_datepicker_handler( $field = '', $key, $args, $value ) {

	global $current_user;

	$user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);

	if( !empty($args['user_role']) && (!empty($args['role_options']) || !empty($args['role_options2'])) ) {
		$rolekeys = explode('||',$args['role_options']);
		$rolekeys2 = explode('||',$args['role_options2']);
		if( !empty($args['role_options']) && !in_array($user_role, $rolekeys) ) {
			return;
		}
		if( !empty($args['role_options2']) && in_array($user_role, $rolekeys2) ) {
			return;
		}
	}
	

	if( ( !empty( $args['clear'] ) ) )
		$after = '<div class="clear"></div>';
	else
		$after = '';

	if( $args['wooccm_required'] ) {
		$args['class'][] = 'validate-required';
		$required = ' <abbr class="required" title="' . esc_attr( 'required', 'woocommerce-checkout-manager'  ) . '">*</abbr>';
	} else {
		$required = '';
	}

	$field = '<p class="form-row ' . implode( ' ', $args['class'] ) .'MyDate'.$args['cow'].' wccs-form-row-wide" id="' . $key . '_field">
					<label for="' . $key . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label'] . $required . '</label>
					<input type="text" class="input-text" name="' . $key . '" id="' . $key . '" placeholder="' . $args['placeholder'] . '" value="'. $value.'" />
				</p>' . $after;

	return $field;

}
add_filter( 'woocommerce_form_field_datepicker', 'wooccm_checkout_field_datepicker_handler', 10, 4 );

// WooCommerce Checkout field - Time Picker
function wooccm_checkout_field_timepicker_handler( $field = '', $key, $args, $value ) {

	global $current_user;

	$user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);

	if( !empty($args['user_role']) && (!empty($args['role_options']) || !empty($args['role_options2'])) ) {
		$rolekeys = explode('||',$args['role_options']);
		$rolekeys2 = explode('||',$args['role_options2']);
		if( !empty($args['role_options']) && !in_array($user_role, $rolekeys) ) {
			return;
		}
		if( !empty($args['role_options2']) && in_array($user_role, $rolekeys2) ) {
			return;
		}
	}

	if( ( !empty( $args['clear'] ) ) )
		$after = '<div class="clear"></div>';
	else
		$after = '';

	if( $args['wooccm_required'] ) {
		$args['class'][] = 'validate-required';
		$required = ' <abbr class="required" title="' . esc_attr( 'required', 'woocommerce-checkout-manager'  ) . '">*</abbr>';
	} else {
		$required = '';
	}

	$field = '<p class="form-row ' . implode( ' ', $args['class'] ) .'MyTime'.$args['cow'].' wccs-form-row-wide" id="' . $key . '_field">
					<label for="' . $key . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label'] . $required . '</label>
					<input type="text" class="input-text" name="' . $key . '" id="' . $key . '" placeholder="' . $args['placeholder'] . '" value="'. $value.'" />
				</p>' . $after;

	return $field;

}
add_filter( 'woocommerce_form_field_time', 'wooccm_checkout_field_timepicker_handler', 10, 4 );

// WooCommerce Checkout field - File Picker
function wooccm_checkout_field_upload_handler( $field = '', $key, $args, $value ) {

	global $wpdb, $woocommerce, $post, $current_user;

	$user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);
	
	if( !empty($args['user_role']) && (!empty($args['role_options']) || !empty($args['role_options2'])) ) {
		$rolekeys = explode('||',$args['role_options']);
		$rolekeys2 = explode('||',$args['role_options2']);
		if( !empty($args['role_options']) && !in_array($user_role, $rolekeys) ) {
			return;
		}
		if( !empty($args['role_options2']) && in_array($user_role, $rolekeys2) ) {
			return;
		}
	}

	$upload_name = ( !empty($args['placeholder'] ) ? esc_attr( $args['placeholder'] ) : __( 'Upload Files', 'woocommerce-checkout-manager' ) );

	if( ( !empty( $args['clear'] ) ) ) {
		$after = '<div class="clear"></div>';
	} else {
		$after = '';
	}

	if( $args['wooccm_required'] ) {
		$args['class'][] = 'validate-required';
		$required = ' <abbr class="required" title="' . esc_attr( 'required', 'woocommerce-checkout-manager'  ) . '">*</abbr>';
	} else {
		$required = '';
	}

	$args['maxlength'] = ( $args['maxlength'] ) ? 'maxlength="' . absint( $args['maxlength'] ) . '"' : '';

	if( is_string( $args['label_class'] ) ) {
		$args['label_class'] = array( $args['label_class'] );
	}

	if( is_null( $value ) ) {
		$value = $args['default'];
	}

	// Custom attribute handling
	$custom_attributes = array();

	if( !empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
		foreach( $args['custom_attributes'] as $attribute => $attribute_value ) {
			$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
		}
	}

	if( !empty( $args['validate'] ) ) {
		foreach( $args['validate'] as $validate ) {
			$args['class'][] = 'validate-' . $validate;
		}
	}

	$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $args['id'] ) . '_field">';

	if( $args['label'] ) {
		$field .= '<label for="' . esc_attr( $args['id'] ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) .'">' . $args['label'] . $required . '</label>';
	}

/*
	// @mod - It looks like the file picker ignores required
	$field .= '
<!-- <input style="display:none;" type="hidden" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="1||" /> -->
';
*/
	$field .= '
<input style="display:none;" type="hidden" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="" />
<input style="display:none;" type="file" name="' . esc_attr( $key ) . '_file" id="' . esc_attr( $key ) . '_file" class="file_upload_button_hide" multiple />

<button type="button" class="file_upload_account wooccm-btn wooccm-btn-primary start" id="' . esc_attr( $key ) . '_files_button_wccm">'.$upload_name.'</button>';

	$field .= '</p>' . $after;

	return $field;

}
add_filter( 'woocommerce_form_field_wooccmupload', 'wooccm_checkout_field_upload_handler', 10, 4 );

// WooCommerce Checkout field - Heading
function wooccm_checkout_field_heading_handler( $field = '', $key, $args, $value ) {

	global $current_user;

	$user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);

	if( !empty($args['user_role']) && (!empty($args['role_options']) || !empty($args['role_options2'])) ) {
		$rolekeys = explode('||',$args['role_options']);
		$rolekeys2 = explode('||',$args['role_options2']);
		if( !empty($args['role_options']) && !in_array($user_role, $rolekeys) ) {
			return;
		}
		if( !empty($args['role_options2']) && in_array($user_role, $rolekeys2) ) {
			return;
		}
	}

	$field = '<h3 class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">' . $args['label'] . '</h3>';

	return $field;

}
add_filter( 'woocommerce_form_field_heading', 'wooccm_checkout_field_heading_handler', 10, 4 );
?>