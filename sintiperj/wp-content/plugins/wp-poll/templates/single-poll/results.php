<?php
/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/


if ( ! defined('ABSPATH')) exit;  // if direct access 
	
	if( empty( $poll_id ) ) $poll_id = get_the_ID();
	
	
	
	$poll_meta_options 	= get_post_meta( $poll_id, 'poll_meta_options', true );
	$polled_data		= get_post_meta( $poll_id, 'polled_data', true );
	$poller 			= get_poller();
	
	if( empty( $polled_data ) ) $polled_data = array();
	
	// echo '<pre>'; print_r( $poll_meta_options ); echo '</pre>';
	// echo '<pre>'; print_r( $polled_data ); echo '</pre>';

	
	echo "<ul class='wpp_result_list'>";
	foreach( $poll_meta_options as $option_id => $option_value ) {
		
		if( empty( $option_value ) ) continue;
		
		$count = 0;
		foreach( $polled_data as $data ){
			
			if( in_array( $option_id, $data ) ){
				$count++;
			}
		}
		
		echo "
		<li class='wpp_option_single slideRight' option_id='$option_id'>
			<span><i class='fa fa-caret-right'></i></span>
			<span>$option_value</span>
			<span> - </span>
			<span>$count</span>
		</li>";
	}
	echo "</ul>";
	