<div class="widefat general-semi order_notes" border="1" style="display:none;">

	<div class="section">

		<h3 class="heading"><?php _e('Order Notes','woocommerce-checkout-manager'); ?></h3>

		<div class="option">
			<div class="info-of"><?php _e('Order Notes Label', 'woocommerce-checkout-manager'); ?></div>
			<input type="text" name="wccs_settings[checkness][noteslabel]" class="full-width" style="clear:both;" value="<?php echo ( isset( $options['checkness']['noteslabel'] ) ? sanitize_text_field( $options['checkness']['noteslabel'] ) : '' ); ?>" />
		</div>
		<!-- .option -->

		<div class="option">
			<div class="info-of"><?php _e('Order Notes Placeholder', 'woocommerce-checkout-manager');  ?></div>
			<input type="text" name="wccs_settings[checkness][notesplaceholder]" class="full-width" style="clear:both;" value="<?php echo ( isset( $options['checkness']['notesplaceholder'] ) ? sanitize_text_field( $options['checkness']['notesplaceholder'] ) : '' ); ?>" />
		</div>
		<!-- .option -->

		<h3 class="heading checkbox" style="clear:both;">

			<div class="option">
				<label>
					<input type="checkbox" name="wccs_settings[checkness][notesenable]" value="true"<?php checked( !empty( $options['checkness']['notesenable'] ), true ); ?> />
					<div class="info-of"><?php _e('Disable Order Notes.', 'woocommerce-checkout-manager');  ?></div>
				</label>
			</div>
			<!-- .option -->

		</h3>

	</div>
	<!-- .section -->

	<div class="section">

		<h3 class="heading"><?php _e('Time Order was purchased', 'woocommerce-checkout-manager');  ?></h3>

		<div class="option">
			<div class="info-of"><?php _e('Order time title', 'woocommerce-checkout-manager');  ?></div>
			<input type="text" name="wccs_settings[checkness][time_stamp_title]" class="full-width" style="clear:both;" value="<?php echo ( !empty( $options['checkness']['time_stamp_title'] ) ? sanitize_text_field( $options['checkness']['time_stamp_title'] ) : '' ); ?>" />
		</div>
		<!-- .option -->

		<div class="option">
			<div class="info-of"><?php _e('Set TimeZone', 'woocommerce-checkout-manager');  ?></div>
			<input type="text" name="wccs_settings[checkness][set_timezone]" class="full-width" style="clear:both;" value="<?php echo ( !empty( $options['checkness']['set_timezone'] ) ? sanitize_text_field( $options['checkness']['set_timezone'] ) : '' ); ?>" />
		</div>
		<!-- .option -->

		<h3 class="heading checkbox" style="clear:both;">

			<div class="option">
				<label>
					<input type="checkbox" name="wccs_settings[checkness][time_stamp]" value="true"<?php checked( !empty( $options['checkness']['time_stamp'] ), true ); ?> />
					<div class="info-of"><?php _e('Enable display of order time.', 'woocommerce-checkout-manager');  ?></div>
				</label>
			</div>
			<!-- .option -->

			<div class="option">
				<label>
					<input type="checkbox" name="wccs_settings[checkness][twenty_hour]" value="true"<?php checked( !empty( $options['checkness']['twenty_hour]'] ), true ); ?> />
					<div class="info-of"><?php _e('Enable 24 hour time.', 'woocommerce-checkout-manager');  ?></div>
				</label>
			</div>
			<!-- .option -->

		</h3>
		<!-- .heading -->

	</div>
	<!-- .section -->

	<div class="section">

		<h3 class="heading"><?php _e('Payment Method used by Customer', 'woocommerce-checkout-manager');  ?></h3>

		<div class="option">
			<input type="text" name="wccs_settings[checkness][payment_method_d]" class="full-width" value="<?php echo ( !empty( $options['checkness']['payment_method_d'] ) ? sanitize_text_field( $options['checkness']['payment_method_d'] ) : '' ); ?>" />
		</div>
		<!-- .option -->

		<h3 class="heading checkbox">

			<div class="option">
				<label>
					<input type="checkbox" name="wccs_settings[checkness][payment_method_t]" value="true" <?php checked( !empty( $options['checkness']['payment_method_t'] ), true ) ?> />
					<div class="info-of"><?php _e('Enable display of Payment Method.', 'woocommerce-checkout-manager');  ?></div>
				</label>
			</div>
			<!-- .option -->

		</h3>
		<!-- .heading -->

	</div>
	<!-- .section -->

	<div class="section">

		<h3 class="heading"><?php _e('Shipping method used by customer', 'woocommerce-checkout-manager');  ?></h3>

		<div class="option">
			<input type="text" name="wccs_settings[checkness][shipping_method_d]" class="full-width" value="<?php echo ( !empty( $options['checkness']['shipping_method_d'] ) ? sanitize_text_field( $options['checkness']['shipping_method_d'] ) : '' ) ?>" />
		</div>
		<!-- .option -->

		<h3 class="heading checkbox">

			<div class="option">
				<label>
					<input type="checkbox" name="wccs_settings[checkness][shipping_method_t]" value="true"<?php checked( !empty( $options['checkness']['shipping_method_t'] ), true ); ?> />
					<div class="info-of"><?php _e('Enable display of Shipping Method.', 'woocommerce-checkout-manager');  ?></div>
				</label>
			</div>
			<!-- .option -->

		</h3>
		<!-- .heading -->

	</div>
	<!-- .section -->

	<div class="section">

		<h3 class="heading"><?php _e('Default State code for Checkout.', 'woocommerce-checkout-manager');  ?></h3>

		<div class="option">
			<input type="text" placeholder="ND" name="wccs_settings[checkness][per_state]" class="full-width" value="<?php echo ( !empty( $options['checkness']['per_state'] ) ? sanitize_text_field( $options['checkness']['per_state'] ) : '' ); ?>" />
		</div>
		<!-- .option -->

		<h3 class="heading checkbox">

			<div class="option">
				<label>
					<input type="checkbox" name="wccs_settings[checkness][per_state_check]" value="true" <?php checked( !empty( $options['checkness']['per_state_check'] ), true ); ?> />
					<div class="info-of"><?php _e('Enable default state code.', 'woocommerce-checkout-manager');  ?></div>
				</label>
			</div>
			<!-- .option -->

		</h3>
		<!-- .heading -->

	</div>
	<!-- .section -->

</div>
<!-- .order_notes -->