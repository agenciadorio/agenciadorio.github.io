<div id="general-semi-nav">

	<div id="main-nav-left">
		<ul>
			<li class="upload_class current">
				<a title="<?php _e( 'Uploads', 'woocommerce-checkout-manager' ); ?>"><?php _e( 'Uploads', 'woocommerce-checkout-manager' ); ?></a>
			</li>
			<li class="address_fields_class">
				<a title="<?php _e( 'Address Fields', 'woocommerce-checkout-manager' ); ?>"><?php _e( 'Hide Address Fields', 'woocommerce-checkout-manager' ); ?></a>
			</li>
			<li class="checkout_notice_class">
				<a title="<?php _e( 'Checkout Notices', 'woocommerce-checkout-manager' ); ?>"><?php _e( 'Checkout Notices', 'woocommerce-checkout-manager' ); ?></a>
			</li>
			<li class="switches_class">
				<a title="<?php _e( 'Switches', 'woocommerce-checkout-manager' ); ?>"><?php _e( 'Switches', 'woocommerce-checkout-manager' ); ?></a>
			</li>
			<li class="order_notes_class">
				<a title="<?php _e( 'Order Notes', 'woocommerce-checkout-manager' ); ?>"><?php _e( 'Order Notes', 'woocommerce-checkout-manager' ); ?></a>
			</li>
			<li class="custom_css_class">
				<a title="<?php _e( 'Custom CSS', 'woocommerce-checkout-manager' ); ?>"><?php _e( 'Custom CSS', 'woocommerce-checkout-manager' ); ?></a>
			</li>
			<li class="advanced_class">
				<a title="<?php _e( 'Advanced', 'woocommerce-checkout-manager' ); ?>"><?php _e( 'Advanced', 'woocommerce-checkout-manager' ); ?></a>
			</li>
		</ul>
	</div>
	<!-- #main-nav-left -->

	<div id="content-nav-right" class="general-vibe">

		<!-- Uploads tab -->
		<?php require( WOOCCM_PATH.'includes/templates/admin/woocheckout-general-uploads.php' ); ?>

		<!-- Hide Address Fields tab -->
		<?php require( WOOCCM_PATH.'includes/templates/admin/woocheckout-general-address-fields.php' ); ?>

		<!-- Order Notes tab -->
		<?php require( WOOCCM_PATH.'includes/templates/admin/woocheckout-general-order-notes.php' ); ?>

		<!-- Custom CSS tab -->
		<?php require( WOOCCM_PATH.'includes/templates/admin/woocheckout-general-custom-css.php' ); ?>

		<!-- Checkout Notices tab -->
		<?php require( WOOCCM_PATH.'includes/templates/admin/woocheckout-general-checkout-notices.php' ); ?>

		<!-- Switches tab -->
		<?php require( WOOCCM_PATH.'includes/templates/admin/woocheckout-general-switches.php' ); ?>

		<!-- Advanced tab -->
		<?php require( WOOCCM_PATH.'includes/templates/admin/woocheckout-general-advanced.php' ); ?>

	</div>
	<!-- #content-nav-right -->

</div>
<!-- #general-semi-nav -->