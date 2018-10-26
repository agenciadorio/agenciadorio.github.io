<?php
/**
 * WCFM plugin core
 *
 * Plugin shortcode
 *
 * @author 		WC Lovers
 * @package 	wcfm/core
 * @version   1.0.0
 */
 
class WCFM_Shortcode {

	public $list_product;

	public function __construct() {
		// WC Frontend Manager Shortcode
		add_shortcode('wc_frontend_manager', array(&$this, 'wc_frontend_manager'));
		
		// WC Frontend Manager Endpoint as Shortcode
		add_shortcode('wcfm', array(&$this, 'wcfm_endpoint_shortcode'));
		
		// WC Frontend Manager Header Panel Notifications as Shortcode
		add_shortcode('wcfm_notifications', array(&$this, 'wcfm_notifications_shortcode'));
		
		// WCfM Enquiry Button Short code
		add_shortcode('wcfm_enquiry', array(&$this, 'wcfm_enquiry_shortcode'));
	}

	public function wc_frontend_manager($attr) {
		global $WCFM;
		$this->load_class('wc-frontend-manager');
		return $this->shortcode_wrapper(array('WCFM_Frontend_Manager_Shortcode', 'output'));
	}
	
	/**
	 * WCFM End point as Short Code
	 */
	public function wcfm_endpoint_shortcode( $attr ) {
		global $WCFM, $wp, $WCFM_Query;
		$WCFM->nocache();
		
		echo '<div id="wcfm-main-contentainer"> <div id="wcfm-content">';
		
		$menu = true;
		if ( isset( $attr['menu'] ) && !empty( $attr['menu'] ) && ( 'false' == $attr['menu'] ) ) { $menu = false; } 
		
		if ( !isset( $attr['endpoint'] ) || ( isset( $attr['endpoint'] ) && empty( $attr['endpoint'] ) ) ) {
			
			// Load Scripts
			$WCFM->library->load_scripts( 'wcfm-dashboard' );
			
			// Load Styles
			$WCFM->library->load_styles( 'wcfm-dashboard' );
			
			// Load View
			$WCFM->library->load_views( 'wcfm-dashboard', $menu );
		} else {
			$wcfm_endpoints = $WCFM_Query->get_query_vars();
			
			foreach ( $wcfm_endpoints as $key => $value ) {
				if ( isset( $attr['endpoint'] ) && !empty( $attr['endpoint'] ) && ( $key == $attr['endpoint'] ) ) {
					// Load Scripts
					$WCFM->library->load_scripts( $key );
					
					// Load Styles
					$WCFM->library->load_styles( $key );
					
					// Load View
					$WCFM->library->load_views( $key, $menu );
				}
			}
		}
		
		echo '</div></div>';
	}
	
	/**
	 * WC Frontend Manager Header Panel Notifications as Shortcode
	 */
	public function wcfm_notifications_shortcode( $attr ) {
		global $WCFM, $wp, $WCFM_Query;
		
		if( !$WCFM || !$WCFM->frontend ) return;
		
		if( is_admin() ) return;
		
		if( !wcfm_is_allow_wcfm() ) return;
		
		$message = true;
		if ( isset( $attr['message'] ) && !empty( $attr['message'] ) && ( 'false' == $attr['message'] ) ) { $message = false; }
		
		$enquiry = true;
		if ( isset( $attr['enquiry'] ) && !empty( $attr['enquiry'] ) && ( 'false' == $attr['enquiry'] ) ) { $enquiry = false; }
		
		$notice = true;
		if ( isset( $attr['notice'] ) && !empty( $attr['notice'] ) && ( 'false' == $attr['notice'] ) ) { $notice = false; }
		
		$unread_notice = $WCFM->frontend->wcfm_direct_message_count( 'notice' );
		$unread_message = $WCFM->frontend->wcfm_direct_message_count( 'message' ); 
		$unread_enquiry = $WCFM->frontend->wcfm_direct_message_count( 'enquiry' );
		?>
		<div class="wcfm_sc_notifications">
			<?php if( $message && apply_filters( 'wcfm_is_pref_direct_message', true ) && apply_filters( 'wcfm_is_allow_notifications', true ) && apply_filters( 'wcfm_is_allow_sc_notifications', true ) ) { ?>
				<a href="<?php echo get_wcfm_messages_url( ); ?>" class="fa fa-bell-o text_tip" data-tip="<?php _e( 'Notification Board', 'wc-frontend-manager' ); ?>"><span class="unread_notification_count message_count"><?php echo $unread_message; ?></span></a>
			<?php } ?>
			
			<?php if( $enquiry && apply_filters( 'wcfm_is_pref_enquiry', true ) && apply_filters( 'wcfm_is_allow_enquiry', true ) && apply_filters( 'wcfm_is_allow_sc_enquiry_notifications', true ) ) { ?>
				<a href="<?php echo get_wcfm_enquiry_url(); ?>" class="fa fa-question-circle-o text_tip" data-tip="<?php _e( 'Enquiry Board', 'wc-frontend-manager' ); ?>"><span class="unread_notification_count enquiry_count"><?php echo $unread_enquiry; ?></span></a>
			<?php } ?>
			
			<?php if( $notice && apply_filters( 'wcfm_is_pref_notice', true ) && apply_filters( 'wcfm_is_allow_notice', true ) && apply_filters( 'wcfm_is_allow_sc_notice_notifications', true ) ) { ?>
				<a href="<?php echo get_wcfm_notices_url( ); ?>" class="fa fa-bullhorn text_tip" data-tip="<?php _e( 'Notice Board', 'wc-frontend-manager' ); ?>"><?php if( wcfm_is_vendor() ) { ?><span class="unread_notification_count notice_count"><?php echo $unread_notice; ?></span><?php } ?></a>
			<?php } ?>
		</div>
		<?php
	}
	
