<?php
/**
 * Album Design 1 HTML
 * 
 * @package Album and Image Gallery Plus Lightbox
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
?>


<div class="<?php echo $wrpper_cls; ?>">
	
		<div class="aigpl-inr-wrp">
		<div class="aigpl-img-wrp" style="">
			<a class="aigpl-img-link" href="<?php echo $album_image; ?>#inicio" target="<?php echo $album_link_target; ?>">
				<?php if($image_link) { ?>
				<img class="aigpl-img" src="<?php echo $image_link; ?>"   title="<?php echo $post->post_title; ?>"  alt="<?php _e('Album Image', 'album-and-image-gallery-plus-lightbox'); ?>" /> </li>

				<?php } ?>
			</a>
		</div>


<!-- end .aigpl-img-wrp -->

		
		<?php if( $album_title == 'true' ) { ?>
		<div class="aigpl-img-title aigpl-center"><?php echo $post->post_title; ?></a></div>
<br/><center> <a class="button" href="<?php echo $album_image; ?>#inicio">Conhecer > </a></center>
		<?php } ?>
		
		<?php if( !empty($total_photo_lbl) ) { ?>
		<div class="aigpl-img-count aigpl-center"></div>
		<?php } ?>
		
		<?php if( $album_description == 'true' ) {
				if( $album_full_content == 'true' ) { ?>
					<div class="aigpl-img-desc aigpl-center"><?php echo wpautop($post->post_content); ?></div>
		<?php } else { ?>
					<div class="aigpl-img-desc aigpl-center"><?php echo aigpl_get_post_excerpt( $post->ID, get_the_content(), $words_limit, $content_tail ); ?></div>
		<?php } } ?>
		
	</div><!-- end .aigpl-inr-wrp -->
</div><!-- end .aigpl-columns -->