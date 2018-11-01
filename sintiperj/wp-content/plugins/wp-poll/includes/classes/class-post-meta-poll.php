<?php

/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class WPP_Post_meta_Poll{
	
	public function __construct(){
		add_action('add_meta_boxes', array($this, 'meta_boxes_poll'));
		add_action('save_post', array($this, 'meta_boxes_poll_save'));
	}
	
	public function meta_boxes_poll($post_type) {
		$post_types = array('poll');
		if (in_array($post_type, $post_types)) {
		
			add_meta_box('poll_metabox',
				__( 'Poll Data Box', WPP_TEXT_DOMAIN ),
				array($this, 'poll_meta_box_function'),
				$post_type,
				'normal',
				'high'
			);
				
		}
	}
	
	public function poll_meta_box_function($post) {
 
        wp_nonce_field('poll_nonce_check', 'poll_nonce_check_value');
		
		require_once( WPP_PLUGIN_DIR . 'templates/admin/poll-meta-box.php' );
   	}
	
	public function meta_boxes_poll_save($post_id){
	 
		if (!isset($_POST['poll_nonce_check_value'])) return $post_id;
		$nonce = $_POST['poll_nonce_check_value'];
		if (!wp_verify_nonce($nonce, 'poll_nonce_check')) return $post_id;

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
	 
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}

		$poll_meta_options = stripslashes_deep( $_POST['poll_meta_options'] );
		update_post_meta( $post_id, 'poll_meta_options', $poll_meta_options );		
		
		$poll_meta_multiple = stripslashes_deep( $_POST['poll_meta_multiple'] );
		update_post_meta( $post_id, 'poll_meta_multiple', $poll_meta_multiple );		
		
		$poll_deadline = stripslashes_deep( $_POST['poll_deadline'] );
		update_post_meta( $post_id, 'poll_deadline', $poll_deadline );		
		
		$poll_meta_new_option = stripslashes_deep( $_POST['poll_meta_new_option'] );
		update_post_meta( $post_id, 'poll_meta_new_option', $poll_meta_new_option );		
			
	}
	
} new WPP_Post_meta_Poll();