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

		<div class="aigpl-img-wrp" style="<?php echo $album_height_css; ?>">
			<a class="aigpl-img-link" href="<?php echo $album_image; ?>" target="<?php echo $album_link_target; ?>">
				<?php if($image_link) { ?>
				<img class="aigpl-img" src="<?php echo $image_link; ?>" alt="<?php the_title_attribute(); ?>" />
				<?php } ?>
			</a>
		</div>

		<?php if( $album_title == 'true' ) { ?>
		<div class="aigpl-img-title aigpl-center"><?php echo $post->post_title; ?></div>
		<?php } ?>

		<?php if( !empty($total_photo_lbl) ) { ?>
		<div class="aigpl-img-count aigpl-center"><?php echo $total_photo_lbl; ?></div>
		<?php } ?>

		<?php if( $album_description == 'true' ) {
				if( $album_full_content == 'true' ) { ?>
					<div class="aigpl-img-desc aigpl-center"><?php echo wpautop($post->post_content); ?></div>
		<?php } else { ?>
					<div class="aigpl-img-desc aigpl-center"><?php echo aigpl_get_post_excerpt( $post->ID, get_the_content(), $words_limit, $content_tail ); ?></div>
		<?php } } ?>	
	</div>
</div>