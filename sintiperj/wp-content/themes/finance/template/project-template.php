<?php get_header();

/*
Template Name: Case Template
*/

//if( class_exists('acf') ) { 

?>

<?php finance_page_title(); ?>

<?php
	//$project_per_page       = get_field('project_per_page'); 
?>

<div id="content" class="project-page clearfix">
	<div class="container">
		<div class="row">

		<?php $finance_project_page_args = array(
			'post_type'		=> 'finance-project',
			/*'posts_per_page' => $project_per_page,*/
		);
		
		$finance_project_page_loop = new WP_Query($finance_project_page_args);
		if ($finance_project_page_loop->have_posts()) : while($finance_project_page_loop->have_posts()) : $finance_project_page_loop->the_post(); ?>

		<div class="project-item col-md-4">
			<div class="project-post-wrap">
				<?php if ( has_post_thumbnail()) { ?>
					<div class="post-thumb">
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail('finance-project-loop'); ?>
						</a>
					</div><!-- thumbnail-->
				<?php } ?>

				<div class="loop-content">
					<a href="<?php the_permalink(); ?>"><h4 class="title"><?php the_title(); ?></h4></a>

					<p class="excerpt"><?php echo finance_excerpt(20); ?></p>
				</div>
				<div class="view-more">
					<a href="<?php the_permalink(); ?>"><?php esc_html_e( 'Learn More', 'finance' ); ?></a>
				</div>
			</div>
		</div>

		<?php endwhile; wp_reset_postdata(); endif; ?>
			
		</div>
	</div>
</div>

<?php //} ?>

<?php get_footer(); ?>