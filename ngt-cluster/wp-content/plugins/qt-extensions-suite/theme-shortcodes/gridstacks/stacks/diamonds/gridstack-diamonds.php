<?php  

/**
 *
 *	Filename: gridstack-diamonds.php
 *	Function: creates a rhombus (diamonds) grid of elements based on jquery and nmhgrid
 *
 *
 **/

/**
 *
 *	Gridstack Diamonds Shortcodes
 * 
 */
if(!function_exists("qt_gridstack_diamonds_shortcode")){
	function qt_gridstack_diamonds_shortcode($atts){
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
			'archiveurl' => false,
			'taxonomy' => false,
			'term_ids' => false,
			'archivelink' => false,
			'icon' => 'fa fa-link',
			'ignore_sticky_posts' => 1,
			'preview' => false,
			'_thumbnail_id' => 0
			
		), $atts ) );

		/**
		 *	Query preparation
		 */
		$argsList = array(
	        'post_per_page' => $quantity,
	        'post_type' => $posttype,
	        'posts_per_page' => $quantity,
	        'orderby' =>  array(  'menu_order' => 'ASC' ,	'post_date' => 'DESC'),
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
		//echo "archivelink". $archivelink;

		$the_query = new WP_Query($argsList);
		/**
		 *	Output the HTML of the gridstack 
		 */
		ob_start();
		?>
		<div class="qt-gridstackDiamonds" id="<?php echo esc_attr($stackid); ?>"  >
			<?php if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?> 
					<div class="qt-gsd-item" data-bottom-top="opacity:0" data-bottom-center="opacity:0"  data-bottom="opacity:1">
						<div class="grid-inner">
							<?php if(has_post_thumbnail()){ the_post_thumbnail("qantumthemes_gridthumb"); } ?>
							<div class="caption">
								<h4 class="qt-titdeco center-align"><?php the_title(); ?></h4>
							</div>
							<a href="<?php the_permalink(); ?>" class="<?php if($preview == 'true' || $preview == '1' ) { ?>qwjquerycontent<?php } ?>">
								<span class="qt-btn-rhombus btn"><i class="<?php echo esc_attr($icon); ?>"></i></span>
							</a>
						</div>
					</div>			
			<?php endwhile; endif; ?>
			<?php if($archivelink){ ?>
				<div class="qt-gsd-item bg-accent">
					<div class="grid-inner bg-accent">
						<div class="caption">
							<h4 class="qt-titdeco center-align"><?php echo esc_attr__("View all", "qt-extensions-suite"); ?></h4>
						</div>
						<a href="<?php 
						if(!$archiveurl || $archiveurl == ''){
							echo esc_attr(get_post_type_archive_link( $posttype )); 
						} else {
							echo esc_url( $archiveurl ); 
						}

						?>">
							<span class="qt-btn-rhombus btn"><i class="fa fa-link"></i></span>
						</a>
					</div>
				</div>
			<?php } ?>
		</div>
		<?php
		wp_reset_postdata();
		return ob_get_clean();
	}
}
add_shortcode( "qt-diamonds", "qt_gridstack_diamonds_shortcode");









