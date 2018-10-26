<?php
// Decides where the Additional Checkout fields appear on the Checkout page
function wooccm_checkout_additional_positioning() {

	$options = get_option( 'wccs_settings' );
	// Defaults to after_order_notes
	$position = ( !empty( $options['checkness']['position'] ) ? sanitize_text_field( $options['checkness']['position'] ) : 'after_order_notes' );
	switch( $position ) {

		case 'before_shipping_form':
		case 'after_shipping_form':
		case 'before_billing_form':
		case 'after_billing_form':
		case 'after_order_notes':
			return $position;
			break;

	}

}

function wooccm_checkout_default_address_fields( $fields ) {

	// Billing fields
	$options = get_option( 'wccs_settings3' );
	$buttons = ( isset( $options['billing_buttons'] ) ? $options['billing_buttons'] : false );
	if( !empty( $buttons ) ) {
		foreach( $buttons as $btn ) {
			if( !empty( $btn['cow'] ) && empty( $btn['deny_checkout'] ) ) {
				$key = $btn['cow'];
				if( isset( $fields[$key] ) )
					$fields[$key]['required'] = ( isset( $btn['checkbox'] ) ? $btn['checkbox'] : ( isset( $fields[$key]['required'] ) ? $fields[$key]['required'] : false ) );
			}
		}
	}

	return $fields;

}

function wooccm_autocreate_account( $fields ) {

	$options = get_option( 'wccs_settings' );

	if( !empty( $options['checkness']['auto_create_wccm_account'] ) ) {
?>
<script type="text/javascript">

	jQuery(document).ready(function() {
		jQuery( "input#createaccount" ).prop("checked","checked");
	});

</script>

<style type="text/css">
.create-account {
	display:none;
}
</style>

<?php
	}

}

function wooccm_display_front() {

	global $woocommerce;

	if( !is_checkout() )
		return;

	$options = get_option( 'wccs_settings' );

	// Hide Ship to a different address? heading
	if( !empty( $options['checkness']['additional_info'] ) ) {
		echo '
<style type="text/css">
.woocommerce-shipping-fields h3:first-child {
	display: none;
}
</style>
';
	}

	// Force show Billing fields
	if( !empty( $options['checkness']['show_shipping_fields'] ) ) {
		echo '
<style type="text/css">
.woocommerce-shipping-fields .shipping_address {
	display: block !important;
}
</style>
';
	}

	// Custom CSS
	echo '
<style type="text/css">';
	if( !empty( $options['checkness']['custom_css_w'] ) ) {
		echo esc_textarea( $options['checkness']['custom_css_w'] );
	}
	echo '

@media screen and (max-width: 685px) {
	.woocommerce .checkout .container .wooccm-btn {
		padding: 1% 6%;
	}
}

@media screen and (max-width: 685px) {
	.woocommerce .checkout .container .wooccm-btn {
		padding: 1% 8%;
	}
}

@media screen and (max-width: 770px) {
	.checkout .wooccm_each_file .wooccm-image-holder {
		width: 20%;
	}
	.checkout name.wooccm_name, .wooccm_each_file span.container{
		width: 80%;
	}
	.checkout .container .wooccm-btn {
		padding: 1% 10%;
	}
}

@media screen and (max-width: 992px) {
	.wooccm_each_file .wooccm-image-holder {
		width: 26%;
	}
	name.wooccm_name, .wooccm_each_file span.container{
		width: 74%;
	}
	.container .wooccm-btn {
		padding: 5px 8px;
		font-size: 12px;
	}
}

.container .wooccm-btn {
	padding: 1.7% 6.7%;
}

#caman_content .blockUI.blockOverlay:before, #caman_content .loader:before {
	height: 1em;
	width: 1em;
	position: absolute;
	top: 50%;
	left: 50%;
	margin-left: -.5em;
	margin-top: -.5em;
	display: block;
	-webkit-animation: spin 1s ease-in-out infinite;
	-moz-animation: spin 1s ease-in-out infinite;
	animation: spin 1s ease-in-out infinite;
	content: "";
	/* @mod - We need to check this file exists */
	background: url('.plugins_url( 'woocommerce/assets/images/icons/loader.svg' ).') center center/cover;
	line-height: 1;
	text-align: center;
	font-size: 2em;
	color: rgba(0,0,0,.75);
}
body.admin-bar #caman_content {
	margin-top:	32px;
}

