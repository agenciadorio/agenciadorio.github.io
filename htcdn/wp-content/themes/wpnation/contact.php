<?php 

/*
Template Name: Contact page
*/

get_header(); 

$gmapsKey = ot_get_option('gmaps_key');

?>

	<!-- BEGIN CONTACT PAGE GOOGLE MAPS -->
	<div class="contact-maps-wrap">
		<?php if ( isset($gmapsKey) && ! empty($gmapsKey) ) { ?>
		<div id="gmaps"></div>
		<?php } else { ?>
		<p style="text-align:center;margin-top:30px;margin-bottom:70px;">
		<?php _e( 'Please obtain Google Maps API key and enter it in <b>WordPress Dashboard > Appearance > Theme Options > Google Maps > Google Maps API Key</b> field to display Google Maps here.','nation' ) ?></p>
		<?php } ?>
	</div>
	<!-- END CONTACT PAGE GOOGLE MAPS -->
			
	<!-- BEGIN CONTACT PAGE CONTENT -->
	<div class="container">
		<div class="eleven columns contact-page" id="is-contact-page">
			<?php
				if (have_posts()) : while (have_posts()) : the_post();
				the_content();
				endwhile; endif;
			?>
		</div>
		<div class="four columns offset-by-one contact-sidebar">
			<?php dynamic_sidebar( 'contact_sidebar' ); ?>	
		</div>
	</div>
	<!-- END CONTACT PAGE CONTENT -->
	
<?php get_footer(); ?>
