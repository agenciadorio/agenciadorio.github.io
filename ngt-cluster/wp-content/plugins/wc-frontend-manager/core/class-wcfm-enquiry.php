<?php
/**
 * WCFM plugin core
 *
 * Enquiry board core
 *
 * @author 		WC Lovers
 * @package 	wcfm/core
 * @version   3.2.8
 */
 
class WCFM_Enquiry {
	
	public $wcfm_myaccount_inquiry_endpoint = 'inquiry';
	public $wcfm_myaccount_view_inquiry_endpoint = 'view-inquiry';

	public function __construct() {
		global $WCFM;
		
		$wcfm_myac_modified_endpoints = get_option( 'wcfm_myac_endpoints', array() );
		$this->wcfm_myaccount_inquiry_endpoint = ! empty( $wcfm_myac_modified_endpoints['inquiry'] ) ? $wcfm_myac_modified_endpoints['inquiry'] : 'inquiry';
		$this->wcfm_myaccount_view_inquiry_endpoint = ! empty( $wcfm_myac_modified_endpoints['view-inquiry'] ) ? $wcfm_myac_modified_endpoints['view-inquiry'] : 'view-inquiry';
		
		add_filter( 'wcfm_query_vars', array( &$this, 'wcfm_enquiry_query_vars' ), 20 );
		add_filter( 'wcfm_endpoint_title', array( &$this, 'wcfm_enquiry_endpoint_title' ), 20, 2 );
		add_action( 'init', array( &$this, 'wcfm_enquiry_init' ), 20 );
		
		// Enquiry Endpoint Edit
		add_filter( 'wcfm_endpoints_slug', array( $this, 'enquiry_wcfm_endpoints_slug' ) );
		
		// Enquiry Load Scripts
		add_action( 'wcfm_load_scripts', array( &$this, 'load_scripts' ), 30 );
		add_action( 'after_wcfm_load_scripts', array( &$this, 'load_scripts' ), 30 );
		
		// Enquiry Load Styles
		add_action( 'wcfm_load_styles', array( &$this, 'load_styles' ), 30 );
		add_action( 'after_wcfm_load_styles', array( &$this, 'load_styles' ), 30 );
		
		// Enquiry Load views
		add_action( 'wcfm_load_views', array( &$this, 'load_views' ), 30 );
		
		// Enquiry Ajax Controllers
		add_action( 'after_wcfm_ajax_controller', array( &$this, 'ajax_controller' ) );
		add_action( 'wp_ajax_nopriv_wcfm_ajax_controller', array( &$this, 'ajax_controller' ) );
		
		// My Account Support End Point
		add_action( 'init', array( &$this, 'wcfm_enquiry_my_account_endpoints' ) );
		
		// My Account Support Query Vars
		add_filter( 'query_vars', array( &$this, 'wcfm_enquiry_my_account_query_vars' ), 0 );
		
		// My Account Support Rule Flush
		register_activation_hook( $WCFM->file, array( &$this,'wcfm_enquiry_my_account_flush_rewrite_rules' ) );
		register_deactivation_hook( $WCFM->file, array( &$this, 'wcfm_enquiry_my_account_flush_rewrite_rules' ) );
		
		// My Account Support Menu
		add_filter( 'woocommerce_account_menu_items', array( &$this, 'wcfm_enquiry_my_account_menu_items' ), 200 );
		
		// My Account Support End Point Title
		add_filter( 'the_title', array( &$this, 'wcfm_enquiry_my_account_endpoint_title' ) );
		
		// My Account Support End Point Content
		add_action( 'woocommerce_account_'.$this->wcfm_myaccount_inquiry_endpoint.'_endpoint', array( &$this, 'wcfm_enquiry_my_account_endpoint_content' ) );
		add_action( 'woocommerce_account_'.$this->wcfm_myaccount_view_inquiry_endpoint.'_endpoint', array( &$this, 'wcfm_enquiry_view_my_account_endpoint_content' ) );
		
		// Delete Enquiry
		add_action( 'wp_ajax_delete_wcfm_enquiry', array( &$this, 'delete_wcfm_enquiry' ) );
		
		// Enquiry tab on Single Product
		if( apply_filters( 'wcfm_is_pref_enquiry_tab', true ) ) {
			//add_filter( 'woocommerce_product_tabs', array( &$this, 'wcfm_enquiry_product_tab' ) );
		}
		
		// Single Product page enquiry button
		add_action( 'woocommerce_single_product_summary',	array( &$this, 'wcfm_enquiry_button' ), 35 );
		
		// WCFM Marketplace Store enquiry button
		add_action( 'wcfmmp_store_enquiry',	array( &$this, 'wcfmmp_store_enquiry_button' ), 35 );
		
		// Enquiry list in WCFM Dashboard
		add_action( 'after_wcfm_dashboard_zone_analytics', array( $this, 'wcfm_dashboard_enquiry_list' ) );
		
		// Enquiry direct message type
		add_filter( 'wcfm_message_types', array( &$this, 'wcfm_enquiry_message_types' ), 25 );
		
		//enqueue scripts
		add_action('wp_enqueue_scripts', array(&$this, 'wcfm_enquiry_scripts'));
		//enqueue styles
		add_action('wp_enqueue_scripts', array(&$this, 'wcfm_enquiry_styles'));
	}
	
