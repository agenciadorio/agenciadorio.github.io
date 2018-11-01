<?php
/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/


if ( ! defined('ABSPATH')) exit;  // if direct access 

	if( empty( $poll_id ) ) $poll_id = get_the_ID();
	

	echo '<div class="wpp_content">';
	the_content();
	echo '</div>';
?>
