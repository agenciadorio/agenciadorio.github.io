<?php
/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 



function wpp_ajax_submit_comment() {
	
	$html 			= '';
	$poll_id 		= (int)sanitize_text_field($_POST['poll_id']);
	$wpp_name 		= sanitize_text_field($_POST['wpp_name']);
	$wpp_email 		= sanitize_email($_POST['wpp_email']);
	$wpp_comment 	= sanitize_text_field($_POST['wpp_comment']);
	
	$user_id = email_exists( $wpp_email );
	if ( !$user_id ) {
		
		$arr_user_name		= explode($wpp_email);
		$user_name			= isset($arr_user_name[0]) ? $arr_user_name[0] : $wpp_email;
		$random_password 	= wp_generate_password( $length=12, $include_standard_special_chars=false );
		
		$user_id = wp_create_user( $user_name, $random_password, $wpp_email );
		wp_update_user( array( 'ID' => $user_id, 'display_name' => $wpp_name ) );
	}
	
	$wpp_comment_data = array(
		'comment_post_ID' => $poll_id,
		'comment_author' => $wpp_name,
		'comment_author_email' => $wpp_email,
		'comment_content' => $wpp_comment,
		'comment_type' => '',
		'comment_parent' => 0,
		'user_id' => $user_id,
		'comment_author_IP' => wpp_get_ip_address(),
		'comment_date' => current_time('mysql'),
		'comment_approved' => 1,
	);

	$wpp_comment_id = wp_insert_comment($wpp_comment_data);
	
	$wpp_comment_message_error = get_option( 'wpp_comment_message_error' );
	$wpp_comment_message_success = get_option( 'wpp_comment_message_success' );
	if( empty( $wpp_comment_message_error ) )
		$wpp_comment_message_error = __('Something went wrong, Please try latter', WPP_TEXT_DOMAIN );
	if( empty( $wpp_comment_message_success ) )
		$wpp_comment_message_success = __('Success, Your Comment may be under review and publish latter', WPP_TEXT_DOMAIN );
	
	if( ! $wpp_comment_id ){	
		$html .= '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> '.
		apply_filters( 'wpp_filters_comment_error', $wpp_comment_message_error );
	}
	else {
		$html .= '<i class="fa fa-check-circle-o" aria-hidden="true"></i> '.
		apply_filters( 'wpp_filters_comment_success', $wpp_comment_message_success );
	}
	
	echo $html;
	die();
}
add_action('wp_ajax_wpp_ajax_submit_comment', 'wpp_ajax_submit_comment');
add_action('wp_ajax_nopriv_wpp_ajax_submit_comment', 'wpp_ajax_submit_comment');	
	
	
	
	
	function wpp_ajax_add_new_option() {
		
		$response 		= array();
		$poll_id 		= (int)sanitize_text_field($_POST['poll_id']);
		$option_val 	= sanitize_text_field($_POST['option_val']);
		
		if( empty( $poll_id ) || empty( $option_val ) ) die();
		
		$poll_meta_options 	= get_post_meta( $poll_id, 'poll_meta_options', true );
		if( empty( $poll_meta_options ) ) $poll_meta_options = array();
		
		$poll_meta_options[ time() ] = $option_val;
		
		
		update_post_meta( $poll_id, 'poll_meta_options', $poll_meta_options );
		
		echo 'ok';
		die();
	}
	add_action('wp_ajax_wpp_ajax_add_new_option', 'wpp_ajax_add_new_option');
	add_action('wp_ajax_nopriv_wpp_ajax_add_new_option', 'wpp_ajax_add_new_option');	
	
	
	
	function wpp_ajax_submit_poll() {
		
		$response 		= array();
		$poll_id 		= (int)sanitize_text_field($_POST['poll_id']);
		$checked_opts	= $_POST['checked'];
		
		$polled_data 	= array();
		$polled_data	= get_post_meta( $poll_id, 'polled_data', true );
		$poller 		= get_poller();
		
		if( array_key_exists( $poller, $polled_data ) ) {
			
			$response['status'] = 0;
			$response['notice'] = '<i class="fa fa-exclamation-triangle"></i> You have reached the Maximum Polling quota !';
		}
		else {
			
			foreach( $checked_opts as $option ) {
				$polled_data[$poller][] = $option;
			}
			update_post_meta( $poll_id, 'polled_data', $polled_data );
			
			$response['status'] = 1;
			$response['notice'] = '<i class="fa fa-check"></i> Successfully Polled on this.';
		}

		echo json_encode($response);
		die();
	}
	add_action('wp_ajax_wpp_ajax_submit_poll', 'wpp_ajax_submit_poll');
	add_action('wp_ajax_nopriv_wpp_ajax_submit_poll', 'wpp_ajax_submit_poll');	
	
	
	
	function show_notice( $type = 1 ){
		
		if( $type == 1 ) $notice_type = 'wpp_success';
		if( $type == 0 ) $notice_type = 'wpp_error';
		
		return "<div class='wpp_notice $notice_type'>Warning: You have already Polled !</div>";
	}
	
	function get_poller(){
		
		$user = wp_get_current_user();
		if( $user->ID != 0 ) return $user->ID;
		
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}


		return $ip;
	}

	function qa_single_poll_template($single_template) {
		 global $post;
		 if ($post->post_type == 'poll') {
			  $single_template = WPP_PLUGIN_DIR . 'templates/single-poll/single-poll.php';
		 }
		 return $single_template;
	}
	add_filter( 'single_template', 'qa_single_poll_template' );
	
	
	// function qa_single_poll_template($content) {
		 // global $post;
		 
		 // if ($post->post_type == 'poll') {
			 
			// ob_start();		
			// include WPP_PLUGIN_DIR . 'templates/single-poll/single-poll.php';
			// return ob_get_clean();		
		 // }
		 // return $content;
	// }
	// add_filter( 'the_content', 'qa_single_poll_template' );
	
	
	function wpp_dark_color($rgb, $darker=2) {

		$hash = (strpos($rgb, '#') !== false) ? '#' : '';
		$rgb = (strlen($rgb) == 7) ? str_replace('#', '', $rgb) : ((strlen($rgb) == 6) ? $rgb : false);
		if(strlen($rgb) != 6) return $hash.'000000';
		$darker = ($darker > 1) ? $darker : 1;

		list($R16,$G16,$B16) = str_split($rgb,2);

		$R = sprintf("%02X", floor(hexdec($R16)/$darker));
		$G = sprintf("%02X", floor(hexdec($G16)/$darker));
		$B = sprintf("%02X", floor(hexdec($B16)/$darker));

		return $hash.$R.$G.$B;
	}
	
function wpp_get_ip_address() {
    $ip = $_SERVER['REMOTE_ADDR'];
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    return $ip;
}