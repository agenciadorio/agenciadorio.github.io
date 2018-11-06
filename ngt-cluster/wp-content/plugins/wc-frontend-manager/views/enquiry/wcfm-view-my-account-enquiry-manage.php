<?php
/**
 * WCFM plugin view
 *
 * wcfm Inquiry Manage View
 *
 * @author 		WC Lovers
 * @package 	wcfmu/views/inquiry
 * @version   5.0.8
 */
 
global $wp, $WCFM, $WCFMu, $wpdb;

if( !apply_filters( 'wcfm_is_pref_inquiry', true ) || !apply_filters( 'wcfm_is_allow_inquiry', true ) || !apply_filters( 'wcfm_is_allow_manage_inquiry', true ) ) {
	wcfm_restriction_message_show( "Manage Inquiry" );
	return;
}

$inquiry_id = 0;
$inquiry_content = '';
$inquiry_product_id = 0;
$inquiry_vendor_id = 0;
$inquiry_customer_id = 0;
$inquiry_customer_name = 0;
$inquiry_customer_email = 0;

$wcfm_myac_modified_endpoints = get_option( 'wcfm_myac_endpoints', array() );
$wcfm_myaccount_inquiry_endpoint = ! empty( $wcfm_myac_modified_endpoints['inquiry'] ) ? $wcfm_myac_modified_endpoints['inquiry'] : 'inquiry';
$wcfm_myaccount_view_inquiry_endpoint = ! empty( $wcfm_myac_modified_endpoints['view-inquiry'] ) ? $wcfm_myac_modified_endpoints['view-inquiry'] : 'view-inquiry';

if( isset( $wp->query_vars[$wcfm_myaccount_view_inquiry_endpoint] ) && !empty( $wp->query_vars[$wcfm_myaccount_view_inquiry_endpoint] ) ) {
	$inquiry_id = absint( $wp->query_vars[$wcfm_myaccount_view_inquiry_endpoint] );
	$inquiry_post = $wpdb->get_row( "SELECT * from {$wpdb->prefix}wcfm_enquiries WHERE `ID` = " . $inquiry_id );
	// Fetching Inquiry Data
	if($inquiry_post && !empty($inquiry_post)) {
		$inquiry_content = $inquiry_post->enquiry;
		$inquiry_product_id = $inquiry_post->product_id;
		$inquiry_vendor_id = $inquiry_post->vendor_id;
		$inquiry_customer_id = $inquiry_post->customer_id;
		$inquiry_customer_name = $inquiry_post->customer_name;
		$inquiry_customer_email = $inquiry_post->customer_email;
		$customer_id = get_current_user_id();
		if( $inquiry_customer_id != $customer_id ) {
			wcfm_restriction_message_show( "Inquiry Not Found" );
			return;
		}
	} else {
		wcfm_restriction_message_show( "Inquiry Not Found" );
		return;
	}
}
$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
if ( $myaccount_page_id ) {
	$myaccount_page_url = get_permalink( $myaccount_page_id );
}

do_action( 'before_my_account_wcfm_inquiry_manage' );

?>

