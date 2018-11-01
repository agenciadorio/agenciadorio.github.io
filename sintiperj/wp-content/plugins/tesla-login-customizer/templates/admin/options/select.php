<?php
/**
 * Select Option template
 */
?>
<select
    id="<?php echo esc_attr( $id ) ?>"
    name="<?php echo esc_attr( $id ) ?>"
    <?php if(!empty($alert_message)) : ?>
        data-alert-message="<?php echo esc_attr($alert_message) ?>"
    <?php endif; ?>
    >
    <?php foreach( $value as $val => $label ) : ?>
        <option value="<?php echo esc_attr( $val ) ?>" <?php selected( get_option( $id ) ? get_option( $id ) : $std , $val ) ?>><?php echo esc_html($label) ?></option>
    <?php endforeach; ?>
</select>
<?php if( !empty($descr) ) printf('<p class="description tt-option-description">%s</p>', $descr ); ?>