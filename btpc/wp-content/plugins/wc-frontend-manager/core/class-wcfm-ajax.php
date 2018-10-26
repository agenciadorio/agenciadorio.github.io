<?php
/**
 * WCFM plugin core
 *
 * Plugin Ajax Controler
 *
 * @author 		WC Lovers
 * @package 	wcfm/core
 * @version   1.0.0
 */
 
class WCFM_Ajax {
	
	public $controllers_path;

	public function __construct() {
		global $WCFM;
		
		$this->controllers_path = $WCFM->plugin_path . 'controllers/';
		
		add_action( 'wp_ajax_wcfm_ajax_controller', array( &$this, 'wcfm_ajax_controller' ) );
		
		// Add Taxonomy New Term
    add_action('wp_ajax_wcfm_add_taxonomy_new_term', array( &$this, 'wcfm_add_taxonomy_new_term' ) );
    
    // Generate Variation Attributes
    add_action('wp_ajax_wcfm_generate_variation_attributes', array( &$this, 'wcfm_generate_variation_attributes' ) );
    
    // Product Mark as Approve
		add_action( 'wp_ajax_wcfm_product_approve', array( &$this, 'wcfm_product_approve' ) );
    
    // Order Mark as Complete
		add_action( 'wp_ajax_wcfm_order_mark_complete', array( &$this, 'wcfm_order_mark_complete' ) );
    
    // Order Status Update
		add_action( 'wp_ajax_wcfm_modify_order_status', array( &$this, 'wcfm_modify_order_status' ) );
    
    // Product Delete
		add_action( 'wp_ajax_delete_wcfm_product', array( &$this, 'delete_wcfm_product' ) );
		
		// Knowledgebase Delete
		add_action( 'wp_ajax_delete_wcfm_knowledgebase', array( &$this, 'delete_wcfm_knowledgebase' ) );
		
		// Notice Topic Delete
		add_action( 'wp_ajax_delete_wcfm_notice', array( &$this, 'delete_wcfm_notice' ) );
    
		// Dismiss Add-on inactive notice
		add_action( 'wp_ajax_wcfm-dismiss-addon-inactive-notice', array( $this, 'wcfm_dismiss_inactive_addon_notice' ) );
		
		// Vendor Manager Change Vendor
		add_action( 'wp_ajax_vendor_manager_change_url', array( $this, 'vendor_manager_change_url' ) );
		
		// WCfM Dashboard Menu Toggle
		add_action( 'wp_ajax_wcfm_menu_toggler', array( $this, 'wcfm_menu_toggler' ) );
		
		// WCfM Ajax Product Search
		add_action( 'wp_ajax_wcfm_json_search_products_and_variations', array( $this, 'wcfm_json_search_products_and_variations' ) );
		
		// WCfM Ajax Vendor Search
		add_action( 'wp_ajax_wcfm_json_search_vendors', array( $this, 'wcfm_json_search_vendors' ) );
		
		// Email Verification Code
		add_action( 'wp_ajax_wcfm_email_verification_code', array( &$this, 'wcfm_email_verification_code' ) );
		
		// Vendor disable
    add_action( 'wp_ajax_wcfm_vendor_disable', array( &$this, 'wcfm_vendor_disable' ) );
    
    // Vendor disable
    add_action( 'wp_ajax_wcfm_vendor_enable', array( &$this, 'wcfm_vendor_enable' ) );
  }
  