<div id="wcfm-main-contentainer">
	<div class="collapse wcfm-collapse">
		<div class="wcfm-collapse-content">
			<div class="wcfm-container wcfm-top-element-container">
				<h2><?php echo __( 'Inquiry', 'wc-frontend-manager' ) . ' #' . sprintf( '%06u', $inquiry_id ); ?></h2>
				
				<?php
				echo '<a id="add_new_inquiry_dashboard" class="add_new_wcfm_ele_dashboard text_tip" href="' . $myaccount_page_url . $wcfm_myaccount_inquiry_endpoint. '" data-tip="' . __('Inquiries', 'wc-frontend-manager') . '"><span class="fa fa-question-circle fa-question-circle-o"></span><span class="text">' . __( 'Inquiries', 'wc-frontend-manager') . '</span></a>';
				?>
				<div class="wcfm-clearfix"></div>
			</div>
			<div class="wcfm-clearfix"></div><br />
			
			<?php do_action( 'begin_my_account_wcfm_inquiry_manage_form' ); ?>
			
			<!-- collapsible -->
			<div class="wcfm-container">
				<div id="inquiry_manage_general_expander" class="wcfm-content">
					<div class="inquiry_content">
						<?php echo $inquiry_content; ?>
					</div>
					<div class="inquiry_info">
						<div class="inquiry_date"><span class="fa fa-clock-o"></span>&nbsp;<?php echo date_i18n( wc_date_format(), strtotime( $inquiry_post->posted ) ); ?></div>
					</div>
					<div class="wcfm_clearfix"></div>
				</div>
			</div>
			<div class="wcfm_clearfix"></div><br />
			<!-- end collapsible -->
			
			<?php 
			if( $wcfm_is_allow_view_inquiry_reply_view = apply_filters( 'wcfmcap_is_allow_inquiry_reply_view', true ) ) {
				$wcfm_inquiry_replies = $wpdb->get_results( "SELECT * from {$wpdb->prefix}wcfm_enquiries_response WHERE `enquiry_id` = " . $inquiry_id );
				
				echo '<h2>' . __( 'Replies', 'wc-frontend-manager' ) . ' (' . count( $wcfm_inquiry_replies ) . ')</h2><div class="wcfm_clearfix"></div>';
				
				if( !empty( $wcfm_inquiry_replies ) ) {
					foreach( $wcfm_inquiry_replies as $wcfm_inquiry_reply ) {
					?>
					<!-- collapsible -->
					<div class="wcfm-container">
						<div id="inquiry_reply_<?php echo $wcfm_inquiry_reply->ID; ?>" class="inquiry_reply wcfm-content">
							<div class="inquiry_reply_author">
								<?php
								$author_id = $wcfm_inquiry_reply->reply_by;
								$wp_user_avatar_id = get_user_meta( $author_id, 'wp_user_avatar', true );
								$wp_user_avatar = wp_get_attachment_url( $wp_user_avatar_id );
								if ( !$wp_user_avatar ) {
									$wp_user_avatar = $WCFM->plugin_url . 'assets/images/user.png';
								}
								?>
								<img src="<?php echo $wp_user_avatar; ?>" /><br />
								<?php
								$userdata = get_userdata( $author_id );
								$first_name = $userdata->first_name;
								$last_name  = $userdata->last_name;
								$display_name  = $userdata->display_name;
								if( $first_name ) {
									echo $first_name . ' ' . $last_name;
								} else {
									echo $display_name;
								}
								?>
								<br /><?php echo date_i18n( wc_date_format(), strtotime( $wcfm_inquiry_reply->posted ) ); ?>
							</div>
							<div class="inquiry_reply_content">
								<?php echo $wcfm_inquiry_reply->reply; ?>
							</div>
						</div>
					</div>
					<div class="wcfm_clearfix"></div><br />
					<!-- end collapsible -->
					<?php
					}
				}
			} 
			?>
			
			<?php if( $wcfm_is_allow_view_inquiry_reply = apply_filters( 'wcfmcap_is_allow_inquiry_reply', true ) ) { ?>
				<?php do_action( 'before_wcfm_inquiry_reply_form' ); ?>
				<form id="wcfm_inquiry_reply_form" class="wcfm">
					<h2><?php _e('New Reply', 'wc-frontend-manager' ); ?></h2>
					<div class="wcfm-clearfix"></div>
					<div class="wcfm-container">
						<div id="wcfm_new_reply_listing_expander" class="wcfm-content">
							<?php
							$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_enquiry_reply_fields', array(
																																																			"inquiry_reply"           => array( 'type' => 'wpeditor', 'class' => 'wcfm-textarea wcfm_ele wcfm_wpeditor', 'label_class' => 'wcfm_title', 'media_buttons' => false ),
																																																			"inquiry_reply_beak"      => array( 'type' => 'html', 'value' => '<div class="wcfm-clearfix" style="margin-bottom: 15px;"></div>' ),
																																																			"inquiry_id"              => array( 'type' => 'hidden', 'value' => $inquiry_id ),
																																																			"inquiry_product_id"      => array( 'type' => 'hidden', 'value' => $inquiry_product_id ),
																																																			"inquiry_vendor_id"       => array( 'type' => 'hidden', 'value' => $inquiry_vendor_id ),
																																																			"inquiry_customer_id"     => array( 'type' => 'hidden', 'value' => $inquiry_customer_id ),
																																																			"inquiry_customer_name"   => array( 'type' => 'hidden', 'value' => $inquiry_customer_name ),
																																																			"inquiry_customer_email"  => array( 'type' => 'hidden', 'value' => $inquiry_customer_email )
																																																			) ) );
							?>
							<div class="wcfm-clearfix"></div>
							<div class="wcfm-message" tabindex="-1"></div>
							<div class="wcfm-clearfix"></div>
							<div id="wcfm_inquiry_reply_submit">
								<input type="submit" name="save-data" value="<?php _e( 'Send', 'wc-frontend-manager' ); ?>" id="wcfm_inquiry_reply_send_button" class="wcfm_submit_button" />
							</div>
							<div class="wcfm-clearfix"></div>
						</div>
					</div>
				</form>
				<?php do_action( 'after_wcfm_inquiry_reply_form' ); ?>
				<div class="wcfm-clearfix"></div><br />
			<?php } ?>
			
			<?php do_action( 'end_my_account_wcfm_inquiry_manage_form' ); ?>
			
			<?php
			do_action( 'after_my_account_wcfm_inquiry_manage' );
			?>
		</div>
	</div>
</div>