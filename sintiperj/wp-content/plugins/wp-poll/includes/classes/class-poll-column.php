<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class WPP_Poll_column{
	
	public function __construct(){

		add_action( 'manage_poll_posts_columns', array( $this, 'add_core_poll_columns' ), 16, 1 );
		add_action( 'manage_poll_posts_custom_column', array( $this, 'custom_columns_content' ), 10, 2 );
	}
	
	public function add_core_poll_columns( $columns ) {

		$new = array();
		
		$count = 0;
		foreach ( $columns as $col_id => $col_label ) { $count++;

			if ( $count == 3 ) {
				$new['poll-report'] = __( 'Poll Report', WPP_TEXT_DOMAIN );
			}
			
			if( 'title' === $col_id ) {
				$new[$col_id] = __( 'Poll title', WPP_TEXT_DOMAIN );
			} else {
				$new[ $col_id ] = $col_label;
			}
			
			unset( $new['date'] );
		}
		
		$new['poll-date'] = __( 'Published at', WPP_TEXT_DOMAIN );
		
		return $new;
	}
	
	public function custom_columns_content( $column, $post_id ) {
		
		if( $column == 'poll-report' ):
			
			$polled_data	= get_post_meta( $post_id, 'polled_data', true );
			$poller 		= count( $polled_data );
			
			echo sprintf("<i>%d %s</i>", $poller, __('people polled on this', WPP_TEXT_DOMAIN) );
			
			echo '<div class="row-actions">';
			echo sprintf(  '<span class="view_report"><a href="%s" rel="permalink">'.__('View Reports', WPP_TEXT_DOMAIN).'</a></span>', "edit.php?post_type=poll&page=wpp_reports&id=".$post_id );
			echo '</div>';
			
		endif;
		
		if( $column == 'poll-date' ):
			
			$time_ago = human_time_diff( get_the_time('U'), current_time('timestamp') ); 
			echo "<i>$time_ago ". __('ago', WPP_TEXT_DOMAIN) ."</i>";
			
		endif;
	}

} new WPP_Poll_column();