  public function wcfm_ajax_controller() {
  	global $WCFM;
  	
  	do_action( 'after_wcfm_ajax_controller' );
  	
  	$controller = '';
  	if( isset( $_POST['controller'] ) ) {
  		$controller = $_POST['controller'];
  		
  		switch( $controller ) {
	  	
				case 'wc-products':
				case 'wcfm-products':
					require_once( $this->controllers_path . 'products/wcfm-controller-products.php' );
					new WCFM_Products_Controller();
			  break;
			  
			  case 'wcfm-products-manage':
			  	if( wcfm_is_booking() ) {
						require_once( $this->controllers_path . 'wc_bookings/wcfm-controller-wcbookings-products-manage.php' );
						new WCFM_WCBookings_Products_Manage_Controller();
					}
					// Third Party Plugin Support
					require_once( $this->controllers_path . 'products-manager/wcfm-controller-thirdparty-products-manage.php' );
					new WCFM_ThirdParty_Products_Manage_Controller();
					
					// Custom Field Plugin Support
					require_once( $this->controllers_path . 'products-manager/wcfm-controller-customfield-products-manage.php' );
					new WCFM_Custom_Field_Products_Manage_Controller();
					
					require_once( $this->controllers_path . 'products-manager/wcfm-controller-products-manage.php' );
					new WCFM_Products_Manage_Controller();
					
			  break;
					
			  case 'wcfm-coupons':
					require_once( $this->controllers_path . 'coupons/wcfm-controller-coupons.php' );
					new WCFM_Coupons_Controller();
				break;
				
				case 'wcfm-coupons-manage':
					require_once( $this->controllers_path . 'coupons/wcfm-controller-coupons-manage.php' );
					new WCFM_Coupons_Manage_Controller();
				break;
				
				case 'wcfm-orders':
					if( $WCFM->is_marketplace && ( wcfm_is_vendor() || apply_filters( 'wcfm_is_show_marketplace_orders', false ) ) ) {
						require_once( $this->controllers_path . 'orders/wcfm-controller-' . $WCFM->is_marketplace . '-orders.php' );
						if( $WCFM->is_marketplace == 'wcvendors' ) new WCFM_Orders_WCVendors_Controller();
						elseif( $WCFM->is_marketplace == 'wcpvendors' ) new WCFM_Orders_WCPVendors_Controller();
						elseif( $WCFM->is_marketplace == 'wcmarketplace' ) new WCFM_Orders_WCMarketplace_Controller();
						elseif( $WCFM->is_marketplace == 'dokan' ) new WCFM_Orders_Dokan_Controller();
					} else {
						require_once( $this->controllers_path . 'orders/wcfm-controller-orders.php' );
						new WCFM_Orders_Controller();
					}
				break;
				
				case 'wcfm-listings':
					require_once( $this->controllers_path . 'listings/wcfm-controller-listings.php' );
					new WCFM_Listings_Controller();
				break;
				
				case 'wcfm-reports-out-of-stock':
					require_once( $this->controllers_path . 'reports/wcfm-controller-reports-out-of-stock.php' );
					new WCFM_Reports_Out_Of_Stock_Controller();
				break;
				
				case 'wcfm-profile':
					require_once( $this->controllers_path . 'profile/wcfm-controller-profile.php' );
					new WCFM_Profile_Controller();
				break;
					
				case 'wcfm-settings':
					if( $WCFM->is_marketplace && wcfm_is_vendor() ) {
						require_once( $this->controllers_path . 'settings/wcfm-controller-' . $WCFM->is_marketplace . '-settings.php' );
						if( $WCFM->is_marketplace == 'wcvendors' ) new WCFM_Settings_WCVendors_Controller();
						elseif( $WCFM->is_marketplace == 'wcpvendors' ) new WCFM_Settings_WCPVendors_Controller();
						elseif( $WCFM->is_marketplace == 'wcmarketplace' ) new WCFM_Settings_WCMarketplace_Controller();
						elseif( $WCFM->is_marketplace == 'dokan' ) new WCFM_Settings_Dokan_Controller();
					} else {
						require_once( $this->controllers_path . 'settings/wcfm-controller-settings.php' );
						new WCFM_Settings_Controller();
					}
				break;
				
				case 'wcfm-capability':
					require_once( $this->controllers_path . 'capability/wcfm-controller-capability.php' );
					new WCFM_Capability_Controller();
				break;
				
				case 'wcfm-knowledgebase':
					require_once( $this->controllers_path . 'knowledgebase/wcfm-controller-knowledgebase.php' );
					new WCFM_Knowledgebase_Controller();
				break;
				
				case 'wcfm-knowledgebase-manage':
					require_once( $this->controllers_path . 'knowledgebase/wcfm-controller-knowledgebase-manage.php' );
					new wcfm_Knowledgebase_Manage_Controller();
				break;
				
				case 'wcfm-notices':
					require_once( $this->controllers_path . 'notice/wcfm-controller-notices.php' );
					new WCFM_Notices_Controller();
				break;
				
				case 'wcfm-notice-manage':
					require_once( $this->controllers_path . 'notice/wcfm-controller-notice-manage.php' );
					new wcfm_Notice_Manage_Controller();
				break;
				
				case 'wcfm-notice-reply':
					require_once( $this->controllers_path . 'notice/wcfm-controller-notice-reply.php' );
					new WCFM_Notice_Reply_Controller();
				break;
				
				case 'wcfm-messages':
					require_once( $this->controllers_path . 'messages/wcfm-controller-messages.php' );
					new WCFM_Messages_Controller();
				break;
				
				case 'wcfm-message-sent':
					require_once( $this->controllers_path . 'messages/wcfm-controller-message-sent.php' );
					new WCFM_Message_Sent_Controller();
				break;
				
				case 'wcfm-vendors':
					require_once( $this->controllers_path . 'vendors/wcfm-controller-vendors.php' );
					new WCFM_Vendors_Controller();
				break;
				
				case 'wcfm-vendors-manage':
					require_once( $this->controllers_path . 'vendors/wcfm-controller-vendors-manage.php' );
					new WCFM_Vendors_Manage_Controller();
				break;
				
				case 'wcfm-vendors-manage-profile':
					require_once( $this->controllers_path . 'vendors/wcfm-controller-vendors-manage.php' );
					new WCFM_Vendors_Manage_Profile_Controller();
				break;
				
				case 'wcfm-vendors-manage-badges':
					require_once( $this->controllers_path . 'vendors/wcfm-controller-vendors-manage.php' );
					new WCFM_Vendors_Manage_Badges_Controller();
				break;
				
				case 'wcfm-vendors-commission':
					require_once( $this->controllers_path . 'vendors/wcfm-controller-vendors-commission.php' );
					new WCFM_Vendors_Commission_Controller();
				break;
			}
  	}
  	
  	do_action( 'before_wcfm_ajax_controller' );
  	die();
  }
  
