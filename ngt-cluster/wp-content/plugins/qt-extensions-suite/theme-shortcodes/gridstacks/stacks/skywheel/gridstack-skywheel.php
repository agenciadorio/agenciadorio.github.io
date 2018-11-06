<?php  

/**
 *
 *	Filename: gridstack-skywheel.php
 *	Function: adds automatic slideshow templates to the theme
 *	Based on materialize slideshow
 *
 *
 *
 **/

 
if(!function_exists("qt_gridstack_skywheel_shortcode")){
	function qt_gridstack_skywheel_shortcode($atts){
		/**
		 *	Defaults
		 * 	All parameters can be bypassed by same attribute in the shortcode
		 */
		extract( shortcode_atts( array(
			'stackid' => '',
			'quantity' => 7,
			'posttype' => 'post',
			'orderby' => 'post_date',
			'taxonomy' => false,
			'term_ids' => false,
			'archivelink' => false,
			'preview' => false,
			'height' => '400px',
			'width' => '100%'
		), $atts ) );

		/**
		 *	Query preparation
		 */
		$argsList = array(
				        'post_type' => esc_attr($posttype),
				        'posts_per_page' =>  esc_attr(intval($quantity)),
				        'orderby' => array(  'menu_order' => 'ASC' ,	'post_date' => 'DESC'),
				        'post_status' => 'publish',
				        'meta_key' => '_thumbnail_id'
				     );  
		if ($taxonomy && $term_ids) {
			$argsList[ 'tax_query'] = array(
            		array(
                    'taxonomy' => esc_attr($taxonomy),
                    'field' => 'id',
                    'terms' =>  esc_attr(intval($term_ids)),
                    'operator'=> 'IN' //Or 'AND' or 'NOT IN'
             	)
            );
		}

		$the_query = new WP_Query($argsList);



		add_filter( 'excerpt_length', 'my_excerpt_length', 999 );

		if(!function_exists('my_excerpt_length')){
			function my_excerpt_length( $length ){
				return 25;
			}
		}


		/**
		 *	Output the HTML of the gridstack 
		 */
		ob_start();
		?>


		<div id="<?php echo  esc_attr(esc_js($stackid)); ?>" class="qt-gridstackSkywheel" data-width="<?php echo esc_attr(esc_js($width)); ?>" data-height="<?php echo  esc_attr(esc_js($height)); ?>">
			<ul>
				<?php if(true === $archivelink || "true" === $archivelink){ ?>

					<?php  
						$obj = get_post_type_object( $posttype );
						$name = $obj->labels->menu_name;
					?>
					<li class="bg-accent">
						<div class="inner qt-border top bottom thick">

							<h2 class="title qt-titdeco center-align center"><?php  echo esc_attr($name); ?></h2>
							<h2 class="title center-align">
								<a class="btn qt-btn-rhombus qt-actionicon" href="<?php echo esc_attr(get_post_type_archive_link( $posttype )); ?>"><i class="fa fa-list-ul"></i></a> 
							</h2>


							<div class="caption">
								<div class="contents center-align">
									<h2><?php echo esc_attr($name); ?></h2>
									<a class="btn" href="<?php echo esc_attr(get_post_type_archive_link( $posttype )); ?>">
										<i class="fa fa-list-ul"></i> <?php echo esc_attr("Full archive" ,"qt-extensions-suite"); ?>
									</a>
								</div>
							</div>
						</div>
					</li>	

				<?php } ?>

				<?php if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?> 

						<?php 
						global $post;
						$bg = '';
						if(has_post_thumbnail()){ 
							$img =  wp_get_attachment_image_src(get_post_thumbnail_id( $post->ID ),'large');
							$bg = $img [0];
						} 

						?>

						<li data-bgimage="<?php echo esc_url(esc_attr($bg)); ?>">
							<div class="inner qt-border top bottom thick">
								<h2 class="title qt-titdeco center-align center"><?php the_title(); ?></h2>
								<h2 class="title center-align"><i class="fa fa-align-justify qt-actionicon"></i></h2>
								
								<div class="caption">
									
									<div class="contents">
										<?php if(has_post_thumbnail()){ the_post_thumbnail("qantumthemes_gridthumb", array("class" => "left-align feat qt-bordered")); } ?>
										<h3 class="qt-titdeco tit"><?php the_title(); ?></h3>
										<?php the_excerpt(); ?>
										<a class="btn <?php if($preview == 'true' || $preview == '1' ) { ?>qwjquerycontent<?php } ?>" href="<?php the_permalink(); ?>">
											<i class="fa fa-link"></i> <?php echo esc_attr__("Read more", "qt-extensions-suite") ?>
										</a>

									</div>
									<i class="fa fa-close close"></i>
								</div>
							</div>
						</li>	


				<?php endwhile; endif; ?> 






				
			</ul>
			<a href="#" class="btn qt-arrowUp"><i class="fa fa-chevron-up"></i></a>
			<a href="#" class="btn qt-arrowDown"><i class="fa fa-chevron-down"></i></a>
		</div>
		<?php
		wp_reset_postdata();

		return ob_get_clean();
		remove_filter( 'excerpt_length', 'my_excerpt_length', 999 );
	}
}
add_shortcode( "gridstackSkywheel", "qt_gridstack_skywheel_shortcode");
add_shortcode( "qt-skywheel", "qt_gridstack_skywheel_shortcode");