	/**
   * Enquiry Query Var
   */
  function wcfm_enquiry_query_vars( $query_vars ) {
  	$wcfm_modified_endpoints = (array) get_option( 'wcfm_endpoints' );
  	
		$query_enquiry_vars = array(
			'wcfm-enquiry'                 => ! empty( $wcfm_modified_endpoints['wcfm-enquiry'] ) ? $wcfm_modified_endpoints['wcfm-enquiry'] : 'enquiry',
			'wcfm-enquiry-manage'          => ! empty( $wcfm_modified_endpoints['wcfm-enquiry-manage'] ) ? $wcfm_modified_endpoints['wcfm-enquiry-manage'] : 'enquiry-manage'
		);
		
		$query_vars = array_merge( $query_vars, $query_enquiry_vars );
		
		return $query_vars;
  }
  
  /**
   * Enquiry End Point Title
   */
  function wcfm_enquiry_endpoint_title( $title, $endpoint ) {
  	global $wp;
  	switch ( $endpoint ) {
  		case 'wcfm-enquiry' :
				$title = __( 'Enquiry Dashboard', 'wc-frontend-manager' );
			break;
			case 'wcfm-enquiry-manage' :
				$title = __( 'Enquiry Manager', 'wc-frontend-manager' );
			break;
  	}
  	
  	return $title;
  }
  
  /**
   * Enquiry Endpoint Intialize
   */
  function wcfm_enquiry_init() {
  	global $WCFM_Query;
	
		// Intialize WCFM End points
		$WCFM_Query->init_query_vars();
		$WCFM_Query->add_endpoints();
		
		add_rewrite_endpoint( $this->wcfm_myaccount_inquiry_endpoint, EP_ROOT | EP_PAGES );
		add_rewrite_endpoint( $this->wcfm_myaccount_view_inquiry_endpoint, EP_ROOT | EP_PAGES );
		
		if( !get_option( 'wcfm_updated_end_point_Enquiry' ) ) {
			// Flush rules after endpoint update
			flush_rewrite_rules();
			update_option( 'wcfm_updated_end_point_Enquiry', 1 );
		}
  }
  
  /**
	 * Enquiry Endpoiint Edit
	 */
	function enquiry_wcfm_endpoints_slug( $endpoints ) {
		
		$enquiry_endpoints = array(
													'wcfm-enquiry'          => 'enquiry',
													'wcfm-enquiry-manage'   => 'enquiry-manage',
													);
		
		$endpoints = array_merge( $endpoints, $enquiry_endpoints );
		
		return $endpoints;
	}
  
  /**
   * Enquiry Scripts
   */
  public function load_scripts( $end_point ) {
	  global $WCFM;
    
	  switch( $end_point ) {
	  	case 'wcfm-enquiry':
      	$WCFM->library->load_datatable_lib();
      	$WCFM->library->load_select2_lib();
      	wp_enqueue_script( 'wcfm_enquiry_js', $WCFM->library->js_lib_url . 'enquiry/wcfm-script-enquiry.js', array('jquery'), $WCFM->version, true );
      	
      	$wcfm_screen_manager_data = array();
    		if( !$WCFM->is_marketplace || wcfm_is_vendor() ) {
	    		$wcfm_screen_manager_data[3] = 'yes';
	    	}
	    	$wcfm_screen_manager_data = apply_filters( 'wcfm_enquiry_screen_manage', $wcfm_screen_manager_data );
	    	wp_localize_script( 'wcfm_enquiry_js', 'wcfm_enquiry_screen_manage', $wcfm_screen_manager_data );
      break;
      
      case 'wcfm-enquiry-manage':
      	wp_enqueue_script( 'wcfm_enquiry_manage_js', $WCFM->library->js_lib_url . 'enquiry/wcfm-script-enquiry-manage.js', array('jquery'), $WCFM->version, true );
      	// Localized Script
        $wcfm_messages = get_wcfm_enquiry_manage_messages();
			  wp_localize_script( 'wcfm_enquiry_manage_js', 'wcfm_enquiry_manage_messages', $wcfm_messages );
      break;
	  }
	}
	
