<?php
/**
 * ColorPicker Option template
 */
?>
<input
    type="text"
    class="tt-colorpicker"
    id="<?php echo esc_attr( $id ) ?>"
    name="<?php echo esc_attr( $id ) ?>"
    value="<?php echo esc_attr( get_option( $id ) ); ?>"
    data-default-color="<?php echo esc_attr( $std ) ?>"
    data-alpha="true"
    >
<p class="description tt-option-description"><?php echo esc_html($descr) ?></p>