<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains comments and the comment form.
/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() )
    return;
?>
<?php  global $textdomain; ?>
<!-- COMMENTS -->
<div class="blog-article-comments list-comment">
	<?php if ( have_comments() ) { ?> 
		<h4 class="title">
			<?php echo sprintf( _n( '%d comments', '%d comments', get_comments_number(), 'construction' ), get_comments_number() ); ?>
		</h4>
		<ul>
		<?php wp_list_comments('style=ul&callback=construction_theme_comment'); ?>
		</ul>
	<?php
	// Are there comments to navigate through?
	if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
		?>
			<footer class="navigation comment-navigation" role="navigation">
				
				<div class="previous"><?php previous_comments_link( esc_html__( '&larr; Older Comments', 'construction' ) ); ?></div>
				<div class="next right"><?php next_comments_link( esc_html__( 'Newer Comments &rarr;', 'construction' ) ); ?></div>
			</footer><!-- .comment-navigation -->
		<?php endif; // Check for comment navigation ?>

		<?php if ( ! comments_open() && get_comments_number() ) : ?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.' , 'construction' ); ?></p>
		<?php endif; ?>
	<?php } ?>
</div><!-- //COMMENTS -->
<?php
	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$comment_args = array(
	'id_form'           => 'send-form',
  	'id_submit'         => 'submit',
  	'class_submit' => 'btn btn-1 btn-bg-1',
	'title_reply'=> '',
	'comment_field' => '<div class="form-group"><textarea name="comment" class="form-control form-item" id="comment" rows="10" cols="7" placeholder=""></textarea></div>',
	'fields' => apply_filters( 'comment_form_default_fields', array(
		'author' => '<div class="row"><div class="col-md-6"><div class="form-group"><input type="text" name="author" class="form-control form-item" id="author" placeholder="'. esc_html__("Name","construction") .'" value="' . esc_attr( $commenter['comment_author'] ) . '" ' . $aria_req . '></div></div>',
		'email' => '<div class="col-md-6"><div class="form-group"><input type="text" class="form-control form-item" name="email" id="email" placeholder="'. esc_html__("Email","construction") .'"" value="'. esc_attr($commenter['comment_author_email']) .'" '. $aria_req .'></div></div></div>'
	) ),
	'label_submit' => esc_html__('Post comment','construction'),
	'comment_notes_before' => '',
	'comment_notes_after' => '',
	);

?>
<?php global $post; ?>
<?php if('open' == $post->comment_status){ ?>
<!-- LEAVE A COMMENT -->
	<div class="comment-form form-comment">
		<h4 class="comment-form-title title"><?php esc_html_e('Leave a reply','construction');?></h4>
		<?php comment_form( $comment_args ); ?>	
	</div><!-- end comment form --> 
<?php } ?>