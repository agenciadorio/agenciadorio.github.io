<?php get_header();

/*
Template Name: Service Template
*/

if( class_exists('acf') ) { 

?>

<?php finance_page_title(); ?>

<?php
	$finance_service_per_page       = get_field('service_per_page'); 
?>

<div id="content" class="service-page clearfix">
	<div class="container">
		<div class="row">

		<?php $finance_service_page_args = array(
			'post_type'		=> 'finance-service',
			'posts_per_page' => $finance_service_per_page,
		);
		
		$finance_service_page_loop = new WP_Query($finance_service_page_args);
		if ($finance_service_page_loop->have_posts()) : while($finance_service_page_loop->have_posts()) : $finance_service_page_loop->the_post(); ?>

		<div class="service-item col-md-4">
			<div class="service-post-wrap">
				<?php if ( has_post_thumbnail()) { ?>
					<div class="post-thumb">
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail('finance-service-loop'); ?>
						</a>
					</div><!-- thumbnail-->
				<?php } ?>

				<div class="loop-content">
					<a href="<?php the_permalink(); ?>"><h4 class="title"><?php the_title(); ?></h4></a>

					<p class="excerpt"><?php echo finance_excerpt(20); ?></p>
				</div>
				<div class="view-more">
					<i class="icon-finance-arrow-right"></i><a href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read More', 'finance' ); ?></a>
				</div>
			</div>
		</div>

		<?php endwhile; wp_reset_postdata(); endif; ?>
			
		</div>
	</div>
</div>

<?php } ?>

<?php get_footer(); ?>