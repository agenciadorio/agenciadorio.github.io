<?php
/**
 * WCFM plugin controllers
 *
 * Plugin Enquiry Manage Controller
 *
 * @author 		WC Lovers
 * @package 	wcfm/controllers/enquiry
 * @version   3.2.8
 */

class WCFM_Enquiry_Manage_Controller {
	
	public function __construct() {
		global $WCFM;
		
		$this->processing();
	}
	
	public function processing() {
		global $WCFM, $wpdb;
		
		$wcfm_enquiry_reply_form_data = array();
	  parse_str($_POST['wcfm_inquiry_reply_form'], $wcfm_enquiry_reply_form_data);
	  
	  $wcfm_enquiry_messages = get_wcfm_enquiry_manage_messages();
	  $has_error = false;
	  
	  if(isset($_POST['inquiry_reply']) && !empty($_POST['inquiry_reply'])) {
	  	
	  	// WCFM form custom validation filter
			$custom_validation_results = apply_filters( 'wcfm_form_custom_validation', $wcfm_enquiry_reply_form_data, 'enquiry_manage' );
			if(isset($custom_validation_results['has_error']) && !empty($custom_validation_results['has_error'])) {
				$custom_validation_error = __( 'There has some error in submitted data.', 'wc-frontend-manager' );
				if( isset( $custom_validation_results['message'] ) && !empty( $custom_validation_results['message'] ) ) { $custom_validation_error = $custom_validation_results['message']; }
				echo '{"status": false, "message": "' . $custom_validation_error . '"}';
				die;
			}
	  	
	  	$inquiry_reply           = apply_filters( 'wcfm_editor_content_before_save', stripslashes( html_entity_decode( $_POST['inquiry_reply'], ENT_QUOTES, 'UTF-8' ) ) );
	  	$inquiry_reply_by        = apply_filters( 'wcfm_message_author', get_current_user_id() );
	  	$inquiry_id              = absint( $wcfm_enquiry_reply_form_data['inquiry_id'] );
	  	$inquiry_product_id      = absint( $wcfm_enquiry_reply_form_data['inquiry_product_id'] );
	  	$inquiry_vendor_id       = absint( $wcfm_enquiry_reply_form_data['inquiry_vendor_id'] );
	  	$inquiry_customer_id     = absint( $wcfm_enquiry_reply_form_data['inquiry_customer_id'] );
	  	$inquiry_customer_name   = $wcfm_enquiry_reply_form_data['inquiry_customer_name'];
	  	$inquiry_customer_email  = $wcfm_enquiry_reply_form_data['inquiry_customer_email'];
	  	
	  	if( !defined( 'DOING_WCFM_EMAIL' ) ) 
	  		define( 'DOING_WCFM_EMAIL', true );
	  	
			$wcfm_create_enquiry_reply = "INSERT into {$wpdb->prefix}wcfm_enquiries_response 
																	(`reply`, `enquiry_id`, `product_id`, `vendor_id`, `customer_id`, `customer_name`, `customer_email`, `reply_by`)
																	VALUES
																	('{$inquiry_reply}', {$inquiry_id}, {$inquiry_product_id}, {$inquiry_vendor_id}, {$inquiry_customer_id}, '{$inquiry_customer_name}', '{$inquiry_customer_email}', {$inquiry_reply_by})";
													
			$wpdb->query($wcfm_create_enquiry_reply);
			$enquiry_reply_id = $wpdb->insert_id;
		
			if(isset($wcfm_enquiry_reply_form_data['inquiry_stick']) && !empty($wcfm_enquiry_reply_form_data['inquiry_stick'])) {
				$replied = date('Y-m-d H:i:s');
				
				$wcfm_update_enquiry    = "UPDATE {$wpdb->prefix}wcfm_enquiries 
																	SET 
																	`reply` = '{$inquiry_reply}',
																	`reply_by` = {$inquiry_reply_by},
																	`is_private` = 0, 
																	`replied` = '{$replied}'
																	WHERE 
																	`ID` = {$inquiry_id}";
																
				$wpdb->query($wcfm_update_enquiry);
			}
		
			// Send mail to customer
			if( $inquiry_customer_email ) {
				$enquiry_for_label =  __( 'Store', 'wc-frontend-manager' );
				if( $inquiry_vendor_id ) $enquiry_for_label = $WCFM->wcfm_vendor_support->wcfm_get_vendor_store_name_by_vendor( $inquiry_vendor_id ) . ' ' . __( 'Store', 'wc-frontend-manager' );
				if( $inquiry_product_id ) $enquiry_for_label = get_the_title( $inquiry_product_id );
				
				$enquiry_for =  __( 'Store', 'wc-frontend-manager' );
				if( $inquiry_vendor_id ) $enquiry_for = $WCFM->wcfm_vendor_support->wcfm_get_vendor_store_by_vendor( $inquiry_vendor_id );
				if( $inquiry_product_id ) $enquiry_for = '<a target="_blank" class="wcfm_dashboard_item_title" href="' . get_permalink( $inquiry_product_id ) . '">' . get_the_title( $inquiry_product_id ) . '</a>';
				
				$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
				$myaccount_page_url = '';
				if ( $myaccount_page_id ) {
					$myaccount_page_url = get_permalink( $myaccount_page_id );
				}
				$wcfm_myac_modified_endpoints = get_option( 'wcfm_myac_endpoints', array() );
				$wcfm_myaccount_view_inquiry_endpoint = ! empty( $wcfm_myac_modified_endpoints['view-inquiry'] ) ? $wcfm_myac_modified_endpoints['view-inquiry'] : 'view-inquiry';
				$enquiry_url = $myaccount_page_url .$wcfm_myaccount_view_inquiry_endpoint.'/' . $inquiry_id;
			
			
				if( !defined( 'DOING_WCFM_EMAIL' ) ) 
					define( 'DOING_WCFM_EMAIL', true );
				
				$reply_mail_subject = "{site_name}: " . __( "Reply for your Inquiry", "wc-frontend-manager" ) . " - {enquiry_for}";
				$reply_mail_body    =    '<br/>' . __( 'Hi', 'wc-frontend-manager' ) . ' {first_name}' .
																 ',<br/><br/>' . 
																 sprintf( __( 'We recently have a enquiry from you regarding "%s". Please check below for our input for the same: ', 'wc-frontend-manager' ), '{enquiry_for}' ) .
																 '<br/><br/><strong><i>' . 
																 '"{inquiry_reply}"' . 
																 '</i></strong><br/><br/>' .
																 sprintf( __( 'Check more details %shere%s.', 'wc-frontend-manager' ), '<a href="{enquiry_url}">', '</a>' ) .
																 '<br /><br/>' . __( 'Thank You', 'wc-frontend-manager' ) .
																 '<br/><br/>';
																 
				$subject = str_replace( '{site_name}', get_bloginfo( 'name' ), $reply_mail_subject );
				$subject = str_replace( '{enquiry_for}', $enquiry_for_label, $subject );
				$message = str_replace( '{enquiry_for}', $enquiry_for, $reply_mail_body );
				$message = str_replace( '{first_name}', $inquiry_customer_name, $message );
				$message = str_replace( '{enquiry_url}', $enquiry_url, $message );
				$message = str_replace( '{inquiry_reply}', $inquiry_reply, $message );
				$message = apply_filters( 'wcfm_email_content_wrapper', $message, __( 'Inquiry Reply', 'wc-frontend-manager' ) );
				
				wp_mail( $inquiry_customer_email, $subject, $message );
			}
			
			// Admin Direct message
			if( wcfm_is_vendor() ) {
				$wcfm_messages = sprintf( __( 'New reply posted for Inquiry <b>%s</b>', 'wc-frontend-manager' ), '<a target="_blank" class="wcfm_dashboard_item_title" href="' . get_wcfm_enquiry_manage_url( $inquiry_id ) . '">#' . sprintf( '%06u', $inquiry_id ) . '</a>' );
				$WCFM->wcfm_notification->wcfm_send_direct_message( $inquiry_vendor_id, 0, 0, 1, $wcfm_messages, 'enquiry', false );
			}
				
			echo '{"status": true, "message": "' . $wcfm_enquiry_messages['enquiry_reply_saved'] . '", "redirect": "' . get_wcfm_enquiry_manage_url( $inquiry_id ) . '#inquiry_reply_' . $enquiry_reply_id . '"}';
		} else {
			echo '{"status": false, "message": "' . $wcfm_enquiry_messages['no_reply'] . '"}';
		}
		
		die;
	}
}

