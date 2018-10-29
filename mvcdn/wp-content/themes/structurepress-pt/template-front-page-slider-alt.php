<?php
/*
Template Name: Front Page With Layer/Revolution Slider
*/

get_header();

// slider
$structurepress_type = get_field( 'slider_type' );

if ( 'layer' === $structurepress_type && function_exists( 'layerslider' ) ) { // layer slider
	layerslider( (int) get_field( 'layerslider_id' ) );
}
else if ( 'revolution' === $structurepress_type && function_exists( 'putRevSlider' ) ) { // revolution slider
	putRevSlider( get_field( 'revolution_slider_alias' ) );
}

?>

<div id="primary" class="content-area  container" role="main">
	<div class="entry-content">
		<?php
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				the_content();
			}
		}
		?>
	</div>
</div>

<?php get_footer(); ?>