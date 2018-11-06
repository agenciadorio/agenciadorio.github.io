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
 *	Gridstack Carousel Shortcodes
 * 
 */
if(!function_exists("qt_gridstack_carousel_shortcode")){
	function qt_gridstack_carousel_shortcode($atts){
		/**
		 *	Defaults
		 * 	All parameters can be bypassed by same attribute in the shortcode
		 */
		extract( shortcode_atts( array(
			/* PHP and Query parameters */
			'stackid' => '',
			'quantity' => 5,
			'posttype' => 'post',
			'orderby' => 'post_date',
			'taxonomy' => false,
			'term_ids' => false,
			'arrows' => false,
			'preview' => 1,
			'icon' => 'fa fa-link',
			/* Javascript data parameters */
			'vpadding' => '50px',
			'time_constant' => "200",
			'dist'	=> '-30',
			'shift' => '0',
			'padding' => "10",
			'full_width' => false // useless, if true breaks all
		), $atts ) );

		/**
		 *	Query preparation
		 */
		$argsList = array(
				        'post_per_page' => $quantity,
				        'post_type' => $posttype,
				        'posts_per_page' => $quantity,
				        'orderby' => array(  'menu_order' => 'ASC' ,	'post_date' => 'DESC'),
				       // 'order' => 'ASC',
				        'post_status' => 'publish',
				        'meta_key' => '_thumbnail_id'
				     );  
		if ($taxonomy && $term_ids) {
			$argsList[ 'tax_query'] = array(
            		array(
                    'taxonomy' => $taxonomy,
                    'field' => 'id',
                    'terms' => $term_ids,
                    'operator'=> 'IN' //Or 'AND' or 'NOT IN'
             	)
            );
		}
		$the_query = new WP_Query($argsList);

		/**
		 *	Output the HTML of the gridstack 
		 */
		ob_start();
		?>
		<div class="qt-gridstackCarousel-container" id="<?php echo esc_attr($stackid); ?>" <?php if(!wp_is_mobile()){ ?>data-100p-top="opacity:0;" data-80p-top="opacity:0;" data-60p-top="opacity:1;"<?php } ?>>
			<div class="carousel qt-gridstackCarousel" data-vpadding="<?php echo esc_attr($vpadding); ?>" data-time_constant="<?php echo esc_attr($time_constant); ?>"   data-dist="<?php echo esc_attr($dist); ?>"  data-shift="<?php echo esc_attr($shift); ?>" data-padding="<?php echo esc_attr($padding); ?>" data-full_width="<?php echo esc_attr($full_width); ?>" >
				<?php if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); 
				global $post;
				setup_postdata( $post );
				?> 
					<?php  if(has_post_thumbnail()){ ?>
					    <div class="carousel-item" href="<?php the_permalink(); ?>">
							<?php 
							$size = "qantumthemes_medium-thumb";
							if($posttype === "release" || $posttype === "podcast") {
								$size = "qantumthemes_gridthumb";
							}
							$img =  wp_get_attachment_image_src(get_post_thumbnail_id( $post->ID ), $size );
					        $link = esc_url($img[0]);
					        ?><img src="<?php echo esc_url($img[0]); ?>" data-imagesize="<?php echo esc_attr($size); ?>" width="<?php echo esc_attr($img[1]); ?>" height="<?php echo esc_attr($img[02]); ?>" alt="<?php the_title(); ?>">
							<a href="<?php the_permalink(); ?>" class="<?php if($preview == 'true' || $preview == '1' ) { ?>qwjquerycontent<?php } ?> qt-btn-rhombus btn"><i class="<?php echo esc_attr($icon); ?>"></i></a>
						</div>
					<?php } ?>				
				<?php endwhile; endif; ?> 
			</div>
			<?php if($arrows == true){ ?>
			<div class="nav hide-on-med-and-down">
				<div class="prev">
					<span class="qt-btn-rhombus btn"><i class="fa fa-chevron-left"></i></span>
				</div>
				<div class="next">
					<span class="qt-btn-rhombus btn"><i class="fa fa-chevron-right"></i></span>
				</div>
			</div>
			<?php } ?>
		</div>
		<i class="qt-swipe hide-on-large-only"></i>
		<?php
		wp_reset_postdata();
		return ob_get_clean();
	}
}
add_shortcode( "qt-carousel", "qt_gridstack_carousel_shortcode");









