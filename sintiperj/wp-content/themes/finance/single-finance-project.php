<?php get_header();

while ( have_posts() ) : the_post();
?>

<?php finance_page_title(); ?>

<!-- CONTENT START
============================================= -->
<div id="content" class="clearfix">

	<div class="single-project wrapper clearfix">
		<div class="container">
			<div class="row">

				<div class="post-content single-content">
					
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
						
						<div class="content-bottom clearfix">
							<div class="faq-project">
								<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
								<?php  
								$ids = get_field('faq_project', false, false);

								$finance_faq_project_args = array(
									'post_type'			=> 'finance-faq',
									'post__in'			=> $ids,
									'post_status'		=> 'any',
									'orderby'        	=> 'rand',
								);
								
								$finance_faq_project_loop = new WP_Query($finance_faq_project_args);
								if ($finance_faq_project_loop->have_posts()) : while($finance_faq_project_loop->have_posts()) : $finance_faq_project_loop->the_post(); ?>
								
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
								jQuery('.faq-project .panel-default:first-child').each(function() {
									jQuery(this).find( '.panel-collapse' ).addClass( 'in' );
								});
								</script>
								
								</div>
							</div><!-- faq end -->

							<!-- team for project -->
							<div class="team-project">
								<div class="team-slider wow fadeIn" data-wow-duration="2s" data-wow-delay="0.3s">
									<div class="flexslider clearfix">
										<ul class="slides">

										<?php $finance_team_home_args = array(
											'post_type'		=> 'finance-team',
											//'posts_per_page' => $team_per_page,
										);
										
										$finance_team_home_loop = new WP_Query($finance_team_home_args);
										if ($finance_team_home_loop->have_posts()) : while($finance_team_home_loop->have_posts()) : $finance_team_home_loop->the_post(); 

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
						</div>
					</div> 

				</div>

				<!-- SIDEBAR START
				============================================= -->
				
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
				<h4 class="slogan-title"><?php echo sanitize_text_field( $finance_slogan_title ); ?></h4>
			</div>

			<div class="slogan-button col-md-3">
				<a href="<?php echo esc_url( $finance_slogan_link ); ?>"><?php echo sanitize_text_field( $finance_slogan_button ); ?></a>
			</div>
		</div>
	</div>
</div>
<?php } ?>
<!-- Slogan Section end -->

<?php
endwhile; // end of the loop. 
get_footer(); ?>