class WCFM_My_Account_Enquiry_Manage_Controller {
	
	public function __construct() {
		global $WCFM, $WCFMu;
		
		$this->processing();
	}
	
	public function processing() {
		global $WCFM, $WCFMu, $wpdb, $_POST;
		
		$wcfm_enquiry_reply_form_data = array();
	  parse_str($_POST['wcfm_inquiry_reply_form'], $wcfm_enquiry_reply_form_data);
	  
	  $wcfm_enquiry_messages = get_wcfm_enquiry_manage_messages();
	  $has_error = false;
	  
	  if(isset($_POST['inquiry_reply']) && !empty($_POST['inquiry_reply'])) {
	  	
	  	$inquiry_reply           =  apply_filters( 'wcfm_editor_content_before_save', stripslashes( html_entity_decode( $_POST['inquiry_reply'], ENT_QUOTES, 'UTF-8' ) ) );
	  	$inquiry_reply_by        = apply_filters( 'wcfm_message_author', get_current_user_id() );
	  	$inquiry_id              = absint( $wcfm_enquiry_reply_form_data['inquiry_id'] );
	  	$inquiry_product_id      = absint( $wcfm_enquiry_reply_form_data['inquiry_product_id'] );
	  	$inquiry_vendor_id       = absint( $wcfm_enquiry_reply_form_data['inquiry_vendor_id'] );
	  	$inquiry_customer_id     = absint( $wcfm_enquiry_reply_form_data['inquiry_customer_id'] );
	  	$inquiry_customer_name   = $wcfm_enquiry_reply_form_data['inquiry_customer_name'];
	  	$inquiry_customer_email  = $wcfm_enquiry_reply_form_data['inquiry_customer_email'];
	  	
	  	$wcfm_myac_modified_endpoints = get_option( 'wcfm_myac_endpoints', array() );
	  	$wcfm_myaccount_view_inquiry_endpoint = ! empty( $wcfm_myac_modified_endpoints['view-inquiry'] ) ? $wcfm_myac_modified_endpoints['view-inquiry'] : 'view-inquiry';
	  	
	  	if( !defined( 'DOING_WCFM_EMAIL' ) ) 
	  		define( 'DOING_WCFM_EMAIL', true );
	  	
	  	$wcfm_create_enquiry_reply = "INSERT into {$wpdb->prefix}wcfm_enquiries_response 
																	(`reply`, `enquiry_id`, `product_id`, `vendor_id`, `customer_id`, `customer_name`, `customer_email`, `reply_by`)
																	VALUES
																	('{$inquiry_reply}', {$inquiry_id}, {$inquiry_product_id}, {$inquiry_vendor_id}, {$inquiry_customer_id}, '{$inquiry_customer_name}', '{$inquiry_customer_email}', {$inquiry_reply_by})";
													
			$wpdb->query($wcfm_create_enquiry_reply);
			$enquiry_reply_id = $wpdb->insert_id;
			
			// Send mail to admin
			$mail_to = get_bloginfo( 'admin_email' );
			$reply_mail_subject = '{site_name}: ' . __( 'Inquiry Reply', 'wc-frontend-manager' ) . ' - ' . __( 'Inquiry', 'wc-frontend-manager' ) . ' #{enquiry_id}';
			$reply_mail_body =   '<br/>' . __( 'Hi', 'wc-frontend-manager' ) .
													 ',<br/><br/>' . 
													 __( 'You have received reply for your "{product_title}" inquiry. Please check below for the details: ', 'wc-frontend-manager' ) .
													 '<br/><br/><strong><i>' . 
													 '"{enquiry_reply}"' . 
													 '</i></strong><br/><br/>' .
													 __( 'Check more details here', 'wc-frontend-manager' ) . ': <a href="{support_url}">' . __( 'Inquiry', 'wc-frontend-manager' ) . ' #{enquiry_id}</a>' .
													 '<br /><br/>' . __( 'Thank You', 'wc-frontend-manager' ) .
													 '<br/><br/>';
			
			$headers[] = 'From: [' . get_bloginfo( 'name' ) . '] ' . __( 'Inquiry Reply', 'wc-frontend-manager' );
			$headers[] = 'Cc: ' . $mail_to;
			$subject = str_replace( '{site_name}', get_bloginfo( 'name' ), $reply_mail_subject );
			$subject = str_replace( '{enquiry_id}', sprintf( '%06u', $inquiry_id ), $subject );
			$message = str_replace( '{product_title}', get_the_title( $inquiry_product_id ), $reply_mail_body );
			$message = str_replace( '{support_url}', get_wcfm_enquiry_manage_url( $inquiry_id ), $message );
			$message = str_replace( '{enquiry_reply}', $inquiry_reply, $message );
			$message = str_replace( '{enquiry_id}', sprintf( '%06u', $inquiry_id ), $message );
			$message = apply_filters( 'wcfm_email_content_wrapper', $message, __( 'Reply to Inquiry', 'wc-frontend-manager' ) . ' #' . sprintf( '%06u', $inquiry_id ) );
			
			wp_mail( $mail_to, $subject, $message, $headers );
			
			// Direct message
			$wcfm_messages = sprintf( __( 'New reply received for Inquiry <b>%s</b>', 'wc-frontend-manager' ), '<a target="_blank" class="wcfm_dashboard_item_title" href="' . get_wcfm_enquiry_manage_url( $inquiry_id ) . '">#' . sprintf( '%06u', $inquiry_id ) . '</a>' );
			$WCFM->wcfm_notification->wcfm_send_direct_message( -2, 0, 1, 0, $wcfm_messages, 'enquiry', false );
			
			// Semd email to vendor
			if( wcfm_is_marketplace() ) {
				if( $inquiry_vendor_id ) {
					$is_allow_enquiry = $WCFM->wcfm_vendor_support->wcfm_vendor_has_capability( $inquiry_vendor_id, 'enquiry' );
					if( $is_allow_enquiry && apply_filters( 'wcfm_is_allow_enquiry_vendor_notification', true ) ) {
						$vendor_email = $WCFM->wcfm_vendor_support->wcfm_get_vendor_email_from_product( $inquiry_product_id );
						if( $vendor_email ) {
							wp_mail( $vendor_email, $subject, $message, $headers );
						}
						
						// Direct message
						$wcfm_messages = sprintf( __( 'New reply received for Inquiry <b>%s</b>', 'wc-frontend-manager' ), '<a target="_blank" class="wcfm_dashboard_item_title" href="' . get_wcfm_enquiry_manage_url( $inquiry_id ) . '">#' . sprintf( '%06u', $inquiry_id ) . '</a>' );
						$WCFM->wcfm_notification->wcfm_send_direct_message( -1, $inquiry_vendor_id, 1, 0, $wcfm_messages, 'enquiry', false );
					}
				}
	  	}
			
	  	$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
	  	$myaccount_page_url = '';
			if ( $myaccount_page_id ) {
				$myaccount_page_url = get_permalink( $myaccount_page_id );
			}
			echo '{"status": true, "message": "' . $wcfm_enquiry_messages['enquiry_reply_saved'] . '", "redirect": "' . $myaccount_page_url .$wcfm_myaccount_view_inquiry_endpoint.'/' . $inquiry_id . '#inquiry_reply_' . $enquiry_reply_id . '"}';
		} else {
			echo '{"status": false, "message": "' . $wcfm_enquiry_messages['no_reply'] . '"}';
		}
		
		die;
	}
}