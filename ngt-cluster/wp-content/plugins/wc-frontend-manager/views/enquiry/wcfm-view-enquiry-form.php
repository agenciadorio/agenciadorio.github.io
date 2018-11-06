<?php
/**
 * WCFM plugin view
 *
 * wcfm Enquiry Form View
 * This template can be overridden by copying it to yourtheme/wcfm/enquiry/
 *
 * @author 		WC Lovers
 * @package 	wcfm/views/enquiry
 * @version   5.0.0
 */
 
global $wp, $WCFM, $WCFMu, $post, $wpdb;

if( !$product_id ) {
	$product_id = 0;
	if( is_product() && $post && is_object( $post ) ) {
		$product_id = $post->ID;
	}
}

if( wcfm_is_marketplace() ) {
	if( !$vendor_id ) {
		if( ( function_exists( 'wcfmmp_is_store_page' ) && wcfmmp_is_store_page() ) ) {
			$vendor_id = get_query_var( 'author' );
			$product_id = 0;
		} elseif( $product_id ) {
			$vendor_id = $WCFM->wcfm_vendor_support->wcfm_get_vendor_id_from_product( $product_id );
		}
	}
}

$wcfm_options = $WCFM->wcfm_options;
$wcfm_enquiry_custom_fields = isset( $wcfm_options['wcfm_enquiry_custom_fields'] ) ? $wcfm_options['wcfm_enquiry_custom_fields'] : array();

?>

