<?php
/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/


if ( ! defined('ABSPATH')) exit;  // if direct access 

	// if( $poll_post->comment_status == 'closed' ) return;
	
	echo '<br /><br />';
	comments_template();
	return;
	
	
	
	
	
	if( empty( $poll_id ) ) $poll_id = get_the_ID();
	
	$poll_post = get_post( $poll_id );
	$current_user = get_userdata( 1 );
	
	$comments = get_comments( 
		array(
			'post_id' 	=> $poll_id, 
			'order' 	=> 'ASC', 
			'status'	=> 'approve', 
		) 
	);
	
	$arr_comments = array();
	
	foreach( $comments as $comment ){
		
		if( $comment->comment_parent == 0 ){
			$arr_comments[$comment->comment_ID] = array();
		}
		else {
			
			if( ! isset( $arr_comments[$comment->comment_parent] ) ) {
				$arr_comments[$comment->comment_parent] = array();
			}
			array_push( $arr_comments[$comment->comment_parent], $comment->comment_ID );
		}
	}
	
	// echo '<pre>'; print_r( $arr_comments ); echo '</pre>';
	// echo '<pre>'; print_r( $comments ); echo '</pre>';
	

?>

</br></br>

<div class="wpp_comments">
	
	<div class="wpp_comments_list">
	<?php 
	foreach( $comments as $comment ) {
		
		$comment_date 	= new DateTime($comment->comment_date);
		$comment_date 	= $comment_date->format('M d, Y h:i A');
		$comment_author	= get_comment_author( $comment->comment_ID ); 
			
		echo "<div class='wpp_single_comment' id='comment-".$comment->comment_ID."'>";
		echo "<div class='comment_by'>$comment_author</div>";
		echo "<div class='comment_content'>";
		ob_start();
		comment_text( $comment->comment_ID );
		echo ob_get_clean();
		echo "</div>";
		echo "</div>";
	}			
	?>	
	</div>
	
	<div class="wpp_comments_box">
		<div class="wpp_comment_header"><?php echo __('Comment on this', WPP_TEXT_DOMAIN); ?></div>
		<div class="wpp_comment_section">
			<div class="wpp_comment_section_label"><?php echo __('Your Name', WPP_TEXT_DOMAIN) ?></div>
			<input type="text" name="wpp_name" class="wpp_name" placeholder="John Butter" />
		</div>
		<div class="wpp_comment_section">
			<div class="wpp_comment_section_label"><?php echo __('Your Email', WPP_TEXT_DOMAIN) ?></div>
			<input type="email" name="wpp_email" class="wpp_email" placeholder="user@yoursite.com" />
		</div>
		<div class="wpp_comment_section">
			<div class="wpp_comment_section_label"><?php echo __('Your Comment', WPP_TEXT_DOMAIN) ?></div>
			<textarea rows="10" name="wpp_comment" class="wpp_comment" placeholder="Write something here..."></textarea>
		</div>
		<br>
		<div class="wpp_comment_message"></div>
		<div class="button wpp_submit_comment" poll_id="<?php echo $poll_id; ?>"><?php echo __('Submit Comment', WPP_TEXT_DOMAIN); ?></div>
		
	</div>
	
</div>
