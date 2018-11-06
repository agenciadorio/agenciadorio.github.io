<div class="rpc-post-carousel4 rpc-box rpc-bg style6">
	<div class="rpc-post-image">
		<a href="<?php the_permalink(); ?>" target="<?php echo $carousel_settings['read_more_target']; ?>">
			<?php do_action( 'rpc_carousel_thumbnail', $post_id, $carousel_settings ); ?>
		</a>
	</div>

	<div class="rpc-post-category">
		<?php $categories = get_the_category();
			$separator = ' , ';
			$output = '';
			if ( ! empty( $categories[0] ) ) {
			        $output .= '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>' . $separator;
			    echo trim( $output, $separator );
			}
		?>
	</div>
	<span class="rpc-post-date rpc-date">
		<i class="fa fa-clock-o"></i>
		<?php echo get_the_date(); ?>
	</span>
	<h3 class="rpc-post-title">
		<a href="<?php the_permalink(); ?>" class="rpc-title" target="<?php echo $carousel_settings['read_more_target']; ?>">
			<?php do_action( 'rpc_carousel_title', $post_id,  $carousel_settings ); ?>
		</a>
	</h3>
	<div class="clearfix"></div>
	<div class="rpc-post-para rpc-content">
        <?php do_action( 'rpc_carousel_desc', $post_id, $carousel_settings); ?>
        <?php do_action( 'rpc_read_more_btn', $post_id, $carousel_settings); ?>
	</div>
	<?php if (!isset($carousel_settings['hidemeta'])) { ?>
	<span class="rpc-post-meta wcp-disable-post-meta">
		<i class="fa fa-user"></i>
		<?php the_author_posts_link(); ?>
	</span>
	<span class="rpc-comment-box wcp-disable-post-meta">
		<span class="rpc-post-comment">
			<i class="fa fa-comment"></i>
			<?php
				$comments = wp_count_comments(get_the_id());
				echo $comments->total_comments;
			?>			
		</span>					
	</span>
	<?php } ?>
	<div class="clearfix"></div>
</div>