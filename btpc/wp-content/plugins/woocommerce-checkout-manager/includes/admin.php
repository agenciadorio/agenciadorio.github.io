<?php
if( is_admin() ) {

	// backend scripts
	add_action( 'admin_enqueue_scripts', 'wooccm_admin_enqueue_scripts' );
	// List of action links on the Plugins screen
	add_filter( sprintf( 'plugin_action_links_%s', WOOCCM_RELPATH ), 'wooccm_admin_plugin_actions' );
	// WordPress Settings screen for WooCheckout
	add_action( 'admin_init', 'wooccm_register_settings' );
	// WP Admin Actions
	add_action( 'admin_init', 'wooccm_admin_actions' );
	add_action( 'admin_init', 'wooccm_admin_woocheckout_actions' );
	// Updater notice
	add_action( 'admin_notices', 'wooccm_admin_notices' );
	// Add fields to the Edit Order screen
	add_action( 'woocommerce_admin_order_data_after_order_details', 'wooccm_admin_edit_order_additional_details' );
	add_action( 'woocommerce_admin_order_data_after_billing_address', 'wooccm_admin_edit_order_billing_details' );
	add_action( 'woocommerce_admin_order_data_after_shipping_address', 'wooccm_admin_edit_order_shipping_details' );

}

// Display admin notice on screen load
function wooccm_admin_notice( $message = '', $priority = 'updated', $screen = '' ) {

	if( $priority == false || $priority == '' )
		$priority = 'updated';
	if( $message <> '' ) {
		ob_start();
		wooccm_admin_notice_html( $message, $priority, $screen );
		$output = ob_get_contents();
		ob_end_clean();
		// Check if an existing notice is already in queue
		$existing_notice = get_transient( WOOCCM_PREFIX . '_notice' );
		if( $existing_notice !== false ) {
			$existing_notice = base64_decode( $existing_notice );
			$output = $existing_notice . $output;
		}
		set_transient( WOOCCM_PREFIX . '_notice', base64_encode( $output ), MINUTE_IN_SECONDS );
		add_action( 'admin_notices', WOOCCM_PREFIX . '_admin_notice_print' );
	}

}

// HTML template for admin notice
function wooccm_admin_notice_html( $message = '', $priority = 'updated', $screen = '' ) {

	// Display admin notice on specific screen
	if( !empty( $screen ) ) {

		global $pagenow;

		if( is_array( $screen ) ) {
			if( in_array( $pagenow, $screen ) == false )
				return;
		} else {
			if( $pagenow <> $screen )
				return;
		}

	} ?>
<div id="message" class="<?php echo $priority; ?>">
	<p><?php echo $message; ?></p>
</div>
<?php

}

// Grabs the WordPress transient that holds the admin notice and prints it
function wooccm_admin_notice_print() {

	$output = get_transient( WOOCCM_PREFIX . '_notice' );
	if( $output !== false ) {
		delete_transient( WOOCCM_PREFIX . '_notice' );
		$output = base64_decode( $output );
		echo $output;
	}

}

// WordPress Administration menu
function wooccm_admin_menu() {

	add_menu_page( 'WooCheckout', 'WooCheckout', 'manage_options', 'woocommerce-checkout-manager' , 'wooccm_options_page', 'dashicons-businessman', 57);
	// @mod - Remove until exports are fixed...
	// add_submenu_page( 'woocommerce-checkout-manager', 'Export', 'Export', 'manage_options', 'wooccm-advance-export', 'wooccm_advance_export' );

}
add_action( 'admin_menu', 'wooccm_admin_menu' );

function wooccm_admin_enqueue_scripts( $hook_suffix ) {

	if( $hook_suffix == 'toplevel_page_woocommerce-checkout-manager' ) {
		wp_enqueue_style( 'farbtastic' );
		// @mod - We need to check that farbtastic exists
		wp_enqueue_script( 'farbtastic', site_url( '/wp-admin/js/farbtastic.js' ) );
		wp_enqueue_style( 'wooccm-backend-css', plugins_url( 'includes/pickers/css/backend_css.css', WOOCCM_RELPATH ) );
		wp_enqueue_script( 'script_wccs', plugins_url( 'includes/templates/js/script_wccs.js', WOOCCM_RELPATH ), array( 'jquery' ), '1.2' );
		wp_enqueue_script( 'billing_script_wccs', plugins_url( 'includes/templates/js/billing_script_wccs.js', WOOCCM_RELPATH ), array( 'jquery' ), '1.2' );
		wp_enqueue_script( 'shipping_script_wccs', plugins_url( 'includes/templates/js/shipping_script_wccs.js', WOOCCM_RELPATH ), array( 'jquery' ), '1.2' );
		if( wp_script_is( 'jquery-ui-sortable', 'queue' ) == false )
			wp_enqueue_script('jquery-ui-sortable');
	}
	if( $hook_suffix === 'woocheckout_page_wooccm-advance-export' ) {
		wp_enqueue_style( 'export', plugins_url( 'includes/templates/admin/woocheckout-export.css', WOOCCM_RELPATH ) );
	}

}

// List of action links on the Plugins screen
function wooccm_admin_plugin_actions( $links ) {

	$page_url = add_query_arg( 'page', 'woocommerce-checkout-manager', 'admin.php' );
	$support_url = 'https://wordpress.org/support/plugin/woocommerce-checkout-manager/';

	$plugin_links = array(
		'<a href="' . $page_url . '">'.__('Settings', 'woocommerce-checkout-manager' ).'</a>',
		'<a href="' . $support_url . '">'.__('Support', 'woocommerce-checkout-manager' ).'</a>',
	);
	return array_merge( $plugin_links, $links );

}

function wooccm_deactivate_plugin_conditional() {

	$name = 'woocommerce-checkout-manager/woocommerce-checkout-manager.php';
	if( !is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
		add_action('admin_notices', 'wooccm_admin_notice_woo');
		deactivate_plugins( $name );
	}

}
add_action( 'admin_init', 'wooccm_deactivate_plugin_conditional' );

function wooccm_admin_notice_woo() {

	$message = __( 'WooCommerce is not active. WooCommerce Checkout Manager requires WooCommerce to be active.', 'woocommerce-checkout-manager' );
	echo '<div class="error"><p><strong>' . $message . '</strong></p></div>';

}

// Global actions
function wooccm_admin_actions() {

	// Check the User has the manage_options capability
	if( current_user_can( 'manage_options' ) == false )
		return;

	// Process any actions
	$action = ( function_exists( 'woo_get_action' ) ? woo_get_action() : false );
	switch( $action ) {

		case 'wooccm_dismiss_beta_notice':
			// We need to verify the nonce.
			if( !empty( $_GET ) && check_admin_referer( 'wooccm_dismiss_beta_notice' ) ) {
				add_option( WOOCCM_PREFIX . '_beta_notice', 1 );
				$url = add_query_arg( array( 'action' => null, '_wpnonce' => null ) );
				wp_redirect( $url );
				exit();
			}
			break;

		case 'wooccm_dismiss_update_notice':
			// We need to verify the nonce.
			if( !empty( $_GET ) && check_admin_referer( 'wooccm_dismiss_update_notice' ) ) {
				update_option( WOOCCM_PREFIX . '_update_notice', 1 );
				$url = add_query_arg( array( 'action' => null, '_wpnonce' => null ) );
				wp_redirect( $url );
				exit();
			}
			break;

	}

}

