<?php
function wooccm_checkout_additional_fields( $checkout ) {

	$options = get_option( 'wccs_settings' );
	$buttons = ( isset( $options['buttons'] ) ? $options['buttons'] : false );

	// Check if we have any fields to process
	if( empty( $buttons ) )
		return;

	foreach( $buttons as $btn ) {

		if( $btn['type'] == 'heading' && empty( $btn['deny_checkout'] ) ) {
			echo '
<h3 class="form-row '.$btn['position'].'" id="'.$btn['cow'].'_field">' . wooccm_wpml_string( $btn['label'] ) . '</h3>';
		}

		switch( $btn['type'] ) {

			// Text field
			case 'wooccmtext':
				woocommerce_form_field( $btn['cow'], array(
					'type'          => 'wooccmtext',
					'class'         => array( $btn['position'].' '.$btn['conditional_tie'].' '.$btn['extra_class'] ),
					'label'         =>  wooccm_wpml_string( $btn['label'] ),
					'wooccm_required'  => ( isset( $btn['checkbox'] ) ? $btn['checkbox'] : '' ),
					'clear'  => ( isset( $btn['clear_row'] ) ? $btn['clear_row'] : '' ),
					'user_role'  => ( isset( $btn['user_role'] ) ? $btn['user_role'] : '' ),
					'role_options' => ( isset( $btn['role_options'] ) ? $btn['role_options'] : '' ),
					'role_options2' => ( isset( $btn['role_options2'] ) ? $btn['role_options2'] : '' ),
					'placeholder'       => wooccm_wpml_string( $btn['placeholder'] ),
					), $checkout->get_value( $btn['cow'] )
				);
				break;

			// Textarea field
			case 'wooccmtextarea':
				woocommerce_form_field( $btn['cow'], array(
					'type'          => 'wooccmtextarea',
					'class'         => array( $btn['position'].' wccs-form-row-wide '.$btn['conditional_tie'].' '.$btn['extra_class'] ),
					'label'         =>  wooccm_wpml_string( $btn['label'] ),
					'wooccm_required'  => ( isset( $btn['checkbox'] ) ? $btn['checkbox'] : '' ),
					'clear'  => ( isset( $btn['clear_row'] ) ? $btn['clear_row'] : '' ),
					'user_role'  => ( isset( $btn['user_role'] ) ? $btn['user_role'] : '' ),
					'role_options' => ( isset( $btn['role_options'] ) ? $btn['role_options'] : '' ),
					'role_options2' => ( isset( $btn['role_options2'] ) ? $btn['role_options2'] : '' ),
					'placeholder'       => wooccm_wpml_string( $btn['placeholder'] ),
					), $checkout->get_value( $btn['cow'] )
				);
				break;

			// Password field
			case 'wooccmpassword':
				woocommerce_form_field( $btn['cow'], array(
					'type'          => 'wooccmpassword',
					'class'         => array( $btn['position'].' '.$btn['conditional_tie'].' '.$btn['extra_class'].' '.$btn['extra_class'] ),
					'label'         => wooccm_wpml_string( $btn['label'] ),
					'wooccm_required'  => ( isset( $btn['checkbox'] ) ? $btn['checkbox'] : '' ),
					'user_role'  => ( isset( $btn['user_role'] ) ? $btn['user_role'] : '' ),
					'role_options' => ( isset( $btn['role_options'] ) ? $btn['role_options'] : '' ),
					'role_options2' => ( isset( $btn['role_options2'] ) ? $btn['role_options2'] : '' ),
					'clear'  => ( isset( $btn['clear_row'] ) ? $btn['clear_row'] : '' ),
					'placeholder'       => $btn['placeholder'],
					), $checkout->get_value( $btn['cow'] )
				);
				break;

			// Checkbox field (single)
			case 'checkbox_wccm':
				woocommerce_form_field( $btn['cow'], array(
					'type'          => 'checkbox_wccm',
					'class'         => array( $btn['position'].' '.$btn['conditional_tie'].' '.$btn['extra_class'] ),
					'label'         =>  wooccm_wpml_string( $btn['label'] ),
					'user_role'  => ( isset( $btn['user_role'] ) ? $btn['user_role'] : '' ),
					'role_options' => ( isset( $btn['role_options'] ) ? $btn['role_options'] : '' ),
					'role_options2' => ( isset( $btn['role_options2'] ) ? $btn['role_options2'] : '' ),
					'required' => ( isset( $btn['checkbox'] ) ? $btn['checkbox'] : false ),
					'wooccm_required'  => ( isset( $btn['checkbox'] ) ? $btn['checkbox'] : false ),
					'clear'  => ( isset( $btn['clear_row'] ) ? $btn['clear_row'] : '' ),
					'options'       => $btn['option_array'],
					), $checkout->get_value( $btn['cow'] )
				);
				break;

			// Select field (single)
			case 'wooccmselect':
				woocommerce_form_field( $btn['cow'] , array(
					'type'          => 'wooccmselect',
					'class'         => array( $btn['position'].' '.$btn['conditional_tie'].' '.$btn['extra_class'] ),
					'label'         => wooccm_wpml_string( $btn['label'] ),
					'wooccm_required'  => ( isset( $btn['checkbox'] ) ? $btn['checkbox'] : '' ),
					'clear'  => ( isset( $btn['clear_row'] ) ? $btn['clear_row'] : '' ),
					'user_role'  => ( isset( $btn['user_role'] ) ? $btn['user_role'] : '' ),
					'role_options' => ( isset( $btn['role_options'] ) ? $btn['role_options'] : '' ),
					'role_options2' => ( isset( $btn['role_options2'] ) ? $btn['role_options2'] : '' ),
					'fancy' => ( isset( $btn['fancy'] ) ? $btn['fancy'] : '' ),
					'default' => $btn['force_title2'],
					'options'       => $btn['option_array'],
					), $checkout->get_value( $btn['cow'] )
				);
				break;

			// Radio field (multiple)
			case 'wooccmradio':
				woocommerce_form_field( $btn['cow'], array(
					'type'          => 'wooccmradio',
					'class'         => array( $btn['position'].' '.$btn['conditional_tie'].' '.$btn['extra_class'] ),
					'label'         => wooccm_wpml_string( $btn['label'] ),
					'wooccm_required'  => ( isset( $btn['checkbox'] ) ? $btn['checkbox'] : '' ),
					'default' => $btn['force_title2'],
					'user_role'  => ( isset( $btn['user_role'] ) ? $btn['user_role'] : '' ),
					'role_options' => ( isset( $btn['role_options'] ) ? $btn['role_options'] : '' ),
					'role_options2' => ( isset( $btn['role_options2'] ) ? $btn['role_options2'] : '' ),
					'clear'  => ( isset( $btn['clear_row'] ) ? $btn['clear_row'] : '' ),
					'options'       => $btn['option_array'],
					), $checkout->get_value( $btn['cow'] )
				);
				break;

			// Multiple select field
			case 'multiselect':
				woocommerce_form_field( $btn['cow'], array(
					'type'          => 'multiselect',
					'class'         => array( $btn['position'].' '.$btn['conditional_tie'].' '.$btn['extra_class'] ),
					'label'         => wooccm_wpml_string( $btn['label'] ),
					'wooccm_required'  => ( isset( $btn['checkbox'] ) ? $btn['checkbox'] : '' ),
					'user_role'  => ( isset( $btn['user_role'] ) ? $btn['user_role'] : '' ),
					'role_options' => ( isset( $btn['role_options'] ) ? $btn['role_options'] : '' ),
					'role_options2' => ( isset( $btn['role_options2'] ) ? $btn['role_options2'] : '' ),
					'clear'  => ( isset( $btn['clear_row'] ) ? $btn['clear_row'] : '' ),
					'options'       =>$btn['option_array'],
					), $checkout->get_value( $btn['cow'] )
				);
				break;

			// Mulitple checkbox field
			case 'multicheckbox':
			// Check if Multi-checkbox has options assigned to it
				if( empty( $btn['option_array'] ) )
					continue;
				woocommerce_form_field( $btn['cow'], array(
					'type'          => 'multicheckbox',
					'class'         => array( $btn['position'].' '.$btn['conditional_tie'].' '.$btn['extra_class'] ),
					'label'         => wooccm_wpml_string( $btn['label'] ),
					'wooccm_required'  => ( isset( $btn['checkbox'] ) ? $btn['checkbox'] : '' ),
					'user_role'  => ( isset( $btn['user_role'] ) ? $btn['user_role'] : '' ),
					'role_options' => ( isset( $btn['role_options'] ) ? $btn['role_options'] : '' ),
					'role_options2' => ( isset( $btn['role_options2'] ) ? $btn['role_options2'] : '' ),
					'clear'  => ( isset( $btn['clear_row'] ) ? $btn['clear_row'] : '' ),
					'options'       => $btn['option_array'],
					), $checkout->get_value( $btn['cow'] )
				);
				break;

			// Colour picker
			case 'colorpicker':
				woocommerce_form_field( $btn['cow'], array(
					'type'          => 'colorpicker',
					'class'         => array( $btn['position'].' '.$btn['conditional_tie'].' wccs_colorpicker '.$btn['extra_class'] ),
					'label'         =>  wooccm_wpml_string( $btn['label'] ),
					'wooccm_required'  => ( isset( $btn['checkbox'] ) ? $btn['checkbox'] : '' ),
					'clear'  => ( isset( $btn['clear_row'] ) ? $btn['clear_row'] : '' ),
					'user_role'  => ( isset( $btn['user_role'] ) ? $btn['user_role'] : '' ),
					'role_options' => ( isset( $btn['role_options'] ) ? $btn['role_options'] : '' ),
					'role_options2' => ( isset( $btn['role_options2'] ) ? $btn['role_options2'] : '' ),
					'placeholder'       => wooccm_wpml_string( $btn['placeholder'] ),
					'color' => ( isset( $btn['colorpickerd'] ) ? $btn['colorpickerd'] : '' ),
					'colorpickertype' => ( isset( $btn['colorpickertype'] ) ? $btn['colorpickertype'] : '' )
					), $checkout->get_value( $btn['cow'] )
				);
				break;

			// Date picker
			case 'datepicker':
				woocommerce_form_field( $btn['cow'], array(
					'type'          => 'wooccmtext',
					'class'         => array( $btn['position'].' MyDate'.$btn['cow'].' wccs-form-row-wide '.$btn['conditional_tie'].' '.$btn['extra_class'] ),
					'label'         =>  wooccm_wpml_string( $btn['label'] ),
					'wooccm_required'  => ( isset( $btn['checkbox'] ) ? $btn['checkbox'] : '' ),
					'user_role'  => ( isset( $btn['user_role'] ) ? $btn['user_role'] : '' ),
					'role_options' => ( isset( $btn['role_options'] ) ? $btn['role_options'] : '' ),
					'role_options2' => ( isset( $btn['role_options2'] ) ? $btn['role_options2'] : '' ),
					'clear'  => ( isset( $btn['clear_row'] ) ? $btn['clear_row'] : '' ),
					'placeholder'       => wooccm_wpml_string( $btn['placeholder'] ),
					), $checkout->get_value( $btn['cow'] )
				);
				break;

			// Time picker
			case 'time':
				woocommerce_form_field( $btn['cow'], array(
					'type'          => 'wooccmtext',
					'class'         => array( $btn['position'].' MyTime'.$btn['cow'].' wccs-form-row-wide '.$btn['conditional_tie'].' '.$btn['extra_class']),
					'label'         => wooccm_wpml_string( $btn['label'] ),
					'wooccm_required'  => ( isset( $btn['checkbox'] ) ? $btn['checkbox'] : '' ),
					'user_role'  => ( isset( $btn['user_role'] ) ? $btn['user_role'] : '' ),
					'role_options' => ( isset( $btn['role_options'] ) ? $btn['role_options'] : '' ),
					'role_options2' => ( isset( $btn['role_options2'] ) ? $btn['role_options2'] : '' ),
					'clear'  => ( isset( $btn['clear_row'] ) ? $btn['clear_row'] : '' ),
					'placeholder'       => wooccm_wpml_string( $btn['placeholder'] ),
					), $checkout->get_value( $btn['cow'] )
				);
				break;

			// File uploader
			case 'wooccmupload':
				woocommerce_form_field( $btn['cow'] , array(
					'type'          => 'wooccmupload',
					'placeholder'          => $btn['placeholder'],
					'class'         => array( $btn['position'].' '.$btn['conditional_tie'].' '.$btn['extra_class'] ),
					'label'         => wooccm_wpml_string( $btn['label'] ),
					'wooccm_required'  => ( isset( $btn['checkbox'] ) ? $btn['checkbox'] : '' ),
					'clear'  => ( isset( $btn['clear_row'] ) ? $btn['clear_row'] : '' ),
					'user_role'  => ( isset( $btn['user_role'] ) ? $btn['user_role'] : '' ),
					'role_options' => ( isset( $btn['role_options'] ) ? $btn['role_options'] : '' ),
					'role_options2' => ( isset( $btn['role_options2'] ) ? $btn['role_options2'] : '' ),
					'fancy' => ( isset( $btn['fancy'] ) ? $btn['fancy'] : '' ),
					'default' => $btn['force_title2'],
					'options'       => $btn['option_array'],
					), $checkout->get_value( $btn['cow'] )
				);
				break;

		}

	}

}
?>