<div class="widefat general-semi custom_css" border="1" style="display:none;">

	<div class="section">

		<h3 class="heading"><?php _e('Custom CSS','woocommerce-checkout-manager'); ?></h3>
		
		<div class="option">
			<textarea type="text" name="wccs_settings[checkness][custom_css_w]" class="full-width" style="height:200px;"><?php echo ( !empty( $options['checkness']['custom_css_w'] ) ? esc_textarea( $options['checkness']['custom_css_w'] ) : '' ); ?></textarea>
		</div>
		<!-- .option -->

		<h3 class="heading checkbox">

			<div class="option">
				<div class="info-of">
					<?php _e('CSS language stands for Cascading Style Sheets which is used to style html content. You can change the fonts size, colours, margins of content, which lines to show or input, adjust height, width, background images etc.','woocommerce-checkout-manager'); ?>
					<?php _e('Get help in our', 'woocommerce-checkout-manager');  ?> <a href="https://wordpress.org/support/plugin/woocommerce-checkout-manager" target="_blank"><?php _e('Support Forum', 'woocommerce-checkout-manager');  ?></a>.
				</div>
			</div>
			<!-- .option -->

		</h3>

	</div>
	<!-- .section -->

</div>
<!-- .custom_css -->