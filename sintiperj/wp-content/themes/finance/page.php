<?php get_header(); ?>

<?php finance_page_title(); ?>

<!-- CONTENT START
============================================= -->
<div id="content" class="single-wrapper">
	
	<div class="content-wrapper">
	
		<?php while ( have_posts() ) : the_post(); 
		
			get_template_part( 'inc/format/content', 'page' );
					
		endwhile; // end of the loop. ?>

	</div>
</div>
<!-- CONTENT END -->


<?php get_footer(); ?>