  /**
	 * Add new taxonomy term
	 */
	function wcfm_add_taxonomy_new_term() {
		global $WCFM, $WCFMu, $_POST;
		
		$taxonomy     = esc_attr( $_POST['taxonomy'] );
		$new_term     = wc_clean( $_POST['new_term'] );
		$parent_term  = wc_clean( $_POST['parent_term'] );

		if ( taxonomy_exists( $taxonomy ) ) {

			$result = wp_insert_term( $new_term, $taxonomy, array( 'parent' => $parent_term ) );

			if ( is_wp_error( $result ) ) {
				wp_send_json( array(
					'error' => $result->get_error_message(),
				) );
			} else {
				// Addmin notification message for new_taxonomy_term 
				$author_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
				$author_is_admin = 0;
				$author_is_vendor = 1;
				$message_to = 0;
				$wcfm_messages = sprintf( __( 'A new %s <b>%s</b> added to the store by <b>%s</b>', 'wc-frontend-manager' ), ucfirst( $taxonomy ), $new_term, get_user_by( 'id', $author_id )->display_name );
				$WCFM->frontend->wcfm_send_direct_message( $author_id, $message_to, $author_is_admin, $author_is_vendor, $wcfm_messages, 'new_taxonomy_term' );
				
				// Sending front-end HTML
				$term = get_term_by( 'id', $result['term_id'], $taxonomy );
				echo '<li class="product_cats_checklist_item checklist_item_' . esc_attr( $term->term_id ) . '" data-item="' . esc_attr( $term->term_id ) . '">';
				echo '<span class="fa fa-arrow-circle-right sub_checklist_toggler"></span>';
				if( $taxonomy != 'product_cat' ) {
					echo '<label class="selectit">' . $nbsp . '<input type="checkbox" class="wcfm-checkbox" name="product_custom_taxonomies[' . $taxonomy . '][]" value="' . esc_attr( $term->term_id ) . '" checked />' . esc_html( $term->name ) . '</label>';
				} else {
					echo '<label class="selectit">' . $nbsp . '<input type="checkbox" class="wcfm-checkbox" name="product_cats[]" value="' . esc_attr( $term->term_id ) . '" checked /><span>' . esc_html( $term->name ) . '</span></label>';
				}
				echo '</li>';
			}
		}
		die();
	}
  
