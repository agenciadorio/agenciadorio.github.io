<?php
/**
 * Font picker Option template
 */
?>
<select
    id="<?php echo esc_attr( $id ) ?>"
    name="<?php echo esc_attr( $id ) ?>"
    class="tt-font-picker"
    >
        <option value=""></option>
        <?php foreach( $fonts as $font_obj ) : ?>
            <option value="<?php echo esc_attr( $font_obj->family ) ?>" <?php selected( get_option( $id ) ? get_option( $id ) : $std , $font_obj->family ) ?>><?php echo esc_html($font_obj->family) ?></option>
        <?php endforeach; ?>
</select>