<?php
/**
 * Admin Class
 *
 * Handles the Admin side functionality of plugin
 *
 * @package Album and Image Gallery Plus Lightbox
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class Aigpl_Admin {
	
	function __construct() {
		
		// Action to add metabox
		add_action( 'add_meta_boxes', array($this, 'aigpl_post_sett_metabox') );

		// Action to save metabox
		add_action( 'save_post', array($this, 'aigpl_save_metabox_value') );

		// Filter to add extra column in gallery `category` table
		add_filter( 'manage_edit-'.AIGPL_CAT.'_columns', array($this, 'aigpl_manage_category_columns') );
		add_filter( 'manage_'.AIGPL_CAT.'_custom_column', array($this, 'aigpl_category_data'), 10, 3 );

		// Action to add custom column to Gallery listing
		add_filter( 'manage_'.AIGPL_POST_TYPE.'_posts_columns', array($this, 'aigpl_posts_columns') );

		// Action to add custom column data to Gallery listing
		add_action('manage_'.AIGPL_POST_TYPE.'_posts_custom_column', array($this, 'aigpl_post_columns_data'), 10, 2);

		// Filter to add row data
		add_filter( 'post_row_actions', array($this, 'aigpl_add_post_row_data'), 10, 2 );

		// Action to add Attachment Popup HTML
		add_action( 'admin_footer', array($this,'aigpl_image_update_popup_html') );

		// Ajax call to update option
		add_action( 'wp_ajax_aigpl_get_attachment_edit_form', array($this, 'aigpl_get_attachment_edit_form'));
		add_action( 'wp_ajax_nopriv_aigpl_get_attachment_edit_form',array( $this, 'aigpl_get_attachment_edit_form'));

		// Ajax call to update attachment data
		add_action( 'wp_ajax_aigpl_save_attachment_data', array($this, 'aigpl_save_attachment_data'));
		add_action( 'wp_ajax_nopriv_aigpl_save_attachment_data',array( $this, 'aigpl_save_attachment_data'));
	}

	/**
	 * Post Settings Metabox
	 * 
	 * @package Album and Image Gallery Plus Lightbox
	 * @since 1.0.0
	 */
	function aigpl_post_sett_metabox() {
		add_meta_box( 'aigpl-post-sett', __( 'Album and Image Gallery Plus Lightbox - Settings', 'album-and-image-gallery-plus-lightbox' ), array($this, 'aigpl_post_sett_mb_content'), AIGPL_POST_TYPE, 'normal', 'high' );
	}

	/**
	 * Post Settings Metabox HTML
	 * 
	 * @package Album and Image Gallery Plus Lightbox
	 * @since 1.0.0
	 */
	function aigpl_post_sett_mb_content() {
		include_once( AIGPL_DIR .'/includes/admin/metabox/aigpl-sett-metabox.php');
	}

	/**
	 * Function to save metabox values
	 * 
	 * @package Album and Image Gallery Plus Lightbox
	 * @since 1.0.0
	 */
	function aigpl_save_metabox_value( $post_id ) {

		global $post_type;
		
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )                	// Check Autosave
		|| ( ! isset( $_POST['post_ID'] ) || $post_id != $_POST['post_ID'] )  	// Check Revision
		|| ( $post_type !=  AIGPL_POST_TYPE ) )              					// Check if current post type is supported.
		{
		  return $post_id;
		}
		
		$prefix = AIGPL_META_PREFIX; // Taking metabox prefix
		
		// Taking variables
		$gallery_imgs = isset($_POST['aigpl_img']) ? aigpl_slashes_deep($_POST['aigpl_img']) : '';
		
		update_post_meta($post_id, $prefix.'gallery_imgs', $gallery_imgs);
	}
	
	/**
	 * Add extra column to news category
	 * 
	 * @package Album and Image Gallery Plus Lightbox
	 * @since 1.0.0
	 */
	function aigpl_manage_category_columns($columns) {

		$new_columns['aigpl_shortcode'] = __( 'Category Shortcode', 'album-and-image-gallery-plus-lightbox' );

		$columns = aigpl_add_array( $columns, $new_columns, 2 );

		return $columns;
	}

	/**
	 * Add data to extra column to news category
	 * 
	 * @package Album and Image Gallery Plus Lightbox
	 * @since 1.0.0
	 */
	function aigpl_category_data($ouput, $column_name, $tax_id) {
		
		if( $column_name == 'aigpl_shortcode' ) {
			$ouput .= '[aigpl-gallery-album category="' . $tax_id. '"]<br/>';
			$ouput .= '[aigpl-gallery-album-slider category="' . $tax_id. '"]';
	    }
		
	    return $ouput;
	}

	/**
	 * Add custom column to Post listing page
	 * 
	 * @package Album and Image Gallery Plus Lightbox
	 * @since 1.0.0
	 */
	function aigpl_posts_columns( $columns ) {

	    $new_columns['aigpl_shortcode'] 	= __('Shortcode', 'album-and-image-gallery-plus-lightbox');
	    $new_columns['aigpl_photos'] 		= __('Number of Photos', 'album-and-image-gallery-plus-lightbox');

	    $columns = aigpl_add_array( $columns, $new_columns, 1, true );

	    return $columns;
	}

	/**
	 * Add custom column data to Post listing page
	 * 
	 * @package Album and Image Gallery Plus Lightbox
	 * @since 1.0.0
	 */
	function aigpl_post_columns_data( $column, $post_id ) {

		global $post;

		// Taking some variables
		$prefix = AIGPL_META_PREFIX;

	    switch ($column) {
	    	case 'aigpl_shortcode':
	    		
	    		echo '<div class="aigpl-shortcode-preview">[aigpl-gallery id="'.$post_id.'"]</div> <br/>';
	    		echo '<div class="aigpl-shortcode-preview">[aigpl-gallery-slider id="'.$post_id.'"]</div>';
	    		break;

	    	case 'aigpl_photos':
	    		$total_photos = get_post_meta($post_id, $prefix.'gallery_imgs', true);
	    		echo !empty($total_photos) ? count($total_photos) : '--';
	    		break;
		}
	}

	/**
	 * Function to add custom quick links at post listing page
	 * 
	 * @package Album and Image Gallery Plus Lightbox
	 * @since 1.0.0
	 */
	function aigpl_add_post_row_data( $actions, $post ) {
		
		if( $post->post_type == AIGPL_POST_TYPE ) {
			return array_merge( array( 'aigpl_id' => 'ID: ' . $post->ID ), $actions );
		}
		
		return $actions;
	}

	/**
	 * Image data popup HTML
	 * 
	 * @package Album and Image Gallery Plus Lightbox
	 * @since 1.0.0
	 */
	function aigpl_image_update_popup_html() {

		global $typenow;

		if( $typenow == AIGPL_POST_TYPE ) {
			include_once( AIGPL_DIR .'/includes/admin/settings/aigpl-img-popup.php');
		}
	}

	/**
	 * Get attachment edit form
	 * 
	 * @package Album and Image Gallery Plus Lightbox
	 * @since 1.0.0
	 */
	function aigpl_get_attachment_edit_form() {

		// Taking some defaults
		$result 			= array();
		$result['success'] 	= 0;
		$result['msg'] 		= __('Sorry, Something happened wrong.', 'album-and-image-gallery-plus-lightbox');
		$attachment_id 		= !empty($_POST['attachment_id']) ? trim($_POST['attachment_id']) : '';

		if( !empty($attachment_id) ) {
			$attachment_post = get_post( $_POST['attachment_id'] );

			if( !empty($attachment_post) ) {
				
				ob_start();

				// Popup Data File
				include( AIGPL_DIR . '/includes/admin/settings/aigpl-img-popup-data.php' );

				$attachment_data = ob_get_clean();

				$result['success'] 	= 1;
				$result['msg'] 		= __('Attachment Found.', 'album-and-image-gallery-plus-lightbox');
				$result['data']		= $attachment_data;
			}
		}

		echo json_encode($result);
		exit;
	}

	/**
	 * Get attachment edit form
	 * 
	 * @package Album and Image Gallery Plus Lightbox
	 * @since 1.0.0
	 */
	function aigpl_save_attachment_data() {

		$prefix 			= AIGPL_META_PREFIX;
		$result 			= array();
		$result['success'] 	= 0;
		$result['msg'] 		= __('Sorry, Something happened wrong.', 'album-and-image-gallery-plus-lightbox');
		$attachment_id 		= !empty($_POST['attachment_id']) ? trim($_POST['attachment_id']) : '';
		$form_data 			= parse_str($_POST['form_data'], $form_data_arr);

		if( !empty($attachment_id) && !empty($form_data_arr) ) {

			// Getting attachment post
			$aigpl_attachment_post = get_post( $attachment_id );

			// If post type is attachment
			if( isset($aigpl_attachment_post->post_type) && $aigpl_attachment_post->post_type == 'attachment' ) {
				$post_args = array(
									'ID'			=> $attachment_id,
									'post_title'	=> !empty($form_data_arr['aigpl_attachment_title']) ? $form_data_arr['aigpl_attachment_title'] : $aigpl_attachment_post->post_name,
									'post_content'	=> $form_data_arr['aigpl_attachment_desc'],
									'post_excerpt'	=> $form_data_arr['aigpl_attachment_caption'],
								);
				$update = wp_update_post( $post_args, $wp_error );

				if( !is_wp_error( $update ) ) {

					update_post_meta( $attachment_id, '_wp_attachment_image_alt', aigpl_slashes_deep($form_data_arr['aigpl_attachment_alt']) );
					update_post_meta( $attachment_id, $prefix.'attachment_link', aigpl_slashes_deep($form_data_arr['aigpl_attachment_link']) );

					$result['success'] 	= 1;
					$result['msg'] 		= __('Your changes saved successfully.', 'album-and-image-gallery-plus-lightbox');
				}
			}
		}
		echo json_encode($result);
		exit;
	}
}

$aigpl_admin = new Aigpl_Admin();