<?php
/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/


if ( ! defined('ABSPATH')) exit;  // if direct access 

if( empty( $poll_id ) ) $poll_id = get_the_ID();

$thumb_url = get_the_post_thumbnail_url( $poll_id )



?>

<div class="wpp_thumbnail_container">
	<img src="<?php echo $thumb_url; ?>" />
</div>
