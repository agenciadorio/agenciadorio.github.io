<?php
/**
 * WCFM plugin view
 *
 * wcfm Enquiry Tab View
 *
 * @author 		WC Lovers
 * @package 	wcfm/views/enquiry
 * @version   3.2.8
 */
 
global $wp, $WCFM, $WCFMu, $post, $wpdb;

$product_id = $post->ID;

if( !$product_id ) return;

$wcfm_options = get_option( 'wcfm_options', array() );
$wcfm_enquiry_custom_fields = isset( $wcfm_options['wcfm_enquiry_custom_fields'] ) ? $wcfm_options['wcfm_enquiry_custom_fields'] : array();

?>

<?php
// Fetching existing Enquries
if( apply_filters( 'wcfm_is_pref_enquiry_tab', true ) ) {
	$enquiries = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wcfm_enquiries WHERE `is_private` = 0 AND `reply` != '' AND `product_id` = {$product_id}" );
	?>
	
	<h2 class="wcfm-enquiries-heading"><?php _e( 'General Enquiries', 'wc-frontend-manager' ); ?></h2>
	
	<?php
	if( empty( $enquiries ) ) {
		?>
		<p class="woocommerce-noreviews wcfm-noenquiries"><?php _e( 'There are no enquiries yet.', 'wc-frontend-manager' ); ?></p>
	<?php } ?>	


	<?php if( !apply_filters( 'wcfm_is_pref_enquiry_button', true ) ) { ?>
		<p><span class="add_enquiry"><span class="fa fa-question-circle fa-question-circle-o"></span><span class="add_enquiry_label"><?php _e( 'Ask a Question', 'wc-frontend-manager' ); ?></span></span></p>
	<?php } ?>
<?php } ?>
	
<div class="enquiry_form_wrapper_hide">
	<div id="enquiry_form_wrapper">
		<div id="enquiry_form">
			<div id="respond" class="comment-respond">
				<form action="" method="post" id="wcfm_enquiry_form" class="enquiry-form" novalidate="">
				  <?php if( !is_user_logged_in() ) { ?>
					  <p class="comment-notes"><span id="email-notes"><?php _e( 'Your email address will not be published.', 'wc-frontend-manager' ); ?></span></p>
					<?php } ?>
					
					<p class="comment-form-comment">
						<label for="comment"><?php _e( 'Your enquiry', 'wc-frontend-manager' ); ?> <span class="required">*</span></label>
						<textarea id="enquiry_comment" name="enquiry" style="width: 95%" aria-required="true" required=""></textarea>
					</p>
					
					<?php if( !is_user_logged_in() ) { ?>
						<p class="comment-form-author">
							<label for="author"><?php _e( 'Name', 'wc-frontend-manager' ); ?> <span class="required">*</span></label> 
							<input id="enquiry_author" name="customer_name" type="text" value="" style="width: 95%" aria-required="true"-required="">
						</p>
						
						<p class="comment-form-email">
							<label for="email"><?php _e( 'Email', 'wc-frontend-manager' ); ?> <span class="required">*</span></label> 
							<input id="enquiry_email" name="customer_email" type="email" value="" style="width: 95%" aria-required="true"-required="">
						</p>
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
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfm_enquiry_custom_field['name'] => array( 'label' => __( $wcfm_enquiry_custom_field['label'], 'wc-frontend-manager') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'text', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'attributes' => array( 'style' => 'width: 95%;'), 'value' => $field_value, 'hints' => __( $wcfm_enquiry_custom_field['help_text'], 'wc-frontend-manager') ) ) );
								break;
								
								case 'number':
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfm_enquiry_custom_field['name'] => array( 'label' => __( $wcfm_enquiry_custom_field['label'], 'wc-frontend-manager') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'number', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'attributes' => array( 'style' => 'width: 95%;'), 'value' => $field_value, 'hints' => __( $wcfm_enquiry_custom_field['help_text'], 'wc-frontend-manager') ) ) );
								break;
								
								case 'textarea':
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfm_enquiry_custom_field['name'] => array( 'label' => __( $wcfm_enquiry_custom_field['label'], 'wc-frontend-manager') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'textarea', 'class' => 'wcfm-textarea', 'label_class' => 'wcfm_title', 'attributes' => array( 'style' => 'width: 95%;'), 'value' => $field_value, 'hints' => __( $wcfm_enquiry_custom_field['help_text'], 'wc-frontend-manager') ) ) );
								break;
								
								case 'datepicker':
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfm_enquiry_custom_field['name'] => array( 'label' => __( $wcfm_enquiry_custom_field['label'], 'wc-frontend-manager') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'text', 'placeholder' => 'YYYY-MM-DD', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'value' => $field_value, 'hints' => __( $wcfm_enquiry_custom_field['help_text'], 'wc-frontend-manager') ) ) );
								break;
								
								case 'timepicker':
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfm_enquiry_custom_field['name'] => array( 'label' => __( $wcfm_enquiry_custom_field['label'], 'wc-frontend-manager') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'time', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'value' => $field_value, 'hints' => __( $wcfm_enquiry_custom_field['help_text'], 'wc-frontend-manager') ) ) );
								break;
								
								case 'checkbox':
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfm_enquiry_custom_field['name'] => array( 'label' => __( $wcfm_enquiry_custom_field['label'], 'wc-frontend-manager') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'checkbox', 'class' => 'wcfm-checkbox', 'label_class' => 'wcfm_title checkbox-title', 'value' => 'yes', 'dfvalue' => $field_value, 'hints' => __( $wcfm_enquiry_custom_field['help_text'], 'wc-frontend-manager') ) ) );
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
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $wcfm_enquiry_custom_field['name'] => array( 'label' => __( $wcfm_enquiry_custom_field['label'], 'wc-frontend-manager') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'select', 'class' => 'wcfm-select', 'label_class' => 'wcfm_title', 'attributes' => array( 'style' => 'width: 95%;'), 'options' => $select_opt_vals, 'value' => $field_value, 'hints' => __( $wcfm_enquiry_custom_field['help_text'], 'wc-frontend-manager') ) ) );
								break;
							}
						}
					}
					?>
					
					<?php if ( function_exists( 'gglcptch_init' ) ) { ?>
						<div class="wcfm_clearfix"></div>
						<div class="wcfm_gglcptch_wrapper" style="width: 100%;">
							<?php echo apply_filters( 'gglcptch_display_recaptcha', '', 'wcfm_enquiry_form' ); ?>
						</div>
					<?php } ?>
					<div class="wcfm_clearfix"></div>
					<div class="wcfm-message" tabindex="-1"></div>
					<div class="wcfm_clearfix"></div><br />
					
					<p class="form-submit">
						<input name="submit" type="submit" id="wcfm_enquiry_submit_button" class="submit" value="<?php _e( 'Submit', 'wc-frontend-manager' ); ?>"> 
						<input type="hidden" name="product_id" value="<?php echo $product_id; ?>" id="enquiry_product_id">
					</p>	
				</form>
			</div><!-- #respond -->
		</div>
	</div>
