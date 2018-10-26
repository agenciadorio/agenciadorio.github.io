<?php
/**
 * WCFM plugin controllers
 *
 * Plugin Enquiry Tab Controller
 *
 * @author 		WC Lovers
 * @package 	wcfm/controllers/enquiry
 * @version   3.2.8
 */

class WCFM_Enquiry_Tab_Controller {
	
	public function __construct() {
		global $WCFM;
		
		$this->processing();
	}
	
	public function processing() {
		global $WCFM, $wpdb;
		
		$wcfm_enquiry_tab_form_data = array();
	  parse_str($_POST['wcfm_enquiry_tab_form'], $wcfm_enquiry_tab_form_data);
	  
	  $wcfm_enquiry_messages = get_wcfm_enquiry_manage_messages();
	  $has_error = false;
	  
	  // Google reCaptcha support
	  if ( function_exists( 'gglcptch_init' ) ) {
			if(isset($wcfm_enquiry_tab_form_data['g-recaptcha-response']) && !empty($wcfm_enquiry_tab_form_data['g-recaptcha-response'])) {
				$_POST['g-recaptcha-response'] = $wcfm_enquiry_tab_form_data['g-recaptcha-response'];
			}
			$check_result = apply_filters( 'gglcptch_verify_recaptcha', true, 'string', 'wcfm_enquiry_form' );
			if ( true === $check_result ) {
					/* do necessary action */
			} else { 
				echo '{"status": false, "message": "' . $check_result . '"}';
				die;
			}
		}
	  
	  if(isset($wcfm_enquiry_tab_form_data['enquiry']) && !empty($wcfm_enquiry_tab_form_data['enquiry'])) {
	  	
	  	$enquiry = $wcfm_enquiry_tab_form_data['enquiry'];
	  	$reply = '';
	  	$product_id = $wcfm_enquiry_tab_form_data['product_id'];
	  	
	  	$product_post = get_post( $product_id );
	  	$author_id = $product_post->post_author;
	  	
	  	$vendor_id = 0;
	  	if( wcfm_is_marketplace() ) {
	  		$vendor_id = $WCFM->wcfm_vendor_support->wcfm_get_vendor_id_from_product( $product_id );
	  	}
	  	
	  	if( !is_user_logged_in() ) {
	  		$customer_id = 0;
	  		$customer_name = $wcfm_enquiry_tab_form_data['customer_name'];
	  		$customer_email = $wcfm_enquiry_tab_form_data['customer_email'];
	  	} else {
	  		$customer_id = get_current_user_id();
	  		$userdata = get_userdata( $customer_id );
				$first_name = $userdata->first_name;
				$last_name  = $userdata->last_name;
				$display_name  = $userdata->display_name;
				if( $first_name ) {
					$customer_name = $first_name . ' ' . $last_name;
				} else {
					$customer_name = $display_name;
				}
	  		$customer_email = $userdata->user_email;
	  	}
	  	
	  	define( 'DOING_WCFM_EMAIL', true );
	  	
	  	$reply_by = 0;
	  	$is_private = 0;
	  	$replied = date('Y-m-d H:i:s');
	  	
	  	$wcfm_create_enquiry    = "INSERT into {$wpdb->prefix}wcfm_enquiries 
																(`enquiry`, `reply`, `author_id`, `product_id`, `vendor_id`, `customer_id`, `customer_name`, `customer_email`, `reply_by`, `is_private`, `replied`)
																VALUES
																('{$enquiry}', '{$reply}', {$author_id}, {$product_id}, {$vendor_id}, {$customer_id}, '{$customer_name}', '{$customer_email}', {$reply_by}, {$is_private}, '{$replied}')";
															
			$wpdb->query($wcfm_create_enquiry);
			$enquiry_id = $wpdb->insert_id;
			
			$additional_info = '';
			$wcfm_options = get_option( 'wcfm_options', array() );
			$wcfm_enquiry_custom_fields = isset( $wcfm_options['wcfm_enquiry_custom_fields'] ) ? $wcfm_options['wcfm_enquiry_custom_fields'] : array();
			$wcfm_enquiry_meta_values = $wcfm_enquiry_tab_form_data['wcfm_enquiry_meta'];
			if( !empty( $wcfm_enquiry_custom_fields ) && !empty( $wcfm_enquiry_meta_values ) ) {
				foreach( $wcfm_enquiry_custom_fields as $wcfm_enquiry_custom_field ) {
					if( !isset( $wcfm_enquiry_custom_field['enable'] ) ) continue;
					if( !$wcfm_enquiry_custom_field['label'] ) continue;
					$wcfm_enquiry_custom_field['name'] = sanitize_title( $wcfm_enquiry_custom_field['label'] );
					if( isset( $wcfm_enquiry_meta_values[ $wcfm_enquiry_custom_field['name'] ] ) ) {
						$wcfm_create_enquiry_meta    = "INSERT into {$wpdb->prefix}wcfm_enquiries_meta 
																						(`enquiry_id`, `key`, `value`)
																						VALUES
																						({$enquiry_id}, '{$wcfm_enquiry_custom_field['label']}', '{$wcfm_enquiry_meta_values[ $wcfm_enquiry_custom_field['name'] ]}')";
						$wpdb->query($wcfm_create_enquiry_meta);
						$additional_info .= '<tr><td>' . __( $wcfm_enquiry_custom_field['label'], 'wc-frontend-manager' ) . '</td><td>' . $wcfm_enquiry_meta_values[ $wcfm_enquiry_custom_field['name'] ] . '</td>';
					}
				}
			}
			if( $additional_info ) $additional_info = '<strong>' . __( 'Additional Info', 'wc-frontend-manager' ) . ':-</strong><table border="1">' . $additional_info . '</table><br /><br />';
			
			
			// Send mail to admin
			$mail_to = apply_filters( 'wcfm_admin_email_enquiry', get_bloginfo( 'admin_email' ) );
			$reply_mail_subject = "{site_name}: " . __( "New enquiry for", "wc-frontend-manager" ) . " - {product_title}";
			$reply_mail_body = __( 'Hi', 'wc-frontend-manager' ) .
													 ',<br/><br/>' . 
													 sprintf( __( 'You have a recent enquiry for %s.', 'wc-frontend-manager' ), '<a href="{product_url}">{product_title}</a>' ) .
													 '<br/><br/><strong><i>' . 
													 '"{enquiry}"' . 
													 '</i></strong><br/><br/>' .
													 '{additional_info}' .
													 sprintf( __( 'To respond to this Enquiry, please %sClick Here%s', 'wc-frontend-manager' ), '<a href="{enquiry_url}">', '</a>' ) .
													 '<br /><br/>' . __( 'Thank You', 'wc-frontend-manager' ) .
													 '<br /><br/>';
			
			if( apply_filters( 'wcfm_is_allow_enquiry_by_customer', true ) ) {
				//define( 'DOING_WCFM_RESTRICTED_EMAIL', true );
				//$headers[] = 'From: [' . get_bloginfo( 'name' ) . '] ' . __( 'Enquiry', 'wc-frontend-manager' ) . ': ' . $customer_name . ' <' . $customer_email . '>';
				$headers[] = 'Reply-to: ' . $customer_name . ' <' . $customer_email . '>';
			}
		  //$headers[] = 'Cc: ' . $customer_email;
			$subject = str_replace( '{site_name}', get_bloginfo( 'name' ), $reply_mail_subject );
			$subject = str_replace( '{product_title}', get_the_title( $product_id ), $subject );
			$message = str_replace( '{product_title}', get_the_title( $product_id ), $reply_mail_body );
			$message = str_replace( '{product_url}', get_permalink( $product_id ), $message );
			$message = str_replace( '{enquiry_url}', get_wcfm_enquiry_url(), $message );
			$message = str_replace( '{enquiry}', $enquiry, $message );
			$message = str_replace( '{additional_info}', $additional_info, $message );
			$message = apply_filters( 'wcfm_email_content_wrapper', $message, __( 'New Enquiry', 'wc-frontend-manager' ) );
			
			if( apply_filters( 'wcfm_is_allow_enquiry_by_customer', true ) ) {
				wp_mail( $mail_to, $subject, $message, $headers );
			} else {
				wp_mail( $mail_to, $subject, $message );
			}
			
			// Direct message
			$wcfm_messages = sprintf( __( 'You have a recent enquiry for <b>%s</b>', 'wc-frontend-manager' ), '<a target="_blank" class="wcfm_dashboard_item_title" href="' . get_wcfm_enquiry_url() . '">' . get_the_title( $product_id ) . '</a>' );
			$WCFM->frontend->wcfm_send_direct_message( -2, 0, 1, 0, $wcfm_messages, 'enquiry', false );
			
			// Semd email to vendor
			if( wcfm_is_marketplace() ) {
				if( $vendor_id ) {
					$vendor_email = $WCFM->wcfm_vendor_support->wcfm_get_vendor_email_from_product( $product_id );
					if( $vendor_email ) {
						if( apply_filters( 'wcfm_is_allow_enquiry_by_customer', true ) ) {
							wp_mail( $vendor_email, $subject, $message, $headers );
						} else {
							wp_mail( $vendor_email, $subject, $message );
						}
					}
					
					// Direct message
					$wcfm_messages = sprintf( __( 'You have received an Enquiry for <b>%s</b>', 'wc-frontend-manager' ), '<a target="_blank" class="wcfm_dashboard_item_title" href="' . get_wcfm_enquiry_url() . '">' . get_the_title( $product_id ) . '</a>' );
					$WCFM->frontend->wcfm_send_direct_message( -1, $vendor_id, 1, 0, $wcfm_messages, 'enquiry', false );
				}
	  	}
			
			echo '{"status": true, "message": "' . $wcfm_enquiry_messages['enquiry_saved'] . '"}';
		} else {
			echo '{"status": false, "message": "' . $wcfm_enquiry_messages['no_enquiry'] . '"}';
		}
		
		die;
	}
}