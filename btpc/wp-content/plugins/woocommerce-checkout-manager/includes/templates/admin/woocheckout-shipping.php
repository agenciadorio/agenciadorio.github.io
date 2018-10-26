<table class="widefat shipping-wccs-table shipping-semi" style="display:none;" border="1" name="shipping_table">
	<thead>

		<tr>
			<th style="width:3%;" class="shipping-wccs-order" title="<?php esc_attr_e( 'Change the order of Checkout fields', 'woocommerce-checkout-manager' ); ?>">#</th>

			<?php require( WOOCCM_PATH.'includes/templates/admin/woocheckout-shipping-thead.php' ); ?>

			<th width="1%" scope="col" title="<?php esc_attr_e( 'Remove button', 'woocommerce-checkout-manager' ); ?>"><strong>X</strong><!-- remove --></th>
		</tr>

	</thead>
	<tbody>

<?php
	if( isset ( $options2['shipping_buttons'] ) ) {
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
		for( $i = 0; $i < count( $options2['shipping_buttons'] ); $i++ ) {

			if( !isset( $options2['shipping_buttons'][$i] ) )
				break;
?>

		<tr valign="top" id="wccs-shipping-id-<?php echo $i; ?>" class="shipping-wccs-row">

			<td style="display:none;" class="shipping-wccs-order-hidden">
				<input type="hidden" name="wccs_settings2[shipping_buttons][<?php echo $i; ?>][order]" value="<?php echo (empty( $options2['shipping_buttons'][$i]['order'])) ? $i :  $options2['shipping_buttons'][$i]['order']; ?>" />
			</td>
			<td class="shipping-wccs-order" title="<?php esc_attr_e( 'Drag-and-drop this Checkout field to adjust its ordering', 'woocommerce-checkout-manager' ); ?>"><?php echo $i+1; ?></td>

				<?php require(WOOCCM_PATH.'includes/templates/admin/woocheckout-shipping-tbody.php'); ?>

<?php if( in_array( $options2['shipping_buttons'][$i]['cow'],$shipping ) ) { ?>
			<td style="text-align:center;">
				<input name="wccs_settings2[shipping_buttons][<?php echo $i; ?>][disabled]" type="checkbox" value="true" <?php if (  !empty ($options2['shipping_buttons'][$i]['disabled'])) echo "checked='checked'"; ?> />
			</td>
<?php } else { ?>
			<td class="shipping-wccs-remove"><a class="shipping-wccs-remove-button" href="javascript:;" title="<?php esc_attr_e( 'Delete this Checkout field' , 'woocommerce-checkout-manager' ); ?>">&times;</a></td>
<?php } ?>

		</tr>
		<!-- #wccs-shipping-id-<?php echo $i; ?> .shipping-wccs-row -->

<?php
		}
	}
?>

<?php
	$i = 999;
?>

		<tr valign="top" id="wccs-shipping-id-<?php echo $i; ?>" class="shipping-wccs-clone" >

			<td style="display:none;" class="shipping-wccs-order-hidden" >
				<input type="hidden" name="wccs_settings2[shipping_buttons][<?php echo $i; ?>][order]" value="<?php echo $i; ?>" />
			</td>

			<td class="shipping-wccs-order" title="<?php esc_attr_e( 'Drag-and-drop this Checkout field to adjust its ordering', 'woocommerce-checkout-manager' ); ?>"><?php echo $i; ?></td>

			<?php require( WOOCCM_PATH.'includes/templates/admin/woocheckout-shipping-clone.php' ); ?>

			<td class="shipping-wccs-remove"><a class="shipping-wccs-remove-button" href="javascript:;" title="<?php esc_attr_e( 'Delete this Checkout field' , 'woocommerce-checkout-manager' ); ?>">&times;</a></td>

		</tr>
		<!-- #wccs-shipping-id-<?php echo $i; ?> .shipping-wccs-clone -->
	</tbody>
</table>
<!-- .widefat -->

<div class="shipping-wccs-table-footer shipping-semi" style="display:none;">
	<a href="javascript:;" id="shipping-wccs-add-button" class="button-secondary"><?php _e( '+ Add New Field' , 'woocommerce-checkout-manager' ); ?></a>
</div>
<!-- .shipping-wccs-table-footer -->