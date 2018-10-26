<div class="widefat general-semi checkout_notices" border="1" style="display:none;" >

	<div class="section">

		<h3 class="heading"><?php _e('Position for notification one', 'woocommerce-checkout-manager');  ?></h3>

		<h3 class="heading checkbox">

			<div class="option">
				<label>
					<input type="checkbox" name="wccs_settings[checkness][checkbox1]" style="float:left;" value="true"<?php checked( !empty( $options['checkness']['checkbox1'] ), true ); ?> />
					<div class="info-of"><?php _e('Before Customer Address Fields', 'woocommerce-checkout-manager');  ?></div>
				</label>
			</div>
			<!-- .option -->

		</h3>
		<!-- .heading -->

		<h3 class="heading checkbox">

			<div class="option">
				<label>
					<input type="checkbox" name="wccs_settings[checkness][checkbox2]" style="float:left;" value="true"<?php checked( !empty( $options['checkness']['checkbox2'] ), true ); ?> />
					<div class="info-of"><?php _e('Before Order Summary', 'woocommerce-checkout-manager');  ?></div>
				</label>
			</div>
			<!-- .option -->

		</h3>
		<!-- .heading -->

		<div class="option">
			<div class="info-of"><?php _e('Notification text area: You can use class', 'woocommerce-checkout-manager');  ?> "woocommerce-info" <?php _e('for the same design as WooCommerce Coupon.', 'woocommerce-checkout-manager');  ?></div>
			<textarea type="textarea" name="wccs_settings[checkness][text1]" class="full-width" style="height:150px;"><?php echo ( !empty( $options['checkness']['text1'] ) ? esc_attr( $options['checkness']['text1'] ) : '' ); ?></textarea>
		</div>
		<!-- .option -->

	</div>
	<!-- section -->

	<div class="section">

		<h3 class="heading"><?php _e('Position for notification two', 'woocommerce-checkout-manager');  ?></h3>

		<h3 class="heading checkbox">

			<div class="option">
				<label>
					<input type="checkbox" name="wccs_settings[checkness][checkbox3]" style="float:left;" value="true"<?php checked( !empty( $options['checkness']['checkbox3'] ), true ); ?> />
					<div class="info-of"><?php _e('Before Customer Address Fields', 'woocommerce-checkout-manager');  ?></div>
				</label>
			</div>
			<!-- .option -->

		</h3>
		<!-- .heading -->

		<h3 class="heading checkbox">

			<div class="option">
				<label>
					<input type="checkbox" name="wccs_settings[checkness][checkbox4]" style="float:left;" value="true"<?php checked( !empty( $options['checkness']['checkbox4'] ), true ); ?> />
					<div class="info-of"><?php _e('Before Order Summary', 'woocommerce-checkout-manager');  ?></div>
				</label>
			</div>
			<!-- .option -->

		</h3>
		<!-- .heading -->

		<div class="option">
			<div class="info-of"><?php _e('Notification text area: You can use class', 'woocommerce-checkout-manager');  ?> "woocommerce-info" <?php _e('for the same design as WooCommerce Coupon.', 'woocommerce-checkout-manager');  ?></div>
			<textarea type="textarea" name="wccs_settings[checkness][text2]" class="full-width" style="height:150px;"><?php echo ( !empty( $options['checkness']['text2'] ) ? esc_attr( $options['checkness']['text2'] ) : '' ); ?></textarea>
		</div>
		<!-- .option -->

	</div>    
	<!-- section -->

</div>
<!-- .checkout_notices -->