	/**
	 * WCfM Enquiry Ask a Question button short code
	 */
	function wcfm_enquiry_shortcode( $atts ) {
		global $WCFM;
		if( apply_filters( 'wcfm_is_pref_enquiry', true ) ) {
			if( is_product() ) {
				$ask_question_label = __( 'Ask a Question', 'wc-frontend-manager' );
				if ( isset( $attr['label'] ) && !empty( $attr['label'] ) ) { 
					$ask_question_label = $attr['label']; 
				} 
				
				$button_style = '';
				$wcfm_options = get_option('wcfm_options');
				if ( isset( $attr['background'] ) && !empty( $attr['background'] ) ) { $background_color = $attr['background']; }
				if( $background_color ) { $button_style .= 'background: ' . $background_color . ';border-bottom-color: ' . $background_color . ';'; }
				elseif( isset( $wcfm_options['wc_frontend_manager_button_background_color_settings'] ) ) { $button_style .= 'background: ' . $wcfm_options['wc_frontend_manager_button_background_color_settings'] . ';border-bottom-color: ' . $wcfm_options['wc_frontend_manager_button_background_color_settings'] . ';'; }
				if ( isset( $attr['color'] ) && !empty( $attr['color'] ) ) { $color = $attr['color']; }
				if( $color ) { $button_style .= 'color: ' . $color . ';'; }
				elseif( isset( $wcfm_options['wc_frontend_manager_button_text_color_settings'] ) ) { $button_style .= 'color: ' . $wcfm_options['wc_frontend_manager_button_text_color_settings'] . ';'; }
				
				$base_color = '';
				if( isset( $wcfm_options['wc_frontend_manager_base_highlight_color_settings'] ) ) { $base_color = $wcfm_options['wc_frontend_manager_base_highlight_color_settings']; }
				
				?>
				<div class="wcfm_ele_wrapper wcfm_enquiry_widget">
				  <div class="wcfm-clearfix"></div>
					<a href="#" class="wcfm_catalog_enquiry" style="<?php echo $button_style; ?>"><span class="fa fa-question-circle-o"></span>&nbsp;&nbsp;<span class="add_enquiry_label"><?php echo $ask_question_label; ?></span></a>
					<?php if( $base_color ) { ?>
						<style>a.wcfm_catalog_enquiry:hover{background: <?php echo $base_color; ?> !important;border-bottom-color: <?php echo $base_color; ?> !important;}</style>
				  <?php } ?>
					<div class="wcfm-clearfix"></div><br />
					<?php
					if( !apply_filters( 'wcfm_is_pref_enquiry_tab', true ) && !apply_filters( 'wcfm_is_pref_enquiry_button', true ) ) {
						require_once( $WCFM->library->views_path . 'enquiry/wcfm-view-enquiry-tab.php' );
					}
					?>
					<div class="wcfm-clearfix"></div>
				</div>
				<?php
			}
		}
	}

	/**
	 * Helper Functions
	 */

	/**
	 * Shortcode Wrapper
	 *
	 * @access public
	 * @param mixed $function
	 * @param array $atts (default: array())
	 * @return string
	 */
	public function shortcode_wrapper($function, $atts = array()) {
		ob_start();
		call_user_func($function, $atts);
		return ob_get_clean();
	}

	/**
	 * Shortcode CLass Loader
	 *
	 * @access public
	 * @param mixed $class_name
	 * @return void
	 */
	public function load_class($class_name = '') {
		global $WCFM;
		if ('' != $class_name && '' != $WCFM->token) {
			require_once ( $WCFM->plugin_path . 'includes/shortcodes/class-' . esc_attr($WCFM->token) . '-shortcode-' . esc_attr($class_name) . '.php' );
		}
	}

}
?>