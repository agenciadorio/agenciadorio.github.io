<?php
/**
 * WCFM plugin shortcode
 *
 * Plugin Shortcode output
 *
 * @author 		WC Lovers
 * @package 	wcfm/includes/shortcode
 * @version   1.0.0
 */
 
class WCFM_Enquiry_Shortcode {

	public function __construct() {

	}

	/**
	 * Output the Enquiry shortcode.
	 *
	 * @access public
	 * @param array $atts
	 * @return void
	 */
	static public function output( $attr ) {
		global $WCFM, $wp, $WCFM_Query;
		$WCFM->nocache();
		
		if( !apply_filters( 'wcfm_is_pref_enquiry', true ) ) return;
		
		$wcfm_options = $WCFM->wcfm_options;
		
		$ask_question_label  = isset( $wcfm_options['wcfm_enquiry_button_label'] ) ? $wcfm_options['wcfm_enquiry_button_label'] : __( 'Ask a Question', 'wc-frontend-manager' );
		if ( isset( $attr['label'] ) && !empty( $attr['label'] ) ) { $ask_question_label = $attr['label']; } 
		
		$product_id = 0;
		if ( isset( $attr['product'] ) && !empty( $attr['product'] ) ) { $product_id = absint($attr['product']); }
		
		$vendor_id  = 0;
		if ( isset( $attr['store'] ) && !empty( $attr['store'] ) ) { $vendor_id = absint($attr['store']); }
		
		
		$button_style = '';
		$background_color = '';
		$color = '';
		$base_color = '';
		$alignment = '';
		
		if ( isset( $attr['background'] ) && !empty( $attr['background'] ) ) { $background_color = $attr['background']; }
		if( $background_color ) { $button_style .= 'background: ' . $background_color . ';border-bottom-color: ' . $background_color . ';'; }
		elseif( isset( $wcfm_options['wc_frontend_manager_button_background_color_settings'] ) ) { $button_style .= 'background: ' . $wcfm_options['wc_frontend_manager_button_background_color_settings'] . ';border-bottom-color: ' . $wcfm_options['wc_frontend_manager_button_background_color_settings'] . ';'; }
		if ( isset( $attr['color'] ) && !empty( $attr['color'] ) ) { $color = $attr['color']; }
		if( $color ) { $button_style .= 'color: ' . $color . ';'; }
		elseif( isset( $wcfm_options['wc_frontend_manager_button_text_color_settings'] ) ) { $button_style .= 'color: ' . $wcfm_options['wc_frontend_manager_button_text_color_settings'] . ';'; }
		
		if ( isset( $attr['hover'] ) && !empty( $attr['hover'] ) ) { $base_color = $attr['hover']; }
		elseif( isset( $wcfm_options['wc_frontend_manager_base_highlight_color_settings'] ) ) { $base_color = $wcfm_options['wc_frontend_manager_base_highlight_color_settings']; }
		
		if ( isset( $attr['align'] ) && !empty( $attr['align'] ) ) { $button_style .= 'float: ' . $attr['align'] . ';'; }
		
		?>
		<div class="wcfm_ele_wrapper wcfm_enquiry_widget">
			<div class="wcfm-clearfix"></div>
			<a href="#" class="wcfm_catalog_enquiry" style="<?php echo $button_style; ?>"><span class="fa fa-question-circle-o"></span>&nbsp;&nbsp;<span class="add_enquiry_label"><?php echo $ask_question_label; ?></span></a>
			<?php if( $base_color ) { ?>
				<style>a.wcfm_catalog_enquiry:hover{background: <?php echo $base_color; ?> !important;border-bottom-color: <?php echo $base_color; ?> !important;}</style>
			<?php } ?>
			<div class="wcfm-clearfix"></div><br />
			<?php
			if( !is_product() || ( function_exists( 'wcfmmp_is_store_page' ) && !wcfmmp_is_store_page() ) ) {
				$WCFM->template->get_template( 'enquiry/wcfm-view-enquiry-form.php', array( 'product_id' => $product_id, 'vendor_id' => $vendor_id ) );
			}
			?>
			<div class="wcfm-clearfix"></div>
		</div>
		<?php
	}
}