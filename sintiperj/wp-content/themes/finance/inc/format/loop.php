<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-item col-md-4'); ?>>

	<div class="post-content-wrap">
	<?php if ( has_post_thumbnail()) { ?>
		<div class="post-thumb">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail(); ?>
			</a>
		</div><!-- thumbnail-->
	<?php } ?> 

		<div class="post-content">
			<a href="<?php the_permalink(); ?>"><h4 class="post-title"><?php the_title(); ?></h4></a>

				<div class="post-meta clearfix">
					<div class="post-author">
						<div class="author-img">
							<?php  echo get_avatar( get_the_author_meta('ID'), '45' ); ?>
						</div>
						<div class="author-name">
							<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" title="<?php echo get_the_author_meta( 'display_name' ); ?>" rel="author"><?php echo get_the_author_meta( 'display_name' ); ?></a>
						</div>
					</div>
					
					<div class="post-date">
						<a href="<?php the_permalink(); ?>"><?php the_time( get_option( 'date_format' ) ); ?></a>
					</div>
				</div>
			

			<div class="post-text excerpt">
				<?php the_excerpt(); ?>
			</div>
			<a href="<?php the_permalink(); ?>" class="button button-normal"><?php esc_html_e( 'Read More', 'finance' ); ?></a>
		</div>
	</div>
</article>