  public function wcfm_generate_variation_attributes() {
		global $wpdb, $WCFM;
	  
	  $wcfm_products_manage_form_data = array();
	  parse_str($_POST['wcfm_products_manage_form'], $wcfm_products_manage_form_data);
	  //print_r($wcfm_products_manage_form_data);
	  
	  if(isset($wcfm_products_manage_form_data['attributes']) && !empty($wcfm_products_manage_form_data['attributes'])) {
			$pro_attributes = '{';
			$attr_first = true;
			foreach($wcfm_products_manage_form_data['attributes'] as $attributes) {
				if(isset($attributes['is_variation'])) {
					if( isset( $attributes['is_active'] ) && !empty( $attributes['name'] ) && !empty( $attributes['value'] ) ) {
						if(!$attr_first) $pro_attributes .= ',';
						if($attr_first) $attr_first = false;
						
						if($attributes['is_taxonomy']) {
							$pro_attributes .= '"' . sanitize_title( $attributes['tax_name'] ) . '": {';
							if( !is_array($attributes['value']) ) {
								$att_values = explode( WC_DELIMITER , $attributes['value']);
								$is_first = true;
								foreach($att_values as $att_value) {
									if(!$is_first) $pro_attributes .= ',';
									if($is_first) $is_first = false;
									$pro_attributes .= '"' . sanitize_title($att_value) . '": "' . esc_attr(trim($att_value)) . '"';
								}
							} else {
								$att_values = $attributes['value'];
								$is_first = true;
								foreach($att_values as $att_value) {
									if(!$is_first) $pro_attributes .= ',';
									if($is_first) $is_first = false;
									$att_term = get_term( absint($att_value) );
									if( $att_term ) {
										$pro_attributes .= '"' . $att_term->slug . '": "' . esc_attr($att_term->name) . '"';
									} else {
										$pro_attributes .= '"' . sanitize_title($att_value) . '": "' . esc_attr(trim($att_value)) . '"';
									}
								}
							}
							$pro_attributes .= '}';
						} else {
							$pro_attributes .= '"' . sanitize_title( $attributes['name'] ) . '": {';
							$att_values = explode( WC_DELIMITER, $attributes['value']);
							$is_first = true;
							foreach($att_values as $att_value) {
								if(!$is_first) $pro_attributes .= ',';
								if($is_first) $is_first = false;
								$pro_attributes .= '"' . esc_attr(trim($att_value)) . '": "' . esc_attr(trim($att_value)) . '"';
							}
							$pro_attributes .= '}';
						}
					}
				}
			}
			$pro_attributes .= '}';
			echo $pro_attributes;
		}
		
		die();
	}
  
  /**
   * Handle Product Delete
   */
  public function delete_wcfm_product() {
  	global $WCFM, $WCFMu;
  	
  	$proid = $_POST['proid'];
		
		if( $proid ) {
			$product = wc_get_product( $proid );
			if ( 'appointment' === $product->get_type() ) {
				remove_all_actions( 'before_delete_post' );
			}
			
			if(wp_delete_post($proid)) {
				echo 'success';
				die;
			}
			die;
		}
  }
  
  /**
   * Handle Product mark as Approve
   */
  function wcfm_product_approve() {
  	global $WCFM;
  	
  	if( isset( $_POST['proid'] ) && !empty( $_POST['proid'] ) ) {
  		$product_id = absint( $_POST['proid'] );
  		$update_product = apply_filters( 'wcfm_product_content_before_update', array(
  																																					'ID'           => $product_id,
																																						'post_status'  => 'publish',
																																						'post_type'    => 'product',
																																					), $product_id );
  		wp_update_post( $update_product, true );
  	}
		
		die;
  }
  
