<?php
/**
 * TextArea Option template
 */
?>
<textarea
    id="<?php echo esc_attr( $id ) ?>"
    name="<?php echo esc_attr( $id ) ?>"
    value="<?php echo esc_attr( get_option( $id ) ); ?>"
    cols=""
    rows=""
    ><?php echo get_option( $id ) ? esc_attr( get_option( $id ) ) : $std ; ?></textarea>