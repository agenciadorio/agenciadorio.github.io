<?php

/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


class WPP_Update_db {

	public function __construct() {
		add_action( 'admin_notices', array( $this, 'wpp_notice_update_db') );
		add_action( 'admin_init', array( $this, 'wpp_db_update_settings') );
	}

	public function wpp_notice_update_db() {
		
		if( get_option( 'wpp_complete_update', 'no' ) == 'no' ) {
			?>
			<div id="message" class="updated">
				<p><?php 
				echo 
				'<strong>'. 
				__( 'Welcome to WP Poll.', WPP_TEXT_DOMAIN ). ' '.
				__( 'You may have Previous poll data.', WPP_TEXT_DOMAIN ). ' '.
				__( 'You need to Update Database to get back them all.', WPP_TEXT_DOMAIN ).
				'</strong>';
				?></p>
				<p class="submit">
					<a href="<?php echo esc_url( add_query_arg( 'wpp_action', 'do_update' ) ); ?>" class="button-primary"><?php _e( 'Update Now', WPP_TEXT_DOMAIN ); ?></a> 
					<a href="<?php echo esc_url( add_query_arg( 'wpp_action', 'skip_update' ) ); ?>" class="button-secondary skip"><?php _e( 'Skip Update', WPP_TEXT_DOMAIN ); ?></a>
				</p>
			</div>
			<?php
		}
	}
	
	public function wpp_db_update_settings(){
		
		global $wpdb;
		$previous_polls = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type='wp_poll'");
			
		// Return if No Previous Polls found
		if( empty( $previous_polls ) ) {
			update_option( 'wpp_complete_update', 'no_need' );
			return;
		} 
		
		$wpp_complete_update = get_option( 'wpp_complete_update', 'no' );
		if( $wpp_complete_update == 'skip' || $wpp_complete_update == 'yes' || $wpp_complete_update == 'no_need' ) return;
		
		$wpp_action = isset( $_GET['wpp_action'] ) ? $_GET['wpp_action'] : '';
		if( empty( $wpp_action ) ) return;
		
		// Skip if Skip option selected
		if( $wpp_action == 'skip_update' ): update_option( 'wpp_complete_update', 'skip' );
		endif;
		
		if( $wpp_action == 'do_update' ):
		
			foreach( $previous_polls as $poll ) {
				
				$poll_meta_options = array();
				$poll_meta_options[time()] 		= get_post_meta( $poll->ID, 'wp_poll_option_1', true );
				$poll_meta_options[time()+10] 	= get_post_meta( $poll->ID, 'wp_poll_option_2', true );
				$poll_meta_options[time()+20] 	= get_post_meta( $poll->ID, 'wp_poll_option_3', true );
				$poll_meta_options[time()+30] 	= get_post_meta( $poll->ID, 'wp_poll_option_4', true );
				$poll_meta_options[time()+40] 	= get_post_meta( $poll->ID, 'wp_poll_option_5', true );
				
				
				$new_poll_ID = wp_insert_post(
					array(
						'post_title'    => $poll->post_title,
						'post_content'  => $poll->post_content,
						'post_status'   => $poll->post_status,
						'post_type'   	=> 'poll',
						'post_author'   => $poll->post_author,
					)
				);
				
				// Update Post Metas
				update_post_meta( $new_poll_ID, 'poll_meta_options', array_filter( $poll_meta_options) );
				
				update_option( 'wpp_complete_update', 'yes' );
			}

		endif;
	}
} new WPP_Update_db();