  /**
   * Handle Order status update
   */
  public function wcfm_order_mark_complete() {
  	global $WCFM;
  	
  	$order_id = $_POST['orderid'];
  	
  	if ( wc_is_order_status( 'wc-completed' ) && $order_id ) {
			$order = wc_get_order( $order_id );
			$order->update_status( 'completed', '', true );
			
			// Add Order Note for Log
			$user_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
			$shop_name =  get_user_by( 'ID', $user_id )->display_name;
			if( wcfm_is_vendor() ) {
				$shop_name =  $WCFM->wcfm_vendor_support->wcfm_get_vendor_store_by_vendor( absint($user_id) );
			}
			$comment_id = $order->add_order_note( sprintf( __( '<b>%s</b> has updated order status to <b>%s</b>', 'wc-frontend-manager' ), $shop_name, wc_get_order_status_name( 'completed' ) ), '1');
			if( wcfm_is_vendor() ) { add_comment_meta( $comment_id, '_vendor_id', $user_id ); }
			
			do_action( 'woocommerce_order_edit_status', $order_id, 'completed' );
			do_action( 'wcfm_order_update_status', $order_id, 'completed' );
		}
		die;
  }
  
  /**
   * Handle Order Details Status Update
   */
  public function wcfm_modify_order_status() {
  	global $WCFM;
  	
  	$order_id = $_POST['order_id'];
  	$order_status = $_POST['order_status'];
  	
  	if ( wc_is_order_status( $order_status ) && $order_id ) {
			$order = wc_get_order( $order_id );
			$order->update_status( str_replace('wc-', '', $order_status), '', true );
			
			// Add Order Note for Log
			$user_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
			$shop_name =  get_user_by( 'ID', $user_id )->display_name;
			if( wcfm_is_vendor() ) {
				$shop_name =  $WCFM->wcfm_vendor_support->wcfm_get_vendor_store_by_vendor( absint($user_id) );
			}
			$comment_id = $order->add_order_note( sprintf( __( '<b>%s</b> has updated order status to <b>%s</b>', 'wc-frontend-manager' ), $shop_name, wc_get_order_status_name( str_replace('wc-', '', $order_status) ) ), '1');
			if( wcfm_is_vendor() ) { add_comment_meta( $comment_id, '_vendor_id', $user_id ); }
			
			do_action( 'woocommerce_order_edit_status', $order_id, str_replace('wc-', '', $order_status) );
			do_action( 'wcfm_order_update_status', $order_id, str_replace('wc-', '', $order_status) );
		}
		die;
  	
  }
  
  /**
   * Handle Knowledgebase Delete
   */
  public function delete_wcfm_knowledgebase() {
  	global $WCFM, $WCFMu;
  	
  	$knowledgebaseid = $_POST['knowledgebaseid'];
		
		if($knowledgebaseid) {
			if(wp_delete_post($knowledgebaseid)) {
				echo 'success';
				die;
			}
			die;
		}
  }
  
  /**
   * Handle Notice - Topic Delete
   */
  public function delete_wcfm_notice() {
  	global $WCFM, $WCFMu;
  	
  	$noticeid = $_POST['noticeid'];
		
		if($noticeid) {
			if(wp_delete_post($noticeid)) {
				echo 'success';
				die;
			}
			die;
		}
  }
  
  /**
	 * Dismiss addon inactive notice
	 *
	 * @since 3.3.6
	 *
	 * @return void
	 */
  function wcfm_dismiss_inactive_addon_notice() {
  	if ( ! empty( $_POST['wcfm_wcfmvm_inactive'] ) ) {
			$offer_key = 'wcfm_wcfmvm_inactive2306';
			update_option( $offer_key . '_tracking_notice', 'hide' );
		}
		
		if ( ! empty( $_POST['wcfm_wcfmu_inactive'] ) ) {
			$offer_key = 'wcfm_wcfmu_inactive2306';
			update_option( $offer_key . '_tracking_notice', 'hide' );
		}
		
		if ( ! empty( $_POST['wcfm_wcfmgs_inactive'] ) ) {
			$offer_key = 'wcfm_wcfmgs_inactive2306';
			update_option( $offer_key . '_tracking_notice', 'hide' );
		}
  }
  