// Actions limited to the WooCheckout screen
function wooccm_admin_woocheckout_actions() {

	// Check the User has the manage_options capability
	if( current_user_can( 'manage_options' ) == false )
		return;

	// Check that we are on the WooCheckout screen
	$page = ( isset($_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : false );
	if( $page != 'woocommerce-checkout-manager' )
		return;

	// Process any actions
	$action = ( function_exists( 'woo_get_action' ) ? woo_get_action() : false );
	switch( $action ) {

		// Reset the Run the updater notice
		case 'wooccm_reset_update_notice':
			// We need to verify the nonce.
			if( !empty( $_GET ) && check_admin_referer( 'wooccm_reset_update_notice' ) ) {
				delete_option( WOOCCM_PREFIX . '_update_notice' );
				$url = add_query_arg( array( 'action' => null, '_wpnonce' => null ) );
				wp_redirect( $url );
				exit();
			}
			break;

		case 'wooccm_nuke_options':
			// We need to verify the nonce.
			if( !empty( $_GET ) && check_admin_referer( 'wooccm_nuke_options' ) ) {
				// Delete the default options
				$options = array( 'settings', 'settings2', 'settings3' );
				foreach( $options as $option )
					delete_option( 'wccs_' . $option );
				// Delete any notices
				$notices = array( 'update_notice', 'beta_notice' );
				foreach( $notices as $notice )
					delete_option( WOOCCM_PREFIX . '_' . $notice );
				$url = add_query_arg( array( 'action' => null, '_wpnonce' => null ) );
				wp_redirect( $url );
				exit();
			}
			break;

		case 'wooccm_nuke_order_meta':
			// We need to verify the nonce.
			if( !empty( $_GET ) && check_admin_referer( 'wooccm_nuke_order_meta' ) ) {
				$post_type = 'shop_order';
				$args = array(
					'post_type' => $post_type,
					'post_status' => ( function_exists( 'wc_get_order_statuses()' ) ? wc_get_order_statuses() : false ),
					'fields' => 'ids',
					'numberposts' => -1
				);
				$orders = get_posts( $args );
				if( !empty( $orders ) ) {
					// Prepare the Post meta name lists for only custom fields
					$meta_keys = array();

					// Additional section
					$options = get_option( 'wccs_settings' );
					$buttons = ( isset( $options['buttons'] ) ? $options['buttons'] : false );
					if( !empty( $buttons ) ) {
						foreach( $buttons as $btn ) {
							if( strstr( $btn['cow'], 'myfield' ) )
								$meta_keys[] = $btn['cow'];
						}
					}
					// Billing section
					$options = get_option( 'wccs_settings3' );
					$buttons = ( isset( $options['billing_buttons'] ) ? $options['billing_buttons'] : false );
					if( !empty( $buttons ) ) {
						foreach( $buttons as $btn ) {
							if( strstr( $btn['cow'], 'myfield' ) )
								$meta_keys[] = sprintf( '_billing_%s', $btn['cow'] );
						}
					}
					// Shipping section
					$options = get_option( 'wccs_settings2' );
					$buttons = ( isset( $options['shipping_buttons'] ) ? $options['shipping_buttons'] : false );
					if( !empty( $buttons ) ) {
						foreach( $buttons as $btn ) {
							if( strstr( $btn['cow'], 'myfield' ) )
								$meta_keys[] = sprintf( '_shipping_%s', $btn['cow'] );
						}
					}

					// Do the deed
					if( !empty( $meta_keys ) ) {
						foreach( $orders as $order_id ) {
							if( !empty( $order_id ) ) {
								foreach( $meta_keys as $meta_key )
									delete_post_meta( $order_id, $meta_key );
							}
						}
					}
				}
				$url = add_query_arg( array( 'action' => null, '_wpnonce' => null ) );
				wp_redirect( $url );
				exit();
			}
			break;

		case 'wooccm_nuke_user_meta':
			// We need to verify the nonce.
			if( !empty( $_GET ) && check_admin_referer( 'wooccm_nuke_user_meta' ) ) {
				$args = array(
					'fields' => array( 'ID' )
				);
				$users = get_users( $args );
				if( !empty( $users ) ) {
					// Prepare the Post meta name lists for only custom fields
					$meta_keys = array();

					// Additional section
					$options = get_option( 'wccs_settings' );
					$buttons = ( isset( $options['buttons'] ) ? $options['buttons'] : false );
					if( !empty( $buttons ) ) {
						foreach( $buttons as $btn ) {
							if( strstr( $btn['cow'], 'myfield' ) )
								$meta_keys[] = $btn['cow'];
						}
					}
					// Billing section
					$options = get_option( 'wccs_settings3' );
					$buttons = ( isset( $options['billing_buttons'] ) ? $options['billing_buttons'] : false );
					if( !empty( $buttons ) ) {
						foreach( $buttons as $btn ) {
							if( strstr( $btn['cow'], 'myfield' ) )
								$meta_keys[] = sprintf( 'billing_%s', $btn['cow'] );
						}
					}
					// Shipping section
					$options = get_option( 'wccs_settings2' );
					$buttons = ( isset( $options['shipping_buttons'] ) ? $options['shipping_buttons'] : false );
					if( !empty( $buttons ) ) {
						foreach( $buttons as $btn ) {
							if( strstr( $btn['cow'], 'myfield' ) )
								$meta_keys[] = sprintf( 'shipping_%s', $btn['cow'] );
						}
					}

					// Do the deed
					if( !empty( $meta_keys ) ) {
						foreach( $users as $user_id ) {
							foreach( $meta_keys as $meta_key )
								delete_user_meta( $user_id->ID, $meta_key );
						}
					}
				}
				$url = add_query_arg( array( 'action' => null, '_wpnonce' => null ) );
				wp_redirect( $url );
				exit();
			}
			break;

		case 'wooccm_dismiss_beta_notice':
			// We need to verify the nonce.
			if( !empty( $_GET ) && check_admin_referer( 'wooccm_dismiss_beta_notice' ) ) {
				add_option( WOOCCM_PREFIX . '_beta_notice', 1 );
				$url = add_query_arg( array( 'action' => null, '_wpnonce' => null ) );
				wp_redirect( $url );
				exit();
			}
			break;

		case 'wooccm_dismiss_update_notice':
			// We need to verify the nonce.
			if( !empty( $_GET ) && check_admin_referer( 'wooccm_dismiss_update_notice' ) ) {
				add_option( WOOCCM_PREFIX . '_update_notice', 1 );
				$url = add_query_arg( array( 'action' => null, '_wpnonce' => null ) );
				wp_redirect( $url );
				exit();
			}
			break;

	}

}

if( !function_exists( 'woo_get_action' ) ) {
	function woo_get_action( $prefer_get = false ) {

		if ( isset( $_GET['action'] ) && $prefer_get )
			return sanitize_text_field( $_GET['action'] );

		if ( isset( $_POST['action'] ) )
			return sanitize_text_field( $_POST['action'] );

		if ( isset( $_GET['action'] ) )
			return sanitize_text_field( $_GET['action'] );

		return;

	}
}

// WordPress Settings screen for WooCheckout
function wooccm_register_settings() {

	register_setting( 'wccs_options', 'wccs_settings', 'wooccm_options_validate' );
	register_setting( 'wccs_options2', 'wccs_settings2', 'wooccm_options_validate_shipping' );
	register_setting( 'wccs_options3', 'wccs_settings3', 'wooccm_options_validate_billing' );

}

function wooccm_options_page() {

	if ( !current_user_can('manage_options') )
		wp_die( __('You do not have sufficient permissions to access this page.', 'woocommerce-checkout-manager') ); 

	$htmlshippingabbr = array( 'country', 'first_name', 'last_name', 'company', 'address_1', 'address_2', 'city', 'state', 'postcode' );
	$htmlbillingabbr = array( 'country', 'first_name', 'last_name', 'company', 'address_1', 'address_2', 'city', 'state', 'postcode', 'email', 'phone' );
	$upload_dir = wp_upload_dir();
	$hidden_field_name = 'mccs_submit_hidden';
	$hidden_wccs_reset = "my_new_field_reset";
	$options = get_option( 'wccs_settings' );
	$options2 = get_option( 'wccs_settings2' );
	$options3 = get_option( 'wccs_settings3' );

	// Check if the reset button has been clicked
	if( isset($_POST[ $hidden_wccs_reset ]) && sanitize_text_field( $_POST[ $hidden_wccs_reset ] ) == 'Y' ) {
		delete_option('wccs_settings');
		delete_option('wccs_settings2');
		delete_option('wccs_settings3');
		$defaults = array(
			'checkness' => array(
				'position' => 'after_billing_form',
				'wooccm_notification_email' => get_option( 'admin_email' ),
				'payment_method_t' => true,
				'shipping_method_t' => true,
				'payment_method_d' => __( 'Payment Method','woocommerce-checkout-manager' ),
				'shipping_method_d' => __( 'Shipping Method','woocommerce-checkout-manager' ),
				'time_stamp_title' => __( 'Order Time','woocommerce-checkout-manager' ),
			),
		);

		$shipping = array(
			'country' => __( 'Country', 'woocommerce-checkout-manager' ), 
			'first_name' => __( 'First Name', 'woocommerce-checkout-manager' ), 
			'last_name' => __( 'Last Name', 'woocommerce-checkout-manager' ), 
			'company' => __( 'Company Name', 'woocommerce-checkout-manager' ), 
			'address_1' => __( 'Address', 'woocommerce-checkout-manager' ), 
			'address_2' => '', 
			'city' => __( 'Town/ City', 'woocommerce-checkout-manager' ), 
			'state' => __( 'State', 'woocommerce-checkout-manager' ), 
			'postcode' => __( 'Zip', 'woocommerce-checkout-manager' )
		);
		$ship = 0;
		foreach( $shipping as $name => $value ) {

			$defaults2['shipping_buttons'][$ship]['label'] = __( $value, 'woocommerce-checkout-manager' );
			$defaults2['shipping_buttons'][$ship]['cow'] = $name;
			$defaults2['shipping_buttons'][$ship]['checkbox']  = 'true';
			$defaults2['shipping_buttons'][$ship]['order'] = $ship + 1;
			$defaults2['shipping_buttons'][$ship]['type'] = 'wooccmtext';

			switch( $name ) {

				case 'country':
					$defaults2['shipping_buttons'][$ship]['position'] = 'form-row-wide';
					break;

				case 'first_name':
					$defaults2['shipping_buttons'][$ship]['position'] = 'form-row-first';
					break;

				case 'last_name':
					$defaults2['shipping_buttons'][$ship]['position'] = 'form-row-last';
					$defaults2['shipping_buttons'][$ship]['clear_row'] = true;
					break;

				case 'company':
					$defaults2['shipping_buttons'][$ship]['position'] = 'form-row-wide';
					break;

				case 'address_1':
					$defaults2['shipping_buttons'][$ship]['position'] = 'form-row-wide';
					$defaults2['shipping_buttons'][$ship]['placeholder'] = __('Street address', 'woocommerce-checkout-manager');
					break;

				case 'address_2':
					$defaults2['shipping_buttons'][$ship]['position'] = 'form-row-wide';
					$defaults2['shipping_buttons'][$ship]['placeholder'] = __('Apartment, suite, unit etc. (optional)', 'woocommerce-checkout-manager');
					break;

				case 'city':
					$defaults2['shipping_buttons'][$ship]['position'] = 'form-row-wide';
					$defaults2['shipping_buttons'][$ship]['placeholder'] = __('Town / City', 'woocommerce-checkout-manager');
					break;

				case 'state':
					$defaults2['shipping_buttons'][$ship]['position'] = 'form-row-first';
					break;

			}

			$ship++;

		}

		$billing = array(
			'country' => __( 'Country', 'woocommerce-checkout-manager' ), 
			'first_name' => __( 'First Name', 'woocommerce-checkout-manager' ), 
			'last_name' => __( 'Last Name', 'woocommerce-checkout-manager' ), 
			'company' => __( 'Company Name', 'woocommerce-checkout-manager' ), 
			'address_1' => __( 'Address', 'woocommerce-checkout-manager' ), 
			'address_2' => '', 
			'city' => __( 'Town/ City', 'woocommerce-checkout-manager' ), 
			'state' => __( 'State', 'woocommerce-checkout-manager' ), 
			'postcode' => __( 'Zip', 'woocommerce-checkout-manager' ), 
			'email' => __( 'Email Address', 'woocommerce-checkout-manager' ), 
			'phone' => __( 'Phone', 'woocommerce-checkout-manager' )
		);

		$bill = 0;

		foreach( $billing as $name => $value ) {

			$defaults3['billing_buttons'][$bill]['label'] = __( $value, 'woocommerce-checkout-manager' );
			$defaults3['billing_buttons'][$bill]['cow'] = $name;
			$defaults3['billing_buttons'][$bill]['checkbox']  = 'true';
			$defaults3['billing_buttons'][$bill]['order'] = $bill + 1;	
			$defaults3['billing_buttons'][$bill]['type'] = 'wooccmtext';

			switch( $name ) {

				case 'country':
					$defaults3['billing_buttons'][$bill]['position'] = 'form-row-wide';
					break;

				case 'first_name':
					$defaults3['billing_buttons'][$bill]['position'] = 'form-row-first';
					break;

				case 'last_name':
					$defaults3['billing_buttons'][$bill]['position'] = 'form-row-last';
					$defaults3['billing_buttons'][$bill]['clear_row'] = true;
					break;

				case 'company':
					$defaults3['billing_buttons'][$bill]['position'] = 'form-row-wide';
					break;

				case 'address_1':
					$defaults3['billing_buttons'][$bill]['position'] = 'form-row-wide';
					$defaults3['billing_buttons'][$bill]['placeholder'] = __('Street address', 'woocommerce-checkout-manager');
					break;

				case 'address_2':
					$defaults3['billing_buttons'][$bill]['position'] = 'form-row-wide';
					$defaults3['billing_buttons'][$bill]['placeholder'] = __('Apartment, suite, unit etc. (optional)', 'woocommerce-checkout-manager');
					break;

				case 'city':
					$defaults3['billing_buttons'][$bill]['position'] = 'form-row-wide';
					$defaults3['billing_buttons'][$bill]['placeholder'] = __('Town / City', 'woocommerce-checkout-manager');
					break;

				case 'state':
					$defaults3['billing_buttons'][$bill]['position'] = 'form-row-first';
					break;

				case 'postcode':
					$defaults3['billing_buttons'][$bill]['position'] = 'form-row-last';
					$defaults3['billing_buttons'][$bill]['placeholder'] = __('Postcode / Zip', 'woocommerce-checkout-manager');
					$defaults3['billing_buttons'][$bill]['clear_row'] = true;
					break;

				case 'email':
					$defaults3['billing_buttons'][$bill]['position'] = 'form-row-first';
					break;

				case 'phone':
					$defaults3['billing_buttons'][$bill]['position'] = 'form-row-last';
					$defaults3['billing_buttons'][$bill]['clear_row'] = true;
					break;

			}

			$bill++;

		}
		add_option( 'wccs_settings' , $defaults );
		add_option( 'wccs_settings2' , $defaults2 );
		add_option( 'wccs_settings3' , $defaults3 );

		// @mod - Change this to add_query_arg()
		echo '
<script type="text/javascript">window.location.href="'.$_SERVER['PHP_SELF'].'?page=woocommerce-checkout-manager";</script>';
		echo '
<noscript><meta http-equiv="refresh" content="0;url='.$_SERVER['PHP_SELF'].'?page=woocommerce-checkout-manager" /></noscript>';
		exit;

	}
	echo '
<script type="text/javascript" src="'.plugins_url( '/woocommerce/assets/js/jquery-blockui/jquery.blockUI.js' ).'"></script>';
	echo '
<div class="refreshwooccm">
';

	// display error
	settings_errors();

	// Now display the settings editing screen

	// header
?>
<h2><?php _e( 'WooCommerce Checkout Manager', 'woocommerce-checkout-manager' ); ?></h2>
<div id="content">

	<h2 class="nav-tab-wrapper add_tip_wrap">
		<a class="nav-tab general-tab nav-tab-active"><?php _e( 'General', 'woocommerce-checkout-manager' ); ?></a>
		<a class="nav-tab billing-tab"><?php _e( 'Billing', 'woocommerce-checkout-manager' ); ?></a>
		<a class="nav-tab shipping-tab"><?php _e( 'Shipping', 'woocommerce-checkout-manager' ); ?></a>
		<a class="nav-tab additional-tab"><?php _e( 'Additional', 'woocommerce-checkout-manager' ); ?></a>
		<a class="nav-tab star" href="https://wordpress.org/support/view/plugin-reviews/woocommerce-checkout-manager?filter=5" target="_blank">
			<div id="star-five" title="<?php _e('Like the plugin? Rate it! On WordPress.org', 'woocommerce-checkout-manager' ); ?>">
				<div class="star-rating">
					<div class="star star-full"></div>
					<div class="star star-full"></div>
					<div class="star star-full"></div>
					<div class="star star-full"></div>
					<div class="star star-full"></div>
				</div>
				<!-- .star-rating -->
			</div>
			<!-- #star-five -->
		</a>
	</h2>
	<!-- .nav-tab-wrapper -->

	<?php do_action('wooccm_run_color_innerpicker'); ?>

	<form name="reset_form" class="reset_form" method="post" action="">
		<input type="hidden" name="<?php echo esc_attr( $hidden_wccs_reset ); ?>" value="Y">
		<input type="submit" name="submit" id="wccs_reset_submit" class="button button-hero" value="Reset">
	</form>
	<script type="text/javascript">
		jQuery( '#wccs_reset_submit' ).click( 'click', function() {
			return window.confirm( '<?php echo esc_js( __( 'Are you sure you wish to reset the settings on this tab for WooCommerce Checkout Manager?', 'woocommerce-checkout-manager' ) ); ?>' );
		});
	</script>

<?php require( WOOCCM_PATH.'includes/classes/import.php'); ?>

	<div class="wrap">

		<!-- Shipping section -->
		<form name="wooccmform2" method="post" action="options.php" id="frm2">

			<?php settings_fields( 'wccs_options2' ); ?>

			<input type="submit" id="wccs_submit_button" style="display:none;" name="Submit" class="save-shipping wccs_submit_button button button-primary button-hero" value="<?php _e( 'Save Changes', 'woocommerce-checkout-manager' ); ?>" />

			<?php require( WOOCCM_PATH.'includes/templates/admin/woocheckout-shipping.php' ); ?>

		</form>
		<!-- #frm2 -->

		<!-- Billing section -->
		<form name="wooccmform3" method="post" action="options.php" id="frm3">

			<?php settings_fields( 'wccs_options3' ); ?>

			<input type="submit" id="wccs_submit_button" name="Submit" style="display:none;" class="save-billing wccs_submit_button button button-primary button-hero" value="<?php _e( 'Save Changes', 'woocommerce-checkout-manager' ); ?>" />

			<?php require( WOOCCM_PATH.'includes/templates/admin/woocheckout-billing.php' ); ?>

		</form>
		<!-- #frm3 -->

		<!-- Additional section -->
		<form name="wooccmform" method="post" action="options.php" id="frm1">

			<?php settings_fields( 'wccs_options' ); ?>

			<input type="submit" id="wccs_submit_button" name="Submit" class="save-additional wccs_submit_button button button-primary button-hero" value="<?php _e( 'Save Changes', 'woocommerce-checkout-manager' ); ?>" />

			<!-- Additional section -->
			<?php require( WOOCCM_PATH.'includes/templates/admin/woocheckout-additional.php' ); ?>

			<!-- General section -->
			<?php require( WOOCCM_PATH.'includes/templates/admin/woocheckout-general.php' ); ?>

		</form>
		<!-- #frm1 -->

	</div>
	<!-- .wrap -->

</div>
<!-- #content -->

</div>
<!-- #refreshwooccm -->

<?php 

}

function wooccm_options_validate( $input ) {

	$detect_error = 0;
	// translate additional fields
	if( !empty( $input['buttons'] ) ) {
		foreach( $input['buttons'] as $i => $btn ) {

			if( function_exists( 'icl_register_string' ) ) {
				if( !empty($btn['label']) ) {
					icl_register_string( 'WooCommerce Checkout Manager', $btn['label'], $btn['label'] );
				}
				if( !empty($btn['placeholder']) ) {
					icl_register_string( 'WooCommerce Checkout Manager', $btn['placeholder'], $btn['placeholder'] );
				}

				if( !empty($btn['option_array']) ) {	
					$mysecureop = explode( '||', $btn['option_array']);
					foreach( $mysecureop as $one ) {
						icl_register_string( 'WooCommerce Checkout Manager', $one, $one );
					}
				}
			}

			if( !empty($btn['role_options']) && !empty($btn['role_options2']) ) { 
				$input['buttons'][$i]['role_options2'] = '';
				add_settings_error(
					'wooccm_settings_errors',
					esc_attr( 'settings_updated' ),
					__( 'Sorry! An error occurred. WooCommerce Checkout Manager requires you to not have values in both role options. OK.', 'woocommerce-checkout-manager' ),
					'error'
				);
			} 

			if( !empty($btn['single_p']) && !empty($btn['single_px']) ) { 
				$input['buttons'][$i]['single_px'] = '';
				add_settings_error(
					'wooccm_settings_errors',
					esc_attr( 'settings_updated' ),
					__( 'Sorry! An error occurred. WooCommerce Checkout Manager requires you to not have values in both hidden product options. OK.', 'woocommerce-checkout-manager' ),
					'error'
				);
			} 

			if( !empty($btn['single_p_cat']) && !empty($btn['single_px_cat']) ) { 
				$input['buttons'][$i]['single_px_cat'] = '';
				add_settings_error(
					'wooccm_settings_errors',
					esc_attr( 'settings_updated' ),
					__( 'Sorry! An error occurred. WooCommerce Checkout Manager requires you to not have values in both hidden category options. OK.', 'woocommerce-checkout-manager' ),
					'error'
				);
			} 

			if( empty( $btn['cow'] ) && empty( $btn['label'] ) && empty( $btn['placeholder'] ) ) {
				unset( $input['buttons'][$i] );

				if( $i != 999 ) {
					$detect_error++;
					$fieldnum = $i + 1;
					add_settings_error(
						'wooccm_settings_errors',
						esc_attr( 'settings_updated' ),
						__( 'Sorry! An error occurred. WooCommerce Checkout Manager removed Additional field #'.$fieldnum.' because no Label or Placeholder name was provided.', 'woocommerce-checkout-manager' ),
						'error'
					);
				}
			}

			if ( empty( $btn['cow'] ) && (!empty( $btn['label'] ) || !empty( $btn['placeholder'] )) ) {
				$newNum = $i + 1;
				if( wooccm_mul_array( 'myfield'.$newNum , $input['buttons'] ) ) {
					$input['buttons'][$i]['cow'] = 'myfield'.$newNum.'c';
				} else {
					$input['buttons'][$i]['cow'] = 'myfield'.$newNum;
				}
			}

			if( !empty( $btn['cow'] ) && empty( $btn['label'] ) && empty( $btn['placeholder'] ) ) {
				unset( $input['buttons'][$i] );

				if( $i != 999 ) {
					$detect_error++;
					$fieldnum = $i + 1;
					add_settings_error(
						'wooccm_settings_errors',
						esc_attr( 'settings_updated' ),
						__( 'Sorry! An error occurred. WooCommerce Checkout Manager removed Additional field #'.$fieldnum.' because no Label or Placeholder name was provided.', 'woocommerce-checkout-manager' ),
						'error'
					);
				}
			}

		}
	}
	if( $detect_error == 0 ) {
		add_settings_error(
			'wooccm_settings_errors',
			esc_attr( 'settings_updated' ),
			__( 'Your changes have been saved.', 'woocommerce-checkout-manager' ),
			'updated'
		);
	}
	return $input;

}

function wooccm_options_validate_shipping( $input ) {

	$detect_error = 0;
	// translate shipping fields
	if( !empty( $input['shipping_buttons'] ) ) {
		foreach( $input['shipping_buttons'] as $i => $btn ) {

			if( function_exists( 'icl_register_string' ) ) {
				if( !empty($btn['label']) ) {
					icl_register_string( 'WooCommerce Checkout Manager', $btn['label'], $btn['label'] );
				}
				if( !empty($btn['placeholder']) ) {
					icl_register_string( 'WooCommerce Checkout Manager', $btn['placeholder'], $btn['placeholder'] );
				}

				if( !empty($btn['option_array']) ) {
					$mysecureop = explode( '||', $btn['option_array']);
					foreach( $mysecureop as $one ) {
						icl_register_string( 'WooCommerce Checkout Manager', $one, $one );
					}
				}
			}

			if( !empty($btn['role_options']) && !empty($btn['role_options2']) ) {
				$input['buttons'][$i]['role_options2'] = '';
				add_settings_error(
					'wooccm_settings_errors',
					esc_attr( 'settings_updated' ),
					__( 'Sorry! An error occurred. WooCommerce Checkout Manager requires you to not have values in both role options.', 'woocommerce-checkout-manager' ),
					'error'
				);
			}

			if( !empty($btn['single_p']) && !empty($btn['single_px']) ) {
				$input['buttons'][$i]['single_px'] = '';
				add_settings_error(
					'wooccm_settings_errors',
					esc_attr( 'settings_updated' ),
					__( 'Sorry! An error occurred. WooCommerce Checkout Manager requires you to not have values in both hidden product options.', 'woocommerce-checkout-manager' ),
					'error'
				);
			}

			if( !empty($btn['single_p_cat']) && !empty($btn['single_px_cat']) ) { 
				$input['buttons'][$i]['single_px_cat'] = '';
				add_settings_error(
					'wooccm_settings_errors',
					esc_attr( 'settings_updated' ),
					__( 'Sorry! An error occurred. WooCommerce Checkout Manager requires you to not have values in both hidden category options.', 'woocommerce-checkout-manager' ),
					'error'
				);
			}

			if( empty( $btn['cow'] ) && empty( $btn['label'] ) && empty( $btn['placeholder'] ) ) {
				unset( $input['shipping_buttons'][$i] );

				if( $i != 999 ) {
					$detect_error++;
					$fieldnum = $i + 1;
					add_settings_error(
						'wooccm_settings_errors',
						esc_attr( 'settings_updated' ),
						__( 'Sorry! An error occurred. WooCommerce Checkout Manager removed Shipping field #'.$fieldnum.' because no Label or Placeholder name was provided.', 'woocommerce-checkout-manager' ),
						'error'
						);
				}
			}

			if( empty( $btn['cow'] ) && (!empty( $btn['label'] ) || !empty( $btn['placeholder'] )) ) {
				$newNum = $i + 1;
				if( wooccm_mul_array( 'myfield'.$newNum , $input['shipping_buttons'] ) ) {
					$input['shipping_buttons'][$i]['cow'] = 'myfield'.$newNum.'c';
				} else {
					$input['shipping_buttons'][$i]['cow'] = 'myfield'.$newNum;
				}
			} 

			if( !empty( $btn['cow'] ) && empty( $btn['label'] ) && empty( $btn['placeholder'] ) ) {
				unset( $input['shipping_buttons'][$i] );

				if( $i != 999 ) {
					$detect_error++;
					$fieldnum = $i + 1;
					add_settings_error(
						'wooccm_settings_errors',
						esc_attr( 'settings_updated' ),
						__( 'Sorry! An error occurred. WooCommerce Checkout Manager removed Shipping field #'.$fieldnum.' because no Label or Placeholder name was provided.', 'woocommerce-checkout-manager' ),
						'error'
					);
				}
			}

		}
	}

	if( $detect_error == 0 ) {
		add_settings_error(
			'wooccm_settings_errors',
			esc_attr( 'settings_updated' ),
			__( 'Your changes have been saved.', 'woocommerce-checkout-manager' ),
			'updated'
		);
	}

	return $input;

}

function wooccm_options_validate_billing( $input ) {

	$detect_error = 0;

	// translate billing fields
	if( !empty( $input['billing_buttons'] ) ) {
		foreach( $input['billing_buttons'] as $i => $btn ) {

			if( function_exists( 'icl_register_string' ) ) {
				if( !empty($btn['label']) ) {
					icl_register_string( 'WooCommerce Checkout Manager', $btn['label'], $btn['label'] );
				}
				if( !empty($btn['placeholder']) ) {
					icl_register_string( 'WooCommerce Checkout Manager', $btn['placeholder'], $btn['placeholder'] );
				}

				if( !empty($btn['option_array']) ) {
					$mysecureop = explode( '||', $btn['option_array']);
					foreach( $mysecureop as $one ) {
						icl_register_string( 'WooCommerce Checkout Manager', $one, $one );
					}
				}
			}

			if( !empty($btn['role_options']) && !empty($btn['role_options2']) ) { 
				$input['buttons'][$i]['role_options2'] = '';
				add_settings_error(
					'wooccm_settings_errors',
					esc_attr( 'settings_updated' ),
					__( 'Sorry! An error occurred. WooCommerce Checkout Manager requires you to not have values in both role options.', 'woocommerce-checkout-manager' ),
					'error'
				);
			}

			if( !empty($btn['single_p']) && !empty($btn['single_px']) ) { 
				$input['buttons'][$i]['single_px'] = '';
				add_settings_error(
					'wooccm_settings_errors',
					esc_attr( 'settings_updated' ),
					__( 'Sorry! An error occurred. WooCommerce Checkout Manager requires you to not have values in both hidden product options.', 'woocommerce-checkout-manager' ),
					'error'
				);
			}

			if( !empty($btn['single_p_cat']) && !empty($btn['single_px_cat']) ) { 
				$input['buttons'][$i]['single_px_cat'] = '';
				add_settings_error(
					'wooccm_settings_errors',
					esc_attr( 'settings_updated' ),
					__( 'Sorry! An error occurred. WooCommerce Checkout Manager requires you to not have values in both hidden category options.', 'woocommerce-checkout-manager' ),
					'error'
				);
			}

			if( empty( $btn['cow'] ) && empty( $btn['label'] ) && empty( $btn['placeholder'] ) ) { 
				unset( $input['billing_buttons'][$i] );

				if( $i != 999 ) {
					$detect_error++;
					$fieldnum = $i + 1;
					add_settings_error(
						'wooccm_settings_errors',
						esc_attr( 'settings_updated' ),
						__( 'Sorry! An error occurred. WooCommerce Checkout Manager removed Billing field #'.$fieldnum.' because no Label or Placeholder name was provided.', 'woocommerce-checkout-manager' ),
						'error'
					);
				}
			}

			if( empty( $btn['cow'] ) && (!empty( $btn['label'] ) || !empty( $btn['placeholder'] )) ) {
				$newNum = $i + 1;
				if( wooccm_mul_array( 'myfield'.$newNum, $input['billing_buttons'] ) ) {
					$input['billing_buttons'][$i]['cow'] = 'myfield'.$newNum.'c';
				} else {
					$input['billing_buttons'][$i]['cow'] = 'myfield'.$newNum;
				}
			}

			if( !empty( $btn['cow'] ) && empty( $btn['label'] ) && empty( $btn['placeholder'] ) ) { 
				$detect_error++;
				unset( $input['billing_buttons'][$i] );

				if( $i != 999 ) {
					$detect_error++;
					$fieldnum = $i + 1;
					add_settings_error(
						'wooccm_settings_errors',
						esc_attr( 'settings_updated' ),
						__( 'Sorry! An error occurred. WooCommerce Checkout Manager removed Billing field #'.$fieldnum.' because no Label or Placeholder name was provided.', 'woocommerce-checkout-manager' ),
						'error'
					);
				}
			}

		}
	}

	if( $detect_error == 0 ) {
		add_settings_error(
			'wooccm_settings_errors',
			esc_attr( 'settings_updated' ),
			__( 'Your changes have been saved.', 'woocommerce-checkout-manager' ),
			'updated'
		);
	}

	return $input;

}

function wooccm_admin_notices() {

	// Check the User has the manage_options capability
	if( current_user_can( 'manage_options' ) == false )
		return;

	// @mod - Removed as it tends to blow people Options up...
	// Data update from legacy (<3.0)
	// wooccm_admin_updater_notice();

	// Check whether we are on the WooCommerce Checkout Manager screen
	$screen = get_current_screen();

	if( get_option( 'wooccm_beta_notice' ) == false ) {
		$beta_url = 'https://www.visser.com.au/plugins/woocommerce-checkout-manager/#beta';
		$support_url = 'https://wordpress.org/support/plugin/woocommerce-checkout-manager#postform';
		$dismiss_url = add_query_arg( array( 'action' => 'wooccm_dismiss_beta_notice', '_wpnonce' => wp_create_nonce( 'wooccm_dismiss_beta_notice' ) ) );

		$message = '<span style="float:right;"><a href="' . $dismiss_url . '">' . __( 'Dismiss', 'woocommerce-checkout-manager' ) . '</a></span>';
		$message .= __( '<strong>WooCommerce Checkout Manager Notice:</strong> We urgently need developers, integrators and interested store owners to test early Plugin releases and provide feedback to help stabilise the 4.0+ series. Can you help?', 'woocommerce-checkout-manager' );
		$message .= '
		<p class="submit">
			<a href="' . $beta_url . '" target="_blank" class="button-primary button-hero">' . __( 'Join the Developers list', 'woocommerce-checkout-manager' ) . '</a>
			<a href="' . $support_url . '" target="_blank" class="button-secondary button-hero">' . __( 'Send feedback', 'woocommerce-checkout-manager' ) . '</a>
		</p>';
		echo wooccm_admin_notice_html( $message );
	}

}

function wooccm_admin_updater_notice() {

	if( in_array( get_option( WOOCCM_PREFIX . '_update_notice' ), array( 1, 'yep' ) ) == true )
		return;

	$dismiss_url = add_query_arg( array( 'action' => 'wooccm_dismiss_update_notice', '_wpnonce' => wp_create_nonce( 'wooccm_dismiss_update_notice' ) ) );

?>
<form method="post" name="clickhere" action="">
	<div id="message" class="updated settings-error click-here-wooccm">
		<p>
			<span style="float:right;"><a href="<?php echo $dismiss_url; ?>"><?php _e( 'Dismiss', 'woocommerce-checkout-manager' ); ?></a></span>
			<?php _e( '<strong>WooCommerce Checkout Manager Data Update Required</strong> &#8211; We just need to update the settings for WooCommerce Checkout Manager to the latest version.', 'woocommerce-checkout-manager' ); ?>
		</p>
<?php
	// Check whether we are on the WooCommerce Checkout Manager screen
	$screen = get_current_screen();
	if( strstr( $screen->base, 'woocommerce-checkout-manager' ) ) {
?>
		<p class="submit">
			<input type="submit" class="wooccm-update-now button-primary button-hero" value="<?php _e( 'Run the updater', 'woocommerce-checkout-manager' ); ?>" />
		</p>
<?php
	} else {
?>
		<p class="submit">
			<a href="<?php echo add_query_arg( 'page', 'woocommerce-checkout-manager' ); ?>" class="button-primary button-hero "><?php _e( 'Open WooCheckout', 'woocommerce-checkout-manager' ); ?></a>
		</p>
<?php
	}
?>
	</div>
	<!-- #message -->
	<input type="hidden" name="click-here-wooccm" value="y" />
</form>
<?php
	if( strstr( $screen->base, 'woocommerce-checkout-manager' ) ) {
?>
<script type="text/javascript">
	jQuery( '.wooccm-update-now' ).click( 'click', function() {
		return window.confirm( '<?php echo esc_js( __( 'It is strongly recommended that you backup your database before proceeding. Are you sure you wish to run the updater now?', 'woocommerce-checkout-manager' ) ); ?>' );
	});
</script>
<?php

		if( isset($_POST['click-here-wooccm']) && sanitize_text_field( $_POST['click-here-wooccm'] ) == 'y') {
			// @mod - We need to check this file exists
?>

<!-- First Use -->
<script type="text/javascript">
	jQuery(document).ready(function($) {

		$( '#wpbody-content' ).block({message:null,overlayCSS:{background:"#fff url(<?php echo plugins_url( 'woocommerce/assets/images/ajax-loader.gif' ); ?> ) no-repeat center",opacity:.6}});

		var form = $('#frm1');
			data = $('#frm1');
			forma = $('#frm2'); 
			dataa = $('#frm2');
			formb = $('#frm3'); 
			datab = $('#frm3');
			
		$.ajax( {
			type: "POST",
			url: form.attr( 'action' ),
			data: data.serialize(),
			success: function( response ) {

				$.ajax( {
					type: "POST",
					url: forma.attr( 'action' ),
					data: dataa.serialize(),
					success: function( response ) {}
				});

				$.ajax( {
					type: "POST",
					url: formb.attr( 'action' ),
					data: datab.serialize(),
					success: function( response ) {}
				});
				$('.settings-error.click-here-wooccm').hide();
				$('#wpbody-content').unblock();

			}
		});

	});
</script>

<?php
			update_option( WOOCCM_PREFIX . '_update_notice', 1 );
		}

	}

}

// Additional details
function wooccm_admin_edit_order_additional_details( $order ) {

	global $post;

	if( version_compare( wooccm_get_woo_version(), '2.7', '>=' ) ) {
		$order_id = ( method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id );
	} else {
		$order_id = ( isset( $order->id ) ? $order->id : 0 );
	}

	$options = get_option( 'wccs_settings' );
	$buttons = ( isset( $options['buttons'] ) ? $options['buttons'] : false );
	if( !empty( $buttons ) ) {
		echo '
<p>&nbsp;</p>
<h4>' . __( 'Additional Details', 'woocommerce-checkout-manager' ) . '</h4>';
		foreach( $buttons as $btn ) {

			if(
				( get_post_meta( $order_id , $btn['cow'], true ) !== '' ) && 
				!empty( $btn['label'] ) && 
				$btn['type'] !== 'heading' && 
				$btn['type'] !== 'multiselect' && 
				$btn['type'] !== 'wooccmupload' && 
				$btn['type'] !== 'multicheckbox'
			) {
				echo '
<p id="additional_' . $btn['cow'] . '" class="form-field form-field-wide form-field-type-' . $btn['type'] . '">
	<strong title="' . sprintf( __( 'ID: %s | Field Type: %s', 'woocommerce-checkout-manager' ), $btn['cow'], __( 'Generic', 'woocommerce-checkout-manager' ) ) . '">
	'.wooccm_wpml_string( trim( $btn['label'] ) ).':</strong><br />' . nl2br( get_post_meta( $order_id , $btn['cow'], true ) ) . '
</p>
<!-- .form-field-type-... -->';
			} elseif(
				!empty( $btn['label'] ) && 
				$btn['type'] !== 'wooccmupload' && 
				$btn['type'] !== 'multiselect' && 
				$btn['type'] !== 'multicheckbox' && 
				$btn['type'] == 'heading'
			) {
				echo '
<h4>' .wooccm_wpml_string( trim( $btn['label'] ) ). '</h4>';
			} elseif( 
				( get_post_meta( $order_id , $btn['cow'], true ) !== '' ) && 
				!empty( $btn['label'] ) && 
				$btn['type'] !== 'heading' && 
				$btn['type'] !== 'wooccmupload' && 
				(
					$btn['type'] == 'multiselect' || $btn['type'] == 'multicheckbox'
				)
			) {
				$value = get_post_meta( $order_id, $btn['cow'], true );
				$strings = maybe_unserialize( $value );
				echo '
<p class="form-field form-field-wide form-field-type-' . $btn['type'] . '">
	<strong title="' . sprintf( __( 'ID: %s | Field Type: %s', 'woocommerce-checkout-manager' ), $btn['cow'], __( 'Multi-Select or Multi-Checkbox', 'woocommerce-checkout-manager' ) ) . '">'.wooccm_wpml_string( trim( $btn['label'] ) ).':</strong> ';
				if( !empty( $strings ) ) {
					if( is_array( $strings ) ) {
						$iww = 0;
						$len = count($strings);
						foreach( $strings as $key ) {
							if( $iww == $len - 1 ) {
								echo '' . wooccm_wpml_string( $key );
							} else {
								echo '' . wooccm_wpml_string( $key ) . ', ';
							}
							$iww++;
						}
					}
				} else {
					echo '-';
				}
				echo '
</p>
<!-- .form-field-type-multiselect .form-field-type-multicheckbox -->';
			} elseif(
				( get_post_meta( $order_id , $btn['cow'], true ) !== '' ) && 
				$btn['type'] == 'wooccmupload'
			) {
				$attachments = get_post_meta( $order_id, $btn['cow'], true );
				$attachments = ( $attachments !== '' ? explode( ",", $attachments ) : false );
				echo '
<p class="form-field form-field-wide form-field-type-wooccmupload">
	<strong>'.wooccm_wpml_string( trim( $btn['label'] ) ).':</strong>';
if( empty( $attachments ) ) {
	echo '<br />';
	echo '-';
}
				echo '
</p>' . "\n";
				if( !empty( $attachments ) ) {
					echo '<ul>' . "\n";
					foreach( $attachments as $attachment ) {
						$attachment_url = wp_get_attachment_url( $attachment );
						if( !empty( $attachment_url ) )
							echo '<li><a href="' . $attachment_url . '" target="_blank">' . basename( $attachment_url ) . '</a></li>' . "\n";
					}
					echo '</ul>';
				}
				echo '
<!-- .form-field-type-wooccmupload -->';
			}

		}
	}

}

// Billing details
function wooccm_admin_edit_order_billing_details( $order ) {

	global $post;

	$order_id = ( isset( $post->ID ) ? $post->ID : false );

	$options = get_option( 'wccs_settings3' );
	$buttons = ( isset( $options['billing_buttons'] ) ? $options['billing_buttons'] : false );
	if( !empty( $buttons ) ) {
		$fields = array(
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
		foreach( $buttons as $btn ) {

			if( !in_array( $btn['cow'], $fields ) ) {
				if(
					( get_post_meta( $order_id , sprintf( '_billing_%s', $btn['cow'] ), true ) !== '' ) && 
					!empty( $btn['label'] ) && 
					$btn['type'] !== 'heading' && 
					$btn['type'] !== 'multiselect' && 
					$btn['type'] !== 'wooccmupload' && 
					$btn['type'] !== 'multicheckbox'
				) {
					echo '
<p id="billing_' . $btn['cow'] . '" class="form-field form-field-wide form-field-type-' . $btn['type'] . '">
	<strong title="' . sprintf( __( 'ID: %s | Field Type: %s', 'woocommerce-checkout-manager' ), sprintf( '_billing_%s', $btn['cow'] ), __( 'Generic', 'woocommerce-checkout-manager' ) ) . '">
	'.wooccm_wpml_string( trim( $btn['label'] ) ).':</strong><br />'.nl2br( get_post_meta( $order_id , sprintf( '_billing_%s', $btn['cow'] ), true ) ).'
</p>
<!-- .form-field-type-... -->';
				} elseif( 
					!empty( $btn['label'] ) && 
					$btn['type'] !== 'wooccmupload' && 
					$btn['type'] !== 'multiselect' && 
					$btn['type'] !== 'multicheckbox' && 
					$btn['type'] == 'heading'
				) {
					echo '
<h4>' .wooccm_wpml_string( trim( $btn['label'] ) ). '</h4>';
				} elseif(
					( get_post_meta( $order_id , sprintf( '_billing_%s', $btn['cow'] ), true ) !== '' ) && 
					!empty( $btn['label'] ) && 
					$btn['type'] !== 'heading' && 
					$btn['type'] !== 'wooccmupload' && 
					(
						$btn['type'] == 'multiselect' || $btn['type'] == 'multicheckbox'
					)
				) {
					$value = get_post_meta( $order_id , sprintf( '_billing_%s', $btn['cow'] ), true );
					$strings = maybe_unserialize( $value );

					echo '
<p class="form-field form-field-wide form-field-type-' . $btn['type'] . '">
	<strong title="' . sprintf( __( 'ID: %s | Field Type: %s', 'woocommerce-checkout-manager' ), sprintf( '_billing_%s', $btn['cow'] ), __( 'Multi-Select or Multi-Checkbox', 'woocommerce-checkout-manager' ) ) . '">'.wooccm_wpml_string( trim( $btn['label'] ) ).':</strong> ';
					if( !empty( $strings ) ) {
						if( is_array( $strings ) ) {
							$iww = 0;
							$len = count( $strings );
							foreach( $strings as $key ) {
								if( $iww == $len - 1 ) {
									echo wooccm_wpml_string( $key );
								} else {
									echo wooccm_wpml_string( $key ) . ', ';
								}
								$iww++;
							}
						} else {
							echo $strings;
						}
					} else {
						echo '-';
					}
					echo '
</p>
<!-- .form-field-type-multiselect .form-field-type-multicheckbox -->';
				} elseif(
					( get_post_meta( $order_id , sprintf( '_billing_%s', $btn['cow'] ), true ) !== '' ) && 
					$btn['type'] == 'wooccmupload'
				) {
					$attachments = get_post_meta( $order_id, sprintf( '_billing_%s', $btn['cow'] ), true );
					$attachments = ( $attachments !== '' ? explode( ",", $attachments ) : false );
					$btn['label'] = ( !empty( $btn['force_title2'] ) ? $btn['force_title2'] : $btn['label'] );
					echo '
<p class="form-field form-field-wide form-field-type-wooccmupload">
	<strong>'.wooccm_wpml_string( trim( $btn['label'] ) ).':</strong>';
if( empty( $attachments ) ) {
	echo '<br />';
	echo '-';
}
					echo '
</p>' . "\n";
					if( !empty( $attachments ) ) {
						echo '<ul>' . "\n";
						foreach( $attachments as $attachment ) {
							$attachment_url = wp_get_attachment_url( $attachment );
							if( !empty( $attachment_url ) )
								echo '<li><a href="' . $attachment_url . '" target="_blank">' . basename( $attachment_url ) . '</a></li>' . "\n";
						}
						echo '</ul>';
					}
					echo '
<!-- .form-field-type-wooccmupload -->';
				}
			}

		}
	}

}

// Shipping details
function wooccm_admin_edit_order_shipping_details( $order ) {

	global $post;

	$order_id = ( isset( $post->ID ) ? $post->ID : false );

	$options = get_option( 'wccs_settings2' );
	$buttons = ( isset( $options['shipping_buttons'] ) ? $options['shipping_buttons'] : false );
	if( !empty( $buttons ) ) {
		$fields = array(
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
		foreach( $buttons as $btn ) {

			if( !in_array( $btn['cow'], $fields ) ) {
				if(
					( get_post_meta( $order_id , sprintf( '_shipping_%s', $btn['cow'] ), true ) !== '' ) && 
					!empty( $btn['label'] ) && 
					$btn['type'] !== 'wooccmupload' && 
					$btn['type'] !== 'heading' && 
					$btn['type'] !== 'multiselect' && 
					$btn['type'] !== 'multicheckbox'
				) {
					echo '
<p id="shipping_' . $btn['cow'] . '" class="form-field form-field-wide form-field-type-' . $btn['type'] . '">
	<strong title="' . sprintf( __( 'ID: %s | Field Type: %s', 'woocommerce-checkout-manager' ), sprintf( '_shipping_%s', $btn['cow'] ), __( 'Generic', 'woocommerce-checkout-manager' ) ) . '">
	'.wooccm_wpml_string( trim( $btn['label'] ) ).':</strong><br />'.nl2br( get_post_meta( $order_id , sprintf( '_shipping_%s', $btn['cow'] ), true ) ).'
</p>
<!-- .form-field-type-... -->';
				} elseif(
					!empty( $btn['label'] ) && 
					$btn['type'] !== 'multiselect' && 
					$btn['type'] !== 'multicheckbox' && 
					$btn['type'] == 'heading'
				) {
					echo '
<h4>' .wooccm_wpml_string( trim( $btn['label'] ) ). '</h4>';
				} elseif( 
					( get_post_meta( $order_id, sprintf( '_shipping_%s', $btn['cow'] ), true ) !== '' ) && 
					!empty( $btn['label'] ) && 
					$btn['type'] !== 'heading' && 
					$btn['type'] !== 'wooccmupload' && 
					(
						$btn['type'] == 'multiselect' || $btn['type'] == 'multicheckbox'
					)
				) {
					$value = get_post_meta( $order_id, sprintf( '_shipping_%s', $btn['cow'] ), true );
					$strings = maybe_unserialize( $value );
					echo '
<p class="form-field form-field-wide form-field-type-' . $btn['type'] . '">
	<strong title="' . sprintf( __( 'ID: %s | Field Type: %s', 'woocommerce-checkout-manager' ), sprintf( '_shipping_%s', $btn['cow'] ), __( 'Multi-Select or Multi-Checkbox', 'woocommerce-checkout-manager' ) ) . '">'.wooccm_wpml_string( trim( $btn['label'] ) ).':</strong> ';
					if( !empty( $strings ) ) {
						if( is_array( $strings ) ) {
							$iww = 0;
							$len = count( $strings );
							foreach( $strings as $key ) {
								if( $iww == $len - 1 ) {
									echo wooccm_wpml_string($key);
								} else {
									echo wooccm_wpml_string($key).', ';
								}
								$iww++;
							}
						} else {
							echo $strings;
						}
					} else {
						echo '-';
					}
					echo '
</p>
<!-- .form-field-type-multiselect .form-field-type-multicheckbox -->';
				} elseif(
					( get_post_meta( $order_id , sprintf( '_shipping_%s', $btn['cow'] ), true ) !== '' ) && 
					$btn['type'] == 'wooccmupload'
				) {
					$attachments = get_post_meta( $order_id, sprintf( '_shipping_%s', $btn['cow'] ), true );
					$attachments = ( $attachments !== '' ? explode( ",", $attachments ) : false );
					$btn['label'] = ( !empty( $btn['force_title2'] ) ? $btn['force_title2'] : $btn['label'] );
					echo '
<p class="form-field form-field-wide form-field-type-wooccmupload">
	<strong>'.wooccm_wpml_string( trim( $btn['label'] ) ).':</strong>';
if( empty( $attachments ) ) {
	echo '<br />';
	echo '-';
}
					echo '
</p>' . "\n";
					if( !empty( $attachments ) ) {
						echo '<ul>' . "\n";
						foreach( $attachments as $attachment ) {
							$attachment_url = wp_get_attachment_url( $attachment );
							if( !empty( $attachment_url ) )
								echo '<li><a href="' . $attachment_url . '" target="_blank">' . basename( $attachment_url ) . '</a></li>' . "\n";
						}
						echo '</ul>';
					}
					echo '
<!-- .form-field-type-wooccmupload -->';
				}
			}

		}
	}

}

function wooccm_validate_upload_process_customer() {

	$options = get_option( 'wccs_settings' );

	if( !empty($options['checkness']['enable_file_upload'])) {
		return true;
	} else {
		return false;
	}

}

// Check if the customer can upload images
// @mod - This disables the Order Uploaded Files meta box even for Administrators...?
if( wooccm_validate_upload_process_customer() ) {
	add_action( 'woocommerce_view_order', 'wooccm_file_uploader_front_end' );
	add_action( 'add_meta_boxes', 'wooccm_admin_edit_order_metaboxes' );
}

function wooccm_admin_edit_order_metaboxes() {

	global $post;

	$post_type = 'shop_order';
	add_meta_box( 'woocommerce-order-files', __( 'Order Uploaded Files', 'woocommerce-checkout-manager' ), 'wooccm_admin_edit_order_uploaded_files_meta_box', $post_type, 'normal', 'default' );

}

function wooccm_admin_edit_order_uploaded_files_meta_box( $post ) {

	global $wpdb, $thepostid, $theorder, $woocommerce, $post;

	$order_id = ( isset( $post->ID ) ? $post->ID : false );

	$options = get_option( 'wccs_settings' );

	$upload_dir = wp_upload_dir();

	$array = array();
	$default_wccm_values = '';
	$product_image_gallery = '';

	$post_type = 'attachment';
	$args = array(
		'post_type' => $post_type,
		'numberposts' => -1,
		'post_status' => null,
		'post_parent' => $order_id
	);
	$posts = get_posts( $args );
	// wooccm_error_log( 'posts: ' . print_r( $posts, true ) );
	// @mod - Legacy versions were not correctly saving Post Parent so let's do a search if no results were returned
	if( empty( $posts ) ) {

		$meta_keys = array();

		// Additional section
		$options = get_option( 'wccs_settings' );
		$buttons = ( isset( $options['buttons'] ) ? $options['buttons'] : false );
		if( !empty( $buttons ) ) {
			foreach( $buttons as $btn ) {
				if( $btn['type'] == 'wooccmupload' )
					$meta_keys[] = $btn['cow'];
			}
		}
		// Billing section
		$options = get_option( 'wccs_settings3' );
		$buttons = ( isset( $options['billing_buttons'] ) ? $options['billing_buttons'] : false );
		if( !empty( $buttons ) ) {
			foreach( $buttons as $btn ) {
				if( $btn['type'] == 'wooccmupload' )
					$meta_keys[] = sprintf( '_billing_%s', $btn['cow'] );
			}
		}
		// Shipping section
		$options = get_option( 'wccs_settings2' );
		$buttons = ( isset( $options['shipping_buttons'] ) ? $options['shipping_buttons'] : false );
		if( !empty( $buttons ) ) {
			foreach( $buttons as $btn ) {
				if( $btn['type'] == 'wooccmupload' )
					$meta_keys[] = sprintf( '_shipping_%s', $btn['cow'] );
			}
		}

		if( !empty( $meta_keys ) ) {
			$values = array();
			// Check the Order for these meta keys
			foreach( $meta_keys as $meta_key ) {
				$value = get_post_meta( $order_id, $meta_key, true );
				if( !empty( $value ) )
					$array[] = implode( ',', $value );
			}
		}

	}
	// Check if there are any attachments
	if( !empty( $posts ) || !empty( $array ) ) {
		if( !empty( $posts ) ) {
			foreach( $posts as $attachment ) {
				$array[] = $attachment->ID;
			}
		}
		$default_wccm_values = implode( ',', $array );
		$product_image_gallery = implode( ',', $array );
		// wooccm_error_log( 'default_wccm_values: ' . print_r( $default_wccm_values, true ) );
		// wooccm_error_log( 'product_image_gallery: ' . print_r( $product_image_gallery, true ) );
	}

	$attachments = array_filter( explode( ',', $product_image_gallery ) );
	// wooccm_error_log( 'attachments: ' . print_r( $attachments, true ) );
?>

<script type="text/javascript">
jQuery(document).ready(function($) {

	$('#wccm_save_order_submit').click(function() {
		$(".wccm_results").html( "<?php _e( 'Saving, please wait...', 'woocommerce-checkout-manager' ); ?>" );
		var data = {
			action: 'update_attachment_wccm',
			post_id : '<?php echo $post->ID; ?>',
			product_image_gallery : $('#product_image_gallery').val(),
			wccm_default_keys_load : $('#wccm_default_keys_load').val()
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		$.post(ajaxurl, data, function(response) {
			$(".wccm_results").html(response);
		});
	}); 

});
</script>

<?php wp_enqueue_style( 'wccm_upload_file_style', plugins_url( 'includes/templates/admin/edit-order-uploads-file_editing_table.css', WOOCCM_RELPATH ) ); ?>

<div class="woocommerce_order_items_wrapper">
	<table class="woocommerce_order_items back_end">

		<thead>
			<tr>
				<th nowrap>
					<attr title="<?php _e( 'Attachment ID', 'woocommerce-checkout-manager' ); ?>"><?php _e('ID','woocommerce-checkout-manager'); ?></attr>
				</th>
				<th><?php _e( 'Image', 'woocommerce-checkout-manager' ); ?></th>
				<th><?php _e( 'Filename', 'woocommerce-checkout-manager' ); ?></th>
				<th><?php _e( 'Dimensions', 'woocommerce-checkout-manager' ); ?></th>
				<th><?php _e( 'Extension',' woocommerce-checkout-manager' ); ?></th>
				<th class="column-actions"><?php _e( 'Actions', 'woocommerce-checkout-manager' ); ?></th>
			</tr>
		</thead>

		<tbody class="product_images">
<?php
	if( !empty( $attachments ) ) {
		foreach ( $attachments as $attachment_id ) {

			$image_attributes = wp_get_attachment_url( $attachment_id );
			$image_attributes2 = wp_get_attachment_image_src( $attachment_id );
			$filename = basename( $image_attributes );
			$wp_filetype = wp_check_filetype( $filename );

			$value_declear = array_diff( explode( ',',$default_wccm_values ), explode( ',', $attachment_id ) );

			echo '
			<tr class="image wccm_filesli wccmv_' . esc_attr( $attachment_id ) . '">
				<script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery(".wccmx_' . esc_attr( $attachment_id ) . '").click(function(){

						jQuery(".wccmv_' . esc_attr( $attachment_id ) . '").hide();
						jQuery("#product_image_gallery").val(jQuery("#product_image_gallery").val().replace("'.esc_attr( $attachment_id ).'", ""));

					});
				});
				</script>
				<td>' . $attachment_id . '</td>
				<td>' . wp_get_attachment_link( $attachment_id, '' , false, false, wp_get_attachment_image( $attachment_id, array( 75, 75 ), false ) ) . '</td>
				<td>' . wp_get_attachment_link( $attachment_id, '' , false, false, preg_replace( '/\.[^.]+$/', '', $filename ) ) . '</td>
				<td>';
			if( $image_attributes2[1] == '' ) {
				echo '-';
			} else {
				echo $image_attributes2[1] . ' x ' . $image_attributes2[2];
			}
			echo '</td>
				<td>' . strtoupper( $wp_filetype['ext'] ) . '</td>
				<td class="column-actions" nowrap>
					<a href="' . $image_attributes2[0] . '" target="_blank" class="button">' . __( 'Download', 'woocommerce-checkout-manager' ) . '</a>
					<a class="delete tips wccm_delete wccmx_' . esc_attr( $attachment_id ) . ' button" data-tip="' . __( 'Delete file', 'woocommerce-checkout-manager' ) . '">' . __( 'Delete file', 'woocommerce-checkout-manager' ) . '</a>
				</td>
			</tr>
';

		}
	} else {
		echo '
<tr>
	<td colspan="6" style="text-align:left;">' . __( 'No files have been uploaded to this Order.', 'woocommerce-checkout-manager' ) . '</td>
</tr>';
	}
?>
		</tbody>
	</table>
	<input type="hidden" class="wccm_add_to_list" id="product_image_gallery" name="product_image_gallery" value="<?php echo esc_attr( $product_image_gallery ); ?>" />
	<input type="hidden" id="wccm_default_keys_load" name="wccm_default_keys_load" value="<?php echo esc_attr( $default_wccm_values ); ?>" />
	<!-- .woocommerce_order_items -->

	<p class="add_product_images hide-if-no-js">
		<form method="POST" action="">
			<span class="btn button-primary wccm_add_order_link fileinput-button">
				<span><?php _e( 'Add Order Files', 'woocommerce-checkout-manager' ); ?></span>
				<input type="file" name="files_wccm" id="files_wccm" multiple />
				<button type="button" id="files_button_wccm"><?php _e( 'Upload Files!', 'woocommerce-checkout-manager' ); ?></button>
			</span>
		</form>
	</p>

	<!-- Edit Order: File upload -->
	<script type="text/javascript">
	jQuery(document).ready(function($){(

		function post_image_content() {

			var input = document.getElementById("files_wccm"), formdata = false;

			if(window.FormData) {
				formdata = new FormData();
				document.getElementById("files_button_wccm").style.display = "none";
			}

			input.addEventListener("change", function (evt) {

				$(".wccm_results").html("Uploading, please wait....");

				var i = 0, len = this.files.length, img, reader, file;

				for ( ; i < len; i++ ) {
					file = this.files[i];
					if (formdata) {
						formdata.append("files_wccm[]",file); 
					}
				}

				if(formdata) {
					$.ajax({
						url: "<?php echo admin_url('/admin-ajax.php?action=wccs_upload_file_func&order_id='.$order_id.'&name=files_wccm'); ?>",
						type: "POST",
						data: formdata,
						processData: false,
						contentType: false,
						success: function (res) {
							$('#files_wccm').show();
							$(".wccm_results").html("Files uploaded successfully.");
							/* $(".wccm_results").html(res); */

							$.ajax({
								url: '<?php echo admin_url('/post.php?post='.$post->ID.'&action=edit'); ?>',
								data: {},
								success: function (data) {
									$("div#product_images_container").html($(data).find("div#product_images_container"));
									$(".wccm_results").html("Files uploaded successfully.");
									/* $(".wccm_results").html(data); */
								},
								dataType: 'html'
							});
						}
					});
				}
			}, false);
		}

	());

	});
	</script>

	<input type="button" id="wccm_save_order_submit" class="button button-primary" value="<?php _e( 'Save Changes', 'woocommerce-checkout-manager' ); ?>">
	<div class="wccm_results"></div>
	<div class="clear"></div>

</div>
<!-- .woocommerce_order_items_wrapper -->
<?php

}

// front end for user
function wooccm_file_uploader_front_end( $order_id ) {

	global $wpdb, $thepostid, $theorder, $woocommerce, $post;

	$order = new WC_Order( $order_id );

	$options = get_option( 'wccs_settings' );
	$length = ( empty( $options['checkness']['file_upload_number'] ) ) ? 'this.files.length' : $options['checkness']['file_upload_number'];

	$file_types = ( !empty( $options['checkness']['file_types'] ) ? explode( ",", $options['checkness']['file_types'] ) : array() );
	$number_of_types = count( $file_types );

	$prefix = 'wc-';

	if( empty( $options['checkness']['upload_os'] ) || ( $order->post_status == $prefix.$options['checkness']['upload_os'] ) ) {

		$post_type = 'attachment';
		$upload_dir = wp_upload_dir();
		$args = array(
			'post_type' => $post_type,
			'numberposts' => -1,
			'post_status' => null,
			'post_parent' => $order_id
		);
		$default_wccm_values = false;
		$product_image_gallery = false;
		$posts = get_posts( $args );
		// wooccm_error_log( 'posts: ' . print_r( $posts, true ) );
		if( !empty( $posts ) ) {
			foreach( $posts as $attachment )
				$array[] = $attachment->ID;
			$default_wccm_values = implode( ',', $array );
			$product_image_gallery = implode( ',', $array );
		}
		// wooccm_error_log( 'default_wccm_values: ' . print_r( $default_wccm_values, true ) );
		// wooccm_error_log( 'product_image_gallery: ' . print_r( $product_image_gallery, true ) );
		if( !empty( $product_image_gallery ) )
			$attachments = array_filter( explode( ',', $product_image_gallery ) );
		// wooccm_error_log( 'attachments: ' . print_r( $attachments, true ) );

?>

<script type="text/javascript">
jQuery(document).ready(function($) {

	$('#wccm_save_order_submit').click(function() {
		$(".wccm_results").html("Deleting files, please wait....");
		var ajaxurl = '<?php echo admin_url('/admin-ajax.php'); ?>';
			data = {
			action: 'update_attachment_wccm',
			product_image_gallery : $('#product_image_gallery').val(),
			wccm_default_keys_load : $('#wccm_default_keys_load').val()
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		$.post(ajaxurl, data, function(response) {
			$(".wccm_results").html(response);
		});
	});

});
</script>

<?php wp_enqueue_style( 'wccm_upload_file_style', plugins_url( 'includes/templates/admin/edit-order-uploads-file_editing_table.css', WOOCCM_RELPATH ) ); ?>

<h2><?php echo ( empty( $options['checkness']['upload_title'] ) ? 'Order Uploaded Files' : esc_attr( $options['checkness']['upload_title'] ) ); ?></h2>
<div class="woocommerce_order_items_wrapper front_end">
	<table class="woocommerce_order_items front_end">

		<thead>
			<tr>
				<th style="width:15%;text-align: center;"><?php _e( 'Attachment ID', 'woocommerce-checkout-manager' ); ?></th>
				<th style="width:12%"><?php _e( 'Image', 'woocommerce-checkout-manager' ); ?></th>
				<th style="width:30%;text-align: center;"><?php _e( 'Name', 'woocommerce-checkout-manager' ); ?></th>
				<th style="width:12%"><?php _e( 'Dimensions', 'woocommerce-checkout-manager' ); ?></th>
				<th style="width:8%"><?php _e( 'Extension' ,'woocommerce-checkout-manager' ); ?></th>
				<th style="width:10%" class="column-actions"><?php _e( 'Actions', 'woocommerce-checkout-manager' ); ?></th>
			</tr>
		</thead>

		<tbody class="product_images front_end">
<?php
		if( !empty( $attachments ) ) {
			foreach( $attachments as $attachment_id ) {

				$image_attributes = wp_get_attachment_url( $attachment_id );
				$image_attributes2 = wp_get_attachment_image_src( $attachment_id );
				$filename = basename( $image_attributes );
				$wp_filetype = wp_check_filetype( $filename );

				$value_declear = array_diff( explode( ',', $default_wccm_values ), explode( ',', $attachment_id ) );

				echo '
		<tr class="image wccm_filesli wccmv_' . esc_attr( $attachment_id ) . '">

			<td style="display:none;">
				<script type="text/javascript">

					jQuery(document).ready(function(){

					jQuery(".wccmx_' . esc_attr( $attachment_id ) . '").click(function(){
						jQuery(".wccmv_' . esc_attr( $attachment_id ) . '").hide();
						jQuery("#product_image_gallery").val(jQuery("#product_image_gallery").val().replace("'.esc_attr( $attachment_id ).'", ""));

					});
				});

				</script>
			</td>
			<td>'.$attachment_id.'</td>
			<td>'.wp_get_attachment_link( $attachment_id, '' , false, false, wp_get_attachment_image( $attachment_id, array(75,75), true ) ).'</td>
			<td>'.wp_get_attachment_link( $attachment_id, '' , false, false, preg_replace( '/\.[^.]+$/', '', $filename ) ).'</td>
			<td>';
				if( $image_attributes2[1] == '' ) {
					echo '-';
				} else {
					echo $image_attributes2[1].' x '.$image_attributes2[2];
				}
				echo '
			</td>
			<td>' . strtoupper( $wp_filetype['ext'] ) . '</td>
			<td class="column-actions" nowrap>
				<a class="delete tips wccm_delete wccmx_' . esc_attr( $attachment_id ) . ' button" data-tip="' . __( 'Delete file', 'woocommerce-checkout-manager' ) . '">' . __( 'Delete file', 'woocommerce-checkout-manager' ) . '</a>
			</td>
		</tr>';
			}
		} else {
			echo '
		<tr>
			<td colspan="7">' . __( 'No uploaded files have been assigned to this Order.', 'woocommerce-checkout-manager' ) . '</td>
		</tr>
';
		}
?>
		</tbody>
	</table>
	<input type="hidden" class="wccm_add_to_list" id="product_image_gallery" name="product_image_gallery" value="<?php echo ( !empty( $product_image_gallery ) ? esc_attr( $product_image_gallery ) : '' ); ?>" />
	<input type="hidden" id="wccm_default_keys_load" name="wccm_default_keys_load" value="<?php echo ( !empty( $default_wccm_values ) ? esc_attr( $default_wccm_values ) : '' ); ?>" />
	<!-- .woocommerce_order_items -->
</div>
<!-- .woocommerce_order_items_wrapper -->

<button type="button" id="wccm_save_order_submit" class="file_upload_delete wooccm-btn wooccm-btn-danger delete"><?php _e( 'Confirm Delete', 'woocommerce-checkout-manager' ); ?></button>

<span id="wccm_uploader_select">
	<input type="file" style="display:none;" name="files_wccm" id="files_wccm" multiple />
	<button type="button" class="file_upload_account wooccm-btn wooccm-btn-primary start" id="files_button_wccm"><?php _e( 'Upload Files', 'woocommerce-checkout-manager' ); ?></button>
</span>
<!-- #wccm_uploader_select -->

<div class="wccm_results front_end"></div>	

<div class="clear"></div>
<?php
		// script for uploading the files
		echo '
<!-- Edit Order: File upload -->
<script type="text/javascript">
jQuery(document).ready(function($){

	(function post_image_content() {

		var input = document.getElementById("files_wccm"), formdata = false; 

		$("#files_button_wccm").click( function(){
			$("#wccm_uploader_select input[type=file]").click();
			return false;
		});

		if (window.FormData) {
			formdata = new FormData();
		}

		input.addEventListener("change", function (evt) {
			$("#wccm_uploader_select").block({message: null, overlayCSS: {background: "#fff url(" + woocommerce_params.plugin_url + "/assets/images/ajax-loader.gif) no-repeat center", opacity: 0.6}});

			$("#wccm_uploader_select").block({message: null, overlayCSS: {background: "#fff url(" + woocommerce_params.ajax_loader_url + ") no-repeat center", opacity: 0.6}});

			var length = '.$length.';
			var file_array = ' . wooccm_js_array( $file_types ) . ';
			var wooempt = "' . implode( ',', $file_types ) . '";

			for ( i = 0; i < length; i++ ) {
				file = this.files[i];
				for(x=0; x < '.$number_of_types.'; x++){
					if( !wooempt || file.type.match(file_array[x])  ) {
						if (formdata) {
							formdata.append("files_wccm[]",file); 
						}
					}
				}
			}

					if (formdata) {
						$.ajax({
							url: "'.admin_url('/admin-ajax.php?action=wccs_upload_file_func&order_id='.$order_id.'&name=files_wccm').'",
							type: "POST",
							data: formdata,
							processData: false,
							contentType: false,
							success: function (res) {
								$("#files_wccm").show();

							$.ajax({
								url: "'.$order->get_view_order_url().'",
								data: {},
								success: function (data) {
									$("div.woocommerce_order_items_wrapper.front_end").html($(data).find("div.woocommerce_order_items_wrapper.front_end"));
									jQuery("#wccm_uploader_select").unblock();
								},
								dataType: "html"
							});

						}
					});
				}
			}, false);
		}
	());
});
</script>';
// end script
// ------------

	}

}

function wccs_upload_file_func_callback( $order_id ) {

	global $wpdb, $woocommerce, $post; // this is how you get access to the database

	$options = get_option( 'wccs_settings' );

	$order_id = ( isset( $_REQUEST['order_id'] ) ? absint( $_REQUEST['order_id'] ) : false );

	// load files
	require_once( ABSPATH . 'wp-admin/includes/file.php' ); 
	require_once( ABSPATH . 'wp-admin/includes/media.php' );

	$upload_dir = wp_upload_dir();

	$name = ( isset( $_REQUEST['name'] ) ? $_REQUEST['name'] : false );

	if( empty( $name ) ) {
		echo ' '.__('Upload failed. Files were not uploaded.','woocommerce-checkout-manager').'';
		die();
	}

	if( empty( $order_id ) ) {
		echo ' '.__('Invalid Order. Files were not uploaded.','woocommerce-checkout-manager').'';
		die();
	}

	$has_uploads = false;
	$order = new WC_Order( $order_id );

	$files = $_FILES[''. $name .''];
	// $upload_overrides = array( 'test_form' => false );

	if( !empty( $files['name'] ) ) {
		foreach( $files['name'] as $key => $value ) {
			if( $files['name'][$key] ) {

				// using the wp_handle_upload
				if ( empty($options['checkness']['cat_file_upload']) ) {
					$file = array(
						'name'     => $files['name'][$key],
						'type'     => $files['type'][$key],
						'tmp_name' => $files['tmp_name'][$key],
						'error'    => $files['error'][$key],
						'size'     => $files['size'][$key]
					);

					// $movefile = wp_handle_upload($file, $upload_overrides);
					$movefile = wp_handle_upload( $file );

					// Check if the save process failed
					if( isset( $movefile['error'] ) ) {
						echo 'Could not save uploaded file. Files were not uploaded.';
						die();
					}

					$attachment = array(
						'guid' => $movefile['url'], 
						'post_mime_type' => $movefile['type'],
						'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $movefile['file'] ) ),
						'post_content' => '',
						'post_status' => 'inherit',
						'post_parent' => $order_id
					);

					$attach_id = wp_insert_attachment( $attachment, $movefile['url'], $order_id );

					// you must first include the image.php file
					// for the function wp_generate_attachment_metadata() to work

					require_once( ABSPATH . 'wp-admin/includes/image.php' );
					$attach_data = wp_generate_attachment_metadata( $attach_id, $movefile['url'] );
					wp_update_attachment_metadata( $attach_id, $attach_data );

					$has_uploads = true;

				} else {

					// using move_uploaded_file to categorized uploaded images
					if( !file_exists( $upload_dir['basedir'] . '/wooccm_uploads/' . $order_id . '/' ) ) {
						wp_mkdir_p( $upload_dir['basedir'] . '/wooccm_uploads/' . $order_id . '/' );
					}

					$filename = $files['name'][$key];
					$wp_filetype = wp_check_filetype( $filename );
					$URLpath = $upload_dir['baseurl'] . '/wooccm_uploads/' . $order_id . '/' . $filename;

					move_uploaded_file( $files["tmp_name"][$key], $upload_dir['basedir'] . '/wooccm_uploads/' . $order_id . '/' . $filename);

					$attachment = array(
						'guid' => $URLpath, 
						'post_mime_type' => $wp_filetype['type'],
						'post_title' => preg_replace( '/\.[^.]+$/', '', $filename),
						'post_content' => '',
						'post_status' => 'inherit'
					);

					$attach_id = wp_insert_attachment( $attachment, $URLpath, $order_id);

					// you must first include the image.php file
					// for the function wp_generate_attachment_metadata() to work

					require_once( ABSPATH . 'wp-admin/includes/image.php' );
					$attach_data = wp_generate_attachment_metadata( $attach_id, $URLpath );
					wp_update_attachment_metadata( $attach_id, $attach_data );

					$has_uploads = true;

				}
			}
		}
		if( $has_uploads ) {

			// send email
			$email_recipients = $options['checkness']['wooccm_notification_email'];
			if( empty( $email_recipients ) )
				$email_recipients = get_option( 'admin_email' );
			$email_heading = __( 'Files Uploaded by Customer', 'woocommerce-checkout-manager' );
			$subject = sprintf( __( 'WooCommerce Checkout Manager - %s [%s]', 'woocommerce-checkout-manager' ), $email_heading, $order->billing_first_name . ' ' . $order->billing_last_name );

			$mailer = WC()->mailer();

			// Buffer
			ob_start();
?>
<p>This is an automatic message from WooCommerce Checkout Manager, reporting that files have been uploaded by <?php echo $order->billing_first_name; ?> <?php echo $order->billing_last_name; ?>.</p>
<h3>Customer Details</h3>
<ul>
	<li>Name: <?php echo $order->billing_first_name; ?> <?php $order->billing_last_name; ?></li>
	<li>E-mail: <?php echo $order->billing_email; ?></li>
	<li>Order Number: <?php echo $order_id; ?></li>
</ul>
<p>You can view the files and order details via back-end by following this <a href="<?php echo admin_url( '/post.php?post='.$order_id.'&action=edit' ); ?>" target="_blank">link</a>.</p>
<?php
			// Get contents
			$message = ob_get_clean();

			$message = $mailer->wrap_message( $email_heading, $message );

			// add_filter( 'wp_mail_content_type', 'wooccm_set_html_content_type' );
			// wc_mail( $email_recipients, $subject, $message );
			$mailer->send( $email_recipients, strip_tags( $subject ), $message );
			// remove_filter( 'wp_mail_content_type', 'wooccm_set_html_content_type' );

		}
		echo ' '.__('Files were uploaded successfully.','woocommerce-checkout-manager').'';
	} else {
		echo ' '.__('No uploads were recognised. Files were not uploaded.','woocommerce-checkout-manager').'';
	}
	die();

}
add_action("wp_ajax_wccs_upload_file_func", "wccs_upload_file_func_callback");
add_action("wp_ajax_nopriv_wccs_upload_file_func", "wccs_upload_file_func_callback");

function wooccm_set_html_content_type() {

	return 'text/html';

}

function wooccm_js_str( $s ) {

	return '"' . addcslashes($s, "\0..\37\"\\") . '"';

}

function wooccm_js_array( $array ) {

	$temp = array_map( 'wooccm_js_str', $array );
	return '[' . implode(',', $temp) . ']';

}

function update_attachment_wccm_callback() {

	global $post, $wpdb, $woocommerce;

	$array1 = explode( ',', sanitize_text_field( isset( $_POST['wccm_default_keys_load'] ) ? $_POST['wccm_default_keys_load'] : '' ) );
	$array2 = explode( ',', sanitize_text_field( isset( $_POST['product_image_gallery'] ) ? $_POST['product_image_gallery'] : '' ) );
	$attachment_ids = array_diff( $array1, $array2 );

	if( isset( $_POST['wccm_default_keys_load'] ) ) {
		if( !empty( $attachment_ids ) ) {
			foreach( $attachment_ids as $key => $values ) {
				wp_delete_attachment( $attachment_ids[$key] );
			}
		}
		echo __('Deleted successfully.','woocommerce-checkout-manager');
	}
	die();

}
add_action( 'wp_ajax_update_attachment_wccm', 'update_attachment_wccm_callback' );
add_action( 'wp_ajax_nopriv_update_attachment_wccm', 'update_attachment_wccm_callback' );
?>