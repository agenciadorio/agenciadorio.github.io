<?php
/**
 * WCFM plugin controllers
 *
 * Plugin Shop Customers Controller
 *
 * @author 		WC Lovers
 * @package 	wcfm/controllers/customers
 * @version   3.5.0
 */

class WCFM_Customers_Controller {
	
	public function __construct() {
		global $WCFM;
		
		$this->processing();
	}
	
	public function processing() {
		global $WCFM, $wpdb, $_POST;
		
		$length = $_POST['length'];
		$offset = $_POST['start'];
		
		$customer_user_role = apply_filters( 'wcfm_customer_user_role', 'customer' );
		
		$args = array(
									'role__in'     => array( $customer_user_role ),
									'orderby'      => 'ID',
									'order'        => 'ASC',
									'offset'       => $offset,
									'number'       => $length,
									'count_total'  => false
								 ); 
		
		if( isset( $_POST['search'] ) && !empty( $_POST['search']['value'] )) $args['search'] = $_POST['search']['value'];
		
		$args = apply_filters( 'wcfm_get_customers_args', $args );
		
		$wcfm_customers_array = get_users( $args );
		            
		// Get Product Count
		$customers_count = 0;
		$filtered_customers_count = 0;
		$customers_count = count($wcfm_customers_array);
		// Get Filtered Post Count
		$args['number'] = -1;
		$args['offset'] = 0;
		$wcfm_filterd_customers_array = get_users( $args );
		$filtered_customers_count = count($wcfm_filterd_customers_array);
		
		
		// Generate Products JSON
		$wcfm_customers_json = '';
		$wcfm_customers_json = '{
															"draw": ' . $_POST['draw'] . ',
															"recordsTotal": ' . $customers_count . ',
															"recordsFiltered": ' . $filtered_customers_count . ',
															"data": ';
		$index = 0;
		$wcfm_customers_json_arr = array();
		if(!empty($wcfm_customers_array)) {
			foreach( $wcfm_customers_array as $wcfm_customers_single ) {
				
				// Name
				if ( $wcfm_customers_single->last_name && $wcfm_customers_single->first_name ) {
					$customer_name = $wcfm_customers_single->first_name . ' ' . $wcfm_customers_single->last_name;
				} else {
					$customer_name = $wcfm_customers_single->display_name;
				}
				if( apply_filters( 'wcfm_is_allow_view_customer', true ) ) {
					$wcfm_customers_json_arr[$index][] =  '<a href="' . get_wcfm_customers_details_url($wcfm_customers_single->ID) . '" class="wcfm_dashboard_item_title">' . apply_filters( 'wcfm_customers_display_name_data', $customer_name, $wcfm_customers_single->ID ) . '</a>';
				} else {
					$wcfm_customers_json_arr[$index][] =  apply_filters( 'wcfm_customers_display_name_data', $customer_name, $wcfm_customers_single->ID );
				}
				
				// Username
				$wcfm_customers_json_arr[$index][] = $wcfm_customers_single->user_login;
				
				// Email
				if( apply_filters( 'wcfm_allow_view_customer_email', true ) ) {
					$wcfm_customers_json_arr[$index][] = $wcfm_customers_single->user_email;
				} else {
					$wcfm_customers_json_arr[$index][] = '&ndash;';
				}
				
				// Location
				$state_code   = get_user_meta( $wcfm_customers_single->ID, 'billing_state', true );
				$country_code = get_user_meta( $wcfm_customers_single->ID, 'billing_country', true );

				$state   = isset( WC()->countries->states[ $country_code ][ $state_code ] ) ? WC()->countries->states[ $country_code ][ $state_code ] : $state_code;
				$country = isset( WC()->countries->countries[ $country_code ] ) ? WC()->countries->countries[ $country_code ] : $country_code;

				$value = '';

				if ( $state ) {
					$value .= $state . ', ';
				}

				$value .= $country;

				if ( $value ) {
					$wcfm_customers_json_arr[$index][] = $value;
				} else {
					$wcfm_customers_json_arr[$index][] = '&ndash;';
				}
				
				// Orders
				$wcfm_customers_json_arr[$index][] = wc_get_customer_order_count( $wcfm_customers_single->ID );
				
				// Bookings
				$wcfm_customers_json_arr[$index][] = 0;
				
				// Appointments
				$wcfm_customers_json_arr[$index][] = 0;
				
				// Money Spent
				$wcfm_customers_json_arr[$index][] = wc_price( wc_get_customer_total_spent( $wcfm_customers_single->ID ) );
				
				// Last Order
				$orders = wc_get_orders( array(
					'limit'    => 1,
					'status'   => array_map( 'wc_get_order_status_name', wc_get_is_paid_statuses() ),
					'customer' => $wcfm_customers_single->ID,
				) );

				if ( ! empty( $orders ) ) {
					$order = $orders[0];
					if( apply_filters( 'wcfm_is_allow_order_details', true ) && $WCFM->wcfm_vendor_support->wcfm_is_order_for_vendor( $order->get_id() ) ) {
						$wcfm_customers_json_arr[$index][] = '<span class="customer-orderno"><a href="' . get_wcfm_view_order_url( $order->get_id(), $order ) . '">' . _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number() . '</a></span><br />' . wc_format_datetime( $order->get_date_created() );
					} else {
						$wcfm_customers_json_arr[$index][] = '<span class="customer-orderno">' . _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number() . '</span><br />' . wc_format_datetime( $order->get_date_created() );
					}
				} else {
					$wcfm_customers_json_arr[$index][] = '&ndash;';
				}
				
				// Additional Info
				$wcfm_customers_json_arr[$index][] = apply_filters( 'wcfm_customers_additonal_data', '&ndash;', $wcfm_customers_single->ID );
				
				// Action
				$actions = '<a class="wcfm-action-icon" href="' . get_wcfm_customers_details_url( $wcfm_customers_single->ID ) . '"><span class="fa fa-eye text_tip" data-tip="' . esc_attr__( 'Manage Customer', 'wc-frontend-manager-ultimate' ) . '"></span></a>';
				if( apply_filters( 'wcfm_is_allow_edit_customer', true ) && apply_filters( 'wcfm_is_vendor_customer', true, $wcfm_customers_single->ID ) ) {
					$actions .= '<a class="wcfm-action-icon" href="' . get_wcfm_customers_manage_url( $wcfm_customers_single->ID ) . '"><span class="fa fa-edit text_tip" data-tip="' . esc_attr__( 'Edit Customer', 'wc-frontend-manager-ultimate' ) . '"></span></a>';
				}
				//$actions .= '<a class="wcfm_customer_delete wcfm-action-icon" href="#" data-customerid="' . $wcfm_customers_single->ID . '"><span class="fa fa-trash-o text_tip" data-tip="' . esc_attr__( 'Delete', 'wc-frontend-manager' ) . '"></span></a>';
				$wcfm_customers_json_arr[$index][] = apply_filters ( 'wcfm_customers_actions', $actions, $wcfm_customers_single );
				
				
				$index++;
			}												
		}
		if( !empty($wcfm_customers_json_arr) ) $wcfm_customers_json .= json_encode($wcfm_customers_json_arr);
		else $wcfm_customers_json .= '[]';
		$wcfm_customers_json .= '
													}';
													
		echo $wcfm_customers_json;
	}
}