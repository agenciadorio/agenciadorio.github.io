<?php  


/**
 *
 *	Gridstack Owl Row
 * 	Displays picture and texts in line
 *
 *
 * 
 */

if(!function_exists("qt_gridstack_row_shortcode")){
	function qt_gridstack_row_shortcode($atts){
		/**
		 *
		 *	Defaults
		 * 	All parameters can be bypassed by same attribute in the shortcode
		 *
		 * 
		 */
		extract( shortcode_atts( array(
			'stackid' => '',
			'currentid' => 0,
			'quantity' => 12,
			'posttype' => 'post',
			'postid' => get_the_ID(),
			'type' => "",
			'items' => 5,
			'orderby' => 'post_date',
			'taxonomy' => 'category',
			'term_ids' => false,
			'style' => 'row',
			'preview' => 1,
			'autoplay' => "false",
			'autoplaytimeout' => 3000,
			'nav' => false,
			'dots' => false,
			'navRewind' => "true",
			'autoplayHoverPause' => false,
			'readmore' => false,
			'margin' => '30',

			'loop' =>  'true',
			'center' =>  false,
			'mouseDrag' => 'true',
			'touchDrag' => 'true',
			'pullDrag' => 'true',
			'freeDrag' => 'false',
			'stagepadding' => 0,
			'mergeFit' => 'true',
			'startPosition' => 0,
			'URLhashListener' => 'false',
			'navText' => '&#x27;,next&#x27;,&#x27;prev&#x27;',
			'video' => 'false',
			'videoHeight' => 'false',
			'videoWidth' => 'false'
		), $atts ) );
		if($items == 1) { $items = 2;}

		/**
		 *
		 *	Query preparation
		 *
		 *
		 * 
		 */
		$argsList = array(
				        'post_per_page' => $quantity,
				        'post_type' => $posttype,
				        'posts_per_page' => $quantity,
				        'orderby' => array(  'menu_order' => 'ASC' ,	'post_date' => 'DESC'),
				        'post_status' => 'publish',
				        'meta_query'   => array(
				                            array(
				                                    'key' => '_thumbnail_id',
				                                    'compare' => 'EXISTS'
				                                ),
				                            )
				     );  
		
		switch ($type){
			case "related":
				
				$terms = get_the_terms( $currentid  , $taxonomy, 'string');


				if( is_wp_error( $terms ) ) {
				    return esc_attr__("Error in your shortcode parameters: ", "qt-extensions-suite").$terms->get_error_message();
				}
				$term_ids = wp_list_pluck($terms,'term_id');


				
				
				if ($terms) {
					
					$args = array(
				      'post_type' => $posttype,
				      'tax_query' => array(
				                    		array(
						                        'taxonomy' => $taxonomy,
						                        'field' => 'id',
						                        'terms' => $term_ids,
						                        'operator'=> 'IN' //Or 'AND' or 'NOT IN'
				                     	)
				                    ),
				      'posts_per_page' => $quantity,
				      'orderby' => $orderby,
				      'meta_query'   => array(
				                            array(
				                                    'key' => '_thumbnail_id',
				                                    'compare' => 'EXISTS'
				                                ),
				                            ),
				      'post__not_in'=>array($currentid)
				   );

					$the_query = new WP_Query($args);
					if ( !$the_query->have_posts() ) {
						$the_query = new WP_Query($argsList);
					} 
				} else {

					$the_query = new WP_Query($argsList);
				}
				break;
			case "gallery":
				// we don't need any query for gallery
				break;
			case "adjacent":
			default:
				if($term_ids && $taxonomy) {
					if(is_numeric($term_ids)) {
						$argsList['tax_query'] = array(
	                    		array(
			                        'taxonomy' => $taxonomy,
			                        'field' => 'id',
			                        'terms' => $term_ids,
			                        'operator'=> 'IN' //Or 'AND' or 'NOT IN'
	                     	)
	                    );
					}
				}
				$the_query = new WP_Query($argsList);
				break;

				
		}

		/**
		 * 
		 *
		 *	Output the HTML of the gridstack 
		 *
		 */
		ob_start();

		if($stackid == '') {
			 $stackid = substr(str_shuffle(str_repeat("abcdefghijklmnopqrstuvwxyz", 5)), 0, 5);
		}
		?>
		 
		
		<div id="<?php echo esc_attr($stackid); ?>" class="owl-carousel owl-carousel-row qt-owlcarousel qt-style-<?php echo esc_attr($style); ?> dots-<?php echo esc_attr($dots); ?>" 
			data-items="<?php echo esc_attr($items); ?>"
			data-autoWidth="false" 
			data-dots="<?php echo esc_attr($dots); ?>" 
			data-loop="<?php echo esc_attr($loop); ?>" 
			data-margin="<?php echo esc_attr($margin); ?>" 
			data-center="<?php echo esc_attr($center); ?>" 
			data-mouseDrag="<?php echo esc_attr($mouseDrag); ?>" 
			data-touchDrag="<?php echo esc_attr($touchDrag); ?>" 
			data-pullDrag="<?php echo esc_attr($pullDrag); ?>" 
			data-freeDrag="<?php echo esc_attr($freeDrag); ?>" 
			data-stagePadding="<?php echo esc_attr($stagepadding); ?>"
			data-mergeFit="<?php echo esc_attr($mergeFit); ?>"
			data-startPosition="<?php echo esc_attr($startPosition); ?>" 
			data-URLhashListener="<?php echo esc_attr($URLhashListener); ?>" 
			data-nav="<?php if( !wp_is_mobile() ) { echo esc_attr($nav); } else {echo 'false';} ?>"
			data-navRewind="<?php echo esc_attr($navRewind); ?>" 
			data-navText="<?php echo esc_attr($navText); ?>"
			data-autoplayHoverPause="<?php echo esc_attr($autoplayHoverPause); ?>" 
			data-autoplay="<?php echo esc_attr($autoplay); ?>" 
			data-autoplaytimeout="<?php echo esc_attr($autoplaytimeout); ?>" 
			data-video="<?php echo esc_attr($video); ?>" 
			data-videoHeight="<?php echo esc_attr($videoHeight); ?>" 
			data-videoWidth="<?php echo esc_attr($videoWidth); ?>"
			data-arrowstyle="minimal" 
			<?php if(!wp_is_mobile()){ ?>data-100p-top="opacity:0;" data-90p-top="opacity:0;" data-75p-top="opacity:1;"<?php } ?>
			>

			<?php  if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
				<div class="qt-owlrow-item">
					<?php  if(has_post_thumbnail()){ ?><a href="<?php the_permalink(); ?>" class="qt-featimage qw-imgfx <?php if($preview == 'true' || $preview == '1' ) { ?>qwjquerycontent<?php } ?>">
					<?php the_post_thumbnail('qantumthemes_gridthumb');?></a><?php } ?>
					<h5><?php the_title(); ?></h5>
					<?php if($readmore){ ?>
						<p class="qt-small"><a href="<?php the_permalink(); ?>" class="<?php if($preview == 'true' || $preview == '1' ) { ?>qwjquerycontent<?php } ?>"><?php echo esc_attr__("Read more", "qt-extensions-suite"); ?></a></p>
					<?php } ?>
					
				</div>
			<?php endwhile; endif; ?>
		</div>
		<i class="qt-swipe hide-on-large-only"></i>
		<?php
		wp_reset_postdata();
		return ob_get_clean();
	}
}
add_shortcode( "gridstackRow", "qt_gridstack_row_shortcode");
add_shortcode( "qt-owlcarousel-row", "qt_gridstack_row_shortcode");