	/**
   * Enquiry Styles
   */
	public function load_styles( $end_point ) {
	  global $WCFM, $WCFMu;
		
	  switch( $end_point ) {
	  	case 'wcfm-enquiry':
		    wp_enqueue_style( 'wcfm_enquiry_css',  $WCFM->library->css_lib_url . 'enquiry/wcfm-style-enquiry.css', array(), $WCFM->version );
		  break;
		  
		  case 'wcfm-enquiry-manage':
		  	wp_enqueue_style( 'collapsible_css',  $WCFM->library->css_lib_url . 'wcfm-style-collapsible.css', array(), $WCFM->version );
		  	wp_enqueue_style( 'wcfm_enquiry_manage_css',  $WCFM->library->css_lib_url . 'enquiry/wcfm-style-enquiry-manage.css', array(), $WCFM->version );
		  break;
	  }
	}
	
	/**
   * Enquiry Views
   */
  public function load_views( $end_point ) {
	  global $WCFM, $WCFMu;
	  
	  switch( $end_point ) {
	  	case 'wcfm-enquiry':
        $WCFM->template->get_template( 'enquiry/wcfm-view-enquiry.php' );
      break;
      
      case 'wcfm-enquiry-manage':
        $WCFM->template->get_template( 'enquiry/wcfm-view-enquiry-manage.php' );
      break;
	  }
	}
	
	/**
   * Enquiry Ajax Controllers
   */
  public function ajax_controller() {
  	global $WCFM, $WCFMu;
  	
  	$controllers_path = $WCFM->plugin_path . 'controllers/enquiry/';
  	
  	$controller = '';
  	if( isset( $_POST['controller'] ) ) {
  		$controller = $_POST['controller'];
  		
  		switch( $controller ) {
  			case 'wcfm-enquiry':
					include_once( $controllers_path . 'wcfm-controller-enquiry.php' );
					new WCFM_Enquiry_Controller();
				break;
				
				case 'wcfm-enquiry-manage':
					include_once( $controllers_path . 'wcfm-controller-enquiry-manage.php' );
					new WCFM_Enquiry_Manage_Controller();
				break;
				
				case 'wcfm-enquiry-tab':
					include_once( $controllers_path . 'wcfm-controller-enquiry-tab.php' );
					new WCFM_Enquiry_Tab_Controller();
				break;
				
				case 'wcfm-my-account-enquiry-manage':
					include_once( $controllers_path . 'wcfm-controller-enquiry-manage.php' );
					new WCFM_My_Account_Enquiry_Manage_Controller();
				break;
  		}
  	}
  }
  
  
  function wcfm_enquiry_my_account_endpoints() {
		add_rewrite_endpoint( $this->wcfm_myaccount_inquiry_endpoint, EP_ROOT | EP_PAGES );
		add_rewrite_endpoint( $this->wcfm_myaccount_view_inquiry_endpoint, EP_ROOT | EP_PAGES );
	}
	
	function wcfm_enquiry_my_account_query_vars( $vars ) {
		$vars[] = $this->wcfm_myaccount_inquiry_endpoint;
		$vars[] = $this->wcfm_myaccount_view_inquiry_endpoint;
	
		return $vars;
	}
	
	function wcfm_enquiry_my_account_flush_rewrite_rules() {
		add_rewrite_endpoint( $this->wcfm_myaccount_inquiry_endpoint, EP_ROOT | EP_PAGES );
		add_rewrite_endpoint( $this->wcfm_myaccount_view_inquiry_endpoint, EP_ROOT | EP_PAGES );
		flush_rewrite_rules();
	}
	
