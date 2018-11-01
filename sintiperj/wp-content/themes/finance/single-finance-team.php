<?php get_header();

while ( have_posts() ) : the_post();

$finance_team_function		= get_field('team_function');
$finance_team_facebook		= get_field('facebook');
$finance_team_twitter		= get_field('twitter');
$finance_team_dribble		= get_field('dribble');
$finance_team_instagram		= get_field('instagram');
$finance_team_behance		= get_field('behance');
$finance_team_google_plus	= get_field('google_plus');

?>

<?php finance_page_title(); ?>

<!-- CONTENT START
============================================= -->
<div id="content" class="clearfix">

	<div class="single-team wrapper clearfix">
		<div class="container">
			<div class="row">

				<div class="col-md-3 team-profile">
					<?php if ( has_post_thumbnail()) { ?>
						<div class="post-thumb">
							<?php the_post_thumbnail(); ?>
						</div><!-- thumbnail-->
					<?php } ?>

					<div class="contact-team">
						<?php 	
						$finance_team_email	= get_field('team_email');
						$finance_team_phone	= get_field('team_phone');
						$finance_team_url	= get_field('team_url'); ?>
						
						<h3 class="single-team-name">
							<?php the_title(); ?>
						</h3>
						<?php if(!empty($finance_team_function)){ ?>
							<p class="job"><?php echo sanitize_text_field( $finance_team_function ); ?></p>
						<?php } ?>

						<div class="contact-detail-info clearfix">
							<?php if(!empty($finance_team_phone)) { ?>
							<p class="phone"><?php echo sanitize_text_field( $finance_team_phone ); ?></p>
							<?php }
							if(!empty($finance_team_email)) { ?>
							<p class="email"><?php echo sanitize_text_field( $finance_team_email ); ?></p>
							<?php } ?>
						</div>


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

				<div class="col-md-6 post-content">
					<div class="post-content">
						<div class="inner-content">
							<?php the_content(); ?>
						</div>
					</div> 

					<!-- Form Section -->
					<?php 
						$finance_team_contact	= get_field('team_contact');
					?>
					<div class="form-section clearfix">
						<div class="the-form">
							<?php echo do_shortcode('[contact-form-7 id="' . $finance_team_contact . '"]');  ?>
						</div>
					</div>
					<!-- Form Section end -->

				</div>

				<!-- SIDEBAR START
				============================================= -->
				<div class="blog-sidebar col-md-3">
					<?php 
					if ( is_active_sidebar( 'team-sidebar' ) ) { 
					dynamic_sidebar( 'team-sidebar' ); 
					} ?>

					<?php 
						if(have_rows('download_file')): ?>
						<div class="widget download">
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

<?php
endwhile; // end of the loop. 
get_footer(); ?>