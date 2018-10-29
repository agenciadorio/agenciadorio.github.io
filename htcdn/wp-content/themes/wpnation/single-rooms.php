<?php 

get_header(); 

?>

	<!-- BEGIN PAGE TITLE -->
		<?php	
		$showTitle = get_post_meta($post->ID, "page_title",true);
		
		$roomFeatures = get_post_meta($post->ID, "room_features",true); 
		$roomPolicies = get_post_meta($post->ID, "room_policies",true); 
		
		if ($showTitle == "Yes") {
			$breadcrumb = get_post_meta($post->ID, "breadcrumb",true);
			$pageIcon = get_post_meta($post->ID, "page_icon",true);
			$pageClass = get_post_meta($post->ID, "page_class",true);
			$pageDescription = get_post_meta($post->ID, "page_description",true); 
			$pageTitleAlign = get_post_meta($post->ID, "page_align",true); 	
		?>
		
		<style>
		<?php if (!$pageDescription && $breadcrumb=="Yes") { ?>
		#crumbs {
			margin-top:2px;
		}
		<?php } 
		if ($pageTitleAlign =="Center") { ?>
		#main-title-wrap, #main-title-undertext {
			text-align:center;
		}
		<?php } ?>
		</style>
		
		<div id="top-content-divider">
			<div class="container">
				<div id="main-title-wrap"><?php if ($pageIcon) { ?><span class="<?php echo $pageIcon; ?>"></span><?php } ?> <?php the_title(); ?> <?php if ($breadcrumb == "Yes") { nation_breadcrumbs(); } ?></div>
				<?php if ($pageDescription) { ?>
				<div id="main-title-undertext"><?php echo $pageDescription; ?></div>
				<?php } ?>
			</div>
		</div>
		
		<?php 
		} else {
			echo "<div id='top-divider' class='container'></div>";
		}
	
		// Start the Loop.
		while ( have_posts() ) : the_post();
		?>
	<!-- END PAGE TITLE -->
			
	<!-- BEGIN PAGE CONTENT -->
	<div class="container reservation-page-wrap">
		<div class="sixteen columns">
			<br />	
			<!-- BEGIN RESERVATION PAGE SLIDER -->
			<div class="bannercontainer" >
				<div class="reservation-page-slider" >
					<ul>
						<!-- FIRST SLIDE -->
						<?php 
							// Loops through each feature image and grabs thumbnail URL
							for ($g=1;$g<5;$g++){
								if (MultiPostThumbnails::has_post_thumbnail('rooms', 'slider-image-'.$g)) {
									echo "<li data-transition='fade' data-masterspeed='300' data-slideindex='back'>";
									MultiPostThumbnails::the_post_thumbnail('rooms', 'slider-image-'.$g); 
									echo "</li>";
								}                    
							}
						?>
					</ul>
					<div class="tp-bannertimer tp-bottom"></div>
				</div>
			</div>
			<!-- END RESERVATION PAGE SLIDER -->
			<?php 
			
			//Get currency sign of current room
			$currencyCode = $wpdb->get_var( "SELECT currency_symbol FROM {$wpdb->prefix}nation_booking_settings" );

			$minPrice = $wpdb->get_var( $wpdb->prepare(
				"SELECT min_price FROM {$wpdb->prefix}nation_booking_calendars WHERE id='%d'",
				get_post_meta(get_the_ID(),'calendar',true)
			) ); 
			
			?>
			<div class="room-content-description">
				<h3><?php the_title(); ?></h3>			
				<div id="room-content-price">
					<?php printf( __( "from <span id='room-price'><span>%s</span>%d</span><span id='per-day'> /night</span>", "nation" ), $currencyCode, $minPrice ) ?>
				</div>
				<?php if ( get_post_meta(get_the_ID(),'room_bed',true) || get_post_meta(get_the_ID(),'max_person',true) || get_post_meta(get_the_ID(),'room_size',true) ) { ?>
				<div id="room-parametr">
					<?php if ( get_post_meta(get_the_ID(),'max_person',true) ) { ?><div id="room-person"><?php _e('Max person:','nation'); $person = get_post_meta(get_the_ID(),'max_person',true); echo " "; for ($i=1;$i<=$person;$i++) { echo "<span class='icon-male'></span>"; } ?></div><?php } ?>
					<?php if ( get_post_meta(get_the_ID(),'room_bed',true) ) { ?>,&nbsp;&nbsp;&nbsp;<div id="room-bed"><?php _e('Room bed: ','nation'); echo get_post_meta(get_the_ID(),'room_bed',true); ?></div><?php } ?>
					<?php if ( get_post_meta(get_the_ID(),'room_size',true) ) { ?>,&nbsp;&nbsp;&nbsp;<div id="room-size"><?php _e('Room size: ','nation'); echo get_post_meta(get_the_ID(),'room_size',true); ?> </div> <?php } ?>
				</div>
				<?php } ?>	
				<div id="room-content">
					<?php nation_excerpt(32) ?>
				</div>
				<?php $reservationPage = get_pages(array(
					'meta_key' => '_wp_page_template',
					'meta_value' => 'reservation.php'
				));
				$reservationID = $reservationPage[0]->ID;
				
				 ?>
				<form id="room-date-form" action="<?php echo get_permalink( $reservationID ) ?>" method="post">
					<div id="check-in-date-wrap">
						<input type="text" placeholder="<?php _e("check-in date","nation") ?>" name="check-in" id="check-in-date" class="reservation-form-field">
					</div>
					<div id="check-out-date-wrap">
						<input type="text" placeholder="<?php _e("check-out date","nation") ?>" name="check-out" id="check-out-date" class="reservation-form-field">
					</div>
					<div style="clear:both"></div>
					<select name="room-adults" id="single-room-adult-selection">
						<option value="" selected><?php _e("No. adults","nation"); ?></option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
					</select>
					<select name="room-children" id="single-room-children-selection">
						<option value="" selected><?php _e("No. children","nation"); ?></option>
						<option value="0">0</option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
					</select>
					<input type="hidden" value="1" name="room-number">
					<input type="hidden" value="true" name="from-single-room">
					<input type="hidden" value="<?php echo get_post_meta(get_the_ID(),'calendar',true) ?>" name="selected-room">
					<div class="clear"></div>
					
					<button type="submit" id="book-button"><?php _e('Book Now','nation'); ?><span class="icon-shopping-cart"></span></button>
				</form>
			</div>
			<div class="clear"></div>

			<div id="tabs-widget-wrap">
				<ul id="tabs">
					<li><a href="#" name="#tab1"><?php _e('Description','nation'); ?></a></li>
					<?php if ( isset($roomFeatures) ) { ?><li><a href="#" name="#tab2"><?php _e('Features','nation'); ?></a></li><?php } ?>
					<?php if ( isset($roomPolicies) ) { ?><li><a href="#" name="#tab3"><?php _e('Policies','nation'); ?></a></li><?php } ?>
					<li><a href="#" name="#tab4"><?php _e('Availability','nation'); ?></a></li>
					<li><a href="#" name="#tab5"><?php  comments_number(__("Review (0)",'nation'),__("Review (1)",'nation'),__("Reviews (%)",'nation')) ?> </a></li>
				</ul>
				<div id="tabs-content">
					<div id="tab1">
						<?php the_content(); ?>
					</div>
					<?php if ( isset($roomFeatures) ) { ?>
					<div id="tab2">
						<div id="room-features">
							<?php echo do_shortcode(html_entity_decode($roomFeatures)); ?>
							<div style="clear:both;"></div>
						</div>
					</div>
					<?php } ?>
					<?php if ( isset($roomPolicies) ) { ?>
					<div id="tab3">
						<?php echo do_shortcode(html_entity_decode($roomPolicies)); ?>
					</div>
					<?php } ?>
					<div id="tab4">
						<?php echo do_shortcode("[nation_calendar id='".get_post_meta(get_the_ID(),'calendar',true)."']") ?>
					</div>
					<div id="tab5">
						<?php comments_template(); ?>
					</div>
				</div>
			</div>
		</div>	
	</div>				
	<br /><br />

	<!-- END PAGE CONTENT -->
	
<?php 
endwhile;
get_footer(); ?>