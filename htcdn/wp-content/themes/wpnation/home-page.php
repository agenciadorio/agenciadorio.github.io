<?php 

/*
Template Name: Home page
*/

get_header(); 

	//Extracting the values that user defined in OptionTree Plugin 
	$roomSection = ot_get_option('room_section');
	$aboutusSection = ot_get_option('aboutus_section');
	$infoSection = ot_get_option('info_section');
	$featureSection = ot_get_option('feature_section');
	$gmapsKey = ot_get_option('gmaps_key');
	$roomHeader = ot_get_option('main_room_header');
	$roomDescription = ot_get_option('main_room_description');
	$allRoomsLink = ot_get_option('link_all_room');
	$aboutusHeader = ot_get_option('aboutus_header');
	$aboutusImage = ot_get_option('aboutus_image');
	$aboutusContent = ot_get_option('aboutus_text');
	$mainSlider = ot_get_option('main_slider');
	$testimonialsHeader = ot_get_option('testimonials_header');
	$testimonialsCount = ot_get_option('testimonials_count');
	$textHeader = ot_get_option('text_section_header');
	$textContent = ot_get_option('text_section_content');
	$latestNews = ot_get_option('latest_news');
	$latestNewsCount = ot_get_option('latest_news_count');
	$findusHeader = ot_get_option('where_to_find_us_header');
	$findusTitle = ot_get_option('where_to_find_us_title');
	$findusAddress = ot_get_option('where_to_find_us_address');
	
	if ( isset( $findusTitle ) && $findusTitle != "" ) $findusTitle = explode(";", $findusTitle);
	if ( isset( $findusAddress ) && $findusAddress != "" ) $findusAddress = explode(";", $findusAddress);
	

 
	//Get first slider name	
	if (!isset($wpdb->revslider_sliders)) {
		$wpdb->revslider_sliders = $table_prefix . 'revslider_sliders';
	}
	
	$slider = $wpdb->get_row( "SELECT * FROM $wpdb->revslider_sliders" );
	if ( isset($slider) ) {
		$sliderName = $slider->alias;
	} else {
		$noslider = true;
	}
	
	if ( isset($mainSlider) && !empty($mainSlider) ) {
		$sliderName = $mainSlider;
	}
