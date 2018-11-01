<?php
/**
 * Text Option template
 */
?>
<input
    type="text"
    id="<?php echo esc_attr( $id ) ?>"
    name="<?php echo esc_attr( $id ) ?>"
    value="<?php echo get_option( $id ) ? esc_attr( get_option( $id ) ) : $std ; ?>"
    placeholder="<?php echo esc_attr( $placeholder ) ?>"
    class="regular-text"
    >
<p class="description tt-option-description"><?php print $descr ?></p>