	function wcfm_enquiry_my_account_menu_items( $items ) {
		// Insert your custom endpoint.
		$items = array_slice($items, 0, count($items) - 2, true) +
																	array(
																				$this->wcfm_myaccount_inquiry_endpoint => __( 'Inquiries', 'wc-frontend-manager' )
																				) +
																	array_slice($items, count($items) - 2, count($items) - 1, true) ;
		return $items;
	}
	
	function wcfm_enquiry_my_account_endpoint_title( $title ) {
		global $wp_query;
	
		if( !defined( 'WCFM_ENQUERY_LOOP') ) {
			$is_endpoint = isset( $wp_query->query_vars[$this->wcfm_myaccount_inquiry_endpoint] );
		
			if ( $is_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
				// New page title.
				$title = __( 'Inquiries', 'wc-frontend-manager' );
				remove_filter( 'the_title', array( $this, 'wcfm_enquiry_my_account_endpoint_title' ) );
			}
			
			$is_endpoint = isset( $wp_query->query_vars[$this->wcfm_myaccount_view_inquiry_endpoint] );
		
			if ( $is_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
				// New page title.
				$title = __( 'Inquiry', 'wc-frontend-manager' ) . ' #' . sprintf( '%06u', $wp_query->query_vars[$this->wcfm_myaccount_view_inquiry_endpoint] );
				remove_filter( 'the_title', array( $this, 'wcfm_enquiry_my_account_endpoint_title' ) );
			}
		}
	
		return $title;
	}
	
	function wcfm_enquiry_my_account_endpoint_content() {
		global $WCFM, $wpdb;
		$WCFM->template->get_template( 'enquiry/wcfm-view-my-account-enquiry.php' );
	}
	
	function wcfm_enquiry_view_my_account_endpoint_content() {
		global $_POST, $wp_query, $wp, $WCFM;
		$WCFM->template->get_template( 'enquiry/wcfm-view-my-account-enquiry-manage.php' );
	}
  
  /**
   * Delete Enquiry 
   */
  function delete_wcfm_enquiry() {
  	global $WCFM, $wpdb, $_POST;
  	
  	if( isset( $_POST['enquiryid'] ) && !empty( $_POST['enquiryid'] ) ) {
  		$enquiryid = $_POST['enquiryid'];
  		$wpdb->query( "DELETE FROM {$wpdb->prefix}wcfm_enquiries WHERE ID = {$enquiryid}" );
  	}
  	
  	echo "success";
  	die;
  }
	
  /**
   * Enquiry Tab on Single Product
   */
	function wcfm_enquiry_product_tab( $tabs ) {
		global $WCFM, $wp;
		
		$tabs['wcfm_enquiry_tab'] = array(
			'title' 	=> __( 'Enquiries', 'wc-frontend-manager' ),
			'priority' 	=> 100,
			'callback' 	=> array( &$this, 'wcfm_enquiry_product_tab_content' )
		);
	
		return $tabs;
	}
	
	/**
   * Enquiry Button on Single Product Page
   *
   * @since 3.3.5
   */
	function wcfm_enquiry_button() {
		global $WCFM;
		if( apply_filters( 'wcfm_is_pref_enquiry_button', true ) ) {
			
			$button_style = '';
			$wcfm_options = $WCFM->wcfm_options;
			if( isset( $wcfm_options['wc_frontend_manager_button_background_color_settings'] ) ) { $button_style .= 'background: ' . $wcfm_options['wc_frontend_manager_button_background_color_settings'] . ';border-bottom-color: ' . $wcfm_options['wc_frontend_manager_button_background_color_settings'] . ';'; }
			if( isset( $wcfm_options['wc_frontend_manager_button_text_color_settings'] ) ) { $button_style .= 'color: ' . $wcfm_options['wc_frontend_manager_button_text_color_settings'] . ';'; }
			
			$wcfm_enquiry_button_label  = isset( $wcfm_options['wcfm_enquiry_button_label'] ) ? $wcfm_options['wcfm_enquiry_button_label'] : __( 'Ask a Question', 'wc-frontend-manager' );
			
			$base_color = '';
			if( isset( $wcfm_options['wc_frontend_manager_base_highlight_color_settings'] ) ) { $base_color = $wcfm_options['wc_frontend_manager_base_highlight_color_settings']; }
			?>
			<div class="wcfm_ele_wrapper wcfm_catalog_enquiry_button_wrapper">
				<div class="wcfm-clearfix"></div>
				<a href="#" class="wcfm_catalog_enquiry" style="<?php echo $button_style; ?>"><span class="fa fa-question-circle-o"></span>&nbsp;&nbsp;<span class="add_enquiry_label"><?php _e( $wcfm_enquiry_button_label, 'wc-frontend-manager' ); ?></span></a>
				<?php if( $base_color ) { ?>
					<style>a.wcfm_catalog_enquiry:hover{background: <?php echo $base_color; ?> !important;border-bottom-color: <?php echo $base_color; ?> !important;}</style>
				<?php } ?>
				<div class="wcfm-clearfix"></div>
			</div>
			<?php
			if( !apply_filters( 'wcfm_is_pref_enquiry_tab', true ) ) {
				$this->wcfm_enquiry_form_content();
			}
		}
	}
	
