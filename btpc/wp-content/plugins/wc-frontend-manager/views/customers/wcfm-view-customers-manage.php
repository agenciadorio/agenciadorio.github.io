<?php
/**
 * WCFM plugin views
 *
 * Plugin Shop Customers Manage Views
 *
 * @author 		WC Lovers
 * @package 	wcfm/views/customers
 * @version   3.5.0
 */
global $wp, $WCFM, $WCFMu;

if( !apply_filters( 'wcfm_is_allow_manage_customer', true ) || !apply_filters( 'wcfm_is_allow_edit_customer', true ) ) {
	wcfm_restriction_message_show( "Customers Manage" );
	return;
}

$customer_id = 0;
$user_name = '';
$user_email = '';
$first_name = '';
$last_name = '';
$has_custom_capability = 'no';

$bfirst_name = '';
$blast_name  = '';
$baddr_1 = '';
$baddr_2 = '';
$bcountry = '';
$bcity = '';
$bstate = '';
$bzip = '';

$sfirst_name = ''; 
$slast_name = '';
$saddr_1 = '';
$saddr_2 = '';
$scountry = '';
$scity = '';
$sstate = '';
$szip = '';

if( isset( $wp->query_vars['wcfm-customers-manage'] ) && empty( $wp->query_vars['wcfm-customers-manage'] ) ) {
	if( !apply_filters( 'wcfm_is_allow_add_customer', true ) ) {
		wcfm_restriction_message_show( "Add Customer" );
		return;
	}
	if( !apply_filters( 'wcfm_is_allow_customer_limit', true ) ) {
		wcfm_restriction_message_show( "Customer Limit Reached" );
		return;
	}
}

if( isset( $wp->query_vars['wcfm-customers-manage'] ) && !empty( $wp->query_vars['wcfm-customers-manage'] ) ) {
	$customer_user = get_userdata( $wp->query_vars['wcfm-customers-manage'] );
	// Fetching Customer Data
	if($customer_user && !empty($customer_user)) {
		$customer_id = $wp->query_vars['wcfm-customers-manage'];
		$user_name = $customer_user->user_login;
		$user_email = $customer_user->user_email;
		$first_name = $customer_user->first_name;
		$last_name = $customer_user->last_name;
		
		$bfirst_name = get_user_meta( $customer_id, 'billing_first_name', true );
		$blast_name  = get_user_meta( $customer_id, 'billing_last_name', true );
		$baddr_1  = get_user_meta( $customer_id, 'billing_address_1', true );
		$baddr_2  = get_user_meta( $customer_id, 'billing_address_2', true );
		$bcountry  = get_user_meta( $customer_id, 'billing_country', true );
		$bcity  = get_user_meta( $customer_id, 'billing_city', true );
		$bstate  = get_user_meta( $customer_id, 'billing_state', true );
		$bzip  = get_user_meta( $customer_id, 'billing_postcode', true );
		
		$sfirst_name = get_user_meta( $customer_id, 'shipping_first_name', true );
		$slast_name  = get_user_meta( $customer_id, 'shipping_last_name', true );
		$saddr_1  = get_user_meta( $customer_id, 'shipping_address_1', true );
		$saddr_2  = get_user_meta( $customer_id, 'shipping_address_2', true );
		$scountry  = get_user_meta( $customer_id, 'shipping_country', true );
		$scity  = get_user_meta( $customer_id, 'shipping_city', true );
		$sstate  = get_user_meta( $customer_id, 'shipping_state', true );
		$szip  = get_user_meta( $customer_id, 'shipping_postcode', true );
		
	}
}

do_action( 'before_wcfm_customers_manage' );

?>

