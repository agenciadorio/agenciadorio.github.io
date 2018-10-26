<?php
/**
 * URL Option template
 */
?>
<div style="position: relative">
    <input
        type="url"
        id="<?php echo esc_attr( $id ) ?>"
        name="<?php echo esc_attr( $id ) ?>"
        value="<?php echo get_option( $id ) ? esc_attr( get_option( $id ) ) : $std ; ?>"
        placeholder="<?php echo esc_attr( $placeholder ) ?>"
        class="regular-text"
        >
    <span class="dashicons dashicons-admin-links wp-ui-text-primary"></span>
</div>
<p class="description tt-option-description"><?php echo esc_html($descr) ?></p>