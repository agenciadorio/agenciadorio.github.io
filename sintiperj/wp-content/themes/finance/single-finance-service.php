<?php get_header();

while ( have_posts() ) : the_post();
?>

<?php finance_page_title(); ?>

<!-- CONTENT START
============================================= -->
<div id="content" class="clearfix">

	<div class="single-service wrapper clearfix">
		<div class="container">
			<div class="row">

				<div class="col-md-9 post-content single-content">
					
					<?php if ( has_post_thumbnail()) { ?>
						<div class="post-thumb">
							<?php the_post_thumbnail(); ?>
						</div><!-- thumbnail-->
					<?php } ?>

					<div class="post-content">
						<h2 class="post-title">
							<?php the_title(); ?>
						</h2>

						<div class="inner-content">
							<?php the_content(); ?>
						</div>
						
						<div class="faq-service clearfix">
							<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
							<?php  
							$ids = get_field('faq_service', false, false);

							$finance_faq_service_args = array(
								'post_type'			=> 'finance-faq',
								'post__in'			=> $ids,
								'post_status'		=> 'any',
								'orderby'        	=> 'rand',
							);
							
							$finance_faq_service_loop = new WP_Query($finance_faq_service_args);
							if ($finance_faq_service_loop->have_posts()) : while($finance_faq_service_loop->have_posts()) : $finance_faq_service_loop->the_post(); ?>
							
							<div class="panel panel-default">

								<a data-toggle="collapse" data-parent="#accordion" href="#collapse-<?php the_ID(); ?>" aria-expanded="true" aria-controls="collapse-<?php the_ID(); ?>">
									<div class="panel-heading" role="tab" id="heading-<?php the_ID(); ?>">
										<h4 class="panel-title"><?php the_title(); ?></h4>
									</div>
								</a>

								<div id="collapse-<?php the_ID(); ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-<?php the_ID(); ?>">
									<div class="panel-body">
										<?php the_content(); ?>
									</div>
								</div>
							
							</div>

							<?php endwhile; wp_reset_postdata(); endif; ?>

							<script type="text/javascript">
							jQuery('.faq-service .panel-default:first-child').each(function() {
								jQuery(this).find( '.panel-collapse' ).addClass( 'in' );
							});
							</script>
							
							</div>
						</div><!-- faq end -->
					</div> 

				</div>

				<!-- SIDEBAR START
				============================================= -->
				<div class="blog-sidebar col-md-3">
					<?php 
					if ( is_active_sidebar( 'service-sidebar' ) ) { 
					dynamic_sidebar( 'service-sidebar' ); 
					} ?>

					<!-- team for service -->
					<?php $ids2 = get_field('team_involved', false, false);

					if($ids2 == !NULL){  ?>
					<div class="team-service">
						<div class="team-slider wow fadeIn" data-wow-duration="2s" data-wow-delay="0.3s">
							<div class="flexslider clearfix">
								<ul class="slides">

								<?php $finance_team_side_args = array(
									'post_type'		=> 'finance-team',
									'post__in'			=> $ids2,
								);
								
								$finance_team_side_loop = new WP_Query($finance_team_side_args);
								if ($finance_team_side_loop->have_posts()) : while($finance_team_side_loop->have_posts()) : $finance_team_side_loop->the_post(); 

								$finance_team_function = get_field('team_function'); ?>

								<li>
									<div class="team-content">
										
										<div class="team-author-img">
											<?php if ( has_post_thumbnail()) { 
												the_post_thumbnail('finance-team-project');
											} ?> 
										</div>

										<div class="team-text">
											<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
											<?php if(!empty($finance_team_function)) { ?>
												<span class="team-job"><?php echo sanitize_text_field( $finance_team_function ); ?></span>
											<?php } ?>
										</div>
									</div>
								</li>

								<?php endwhile; wp_reset_postdata(); endif; ?>
								</ul>
							</div>
						</div>

						<script type="text/javascript">
						jQuery(window).load(function() { 
							jQuery('.team-slider .flexslider').flexslider({
								animation: "fade",
								slideshow: true,
								directionNav: true,
								controlNav: false,  
								touch: true
							});
						});
						</script>
					</div>
					<?php } ?>
					<!-- team for service -->

					<?php 
						if(have_rows('download_file')): ?>
						<div class="widget pdf-download">
						<div class="heading-block"><h4><?php esc_html_e( 'Our Brochures', 'finance' ); ?></h4></div>
							<ul>

							<?php while(have_rows('download_file')):the_row(); 
								$finance_file_type	= get_sub_field('file_type');
								$finance_file_name	= get_sub_field('file_name');
								$finance_file_item	= get_sub_field('file_item');
							
								if( $finance_file_item ):  ?>

								<li><?php if(!empty($finance_file_type)) { ?>
									<img src="<?php echo esc_url( $finance_file_type ); ?>" alt="<?php echo sanitize_text_field( $finance_file_name ); ?>">
								<?php } ?>
									<a href="<?php echo esc_url( $finance_file_item ); ?>" download><?php echo sanitize_text_field( $finance_file_name ); ?></a>
								</li>

							<?php endif; ?>
							<?php endwhile; ?>

							</ul>
						</div>
					<?php endif; ?>

				</div>
				<!-- SIDEBAR END -->

			</div>
		</div>
	</div>

</div>
<!-- CONTENT END -->

<!-- Slogan Section -->
<?php
	$finance_slogan_background		= get_field('slogan_background'); 
	$finance_slogan_title			= get_field('slogan_title');
	$finance_slogan_padding			= get_field('slogan_padding');
	$finance_slogan_button			= get_field('slogan_button');
	$finance_slogan_link			= get_field('slogan_link');
if(!empty($finance_slogan_title)) { ?>
<div class="slogan-section clearfix" style="<?php if(!empty($finance_slogan_padding)) { echo 'padding:'. sanitize_text_field( $finance_slogan_padding ) .'px 0;'; } ?> <?php if(!empty($finance_slogan_background)) { echo 'background-color:'. sanitize_text_field( $finance_slogan_background ) .';'; } ?>">
	<div class="container">
		<div class="row">
			<div class="col-md-9">
				<h3 class="slogan-title"><?php echo sanitize_text_field( $finance_slogan_title ); ?></h3>
			</div>

			<div class="slogan-button col-md-3">
				<a class="button button-normal" href="<?php echo esc_url( $finance_slogan_link ); ?>"><?php echo sanitize_text_field( $finance_slogan_button ); ?></a>
			</div>
		</div>
	</div>
</div>
<?php } ?>
<!-- Slogan Section end -->

<?php
endwhile; // end of the loop. 
get_footer(); ?>