  /**
   * Vendor manager change URL
   */
  function vendor_manager_change_url() {
  	global $WCFM, $_POST;
  	
  	if( isset( $_POST['vendor_manager_change'] ) && !empty( $_POST['vendor_manager_change'] ) ) {
  		$vendor_id = absint( $_POST['vendor_manager_change'] );
  		echo '{"status": true, "redirect": "' . get_wcfm_vendors_manage_url($vendor_id) . '"}';
  	}
  	
  	die;
  }
  
  /**
   * wcfm_menu_toggler
   */
  function wcfm_menu_toggler() {
  	global $WCFM, $_POST;
  	$user_id = get_current_user_id();
  	$toggle_state = $_POST['toggle_state'];
  	update_user_meta( $user_id, '_wcfm_menu_toggle_state', $toggle_state );
  	
  	echo "success";
  	die;
  }
  
  /**
   * WCfM ajax product search
   */
  function wcfm_json_search_products_and_variations() {
  	global $WCFM, $_POST, $_GET;
  	
  	$term = wc_clean( empty( $term ) ? stripslashes( $_GET['term'] ) : $term );

		if ( empty( $term ) ) {
			wp_die();
		}
		
		$args = array(
			'posts_per_page'   => 25,
			'offset'           => 0,
			'category'         => '',
			'category_name'    => '',
			'orderby'          => 'date',
			'order'            => 'DESC',
			'include'          => '',
			'exclude'          => '',
			'meta_key'         => '',
			'meta_value'       => '',
			'post_type'        => 'product',
			'post_mime_type'   => '',
			'post_parent'      => '',
			//'author'	   => get_current_user_id(),
			'post_status'      => array('publish'),
			'suppress_filters' => 0 
		);
		$args = apply_filters( 'wcfm_products_args', $args );
		$args['s'] = $term;
		
		$products_objs = get_posts( $args );
		$products_array = array();
		if( !empty($products_objs) ) {
			foreach( $products_objs as $products_obj ) {
				$product_data      = wc_get_product( $products_obj->ID );
				$products_array[esc_attr( $products_obj->ID )] = (!empty($product_data)) ? wp_kses_post( $product_data->get_formatted_name() ) : $products_obj->ID;
			}
		}
		
		wp_send_json( apply_filters( 'wcfm_json_search_products_and_variations', $products_array ) );
  }
  
  /**
   * WCfM ajax vendor search
   */
  function wcfm_json_search_vendors() {
  	global $WCFM, $_POST, $_GET;
  	
  	$term = wc_clean( empty( $term ) ? stripslashes( $_GET['term'] ) : $term );

		if ( empty( $term ) ) {
			wp_die();
		}
		$vendor_arr = $WCFM->wcfm_vendor_support->wcfm_get_vendor_list( false, 0, 25, $term );
		wp_send_json( apply_filters( 'wcfm_json_search_vendors', $vendor_arr ) );
  }
  
  /**
   * WCfM Profile email verification code send
   */
  function wcfm_email_verification_code() {
  	global $WCFM;
  	
  	$user_id = get_current_user_id();
  	$user_email = $_POST['user_email'];
		
		if( $user_email ) {
			$email_verification_code = get_post_meta( $user_id, '_wcfm_email_verification_code', true );
			if( $email_verification_code ) {
				$verification_code = $email_verification_code;
			} else {
				$verification_code = rand( 100000, 999999 );
				update_post_meta( $user_id, '_wcfm_email_verification_code', $verification_code );
			}
			
			// Sending verification code in email
			define( 'DOING_WCFM_EMAIL', true );
			$verification_mail_subject = "{site_name}: " . __( "Email Verification Code", "wc-frontend-manager" ) . " - " . $verification_code;
			$verification_mail_body = __( 'Hi', 'wc-frontend-manager' ) .
																	 ',<br/><br/>' . 
																	 sprintf( __( 'Here is your email verification code - <b>%s</b>', 'wc-frontend-manager' ), '{verification_code}' ) .
																	 '<br /><br/>' . __( 'Thank You', 'wc-frontend-manager' );
													 
			$subject = str_replace( '{site_name}', get_bloginfo( 'name' ), $verification_mail_subject );
			$subject = str_replace( '{verification_code}', $verification_code, $subject );
			$message = str_replace( '{verification_code}', $verification_code, $verification_mail_body );
			$message = apply_filters( 'wcfm_email_content_wrapper', $message, __( 'Email Verification', 'wc-multivendor-membership' ) );
			
			wp_mail( $user_email, $subject, $message );
			
			echo '{"status": true, "message": "' . __( 'Email verification code send to your email.', 'wc-frontend-manager' ) . '"}';
		} else {
			echo '{"status": false, "message": "' . __( 'Email verification not working right now, please try after sometime.', 'wc-frontend-manager' ) . '"}';
		}
		die;
  }
  
