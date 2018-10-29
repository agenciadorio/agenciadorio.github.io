	<?php 
		if ( post_password_required() ) {
	?>
	<p class="nopassword"><?php echo 'This post is password protected. Enter the password to view any comments.'; ?></p>

<?php
			return;
		}
		$postType = get_post_type();
?>

<?php if ( have_comments() ) { ?>
	<!-- BEGIN BLOG COMMENTS SECTION -->
	<?php 
		if ($postType != 'rooms') { 
	?>
		<div class="blog-comments"><?php printf(_n('1 Responce in ','%s Responces in ', get_comments_number(),'nation'), get_comments_number() ); the_title() ?> </div>
	<?php } ?>
	<div style="clear:both"></div>
	<div class="comment-section">

<?php

	if ( $postType == 'rooms' ) {
		wp_list_comments( array( 'max_depth' => 1, 'style' => 'div', 'walker' => new post_comment) );
	} else {
		wp_list_comments( array( 'max_depth' => 3, 'style' => 'div', 'walker' => new post_comment) );
	}
	
	echo "<div id='comments-navigation'>";
	
	echo previous_comments_link( __("<span class='icon-angle-left'></span> &nbsp; Prev Comments",'nation') ); 
	echo next_comments_link( __("Next Comments &nbsp; <span class='icon-angle-right'></span>",'nation') );
	
	echo "</div>";
	

	?>

		<div class="clear"></div>
	</div>
<?php } else { 
	if ( ! comments_open() ) {
?>
	<p class="nocomments"><?php echo 'Comments are closed.'; ?></p>
<?php } ?>

<?php } ?>
	<!-- END BLOG COMMENTS SECTION -->
	
	<!-- BEGIN POST COMMENTS FORM -->
	<?php 
	if ($postType == 'rooms') { 
		$titleReply = __('Leave a Review','nation');
	} else {
		$titleReply = __('Leave a Reply','nation');
	}
		
	comment_form( array( 
		'title_reply' => $titleReply, 
		'fields' => array(
			'author' => '<div id="comment-field-name-wrap"><label for="name-comments-field">'.__('Name *','nation').'</label><input type="text" id="name-comments-field" name="author" value="'.__('enter name','nation').'"></div>', 
			'email' => '<div id="comment-field-email-wrap"><label for="email-comments-field">'.__('Email *','nation').'</label><input type="text" id="email-comments-field" name="email" value="'.__('enter email','nation').'"></div><div style="clear:both"></div>', 
			), 
		'comment_field' => '<label for="text-comments-field">'.__('Message *','nation').'</label><textarea id="text-comments-field" name="comment">'.__('enter comment text here','nation').'</textarea>', 
		'label_submit' => __('Post Comment','nation'), 'id_submit' => 'submit-button',
		'comment_notes_before' => '',
		'comment_notes_after' => '<button type="submit" id="submit-button">Post Comment <span class="icon-envelope"></span></button>',
	) ); ?>	
	<!-- END POST COMMENTS FORM -->