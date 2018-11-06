<?php  

/**
 *
 *	Filename: gridstack-customslideshow.php
 *	Function: adds custom slideshow templates to the theme
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
if(!function_exists("qt_gridstack_customslideshow_shortcode")){
	function qt_gridstack_customslideshow_shortcode($atts){
		/**
		 *	Defaults
		 * 	All parameters can be bypassed by same attribute in the shortcode
		 */
		extract( shortcode_atts( array(
			'stackid' => '',
			// 'quantity' => 5,
			// 'posttype' => 'post',
			// 'orderby' => 'post_date',
			// 'taxonomy' => false,
			// 'term_ids' => false,
			'images' => false,
			'size' => 'widescreen',
			'links' => false,
			'captions' => false,
			'transition' => "500", 
			'interval'	=> '6000',
			'indicators' => false,
			'align' => "center",
			'arrows' => false,
			'full_width' => false,
			'preview' => false,
			'proportion' => 'original',
			'excerpt' => false
		), $atts ) );

		/**
		 *	If there are no pictures we end it here
		 */
		if(!$images) {
			return esc_attr__("No images uploaded for the custom slideshow", "qt-extensions-suite");
		} 





		$images_array = explode(',', $images);

		if(!$links) {
			$links_array = array();
		} else {
			$links_array = explode(',', $links);
		}





		if(!$captions) {
			$captions_array = array();
		} else {
			$captions_array = explode(',', $captions);
		}

		// echo '$proportion: '.$proportion;


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
		data-100p-top="opacity:0;" data-60p-top="opacity:0;" data-50p-top="opacity:1;"
		>
			<ul class="slides">
				<?php 
				$i = 0;
				foreach($images_array as $image){

					$imgsize = $size;
					if($size = 'widescreen') {
						$imgsize = 'qantumthemes_widescreen';
					}
					$image_attributes = wp_get_attachment_image_src( $image, $imgsize );
					if ( $image_attributes ) : ?> 
						<li class="slide">

							<?php  
							 /**
					         *  Image
					         */
					        ?>
							<img src="<?php echo $image_attributes[0]; ?>" width="<?php echo $image_attributes[1]; ?>" height="<?php echo $image_attributes[2]; ?>" />
					        
					        <?php 
					        /**
					         *  Caption
					         */
					        if(array_key_exists($i, $captions_array)) { 
					        ?>
						        <div class="caption vcenter">
						        	<div class="containesr">
						          		<h2 class=" qt-border-accent">
							          		<?php if(array_key_exists($i, $links_array)) { ?><a href="<?php esc_attr($links_array[$i]); ?>"><?php } ?>
						          				<?php echo esc_attr($captions_array[$i]); ?>
						          			<?php if(array_key_exists($i, $links_array)) { ?></a><?php } ?>
							          	</h2>
						          	</div>
						        </div>
					        <?php } ?>

						</li> 
					<?php endif; ?>
				<?php } ?> 
			</ul>

			<?php if(true === $arrows || "true" === $arrows ){ ?>
			<div class="nav hide-on-med-and-down">
				<div class="prev">
					<span class="qt-btn-rhombus btn"><i class="fa fa-chevron-left"></i></span>
				</div>
				<div class="next">
					<span class="qt-btn-rhombus btn"><i class="fa fa-chevron-right"></i></span>
				</div>
			</div>
			<?php } ?>
		</div>
		<?php
		wp_reset_postdata();
		return ob_get_clean();
	}
}
add_shortcode( "qt-customslideshow", "qt_gridstack_customslideshow_shortcode");









