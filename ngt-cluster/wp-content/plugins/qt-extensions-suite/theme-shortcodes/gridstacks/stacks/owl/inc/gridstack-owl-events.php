<?php  


/**
 *
 *	Gridstack Owl Shortcodes
 *
 *
 *
 * 
 */

if(!function_exists("qt_gridstack_events_shortcode")){
	function qt_gridstack_events_shortcode($atts){
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
			'posttype' => 'event',
			'postid' => get_the_ID(),
			'type' => "",
			'items' => 3,
			'orderby' => 'post_date',
			'taxonomy' => 'eventtype',
			'eventtype' => false,
			'autoplay' => "false",
			'autoplaytimeout' => 5000,
			'nav' =>  0,
			'dots' => 0,
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
			'post_type' => 'event',
			'posts_per_page' => $quantity,
			'post_status' => 'publish',
			'orderby' => 'meta_value',
			'order'   => 'ASC',
			'meta_key' => EVENT_PREFIX.'date',
			'suppress_filters' => false
	    );

		if(get_theme_mod( 'qt_events_hideold', 0 ) == '1'){
		    $argsList['meta_query'] = array(
            array(
                'key' => EVENT_PREFIX.'date',
                'value' => date('Y-m-d'),
                'compare' => '>=',
                'type' => 'date'
                 )
           	);
		}

		if($eventtype){
			if($eventtype != '' && $eventtype != false && eventtype != null){
				$argsList[ 'tax_query'] = array(
						array(
						'taxonomy' => "eventtype",
						'field' => 'slug',
						'terms' => array(esc_attr($eventtype)),
						'operator'=> 'IN' //Or 'AND' or 'NOT IN'
					)
				);
			}
		}


		if(!function_exists('sl_excerpt_length')){
		function sl_excerpt_length($length) {
		    return 20;
		}}
		add_filter('excerpt_length', 'sl_excerpt_length');

		$the_query = new WP_Query($argsList);


		/**
		 * 
		 *
		 *	Output the HTML of the gridstack 
		 *
		 */
		
		ob_start();
		
		?>
		 
		
		<div id="<?php echo esc_attr($stackid); ?>" class="owl-carousel qt-owlcarousel eventscarousel dots-<?php echo esc_attr($dots); ?>" 
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
			data-arrowstyle="minimal"
			data-videoHeight="<?php echo esc_attr($videoHeight); ?>" 
			data-videoWidth="<?php echo esc_attr($videoWidth); ?>"  <?php if(!wp_is_mobile()){ ?>data-100p-top="opacity:0;" data-90p-top="opacity:0;" data-75p-top="opacity:1;"<?php } ?>>
			
			<?php  
		
			/**
			 *
			 *
			 *	Creates a list of posts
			 *
			 * 
			 */
			if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); global $post; setup_postdata( $post ); 


				$date = ( get_post_meta( $post->ID, 'eventdate', true ) != "")? get_post_meta( $post->ID, 'eventdate', true ) : date("Y-m-d") ;
				$d = explode('-',$date);
				$day = date( "d", strtotime( $date ));
				$monthyear = date( "M Y", strtotime( $date ));
				$id = $post->ID;
				 $e = array(
				  'id' =>  $post->ID,
				  'date' =>  esc_attr(get_post_meta($post->ID,EVENT_PREFIX.'date',true)),
				  'location' =>  esc_attr(get_post_meta($post->ID, 'qt_location',true)),
				  'street' =>  esc_attr(get_post_meta($post->ID, 'qt_address',true)),
				  'city' =>  esc_attr(get_post_meta($post->ID, 'qt_city',true)),
				  'country' =>  esc_attr(get_post_meta($post->ID, 'qt_country',true)),
				  'permalink' =>  esc_url(esc_url(get_permalink($post->ID))),
				  'title' =>  esc_attr($post->post_title),
				  'phone' => esc_attr(get_post_meta($id, 'qt_phone',true)),
				  'website' => esc_attr(get_post_meta($id, 'qt_link',true)),
				  'facebooklink' => esc_attr(get_post_meta($id,EVENT_PREFIX . 'facebooklink',true)),
				  'coord' => esc_attr(get_post_meta($id,  'qt_coord',true)),
				  'email' => esc_attr(get_post_meta($id,  'qt_email',true))
				  //'thumb' => $thumb
				);

				 
				?>

				<div class="qt-figure event">
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
				
							<h2 class="qt-dateblock hide-on-med-and-down">
								<span class="qt-dateday"><?php echo esc_attr($day );?></span>
								<span class="qt-dateyear "><?php echo esc_attr( $monthyear ); ?></span>
							</h2>
							<a href="<?php the_permalink(); ?>" class="cmblink"><?php  echo esc_attr__("View more", "qt-extensions-suite"); ?></a>
						</figcaption>
					</figure>
					

					<div class="cap qt-border-accent">
						<h4><a href="<?php the_permalink(); ?>" class="cmblink"><?php the_title(); ?></a></h4>
						<h6 class="qt-nomargin hide-on-med-and-down"><?php echo esc_attr($e['location'].' / '.$e['city']) ?></h6>
					</div>
					<p class="hide-on-large-only"><?php echo esc_attr( date( get_option("date_format", "d M Y"), strtotime( $e['date'] )) ); ?> - <?php echo esc_attr($e['location'].' / '.$e['city']) ?></p>



					<div class="qt-small hide-on-small-only"><?php the_excerpt(); ?></div>
				</div>
				<?php
			endwhile; endif;
			?>
		</div>
		<i class="qt-swipe hide-on-large-only"></i>
		<?php
		remove_filter( 'excerpt_length', 'sl_excerpt_length' );

		wp_reset_postdata();
		return ob_get_clean();
	}
}
add_shortcode( "gridstackOwlEvents", "qt_gridstack_events_shortcode"); // used in related posts
add_shortcode( "qt-eventcarousel", "qt_gridstack_events_shortcode");

