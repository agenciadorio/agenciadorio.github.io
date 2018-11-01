<?php
/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/


if ( ! defined('ABSPATH')) exit;  // if direct access 

?>

<div id="poll-<?php echo $poll_id ?>" class="single-poll single-poll-<?php echo $poll_id ?>">
		
	<?php
		do_action( 'wpp_action_single_poll_title', $poll_id );
		do_action( 'wpp_action_single_poll_notice', $poll_id );
		do_action( 'wpp_action_single_poll_message', $poll_id);
		do_action( 'wpp_action_single_poll_options', $poll_id );
		do_action( 'wpp_action_single_poll_results', $poll_id );
		do_action( 'wpp_action_single_poll_buttons', $poll_id );
	?>
	
</div>