.file_upload_button_hide {
	display: none;
}

.wooccm_each_file {
	display: block;
	padding-top: 20px;
	clear: both;
	text-align: center;
}
.wooccm_each_file .wooccm-image-holder {
	width: 20%;
	display: block;
	float: left;
}
.wooccm-btn.disable {
	margin-right: 10px;
	cursor: auto;
}
zoom.wooccm_zoom, edit.wooccm_edit, dele.wooccm_dele {
	padding: 5px;
}
.wooccm_each_file name {
	font-size: 18px;
}
name.wooccm_name, .wooccm_each_file span.container {
	display: block;
	padding: 0 0 10px 20px;
	float: left;
	width: 80%;
}

.wooccm_each_file img{ 
	display: inline-block;
	height: 90px;
	border: 2px solid #767676;
	border-radius: 4px;
}
.file_upload_account:before{ content: "\f317";font-family: dashicons; margin-right: 10px; }
.wooccm_each_file .wooccm_zoom:before{ content: "\f179";font-family: dashicons; margin-right: 5px; }
.wooccm_each_file .wooccm_edit:before{ content: "\f464";font-family: dashicons; margin-right: 5px; }
.wooccm_each_file .wooccm_dele:before{ content: "\f158";font-family: dashicons; margin-right: 5px; }
.wooccm-btn{
	display: inline-block;
	padding: 6px 12px;
	margin-bottom: 0;
	font-size: 14px;
	font-weight: 400;
	line-height: 1.42857143;
	text-align: center;
	white-space: nowrap;
	vertical-align: middle;
	cursor: pointer;
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
	background-image: none;
	border: 1px solid transparent;
	border-radius: 4px;
	font-family: "Raleway", Arial, Helvetica, sans-serif;
	color: #767676;
	background-color: buttonface;
	align-items: flex-start;
	text-indent: 0px;
	text-shadow: none;
	letter-spacing: normal;
	word-spacing: normal;
	text-rendering: auto;
}
.wooccm-btn-primary {
	width: 100%;
	color: #fff;
	background-color: #428bca;
	border-color: #357ebd;
}

.wooccm-btn-danger {
	color: #fff;
	background-color: #d9534f;
	border-color: #d43f3a;
	margin-right: 10px;
}
.wooccm_each_file .container a:hover, .wooccm_each_file .container a:focus, .wooccm_each_file .container a:active, .wooccm_each_file .container a:visited, .wooccm_each_file .container a:link {
	color: #fff;
}
#caman_content #wooccmtoolbar #close:hover, #caman_content #wooccmtoolbar #save:hover {
	background: #1b1917;
}
.wooccm-btn-zoom {
	color: #fff;
	background-color: #5cb85c;
	border-color: #4cae4c;
	margin-right: 10px;
} 

.wooccm-btn-edit {
	color: #fff;
	background-color: #f0ad4e;
	border-color: #eea236;
	margin-right: 10px;
}

</style>
';

}

function wooccm_checkout_text_after() {

	$options = get_option( 'wccs_settings' );

	if( !empty($options['checkness']['text2']) ) {
		if( ( isset( $options['checkness']['checkbox3'] ) && $options['checkness']['checkbox3'] == true ) || ( isset( $options['checkness']['checkbox4'] ) && $options['checkness']['checkbox4'] == true ) ) {
			if( isset( $options['checkness']['checkbox4'] ) && $options['checkness']['checkbox4'] == true ) {
				echo $options['checkness']['text2'];
			}
		}
	}

	if( !empty($options['checkness']['text1']) ) {
		if( $options['checkness']['checkbox1'] == true || $options['checkness']['checkbox2'] == true ) {
			if( isset( $options['checkness']['checkbox2'] ) && $options['checkness']['checkbox2'] == true ) {
				echo $options['checkness']['text1'];
			}
		}
	}

}

function wooccm_checkout_text_before(){

	$options = get_option( 'wccs_settings' );

	if( !empty( $options['checkness']['text2'] ) ) {
		if( ( isset( $options['checkness']['checkbox3'] ) && $options['checkness']['checkbox3'] == true ) || ( isset( $options['checkness']['checkbox4'] ) && $options['checkness']['checkbox4'] == true ) ) {
			if( isset( $options['checkness']['checkbox3'] ) && $options['checkness']['checkbox3'] == true ) {
				echo $options['checkness']['text2'];
			}
		}
	}

	if( !empty( $options['checkness']['text1'] ) ) {
		if( $options['checkness']['checkbox1'] == true || $options['checkness']['checkbox2'] == true ) {
			if( isset( $options['checkness']['checkbox1'] ) && $options['checkness']['checkbox1'] == true ) {
				echo $options['checkness']['text1'];
			}
		}
	}

}

