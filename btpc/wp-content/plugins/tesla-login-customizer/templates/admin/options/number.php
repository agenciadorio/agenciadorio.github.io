<?php
/**
 * Number Option template
 */
?>
<?php if(!empty($label)) : ?>
    <label>
<?php endif; ?>
    <input
        type="number"
        id="<?php echo esc_attr( $id ) ?>"
        name="<?php echo esc_attr( $id ) ?>"
        value="<?php echo get_option( $id ) ? esc_attr( get_option( $id ) ) : $std ; ?>"
        placeholder="<?php echo esc_attr( $placeholder ) ?>"
        <?php if(!empty($min)) : ?>
            min="<?php echo esc_attr($min) ?>"
        <?php endif ?>
        <?php if(!empty($max)) : ?>
            max="<?php echo esc_attr($max) ?>"
        <?php endif ?>
        >
    <?php if(!empty($label)) : ?>
            <span><?php echo esc_html($label) ?></span>
        </label>
    <?php endif; ?>
<p class="tt-option-description"><?php echo esc_html($descr) ?></p>