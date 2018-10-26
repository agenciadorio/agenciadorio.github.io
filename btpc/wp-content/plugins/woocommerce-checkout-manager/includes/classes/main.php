<?php 
/**
 * WooCommerce Checkout Manager
 *
 * MAIN
 *
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if( wooccm_validator_changename() ) {

	function wooccm_before_checkout() {

		$options = get_option( 'wccs_settings' );
		$buttons = ( isset( $options['buttons'] ) ? $options['buttons'] : false );

		// Check if the buttons exist
		if( empty( $buttons ) )
			return;

		foreach( $buttons as $btn ) {
			$label = ( isset( $btn['label'] ) ) ? $btn['label'] : '';
			ob_start();
		}

	}

	function wooccm_after_checkout() {

		$options = get_option( 'wccs_settings' );
		$buttons = ( isset( $options['buttons'] ) ? $options['buttons'] : false );

		// Check if the buttons exist
		if( empty( $buttons ) )
			return;

		foreach( $buttons as $btn ) {
			if( $btn['type'] == 'changename' ) {
				$content = ob_get_clean();
				echo str_replace( $btn['changenamep'], $btn['changename'], $content );
			}
		}

	}

}

// -----------------------------------------------------------
// -----------------------------------------------------------
// -----------------------------------------------------------
// -----------------------------------------------------------

function wooccm_validator_changename() {

	$options = get_option( 'wccs_settings' );
	$buttons = ( isset( $options['buttons'] ) ? $options['buttons'] : false );

	if( !empty( $buttons ) ) {
		foreach( $buttons as $btn ) {
			if (!empty($btn['type']) ) {
				if ( $btn['type'] == 'changename' && !empty($btn['label']) ){
					return true;
				}
			}
		}
	}

}

if( wooccm_validator_changename() ) {

	// @mod - This function isn't referenced anywhere
	function wooccm_string_replacer( $order ) {

		$options = get_option( 'wccs_settings' );
		$buttons = ( isset( $options['buttons'] ) ? $options['buttons'] : false );
?>
<header>
	<h2><?php _e( 'Customer details', 'woocommerce-checkout-manager' ); ?></h2>
</header>

<dl class="customer_details">
<?php 
		if( $order->billing_email ) echo '<dt>'.__( 'Email:', 'woocommerce-checkout-manager' ).'</dt><dd>'.$order->billing_email.'</dd>';
		if( $order->billing_phone ) echo '<dt>'.__( 'Telephone:', 'woocommerce-checkout-manager' ).'</dt><dd>'.$order->billing_phone.'</dd>';
?>
</dl>

<?php if( get_option('woocommerce_ship_to_billing_address_only') == 'no' ) { ?>

<div class="col2-set addresses">

	<div class="col-1">

<?php } ?>

		<header class="title">
			<h3><?php _e( 'Billing Address', 'woocommerce-checkout-manager' ); ?></h3>
		</header>

		<address>
			<p><?php if (!$order->get_formatted_billing_address()) _e( 'N/A', 'woocommerce-checkout-manager' ); else echo $order->get_formatted_billing_address(); ?></p>
		</address>

<?php if( get_option('woocommerce_ship_to_billing_address_only') == 'no' ) { ?>

	</div>
	<!-- .col-1 -->

	<div class="col-2">

		<header class="title">
			<h3><?php _e( 'Shipping Address', 'woocommerce-checkout-manager' ); ?></h3>
		</header>

		<address>
			<p><?php if (!$order->get_formatted_shipping_address()) _e( 'N/A', 'woocommerce-checkout-manager' ); else echo $order->get_formatted_shipping_address(); ?></p>
		</address>

	</div>
	<!-- .col-2 -->

</div>
<!-- .col2-set -->

<?php } ?>

<div class="clear"></div>

<script type="text/javascript">
	var array = [];
<?php foreach( $buttons as $btn ) { ?>
	array.push("<?php echo $btn['changenamep']; ?>" , "<?php echo $btn['changename']; ?>")
<?php } ?>
	b(array);
	function b(array){
		for(var i = 0; i<(array.length-1); i=i+2) {
			document.body.innerHTML= document.body.innerHTML.replace(array[i],array[i+1])
		}
	}
</script>

<?php
	}

}

if( wooccm_enable_auto_complete() ) {

	function wooccm_retain_field_values() {

		$options = get_option( 'wccs_settings' );
		$options2 = get_option( 'wccs_settings2' );
		$options3 = get_option( 'wccs_settings3' );

		if( is_checkout() == false )
			return;

		$saved = WC()->session->get('wooccm_retain', array() );
?>

<script type="text/javascript">

	jQuery(document).ready(function() {
		window.onload = function() {

<?php 
		if( !empty( $options['buttons'] ) ) {
			foreach ( $options['buttons'] as $btn ) {
				if(
					$btn['type'] !== 'wooccmupload' && 
					$btn['type'] !== 'changename' && 
					$btn['type'] !== 'heading' && 
					$btn['disabled'] !== 'true' && 
					empty( $btn['tax_remove'] ) && 
					empty( $btn['add_amount'] ) 
				) {
?>
			document.forms['checkout'].elements['<?php echo $btn['cow']; ?>'].value = "<?php echo $saved[$btn['cow']]; ?>";
<?php
				}
			}
		}

		if( !is_user_logged_in() ) {

			if( WC()->cart->needs_shipping_address() === true && sanitize_text_field( $_POST['ship_to_different_address'] ) == 1 ) {

				if( !empty( $options2['shipping_buttons'] ) ) {
					foreach ( $options2['shipping_buttons'] as $btn ) {
						if(
							$btn['type'] !== 'wooccmupload' && 
							$btn['type'] !== 'changename' && 
							$btn['type'] !== 'heading' && 
							$btn['disabled'] !== 'true' && 
							empty( $btn['tax_remove'] ) && 
							empty( $btn['add_amount'] )
						) {
?>
			document.forms['checkout'].elements['shipping_<?php echo $btn['cow']; ?>'].value = "<?php echo $saved[sprintf( 'shipping_%s', $btn['cow'] )]; ?>";
<?php
						}
					}
				}

			}

			if( !empty( $options3['billing_buttons'] ) ) {
				foreach( $options3['billing_buttons'] as $btn ) {
					if(
						$btn['type'] !== 'wooccmupload' && 
						$btn['type'] !== 'changename' && 
						$btn['type'] !== 'heading' && 
						$btn['disabled'] !== 'true' && 
						empty( $btn['tax_remove'] ) && 
						empty( $btn['add_amount'] )
					) {
?>
			document.forms['checkout'].elements['billing_<?php echo $btn['cow']; ?>'].value = "<?php echo $saved[sprintf( 'billing_%s', $btn['cow'] )]; ?>";
<?php
					}
				}
			}

		}
?>

		} 
	}); 
</script>

<script type="text/javascript">

	jQuery(document).ready(function() {
		jQuery('body').change(function() {

			var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
			data = { action: 'retain_val_wccs',

<?php
		if( !empty( $options['buttons'] ) ) {
			foreach( $options['buttons'] as $btn ) {
				if(
					$btn['type'] !== 'wooccmupload' && 
					$btn['type'] !== 'changename' && 
					$btn['type'] !== 'heading' && 
					empty( $btn['tax_remove'] ) && 
					empty( $btn['add_amount'] )
				) {
?>
			<?php echo $btn['cow']; ?>: jQuery("#<?php echo $btn['cow']; ?>").val(),
<?php
				}
			}
		}

		if( !is_user_logged_in() ) {

			if( WC()->cart->needs_shipping_address() === true && sanitize_text_field( $_POST['ship_to_different_address'] ) == 1 ) {

				if( !empty( $options2['shipping_buttons'] ) ) {
					foreach ( $options2['shipping_buttons'] as $btn ) {
						if(
							$btn['type'] !== 'wooccmupload' && 
							$btn['type'] !== 'changename' && 
							$btn['type'] !== 'heading' && 
							empty( $btn['tax_remove'] ) && 
							empty( $btn['add_amount'] )
						) {
?>
			shipping_<?php echo $btn['cow']; ?>: jQuery("shipping_<?php echo $btn['cow']; ?>").val(),
<?php
						}
					}
				}

			}

			if( !empty( $options3['billing_buttons'] ) ) {
				foreach( $options3['billing_buttons'] as $btn ) {
					if(
						$btn['type'] !== 'wooccmupload' && 
						$btn['type'] !== 'changename' && 
						$btn['type'] !== 'heading' && 
						empty( $btn['tax_remove'] ) && 
						empty( $btn['add_amount'] )
					) {
?>
			billing_<?php echo $btn['cow']; ?>: jQuery("#billing_<?php echo $btn['cow']; ?>").val(),
<?php
					}
				}
			}

		}
?>
			};

			jQuery.post(ajaxurl, data, function(response) { });
			return false;

		});
	});

</script>

<?php 

	}

	function wooccm_retain_val_callback() {

		global $wpdb;

		$options = get_option( 'wccs_settings' );
		$options2 = get_option( 'wccs_settings2' );
		$options3 = get_option( 'wccs_settings3' );

		if( !empty( $options['buttons'] ) ) {
			foreach( $options['buttons'] as $btn ) {
				if(
					$btn['type'] !== 'wooccmupload' && 
					$btn['type'] !== 'changename' && 
					$btn['type'] !== 'heading' && 
					empty( $btn['tax_remove'] ) && 
					empty( $btn['add_amount'] )
				) {
					if( !empty( $_POST[$btn['cow']] ) ) {
						$saved[$btn['cow']] = sanitize_text_field( $_POST[$btn['cow']] );
					}
				}
			}
		}

		if( WC()->cart->needs_shipping_address() === true && sanitize_text_field( $_POST['ship_to_different_address'] ) == 1 ) {
			if( !empty( $options2['shipping_buttons'] ) ) {
				foreach( $options2['shipping_buttons'] as $btn ) {
					if(
						$btn['type'] !== 'wooccmupload' && 
						$btn['type'] !== 'changename' && 
						$btn['type'] !== 'heading' && 
						empty( $btn['tax_remove'] ) && 
						empty( $btn['add_amount'] )
					) {
						if( !empty( $_POST[sprintf( 'shipping_%s', $btn['cow'] )] ) ) {
							$saved[sprintf( 'shipping_%s', $btn['cow'] )] = sanitize_text_field( $_POST[sprintf( 'shipping_%s', $btn['cow'] )] );
						}
					}
				}
			}
		}

		if( !empty( $options3['billing_buttons'] ) ) {
			foreach( $options3['billing_buttons'] as $btn ) {
				if(
					$btn['type'] !== 'wooccmupload' && 
					$btn['type'] !== 'changename' && 
					$btn['type'] !== 'heading' && 
					empty( $btn['tax_remove'] ) && 
					empty( $btn['add_amount'] )
				) {
					if( !empty( $_POST[sprintf( 'billing_%s', $btn['cow'] )] ) ) {
						$saved[sprintf( 'billing_%s', $btn['cow'] )] = sanitize_text_field( $_POST[sprintf( 'billing_%s', $btn['cow'] )] );
					}
				}
			}
		}

		WC()->session->set('wooccm_retain', $saved );

		die(); 

	}
	add_action( 'wp_ajax_retain_val_wccs', 'wooccm_retain_val_callback' );
	add_action('wp_ajax_nopriv_retain_val_wccs', 'wooccm_retain_val_callback');

}

function wooccm_enable_auto_complete() {

	$options = get_option( 'wccs_settings' );

	if( !empty( $options['checkness']['retainval'] ) ) {
		return true;
	} else {
		return false;
	}

}

function wooccm_remove_tax_wccm() {

	$saved['wooccm_addamount453userf'] = sanitize_text_field( $_POST['add_amount_faj'] );
	$saved['wooccm_tax_save_method'] = sanitize_text_field( $_POST['tax_remove_aj'] );
	$saved['wooccm_addamount453user'] = sanitize_text_field( $_POST['add_amount_aj'] );
	WC()->session->set('wooccm_retain', $saved );

	die();

}
add_action( 'wp_ajax_remove_tax_wccm', 'wooccm_remove_tax_wccm' );
add_action( 'wp_ajax_nopriv_remove_tax_wccm', 'wooccm_remove_tax_wccm' );

function wooccm_custom_user_charge_man( $cart ) {

	global $woocommerce, $wpdb;

	$options = get_option( 'wccs_settings' );
	$options2 = get_option( 'wccs_settings2' );
	$options3 = get_option( 'wccs_settings3' );

	$saved = WC()->session->get('wooccm_retain', array() );

	if( !empty( $options['buttons'] ) ) {
		foreach( $options['buttons'] as $btn ) {

			if( !empty( $btn['add_amount'] ) && !empty( $btn['add_amount_field'] ) && !empty( $btn['label'] ) && !empty( $btn['fee_name'] ) ) {
				if( $saved['wooccm_addamount453user'] == $btn['chosen_valt'] ) {        
					$woocommerce->cart->add_fee( $btn['fee_name'], $btn['add_amount_field'], false, '' );
				}
			}

			if( !empty( $btn['add_amount'] ) && empty( $btn['add_amount_field'] ) && !empty( $btn['label'] ) && !empty( $btn['fee_name'] ) ) {
				if( !empty($saved['wooccm_addamount453userf']) && is_numeric($saved['wooccm_addamount453userf']) ) {
					$woocommerce->cart->add_fee( $btn['fee_name'], $saved['wooccm_addamount453userf'], false, '' );
				}
			}

		}
	}

	if( !empty( $options3['billing_buttons'] ) ) {
		foreach( $options3['billing_buttons'] as $btn ) {

			if( !empty( $btn['add_amount'] ) && !empty( $btn['add_amount_field'] ) && !empty( $btn['label'] ) && !empty( $btn['fee_name'] ) ) {
				if( $saved['wooccm_addamount453user'] == $btn['chosen_valt'] ) {        
					$woocommerce->cart->add_fee( $btn['fee_name'], $btn['add_amount_field'], false, '' );
				}
			}

			if( !empty( $btn['add_amount'] ) && empty( $btn['add_amount_field'] ) && !empty( $btn['label'] ) && !empty( $btn['fee_name'] ) ) {	                
				if( !empty($saved['wooccm_addamount453userf']) && is_numeric($saved['wooccm_addamount453userf']) ) {
					$woocommerce->cart->add_fee( $btn['fee_name'], $saved['wooccm_addamount453userf'], false, '' );
				}
			}

		}
	}

	if( !empty( $options2['shipping_buttons'] ) ) {
		foreach( $options2['shipping_buttons'] as $btn ) {

			if( !empty( $btn['add_amount'] ) && !empty( $btn['add_amount_field'] ) && !empty( $btn['label'] ) && !empty( $btn['fee_name'] ) ) {
				if( $saved['wooccm_addamount453user'] == $btn['chosen_valt'] ) {        
					$woocommerce->cart->add_fee( $btn['fee_name'], $btn['add_amount_field'], false, '' );
				}
			}

			if( !empty( $btn['add_amount'] ) && empty( $btn['add_amount_field'] ) && !empty( $btn['label'] ) && !empty( $btn['fee_name'] ) ) {	                
				if( !empty($saved['wooccm_addamount453userf']) && is_numeric($saved['wooccm_addamount453userf']) ) {
					$woocommerce->cart->add_fee( $btn['fee_name'], $saved['wooccm_addamount453userf'], false, '' );
				}
			}

		}
	}

}
add_action( 'woocommerce_cart_calculate_fees','wooccm_custom_user_charge_man' );

function wooccm_remove_tax_for_exempt( $cart ) {

	global $woocommerce, $wpdb;

	$options = get_option( 'wccs_settings' );
	$options2 = get_option( 'wccs_settings2' );
	$options3 = get_option( 'wccs_settings3' );

	$saved = WC()->session->get('wooccm_retain', array() );

	if( !empty( $options['buttons'] ) ) {
		foreach( $options['buttons'] as $btn ) {
			if( !empty( $btn['tax_remove'] ) ) {
				if( $saved['wooccm_tax_save_method'] == $btn['chosen_valt'] ) {
					$cart->remove_taxes();
				}
			}
		}
	}

	if( !empty( $options3['billing_buttons'] ) ) {
		foreach( $options3['billing_buttons'] as $btn ) {
			if( !empty( $btn['tax_remove'] ) ) {
				if( $saved['wooccm_tax_save_method'] == $btn['chosen_valt'] ) {
					$cart->remove_taxes();
				}
			}
		}
	}

	if( !empty( $options2['shipping_buttons'] ) ) {
		foreach( $options2['shipping_buttons'] as $btn ) {
			if( !empty( $btn['tax_remove'] ) ) {
				if( $saved['wooccm_tax_save_method'] == $btn['chosen_valt'] ) {
					$cart->remove_taxes();
				}
			}
		}
	}

	return $cart;

}
add_action( 'woocommerce_calculate_totals', 'wooccm_remove_tax_for_exempt' );

function wooccm_state_default_switch() {

	$options = get_option( 'wccs_settings' );

	if( !empty( $options['checkness']['per_state'] ) && !empty( $options['checkness']['per_state_check'] ) ) {
		return $options['checkness']['per_state']; 
	}

}

function wooccm_woocommerce_delivery_notes_compat( $fields, $order ) {

	if( version_compare( wooccm_get_woo_version(), '2.7', '>=' ) ) {
		$order_id = ( method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id );
	} else {
		$order_id = ( isset( $order->id ) ? $order->id : 0 );
	}

	$new_fields = array();

	$shipping = array(
		'country',
		'first_name',
		'last_name',
		'company',
		'address_1',
		'address_2',
		'city',
		'state',
		'postcode'
	);
	$billing = array(
		'country',
		'first_name',
		'last_name',
		'company',
		'address_1',
		'address_2',
		'city',
		'state',
		'postcode',
		'email',
		'phone'
	);

	$names = array(
		'billing',
		'shipping'
	);
	$inc = 3;
	foreach( $names as $name ) {

		$array = ( $name == 'billing' ) ? $billing : $shipping;

		$options = get_option( 'wccs_settings'.$inc );
		if( !empty( $options[$name.'_buttons'] ) ) {
			foreach( $options[$name.'_buttons'] as $btn ) {

				if( !in_array( $btn['cow'], $array ) ) {

					if(
						get_post_meta( $order_id, sprintf( '_%s_%s', $name, $btn['cow'] ), true ) && 
						$btn['type'] !== 'wooccmupload' && 
						$btn['type'] !== 'heading' && 
						(
							$btn['type'] !== 'multiselect' || $btn['type'] !== 'multicheckbox'
						)
					) {
						$new_fields[sprintf( '_%s_%s', $name, $btn['cow'] )] = array(
							'label' => wooccm_wpml_string( $btn['label'] ),
							'value' => get_post_meta( $order_id, sprintf( '_%s_%s', $name, $btn['cow'] ), true )
						);
					}

					if(
						get_post_meta( $order_id, sprintf( '_%s_%s', $name, $btn['cow'] ), true ) && 
						$btn['type'] !== 'wooccmupload' && 
						$btn['type'] !== 'heading' && 
						(
							$btn['type'] == 'multiselect' || $btn['type'] == 'multicheckbox'
						)
					) {
						$new_fields[sprintf( '_%s_%s', $name, $btn['cow'] )]['label'] = wooccm_wpml_string( $btn['label'] );
						$new_fields[sprintf( '_%s_%s', $name, $btn['cow'] )]['value'] = '';
						$value = get_post_meta( $order_id , sprintf( '_%s_%s', $name, $btn['cow'] ), true );
						$strings = maybe_unserialize( $value );
						if( !empty( $strings ) ) {
							if( is_array( $strings ) ) {
								$iww = 0;
								$len = count( $strings );
								foreach( $strings as $key ) {
									if( $iww == $len - 1 ) {
										$new_fields[sprintf( '_%s_%s', $name, $btn['cow'] )]['value'] .= $key;
									} else {
										$new_fields[sprintf( '_%s_%s', $name, $btn['cow'] )]['value'] .= $key.', ';
									}
									$iww++;
								}
							} else {
								echo $strings;
							}
						} else {
							echo '-';
						}

					} elseif( $btn['type'] == 'wooccmupload' ) {
						$info = explode( "||",get_post_meta( $order_id, sprintf( '_%s_%s', $name, $btn['cow'] ), true ) );
						$btn['label'] = ( !empty( $btn['force_title2'] ) ? $btn['force_title2'] : $btn['label'] );
						$new_fields[sprintf( '_%s_%s', $name, $btn['cow'] )] = array(
							'label' => wooccm_wpml_string( trim( $btn['label'] ) ),
							'value' => $info[0]
						);
					}

				}

			}
		}
		$inc--;

	}

	$options = get_option( 'wccs_settings' );
	if( !empty( $options['buttons'] ) ) {
		foreach( $options['buttons'] as $btn ) {

			if(
				get_post_meta( $order_id, $btn['cow'], true ) && 
				$btn['type'] !== 'wooccmupload' && 
				$btn['type'] !== 'heading' && 
				(
					$btn['type'] !== 'multiselect' || $btn['type'] !== 'multicheckbox'
				)
			) {
				$new_fields[$btn['cow']] = array( 
					'label' => wooccm_wpml_string( $btn['label'] ),
					'value' => get_post_meta( $order_id, $btn['cow'], true )
				);
			}

			if(
				get_post_meta( $order_id, $btn['cow'], true ) && 
				$btn['type'] !== 'wooccmupload' && 
				$btn['type'] !== 'heading' && 
				(
					$btn['type'] == 'multiselect' || $btn['type'] == 'multicheckbox'
				)
			) {
				$new_fields[$btn['cow']]['label'] = wooccm_wpml_string( $btn['label'] );
				$new_fields[$btn['cow']]['value'] = '';
				$value = get_post_meta( $order_id , $btn['cow'], true );
				$strings = maybe_unserialize( $value );
				if( !empty( $strings ) ) {
					if( is_array( $strings ) ) {
						$iww = 0;
						$len = count( $strings );
						foreach( $strings as $key ) {
							if( $iww == $len - 1) {
								$new_fields[$btn['cow']]['value'] .= $key;
							} else {
								$new_fields[$btn['cow']]['value'] .= $key.', ';
							}
							$iww++;
						}
					} else {
						echo $strings;
					}
				} else {
					echo '-';
				}
			}

			if( $btn['type'] == 'wooccmupload' ){
				$info = get_post_meta( $order_id, $btn['cow'], true );
				$btn['label'] = ( !empty( $btn['force_title2'] ) ? $btn['force_title2'] : $btn['label'] );
				$new_fields[$btn['cow']] = array( 
					'label' => wooccm_wpml_string( trim( $btn['label'] ) ),
					'value' => $info[0]
				);
			}
		}
	}

	return array_merge( $fields, $new_fields );

}

function wooccm_order_notes( $fields ) {

	$options = get_option( 'wccs_settings' );

	if( !empty($options['checkness']['noteslabel']) ) {
		$fields['order']['order_comments']['label'] = $options['checkness']['noteslabel'];
	}
	if( !empty($options['checkness']['notesplaceholder']) ) {
		$fields['order']['order_comments']['placeholder'] = $options['checkness']['notesplaceholder'];
	}
	if( !empty($options['checkness']['notesenable']) ) {
		unset($fields['order']['order_comments']);
	}
	return $fields;

}

function woooccm_restrict_manage_posts() {

	$options = get_option( 'wccs_settings' );
	$options2 = get_option( 'wccs_settings2' );
	$options3 = get_option( 'wccs_settings3' );

	$billing = array(
		'country',
		'first_name',
		'last_name',
		'company',
		'address_1',
		'address_2',
		'city',
		'state',
		'postcode',
		'email',
		'phone'
	);
	$shipping = array(
		'country',
		'first_name',
		'last_name',
		'company',
		'address_1',
		'address_2',
		'city',
		'state',
		'postcode'
	);

	$post_type = 'shop_order';
	if( get_current_screen()->post_type == $post_type ) {

		$values = array();
		if( !empty( $options['buttons'] ) ) {
			foreach( $options['buttons'] as $name ) {
				$values[$name['label']] = $name['cow'];
			}
		}
		if( !empty( $values ) ) {
			array_unique( $values );
		}

		$values2 = array();
		if( !empty( $options2['shipping_buttons'] ) ) {
			foreach( $options2['shipping_buttons'] as $name ) {
				if( !in_array( $name['cow'], $shipping ) ) {
					$values2['Shipping ' . $name['label']] = sprintf( '_shipping_%s', $name['cow'] );
				}
			}
		}
		if( !empty( $values2 ) ) {
			array_unique( $values2 );
		}

		$values3 = array();
		if( !empty( $options3['billing_buttons'] ) ) {
			foreach( $options3['billing_buttons'] as $name ) {
				if( !in_array( $name['cow'], $billing ) ) {
					$values3['Billing ' . $name['label']] = sprintf( '_billing_%s', $name['cow'] );
				}
			}
		}
		if( !empty( $values3 ) ) {
			array_unique( $values3 );
		}

		if( !empty($values) && !empty($values2) && !empty($values3) ) {
			$values = array_merge($values, $values2);
			$values = array_merge($values, $values3);
		} elseif( !empty($values) && !empty($values2) && empty($values3) ) {
			$values = array_merge($values, $values2);
		} elseif( !empty($values) && empty($values2) && !empty($values3) ) {
			$values = array_merge($values, $values3);
		} elseif( empty($values) && !empty($values2) && !empty($values3) ) {
			$values = array_merge($values2, $values3);
		} elseif( empty($values) && empty($values2) && !empty($values3) ) {
			$values = $values3;
		} elseif( empty($values) && !empty($values2) && empty($values3) ) {
			$values = $values2;
		} elseif( !empty($values) && empty($values2) && empty($values3) ) {
			$values = $values;
		}
?>
<select name="wooccm_abbreviation">
<?php if( empty($values) && empty($values2) && empty($values3) ) { ?>
	<option value=""><?php _e('No Added Fields', 'woocommerce-checkout-manager'); ?></option>
<?php } else { ?>
	<option value=""><?php _e('Field Name', 'woocommerce-checkout-manager'); ?></option>
<?php } ?>
<?php
		$current_v = ( isset( $_GET['wooccm_abbreviation'] ) ? sanitize_text_field( $_GET['wooccm_abbreviation'] ) : '' );
		if( !empty( $values ) ) {
			foreach( $values as $label => $value ) {
				printf(
					'<option value="%s"%s>%s</option>',
					$value,
					$value == $current_v? ' selected="selected"':'',
					$label
				);
			}
		}
?>
</select>
<?php

	}

}

function wooccm_query_list( $query ) {

	global $pagenow;

	$wooccm_abbreviation = ( isset( $_GET['wooccm_abbreviation'] ) ? sanitize_text_field( $_GET['wooccm_abbreviation'] ) : '' );
	if( is_admin() && $pagenow == 'edit.php' && $wooccm_abbreviation != '' ) {
		$query->query_vars[ 'meta_key' ] = $wooccm_abbreviation;
	}

}

// ========================================
// Remove conditional notices
// ========================================

function wooccm_remove_notices_conditional( $posted ) {

	$notice = WC()->session->get( 'wc_notices' );

	$shipping = array(
		'country', 
		'first_name', 
		'last_name', 
		'company', 
		'address_1', 
		'address_2', 
		'city', 
		'state', 
		'postcode'
	);
	$billing = array(
		'country', 
		'first_name',
		'last_name', 
		'company', 
		'address_1', 
		'address_2', 
		'city', 
		'state', 
		'postcode', 
		'email', 
		'phone' 
	);

	$options = get_option( 'wccs_settings' );
	$buttons = ( isset( $options['buttons'] ) ? $options['buttons'] : false );

	$names = array(
		'billing',
		'shipping'
	);
	$inc = 3;
	foreach( $names as $name ) {

		$array = ( $name == 'billing' ) ? $billing : $shipping;

		$options2 = get_option( 'wccs_settings'.$inc );
		if( !empty( $options2[$name.'_buttons'] ) ) {
			foreach( $options2[$name.'_buttons'] as $btn ) {

				if(
					!empty( $btn['chosen_valt'] ) && 
					!empty( $btn['conditional_parent_use'] ) && 
					!empty( $btn['conditional_tie'] ) && 
					$btn['type'] !== 'changename' && 
					$btn['type'] !== 'heading' && 
					!empty( $btn['conditional_parent'] )
				) {
					if( !empty( $_POST[$btn['cow']] ) ) {
						foreach( $buttons as $btn2 ) {

							if(
								!empty( $btn2['chosen_valt'] ) && 
								!empty( $btn2['conditional_parent_use'] ) && 
								!empty( $btn2['conditional_tie'] ) && 
								$btn2['type'] !== 'changename' && 
								$btn2['type'] !== 'heading' && 
								empty( $btn2['conditional_parent'] )
							) {
								if( sanitize_text_field( $_POST[$btn['cow']] ) != $btn2['chosen_valt'] ) {
									if( empty( $_POST[$btn2['cow']] ) ) {
										foreach( $notice['error'] as $position => $value ) {

											if( strip_tags( $value ) == sprintf( __( '%s is a required field.', 'woocommerce' ), wooccm_wpml_string( $btn2['label'] ) ) ) {
												unset( $notice['error'][$position] );
											}

										}
									}
								} 
							}
						}

					} else {
						foreach( $notice['error'] as $position => $value ) {

							if( strip_tags( $value ) == sprintf( __( '%s is a required field.', 'woocommerce' ), wooccm_wpml_string( $btn2['label'] ) ) ) {
								unset( $notice['error'][$position] );
							}

						}
					}
				}

			}
		}
		$inc--;

	}

	$options = get_option( 'wccs_settings' );

	global $woocommerce;

	if( !empty( $options['buttons'] ) ) {
		foreach( $options['buttons'] as $btn ) {

			if( !empty($btn['chosen_valt']) && !empty($btn['conditional_parent_use']) && !empty($btn['conditional_tie']) && $btn['type'] !== 'changename' && ($btn['type'] !== 'heading') && !empty($btn['conditional_parent']) ) {

				if( !empty( $_POST[$btn['cow']] ) ) {

					foreach( $options['buttons'] as $btn2 ) {

						if( !empty($btn2['chosen_valt']) && !empty($btn2['conditional_parent_use']) && !empty($btn2['conditional_tie']) && $btn2['type'] !== 'changename' && ($btn2['type'] !== 'heading') && empty($btn2['conditional_parent']) ) {
							if( sanitize_text_field( $_POST[$btn['cow']] ) != $btn2['chosen_valt'] ) {
								if( empty( $_POST[$btn2['cow']] ) ) {
									foreach( $notice['error'] as $position => $value ) {

										if( strip_tags($value) == sprintf( __( '%s is a required field.', 'woocommerce' ), wooccm_wpml_string( $btn2['label'] ) ) ) {
											unset( $notice['error'][$position] );
										}

									}
								}
							} 
						}

					}

				} else {

					foreach( $notice['error'] as $position => $value ) {

						if( strip_tags($value) == sprintf( __( '%s is a required field.', 'woocommerce' ), wooccm_wpml_string( $btn2['label'] ) ) ) {
							unset( $notice['error'][$position] );
						}

					}
				}

			}

		}
	}

	WC()->session->set( 'wc_notices', $notice );

}
add_action( 'woocommerce_after_checkout_validation', 'wooccm_remove_notices_conditional' );
?>