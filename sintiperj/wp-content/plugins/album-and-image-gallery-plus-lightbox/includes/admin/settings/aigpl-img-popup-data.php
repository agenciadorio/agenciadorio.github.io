<?php
/**
 * Popup Image Data HTML
 *
 * @package Album and Image Gallery Plus Lightbox
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

$prefix = AIGPL_META_PREFIX;

// Taking some values
$alt_text 			= get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
$attachment_link 	= get_post_meta( $attachment_id, $prefix.'attachment_link', true );
?>

<div class="aigpl-popup-title"><?php _e('Edit Image', 'album-and-image-gallery-plus-lightbox'); ?></div>
	
<div class="aigpl-popup-body">

	<form method="post" class="aigpl-attachment-form">
		
		<?php if( !empty($attachment_post->guid) ) { ?>
		<div class="aigpl-popup-img-preview">
			<img src="<?php echo $attachment_post->guid; ?>" alt="" />
		</div>
		<?php } ?>
		<a href="<?php echo get_edit_post_link( $attachment_id ); ?>" target="_blank" class="button right"><i class="dashicons dashicons-edit"></i> <?php _e('Edit Image From Attachment Page', 'album-and-image-gallery-plus-lightbox'); ?></a>

		<table class="form-table">
			<tr>
				<th><label for="aigpl-attachment-title"><?php _e('Title', 'album-and-image-gallery-plus-lightbox'); ?>:</label></th>
				<td>
					<input type="text" name="aigpl_attachment_title" value="<?php echo aigpl_esc_attr($attachment_post->post_title); ?>" class="large-text aigpl-attachment-title" id="aigpl-attachment-title" />
					<span class="description"><?php _e('Enter image title.', 'album-and-image-gallery-plus-lightbox'); ?></span>
				</td>
			</tr>

			<tr>
				<th><label for="aigpl-attachment-alt-text"><?php _e('Alternative Text', 'album-and-image-gallery-plus-lightbox'); ?>:</label></th>
				<td>
					<input type="text" name="aigpl_attachment_alt" value="<?php echo aigpl_esc_attr($alt_text); ?>" class="large-text aigpl-attachment-alt-text" id="aigpl-attachment-alt-text" />
					<span class="description"><?php _e('Enter image alternative text.', 'album-and-image-gallery-plus-lightbox'); ?></span>
				</td>
			</tr>

			<tr>
				<th><label for="aigpl-attachment-caption"><?php _e('Caption', 'album-and-image-gallery-plus-lightbox'); ?>:</label></th>
				<td>
					<textarea name="aigpl_attachment_caption" class="large-text aigpl-attachment-caption" id="aigpl-attachment-caption"><?php echo aigpl_esc_attr($attachment_post->post_excerpt); ?></textarea>
					<span class="description"><?php _e('Enter image caption.', 'album-and-image-gallery-plus-lightbox'); ?></span>
				</td>
			</tr>

			<tr>
				<th><label for="aigpl-attachment-desc"><?php _e('Description', 'album-and-image-gallery-plus-lightbox'); ?>:</label></th>
				<td>
					<textarea name="aigpl_attachment_desc" class="large-text aigpl-attachment-desc" id="aigpl-attachment-desc"><?php echo aigpl_esc_attr($attachment_post->post_content); ?></textarea>
					<span class="description"><?php _e('Enter image description.', 'album-and-image-gallery-plus-lightbox'); ?></span>
				</td>
			</tr>

			<tr>
				<th><label for="aigpl-attachment-link"><?php _e('Image Link', 'album-and-image-gallery-plus-lightbox'); ?>:</label></th>
				<td>
					<input type="text" name="aigpl_attachment_link" value="<?php echo esc_url($attachment_link); ?>" class="large-text aigpl-attachment-link" id="aigpl-attachment-link" />
					<span class="description"><?php _e('Enter image link. e.g http://wponlinesupport.com', 'album-and-image-gallery-plus-lightbox'); ?></span>
				</td>
			</tr>

			<tr>
				<td colspan="2" align="right">
					<div class="aigpl-success aigpl-hide"></div>
					<div class="aigpl-error aigpl-hide"></div>
					<span class="spinner aigpl-spinner"></span>
					<button type="button" class="button button-primary aigpl-save-attachment-data" data-id="<?php echo $attachment_id; ?>"><i class="dashicons dashicons-yes"></i> <?php _e('Save Changes', 'album-and-image-gallery-plus-lightbox'); ?></button>
					<button type="button" class="button aigpl-popup-close"><?php _e('Close', 'album-and-image-gallery-plus-lightbox'); ?></button>
				</td>
			</tr>
		</table>
	</form><!-- end .aigpl-attachment-form -->

</div><!-- end .aigpl-popup-body -->