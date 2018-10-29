<?php 

/*
Template Name: Gallery with header
*/

$galleryImage = ot_get_option('gallery_image_count');

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
		<div class="container gallery-wrap" id="is-gallery">
	
			<?php 
				$count=0;
				$the_query = new WP_Query( array( 'post_type' => 'gallery', 'showposts' => $galleryImage ) );
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						$count++;
			?>
						
			<div class="gallery-item-wrap <?php if ($count%3==0) { echo 'gallery-three-last'; } ?>">
				<div class="gallery-image-wrap">
					<a href="<?php $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'gallery-full'); echo $thumb[0]; ?>" style="position:relative;">
						<div class="gallery-overlay-wrap">
							<?php if ( has_post_thumbnail() ) {
								the_post_thumbnail( 'room-normal', array('class' => 'gallery-image') );
							} ?>
							<div class="room-main-list-overlay">
								<div class="room-overlay-content">
									<div class="room-overlay-readmore"><span class="icon-search"></span></div>
								</div>
							</div>
						</div>
					</a>
				</div>
				<div class="gallery-content-wrap">
					<div class="gallery-header"><?php the_title(); ?></div>
				</div>
			</div>
					
			<?php
				} } else {
			?>				
			<p><?php _e("There's no picture in gallery to show!"); ?></p>
			<?php
				}
				/* Restore original Post Data */
				wp_reset_postdata();
			?>		
				
		</div>
		<!-- END PAGE CONTENT -->
		
<?php get_footer(); ?> 