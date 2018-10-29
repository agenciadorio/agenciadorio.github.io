<?php
add_shortcode( 'SLGF', 'slgf_ShortCode_load_function' );
function slgf_ShortCode_load_function( $Id ) {
    ob_start();
    if(!isset($Id['id'])) {
        $Id['id'] = "";
    }

    /**
     * Load Lightbox Slider Pro Settings
     */
	 if(!isset($Id['id'])) {
        $Id['id'] = "";
		$SLGF_Show_Gallery_Title  = "yes";
		$SLGF_Show_Image_Label    = "yes";
        $SLGF_Hover_Animation     = "stripe";
        $SLGF_Gallery_Layout      = "col-md-6";
		$SLGF_Thumbnail_Layout    = "same-size";
        $SLGF_Hover_Color         = "#0AC2D2";
		$SLGF_Text_BG_Color       = "#FFFFFF";
		$SLGF_Text_Color          = "#000000";
        $SLGF_Hover_Color_Opacity = "yes";
        $SLGF_Font_Style          = "font-name";
		$SLGF_Box_Shadow          = "yes";
		$SLGF_Custom_CSS          = "";
    } else {
		$SLGF_Id = $Id['id'];
		$SLGF_Settings = "SLGF_Gallery_Settings_".$SLGF_Id;
		$SLGF_Settings = unserialize(get_post_meta( $SLGF_Id, $SLGF_Settings, true));
		if(count($SLGF_Settings)) {
			$SLGF_Show_Gallery_Title  = $SLGF_Settings['SLGF_Show_Gallery_Title'];
			$SLGF_Show_Image_Label    = $SLGF_Settings['SLGF_Show_Image_Label'];
			$SLGF_Hover_Animation     = $SLGF_Settings['SLGF_Hover_Animation'];
			$SLGF_Gallery_Layout      = $SLGF_Settings['SLGF_Gallery_Layout'];
			$SLGF_Thumbnail_Layout    = $SLGF_Settings['SLGF_Thumbnail_Layout'];
			$SLGF_Hover_Color         = $SLGF_Settings['SLGF_Hover_Color'];
			$SLGF_Text_BG_Color       = $SLGF_Settings['SLGF_Text_BG_Color'];
			$SLGF_Text_Color          = $SLGF_Settings['SLGF_Text_Color'];
			$SLGF_Hover_Color_Opacity = $SLGF_Settings['SLGF_Hover_Color_Opacity'];
			$SLGF_Font_Style          = $SLGF_Settings['SLGF_Font_Style'];
			$SLGF_Box_Shadow          = $SLGF_Settings['SLGF_Box_Shadow'];
			$SLGF_Custom_CSS          = $SLGF_Settings['SLGF_Custom_CSS'];
		}
	}

    $RGB = SLGF_RPGhex2rgb($SLGF_Hover_Color);
    $HoverColorRGB = implode(", ", $RGB);
    ?>
	
    <style>
    #slgf_<?php echo $SLGF_Id; ?> .b-link-stroke .b-top-line{
        background: rgba(<?php echo $HoverColorRGB; ?>, <?php if( $SLGF_Hover_Color_Opacity=="yes"){echo "0.5";} else{echo "1.0";} ?>);
    }
    #slgf_<?php echo $SLGF_Id; ?> .b-link-stroke .b-bottom-line{
        background: rgba(<?php echo $HoverColorRGB; ?>, <?php if( $SLGF_Hover_Color_Opacity=="yes"){echo "0.5";} else{echo "1.0";} ?>);
    }

    #slgf_<?php echo $SLGF_Id; ?> .b-wrapper{
        font-family:<?php echo str_ireplace("+", " ", $SLGF_Font_Style); ?>; // real name pass here
    }
	@media (min-width: 992px){
		#slgf_<?php echo $SLGF_Id; ?> .col-md-6 {
			width: 49.97% !important;
		}
		#slgf_<?php echo $SLGF_Id; ?> .col-md-4 {
			width: 33.30% !important;
		}
		#slgf_<?php echo $SLGF_Id; ?> .col-md-3 {
			width: 24.90% !important;
		}
		#slgf_<?php echo $SLGF_Id; ?> .col-md-2 {
			width: 16.60% !important;
		}
	}
	#slgf_<?php echo $SLGF_Id; ?> .slgf_home_portfolio_caption{
		background:<?php echo $SLGF_Text_BG_Color; ?>;
	}
	#slgf_<?php echo $SLGF_Id; ?> .slgf_home_portfolio_caption h3{
		color:<?php echo $SLGF_Text_Color; ?>;
	}
	<?php if($SLGF_Box_Shadow =="yes"){ ?>
	#slgf_<?php echo $SLGF_Id; ?> .img-box-shadow{
		box-shadow: 0 0 6px rgba(0,0,0,.7);
	}
	<?php } else { ?>
	#slgf_<?php echo $SLGF_Id; ?> .slgf_home_portfolio_caption {
			border-bottom: none !important;
		}
	<?php
	}?>
	
	<?php echo $SLGF_Custom_CSS; ?>
    </style>

    <?php
    /**
     * Load All Lightbox Slider Pro Custom Post Type
     */
    $SLGF_CPT_Name = "slgf_slider";
    $AllGalleries = array(  'p' => $Id['id'], 'post_type' => $SLGF_CPT_Name, 'orderby' => 'ASC');
    $loop = new WP_Query( $AllGalleries );
    ?>

    <div  class="gal-container" id="slgf_<?php echo $SLGF_Id; ?>">
		<?php while ( $loop->have_posts() ) : $loop->the_post();?>
			<!--get the post id-->
			<?php $post_id = get_the_ID(); ?>

				<!--Gallery Title-->
				<?php if($SLGF_Show_Gallery_Title == "yes") { ?>
				<div style="font-weight: bolder; padding-bottom:20px; border-bottom:2px solid #cccccc;margin-bottom: 20px">
					<?php echo get_the_title($post_id); ?>
				</div>
				<?php } ?>
				
				<div class="gallery1">
					<?php
					/**
					 * Get All Photos from Lightbox Slider Pro Post Meta
					 */
					$SLGF_AlPhotosDetails = unserialize(get_post_meta( get_the_ID(), 'slgf_all_photos_details', true));
					$TotalImages =  get_post_meta( get_the_ID(), 'slgf_total_images_count', true );
					$i = 1;

					if($TotalImages) {
						foreach($SLGF_AlPhotosDetails as $SLGF_SinglePhotosDetail) {
							$name = $SLGF_SinglePhotosDetail['slgf_image_label'];
							$url  = $SLGF_SinglePhotosDetail['slgf_image_url'];
							$url1 = $SLGF_SinglePhotosDetail['slgf_12_thumb'];
							$url2 = $SLGF_SinglePhotosDetail['slgf_346_thumb'];
							$url3 = $SLGF_SinglePhotosDetail['slgf_12_same_size_thumb'];
							$url4 = $SLGF_SinglePhotosDetail['slgf_346_same_size_thumb'];
							$i++;
							
							if($name == "") {
								// if slide title blank then
								global $wpdb;
								$post_table_prefix = $wpdb->prefix. "posts";
									if(count($attachment = $wpdb->get_col($wpdb->prepare("SELECT `post_title` FROM `$post_table_prefix` WHERE `guid` LIKE '%s'", $url)))) { 
										// attachment title as alt
										$slide_alt = $attachment[0];
										if(empty($attachment[0])) {
										// post title as alt
										$slide_alt = get_the_title( $post_id );
										}  
									}
							}
							else {
								// slide title as alt
								$slide_alt = $name;
								}
							
															
							if($SLGF_Gallery_Layout == "col-md-6") { // two column
								if($SLGF_Thumbnail_Layout == "same-size") $Thummb_Url = $url3; 
								if($SLGF_Thumbnail_Layout == "masonry") $Thummb_Url = $url1;
								if($SLGF_Thumbnail_Layout == "original") $Thummb_Url = $url;
							} 
							if($SLGF_Gallery_Layout == "col-md-4") {// Three column
								if($SLGF_Thumbnail_Layout == "same-size") $Thummb_Url = $url4; 
								if($SLGF_Thumbnail_Layout == "masonry") $Thummb_Url = $url2;
								if($SLGF_Thumbnail_Layout == "original") $Thummb_Url = $url;
							}
						?>
						<div class="<?php echo $SLGF_Gallery_Layout; ?> col-sm-6 wl-gallery" >
							<div class="img-box-shadow">
							
								<?php //  Swipe box	?>
								<a title="<?php echo $name; ?>" class="swipebox_<?php echo $SLGF_Id;?>"  href="<?php echo $url; ?>">
									<div class="b-link-<?php echo $SLGF_Hover_Animation; ?> b-animate-go">
										<img src="<?php echo $Thummb_Url; ?>" class="gall-img-responsive" alt="<?php echo esc_attr($slide_alt); ?>">
									</div>
								</a>

								<!--Gallery Label-->
								<?php if($SLGF_Show_Image_Label == "yes" && $name) {?>
								<div class="slgf_home_portfolio_caption">
									<h3><?php echo $name; ?></h3>
								</div>
								<?php } ?>
							</div>
						</div>
						<?php
						}// end of foreach
					} else { 
						echo __("No Photo Found In Photo Gallery.", WEBLIZAR_SLGF_TEXT_DOMAIN);
					}// end of if else total images
				?>
				</div>
		<?php endwhile; ?>
    </div>

	<!-- Swipe Box-->
	<script type="text/javascript">
	jQuery(document).ready(function(){
		;( function( jQuery ) {
			jQuery( '.swipebox_<?php echo $SLGF_Id;?>' ).swipebox({
							hideBarsDelay:0,
							hideCloseButtonOnMobile : false,
						});
		})( jQuery );
	});

	jQuery('.gallery1').imagesLoaded( function(){
	  jQuery('.gallery1').masonry({
	   itemSelector: '.wl-gallery',
	   isAnimated: true,
	   isFitWidth: true
	  });
	});
	</script>
	
    <?php wp_reset_query(); ?>
    <?php
    return ob_get_clean();
}
?>