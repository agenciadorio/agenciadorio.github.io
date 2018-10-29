<?php 

/*
Template Name: One room list
*/

$roomCount = ot_get_option('rooms_list_count');

get_header(); ?> 

		<!-- BEGIN PAGE TITLE -->
		<?php	
		$showTitle = get_post_meta($post->ID, "page_title",true);
		
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
		?>
		<!-- END PAGE TITLE -->
		
		<!-- BEGIN PAGE CONTENT -->
		<div class="container one-item-list">
			
			<?php
				$postCount = 0;
				$the_query = new WP_Query( array( 'post_type' => 'rooms', 'showposts' => $roomCount ) );
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						$postCount++;
			?>
						
			<!-- BEGIN ROOM WRAP -->
			<div class="rooms-list-item-wrap <?php if ($postCount == sizeof($the_query->posts)) { echo "last-room"; } ?>">
				<div class="rooms-list-item-image-wrap">
					<?php if ( has_post_thumbnail() ) { the_post_thumbnail('room-two',array("class"=>"rooms-list-image")); } else { echo "<div class='main-room-placeholder'></div>"; } ?>
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
				<div class="rooms-list-content-wrap">
					<a href="<?php the_permalink();?>"><div class="rooms-list-header"><?php the_title() ?></div></a>
					<div class="room-list-parametr">
						<div id="room-bed"><?php _e('Beds:','nation'); echo " " . get_post_meta(get_the_ID(),'room_bed',true) ?></div>
						<div id="room-person"><?php _e('Max person:','nation'); $person = get_post_meta(get_the_ID(),'max_person',true); echo " "; for ($i=1;$i <= $person;$i++) { echo "<span class='icon-male'></span>"; } ?></div>
					</div>	
					<div class="rooms-list-content"><?php the_excerpt(); ?></div>
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
			<!-- END ROOM WRAP -->
					
			<?php
					}
				} else {
			?>				
			<p><?php _e("There's no rooms to show!",'nation'); ?></p>
			<?php
				}
				/* Restore original Post Data */
				wp_reset_postdata();
			?>
			<div class="clear"></div>		
		</div>
		<!-- END PAGE CONTENT -->
		
<?php get_footer(); ?> 