?>
		<!-- BEGIN MAIN SLIDER -->
		<?php if ( shortcode_exists( 'rev_slider' ) ) {			
			if ( isset( $sliderName ) && !empty( $sliderName ) ) {
				echo do_shortcode("[rev_slider $sliderName]"); 
			} else {
				echo "<div style='text-align:center;margin-top:60px;font-weight:bold;font-size:1.2em;color:red;'>".__("Please create at least one slider in WordPress Dashboard > Slider Revolution.",'nation')."</div>";
			}	
		} else {
			echo "<div style='text-align:center;margin-top:60px;font-weight:bold;font-size:1.2em;color:red;'>".__("Please install the slider revolution plugin to display main slider.",'nation')."</div>";
		}
		?>
		<!-- END MAIN SLIDER -->
		
		<?php if ( !isset($roomSection[0]) || $roomSection[0] != 'off' ) { ?>
		<!-- BEGIN MAIN ROOM VIEW -->
		<div id="room-view-wrap" class="main-rooms-list">
			<div class="container">
				<div id="main-news-header"><?php echo $roomHeader; ?></div>
				<div id="main-news-subheader"><?php echo do_shortcode($roomDescription); ?></div>	
				<div id="room-view-content">
					
					<?php 
					$the_query = new WP_Query( array( 'post_type' => 'rooms', 'showposts' => 3 ) );
					if ( $the_query->have_posts() ) {
						while ( $the_query->have_posts() ) {
							$the_query->the_post();
					?>
						
					<!-- BEGIN MAIN ROOMS WRAP -->
					<div class="rooms-list-item-wrap">
						<div class="rooms-list-item-image-wrap">
							<?php if ( has_post_thumbnail() ) { the_post_thumbnail('room-normal',array("class"=>"rooms-list-image")); } else { echo "<div class='main-room-placeholder'></div>"; } ?>
							<a href="<?php the_permalink();?>" class="room-overlay-link"><div class="room-main-list-overlay">
								<div class="room-overlay-content">
									<a href="<?php the_permalink();?>"><div class="room-overlay-readmore"><span class="icon-search"></span></div></a>
									<div class="room-overlay-checkavail overlay-checkavail2" id="room-main-one"><span class="icon-calendar"></span></div>
								</div>
								</div></a>
							<div class="rooms-list-item-price">
								<?php 
						
								//Get currency sign of current room
								$currencyCode = $wpdb->get_var( "SELECT currency_symbol FROM {$wpdb->prefix}nation_booking_settings" );

								$minPrice = $wpdb->get_var( $wpdb->prepare(
									"SELECT min_price FROM {$wpdb->prefix}nation_booking_calendars WHERE id='%d'",
									get_post_meta(get_the_ID(),'calendar',true)
								) ); 
						
								printf( __('From &nbsp;<span>%s</span>%d','nation'),$currencyCode,$minPrice); 
								
								?>
								<div class="price-shadow"></div>
							</div>
						</div>
						<div class="rooms-list-content-wrap module-bottom">
							<a href="<?php the_permalink();?>"><div class="rooms-list-header"><?php the_title() ?></div></a>
							<?php if ( get_post_meta(get_the_ID(),'room_bed',true) || get_post_meta(get_the_ID(),'max_person',true) ) { ?><div class="underheader-line"></div>
							<div class="room-list-parametr">
								<?php if ( get_post_meta(get_the_ID(),'room_bed',true) ) { ?><div id="room-bed"><?php _e('Beds:','nation'); echo " " . get_post_meta(get_the_ID(),'room_bed',true) ?></div><?php } ?>
								<?php if ( get_post_meta(get_the_ID(),'max_person',true) ) { ?><div id="room-person"><?php _e('Max person:','nation'); $person = get_post_meta(get_the_ID(),'max_person',true); echo " "; for ($i=1;$i <= $person;$i++) { echo "<span class='icon-male'></span>"; } ?></div><?php } ?>
							</div>	
							<?php } ?>
						</div>
						<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">							
							<div class="modal-body">
								<?php echo do_shortcode("[nation_calendar id='".get_post_meta(get_the_ID(),'calendar',true)."']") ?>
							</div>
							<div class="modal-footer">
								<button class="btn" data-dismiss="modal" aria-hidden="true"><?php _e('Close','nation') ?> <span class="icon-remove"></span></button>
							</div>
						</div>
					</div>
					<!-- END MAIN ROOMS WRAP -->
					
					<?php
						}
					} else {
					?>				
					<p style="text-align:center"><?php _e("There's no rooms to show. Please add new rooms in <b>WordPress Dashboard > Rooms</b> to display something here.",'nation'); ?></p>
					<?php
					}
					/* Restore original Post Data */
					wp_reset_postdata();
					?>
					
				</div>
				<?php if ( $allRoomsLink ) { ?><div id="show-rooms-main-wrap">
					<div id="show-rooms-main"><a href="<?php echo $allRoomsLink; ?>"><span class="icon-star"></span><span><?php _e('View all rooms','nation') ?></span></a></div>
					<div style="clear:both"></div>
				</div>
				<?php } ?>
			</div>
		</div>
		<!-- BEGIN MAIN ROOM VIEW -->
		<?php } else { 
		?>
		<style>
			#information-wrap {
				margin-top:0px;
			}
		</style>
		
		<?php } if ( !isset($aboutusSection[0]) || $aboutusSection[0] != 'off' ) { ?>
		
		<!-- BEGIN INFO SECTION -->
		<div id="information-wrap">
			<div class="container">
				<!-- BEGIN ABOUT US SECTION -->
				<div class="ten columns module-side module-side-left alpha">
					<div class="header-wrap">
						<span class="header-text"><?php echo $aboutusHeader ?></span>
					</div>
					<div id="main-aboutus-wrap">
						<?php if ( isset( $aboutusImage['background-image'] ) && !empty( $aboutusImage['background-image'] ) ) { ?>
						<img src="<?php echo $aboutusImage['background-image']; ?>" id="about-us-img">
						<?php } else { ?>
						<div id="aboutus-placeholder"></div>
						<?php } ?>
						<div id="about-us-content">
							<?php echo do_shortcode($aboutusContent); ?>
						</div>
					</div>
				</div>
				<!-- END ABOUT US SECTION -->
				
				<!-- BEGIN VISITORS TESTIMONIALS -->
				<div class="six columns module-side omega">
					<div class="header-wrap">
						<span class="header-text"><?php echo $testimonialsHeader; ?></span>
					</div>
					<?php 
						$the_query = new WP_Query( array( 'post_type' => 'testimonials', 'showposts' => $testimonialsCount ) );
						$initRotation = ($the_query->post_count>1) ? 'true' : 'false';
						
						if ( $the_query->have_posts() ) {
							while ( $the_query->have_posts() ) {
								$the_query->the_post();
					?>
					<div class="testimonials-content-wrap <?php if ($initRotation == 'true') echo "initialize-rotation"; ?>">
						<div class="testimonials-content"><span class="icon-quote-right"></span><?php the_content() ?> 
							<div class="testimonials-arrow"></div>
						</div>		
						<div class="testimonials-author-wrap">
							<?php if ( has_post_thumbnail() ) { the_post_thumbnail('full', array("class"=>"testimonials-image")); } else { echo "<img src='".get_template_directory_uri()."/images/without-image.png' class='testimonials-image'>"; } ?>
							<div class="author-info-wrap">
								<span class="testimonial-author"><?php if (get_post_meta(get_the_ID(),'author_name',true)) echo "&ndash; ".get_post_meta(get_the_ID(),'author_name',true); ?></span> 
								<span class="testimonial-author-ocupation"><?php if (get_post_meta(get_the_ID(),'author_occupation',true)) echo get_post_meta(get_the_ID(),'author_occupation',true); else echo '&nbsp;'; ?></span>
							</div>
							<div class="clear"></div>
						</div>
					</div>
				
				<!-- END VISITORS TESTIMONIALS -->
				<?php
						}
					} else {
					?>				
					<div><?php _e("There's no testimonials to show. Please add testimonials in <b>WordPress Dashboard > Testimonials</b> to display something here.",'nation'); ?></div>
					<?php
					}
					/* Restore original Post Data */
					wp_reset_postdata();
				?>
				</div>
			</div>
		</div>
		<!-- END INFO SECTION -->
		
		<?php } if ( !isset($infoSection[0]) || $infoSection[0] != 'off' ) { ?>
		
		<!-- BEGIN LOCATION INFO EVENT WRAP -->
		<div id="location-wrap">
			<div class="container">
				<div class="five columns module">
					<div class="header-wrap">
						<span class="header-text"><?php echo $textHeader ?></span>
					</div>
					<div id="main-text-widget-wrap">
						<?php echo do_shortcode($textContent); ?>
					</div>
				</div>		
				
				<!-- BEGIN EVENTS WRAP -->
				<div class="five columns module main-news-wrap">
					<div class="header-wrap">
						<span class="header-text"><?php echo $latestNews ?></span>
					</div>
					
					<?php 
					$the_query = new WP_Query( array('post_type' => 'post', 'showposts' => $latestNewsCount, 'category_name' => 'news' ) );
					if ( $the_query->have_posts() ) {
						while ( $the_query->have_posts() ) {
							$the_query->the_post();
					?>
				
					<div class="main-blog-posts-wrap">
						<div class="main-blog-post-wrap">
							<div class="main-blog-post-image-wrap">
								<?php if (MultiPostThumbnails::has_post_thumbnail('post', 'post-thumbnail')) {MultiPostThumbnails::the_post_thumbnail('post', 'post-thumbnail'); } else { echo "<div class='main-news-placeholder'></div>"; } ?>
								<a href="<?php the_permalink();?>" class="room-overlay-link"><div class="room-main-list-overlay">
									<div class="room-overlay-content">
										<a href="<?php the_permalink();?>"><div class="room-overlay-readmore"><span class="icon-link"></span></div></a>
									</div>
								</div></a>
							</div>
							<div class="main-blog-post-content-wrap">
								<div class="main-blog-post-header"><a href="<?php the_permalink();?>"><?php the_title() ?></a></div>
								<div class="main-blog-meta">
									<div class="main-blog-date"><?php _e('Date:','nation'); ?> <?php the_time(get_option('date_format')); ?></div>
									<!--<div class="main-blog-author"><?php _e('Author:','nation'); ?> <?php the_author_posts_link() ?></div> -->
									<div class="clear"></div>
								</div>
								<div class="main-blog-post-content"><?php nation_excerpt(11); ?></div>
							</div>
						</div>
						<div class="clear"></div>
					</div>
				
					<?php
						}
					} else {
					?>				
					<p><?php _e("Oops, there's no post in this category!",'nation'); ?> </p>
					<?php
					}
					/* Restore original Post Data */
					wp_reset_postdata();
					?>
				</div>
				<!-- END EVENTS WRAP -->
				
				<!-- BEGIN LOCATIONS WRAP -->
				<div class="five columns module">
					<div class="header-wrap">
						<span class="header-text"><?php echo $findusHeader ?></span>
					</div>	
					<?php if ( isset($gmapsKey) && ! empty($gmapsKey) ) { ?>
					<div id="gmaps"></div>
					
					<?php if ( isset($findusTitle) && $findusTitle != "" ) { ?>
					<div class="address-wrap">
						<?php for ($i=0;$i<count($findusTitle);$i++) { ?>
						<span class="icon-map-marker"></span>
						<div class="address-header"><?php if ( isset($findusTitle[$i]) ) { echo $findusTitle[$i]; } ?></div>
						<div class="address-content"><?php if ( isset($findusAddress[$i]) ) { echo $findusAddress[$i]; } ?></div>
						<br>
						<?php } ?>
					</div>
					<?php } ?> 
					<?php } else { ?>
					<p><?php _e( 'To display Google Maps please obtain a Google Maps API key and enter it in the <b>WordPress Dashboard > Appearance > Theme Options > Google Maps > Google Maps API Key</b> field.','nation' ) ?></p>
					<?php } ?>
				</div>
				<!-- END LOCATIONS WRAP -->
								
			</div>
		</div>
		
		<!-- END LOCATION INFO EVENT WRAP -->		
		<?php } else {
			if ( !isset($aboutusSection[0]) || $aboutusSection[0] != 'off' ) {
		?>
		<style>
			#footer-wrap {
				margin-top:0px;
			}
		</style>
		<?php
			}
		}
			
get_footer(); 

?> 