	/**
   * Enquiry Button on WCFM Marketplace Store Page
   *
   * @since 5.0.0
   */
	function wcfmmp_store_enquiry_button() {
		global $WCFM, $WCFMmp;
		if( apply_filters( 'wcfm_is_pref_enquiry_button', true ) ) {
			?>
			<div class="lft bd_icon_box"><a class="wcfm_catalog_enquiry" href="#"><div class="bd_icon"><i class="fa fa-question" aria-hidden="true"></i></div><span><?php _e( 'Inquiry', 'wc-frontend-manager' ); ?></span></a></div>
			<?php
			add_filter( 'wcfm_is_pref_enquiry_tab', '__return_false' );
			$this->wcfm_enquiry_form_content();
		}
	}
	
	/**
   * Enquiry List on WCFM Dashboard
   *
   * @since 3.3.5
   */
	function wcfm_dashboard_enquiry_list() {
		global $WCFM, $wpdb;
		
		if( apply_filters( 'wcfm_is_pref_enquiry', true ) && apply_filters( 'wcfm_is_allow_enquiry', true ) ) {
			$vendor_id = apply_filters( 'wcfm_message_author', get_current_user_id() );
			
			$enquiry_query = "SELECT * FROM {$wpdb->prefix}wcfm_enquiries AS wcfm_enquiries";
			$enquiry_query .= " WHERE 1 = 1";
			$enquiry_query .= " AND `reply` = ''";
			if( wcfm_is_vendor() ) { 
				$enquiry_query .= " AND `vendor_id` = {$vendor_id}";
			}
			$enquiry_query = apply_filters( 'wcfm_enquiry_list_query', $enquiry_query );
			$enquiry_query .= " ORDER BY wcfm_enquiries.`ID` DESC";
			$enquiry_query .= " LIMIT 8";
			$enquiry_query .= " OFFSET 0";
			
			$wcfm_enquirys_array = $wpdb->get_results( $enquiry_query );
			
			?>
			<div class="wcfm_dashboard_enquiries">
				<div class="page_collapsible" id="wcfm_dashboard_enquiries"><span class="fa fa-question-circle fa-question-circle-o"></span><span class="dashboard_widget_head"><?php _e('Enquiries', 'wc-frontend-manager'); ?></span></div>
				<div class="wcfm-container">
					<div id="wcfm_dashboard_enquiries_expander" class="wcfm-content">
					  <?php
					  if( !empty( $wcfm_enquirys_array ) ) {
					  	$counter = 0;
							foreach($wcfm_enquirys_array as $wcfm_enquirys_single) {
								if( $counter == 5 ) break;
								echo '<div class="wcfm_dashboard_enquiry"><a href="' . get_wcfm_enquiry_manage_url($wcfm_enquirys_single->ID) . '" class="wcfm_dashboard_item_title"><span class="fa fa-question-circle-o"></span>' . substr( $wcfm_enquirys_single->enquiry, 0, 60 ) . ' ...</a></div>';
								$counter++;
							}
							if( count( $wcfm_enquirys_array ) > 5 ) {
								echo '<div class="wcfm_dashboard_enquiry_show_all"><a class="wcfm_submit_button" href="' . get_wcfm_enquiry_url() . '">' . __( 'Show All', 'wc-frontend-manager' ) . ' >></a></div>';
							}
						} else {
							_e( 'There is no enquiry yet!!', 'wc-frontend-manager' );
						}
						?>
					</div>
				</div>
			</div>
			<?php
		}
	}
	
