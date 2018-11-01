<?php get_header();

/*
Template Name: About Template
*/

if( class_exists('acf') ) { 

?>

<?php finance_page_title(); ?>

<!-- Start Content -->
<div class="clearfix">

	<!-- About Section -->
	<?php 
	$finance_use_about_section		= get_field('use_about_section');
	$finance_about_section_padding	= get_field('about_section_padding');
	$finance_about_title			= get_field('about_title');
	$finance_about_text				= get_field('about_text');
	$finance_about_button			= get_field('about_button');
	$finance_about_link				= get_field('about_link');
	?>

	<?php if($finance_use_about_section == true ) { ?>
	<div class="about-section clearfix" <?php if(!empty($finance_about_section_padding)) { echo 'style="padding:'. sanitize_text_field( $finance_about_section_padding ) .'px 0;"'; } ?>>
		<div class="container">
			<div class="row">
				
				<?php if(have_rows('about_images')): ?>
				<div class="about-images col-md-6">
					<div class="about-image-wrap row clearfix">
					<?php while(have_rows('about_images')):the_row();
						$about_image	= get_sub_field('about_image');
						$about_img_res = aq_resize($about_image,  285 , 285, true);
					?>
						<div class="about-image col-md-6">
							<img src="<?php echo esc_url( $about_img_res ); ?>" alt="<?php esc_html_e( 'about-img', 'finance' ); ?>" />
						</div>
					<?php endwhile; ?>
					</div>
				</div>
				<?php endif; ?>

				<div class="about-desc col-md-6">
					<?php if(!empty($finance_about_title)) { ?>
						<h2 class="section-title">
							<?php echo sanitize_text_field( $finance_about_title ); ?>
						</h2>
					<?php } 

					if(!empty($finance_about_text)) { ?>
						<div class="about-text">
							<?php echo balancetags( $finance_about_text ); ?>
						</div>
					<?php } 

					if(!empty($finance_about_link) || !empty($finance_about_button)) { ?>
					<div class="about-button">
						<a href="<?php echo esc_url( $finance_about_link ); ?>" class="button button-normal">
							<?php echo sanitize_text_field( $finance_about_button ); ?>
						</a>
					</div>
					<?php } ?>
				</div>

			</div>
		</div>
	</div>
	<?php } ?>
	<!-- About Section end -->

	<!-- Team Section -->
	<?php 
	$finance_allow_team				= get_field('allow_team');
	$finance_team_section_padding	= get_field('team_section_padding');
	$finance_team_section_title		= get_field('team_section_title');
	$finance_team_per_page			= get_field('team_per_page');
	?>

	<?php if($finance_allow_team == true){ ?>
	<div class="team-section clearfix" style="<?php if(!empty($finance_team_section_padding)) { echo 'padding:'. sanitize_text_field( $finance_team_section_padding ) .'px 0;'; } ?>">
		<div class="container">
			<div class="row">

				<?php if(!empty($finance_team_section_title)) { ?>
					<h2 class="section-title">
						<?php echo sanitize_text_field( $finance_team_section_title ); ?>
					</h2>
				<?php } ?>

				<?php $finance_team_about_args = array(
				'post_type'			=> 'finance-team',
				'posts_per_page'	=> $finance_team_per_page,
				);
				
				$finance_team_about_loop = new WP_Query($finance_team_about_args);
				if ($finance_team_about_loop->have_posts()) : while($finance_team_about_loop->have_posts()) : $finance_team_about_loop->the_post();
				
				$finance_team_function		= get_field('team_function');
				$finance_team_facebook		= get_field('facebook');
				$finance_team_twitter		= get_field('twitter');
				$finance_team_dribble		= get_field('dribble');
				$finance_team_instagram		= get_field('instagram');
				$finance_team_behance		= get_field('behance');
				$finance_team_google_plus	= get_field('google_plus');
				?>

				<div class="team-member col-md-4">
					
					<div class="team-img">
						<?php the_post_thumbnail(); ?>

						<div class="overlay">
							<div class="team-detail">
								<div class="team-desc">
									<?php echo finance_excerpt(15); ?>

									<ul class="team-social clearfix">
										<?php if(!empty($finance_team_facebook)){ ?>
											<li><a href="<?php echo esc_url( $finance_team_facebook ); ?>" target="_blank"><i class="icon-facebook"></i></a></li>
										<?php }
										if(!empty($finance_team_twitter)){ ?>
											<li><a href="<?php echo esc_url( $finance_team_twitter ); ?>" target="_blank"><i class="icon-twitter"></i></a></li>
										<?php }
										if(!empty($finance_team_dribble)){ ?>
											<li><a href="<?php echo esc_url( $finance_team_dribble ); ?>" target="_blank"><i class="icon-dribbble"></i></a></li>
										<?php }
										if(!empty($finance_team_instagram)){ ?>
											<li><a href="<?php echo esc_url( $finance_team_instagram ); ?>" target="_blank"><i class="icon-instagram"></i></a></li>
										<?php }
										if(!empty($finance_team_behance)){ ?>
											<li><a href="<?php echo esc_url( $finance_team_behance ); ?>" target="_blank"><i class="icon-behance"></i></a></li>
										<?php }
										if(!empty($finance_team_google_plus)){ ?>
											<li><a href="<?php echo esc_url( $finance_team_google_plus ); ?>" target="_blank"><i class="icon-google-plus"></i></a></li>
										<?php } ?>
									</ul>
								</div>
							</div>
						</div>
					</div>

					<div class="team-name">
						<h4 class="name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
						<?php if(!empty($finance_team_function)){ ?>
							<p class="job"><?php echo sanitize_text_field( $finance_team_function ); ?></p>
						<?php } ?>
					</div>
					
				</div>

				<?php endwhile; wp_reset_postdata(); endif; ?>

			</div>
		</div>
	</div>
	<?php } ?>
	<!-- Team Section end -->

	<!-- Review Section -->
	<?php 
	$finance_allow_about_review_section			= get_field('allow_about_review_section');
	$finance_review_about_section_padding		= get_field('review_about_section_padding');
	$finance_review_about_section_background	= get_field('review_about_section_background');
	$finance_review_about_section_title			= get_field('review_about_section_title');
	$finance_review_about_section_text			= get_field('review_about_section_text');
	$finance_about_the_counter					= get_field('about_the_counter');
	$finance_about_testimonial_per_page			= get_field('about_testimonial_per_page');
	?>

	<?php if($finance_allow_about_review_section == true){ ?>
	<div class="review-section clearfix" style="<?php if(!empty($finance_review_about_section_padding)) { echo 'padding:'. sanitize_text_field( $finance_review_about_section_padding ) .'px 0;'; } ?> <?php if(!empty($finance_review_about_section_background)) { echo 'background-color:'. sanitize_text_field( $finance_review_about_section_background ) .';'; } ?>">
		<div class="container">
			<div class="row">
				
				<div class="counter-review col-md-6">
					<?php if(!empty($finance_review_about_section_title)) { ?>
						<h2 class="section-title white">
							<?php echo sanitize_text_field( $finance_review_about_section_title ); ?>
						</h2>
					<?php } ?>

					<?php if(!empty($finance_review_about_section_text)) { ?>
					<div class="review-text">
						<?php echo balancetags( $finance_review_about_section_text ); ?>
					</div>
					<?php } ?>
					
					<!-- the-counter -->
					<?php if(have_rows('about_the_counter')): ?>
					<div class="the-counter row clearfix">
						<?php while(have_rows('about_the_counter')):the_row(); 
							$finance_counter_picture	= get_sub_field('counter_picture');
							$finance_counter_icon		= get_sub_field('counter_icon');
							$finance_counter_image		= get_sub_field('counter_image');
							$finance_counter_value		= get_sub_field('counter_value');
							$finance_counter_title		= get_sub_field('counter_title');
						?>
						<div class="counter-item col-md-6">

							<div class="counter-pic">
								<?php
								if($finance_counter_picture == 'icon') {
									echo '<i class="icon-finance-'. sanitize_text_field( $finance_counter_icon ) .'"></i>' ;
								}
								else {
									echo '<img src="'. esc_url( $finance_counter_image ) .'" alt="">' ;
								} ?>
							</div>

							<div class="counter-text">
								<?php if(!empty($finance_counter_value)) { ?>
									<p class="counter-value">
										<?php echo sanitize_text_field( $finance_counter_value ); ?>
									</p>
								<?php }
								if(!empty($finance_counter_title)) { ?>
									<p class="counter-title">
										<?php echo sanitize_text_field( $finance_counter_title ); ?>
									</p>
								<?php } ?>
							</div>
							
						</div>
						<?php endwhile; ?>
					</div>
					<script type="text/javascript">
					jQuery(document).ready(function() { 
						jQuery('.counter-value').counterUp({
							delay: 20,
							time: 3000
						});
					});
					</script>
					<?php endif; ?>
					<!-- the-counter end -->

				</div>

				<div class="the-testimonial col-md-6">

					<div class="testimonial-slider wow fadeIn" data-wow-duration="2s" data-wow-delay="0.3s">
						<div class="flexslider clearfix">
							<ul class="slides">

							<?php $finance_testimonial_about_args = array(
								'post_type'		=> 'finance-testimonial',
								'posts_per_page' => $finance_about_testimonial_per_page,
							);
							
							$finance_testimonial_about_loop = new WP_Query($finance_testimonial_about_args);
							if ($finance_testimonial_about_loop->have_posts()) : while($finance_testimonial_about_loop->have_posts()) : $finance_testimonial_about_loop->the_post(); 

							$finance_testimonial_author_job = get_field('testimonial_author_job'); ?>

							<li>
								<div class="testimonial-content">
									
									<div class="testi-author-img">
										<?php if ( has_post_thumbnail()) { 
											the_post_thumbnail();
										} ?> 
									</div>

									<div class="testimonial-text">
										<i class="icon-finance-quote"></i>
										<?php the_content(); ?>

										<h4><?php the_title(); ?></h4>
										<?php if(!empty($finance_testimonial_author_job)) { ?>
											<span class="testi-job"><?php echo sanitize_text_field( $finance_testimonial_author_job ); ?></span>
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
						jQuery('.testimonial-slider .flexslider').flexslider({
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
	<?php } ?>
	<!-- Review Section end -->
	
	<!-- FAQ Section -->
	<?php 
	$finance_allow_faq				= get_field('allow_faq');
	$finance_faq_section_padding	= get_field('faq_section_padding'); 
	$finance_faq_section_title		= get_field('faq_section_title');
	$finance_faq_per_page			= get_field('faq_per_page');
	?>

	<?php if($finance_allow_faq == true){ ?>
	<div class="faq-section clearfix" style="<?php if(!empty($finance_faq_section_padding)) { echo 'padding:'. sanitize_text_field( $finance_faq_section_padding ) .'px 0;'; } ?>">
		<div class="container">

			<?php if(!empty($finance_faq_section_title)) { ?>
				<h2 class="section-title">
					<?php echo sanitize_text_field( $finance_faq_section_title ); ?>
				</h2>
			<?php } ?>

			<div class="row">
				<div class="the-faq col-md-6">
					<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
					<?php  

					$finance_faq_bout_args = array(
						'post_type'         => 'finance-faq',
						'posts_per_page'    => $finance_faq_per_page,
					);
					
					$finance_faq_bout_loop = new WP_Query($finance_faq_bout_args);
					if ($finance_faq_bout_loop->have_posts()) : while($finance_faq_bout_loop->have_posts()) : $finance_faq_bout_loop->the_post(); ?>
					
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
					jQuery('.faq-section .panel-default:first-child').each(function() {
						jQuery(this).find( '.panel-collapse' ).addClass( 'in' );
					});
					</script>
					
					</div>
				</div><!-- faq end -->

				<div class="faq-gallery col-md-6">
					<?php $finance_faq_image = get_field('faq_image');
						if( $finance_faq_image){ ?>

							<div class="flex-wrapper">
								<div id="slider" class="flexslider">
									<div class="slides">
										<?php foreach( $finance_faq_image as $faq_image ): ?>
										<div>
											<img src="<?php echo esc_url( $faq_image['url'] ); ?>" alt="<?php echo esc_attr( $faq_image['alt'] ); ?>" />  
										</div>
										<?php endforeach; ?>
									</div>
								</div>
							</div>

					<?php } ?>

					<script type="text/javascript">
					jQuery(document).ready(function() {
						jQuery('.flex-wrapper .flexslider').flexslider( {
							slideshow : false,
							selector: ".slides > div", 
							animation : 'fade',
							directionNav: true,
							controlNav: false,
						});
					});
					</script>
				</div>
			</div>

		</div>
	</div>
	<?php } ?>
	<!-- FAQ Section end -->

	<!-- Form Section -->
	<?php 
	$finance_form_section_padding       = get_field('form_section_padding');
	$finance_form_section_background    = get_field('form_section_background');
	$finance_form_html_content          = get_field('form_html_content');
	$finance_about_form                 = get_field('about_form');
	?>
	<div class="form-section clearfix" style="<?php if(!empty($finance_form_section_padding)) { echo 'padding:'. sanitize_text_field( $finance_form_section_padding ) .'px 0;'; } ?> <?php if(!empty($finance_form_section_background)) { echo 'background-color:'. sanitize_text_field( $finance_form_section_background ) .';'; } ?>">
		<div class="container">
			<div class="row">
				
				<div class="form-html vertical-center col-md-6">
					<?php echo balancetags( $finance_form_html_content ); ?>
				</div>

				<div class="the-form col-md-6">
					<?php echo do_shortcode('[contact-form-7 id="' . $finance_about_form . '"]');  ?>
				</div>

			</div>
		</div>
	</div>
	<!-- Form Section end -->
	
	<!-- Client Section -->
	<?php 
	$finance_client_about_section_padding		= get_field('client_about_section_padding');
	$finance_client_about_section_background	= get_field('client_about_section_background');
	$finance_client_item_column					= get_field('client_item_column');
	?>
	<?php if(have_rows('the_clients')): ?>
	<div class="client-section clearfix" style="<?php if(!empty($finance_client_about_section_padding)) { echo 'padding:'. sanitize_text_field( $finance_client_about_section_padding ) .'px 0;'; } ?> <?php if(!empty($finance_client_about_section_background)) { echo 'background-color:'. sanitize_text_field( $finance_client_about_section_background ) .';'; } ?>">
		<div class="container">
			<div class="row">
			
				<div class="the-clients-about">
				<?php while(have_rows('the_clients')):the_row(); 
					$finance_client_name	= get_sub_field('client_name');
					$finance_client_url		= get_sub_field('client_url');
					$finance_client_image	= get_sub_field('client_image');
				?>

					<div class="client-item">
						<a href="<?php echo esc_url( $finance_client_url ); ?>">
							<img src="<?php echo esc_url( $finance_client_image ); ?>" alt="<?php echo sanitize_text_field( $finance_client_name ); ?>">
						</a>
					</div>

				<?php endwhile; ?>
				</div>
					
			</div>
		</div>
		<script>
		jQuery(document).ready(function() { 
			jQuery('.the-clients-about').owlCarousel({
				loop:true,
				nav:false,
				dots:false,
				responsive:{
					0:{
						items:1
					},
					600:{
						items:2
					},
					1000:{
						items:4
					},
					1200:{
						items:<?php echo sanitize_text_field( $finance_client_item_column ); ?>
					}
				}
			});
		 });
		</script>
	</div>
	<?php endif; ?>
	<!-- Client Section end -->

</div>

<?php }

else { ?>

<div id="content" class="single-wrapper">
	<!-- Page Title -->
	<div class="wow fadeIn">
		<div class="container">
			<div class="heading-block page-title wow fadeIn">
						<h1>
			<?php esc_html_e( 'Please Activate ACF plugin to use this Page Template', 'finance' ); ?>
		</h1>
			</div>
		</div>
	</div>	

</div>

<?php
}
get_footer(); ?>