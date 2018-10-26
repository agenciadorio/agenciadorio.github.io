<?php
/**
 * Image Option template
 */
$hidden = get_option( $id ) || $std ? '' : 'tt-hide';
?>
<div class="tt-image-picker-box">
    <span class="tt-image-box <?php echo esc_attr($hidden) ?>">
        <img
            class="tt-image-picker-preview"
            src="<?php echo esc_url( get_option( $id ) ? esc_attr( get_option( $id ) ) : $std ) ?>"
            alt="tt preview"
            >
        <i class="dashicons dashicons-update"></i>
    </span>
    <input
        type="url"
        id="<?php echo esc_attr( $id ) ?>"
        class="tt-image-picker-input"
        name="<?php echo esc_attr( $id ) ?>"
        value="<?php echo get_option( $id ) ? esc_attr( get_option( $id ) ) : $std ; ?>"
        placeholder="<?php echo esc_attr( $placeholder ) ?>"
        >
    <span class="spinner"></span>
    <div class="tt-image-picker-nav">
        <button
            type="button"
            class="button upload-button tt-image-picker-button"
            data-uploader-title="<?php esc_attr_e('Select or upload image', 'tt_login'); ?>"
            ><?php _e('Select Image', 'tt_login'); ?></button>
        <button
            type="button"
            class="button remove-button tt-image-remove-button <?php echo esc_attr($hidden) ?>"
            ><?php _e('Remove Image', 'tt_login'); ?></button>
    </div>
</div>
<?php if (!empty($descr)) printf('<p class="tt-option-description">%s</p>', $descr) ?>