</div>
<div class="wcfm-clearfix"></div>

<?php 
if( apply_filters( 'wcfm_is_pref_enquiry_tab', true ) ) {
	if( !empty( $enquiries ) ) {
		?><p class="woocommerce-noreviews wcfm-enquiries-count"><?php echo count( $enquiries ) . ' ' . __( 'Enquiries', 'wc-frontend-manager' ); ?></p><?php
		echo '<div id="reviews" class="wcfm_enquiry_reviews enquiry_reviews"><ol class="wcfm_enquiry_list commentlist">';
		foreach( $enquiries as $enquiry_data ) {
			?>
			<li class="wcfm_enquiry_item comment byuser comment-author-vnd bypostauthor even thread-even depth-1" id="li-enquiry-<?php echo $enquiry_data->ID; ?>">
				<div id="enquiry-<?php echo $enquiry_data->ID; ?>" class="wcfm_enquiry_container comment_container">
					<div class="comment-text">
						<div class="enquiry-by"><span style="width:60%"><span class="fa fa-clock-o"></span> <?php echo date_i18n( wc_date_format(), strtotime( $enquiry_data->posted ) ); ?></span></div>
						<p class="meta">
							<strong class="woocommerce-review__author"><?php echo $enquiry_data->enquiry; ?></strong> 
							<?php if( apply_filters( 'wcfm_is_allow_enquery_tab_customer_show', true ) ) { ?>
								<span class="woocommerce-review__dash">&ndash;</span> 
								<time class="woocommerce-review__published-date"><?php _e( 'by', 'wc-frontend-manager' ); ?> <?php echo $enquiry_data->customer_name; ?></time>
							<?php } ?>
						</p>
						<div class="description">
							<?php echo $enquiry_data->reply; ?>
						</div>
					</div>
				</div>
			</li><!-- #comment-## -->
		<?php
		}
		echo '</ol></div><div class="wcfm-clearfix"></div>';
	}
} 
?>