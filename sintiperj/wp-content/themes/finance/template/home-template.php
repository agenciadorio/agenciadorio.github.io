<?php get_header();

/*
Template Name: Home Template
*/

if( class_exists('acf') ) { 

?>

<!-- slider section -->
<?php $finance_slider_id = get_field('slider_id'); ?>

<?php if(!empty($finance_slider_id)) { ?>
<div class="slider-home clearfix">
	<?php echo do_shortcode( $finance_slider_id); ?>
</div>
<?php } ?>
<!-- slider section end -->

<!-- Start Content -->
<div id="content" class="no-padding clearfix">

	<!-- Service Section -->
	<?php 
		$finance_service_section_padding	= get_field('service_section_padding');
		$finance_service_section_background	= get_field('service_section_background');
		$finance_service_section_title		= get_field('service_section_title');
		$finance_service_section_text		= get_field('service_section_text');
		$finance_service_section_button		= get_field('service_section_button');
		$finance_service_section_link		= get_field('service_section_link');
		$finance_service_per_page			= get_field('service_per_page');
	?>
	<div class="service-section clearfix" style="<?php if(!empty($finance_service_section_padding)) { echo 'padding:'. sanitize_text_field( $finance_service_section_padding ) .'px 0;'; } ?> <?php if(!empty($finance_service_section_background)) { echo 'background-color:'. sanitize_text_field( $finance_service_section_background ) .';'; } ?>">
		<div class="container">
			<div class="row">

				<?php if(!empty($finance_service_section_title) || !empty($finance_service_section_text) || !empty($finance_service_section_link)){ ?>
				<div class="section-header clearfix">
					<div class="section-detail col-md-8">
						<?php if(!empty($finance_service_section_title)){ ?>
						<h2 class="section-title"><?php echo sanitize_text_field( $finance_service_section_title ); ?></h2>
						<?php }
						if(!empty($finance_service_section_text)){ ?>
						<div class="section-text">
							<?php echo balancetags( $finance_service_section_text ); ?>
						</div>
						<?php } ?>
					</div>
					
					<?php if(!empty($finance_service_section_link)) { ?>
					<div class="section-button col-md-4 text-right vertical-center">
						<a href="<?php echo esc_url( $finance_service_section_link ); ?>" class="button button-normal"><?php echo sanitize_text_field( $finance_service_section_button ); ?></a>
					</div>
					<?php } ?>
				</div>
				<?php } ?>
				
				<?php if($finance_service_per_page != NULL || $finance_service_per_page != '0' ) { ?>
				<div class="service-post clearfix">
					<?php $finance_service_home_args = array(
						'post_type'		=> 'finance-service',
						'posts_per_page' => $finance_service_per_page,
					);
					
					$finance_service_hoom_loop = new WP_Query($finance_service_home_args);
					if ($finance_service_hoom_loop->have_posts()) : while($finance_service_hoom_loop->have_posts()) : $finance_service_hoom_loop->the_post(); ?>
					
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
								<i class="icon-finance-plus"></i><a href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read More', 'finance' ); ?></a>
							</div>
						</div>
					</div>

					<?php endwhile; wp_reset_postdata(); endif; ?>
				</div>
				<?php } ?>

			</div>
		</div>
	</div>
	<!-- Service Section end -->

	<!-- Review Section -->
	<?php 
	$finance_allow_review_section		= get_field('allow_review_section');
	$finance_review_section_padding		= get_field('review_section_padding');
	$finance_review_section_background	= get_field('review_section_background');
	$finance_review_section_title		= get_field('review_section_title');
	$finance_review_section_text		= get_field('review_section_text');
	$finance_the_counter				= get_field('the_counter');
	$finance_testimonial_per_page		= get_field('testimonial_per_page');
	?>

	<?php if($finance_allow_review_section == true){ ?>
	<div class="review-section clearfix" style="<?php if(!empty($finance_review_section_padding)) { echo 'padding:'. sanitize_text_field( $finance_review_section_padding ) .'px 0;'; } ?> <?php if(!empty($finance_review_section_background)) { echo 'background-color:'. sanitize_text_field( $finance_review_section_background ) .';'; } ?>">
		<div class="container">
			<div class="row">
				
				<div class="counter-review col-md-6">
					<?php if(!empty($finance_review_section_title)) { ?>
						<h2 class="section-title white">
							<?php echo sanitize_text_field( $finance_review_section_title ); ?>
						</h2>
					<?php } ?>

					<?php if(!empty($finance_review_section_text)) { ?>
					<div class="review-text">
						<?php echo balancetags( $finance_review_section_text ); ?>
					</div>
					<?php } ?>
					
					<!-- the-counter -->
					<?php if(have_rows('the_counter')): ?>
					<div class="the-counter row clearfix">
						<?php while(have_rows('the_counter')):the_row(); 
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

							<?php $finance_testimonial_home_args = array(
								'post_type'		=> 'finance-testimonial',
								'posts_per_page' => $finance_testimonial_per_page,
							);
							
							$finance_testimonial_home_loop = new WP_Query($finance_testimonial_home_args);
							if ($finance_testimonial_home_loop->have_posts()) : while($finance_testimonial_home_loop->have_posts()) : $finance_testimonial_home_loop->the_post(); 

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

	<!-- Feature Section -->
	<?php 
		$finance_feature_section_title		= get_field('feature_section_title');
		$finance_feature_section_padding	= get_field('feature_section_padding');
	?>
	<?php if(have_rows('features')): ?>
	<div class="feature-section clearfix" style="<?php if(!empty($finance_feature_section_padding)) { echo 'padding:'. sanitize_text_field( $finance_feature_section_padding ) .'px 0;'; } ?>">
		<div class="container">

			<?php if(!empty($finance_feature_section_title)) { ?>
				<h2 class="section-title">
					<?php echo sanitize_text_field( $finance_feature_section_title ); ?>
				</h2>
			<?php } ?>

			<div class="features row clearfix">
			<?php while(have_rows('features')):the_row(); 
				$finance_feature_picture	= get_sub_field('feature_picture');
				$finance_feature_icon		= get_sub_field('feature_icon');
				$finance_feature_image		= get_sub_field('feature_image');
				$finance_feature_title		= get_sub_field('feature_title');
				$finance_feature_text		= get_sub_field('feature_text');
			?>

				<div class="feature col-md-4">

					<div class="feature-content">
						<div class="feature-pic">
							<?php
							if($finance_feature_picture == 'icon') {
								echo '<i class="icon-'. sanitize_text_field( $finance_feature_icon ) .'"></i>' ;
							}
							else {
								echo '<img src="'. esc_url( $finance_feature_image ) .'" alt="">' ;
							} ?>
						</div>
						<div class="feature-desc">
							<?php if(!empty($finance_feature_title)) { ?>
							<h5 class="title">
								<?php echo sanitize_text_field( $finance_feature_title ); ?>
							</h5>
							<?php }
							if(!empty($finance_feature_text)){
								echo balancetags( $finance_feature_text );
							} ?>
						</div>
					</div>
				</div>
			
			<?php endwhile; ?>
			</div>

		</div>
	</div>
	<?php endif; ?>
	<!-- Feature Section end -->

	<!-- Form Section -->
	<?php 
	$finance_form_home_padding		= get_field('form_home_padding');
	$finance_form_home_background	= get_field('form_home_background');
	$finance_form_home_html_content	= get_field('form_home_html_content');
	$finance_home_form				= get_field('home_form');
	?>
	<div class="form-section clearfix" style="<?php if(!empty($finance_form_home_padding)) { echo 'padding:'. sanitize_text_field( $finance_form_home_padding ) .'px 0;'; } ?> <?php if(!empty($finance_form_home_background)) { echo 'background-color:'. sanitize_text_field( $finance_form_home_background ) .';'; } ?>">
		<div class="container">
			<div class="row">
				
				<div class="form-html vertical-center col-md-6">
					<?php echo balancetags( $finance_form_home_html_content ); ?>
				</div>

				<div class="the-form col-md-6">
					<?php echo do_shortcode('[contact-form-7 id="' . $finance_home_form . '"]');  ?>
				</div>

			</div>
		</div>
	</div>
	<!-- Form Section end -->

	<!-- Blog Section -->
	<?php 
	$finance_allow_blog			= get_field('allow_blog');
	$finance_blog_home_padding	= get_field('blog_home_padding');
	$finance_blog_section_title	= get_field('blog_section_title');
	$finance_blog_per_page		= get_field('blog_per_page');
	?>

	<?php if($finance_allow_blog == true) { ?>
	<div class="blog-section clearfix" style="<?php if(!empty($finance_blog_home_padding)) { echo 'padding:'. sanitize_text_field( $finance_blog_home_padding ) .'px 0;'; } ?>">
		<div class="container">

			<?php if(!empty($finance_blog_section_title)) { ?>
				<h2 class="section-title">
					<?php echo sanitize_text_field( $finance_blog_section_title ); ?>
				</h2>
			<?php } ?>

			<div class="the-blog row clearfix">
			<?php $finance_posts_home_args = array(
				'post_type'		=> 'post',
				'posts_per_page' => $finance_blog_per_page,
				);
				
				$finance_posts_hoom_loop = new WP_Query($finance_posts_home_args);
				if ($finance_posts_hoom_loop->have_posts()) : while($finance_posts_hoom_loop->have_posts()) : $finance_posts_hoom_loop->the_post(); ?>
				
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-item col-md-4'); ?>>

					<div class="post-content-wrap">
					<?php if ( has_post_thumbnail()) { ?>
						<div class="post-thumb">
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail(); ?>
							</a>
						</div><!-- thumbnail-->
					<?php } ?> 

						<div class="post-content">
							<a href="<?php the_permalink(); ?>"><h4 class="post-title"><?php the_title(); ?></h4></a>

							<div class="post-meta clearfix">
								<div class="post-author">
									<div class="author-img">
										<?php  echo get_avatar( get_the_author_meta('ID'), '45' ); ?>
									</div>
									<div class="author-name">
										<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" title="<?php echo get_the_author_meta( 'display_name' ); ?>" rel="author"><?php echo get_the_author_meta( 'display_name' ); ?></a>
									</div>
								</div>
								
								<div class="post-date">
									<a href="<?php the_permalink(); ?>"><?php the_time( get_option( 'date_format' ) ); ?></a>
								</div>
							</div>

							<div class="post-text excerpt">
								<?php the_excerpt(); ?>
							</div>
							<a href="<?php the_permalink(); ?>" class="button button-normal"><?php esc_html_e( 'Read More', 'finance' ); ?></a>
						</div>
					</div>
				</article>
				
			<?php endwhile; wp_reset_postdata(); endif; ?>
			</div>

		</div>
	</div>
	<?php } ?>
	<!-- Blog Section end -->

	<!-- Client Section -->
	<?php 
	$finance_client_section_padding		= get_field('client_section_padding');
	$finance_client_section_background	= get_field('client_section_background');
	$finance_client_item_column			= get_field('client_item_column');
	?>
	<?php if(have_rows('the_clients')): ?>
	<div class="client-section clearfix" style="<?php if(!empty($finance_client_section_padding)) { echo 'padding:'. sanitize_text_field( $finance_client_section_padding ) .'px 0;'; } ?> <?php if(!empty($finance_client_section_background)) { echo 'background-color:'. sanitize_text_field( $finance_client_section_background ) .';'; } ?>">
		<div class="container">		
			<div class="the-clients">
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
		<script>
		jQuery(document).ready(function() { 
			jQuery('.the-clients').owlCarousel({
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