  /**
	 * Vednor acount disable
	 */
	function wcfm_vendor_disable() {
		global $WCFM, $_POST, $wpdb;
		
		if( isset( $_POST['memberid'] ) ) {
			$member_id = absint( $_POST['memberid'] );
			$member_user = new WP_User( $member_id );
			$vendor_store = $WCFM->wcfm_vendor_support->wcfm_get_vendor_store_by_vendor( $member_id );
			
			$member_user->set_role('disable_vendor');
			
			update_user_meta( $member_id, '_disable_vendor', true );
			
			// Membership Disable Admin Desktop Notification
			$wcfm_messages = sprintf( __( '<b>%s</b> (Store: <b>%s</b>) has been disabled.', 'wc-frontend-manager' ), $member_user->first_name, $vendor_store );
			$WCFM->frontend->wcfm_send_direct_message( -1, 0, 1, 0, $wcfm_messages, 'vendor-disable' );
			
			// Vendor Notification
			$wcfm_messages = sprintf( __( 'Your Store: <b>%s</b> has been disabled.', 'wc-frontend-manager' ), $vendor_store );
			$WCFM->frontend->wcfm_send_direct_message( -1, $member_id, 1, 0, $wcfm_messages, 'vendor-disable' );
				
			echo '{"status": true, "message": "' . __( 'Vendor successfully disabled.', 'wc-frontend-manager' ) . '"}';
			die;
		}
		echo '{"status": false, "message": "' . __( 'Vendor can not be disabled right now, please try after sometime.', 'wc-frontend-manager' ) . '"}';
		die;
	}
	
	/**
	 * Vednor acount enable
	 */
	function wcfm_vendor_enable() {
		global $WCFM, $_POST, $wpdb;
		
		if( isset( $_POST['memberid'] ) ) {
			$member_id = absint( $_POST['memberid'] );
			$member_user = new WP_User( $member_id );
			$vendor_store = $WCFM->wcfm_vendor_support->wcfm_get_vendor_store_by_vendor( $member_id );
			
			$is_marketplace = wcfm_is_marketplace();
			if( $is_marketplace == 'wcmarketplace' ) {
				$member_user->set_role('dc_vendor');
			} elseif( $is_marketplace == 'wcpvendors' ) {
				$member_user->set_role('wc_product_vendors_admin_vendor');
			} elseif( $is_marketplace == 'wcvendors' ) {
				$member_user->set_role('vendor');
			} elseif( $is_marketplace == 'dokan' ) {
				$member_user->set_role('seller');
			}
			
			delete_user_meta( $member_id, '_disable_vendor' );
			
			// Membership Enable Admin Desktop Notification
			$wcfm_messages = sprintf( __( '<b>%s</b> (Store: <b>%s</b>) has been enabled.', 'wc-frontend-manager' ), $member_user->first_name, $vendor_store );
			$WCFM->frontend->wcfm_send_direct_message( -1, 0, 1, 0, $wcfm_messages, 'vendor-enable' );
			
			// Vendor Notification
			$wcfm_messages = sprintf( __( 'Your Store: <b>%s</b> has been enabled.', 'wc-frontend-manager' ), $vendor_store );
			$WCFM->frontend->wcfm_send_direct_message( -1, $member_id, 1, 0, $wcfm_messages, 'vendor-enable' );
			
				
			echo '{"status": true, "message": "' . __( 'Vendor successfully enabled.', 'wc-frontend-manager' ) . '"}';
			die;
		}
		echo '{"status": false, "message": "' . __( 'Vendor can not be enabled right now, please try after sometime.', 'wc-frontend-manager' ) . '"}';
		die;
	}
}