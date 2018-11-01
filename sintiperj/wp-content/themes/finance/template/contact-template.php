<?php get_header();

/*
Template Name: Contact Template
*/

if( class_exists('acf') ) { 
	$finance_google_maps				= get_field('google_maps');
	$finance_telephone					= get_field('telephone');
	$finance_email_address				= get_field('email_address');
	$finance_location					= get_field('location');
	$finance_hours						= get_field('hours');
	$finance_contact_form_template		= get_field('contact_form_template');
	$finance_contact_form_subtitle		= get_field('contact_form_subtitle');
	$finance_contact_detail_title		= get_field('contact_detail_title');
	$finance_contact_detail_subtitle	= get_field('contact_detail_subtitle');
?>

<?php finance_page_title(); ?>

<!-- CONTENT START
============================================= -->
<div class="contact-page clearfix">

	<div id="content" class="clearfix">

		<div class="contact-form-section">
			<div class="container">
				<div class="row">

					<!-- CONTACT FORM START
					============================================= -->
					<div class="contact-form col-md-6">

						<?php if(!empty($finance_contact_form_template)){ ?>
						<div class="form wow fadeIn">
							<?php echo do_shortcode( $finance_contact_form_template ); ?>
						</div>
						<?php } ?>
					</div>
					<!-- CONTACT FORM END -->


					<div class="contact-details col-md-6">
						<?php if(!empty($finance_contact_detail_title) || !empty($finance_contact_detail_subtitle)) { ?>
						<div class="title-section">
							<?php if(!empty($finance_contact_detail_title)) { ?>
								<h5 class="wow fadeInDown" data-wow-duration="2s" data-wow-delay="0.3s"><?php echo sanitize_text_field( $finance_contact_detail_title ); ?></h5>
							<?php }
							if(!empty($finance_contact_detail_subtitle)) { ?>
								<div class="wow fadeIn" data-wow-duration="1s" data-wow-delay="0.7s"><?php echo balancetags( $finance_contact_detail_subtitle ); ?></div>
							<?php } ?>
						</div>
						<?php } ?>

						<div class="contact-text clearfix">
							<div class="contact-author-details">
								<?php if(!empty($finance_telephone)) { ?>
									<div class="telephone">
										<h5 class="title"><?php esc_html_e( 'Telephone', 'finance' ); ?></h5>
										<?php echo balancetags( $finance_telephone ); ?>
									</div>
								<?php }
								if(!empty($finance_email_address)) { ?>
									<div class="email-contact">
										<h5 class="title"><?php esc_html_e( 'Email', 'finance' ); ?></h5>
										<?php echo balancetags( $finance_email_address ); ?>
									</div>
								<?php }
								if(!empty($finance_location)) { ?>
									<div class="location">
										<h5 class="title"><?php esc_html_e( 'Location', 'finance' ); ?></h5>
										<?php echo balancetags( $finance_location ); ?>
									</div>
								<?php }
								if(!empty($finance_hours)) { ?>
									<div class="hours">
										<h5 class="title"><?php esc_html_e( 'Work Hours', 'finance' ); ?></h5>
										<?php echo balancetags( $finance_hours ); ?>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>

	</div>

	<?php if(!empty($finance_google_maps)){ ?>
	<div class="contact-map">
		<div class="maps wow fadeIn">
			<?php echo balancetags( $finance_google_maps ); ?>
		</div>
	</div>
	<?php } ?>

</div>
<!-- CONTENT END -->

<?php } 

else { ?>


<section id="content" class="single-wrapper">
	<!-- Page Title -->
	<div class="grey-background wow fadeIn">
		<div class="container">
			<div class="heading-block page-title wow fadeIn">
				<h1>
					<?php esc_html_e( 'Please Activate ACF plugin to use this Page Template', 'finance' ); ?>
				</h1>
			</div>
		</div>
	</div>	

</section>
<!-- CONTENT END -->


<?php } ?>

<?php get_footer(); ?>