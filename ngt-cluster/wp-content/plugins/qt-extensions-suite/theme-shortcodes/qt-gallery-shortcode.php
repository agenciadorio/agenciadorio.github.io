<?php  

/**
 *
 *	Gallery shortcode
 *
 * 
 */

if(!function_exists('qtGalleryShortcodeFunc')) {
	function qtGalleryShortcodeFunc($atts) {
		//return 'hello';
		extract( shortcode_atts( array(
				'id' => false
		), $atts ) );
		if(is_numeric($id)){
			$args = array(
				'post_type' => 'mediagallery',
				'posts_per_page' => 1,
				'post_status' => 'publish',
				'p' =>  $id
		    );
		    ob_start();
			$wp_query = new WP_Query( $args );
			if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();
				$style = get_post_meta($id, 'style', true);
				switch ($style ){
                  case "carousel":
                     get_template_part('part-gallery', 'carousel' ); 
                      break;
                  case "masonry":
                  default:
                  	get_template_part('part-gallery', 'masonry' ); 
              	}
			endwhile; else: ?>
		   	 	<h3><?php echo esc_attr__("Sorry, nothing here","qt-extensions-suite")?></h3>
		    <?php endif;
		    wp_reset_postdata();
		    return ob_get_clean();
		}
	}
}
	
/**
 *
 *	Adding the shortcode to the PHP
 *
 * 
 */


function qt_register_shortcode_gallery_func(){
   add_shortcode('qtgallery', 'qtGalleryShortcodeFunc');
}
add_action( 'init', 'qt_register_shortcode_gallery_func');

