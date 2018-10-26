<?php
if(!function_exists('wcfm_woocommerce_inactive_notice')) {
	function wcfm_woocommerce_inactive_notice() {
		?>
		<div id="message" class="error">
		<p><?php printf( __( '%sWooCommerce Frontend Manager is inactive.%s The %sWooCommerce plugin%s must be active for the WooCommerce Frontend Manager to work. Please %sinstall & activate WooCommerce%s', 'wc-frontend-manager' ), '<strong>', '</strong>', '<a target="_blank" href="http://wordpress.org/extend/plugins/woocommerce/">', '</a>', '<a href="' . admin_url( 'plugin-install.php?tab=search&s=woocommerce' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
		</div>
		<?php
	}
}

if(!function_exists('wcfm_woocommerce_version_notice')) {
	function wcfm_woocommerce_version_notice() {
		?>
		<div id="message" class="error">
		<p><?php printf( __( '%sOpps ..!!!%s You are using %sWC %s. WCFM works only with %sWC 3.0+%s. PLease upgrade your WooCommerce version now to make your life easier and peaceful by using WCFM.', 'wc-frontend-manager' ), '<strong>', '</strong>', '<strong>', WC_VERSION . '</strong>', '<strong>', '</strong>' ); ?></p>
		</div>
		<?php
	}
}

/*if(!function_exists('wcfm_wcfmu_inactive_notice')) {
	function wcfm_wcfmu_inactive_notice() {
		$wcfm_options = get_option('wcfm_options');
	  $is_ultimate_notice_disabled = isset( $wcfm_options['ultimate_notice_disabled'] ) ? $wcfm_options['ultimate_notice_disabled'] : 'no';
		if( $is_ultimate_notice_disabled == 'no' ) {
			?>
			<div id="wcfmu_message" class="notice notice-warning">
			<p><?php printf( __( 'Are you missing anything in your front-end Dashboard !!! Then why not go for %sWCfM U >>%s', 'wc-frontend-manager' ), '<a class="primary" target="_blank" href="http://wclovers.com/product/woocommerce-frontend-manager-ultimate/">', '</a>' ); ?></p>
			</div>
			<?php
		}
	}
}*/

if(!function_exists('wcfm_restriction_message_show')) {
	function wcfm_restriction_message_show( $feature = '', $text_only = false ) {
		?>
		<div class="collapse wcfm-collapse">
		  <div class="wcfm-container">
			  <div class="wcfm-content">
					<div id="wcfmu-feature-missing-message" class="wcfm-warn-message wcfm-wcfmu" style="display: block;">
						<p><span class="fa fa-warning"></span>
						<?php printf( __( '%s' . $feature . '%s: You don\'t have permission to access this page. Please contact your %sStore Admin%s for assistance.', 'wc-frontend-manager' ), '<strong>', '</strong>', '<strong>', '</strong>' ); ?></p>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}

if(!function_exists('wcfmu_feature_help_text_show')) {
	function wcfmu_feature_help_text_show( $feature, $only_admin = false, $text_only = false ) {
		
		if( wcfm_is_vendor() ) {
			if( !$only_admin ) {
				if( $text_only ) {
					_e( $feature . ': Please ask your Store Admin to upgrade your dashboard to access this feature.', 'wc-frontend-manager' );
				} else {
					?>
					<div id="wcfmu-feature-missing-message" class="wcfm-warn-message wcfm-wcfmu" style="display: block;">
						<p><span class="fa fa-warning"></span>
						<?php printf( __( '%s' . $feature . '%s: Please ask your %sStore Admin%s to upgrade your dashboard to access this feature.', 'wc-frontend-manager' ), '<strong>', '</strong>', '<strong>', '</strong>' ); ?></p>
					</div>
					<?php
				}
			}
		} else {
			if( $text_only ) {
				_e( $feature . ': Upgrade your WCFM to WCFM - Ultimate to avail this feature. Disable this notice from settings panel using "Disable Ultimate Notice" option.', 'wc-frontend-manager' );
			} else {
				?>
				<div id="wcfmu-feature-missing-message" class="wcfm-warn-message wcfm-wcfmu" style="display: block;">
					<p><span class="fa fa-warning"></span><?php printf( __( '%s' . $feature . '%s: Upgrade your WCFM to %sWCFM - Ultimate%s to access this feature. Disable this notice from settings panel using "Disable Ultimate Notice" option.', 'wc-frontend-manager' ), '<strong>', '</strong>', '<a target="_blank" href="http://wclovers.com/product/woocommerce-frontend-manager-ultimate/"><strong>', '</strong></a>' ); ?></p>
				</div>
				<?php
			}
		}
	}
}

if(!function_exists('wcfmgs_feature_help_text_show')) {
	function wcfmgs_feature_help_text_show( $feature, $only_admin = false, $text_only = false ) {
		
		if( wcfm_is_vendor() ) {
			if( !$only_admin ) {
				if( $text_only ) {
					_e( $feature . ': Please ask your Store Admin to upgrade your dashboard to access this feature.', 'wc-frontend-manager' );
				} else {
					?>
					<div id="wcfmu-feature-missing-message" class="wcfm-warn-message wcfm-wcfmu" style="display: block;">
						<p><span class="fa fa-warning"></span>
						<?php printf( __( '%s' . $feature . '%s: Please ask your %sStore Admin%s to upgrade your dashboard to access this feature.', 'wc-frontend-manager' ), '<strong>', '</strong>', '<strong>', '</strong>' ); ?></p>
					</div>
					<?php
				}
			}
		} else {
			if( $text_only ) {
				_e( $feature . ': Associate your WCFM with WCFM - Groups & Staffs to avail this feature.', 'wc-frontend-manager' );
			} else {
				?>
				<div id="wcfmu-feature-missing-message" class="wcfm-warn-message wcfm-wcfmu" style="display: block;">
					<p><span class="fa fa-warning"></span><?php printf( __( '%s' . $feature . '%s: Associate your WCFM with %sWCFM - Groups & Staffs%s to access this feature.', 'wc-frontend-manager' ), '<strong>', '</strong>', '<a target="_blank" href="http://wclovers.com/product/woocommerce-frontend-manager-groups-staffs/"><strong>', '</strong></a>' ); ?></p>
				</div>
				<?php
			}
		}
	}
}

if(!function_exists('wcfma_feature_help_text_show')) {
	function wcfma_feature_help_text_show( $feature, $only_admin = false, $text_only = false ) {
		
		if( wcfm_is_vendor() ) {
			if( !$only_admin ) {
				if( $text_only ) {
					_e( $feature . ': Please contact your Store Admin to access this feature.', 'wc-frontend-manager' );
				} else {
					?>
					<div id="wcfmu-feature-missing-message" class="wcfm-warn-message wcfm-wcfmu" style="display: block;">
						<p><span class="fa fa-warning"></span>
						<?php printf( __( '%s' . $feature . '%s: Please contact your %sStore Admin%s to access this feature.', 'wc-frontend-manager' ), '<strong>', '</strong>', '<strong>', '</strong>' ); ?></p>
					</div>
					<?php
				}
			}
		} else {
			if( $text_only ) {
				_e( $feature . ': Associate your WCFM with WCFM - Analytics to access this feature.', 'wc-frontend-manager' );
			} else {
				?>
				<div id="wcfmu-feature-missing-message" class="wcfm-warn-message wcfm-wcfmu" style="display: block;">
					<p><span class="fa fa-warning"></span><?php printf( __( '%s' . $feature . '%s: Associate your WCFM with %sWCFM - Analytics%s to access this feature.', 'wc-frontend-manager' ), '<strong>', '</strong>', '<a target="_blank" href="http://wclovers.com/product/woocommerce-frontend-manager-analytics/"><strong>', '</strong></a>' ); ?></p>
				</div>
				<?php
			}
		}
	}
}

if( !function_exists( 'wcfm_is_allow_wcfm' ) ) {
	function wcfm_is_allow_wcfm() {
		if( is_user_logged_in() ) {
			$user = wp_get_current_user();
			$allowed_roles = apply_filters( 'wcfm_allwoed_user_rols',  array( 'administrator', 'shop_manager' ) );
			if ( array_intersect( $allowed_roles, (array) $user->roles ) )  {
				return true;
			}
		}
		return false;
	}
}

if( !function_exists( 'wcfm_is_marketplace' ) ) {
	function wcfm_is_marketplace() {
		$active_plugins = (array) get_option( 'active_plugins', array() );
		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}
		
		// WC Vendors Check
		$is_marketplace = ( in_array( 'wc-vendors/class-wc-vendors.php', $active_plugins ) || array_key_exists( 'wc-vendors/class-wc-vendors.php', $active_plugins ) || class_exists( 'WC_Vendors' ) ) ? 'wcvendors' : false;
		
		// WC Marketplace Check
		if( !$is_marketplace )
			$is_marketplace = ( in_array( 'dc-woocommerce-multi-vendor/dc_product_vendor.php', $active_plugins ) || array_key_exists( 'dc-woocommerce-multi-vendor/dc_product_vendor.php', $active_plugins ) || class_exists( 'WCMp' ) ) ? 'wcmarketplace' : false;
		
		// WC Product Vendors Check
		if( !$is_marketplace )
			$is_marketplace = ( in_array( 'woocommerce-product-vendors/woocommerce-product-vendors.php', $active_plugins ) || array_key_exists( 'woocommerce-product-vendors/woocommerce-product-vendors.php', $active_plugins ) ) ? 'wcpvendors' : false;
		
		// Dokan Lite Check
		if( !$is_marketplace )
			$is_marketplace = ( in_array( 'dokan-lite/dokan.php', $active_plugins ) || array_key_exists( 'dokan-lite/dokan.php', $active_plugins ) || class_exists( 'WeDevs_Dokan' ) ) ? 'dokan' : false;
		
		return $is_marketplace;
	}
}

if( !function_exists( 'wcfm_is_vendor' ) ) {
	function wcfm_is_vendor() {
		if( !is_user_logged_in() ) return false;
		
		$is_marketplace = wcfm_is_marketplace();
		
		if( $is_marketplace ) {
			if( 'wcvendors' == $is_marketplace ) {
			  if ( WCV_Vendors::is_vendor( get_current_user_id() ) ) return true;
			} elseif( 'wcmarketplace' == $is_marketplace ) {
				if( is_user_wcmp_vendor( get_current_user_id() ) ) return true;
			} elseif( 'wcpvendors' == $is_marketplace ) {
				if( WC_Product_Vendors_Utils::is_vendor( get_current_user_id() ) ) return true;
			} elseif( 'dokan' == $is_marketplace ) {
				$user = wp_get_current_user();
				if ( in_array( 'seller', (array) $user->roles ) )  return true;
				//if( user_can( get_current_user_id(), 'seller' ) ) return true;
			}
		}
		
		return apply_filters( 'wcfm_is_vendor', false );
	}
}

if( !function_exists( 'wcfm_is_booking' ) ) {
	function wcfm_is_booking() {
		
		// WC Bookings Check
		$is_booking = ( WCFM_Dependencies::wcfm_bookings_plugin_active_check() ) ? 'wcbooking' : false;
		
		return $is_booking;
	}
}

if( !function_exists( 'wcfm_is_subscription' ) ) {
	function wcfm_is_subscription() {
		
		// WC Subscriptions Check
		$is_subscription = ( WCFM_Dependencies::wcfm_subscriptions_plugin_active_check() ) ? 'wcsubscriptions' : false;
		
		return $is_subscription;
	}
}

if( !function_exists( 'wcfm_is_xa_subscription' ) ) {
	function wcfm_is_xa_subscription() {
		
		// XA Subscriptions Check
		$is_xa_subscription = ( WCFM_Dependencies::wcfm_xa_subscriptions_plugin_active_check() && defined( 'HFORCE_WC_SUBSCRIPTION_VERSION' ) ) ? 'xasubscriptions' : false;
		
		return $is_xa_subscription;
	}
}

if(!function_exists('is_wcfm_page')) {
	function is_wcfm_page() {    
		$pages = get_option("wcfm_page_options");
		if( isset( $pages['wc_frontend_manager_page_id'] ) && $pages['wc_frontend_manager_page_id'] ) {
			if ( function_exists('icl_object_id') ) {
				return is_page( icl_object_id( $pages['wc_frontend_manager_page_id'], 'page', true ) ) || wc_post_content_has_shortcode( 'wc_frontend_manager' );
			} else {
				return is_page( $pages['wc_frontend_manager_page_id'] ) || wc_post_content_has_shortcode( 'wc_frontend_manager' );
			}
		}
		return false;
	}
}

if(!function_exists('get_wcfm_page')) {
	function get_wcfm_page( $language_code = '' ) {
		$pages = get_option("wcfm_page_options");
		if( isset($pages['wc_frontend_manager_page_id']) && $pages['wc_frontend_manager_page_id'] ) {
			if ( function_exists('icl_object_id') ) {
				if( $language_code ) {
					echo icl_object_id( $pages['wc_frontend_manager_page_id'], 'page', true, $language_code );
					$wcfm_page = get_permalink( icl_object_id( $pages['wc_frontend_manager_page_id'], 'page', true, $language_code ) );
					$wcfm_page = apply_filters( 'wpml_permalink', $wcfm_page, $language_code );
					return $wcfm_page;
				} else {
					return get_permalink( icl_object_id( $pages['wc_frontend_manager_page_id'], 'page', true ) );
				}
			} else {
				return  get_permalink( $pages['wc_frontend_manager_page_id'] );
			}
		}
		return false;
	}
}

if(!function_exists('get_wcfm_url')) {
	function get_wcfm_url() {
		return apply_filters( 'wcfm_dashboard_home', get_wcfm_page() );
	}
}

if ( ! function_exists( 'is_wcfm_endpoint_url' ) ) {

	/**
	 * is_wcfm_endpoint_url - Check if an endpoint is showing.
	 * @param  string $endpoint
	 * @return bool
	 */
	function is_wcfm_endpoint_url( $endpoint = false ) {
		global $WCFM, $WCFM_Query, $wp;

		$wcfm_endpoints = $WCFM_Query->get_query_vars();

		if ( $endpoint !== false ) {
			if ( ! isset( $wc_endpoints[ $endpoint ] ) ) {
				return false;
			} else {
				$endpoint_var = $wcfm_endpoints[ $endpoint ];
			}

			return isset( $wp->query_vars[ $endpoint_var ] );
		} else {
			foreach ( $wcfm_endpoints as $key => $value ) {
				if ( isset( $wp->query_vars[ $key ] ) ) {
					return true;
				}
			}

			return false;
		}
	}
}

if(!function_exists('get_wcfm_products_url')) {
	function get_wcfm_products_url( $product_status = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_products_url = wcfm_get_endpoint_url( 'wcfm-products', '', $wcfm_page );
		if($product_status) $wcfm_products_url = add_query_arg( 'product_status', $product_status, $wcfm_products_url );
		return $wcfm_products_url;
	}
}

if(!function_exists('get_wcfm_edit_product_url')) {
	function get_wcfm_edit_product_url( $product_id = '', $the_product = array() ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_edit_product_url = wcfm_get_endpoint_url( 'wcfm-products-manage', $product_id, $wcfm_page );
		return $wcfm_edit_product_url;
	}
}

if(!function_exists('get_wcfm_stock_manage_url')) {
	function get_wcfm_stock_manage_url( $product_status = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_stock_manage_url = wcfm_get_endpoint_url( 'wcfm-stock-manage', '', $wcfm_page );
		if($product_status) $wcfm_stock_manage_url = add_query_arg( 'product_status', $product_status, $wcfm_stock_manage_url );
		return apply_filters( 'wcfm_stock_manage_url', $wcfm_stock_manage_url );
	}
}

if(!function_exists('get_wcfm_import_product_url')) {
	function get_wcfm_import_product_url( $step = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_import_product_url = wcfm_get_endpoint_url( 'wcfm-products-import', '', $wcfm_page );
		if($step) $wcfm_import_product_url = add_query_arg( 'step', $step, $wcfm_import_product_url );
		return $wcfm_import_product_url;
	}
}

if(!function_exists('get_wcfm_export_product_url')) {
	function get_wcfm_export_product_url( ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_export_product_url = wcfm_get_endpoint_url( 'wcfm-products-export', '', $wcfm_page );
		return $wcfm_export_product_url;
	}
}

if(!function_exists('get_wcfm_coupons_url')) {
	function get_wcfm_coupons_url() {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_coupons_url = wcfm_get_endpoint_url( 'wcfm-coupons', '', $wcfm_page );
		return $wcfm_coupons_url;
	}
}

if(!function_exists('get_wcfm_coupons_manage_url')) {
	function get_wcfm_coupons_manage_url( $coupon_id = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_coupon_manage_url = wcfm_get_endpoint_url( 'wcfm-coupons-manage', $coupon_id, $wcfm_page );
		return $wcfm_coupon_manage_url;
	}
}

if(!function_exists('get_wcfm_orders_url')) {
	function get_wcfm_orders_url( $order_status = '') {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_orders_url = wcfm_get_endpoint_url( 'wcfm-orders', '', $wcfm_page );
		if( $order_status ) $wcfm_orders_url = add_query_arg( 'order_status', $order_status, $wcfm_orders_url );
		return $wcfm_orders_url;
	}
}

if(!function_exists('get_wcfm_view_order_url')) {
	function get_wcfm_view_order_url($order_id = '') {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_view_order_url = wcfm_get_endpoint_url( 'wcfm-orders-details', $order_id, $wcfm_page );
		return $wcfm_view_order_url;
	}
}

if(!function_exists('get_wcfm_reports_url')) {
	function get_wcfm_reports_url( $range = '', $report_type = 'wcfm-reports-sales-by-date' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_reports_url = wcfm_get_endpoint_url( $report_type, '', $wcfm_page );
		if( $range ) $get_wcfm_reports_url = add_query_arg( 'range', $range, $get_wcfm_reports_url );
		if( $report_type == 'wcfm-reports-sales-by-date' ) $get_wcfm_reports_url = apply_filters( 'wcfm_default_reports_url', $get_wcfm_reports_url );
		return $get_wcfm_reports_url;
	}
}

if(!function_exists('get_wcfm_profile_url')) {
	function get_wcfm_profile_url() {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_profile_url = wcfm_get_endpoint_url( 'wcfm-profile', '', $wcfm_page );
		return $get_wcfm_profile_url;
	}
}

if(!function_exists('get_wcfm_analytics_url')) {
	function get_wcfm_analytics_url( $range = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_analytics_url = wcfm_get_endpoint_url( 'wcfm-analytics', '', $wcfm_page );
		if( $range ) $get_wcfm_analytics_url = add_query_arg( 'range', $range, $get_wcfm_analytics_url );
		return $get_wcfm_analytics_url;
	}
}

if(!function_exists('get_wcfm_settings_url')) {
	function get_wcfm_settings_url() {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_settings_url = wcfm_get_endpoint_url( 'wcfm-settings', '', $wcfm_page );
		return $get_wcfm_settings_url;
	}
}

if(!function_exists('get_wcfm_capability_url')) {
	function get_wcfm_capability_url() {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_capability_url = wcfm_get_endpoint_url( 'wcfm-capability', '', $wcfm_page );
		return $get_wcfm_capability_url;
	}
}

if(!function_exists('get_wcfm_knowledgebase_url')) {
	function get_wcfm_knowledgebase_url() {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_knowledgebase_url = wcfm_get_endpoint_url( 'wcfm-knowledgebase', '', $wcfm_page );
		return $get_wcfm_knowledgebase_url;
	}
}

if(!function_exists('get_wcfm_knowledgebase_manage_url')) {
	function get_wcfm_knowledgebase_manage_url( $knowledgebase_id = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_knowledgebase_manage_url = wcfm_get_endpoint_url( 'wcfm-knowledgebase-manage', $knowledgebase_id, $wcfm_page );
		return $get_wcfm_knowledgebase_manage_url;
	}
}

if(!function_exists('get_wcfm_notices_url')) {
	function get_wcfm_notices_url( ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_notices_url = wcfm_get_endpoint_url( 'wcfm-notices', '', $wcfm_page );
		return $get_wcfm_notices_url;
	}
}

if(!function_exists('get_wcfm_notice_manage_url')) {
	function get_wcfm_notice_manage_url( $topic_id = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_notice_manage_url = wcfm_get_endpoint_url( 'wcfm-notice-manage', $topic_id, $wcfm_page );
		return $get_wcfm_notice_manage_url;
	}
}

if(!function_exists('get_wcfm_notice_view_url')) {
	function get_wcfm_notice_view_url( $topic_id = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_notice_view_url = wcfm_get_endpoint_url( 'wcfm-notice-view', $topic_id, $wcfm_page );
		return $get_wcfm_notice_view_url;
	}
}

if(!function_exists('get_wcfm_messages_url')) {
	function get_wcfm_messages_url( ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_messages_url = wcfm_get_endpoint_url( 'wcfm-messages', '', $wcfm_page );
		return $get_wcfm_messages_url;
	}
}

if(!function_exists('get_wcfm_enquiry_url')) {
	function get_wcfm_enquiry_url( ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_enquiry_url = wcfm_get_endpoint_url( 'wcfm-enquiry', '', $wcfm_page );
		return $get_wcfm_enquiry_url;
	}
}

if(!function_exists('get_wcfm_enquiry_manage_url')) {
	function get_wcfm_enquiry_manage_url( $topic_id = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_enquiry_manage_url = wcfm_get_endpoint_url( 'wcfm-enquiry-manage', $topic_id, $wcfm_page );
		return $get_wcfm_enquiry_manage_url;
	}
}

if(!function_exists('get_wcfm_articles_url')) {
	function get_wcfm_articles_url( $article_status = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_articles_url = wcfm_get_endpoint_url( 'wcfm-articles', '', $wcfm_page );
		if($article_status) $get_wcfm_articles_url = add_query_arg( 'article_status', $article_status, $get_wcfm_articles_url );
		return $get_wcfm_articles_url;
	}
}

if(!function_exists('get_wcfm_articles_manage_url')) {
	function get_wcfm_articles_manage_url( $article_id = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_articles_manage_url = wcfm_get_endpoint_url( 'wcfm-articles-manage', $article_id, $wcfm_page );
		return $get_wcfm_articles_manage_url;
	}
}

if(!function_exists('get_wcfm_vendors_url')) {
	function get_wcfm_vendors_url( ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_vendors_url = wcfm_get_endpoint_url( 'wcfm-vendors', '', $wcfm_page );
		return $get_wcfm_vendors_url;
	}
}

if(!function_exists('get_wcfm_vendors_manage_url')) {
	function get_wcfm_vendors_manage_url( $vendor_id = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_vendors_manage_url = wcfm_get_endpoint_url( 'wcfm-vendors-manage', $vendor_id, $wcfm_page );
		return $get_wcfm_vendors_manage_url;
	}
}

if(!function_exists('get_wcfm_vendors_commission_url')) {
	function get_wcfm_vendors_commission_url( ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_vendors_commission_url = wcfm_get_endpoint_url( 'wcfm-vendors-commission', '', $wcfm_page );
		return $get_wcfm_vendors_commission_url;
	}
}

if(!function_exists('get_wcfm_customers_url')) {
	function get_wcfm_customers_url( ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_customers_url = wcfm_get_endpoint_url( 'wcfm-customers', '', $wcfm_page );
		return $get_wcfm_customers_url;
	}
}

if(!function_exists('get_wcfm_customers_manage_url')) {
	function get_wcfm_customers_manage_url( $customer_id = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_customers_manage_url = wcfm_get_endpoint_url( 'wcfm-customers-manage', $customer_id, $wcfm_page );
		return $get_wcfm_customers_manage_url;
	}
}

if(!function_exists('get_wcfm_customers_details_url')) {
	function get_wcfm_customers_details_url( $customer_id = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_customers_details_url = wcfm_get_endpoint_url( 'wcfm-customers-details', $customer_id, $wcfm_page );
		return $get_wcfm_customers_details_url;
	}
}

if(!function_exists('get_wcfm_listings_url')) {
	function get_wcfm_listings_url( $listing_status = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_listings_dashboard_url = wcfm_get_endpoint_url( 'wcfm-listings', '', $wcfm_page );
		if($listing_status) $wcfm_listings_dashboard_url = add_query_arg( 'listing_status', $listing_status, $wcfm_listings_dashboard_url );
		return $wcfm_listings_dashboard_url;
	}
}

if(!function_exists('get_wcfm_bookings_dashboard_url')) {
	function get_wcfm_bookings_dashboard_url( $booking_status = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_bookings_dashboard_url = wcfm_get_endpoint_url( 'wcfm-bookings-dashboard', '', $wcfm_page );
		return $wcfm_bookings_dashboard_url;
	}
}

if(!function_exists('get_wcfm_bookings_url')) {
	function get_wcfm_bookings_url( $booking_status = '') {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_bookings_url = wcfm_get_endpoint_url( 'wcfm-bookings', '', $wcfm_page );
		if( $booking_status ) $wcfm_bookings_url = add_query_arg( 'booking_status', $booking_status, $wcfm_bookings_url );
		return $wcfm_bookings_url;
	}
}

if(!function_exists('get_wcfm_view_booking_url')) {
	function get_wcfm_view_booking_url( $booking_id = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_view_booking_url = wcfm_get_endpoint_url( 'wcfm-bookings-details', $booking_id, $wcfm_page );
		return $wcfm_view_booking_url;
	}
}

if(!function_exists('is_wcfm_analytics')) {
	function is_wcfm_analytics() {
		$wcfm_options = (array) get_option( 'wcfm_options' );
		$is_analytics_disabled = isset( $wcfm_options['analytics_disabled'] ) ? $wcfm_options['analytics_disabled'] : 'no';
		if( $is_analytics_disabled == 'yes' ) return false;
		return true;
	}
}

// WCMp Payments URL
if(!function_exists('wcfm_payments_url')) {
	function wcfm_payments_url( ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_payments_url = wcfm_get_endpoint_url( 'wcfm-payments', '', $wcfm_page );
		return $get_wcfm_payments_url;
	}
}

// WCMp Withdrawal URL
if(!function_exists('wcfm_withdrawal_url')) {
	function wcfm_withdrawal_url( ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_withdrawal_url = wcfm_get_endpoint_url( 'wcfm-withdrawal', '', $wcfm_page );
		return $get_wcfm_withdrawal_url;
	}
}

// WCMp Transaction Details URL
if(!function_exists('wcfm_transaction_details_url')) {
	function wcfm_transaction_details_url( $transaction_id = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_transaction_details_url = wcfm_get_endpoint_url( 'wcfm-transaction-details', $transaction_id, $wcfm_page );
		return $get_wcfm_transaction_details_url;
	}
}

// WCfM Navigation URL
if(!function_exists('wcfm_get_navigation_url')) {
	function wcfm_get_navigation_url( $endpoint ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$navigation_url = $wcfm_page;
		
		switch( $endpoint ) {
			case 'products':
			  $navigation_url = wcfm_get_endpoint_url( 'wcfm-products', '', $wcfm_page );
			break;
			
			case 'coupons':
			  $navigation_url = wcfm_get_endpoint_url( 'wcfm-coupons', '', $wcfm_page );
			break;
			
			case 'orders':
			  $navigation_url = wcfm_get_endpoint_url( 'wcfm-orders', '', $wcfm_page );
			break;
			
			case 'withdraw':
			  $navigation_url = wcfm_get_endpoint_url( 'wcfm-withdrawal', '', $wcfm_page );
			break;
			
			case 'settings':
			  $navigation_url = wcfm_get_endpoint_url( 'wcfm-settings', '', $wcfm_page );
			break;
			
			case 'payment':
			  $navigation_url = wcfm_get_endpoint_url( 'wcfm-payments', '', $wcfm_page );
			break;
			
			case 'reports':
			  $navigation_url = get_wcfm_reports_url();
			break;
			
			case 'support':
				if( WCFM_Dependencies::wcfmu_plugin_active_check() ) {
					$navigation_url = wcfm_get_endpoint_url( 'wcfm-support', '', $wcfm_page );
				} else {
					$navigation_url = wcfm_get_endpoint_url( 'wcfm-enquiry', '', $wcfm_page );
				}
			break;
			
			case 'subscription':
				if( WCFM_Dependencies::wcfmu_plugin_active_check() ) {
					$navigation_url = wcfm_get_endpoint_url( 'wcfm-subscription-packs', '', $wcfm_page );
				} else {
					$navigation_url = $wcfm_page;
				}
			break;
			
			default:
				$navigation_url = $wcfm_page;
			break;
		}
		
		return apply_filters( 'wcfm_navigation_url', $navigation_url, $endpoint );
	}
}

if(!function_exists('get_wcfm_articles_manager_messages')) {
	function get_wcfm_articles_manager_messages() {
		global $WCFM;
		
		$messages = apply_filters( 'wcfm_validation_messages_article_manager', array(
																																								'no_title' => __('Please insert Article Title before submit.', 'wc-frontend-manager'),
																																								'article_saved' => __('Article Successfully Saved.', 'wc-frontend-manager'),
																																								'article_pending' => __( 'Article Successfully submitted for moderation.', 'wc-frontend-manager' ),
																																								'article_published' => __('Article Successfully Published.', 'wc-frontend-manager'),
																																								) );
		
		return $messages;
	}
}

if(!function_exists('get_wcfm_products_manager_messages')) {
	function get_wcfm_products_manager_messages() {
		global $WCFM;
		
		$messages = apply_filters( 'wcfm_validation_messages_product_manager', array(
																																								'no_title' => __('Please insert Product Title before submit.', 'wc-frontend-manager'),
																																								'sku_unique' => __('Product SKU must be unique.', 'wc-frontend-manager'),
																																								'variation_sku_unique' => __('Variation SKU must be unique.', 'wc-frontend-manager'),
																																								'product_saved' => __('Product Successfully Saved.', 'wc-frontend-manager'),
																																								'product_pending' => __( 'Product Successfully submitted for moderation.', 'wc-frontend-manager' ),
																																								'product_published' => __('Product Successfully Published.', 'wc-frontend-manager'),
																																								) );
		
		return $messages;
	}
}

if(!function_exists('get_wcfm_coupons_manage_messages')) {
	function get_wcfm_coupons_manage_messages() {
		global $WCFM;
		
		$messages = array(
											'no_title' => __( 'Please insert atleast Coupon Title before submit.', 'wc-frontend-manager' ),
											'coupon_saved' => __( 'Coupon Successfully Saved.', 'wc-frontend-manager' ),
											'coupon_published' => __( 'Coupon Successfully Published.', 'wc-frontend-manager' ),
											);
		
		return $messages;
	}
}

if(!function_exists('get_wcfm_knowledgebase_manage_messages')) {
	function get_wcfm_knowledgebase_manage_messages() {
		global $WCFM;
		
		$messages = array(
											'no_title' => __( 'Please insert atleast Knowledgebase Title before submit.', 'wc-frontend-manager' ),
											'knowledgebase_saved' => __( 'Knowledgebase Successfully Saved.', 'wc-frontend-manager' ),
											'knowledgebase_published' => __( 'Knowledgebase Successfully Published.', 'wc-frontend-manager' ),
											);
		
		return $messages;
	}
}

if(!function_exists('get_wcfm_notice_manage_messages')) {
	function get_wcfm_notice_manage_messages() {
		global $WCFM;
		
		$messages = array(
											'no_title' => __( 'Please insert atleast Topic Title before submit.', 'wc-frontend-manager' ),
											'notice_saved' => __( 'Topic Successfully Saved.', 'wc-frontend-manager' ),
											'notice_published' => __( 'Topic Successfully Published.', 'wc-frontend-manager' ),
											);
		
		return $messages;
	}
}

if(!function_exists('get_wcfm_notice_view_messages')) {
	function get_wcfm_notice_view_messages() {
		global $WCFM;
		
		$messages = array(
											'no_title' => __( 'Please write something before submit.', 'wc-frontend-manager' ),
											'notice_failed' => __( 'Reply send failed, try again.', 'wc-frontend-manager' ),
											'reply_published' => __( 'Reply Successfully Send.', 'wc-frontend-manager' ),
											);
		
		return $messages;
	}
}

if(!function_exists('get_wcfm_enquiry_manage_messages')) {
	function get_wcfm_enquiry_manage_messages() {
		global $WCFM;
		
		$messages = array(
											'no_name' => __( 'Name is required.', 'wc-frontend-manager' ),
											'no_email' => __( 'Email is required.', 'wc-frontend-manager' ),
											'no_enquiry' => __( 'Please insert your enquiry before submit.', 'wc-frontend-manager' ),
											'enquiry_saved' => __( 'Your enquiry successfully sent.', 'wc-frontend-manager' ),
											'enquiry_published' => __( 'Enquiry reply successfully published.', 'wc-frontend-manager' ),
											);
		
		return $messages;
	}
}

if(!function_exists('get_wcfm_customers_manage_messages')) {
	function get_wcfm_customers_manage_messages() {
		global $WCFMu;
		
		$messages = array(
											'no_username' => __( 'Please insert Customer Username before submit.', 'wc-frontend-manager' ),
											'no_email' => __( 'Please insert Customer Email before submit.', 'wc-frontend-manager' ),
											'username_exists' => __( 'This Username already exists.', 'wc-frontend-manager' ),
											'email_exists' => __( 'This Email already exists.', 'wc-frontend-manager' ),
											'customer_failed' => __( 'Customer Saving Failed.', 'wc-frontend-manager' ),
											'customer_saved' => __( 'Customer Successfully Saved.', 'wc-frontend-manager' ),
											);
		
		return $messages;
	}
}

if(!function_exists('get_wcfm_dashboard_messages')) {
	function get_wcfm_dashboard_messages() {
		global $WCFM;
		
		$messages = array(
											"product_approve_confirm"            => __( "Are you sure and want to approve / publish this 'Product'?", "wc-frontend-manager" ),
											"article_delete_confirm"             => __( "Are you sure and want to delete this 'Article'?\nYou can't undo this action ...", "wc-frontend-manager" ),
											"product_delete_confirm"             => __( "Are you sure and want to delete this 'Product'?\nYou can't undo this action ...", "wc-frontend-manager" ),
											"message_delete_confirm"             => __( "Are you sure and want to delete this 'Message'?\nYou can't undo this action ...", "wc-frontend-manager" ),
											"order_delete_confirm"               => __( "Are you sure and want to delete this 'Order'?\nYou can't undo this action ...", "wc-frontend-manager" ),
											"enquiry_delete_confirm"             => __( "Are you sure and want to delete this 'Enquiry'?\nYou can't undo this action ...", "wc-frontend-manager" ),
											"support_delete_confirm"             => __( "Are you sure and want to delete this 'Support Ticket'?\nYou can't undo this action ...", "wc-frontend-manager" ),
											"follower_delete_confirm"            => __( "Are you sure and want to delete this 'Follower'?\nYou can't undo this action ...", "wc-frontend-manager" ),
											"following_delete_confirm"           => __( "Are you sure and want to delete this 'Following'?\nYou can't undo this action ...", "wc-frontend-manager" ),
											"order_mark_complete_confirm"        => __( "Are you sure and want to 'Mark as Complete' this Order?", "wc-frontend-manager" ),
											"booking_mark_complete_confirm"      => __( "Are you sure and want to 'Mark as Confirmed' this Booking?", "wc-frontend-manager" ),
											"appointment_mark_complete_confirm"  => __( "Are you sure and want to 'Mark as Complete' this Appointment?", "wc-frontend-manager" ),
											"add_new"                            => __( "Add New", "wc-frontend-manager" ),
											"select_all"                         => __( "Select all", "wc-frontend-manager" ),
											"select_none"                        => __( "Select none", "wc-frontend-manager" ),
											"any_attribute"                      => __( "Any", "wc-frontend-manager" ),
											"add_attribute_term"                 => __( "Enter a name for the new attribute term:", "wc-frontend-manager" ),
											"wcfmu_upgrade_notice"               => __( "Please upgrade your WC Frontend Manager to Ultimate version and avail this feature.", "wc-frontend-manager" ),
											"pdf_invoice_upgrade_notice"         => __( "Install WC Frontend Manager Ultimate and WooCommerce PDF Invoices & Packing Slips to avail this feature.", "wc-frontend-manager" ),
											"wcfm_bulk_action_no_option"         => __( "Please select some element first!!", "wc-frontend-manager" ),
											"wcfm_bulk_action_confirm"           => __( "Are you sure and want to do this?\nYou can't undo this action ...", "wc-frontend-manager" ),
											"review_status_update_confirm"       => __( "Are you sure and want to do this?", "wc-frontend-manager" ),
											"everywhere"                         => __( "Everywhere Else", "wc-frontend-manager" ),
											"required_message"                   => __( 'This field is required.', 'wc-frontend-manager' ),
											"choose_select2"                     => __( "Choose ", "wc-frontend-manager" ),
											"search_attribute_select2"           => __( "Search for a attribute ...", "wc-frontend-manager" ),
											"search_product_select2"             => __( "Search for a product ...", "wc-frontend-manager" ),
											"choose_category_select2"            => __( "Choose Categoies ...", "wc-frontend-manager" ),
											"choose_listings_select2"            => __( "Choose Listings ...", "wc-frontend-manager" ),
											"choose_vendor_select2"              => __( "Choose Vendor ...", "wc-frontend-manager" ),
											"no_category_select2"                => __( "No categories", "wc-frontend-manager" ),
											"select2_searching"                  => __( 'Searching ...', 'wc-frontend-manager' ),
											"select2_no_result"                  => __( 'No matching result found.', 'wc-frontend-manager' ),
											"select2_loading_more"               => __( 'Loading ...', 'wc-frontend-manager' ),
											"select2_minimum_input"              => __( 'Minimum input character ', 'wc-frontend-manager' ),
											"wcfm_product_popup_next"            => __( 'Next', 'wc-frontend-manager' ),
											"wcfm_product_popup_previous"        => __( 'Previous', 'wc-frontend-manager' ),
											"wcfm_multiblick_sortable_help"      => __( 'Drag to re-arrange blocks', 'wc-frontend-manager' ),
											);
		
		return apply_filters( 'wcfm_dashboard_messages', $messages );
	}
}

if(!function_exists('get_wcfm_message_types')) {
	function get_wcfm_message_types() {
		global $WCFM;
		
		$message_types = array(
											'direct'            => __( 'Direct Message', 'wc-frontend-manager' ),
											'product_review'    => __( 'Approve Product', 'wc-frontend-manager' ),
											'new_product'       => __( 'New Product', 'wc-frontend-manager' ),
											'new_taxonomy_term' => __( 'New Category', 'wc-frontend-manager' ),
											'order'             => __( 'New Order', 'wc-frontend-manager' ),
											);
		
		return apply_filters( 'wcfm_message_types', $message_types );
	}
}

/**
 * Get endpoint URL.
 *
 * Gets the URL for an endpoint, which varies depending on permalink settings.
 *
 * @param  string $endpoint
 * @param  string $value
 * @param  string $permalink
 *
 * @return string
 */
function wcfm_get_endpoint_url( $endpoint, $value = '', $permalink = '' ) {
	global $post;
	if ( ! $permalink ) {
		$permalink = get_permalink( $post );
	}
	
	$wcfm_modified_endpoints = (array) get_option( 'wcfm_endpoints' );
	$endpoint = ! empty( $wcfm_modified_endpoints[ $endpoint ] ) ? $wcfm_modified_endpoints[ $endpoint ] : str_replace( 'wcfm-', '', $endpoint );

	if ( get_option( 'permalink_structure' ) ) {
		if ( strstr( $permalink, '?' ) ) {
			$query_string = '?' . parse_url( $permalink, PHP_URL_QUERY );
			$permalink    = current( explode( '?', $permalink ) );
		} else {
			$query_string = '';
		}
		$url = trailingslashit( $permalink ) . $endpoint . '/' . $value . $query_string;
	} else {
		$url = add_query_arg( $endpoint, $value, $permalink );
	}

	return apply_filters( 'wcfm_get_endpoint_url', $url, $endpoint, $value, $permalink );
}

function wcfm_get_user_posts_count( $user_id = 0, $post_type = 'product', $post_status = 'publish', $custom_args = array() ) {
	global $WCFM;
	
	//$post_count = count_user_posts( $user_id, $post_type );
	if( !$user_id ) $user_id  = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
	
	$args = array(
			'post_type'     => $post_type,
			'post_status'   => $post_status,
			'posts_per_page' => -1,
			'suppress_filters' => 0
	);
	$args = array_merge( $args, $custom_args );
	if( $post_type == 'product' ) {
		$args = apply_filters( 'wcfm_products_args', $args );
	} else {
		if( $user_id ) $args['author'] = $user_id;
	}
	$args['fields'] = 'ids';
	$ps = get_posts($args);
	return count($ps);
}

function wcfm_query_time_range_filter( $sql, $time, $interval = '7day', $start_date = '', $end_date = '', $table_handler = 'commission' ) {
	switch( $interval ) {
		case 'year' :
			$sql .= " AND YEAR( {$table_handler}.{$time} ) = YEAR( CURDATE() )";
			break;

		case 'last_month' :
			$sql .= " AND MONTH( {$table_handler}.{$time} ) = MONTH( NOW() ) - 1";
			break;

		case 'month' :
			$sql .= " AND MONTH( {$table_handler}.{$time} ) = MONTH( NOW() )";
			break;

		case 'custom' :
			$start_date = ! empty( $_GET['start_date'] ) ? sanitize_text_field( $_GET['start_date'] ) : '';
			$end_date = ! empty( $_GET['end_date'] ) ? sanitize_text_field( $_GET['end_date'] ) : '';
			if( $start_date ) $start_date = date( 'Y-m-d', strtotime( $start_date ) );
			if( $end_date ) $end_date = date( 'Y-m-d', strtotime( $end_date ) );

			$sql .= " AND DATE( {$table_handler}.{$time} ) BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
			break;

		case 'default' :
		case '7day' :
			$sql .= " AND DATE( {$table_handler}.{$time} ) BETWEEN DATE_SUB( NOW(), INTERVAL 7 DAY ) AND NOW()";
			break;
	}
	
	return $sql;
}

/**
 * WCFM Enquiry Tab - tab manager support
 *
 * @since		3.4.6
 */
function wcfm_enquiry_product_tab( $tabs) {
	global $WCFM;
	if( apply_filters( 'wcfm_is_pref_enquiry_tab', true ) && apply_filters( 'wcfm_is_pref_enquiry', true ) ) {
		unset( $tabs['wcmp_customer_qna'] );
		unset( $tabs['seller_enquiry_form'] );
		$tabs['wcfm_enquiry_tab'] = apply_filters( 'wcfm_enquiry_tab_element',array(
																																								'title' 	=> __( 'Enquiries', 'wc-frontend-manager' ),
																																								'priority' 	=> apply_filters( 'wcfm_enquiry_tab_priority', 100 ),
																																								'callback' 	=> array( $WCFM->wcfm_enquiry, 'wcfm_enquiry_product_tab_content' )
																																							) );
	}
	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'wcfm_enquiry_product_tab', 100 );

/**
 * WCFM BuddyP-ress Functions
 *
 * @since		3.4.2
 */
function bp_wcfm_user_nav_item() {
	global $bp;
	
	if( !$bp || !$bp->displayed_user || !$bp->displayed_user->userdata || !$bp->displayed_user->id ) return;
	
	$other_member_profile = false;
	
	if( is_user_logged_in() ) {
	$current_user_id = get_current_user_id();
		if( $current_user_id == $bp->displayed_user->id ) {
			$pages = get_option("wcfm_page_options");
			$wcfm_page = get_post( $pages['wc_frontend_manager_page_id'] );
			
			$args = array(
							'name' => $wcfm_page->post_title,
							'slug' => $wcfm_page->post_name,
							'default_subnav_slug' => $wcfm_page->post_name,
							'position' => 50,
							'screen_function' => 'bp_wcfm_user_nav_item_screen',
							'item_css_id' => $wcfm_page->post_name
			);
		
			bp_core_new_nav_item( $args );
		} else {
			$other_member_profile = true;
		}
	} else {
		$other_member_profile = true;
	}
	
	if( $other_member_profile ) {
		do_action( 'wcfm_buddypress_show_vendor_store_link', $bp->displayed_user->id );
	}
}

function bp_wcfm_set_as_current_component( $is_current_component, $component ) {
	if ( empty( $component ) ) {
		return false;
	}

	if( $component == 'wcfm' ) {
		if( is_wcfm_page() ) {
			$is_current_component = true;
		}
	}
	
	return $is_current_component;
}

if( apply_filters( 'wcfm_is_pref_buddypress', true ) && WCFM_Dependencies::wcfm_biddypress_plugin_active_check() ) {
	$wcfm_options = (array) get_option( 'wcfm_options' );
	$wcfm_module_options = isset( $wcfm_options['module_options'] ) ? $wcfm_options['module_options'] : array();
	$wcfm_buddypress_off = ( isset( $wcfm_module_options['buddypress'] ) ) ? $wcfm_module_options['buddypress'] : 'no';
  if( $wcfm_buddypress_off == 'no' ) {
		add_filter( 'bp_is_current_component', 'bp_wcfm_set_as_current_component', 10, 2 );
		add_action( 'bp_setup_nav', 'bp_wcfm_user_nav_item', 99 );
	}
}

/**
 * the calback function from our nav item arguments
 */
function bp_wcfm_user_nav_item_screen() {
	add_action( 'bp_template_content', 'bp_wcfm_screen_content' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

/**
 * the function hooked to bp_template_content, this hook is in plugns.php
 */
function bp_wcfm_screen_content() {
	if( wcfm_is_allow_wcfm() ) {
	  echo do_shortcode( '[wcfm]' );
	}
}

if(!function_exists('wcfm_create_log')) {
	function wcfm_create_log( $info ) {
		if(  defined('DOING_AJAX') ) return;
		
		$upload_dir      = wp_upload_dir();

		$files = array(
			array(
				'base' 		=> $upload_dir['basedir'] . '/wcfm',
				'file' 		=> 'error_info.log',
				'content' 	=> $info . "\r\n",
			)
		);

		foreach ( $files as $file ) {
			if ( wp_mkdir_p( $file['base'] ) ) {
				if ( $file_handle = @fopen( trailingslashit( $file['base'] ) . $file['file'], 'a' ) ) {
					fwrite( $file_handle, $file['content'] );
					fclose( $file_handle );
				}
			}
		}
	}
}

function wcfm_wp_date_format_to_js( $date_format ) {
	$date_format = strtoupper( $date_format );
	$date_format = str_replace( 'F', 'MMMM', $date_format );
	$date_format = str_replace( 'J', 'D', $date_format );
	$date_format = str_replace( 'Y', 'YYYY', $date_format );
	
	return apply_filters( 'wcfm_wp_date_format_to_js', $date_format );
}

add_filter( 'wp_mail_content_type', function( $content_type ) {
	if( defined('DOING_WCFM_EMAIL') ) {
		return 'text/html';
	}
	
	return $content_type;
});

add_filter( 'wp_mail', function( $email ) {
	if( defined('DOING_WCFM_EMAIL') && !defined('DOING_WCFM_RESTRICTED_EMAIL') ) {
		$wcfm_options = get_option( 'wcfm_options', array() );
		$email_cc_address = isset( $wcfm_options['email_cc_address'] ) ? $wcfm_options['email_cc_address'] : '';
		$email_bcc_address = isset( $wcfm_options['email_bcc_address'] ) ? $wcfm_options['email_bcc_address'] : '';
		if( is_array( $email['headers'] ) ) {
			$email['headers'][] = 'Content-Type:  text/html';
			if( $email_cc_address ) {
				$email['headers'][] = 'cc: '.$email_cc_address;
			}
			if( $email_bcc_address ) {
				$email['headers'][] = 'Bcc: '.$email_bcc_address;
			}
		} else {
			$email['headers'] .= 'Content-Type:  text/html'."\r\n";
			if( $email_cc_address ) {
				$email['headers'] .= 'cc: '.$email_cc_address."\r\n";
			}
			if( $email_bcc_address ) {
				$email['headers'] .= 'Bcc: '.$email_bcc_address."\r\n";
			}
		}
	}
	return $email;               
});

// Function to change sender name
function wwcfm_email_from_name( $email_from_name ) {
	if( defined('DOING_WCFM_EMAIL') && !defined('DOING_WCFM_RESTRICTED_EMAIL') ) {
		$wcfm_options = get_option( 'wcfm_options', array() );
		$email_from_name = isset( $wcfm_options['email_from_name'] ) ? $wcfm_options['email_from_name'] : get_bloginfo( 'name' );
	}
	return $email_from_name;
}
add_filter( 'wp_mail_from_name', 'wwcfm_email_from_name' );

// Function to change email address
function wcfm_email_from_address( $email_from_address ) {
	if( defined('DOING_WCFM_EMAIL') && !defined('DOING_WCFM_RESTRICTED_EMAIL') ) {
		$wcfm_options = get_option( 'wcfm_options', array() );
		$email_from_address = isset( $wcfm_options['email_from_address'] ) ? $wcfm_options['email_from_address'] : get_option('admin_email');
	}
  return $email_from_address;
}
add_filter( 'wp_mail_from', 'wcfm_email_from_address' );

function wcfm_force_user_can_richedit( $is_allow ) {
	return true;
}

// WCfM Video Tutorial
function wcfm_video_tutorial( $video_url ) {
	if( !$video_url ) return;
	?>
	<p class="wcfm_tutorials_wrapper">
	  <a class="wcfm_tutorials" href="<?php echo $video_url; ?>">
	    <span class="wcfm_tutorials_icon fa fa-video-camera"></span>
	    <span class='wcfm_tutorials_label'><?php _e( 'Tutorial', 'wc-frontend-manager' ); ?></span>
	  </a>
	</p>
	<?php
}
 
function wcfm_removeslashes( $string ) {
	$string=implode("",explode("\\",$string));
	return stripslashes(trim($string));
}

/*add_filter( 'locale', function( $locale ) {
	global $_SESSION;
	if( !is_admin() ) {
		if( isset( $_SESSION['wcfm_my_locale'] ) && !empty( $_SESSION['wcfm_my_locale'] ) ) {
			$locale = $_SESSION['wcfm_my_locale'];
		}
	}
	return $locale;
});*/
?>