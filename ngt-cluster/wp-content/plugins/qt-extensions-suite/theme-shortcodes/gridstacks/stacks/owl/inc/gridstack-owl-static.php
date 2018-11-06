<?php  


/**
 *
 *	Gridstack Owl Shortcodes
 *
 *
 *
 * 
 */

if(!function_exists("qt_gridstack_owl_shortcode")){
	function qt_gridstack_owl_shortcode($atts){
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
			'items' => 4,
			'orderby' => 'post_date',
			'taxonomy' => 'category',
			'term_ids' => false,
			'autoplay' => "false",
			'autoplaytimeout' => 5000,
			'nav' => false,
			'dots' => false,
			'navRewind' => 'true',
			'autoplayHoverPause' => 'true',
			'margin' => '30',
			'loop' => 'true',
			'center' => 'false',
			'mouseDrag' => 'true',
			'touchDrag' => 'true',
			'pullDrag' => 'true',
			'freeDrag' => 'false',
			'stagepadding' => 0,
			'mergeFit' => 'true',
			'autoWidth' => 'false',
			'preview' => 1,
			'startPosition' => 0,
			'URLhashListener' => 'false',
			'navText' => '&#x27;,next&#x27;,&#x27;prev&#x27;',
			'video' => 'false',
			'videoHeight' => 'false',
			'videoWidth' => 'false',
			'fadein' => '0'
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

				$term_ids = false;
				if( is_wp_error( $terms ) ) {
				    return esc_attr__("Error in your shortcode parameters: ", "qt-extensions-suite").$terms->get_error_message();
				} else {
					if(is_array($terms)) {
						$term_ids = wp_list_pluck($terms,'term_id');
					}
					
				}
				


				
				
				if ($term_ids) {
					
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
		

		if(wp_is_mobile()) {
			$dots = '0';
		}

		?>
		 
		
		<div id="<?php echo esc_attr($stackid); ?>" class="owl-carousel qt-owlcarousel dots-<?php echo esc_attr($dots); ?>" 
			data-items="<?php echo esc_attr($items); ?>"
			data-margin="<?php echo esc_attr($margin); ?>" 
			data-loop="<?php echo esc_attr($loop); ?>" 
			data-center="<?php echo esc_attr($center); ?>" 
			data-mouseDrag="<?php echo esc_attr($mouseDrag); ?>" 
			data-touchDrag="<?php echo esc_attr($touchDrag); ?>" 
			data-pullDrag="<?php echo esc_attr($pullDrag); ?>" 
			data-freeDrag="<?php echo esc_attr($freeDrag); ?>" 
			data-stagePadding="<?php echo esc_attr($stagepadding); ?>"
			data-mergeFit="<?php echo esc_attr($mergeFit); ?>"
			data-autoWidth="<?php echo esc_attr($autoWidth); ?>" 
			data-startPosition="<?php echo esc_attr($startPosition); ?>" 
			data-URLhashListener="<?php echo esc_attr($URLhashListener); ?>" 
			data-nav="<?php if( !wp_is_mobile() ) { echo esc_attr($nav); } else {echo 'false';} ?>"
			data-navRewind="<?php echo esc_attr($navRewind); ?>" 
			data-navText="<?php echo esc_attr($navText); ?>"
			data-autoplayHoverPause="<?php echo esc_attr($autoplayHoverPause); ?>" 
			data-dots="<?php echo esc_attr($dots); ?>" 
			data-autoplay="<?php echo esc_attr($autoplay); ?>" 
			data-autoplaytimeout="<?php echo esc_attr($autoplaytimeout); ?>" 
			data-video="<?php echo esc_attr($video); ?>" 
			data-videoHeight="<?php echo esc_attr($videoHeight); ?>" 
			data-videoWidth="<?php echo esc_attr($videoWidth); ?>"
			<?php if($fadein && !wp_is_mobile()){ ?>data-100p-top="opacity:0;" data-90p-top="opacity:0;" data-75p-top="opacity:1;" <?php } ?>
			>
		
			<?php  
			/**
			 *
			 *	Special Gallery
			 *
			 */
			
			if(get_post_type($currentid) == 'mediagallery' && $type == 'gallery'){
				
				$events = get_post_meta($currentid, 'galleryitem', true); 
				  if(is_array($events)){
				    foreach($events as $event){ 
				      $img =  wp_get_attachment_image_src($event['image'],'qantumthemes_medium-thumb');
				      $link = '';
				      if(array_key_exists('video',$event)){
				        if($event['video'] != ''){
				          $link = $event['video'];
				        }
				      }
				      if($link =='') {
				        $img2 =  wp_get_attachment_image_src($event['image'],'full');
				        $link = esc_url($img2[0]);
				      }
				      ?>
				      
				      	<div class="qt-owl-card">
							<a href="<?php echo esc_attr($link); ?>" data-link="<?php echo esc_attr($link); ?>" class="qw-imgfx qw-disableembedding">
								<img src="<?php echo esc_url(esc_attr($img[0]));?>" alt="<?php echo esc_attr__("Zoom","qt-extensions-suite"); ?>"/>
							</a>
						</div>
				     <?php  
				    }
				}

			} else { 
				/**
				 *
				 *
				 *	Creates a list of posts
				 *
				 * 
				 */
				if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); global $post; setup_postdata( $post ); ?>

				<div class="qt-figure">
					<figure>
						<?php  
						if(has_post_thumbnail()){
							$size = "qantumthemes_medium-thumb";
							if($posttype === "release" || $posttype === "podcast") {
								$size = "qantumthemes_gridthumb";
							}
							$img =  wp_get_attachment_image_src(get_post_thumbnail_id( $post->ID ), $size );
					        $link = esc_url($img[0]);
					        ?><img src="<?php echo esc_url($img[0]); ?>" data-imagesize="<?php echo esc_attr($size); ?>" width="<?php echo esc_attr($img[1]); ?>" height="<?php echo esc_attr($img[02]); ?>" alt="<?php the_title(); ?>"><?php
						} else { ?>
							<?php  if($posttype === "release" || $posttype === "podcast") { ?>
								<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/default-release.jpg' ); ?>">
							<?php } else { ?>
								<img src="<?php echo esc_url( get_theme_mod('qt_default_post_image', get_template_directory_uri() . '/assets/img/default.jpg' ) ); ?>">
							<?php } ?>
						<?php  } ?>
						<figcaption>
							<p class="hide-on-med-and-down"><i class="fa fa-link"></i> <?php  echo esc_attr__("Read more","qt-extensions-suite"); ?></p>
							<h2 class="hide-on-med-and-down"><?php the_title(); ?></h2>
							<a href="<?php the_permalink(); ?>" class="cmblink <?php if($preview == 'true' || $preview == '1' ) { ?>qwjquerycontent<?php } ?>"><?php  echo esc_attr__("View more", "qt-extensions-suite"); ?></a>
						</figcaption>
					</figure>
					<h5 class="hide-on-large-only"><a href="<?php the_permalink(); ?>" class="cmblink <?php if($preview == 'true' || $preview == '1' ) { ?>qwjquerycontent<?php } ?>"><?php the_title(); ?></a></h5>
				</div>
				<?php
				endwhile; endif;
			}
			
		?>
		</div>
		<i class="qt-swipe hide-on-large-only"></i>
		<?php
		
		wp_reset_postdata();
		return ob_get_clean();
	}
}
add_shortcode( "gridstackOwl", "qt_gridstack_owl_shortcode"); // used in related posts
add_shortcode( "qt-owlcarousel", "qt_gridstack_owl_shortcode");

