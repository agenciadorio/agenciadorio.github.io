<article  id="post-<?php the_ID(); ?>" <?php post_class( 'blog-item wow fadeIn'); ?>>

	<?php if ( has_post_thumbnail()) { ?>
		<div class="post-thumb">
			<a href="<?php the_permalink(); ?>">
				
			</a>
		</div><!-- thumbnail-->
	<?php } ?> 

	<div class="post-content-wrap">
		<div class="post-content">

			<ul class="post-meta clearfix">
				
				<li><?php the_time( get_option( 'date_format' ) ); ?></li>
				<li><?php esc_html_e( 'Categories:', 'finance' ); ?> <?php the_category(', '); ?></li>
			</ul>

			<a href="<?php the_permalink(); ?>"><h2 class="post-title"><?php the_title(); ?></h2></a>

			<div class="post-text">
				<p style="text-align: left;"><?php the_post_thumbnail(); ?></p><?php the_content(); ?>
				<?php wp_link_pages(); ?>
				<div class="meta-bottom">
					<div class="tag-wrapper">
						<i class="icon-finance-tags"></i>
						<?php the_tags('',', ',''); ?>
					</div>
				</div>
			</div>

			<div class="author-wrapper clearfix">

				<figure class="author-ava">
					<?php  echo get_avatar( get_the_author_meta('ID'), '100' );   ?>
				</figure>

				<div class="author-context">
					<div class="author-name">
						<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" title="<?php echo get_the_author_meta( 'display_name' ); ?>" rel="author"><?php echo get_the_author_meta( 'display_name' ); ?></a>
					</div><!-- end auth-name -->

					<div class="author-content">
						<p><?php the_author_meta('description'); ?></p>
					</div>
					
					<?php
					if( class_exists('acf') ) {
					$finance_author_id 			= get_the_author_meta('ID');

					$finance_user_facebook		= get_field('user_facebook', 'user_'. $finance_author_id);
					$finance_user_twitter		= get_field('user_twitter', 'user_'. $finance_author_id);
					$finance_user_youtube		= get_field('user_youtube', 'user_'. $finance_author_id);
					$finance_user_linkedin		= get_field('user_linkedin', 'user_'. $finance_author_id);
					$finance_user_pinterest		= get_field('user_pinterest', 'user_'. $finance_author_id);
					$finance_user_google_plus	= get_field('user_google_plus', 'user_'. $finance_author_id);
					$finance_user_dribbble		= get_field('user_dribbble', 'user_'. $finance_author_id);
					$finance_user_instagram		= get_field('user_instagram', 'user_'. $finance_author_id);
					?>
					<ul class="user-social">
						<?php if(!empty($finance_user_facebook)){ ?>
							<li><a href="<?php echo esc_url( $finance_user_facebook ); ?>" target="_blank"><i class="icon-facebook"></i></a></li>
						<?php }
						if(!empty($finance_user_twitter)){ ?>
							<li><a href="<?php echo esc_url( $finance_user_twitter ); ?>" target="_blank"><i class="icon-twitter"></i></a></li>
						<?php }
						if(!empty($finance_user_youtube)){ ?>
							<li><a href="<?php echo esc_url( $finance_user_youtube ); ?>" target="_blank"><i class="icon-youtube"></i></a></li>
						<?php }
						if(!empty($finance_user_linkedin)){ ?>
							<li><a href="<?php echo esc_url( $finance_user_linkedin ); ?>" target="_blank"><i class="icon-linkedin"></i></a></li>
						<?php }
						if(!empty($finance_user_pinterest)){ ?>
							<li><a href="<?php echo esc_url( $finance_user_pinterest ); ?>" target="_blank"><i class="icon-pinterest"></i></a></li>
						<?php }
						if(!empty($finance_user_google_plus)){ ?>
							<li><a href="<?php echo esc_url( $finance_user_google_plus ); ?>" target="_blank"><i class="icon-google"></i></a></li>
						<?php }
						if(!empty($finance_user_dribbble)){ ?>
							<li><a href="<?php echo esc_url( $finance_user_dribbble ); ?>" target="_blank"><i class="icon-dribbble"></i></a></li>
						<?php }
						if(!empty($finance_user_instagram)){ ?>
							<li><a href="<?php echo esc_url( $finance_user_instagram ); ?>" target="_blank"><i class="icon-instagram"></i></a></li>
						<?php } ?>
					</ul>
					<?php } ?>
				</div><!-- end author-wrapper -->

			</div><!-- end author-wrapper -->

		</div>
	</div>

</article><!-- #post-<?php the_ID(); ?> -->

<?php 
	finance_comment_reply(); 
	if ( comments_open() || '0' != get_comments_number() ) comments_template();
?>