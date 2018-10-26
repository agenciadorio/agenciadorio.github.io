<table class="widefat wccs-table additional-semi" style="display:none;" border="1" name="additional_table">
	<thead>

		<tr>
			<th style="width:3%;" class="wccs-order" title="<?php esc_attr_e( 'Change the order of Checkout fields' , 'woocommerce-checkout-manager' ); ?>">#</th>

			<?php require( WOOCCM_PATH.'includes/templates/admin/woocheckout-additional-thead.php' ); ?>

			<th width="1%" scope="col" title="<?php esc_attr_e( 'Remove button', 'woocommerce-checkout-manager' ); ?>"><strong>X</strong><!-- remove --></th>
		</tr>

	</thead>
	<tbody>

<?php
	if( isset( $options['buttons'] ) ) {
		for( $i = 0; $i < count( $options['buttons'] ); $i++ ) {

			if( !isset( $options['buttons'][$i] ) )
				break;
?>

		<tr valign="top" id="wccs-additional-id-<?php echo $i; ?>" class="wccs-row">

			<td style="display:none;" class="wccs-order-hidden" >
				<input type="hidden" name="wccs_settings[buttons][<?php echo $i; ?>][order]" value="<?php echo (empty( $options['buttons'][$i]['order'])) ? $i :  $options['buttons'][$i]['order']; ?>" />
			</td>
			<td class="wccs-order" title="<?php esc_attr_e( 'Drag-and-drop this Checkout field to adjust its ordering', 'woocommerce-checkout-manager' ); ?>"><?php echo $i+1; ?></td>

			<?php require( WOOCCM_PATH.'includes/templates/admin/woocheckout-additional-tbody.php' ); ?>

			<td class="wccs-remove"><a class="wccs-remove-button" href="javascript:;" title="<?php esc_attr_e( 'Delete this Checkout field' , 'woocommerce-checkout-manager' ); ?>">&times;</a></td>

		</tr>
		<!-- #wccs-additional-id-<?php echo $i; ?> .wccs-row -->

<?php
		}
	}
?>

<?php
	$i = 999;
?>

		<tr valign="top" id="wccs-additional-id-<?php echo $i; ?>" class="wccs-clone">

			<td style="display:none;" class="wccs-order-hidden">
				<input type="hidden" name="wccs_settings[buttons][<?php echo $i; ?>][order]" value="<?php echo $i; ?>" />
			</td>

			<td class="wccs-order" title="<?php esc_attr_e( 'Drag-and-drop this Checkout field to adjust its ordering' , 'woocommerce-checkout-manager' ); ?>"><?php echo $i; ?></td>

			<?php require( WOOCCM_PATH.'includes/templates/admin/woocheckout-additional-clone.php' ); ?>

			<td class="wccs-remove"><a class="wccs-remove-button" href="javascript:;" title="<?php esc_attr_e( 'Delete this Checkout field' , 'woocommerce-checkout-manager' ); ?>">&times;</a></td>

		</tr>
		<!-- #wccs-additional-id-<?php echo $i; ?> .wccs-clone -->
	</tbody>
</table>
<!-- .widefat -->

<div class="wccs-table-footer additional-semi" style="display:none;">
	<a href="javascript:;" id="wccs-add-button" class="button-secondary"><?php _e( '+ Add New Field' , 'woocommerce-checkout-manager' ); ?></a>
</div>
<!-- .wccs-table-footer -->