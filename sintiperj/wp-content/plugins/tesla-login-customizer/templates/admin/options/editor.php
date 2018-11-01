<?php
/**
 * Editor Option template
 */
?>
<textarea
    id="<?php echo esc_attr( $id ) ?>"
    name="<?php echo esc_attr( $id ) ?>"
    class="tt-editor<?php if(!empty($class)) echo " " . esc_attr( $class ) ?>"
    cols=""
    rows=""
    data-mode="<?php echo isset($mode) ? esc_attr($mode) : 'css' ?>"
    ><?php echo get_option( $id ) ? esc_attr( get_option( $id ) ) : $std ; ?></textarea>