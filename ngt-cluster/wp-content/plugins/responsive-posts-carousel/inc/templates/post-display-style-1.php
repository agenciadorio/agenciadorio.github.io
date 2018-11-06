<div class="rpc-post-carousel1 rpc-box style4 rpc-bg">
	<div class="rpc-post-image">
		<a href="<?php echo get_permalink($post_id);; ?>" target="<?php echo $carousel_settings['read_more_target']; ?>">
			<?php do_action( 'rpc_carousel_thumbnail', $post_id, $carousel_settings ); ?>
		</a>

		<span class="rpc-comment-box">
			<span class="rpc-post-comment">
				<?php
					$comments = wp_count_comments(get_the_id());
					echo $comments->total_comments;
				?>
			</span>
		</span>
	</div>

	<div class="rpc-post-category">
	<?php $categories = get_the_category();
		$limit = 1;
		$separator = ' ';
		$output = '';
		if ( ! empty( $categories ) ) {
		    foreach( $categories as $category ) {
		    	if ($limit < 4) {
		        	$output .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a>' . $separator;
		        	$limit++;
		    	}
		    }
		    echo trim( $output, $separator );
		} ?>
	</div>

	<h3 class="rpc-post-title">
		<a href="<?php the_permalink(); ?>" target="<?php echo $carousel_settings['read_more_target']; ?>" class="rpc-title">
			<?php do_action( 'rpc_carousel_title', $post_id,  $carousel_settings ); ?>
		</a>
	</h3>
	<?php if (!isset($carousel_settings['hidemeta'])) { ?>
	<span class="rpc-post-meta wcp-disable-post-meta">
		<i class="fa fa-user"></i>
		<?php the_author_posts_link(); ?>
	</span>
	<span class="rpc-post-date wcp-disable-post-meta rpc-date">
		<i class="fa fa-clock-o"></i>
		<?php echo get_the_date() ?>
	</span>
	<?php } ?>

	<div class="clearfix"></div>
	<div class="rpc-post-para rpc-content">
        <?php do_action( 'rpc_carousel_desc', $post_id, $carousel_settings); ?>
        <?php do_action( 'rpc_read_more_btn', $post_id, $carousel_settings); ?>
	</div>
</div>