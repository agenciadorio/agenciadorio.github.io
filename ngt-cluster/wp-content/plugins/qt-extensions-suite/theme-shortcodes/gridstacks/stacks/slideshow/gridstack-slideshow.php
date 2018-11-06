<?php  

/**
 *
 *	Filename: gridstack-slideshow.php
 *	Function: adds automatic slideshow templates to the theme
 *	Based on materialize slideshow
 *
 *
 *
 **/
 

/**
 *
 *	Gridstack Slideshow Shortcodes
 * 
 */
if(!function_exists("qt_gridstack_slideshow_shortcode")){
	function qt_gridstack_slideshow_shortcode($atts){
		/**
		 *	Defaults
		 * 	All parameters can be bypassed by same attribute in the shortcode
		 */
		extract( shortcode_atts( array(
			'stackid' => '',
			'quantity' => 5,
			'posttype' => 'post',
			'orderby' => 'post_date',
			'taxonomy' => false,
			'term_ids' => false,
			'transition' => "500", 
			'interval'	=> '6000',
			'indicators' => false,
			'align' => "left",
			'arrows' => false,
			'full_width' => false,
			'preview' => false,
			'proportion' => 'original',
			'size' => 'widescreen',
			'excerpt' => false
		), $atts ) );
		/**
		 *	Query preparation
		 */
		$argsList = array(
				        'post_per_page' => $quantity,
				        'post_type' => $posttype,
				        'posts_per_page' => $quantity,
				        'orderby' => array(  'menu_order' => 'ASC' ,	'post_date' => 'DESC'),
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


		if(!function_exists('ev_excerpt_length')){
		function ev_excerpt_length($length) {
		    return 20;
		}}
		add_filter('excerpt_length', 'ev_excerpt_length');

		/**
		 *	Output the HTML of the gridstack 
		 */
		ob_start();
		?>
		<div class="slider qt-archive-slider qt-gridstackSlideshow <?php echo esc_attr(($full_width)? 'fullwidth' : ''); ?>" id="<?php echo esc_attr($stackid); ?>"   
		data-proportion="<?php echo esc_attr($proportion); ?>"   
		data-full_width="<?php echo esc_attr($full_width); ?>"  
		data-interval="<?php echo esc_attr($interval); ?>" 
		data-indicators="<?php echo esc_attr($indicators); ?>" 
		data-transition="<?php echo esc_attr($transition); ?>"
		<?php if(!wp_is_mobile()){ ?>data-100p-top="opacity:0;" data-75p-top="opacity:0;" data-60p-top="opacity:1;"<?php } ?>
		>
			<ul class="slides">
				<?php if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); 
				/**
		      	 * 
		      	 *
		      	 * 	Custom description
		      	 * 
		      	 */
		      	global $post;
		      	?> 
				<li class="slide">
					<?php  
					/**
			         *  Image
			         */
			        if( has_post_thumbnail() ){ 
			        	$imgsize = $size;
						if($size = 'widescreen') {
							$imgsize = 'qantumthemes_widescreen';
						}
			        	$img =  wp_get_attachment_image_src(get_post_thumbnail_id( $post->ID ), $imgsize );
				        $link = esc_url($img[0]);
				        ?><img src="<?php echo esc_url($img[0]); ?>" width="<?php echo esc_attr($img[1]); ?>" height="<?php echo esc_attr($img[02]); ?>" alt="<?php the_title(); ?>"><?php
			        	// the_post_thumbnail( $size ); // adds srcset which breaks the slideshow responsiveness
			        }
			        ?>

			        <?php  
			         /**
			         *  Caption
			         */
			        ?>
			        <div class="caption <?php echo esc_attr($align); ?>-align   <?php if(!$excerpt){ ?> vcenter <?php } ?>">

			          	<h2 class="qt-border-accent">
			          		<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			          	</h2>

			          	<?php if($excerpt && !wp_is_mobile()):  ?>
			          	<p class="text-flow">
			          	<span>
			          	<?php   echo get_the_excerpt(); ?>
						</span></p>
						<?php endif; ?>
			        </div>
			      
				</li> 
				<?php endwhile; endif; ?> 
			</ul>

			<?php if( ( true === $arrows || "true" === $arrows ) && !wp_is_mobile() ){ ?>
			<div class="nav hide-on-med-and-down">
				<div class="prev">
					<span class="qt-btn-rhombus btn"><i class="fa fa-chevron-left"></i></span>
				</div>
				<div class="next">
					<span class="qt-btn-rhombus btn"><i class="fa fa-chevron-right"></i></span>
				</div>
			</div>
			<?php } else { ?>
			<i class="qt-swipe hide-on-large-only"></i>
			<?php } ?>
		</div>
		<?php
		remove_filter( 'excerpt_length', 'ev_excerpt_length' );
		wp_reset_postdata();
		return ob_get_clean();
	}
}
add_shortcode( "gridstackSlideshow", "qt_gridstack_slideshow_shortcode");
add_shortcode( "qt-slideshow", "qt_gridstack_slideshow_shortcode");









