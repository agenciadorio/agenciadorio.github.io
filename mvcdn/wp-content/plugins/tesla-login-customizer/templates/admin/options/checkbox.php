<?php
/**
 * Checkbox Option template
 */
?>
<?php if(!empty($label)) : ?>
    <label>
<?php endif; ?>
<input
    type="checkbox"
    id="<?php echo esc_attr( $id ) ?>"
    name="<?php echo esc_attr( $id ) ?>"
    value="1"
    <?php checked( 1 ,  get_option( $id ) ) ?>
    >
    <?php if(!empty($label)) : ?>
            <span><?php echo esc_html($label) ?></span>
        </label>
    <?php endif; ?>
<?php if (!empty($descr)) printf('<p class="description tt-option-description">%s</p>', $descr) ?>