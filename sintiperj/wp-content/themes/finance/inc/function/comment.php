<?php if ( ! function_exists( 'finance_comment' ) ) :

function finance_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
	default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<header class="clearfix">
				<div class="avatar pull-left">
					<?php echo get_avatar( $comment, 50 ); ?>
				</div><!-- avatar -->
                <div class="meta-comment">
	                <div class="comment-author vcard">
	                <?php printf( __( '%s', 'finance' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
	                </div><!-- .comment-author .vcard -->
                
					<?php if ( $comment->comment_approved == '0' ) : ?>
	                	<div class="waiting-moderation">
						<em><?php esc_html_e( 'Your comment is awaiting moderation.', 'finance' ); ?></em>
						</div>
					<?php endif; ?>

					<div class="comment-meta commentmetadata">
					said on
						<time datetime="<?php comment_time( 'c' ); ?>">
						<?php printf( _x( '%1$s', '1: date', 'finance' ), get_comment_date()); ?>
						</time>
					</div><!-- .comment-meta .commentmetadata -->

					<div class="comment-content"><?php comment_text(); ?></div>

                	<div class="comment-action">
		            	<span class="edit-comment-link"><?php edit_comment_link( __( 'Edit', 'finance' ), '<span class="edit-link">', '<span>' ); ?></span>
		                
		            	<span class="reply-container"><?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></span>
		                <span class="reply-icon"></span>
					</div><!-- .comment-action -->

           		</div><!-- meta comment -->
			</header>

			
		</article><!-- #comment-## -->
	</li>
	<?php
			break;
	endswitch;
}
endif;