<div class="collapse wcfm-collapse">
  <div class="wcfm-page-headig">
		<span class="fa fa-user-circle-o"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'Manage Customer', 'wc-frontend-manager' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>
	<div class="wcfm-collapse-content">
	  <div id="wcfm_page_load"></div>
	  
	  <div class="wcfm-container wcfm-top-element-container">
	    <h2><?php if( $customer_id ) { _e('Edit Customer', 'wc-frontend-manager' ); } else { _e('Add Customer', 'wc-frontend-manager' ); } ?></h2>
			
			<?php
			if( $allow_wp_admin_view = apply_filters( 'wcfm_allow_wp_admin_view', true ) ) {
				?>
				<a target="_blank" class="wcfm_wp_admin_view text_tip" href="<?php echo admin_url('user-new.php'); ?>" data-tip="<?php _e( 'WP Admin View', 'wc-frontend-manager' ); ?>"><span class="fa fa-wordpress"></span></a>
				<?php
			}
			
			echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_customers_url().'" data-tip="' . __('Manage Customers', 'wc-frontend-manager') . '"><span class="fa fa-user-circle-o"></span></a>';
			
			if( $has_new = apply_filters( 'wcfm_add_new_customer_sub_menu', true ) ) {
				echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_customers_manage_url().'" data-tip="' . __('Add New Customer', 'wc-frontend-manager') . '"><span class="fa fa-user-plus"></span><span class="text">' . __( 'Add New', 'wc-frontend-manager' ) . '</span></a>';
			}
			?>
			<div class="wcfm-clearfix"></div>
		</div>
	  <div class="wcfm-clearfix"></div><br />
	  
		<?php do_action( 'begin_wcfm_customers_manage' ); ?>
			
		<form id="wcfm_customers_manage_form" class="wcfm">
			
		  <?php do_action( 'begin_wcfm_customers_manage_form' ); ?>
			
			<!-- collapsible -->
			<div class="wcfm-container">
				<div id="customers_manage_general_expander" class="wcfm-content">
						<?php
						  if( $customer_id ) {
						  	$WCFM->wcfm_fields->wcfm_generate_form_field(  array( "user_name" => array( 'label' => __('Username', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'attributes' => array( 'readonly' => true ), 'label_class' => 'wcfm_ele wcfm_title', 'value' => $user_name ) ) );
						  } else {
						  	$WCFM->wcfm_fields->wcfm_generate_form_field(  array( "user_name" => array( 'label' => __('Username', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $user_name ) ) );
						  }
							$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_customer_fields_general', array(  
																																						"user_email" => array( 'label' => __('Email', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $user_email),
																																						"first_name" => array( 'label' => __('First Name', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $first_name),
																																						"last_name" => array( 'label' => __('Last Name', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $last_name),
																																						"customer_id" => array('type' => 'hidden', 'value' => $customer_id )
																																				 ) ) );
						?>
				</div>
			</div>
			<div class="wcfm_clearfix"></div><br />
			<!-- end collapsible -->
			
			<div class="wcfm-tabWrap">
			
				<div class="page_collapsible" id="wcfm_customer_address_head">
					<label class="fa fa-address-card-o"></label>
					<?php _e('Address', 'wc-frontend-manager'); ?><span></span>
				</div>
				<div class="wcfm-container">
					<div id="wcfm_customer_address_expander" class="wcfm-content">
						<?php if( apply_filters( 'wcfm_allow_customer_billing_details', true ) ) { ?>
							<div class="wcfm_customer_heading"><h3><?php _e( 'Billing', 'wc-frontend-manager' ); ?></h3></div>
							<?php
								$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_customer_fields_billing', array(
																																																	"bfirst_name" => array('label' => __('First Name', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $bfirst_name ),
																																																	"blast_name" => array('label' => __('Last Name', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $blast_name ),
																																																	"baddr_1" => array('label' => __('Address 1', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $baddr_1 ),
																																																	"baddr_2" => array('label' => __('Address 2', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $baddr_2 ),
																																																	"bcountry" => array('label' => __('Country', 'wc-frontend-manager') , 'type' => 'country', 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'attributes' => array( 'style' => 'width: 60%;' ), 'value' => $bcountry ),
																																																	"bcity" => array('label' => __('City/Town', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $bcity ),
																																																	"bstate" => array('label' => __('State/County', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $bstate ),
																																																	"bzip" => array('label' => __('Postcode/Zip', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $bzip ),
																																																	) ) );
						}
						if( apply_filters( 'wcfm_allow_customer_shipping_details', true ) ) {
						?>
						
						<div class="wcfm_clearfix"></div>
						<div class="wcfm_customer_heading"><h3><?php _e( 'Shipping', 'wc-frontend-manager' ); ?></h3></div>
						<?php
							$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_customer_fields_shipping', array(
																																																"sfirst_name" => array('label' => __('First Name', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $sfirst_name ),
																																																"slast_name" => array('label' => __('Last Name', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $slast_name ),
																																																"saddr_1" => array('label' => __('Address 1', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $saddr_1 ),
																																																"saddr_2" => array('label' => __('Address 2', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $saddr_2 ),
																																																"scountry" => array('label' => __('Country', 'wc-frontend-manager') , 'type' => 'country', 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'attributes' => array( 'style' => 'width: 60%;' ), 'value' => $scountry ),
																																																"scity" => array('label' => __('City/Town', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $scity ),
																																																"sstate" => array('label' => __('State/County', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $sstate ),
																																																"szip" => array('label' => __('Postcode/Zip', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $szip ),
																																																) ) );
							}
						?>
					</div>
				</div>
				<div class="wcfm_clearfix"></div>
				
				<?php do_action( 'end_wcfm_customers_manage_form', $customer_id ); ?>
				
			</div>
			
			<div id="wcfm_customer_submit" class="wcfm_form_simple_submit_wrapper">
			  <div class="wcfm-message" tabindex="-1"></div>
			  
				<input type="submit" name="submit-data" value="<?php _e( 'Submit', 'wc-frontend-manager' ); ?>" id="wcfm_customer_submit_button" class="wcfm_submit_button" />
			</div>
			<?php
			do_action( 'after_wcfm_customers_manage' );
			?>
		</form>
	</div>
</div>