// We are overriding the default Order Post meta values with our own secret sauce
function wooccm_custom_checkout_field_update_order_meta( $order ) {

	// Additional section
	$options = get_option( 'wccs_settings' );
	$buttons = ( isset( $options['buttons'] ) ? $options['buttons'] : false );
	if( !empty( $buttons ) ) {
		foreach( $buttons as $btn ) {
			if( $btn['type'] == 'wooccmtextarea' ) {
				if( !empty( $_POST[$btn['cow']] ) ) {
					update_post_meta( $order, $btn['cow'] , wp_kses( $_POST[ $btn['cow']], false ) );
				}
			} else if( $btn['type'] !== 'multiselect' && $btn['type'] !== 'multicheckbox' ) {
				if( !empty( $_POST[$btn['cow']] ) ) {
					update_post_meta( $order, $btn['cow'] , sanitize_text_field( $_POST[ $btn['cow'] ] ) );
				}
			} elseif( $btn['type'] == 'multiselect' || $btn['type'] == 'multicheckbox' ) {
				if( !empty( $_POST[$btn['cow']] ) ) {
					update_post_meta( $order, $btn['cow'] , maybe_serialize( array_map( 'sanitize_text_field', $_POST[$btn['cow']] ) ) );
				}
			}
		}
	}

	// Shipping section
	$options = get_option( 'wccs_settings2' );
	$buttons = ( isset( $options['shipping_buttons'] ) ? $options['shipping_buttons'] : false );
	if( !empty( $buttons ) ) {
		foreach( $buttons as $btn ) {
			if( $btn['type'] == 'wooccmtextarea' ) {
				if( !empty( $_POST[sprintf( 'shipping_%s', $btn['cow'] )] ) ) {
					update_post_meta( $order, sprintf( '_shipping_%s', $btn['cow'] ), wp_kses( $_POST[sprintf( 'shipping_%s', $btn['cow'] )], false ) );
				}
			}
		}
	}

	// Billing section
	$options = get_option( 'wccs_settings3' );
	$buttons = ( isset( $options['billing_buttons'] ) ? $options['billing_buttons'] : false );
	if( !empty( $buttons ) ) {
		foreach( $buttons as $btn ) {
			if( $btn['type'] == 'wooccmtextarea' ) {
				if( !empty( $_POST[sprintf( 'billing_%s', $btn['cow'] )] ) ) {
					update_post_meta( $order, sprintf( '_billing_%s', $btn['cow'] ), wp_kses( $_POST[sprintf( 'billing_%s', $btn['cow'] )], false ) );
				}
			}
		}
	}

}

function wooccm_custom_checkout_field_update_user_meta( $user_id = 0, $posted ) {

	if( empty( $user_id ) )
		return;

	// Additional section
	$options = get_option( 'wccs_settings' );
	$buttons = ( isset( $options['buttons'] ) ? $options['buttons'] : false );
	if( !empty( $buttons ) ) {
		foreach( $buttons as $btn ) {
			if( $btn['type'] == 'wooccmtextarea' ) {
				if( !empty( $_POST[$btn['cow']] ) ) {
					update_user_meta( $user_id, $btn['cow'], wp_kses( $_POST[$btn['cow']], false ) );
				}
			}
		}
	}

	// Shipping section
	$options = get_option( 'wccs_settings2' );
	$buttons = ( isset( $options['shipping_buttons'] ) ? $options['shipping_buttons'] : false );
	if( !empty( $buttons ) ) {
		foreach( $buttons as $btn ) {
			if( $btn['type'] == 'wooccmtextarea' ) {
				if( !empty( $_POST[sprintf( 'shipping_%s', $btn['cow'] )] ) ) {
					update_user_meta( $user_id, sprintf( 'shipping_%s', $btn['cow'] ), wp_kses( $_POST[sprintf( 'shipping_%s', $btn['cow'] )], false ) );
				}
			}
		}
	}

	// Billing section
	$options = get_option( 'wccs_settings3' );
	$buttons = ( isset( $options['billing_buttons'] ) ? $options['billing_buttons'] : false );
	if( !empty( $buttons ) ) {
		foreach( $buttons as $btn ) {
			if( $btn['type'] == 'wooccmtextarea' ) {
				if( !empty( $_POST[sprintf( 'billing_%s', $btn['cow'] )] ) ) {
					update_user_meta( $user_id, sprintf( 'billing_%s', $btn['cow'] ), wp_kses( $_POST[sprintf( 'billing_%s', $btn['cow'] )], false ) );
				}
			}
		}
	}

}

