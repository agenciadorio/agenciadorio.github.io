<?php
/**
 * Handles Post Setting metabox HTML
 *
 * @package Album and Image Gallery Plus Lightbox
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

global $post;

$prefix = AIGPL_META_PREFIX; // Metabox prefix

$gallery_imgs 	= get_post_meta( $post->ID, $prefix.'gallery_imgs', true );
$no_img_cls		= !empty($gallery_imgs) ? 'aigpl-hide' : '';
?>

<table class="form-table aigpl-post-sett-table">
	<tbody>
		<tr valign="top">
			<th scope="row">
				<label for="aigpl-gallery-imgs"><?php _e('Choose Gallery Images', 'album-and-image-gallery-plus-lightbox'); ?></label>
			</th>
			<td>
				<button type="button" class="button button-secondary aigpl-img-uploader" id="aigpl-gallery-imgs" data-multiple="true" data-button-text="<?php _e('Add to Gallery', 'album-and-image-gallery-plus-lightbox'); ?>" data-title="<?php _e('Add Images to Gallery', 'album-and-image-gallery-plus-lightbox'); ?>"><i class="dashicons dashicons-format-gallery"></i> <?php _e('Gallery Images', 'album-and-image-gallery-plus-lightbox'); ?></button>
				<button type="button" class="button button-secondary aigpl-del-gallery-imgs"><i class="dashicons dashicons-trash"></i> <?php _e('Remove Gallery Images', 'album-and-image-gallery-plus-lightbox'); ?></button><br/>
				
				<div class="aigpl-gallery-imgs-prev aigpl-imgs-preview aigpl-gallery-imgs-wrp">
					<?php if( !empty($gallery_imgs) ) {
						foreach ($gallery_imgs as $img_key => $img_data) {

							$attachment_url 		= wp_get_attachment_thumb_url( $img_data );
							$attachment_edit_link	= get_edit_post_link( $img_data );
					?>
							<div class="aigpl-img-wrp">
								<div class="aigpl-img-tools aigpl-hide">
									<span class="aigpl-tool-icon aigpl-edit-img dashicons dashicons-edit" title="<?php _e('Edit Image in Popup', 'album-and-image-gallery-plus-lightbox'); ?>"></span>
									<a href="<?php echo $attachment_edit_link; ?>" target="_blank" title="<?php _e('Edit Image', 'album-and-image-gallery-plus-lightbox'); ?>"><span class="aigpl-tool-icon aigpl-edit-attachment dashicons dashicons-visibility"></span></a>
									<span class="aigpl-tool-icon aigpl-del-tool aigpl-del-img dashicons dashicons-no" title="<?php _e('Remove Image', 'album-and-image-gallery-plus-lightbox'); ?>"></span>
								</div>
								<img class="aigpl-img" src="<?php echo $attachment_url; ?>" alt="" />
								<input type="hidden" class="aigpl-attachment-no" name="aigpl_img[]" value="<?php echo $img_data; ?>" />
							</div><!-- end .aigpl-img-wrp -->
					<?php }
					} ?>
					
					<p class="aigpl-img-placeholder <?php echo $no_img_cls; ?>"><?php _e('No Gallery Images', 'album-and-image-gallery-plus-lightbox'); ?></p>

				</div><!-- end .aigpl-imgs-preview -->
				<span class="description"><?php _e('Choose your desired images for gallery. Hold Ctrl key to select multiple images at a time.', 'album-and-image-gallery-plus-lightbox'); ?></span>
			</td>
		</tr>
	</tbody>
</table><!-- end .aigpl-post-sett-table -->