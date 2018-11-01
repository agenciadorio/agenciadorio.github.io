<?php get_header();  ?>

<!-- CONTENT START
============================================= -->
<div id="content" full class="clearfix">

	<!-- BLOG START
	============================================= -->
	<div class="blog right-sidebar wrapper clearfix">
		<div class="container">
			<div class="row">

				<!-- BLOG LOOP START
				============================================= -->
			
					<div class="single-content">

					<?php while ( have_posts() ) : the_post(); 
			
						get_template_part( 'inc/format/content', get_post_format() );

					endwhile; // end of the loop. ?>
				
					</div>
				</div>
				<!-- BLOG LOOP END -->

				<!-- SIDEBAR START
				============================================= -->

				
				<!-- SIDEBAR END -->

			</div>
		</div>
	</div>
	<!-- BLOOG END -->

</div>
<!-- CONTENT END -->
		

<?php get_footer(); ?>