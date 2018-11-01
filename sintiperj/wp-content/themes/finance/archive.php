<?php get_header(); ?>

	<!-- CONTENT START
	============================================= -->
	<div id="content" class="clearfix">

		<!-- BLOG START
		============================================= -->
		<div class="blog right-sidebar wrapper clearfix">
	        <div class="container">
	            <div class="row">

					<!-- BLOG LOOP START
					============================================= -->
                    <div class="blog-section grid">

					<?php if ( have_posts() ) : ?>
						<?php while ( have_posts() ) : the_post(); 

							get_template_part( 'inc/format/loop', get_post_format() );

						endwhile; ?>
						
					<?php else : ?>

					<?php get_template_part( 'inc/format/content', 'no-result' ); ?>

					<?php endif; ?>
				
					</div>

					<?php finance_pagination($pages = '', $range = 2); ?>
					<!-- BLOG LOOP END -->

				</div>
			</div>
		</div>
		<!-- BLOOG END -->

	</div>
	<!-- CONTENT END -->

<?php get_footer(); ?>