<div class="enquiry_form_wrapper_hide">
	<div id="enquiry_form_wrapper" class="wcfm_popup_wrapper">
		<div id="enquiry_form">
			<div style="margin-bottom: 15px;"><h2 style="float: none;"><?php _e( 'Inquiry', 'wc-frontend-manager' ); ?></h2></div>
			
			<form action="" method="post" id="wcfm_enquiry_form" class="enquiry-form" novalidate="">
				<?php if( !is_user_logged_in() ) { ?>
					<p class="comment-notes"><span id="email-notes"><?php _e( 'Your email address will not be published.', 'wc-frontend-manager' ); ?></span></p>
				<?php } ?>
				
				<p class="wcfm_popup_label">
					<strong for="comment"><?php _e( 'Your enquiry', 'wc-frontend-manager' ); ?> <span class="required">*</span></strong>
				</p>
				<textarea id="enquiry_comment" name="enquiry" class="wcfm_popup_input wcfm_popup_textarea"></textarea>
				
				<?php if( !is_user_logged_in() ) { ?>
					<p class="wcfm_popup_label">
						<strong for="author"><?php _e( 'Name', 'wc-frontend-manager' ); ?> <span class="required">*</span></strong> 
					</p>
					<input id="enquiry_author" name="customer_name" type="text" value="" class="wcfm_popup_input">
					
					<p class="wcfm_popup_label">
						<strong for="email"><?php _e( 'Email', 'wc-frontend-manager' ); ?> <span class="required">*</span></strong> 
					</p>
					<input id="enquiry_email" name="customer_email" type="email" value="" class="wcfm_popup_input">
				<?php } ?>
				
				<?php
				// Enquiry Custom Field Support - 4.1.3
				if( !empty( $wcfm_enquiry_custom_fields ) ) {
					foreach( $wcfm_enquiry_custom_fields as $wcfm_enquiry_custom_field ) {
						if( !isset( $wcfm_enquiry_custom_field['enable'] ) ) continue;
						if( !$wcfm_enquiry_custom_field['label'] ) continue;
						$field_value = '';
						$wcfm_enquiry_custom_field['name'] = sanitize_title( $wcfm_enquiry_custom_field['label'] );
						$field_name = 'wcfm_enquiry_meta[' . $wcfm_enquiry_custom_field['name'] . ']';
					
						if( !empty( $wcfmvm_custom_infos ) ) {
							if( $wcfm_enquiry_custom_field['type'] == 'checkbox' ) {
								$field_value = isset( $wcfmvm_custom_infos[$wcfm_enquiry_custom_field['name']] ) ? $wcfmvm_custom_infos[$wcfm_enquiry_custom_field['name']] : 'no';
							} else {
								$field_value = isset( $wcfmvm_custom_infos[$wcfm_enquiry_custom_field['name']] ) ? $wcfmvm_custom_infos[$wcfm_enquiry_custom_field['name']] : '';
							}
						}
						
						// Is Required
						$custom_attributes = array();
						if( isset( $wcfm_enquiry_custom_field['required'] ) && $wcfm_enquiry_custom_field['required'] ) $custom_attributes = array( 'required' => 1 );
							
						switch( $wcfm_enquiry_custom_field['type'] ) {
							case 'text':
							case 'upload':
								$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfm_enquiry_custom_field['name'] => array( 'label' => __( $wcfm_enquiry_custom_field['label'], 'wc-frontend-manager') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'text', 'class' => 'wcfm-text wcfm_popup_input', 'label_class' => 'wcfm_title wcfm_popup_label', 'value' => $field_value, 'hints' => __( $wcfm_enquiry_custom_field['help_text'], 'wc-frontend-manager') ) ) );
							break;
							
							case 'number':
								$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfm_enquiry_custom_field['name'] => array( 'label' => __( $wcfm_enquiry_custom_field['label'], 'wc-frontend-manager') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'number', 'class' => 'wcfm-text wcfm_popup_input', 'label_class' => 'wcfm_title wcfm_popup_label', 'value' => $field_value, 'hints' => __( $wcfm_enquiry_custom_field['help_text'], 'wc-frontend-manager') ) ) );
							break;
							
							case 'textarea':
								$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfm_enquiry_custom_field['name'] => array( 'label' => __( $wcfm_enquiry_custom_field['label'], 'wc-frontend-manager') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'textarea', 'class' => 'wcfm-textarea wcfm_popup_input', 'label_class' => 'wcfm_title wcfm_popup_label', 'value' => $field_value, 'hints' => __( $wcfm_enquiry_custom_field['help_text'], 'wc-frontend-manager') ) ) );
							break;
							
							case 'datepicker':
								$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfm_enquiry_custom_field['name'] => array( 'label' => __( $wcfm_enquiry_custom_field['label'], 'wc-frontend-manager') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'text', 'placeholder' => 'YYYY-MM-DD', 'class' => 'wcfm-text wcfm_popup_input', 'label_class' => 'wcfm_title wcfm_popup_label', 'value' => $field_value, 'hints' => __( $wcfm_enquiry_custom_field['help_text'], 'wc-frontend-manager') ) ) );
							break;
							
							case 'timepicker':
								$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfm_enquiry_custom_field['name'] => array( 'label' => __( $wcfm_enquiry_custom_field['label'], 'wc-frontend-manager') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'time', 'class' => 'wcfm-text wcfm_popup_input', 'label_class' => 'wcfm_title wcfm_popup_label', 'value' => $field_value, 'hints' => __( $wcfm_enquiry_custom_field['help_text'], 'wc-frontend-manager') ) ) );
							break;
							
							case 'checkbox':
								$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfm_enquiry_custom_field['name'] => array( 'label' => __( $wcfm_enquiry_custom_field['label'], 'wc-frontend-manager') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'checkbox', 'class' => 'wcfm-checkbox', 'label_class' => 'wcfm_title checkbox-title wcfm_popup_label', 'value' => 'yes', 'dfvalue' => $field_value, 'hints' => __( $wcfm_enquiry_custom_field['help_text'], 'wc-frontend-manager') ) ) );
							break;
							
							case 'upload':
								//$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfm_enquiry_custom_field['name'] => array( 'label' => __( $wcfm_enquiry_custom_field['label'], 'wc-frontend-manager') , 'name' => $field_name, 'type' => 'upload', 'class' => 'wcfm_ele', 'label_class' => 'wcfm_title', 'value' => $field_value, 'hints' => __( $wcfm_enquiry_custom_field['help_text'], 'wc-frontend-manager') ) ) );
							break;
							
							case 'select':
								$select_opt_vals = array();
								$select_options = explode( '|', $wcfm_enquiry_custom_field['options'] );
								if( !empty ( $select_options ) ) {
									foreach( $select_options as $select_option ) {
										if( $select_option ) {
											$select_opt_vals[$select_option] = __( ucfirst( str_replace( "-", " " , $select_option ) ), 'wc-frontend-manager');
										}
									}
								}
								$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfm_enquiry_custom_field['name'] => array( 'label' => __( $wcfm_enquiry_custom_field['label'], 'wc-frontend-manager') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'select', 'class' => 'wcfm-select wcfm_popup_input', 'label_class' => 'wcfm_title wcfm_popup_label', 'options' => $select_opt_vals, 'value' => $field_value, 'hints' => __( $wcfm_enquiry_custom_field['help_text'], 'wc-frontend-manager') ) ) );
							break;
						}
					}
				}
				?>
				
				<?php if ( function_exists( 'gglcptch_init' ) ) { ?>
					<div class="wcfm_clearfix"></div>
					<div class="wcfm_gglcptch_wrapper" style="float:right;">
						<?php echo apply_filters( 'gglcptch_display_recaptcha', '', 'wcfm_enquiry_form' ); ?>
					</div>
				<?php } elseif ( class_exists( 'anr_captcha_class' ) && function_exists( 'anr_captcha_form_field' ) ) { ?>
					<div class="wcfm_clearfix"></div>
					<div class="wcfm_gglcptch_wrapper" style="float:right;">
						<?php do_action( 'anr_captcha_form_field' ); ?>
					</div>
				<?php } ?>
				<div class="wcfm_clearfix"></div>
				<div class="wcfm-message" tabindex="-1"></div>
				<div class="wcfm_clearfix"></div><br />
				
				<p class="form-submit">
					<input name="submit" type="submit" id="wcfm_enquiry_submit_button" class="submit wcfm_popup_button" value="<?php _e( 'Submit', 'wc-frontend-manager' ); ?>"> 
					<input type="hidden" name="product_id" value="<?php echo $product_id; ?>" id="enquiry_product_id">
					<input type="hidden" name="vendor_id" value="<?php echo $vendor_id; ?>" id="enquiry_vendor_id">
				</p>	
			</form>
			<div class="wcfm_clearfix"></div>
		</div>
	</div>
</div>
<div class="wcfm-clearfix"></div>