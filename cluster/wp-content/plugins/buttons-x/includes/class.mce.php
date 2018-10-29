<?php
/**
 * Button X
 *
 * This file is used to register WooCommerce related functionality of the plugin.
 *
 * @package Buttons X
 * @since 0.1
 */

// Make sure we don't expose any info if called directly
if ( !defined( 'ABSPATH' ) )
	exit;

if( !class_exists( 'BtnsxMce' ) ) {
	class BtnsxMce {

		private static $instance;

		/**
		 * Initiator
		 * @since 0.1
		 */
		public static function init() {
			return self::$instance;
		}

		/**
		 * Constructor
		 * @since 0.1
		 */
		public function __construct() {
			add_action( 'wp_ajax_buttons_list', array( $this, 'buttons_list_ajax' ) );
			add_action( 'admin_footer', array( $this, 'buttons_list' ) );
			add_action( 'admin_head', array( $this, 'mce_button' ) );
		}

		// Hooks your functions into the correct filters
		function mce_button() {
			// check user permissions
			if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
				return;
			}
			// check if WYSIWYG is enabled
			if ( 'true' == get_user_option( 'rich_editing' ) ) {
				add_filter( 'mce_external_plugins', array( $this, 'add_mce_plugin' ) );
				add_filter( 'mce_buttons', array( $this, 'register_mce_button' ) );
			}
		}

		// Declare script for our button
		function add_mce_plugin( $plugin_array ) {
			$plugin_array['btnsx_mce_button'] = BTNSX__PLUGIN_URL . 'assets/js/admin/mce.js';
			return $plugin_array;
		}

		// Register our button in the editor
		function register_mce_button( $buttons ) {
			array_push( $buttons, 'btnsx_mce_button' );
			return $buttons;
		}

		/**
		 * Function to fetch buttons
		 * @since  1.7
		 * @return string
		 */
		public function buttons( $post_type ) {

			global $wpdb;
		   	$btnsx_post = $post_type;
			$btnsx_post_status = 'publish';
	        $btnsx = $wpdb->get_results( $wpdb->prepare(
	            "SELECT ID, post_title
	                FROM $wpdb->posts 
	                WHERE $wpdb->posts.post_type = %s
	                AND $wpdb->posts.post_status = %s
	                ORDER BY ID DESC",
	            $btnsx_post,
	            $btnsx_post_status
	        ) );

	        $list = array();

	        foreach ( $btnsx as $btn ) {
				$selected = '';
				$btn_id = $btn->ID;
				$btn_name = $btn->post_title;
				$list[] = array(
					'text' =>	$btn_name,
					'value'	=>	$btn_id
				);
			}

			wp_send_json( $list );
		}

		/**
		 * Function to fetch buttons
		 * @since  1.6
		 * @return string
		 */
		public function buttons_list_ajax() {
			// check for nonce
			check_ajax_referer( 'btnsx-buttons-list', 'security' );
			$btns = $this->buttons( 'buttons-x' );
			return $btns;
		}
 		
		/**
		 * Function to output button list ajax script
		 * @since  1.6
		 * @return string
		 */
		public function buttons_list() {
			global $pagenow;
			if( $pagenow != 'admin.php' ){
				// create nonce
				$buttons_list_nonce = wp_create_nonce( 'btnsx-buttons-list' );
				?>
			    <script type="text/javascript">
					jQuery( document ).ready( function( $ ) {
						var data = {
							'action'	: 'buttons_list',							// wp ajax action
							'security'	: '<?php echo $buttons_list_nonce; ?>'		// nonce value created earlier
						};
						// fire ajax
					  	jQuery.post( ajaxurl, data, function( response ) {
					  		// if nonce fails then not authorized else settings saved
					  		if( response === '-1' ){
						  		// do nothing
						  		console.log('error');
					  		} else {
					  			if (typeof(tinyMCE) != 'undefined') {
					  				if (tinyMCE.activeEditor != null) {
										tinyMCE.activeEditor.settings.btnsxButtonsList = response;
									}
								}
					  		}
					  	});
					});
				</script>
				<?php
			}
		}

		
	} // Mce Class
}

/**
 *  Kicking this off
 */

$btn_mce = new BtnsxMce();
$btn_mce->init();