function wooccm_custom_checkout_field_process() {

	global $woocommerce;

	$options = get_option( 'wccs_settings' );
	$buttons = ( isset( $options['buttons'] ) ? $options['buttons'] : false );
	if( !empty( $buttons ) ) {
		foreach( $buttons as $btn ) {
			if( isset( $btn['checkbox'] ) && $btn['checkbox'] === 'true' ) {
				// without checkbox
				if(
					empty( $btn['single_px_cat'] ) && 
					empty( $btn['single_p_cat'] ) && 
					empty( $btn['single_px'] ) && 
					empty( $btn['single_p'] ) && 
					!empty( $btn['label'] ) && 
					$btn['type'] !== 'wooccmupload' && 
					$btn['type'] !== 'changename' && 
					$btn['type'] !== 'heading'
				) {
					if( empty( $_POST[$btn['cow']] ) ) {
						$message = sprintf( __( '%s is a required field.', 'woocommerce' ), '<strong>' . wooccm_wpml_string( $btn['label'] ) . '</strong>' );
						wc_add_notice( $message, 'error' );
					}
				}
				// checkbox
				if( 
					empty( $btn['single_px_cat'] ) && 
					empty( $btn['single_p_cat'] ) && 
					empty( $btn['single_px'] ) && 
					empty( $btn['single_p'] ) && 
					!empty( $btn['label'] ) && 
					$btn['type'] == 'checkbox' && 
					$btn['type'] !== 'changename' && 
					$btn['type'] !== 'wooccmupload' && 
					$btn['type'] !== 'heading'
				) {
					if( ( sanitize_text_field( $_POST[$btn['cow']] ) == $btn['check_2'] )  && ( !empty ($btn['checkbox'] ) ) ) {
						$message = sprintf( __( '%s is a required field.bbb', 'woocommerce' ), '<strong>' . wooccm_wpml_string( $btn['label'] ) . '</strong>' );
						wc_add_notice( $message, 'error' );
					}
				}
			}
		}
	}

}

function wooccm_remove_fields_filter_billing( $fields ) {

	global $woocommerce;

	// Check if the cart is not empty
	if( empty( $woocommerce->cart->cart_contents ) )
		return $fields;

	$options = get_option( 'wccs_settings' );

	foreach( $woocommerce->cart->cart_contents as $key => $values ) {

		$multiCategoriesx = ( isset( $options['checkness']['productssave'] ) ? $options['checkness']['productssave'] : '' );
		$multiCategoriesArrayx = explode(',',$multiCategoriesx);

		if( in_array( $values['product_id'], $multiCategoriesArrayx ) && ( $woocommerce->cart->cart_contents_count < 2 ) ) {
			unset( $fields['billing']['billing_address_1'] );
			unset( $fields['billing']['billing_address_2'] );
			unset( $fields['billing']['billing_phone'] );
			unset( $fields['billing']['billing_country'] );
			unset( $fields['billing']['billing_city'] );
			unset( $fields['billing']['billing_postcode'] );
			unset( $fields['billing']['billing_state'] );
			break;
		}

	}
	return $fields;

}

function wooccm_remove_fields_filter_shipping( $fields ) {

	global $woocommerce;

	// Check if the cart is not empty
	if( empty( $woocommerce->cart->cart_contents ) )
		return $fields;

	$options = get_option( 'wccs_settings' );

	foreach( $woocommerce->cart->cart_contents as $key => $values ) {

		$multiCategoriesx = ( isset( $options['checkness']['productssave'] ) ? $options['checkness']['productssave'] : '' );
		$multiCategoriesArrayx = explode(',',$multiCategoriesx);
		$_product = $values['data'];

		if( ($woocommerce->cart->cart_contents_count > 1) && ($_product->needs_shipping()) ){
			remove_filter('woocommerce_checkout_fields','wooccm_remove_fields_filter',15);
			break;
		}

	}
	return $fields;

}
?>