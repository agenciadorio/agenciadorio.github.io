<?php get_header();  ?>
	

	<?php finance_blog_title(); ?>
	
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

					<?php while ( have_posts() ) : the_post(); 
			
						get_template_part( 'inc/format/loop', get_post_format() );

					endwhile; // end of the loop. ?>
				
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