	/**
   * Enquiry Form content
   */
	function wcfm_enquiry_form_content() {
		global $WCFM, $wp;
		$product_id = 0;
		$vendor_id  = 0;
		$WCFM->template->get_template( 'enquiry/wcfm-view-enquiry-form.php', array( 'product_id' => $product_id, 'vendor_id' => $vendor_id ) );
	}
	
	/**
   * Enquiry Tab content on Single Product
   */
	function wcfm_enquiry_product_tab_content() {
		global $WCFM, $wp;
		$WCFM->template->get_template( 'enquiry/wcfm-view-enquiry-tab.php' );
	}
	
	function wcfm_enquiry_message_types( $message_types ) {
		$message_types['enquiry'] = __( 'New Enquiry', 'wc-frontend-manager' );
		return $message_types;
	}
	
	/**
	 * WCFM Enquiry JS
	 */
	function wcfm_enquiry_scripts() {
 		global $WCFM, $wp, $WCFM_Query;
 		
 		if( is_product() || ( function_exists( 'wcfmmp_is_store_page' ) && wcfmmp_is_store_page() ) ) {
 			$WCFM->library->load_blockui_lib();
 		}
 			
		wp_enqueue_script( 'wcfm_enquiry_tab_js', $WCFM->library->js_lib_url . 'enquiry/wcfm-script-enquiry-tab.js', array('jquery' ), $WCFM->version, true );
		// Localized Script
		$wcfm_messages = get_wcfm_enquiry_manage_messages();
		wp_localize_script( 'wcfm_enquiry_tab_js', 'wcfm_enquiry_manage_messages', $wcfm_messages );
 		
 		if( is_account_page() ) {
 			if( is_user_logged_in() ) {
				if( isset( $wp->query_vars[$this->wcfm_myaccount_view_inquiry_endpoint] ) && !empty( $wp->query_vars[$this->wcfm_myaccount_view_inquiry_endpoint] ) ) {
					$WCFM->library->load_blockui_lib();
					wp_enqueue_script( 'wcfm_enquiry_manage_js', $WCFM->library->js_lib_url . 'enquiry/wcfm-script-my-account-enquiry-manage.js', array('jquery'), $WCFM->version, true );
					// Localized Script
					$wcfm_messages = get_wcfm_enquiry_manage_messages();
					wp_localize_script( 'wcfm_enquiry_manage_js', 'wcfm_enquiry_manage_messages', $wcfm_messages );
				}
			}
 		}
 	}
 	
 	/**
 	 * WCFM Enquiry CSS
 	 */
 	function wcfm_enquiry_styles() {
 		global $WCFM, $wp, $WCFM_Query;
 		
 		if( is_product() ) {
 			wp_enqueue_style( 'wcfm_enquiry_tab_css',  $WCFM->library->css_lib_url . 'enquiry/wcfm-style-enquiry-tab.css', array(), $WCFM->version );
 			wp_enqueue_style( 'wcfm_enquiry_button_css',  $WCFM->library->css_lib_url . 'enquiry/wcfm-style-enquiry-button.css', array(), $WCFM->version );
 		}
 		
 		if( function_exists( 'wcfmmp_is_store_page' ) && !wcfmmp_is_store_page() ) {
 			wp_enqueue_style( 'wcfm_enquiry_button_css',  $WCFM->library->css_lib_url . 'enquiry/wcfm-style-enquiry-button.css', array(), $WCFM->version );
 		}
 		
 		if( is_account_page() ) {
 			if( is_user_logged_in() ) {
 				if( isset( $wp->query_vars[$this->wcfm_myaccount_view_inquiry_endpoint] ) && !empty( $wp->query_vars[$this->wcfm_myaccount_view_inquiry_endpoint] ) ) {
 					wp_enqueue_style( 'collapsible_css',  $WCFM->library->css_lib_url . 'wcfm-style-collapsible.css', array(), $WCFM->version );
 					wp_enqueue_style( 'wcfm_menu_css',  $WCFM->library->css_lib_url . 'menu/wcfm-style-menu.css', array(), $WCFM->version );
 					wp_enqueue_style( 'wcfm_enquiry_manage_css',  $WCFM->library->css_lib_url . 'enquiry/wcfm-style-enquiry-manage.css', array(), $WCFM->version );
 					wp_enqueue_style( 'wcfm_my_account_enquiry_manage_css',  $WCFM->library->css_lib_url . 'enquiry/wcfm-style-my-account-enquiry-manage.css', array(), $WCFM->version );
 				}
 			}
 		}
 	}
}