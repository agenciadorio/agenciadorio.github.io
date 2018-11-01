<?php
/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/


if ( ! defined('ABSPATH')) exit;  // if direct access 

// Color section
$wpp_color_title = get_option( 'wpp_color_title' );
if( empty( $wpp_color_title ) ) $wpp_color_title = '#2D2D2D';

global $wpp_css;
$wpp_css .= ".wpp_poll_title{ color: $wpp_color_title; }";
	
?>

<h1 itemprop="name" class="title wpp_poll_title"><?php echo apply_filters( 'qa_filter_single_poll_title', get_the_title( $poll_id ) );  ?></h1>
