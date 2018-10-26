<div class="widefat general-semi address_fields" border="1" style="display:none;">

	<div class="section">

		<h3 class="heading"><?php _e( 'Disable Billing Address fields for certain products', 'woocommerce-checkout-manager' ); ?></h3>
		<div class="option">
			<input type="text" name="wccs_settings[checkness][productssave]" style="width: 100%;" value="<?php echo ( !empty( $options['checkness']['productssave'] ) ? sanitize_text_field( $options['checkness']['productssave'] ) : '' ); ?>" />
			<h3 class="heading address"><div class="info-of"><?php _e('To get product number, goto the listing of WooCoommerce Products then hover over each product and you will see ID. Example', 'woocommerce-checkout-manager'); ?> "ID: 3651"</div></h3>
		</div>
		<!-- .option -->

	</div>
	<!-- .section -->

</div>
<!-- .address_fields -->