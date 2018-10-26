<?php
/**
 * WCFM plugin controllers
 *
 * Plugin Vendors Controller
 *
 * @author 		WC Lovers
 * @package 	wcfm/vendors/controllers
 * @version   3.2.1
 */

class WCFM_Vendors_Controller {
	
	public function __construct() {
		global $WCFM;
		
		$this->processing();
	}
	
	public function processing() {
		global $WCFM, $wpdb, $_POST;
		
		$length = $_POST['length'];
		$offset = $_POST['start'];
		
		$report_for = '7day';
		if( isset($_POST['report_for']) && !empty($_POST['report_for']) ) {
			$report_for = $_POST['report_for'];
		}
		
		$report_vendor = '';
		if ( ! empty( $_POST['report_vendor'] ) ) {
			$report_vendor = absint( $_POST['report_vendor'] );
		}
		
		$wcfm_vendors_array = $WCFM->wcfm_vendor_support->wcfm_get_vendor_list( true, $offset, $length, '', $report_vendor );
		unset($wcfm_vendors_array[0]);
		
		// Get Vendor Count
		$wcfm_all_vendors = $WCFM->wcfm_vendor_support->wcfm_get_vendor_list( true, '', '', '' );
		unset($wcfm_all_vendors[0]);
		
		// Get Filtered Vendor Count
		$wcfm_filtered_vendors = $WCFM->wcfm_vendor_support->wcfm_get_vendor_list( true, '', '', '', $report_vendor );
		unset($wcfm_filtered_vendors[0]);
		
		$admin_fee_mode = apply_filters( 'wcfm_is_admin_fee_mode', false );
		
		// Generate Vendors JSON
		$wcfm_vendors_json = '';
		$wcfm_vendors_json = '{
															"draw": ' . $_POST['draw'] . ',
															"recordsTotal": ' . count( $wcfm_all_vendors ) . ',
															"recordsFiltered": ' . count( $wcfm_filtered_vendors ) . ',
															"data": ';
		if(!empty($wcfm_vendors_array)) {
			$index = 0;
			$wcfm_vendors_json_arr = array();
			foreach($wcfm_vendors_array as $wcfm_vendors_id => $wcfm_vendors_name ) {
				
				// Status
				$disable_vendor = get_user_meta( $wcfm_vendors_id, '_disable_vendor', true );
				if( $disable_vendor ) {
					$wcfm_vendors_json_arr[$index][] = '<span class="order-status tips wcicon-status-cancelled text_tip" data-tip="' . __( 'Disable Vendor', 'wc-frontend-manager' ) . '"></span>';
				} else {
					$wcfm_vendors_json_arr[$index][] = '<span class="order-status tips wcicon-status-completed text_tip" data-tip="' . __( 'Active Vendor', 'wc-frontend-manager' ) . '"></span>'; 
				}
				
				// Name
				$vendor_display_name = '<a href="'. get_wcfm_vendors_manage_url($wcfm_vendors_id) . '" class="wcfm_dashboard_item_title">' . apply_filters( 'wcfm_vednors_display_name_data', $wcfm_vendors_name, $wcfm_vendors_id ) . '</a>';
				if( apply_filters( 'wcfm_is_allow_email_verification', true ) ) {
					$email_verified = false;
					$vendor_user = get_userdata( $wcfm_vendors_id );
					$user_email = $vendor_user->user_email;
					$email_verified = get_user_meta( $wcfm_vendors_id, '_wcfm_email_verified', true );
					$wcfm_email_verified_for = get_user_meta( $wcfm_vendors_id, '_wcfm_email_verified_for', true );
					if( $email_verified && ( $user_email != $wcfm_email_verified_for ) ) $email_verified = false;
					if( $email_verified ) {
						$vendor_display_name .= '&nbsp;<span class="fa fa-envelope wcfm_email_verified_icon text_tip" data-tip="' . __( 'Email Verified', 'wc-frontend-manager' ) . '" style="color: #008C00; margin-right: 5px;"></span>';
					} else {
						$vendor_display_name .= '&nbsp;<span class="fa fa-envelope-open wcfm_email_verified_icon text_tip" data-tip="' . __( 'Email Verification Pending', 'wc-frontend-manager' ) . '" style="color: #FF1A00; margin-right: 5px;"></span>';
					}
				}
				$wcfm_vendors_json_arr[$index][] =  $vendor_display_name;
				
				// Shop Name
				$wcfm_vendors_json_arr[$index][] =  '<span class="wcfm_vendor_store">' . apply_filters( 'wcfm_vednors_store_name_data', $WCFM->wcfm_vendor_support->wcfm_get_vendor_store_by_vendor( $wcfm_vendors_id ), $wcfm_vendors_id ) . '</span>';
				
				// Membership
				$wcfm_membership = get_user_meta( $wcfm_vendors_id, 'wcfm_membership', true );
				if( $wcfm_membership ) {
					$wcfm_vendors_json_arr[$index][] = '<span class="wcfm_vendor_memvership">' . get_the_title( $wcfm_membership ) . '</span>';
				} else {
					$wcfm_vendors_json_arr[$index][] =  '<span class="wcfm_vendor_memvership">&ndash;</span>';
				}
				
				// No. of Products
				$total_products = count_user_posts( $wcfm_vendors_id, 'product' ); //$WCFM->wcfm_vendor_support->wcfm_get_products_by_vendor( $wcfm_vendors_id );
				$wcfm_vendors_json_arr[$index][] = apply_filters( 'wcfm_vednors_total_products_data', $total_products, $wcfm_vendors_id );
				
				// Gross Sales
				$gross_sales = $WCFM->wcfm_vendor_support->wcfm_get_gross_sales_by_vendor( $wcfm_vendors_id, $report_for );
				$wcfm_vendors_json_arr[$index][] = apply_filters( 'wcfm_vednors_gross_sales_data', wc_price( $gross_sales ), $wcfm_vendors_id );
				
				// Earned Commission
				$earned = $WCFM->wcfm_vendor_support->wcfm_get_commission_by_vendor( $wcfm_vendors_id, $report_for );
				if( $admin_fee_mode ) {
					$earned = $gross_sales - $earned;
				}
				$wcfm_vendors_json_arr[$index][] = apply_filters( 'wcfm_vednors_earned_commission_data', wc_price( $earned ), $wcfm_vendors_id, $report_for );

				// Received Commission
				$received_commission = $WCFM->wcfm_vendor_support->wcfm_get_commission_by_vendor( $wcfm_vendors_id, $report_for, true );
				if( $admin_fee_mode ) {
					$net_paid_sales = $WCFM->wcfm_vendor_support->wcfm_get_gross_sales_by_vendor( $wcfm_vendors_id, $report_for, true );
					$received_commission = $net_paid_sales - $received_commission;
				}
				$wcfm_vendors_json_arr[$index][] = apply_filters( 'wcfm_vednors_received_commission_data', wc_price( $received_commission ), $wcfm_vendors_id, $report_for );
				
				// Additional Info
				$wcfm_vendors_json_arr[$index][] = apply_filters( 'wcfm_vendors_additonal_data', '&ndash;', $wcfm_vendors_id );

				// Action
				/*$actions = '';
				if( $wcfm_vendors_single->post_status == 'publish' ) {
				  $actions .= ( current_user_can( 'edit_published_shop_vendors' ) ) ? '<a class="wcfm-action-icon" href="' . get_wcfm_vendors_manage_url($wcfm_vendors_single->ID) . '"><span class="fa fa-edit text_tip" data-tip="' . esc_attr__( 'Edit', 'wc-frontend-manager' ) . '"></span></a>' : '';
				} else {
					$actions .= ( current_user_can( 'edit_shop_vendors' ) ) ? '<a class="wcfm-action-icon" href="' . get_wcfm_vendors_manage_url($wcfm_vendors_single->ID) . '"><span class="fa fa-edit text_tip" data-tip="' . esc_attr__( 'Edit', 'wc-frontend-manager' ) . '"></span></a>' : '';
				}
				$wcfm_vendors_json_arr[$index][] = apply_filters ( 'wcfm_vendors_actions', $actions, $wcfm_vendors_single );*/
				
				$index++;
			}												
		}
		if( !empty($wcfm_vendors_json_arr) ) $wcfm_vendors_json .= json_encode($wcfm_vendors_json_arr);
		else $wcfm_vendors_json .= '[]';
		$wcfm_vendors_json .= '
													}';
													
		echo $wcfm_vendors_json;
	}
}