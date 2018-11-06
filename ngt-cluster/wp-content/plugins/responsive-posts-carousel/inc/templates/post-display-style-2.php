<div class="rpc-post-carousel3 rpc-box">
	<div class="rpc-post-image">
		<a href="<?php the_permalink(); ?>" target="<?php echo $carousel_settings['read_more_target']; ?>">
			<?php do_action( 'rpc_carousel_thumbnail', $post_id, $carousel_settings ); ?>
		</a>
	</div>

	<div class="rpc-desc-box rpc-bg">
		<div style="display: table; margin: auto;">
			<div class="rpc-post-category">
			    
			    <div class="rpc-post-para rpc-content"><h3> <b> 
			<?php do_action( 'rpc_carousel_desc', $post_id, $carousel_settings); ?></big> </b> </big> </h3> 
            
		</div>
				<?php $categories = get_the_category();
					$separator = ' , ';
					$output = '';
					if ( ! empty( $categories[0] ) ) {
					        $output .= '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>' . $separator;
					    echo trim( $output, $separator );
					}
				?>
			</div>
		</div>
		<h3 class="rpc-post-title">
			<a href="<?php the_permalink(); ?>" target="<?php echo $carousel_settings['read_more_target']; ?>" class="rpc-title">
				<?php do_action( 'rpc_carousel_title', $post_id,  $carousel_settings ); ?>
			</a>
		</h3>
		<div class="clearfix"></div>
		
		<?php if (!isset($carousel_settings['hidemeta'])) { ?>
		<span class="rpc-post-meta wcp-disable-post-meta">
			<i class="fa fa-user"></i>
			<?php the_author_posts_link(); ?>
		</span>
		<span class="rpc-comment-box wcp-disable-post-meta">
			<span class="rpc-post-comment">
				<a href="<?php the_permalink(); ?>">
					<i class="fa fa-comment"></i>
					<?php
						$comments = wp_count_comments(get_the_id());
						echo $comments->total_comments;
					?>
				</a>
			</span>					
		</span>
		<?php } ?>
		<div class="clearfix"></div>
	</div>
</div>