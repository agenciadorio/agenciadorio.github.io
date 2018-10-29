<?php
/**
 * The template for displaying Comments.
 */
?>
<div id="comments" class="comments  comments-post-<?php the_ID(); ?>">
	<?php if ( have_comments() || comments_open() || pings_open() ) : ?>

	<?php if ( get_comments_number() > 0 ) : ?>
		<h2 class="comments__heading"><?php StructurePressHelpers::pretty_comments_number(); ?></h2>
	<?php endif; ?>

	<?php if ( have_comments() ) : ?>

		<div class="comments__container">
			<?php wp_list_comments( array( 'callback' => 'StructurePressHelpers::custom_comment', 'avatar_size' => '150' ) ); ?>
		</div>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
			<nav id="comment-nav-below" class="text-xs-center  text-uppercase" role="navigation">
				<h3 class="assistive-text"><?php esc_html_e( 'Comment Navigation' , 'structurepress-pt' ); ?></h3>
				<div class="nav-previous  pull-left"><?php previous_comments_link( esc_html__( '&larr; Older Comments' , 'structurepress-pt' ) ); ?></div>
				<div class="nav-next  pull-right"><?php next_comments_link( esc_html__( 'Newer Comments &rarr;' , 'structurepress-pt' ) ); ?></div>
			</nav>
		<?php endif; ?>

		<?php
		//If there are no comments and comments are closed, let's leave a note.
		// But we only want the note on posts and pages that had comments in the first place.
		if ( ! comments_open() && get_comments_number() ) :
		?>
			<p class="nocomments"><?php esc_html_e( 'Comments for this post are closed.', 'structurepress-pt' ); ?></p>
		<?php
		endif;
		?>

	<?php endif; // have_comments() ?>

	<?php
	//display the title only if the comments are opened
	if ( comments_open() ) :
	?>
		<h2 class="comments__heading  comments__heading--write-a-comment"><?php esc_html_e( 'Write a Comment', 'structurepress-pt' ); ?></h2>
	<?php endif; ?>

	<?php
		$structurepress_commenter = wp_get_current_commenter();
		$structurepress_req       = get_option( 'require_name_email' );
		$structurepress_req_html  = $structurepress_req ? '<span class="required theme-clr">*</span>' : '';
		$structurepress_req_aria  = $structurepress_req ? ' aria-required="true" required' : '';

		$structurepress_fields    = array(
			'author' => sprintf( '<div class="row"><div class="col-xs-12  col-sm-6  form-group"><label for="author">%1$s%2$s</label><input id="author" name="author" type="text" value="%3$s" class="form-control" %4$s /></div></div>',
				esc_html__( 'First and Last name', 'structurepress-pt' ),
				$structurepress_req_html,
				esc_attr( $structurepress_commenter['comment_author'] ),
				$structurepress_req_aria
			),
			'email'  => sprintf( '<div class="row"><div class="col-xs-12  col-sm-6  form-group"><label for="email">%1$s%2$s</label><input id="email" name="email" type="email" value="%3$s" class="form-control" %4$s /></div></div>',
				esc_html__( 'E-mail Address', 'structurepress-pt' ),
				$structurepress_req_html,
				esc_attr( $structurepress_commenter['comment_author_email'] ),
				$structurepress_req_aria
			),
			'url'    => sprintf( '<div class="row"><div class="col-xs-12  col-sm-6  form-group"><label for="url">%1$s</label><input id="url" name="url" type="url" value="%2$s" class="form-control" /></div></div>',
				esc_html__( 'Website', 'structurepress-pt' ),
				esc_attr( $structurepress_commenter['comment_author_url'] )
			)
		);

		$structurepress_comments_args = array(
			'fields'        => $structurepress_fields,
			'id_submit'     => 'comments-submit-button',
			'class_submit'  => 'submit  btn  btn-primary  text-uppercase',
			'comment_field' => sprintf( '<div class="row"><div class="col-xs-12  form-group"><label for="comment">%1$s%2$s</label><textarea id="comment" name="comment" class="form-control" rows="8" aria-required="true"></textarea></div></div>',
				_x( 'Your comment', 'noun', 'structurepress-pt' ),
				$structurepress_req_html
			),
			'title_reply'   => '',
		);

		// https://developer.wordpress.org/reference/functions/comment_form/
		comment_form( $structurepress_comments_args );

	else : ?>
	<div class="comments__closed">
		<?php esc_html_e( 'Comments for this post are closed.' , 'structurepress-pt' ); ?>
	</div>
<?php
	endif;
?>

</div>