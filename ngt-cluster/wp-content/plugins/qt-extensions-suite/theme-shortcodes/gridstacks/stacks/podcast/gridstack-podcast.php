<?php

/**
 *
 *	Filename: gridstack-carousel.php
 *	Function: adds automatic carousel templates to the theme
 *	Based on materialize carousel
 *	http://materializecss.com/media.html#two!
 *
 *
 *
 **/


/**
 *
 *	Gridstack Podcast Shortcodes
 * 
 */
if(!function_exists("qt_gridstack_podcast_shortcode")){
	function qt_gridstack_podcast_shortcode($atts){
		/*
		 *	Defaults
		 * 	All parameters can be bypassed by same attribute in the shortcode
		 */
		extract( shortcode_atts( array(
			/* PHP and Query parameters */
			
			'quantity' => 3,
			'term_ids' => false
			
		), $atts ) );

		/**
		 *	Output the HTML of the gridstack 
		 */
		ob_start();	

		
		?>

		<ul class="collapsible popout qt-archivepodcast-list" data-collapsible="accordion" id="itemslist" >
			<?php
			$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
			$args = array(
				'post_type' => 'podcast',
				'posts_per_page' => $quantity,
				'post_status' => 'publish',
				'orderby' => 'meta_value',
				'order'   => 'DESC',
				'meta_key' => '_podcast_date',
				'suppress_filters' => false,
				'paged' => 1
		    );


		    if ($term_ids) {
				$args[ 'tax_query'] = array(
	            		array(
	                    'taxonomy' => 'podcastfilter',
	                    'field' => 'id',
	                    'terms' => esc_attr($term_ids),
	                    'operator'=> 'IN' //Or 'AND' or 'NOT IN'
	             	)
	            );
			}

			$wp_query = new WP_Query( $args );
			if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();
				get_template_part('part','archivepodcast' ); 
			endwhile; else: ?>
		   	 	<h3><?php echo esc_attr__("Sorry, nothing here","qt-extensions-suite")?></h3>
		    <?php endif; ?>
		</ul>
		<?php 
		wp_reset_postdata();
		return ob_get_clean();

	}
}
add_shortcode( "qt-podcast", "qt_gridstack_podcast_shortcode");

?>