<?php
/**
 * Design 1 HTML
 * 
 * @package Album and Image Gallery Plus Lightbox
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
?>

<div class="<?php echo $wrpper_cls; ?>" data-item-index="<?php echo $loop_count; ?>">
	<div class="aigpl-inr-wrp">
		<div class="aigpl-img-wrp" style="<?php echo $height_css; ?>">
			<?php if($image_link) { ?>
			<a class="aigpl-img-link" href="<?php echo $image_link; ?>" target="<?php echo $link_target; ?>">
				<img class="aigpl-img" src="<?php echo $gallery_img_src ?>" alt="<?php echo aigpl_esc_attr( $image_alt_text ); ?>" />
			</a>
			<?php } else { ?>
				<img class="aigpl-img" src="<?php echo $gallery_img_src ?>" alt="<?php echo aigpl_esc_attr( $image_alt_text ); ?>" />
			<?php } ?>

			<?php if( $show_caption == 'true' && $gallery_post->post_excerpt ) { ?>
			<div class="aigpl-img-caption">
				<?php echo $gallery_post->post_excerpt; ?>
			</div>
			<?php } ?>
		</div>

		<?php if( $show_title == 'true' ) { ?>
		<div class="aigpl-img-title aigpl-center"><?php echo $gallery_post->post_title; ?></div>
		<?php } ?>
		
		<?php if( $show_description == 'true' && !empty($gallery_post->post_content) ) { ?>
		<div class="aigpl-img-desc aigpl-center"><?php echo wpautop($gallery_post->post_content); ?></div>
		<?php } ?>
	</div>
</div>