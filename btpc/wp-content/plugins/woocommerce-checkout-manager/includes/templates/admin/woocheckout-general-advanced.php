<div class="widefat general-semi advanced" border="1" style="display:none;">

	<div class="section">
		<h3 class="heading"><?php _e('Advanced', 'woocommerce-checkout-manager'); ?></h3>
	</div>
	<!-- .section -->

	<div class="section">

		<div class="option">
			<div class="info-of"><?php _e('Administrator Actions', 'woocommerce-checkout-manager'); ?></div>
<?php if( current_user_can( 'manage_options' ) ) { ?>
			<ul>
				<li><a href="<?php echo add_query_arg( array( 'action' => 'wooccm_reset_update_notice', '_wpnonce' => wp_create_nonce( 'wooccm_reset_update_notice' ) ) ); ?>"><?php _e( 'Reset <em>Run the updater</em> prompt', 'woocommerce-checkout-manager' ); ?></a></li>
				<li><a href="<?php echo add_query_arg( array( 'action' => 'wooccm_nuke_options', '_wpnonce' => wp_create_nonce( 'wooccm_nuke_options' ) ) ); ?>" class="confirm-button" data-confirm="<?php _e( 'This will permanently delete all WordPress Options associated with WooCommerce Checkout Manager. Are you sure you want to proceed?', 'woocommerce-checkout-manager' ); ?>"><?php _e( 'Delete WooCommerce Checkout Manager WordPress Options', 'woocommerce-checkout-manager' ); ?></a></li>
				<li><a href="<?php echo add_query_arg( array( 'action' => 'wooccm_nuke_order_meta', '_wpnonce' => wp_create_nonce( 'wooccm_nuke_order_meta' ) ) ); ?>" class="confirm-button" data-confirm="<?php _e( 'This will permanently delete all WordPress Post meta associated with WooCommerce Checkout Manager that is linked to Orders. Are you sure you want to proceed?', 'woocommerce-checkout-manager' ); ?>"><?php _e( 'Delete WooCommerce Checkout Manager Orders Post meta', 'woocommerce-checkout-manager' ); ?></a></li>
				<li><a href="<?php echo add_query_arg( array( 'action' => 'wooccm_nuke_user_meta', '_wpnonce' => wp_create_nonce( 'wooccm_nuke_user_meta' ) ) ); ?>" class="confirm-button" data-confirm="<?php _e( 'This will permanently delete all WordPress Post meta associated with WooCommerce Checkout Manager that is linked to Users. Are you sure you want to proceed?', 'woocommerce-checkout-manager' ); ?>"><?php _e( 'Delete WooCommerce Checkout Manager Users Post meta', 'woocommerce-checkout-manager' ); ?></a></li>
			</ul>
<?php } else { ?>
			<p><?php _e( 'These actions are available only to WordPress Users with the <em>manage_options</em> User Capability.', 'woocommerce-checkout-manager' ); ?></p>
<?php } ?>
		</div>
		<!-- .option -->

	</div>
	<!-- .section -->

</div>
<!--.advanced -->