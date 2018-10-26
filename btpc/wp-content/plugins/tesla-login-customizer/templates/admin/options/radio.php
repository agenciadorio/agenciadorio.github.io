<?php
/**
 * Radio Option template
 */
?>
<div>
    <?php foreach( $value as $val => $label ) :
        if(is_array($label)){
            $img_url = $label[1];
            $label = $label[0];
        }else{
            $img_url = null;
        } ?>
        <label class="tt-radio-label<?php if(isset($img_url)) echo ' tt-radio-image'?>">
            <input
                type="radio"
                id="<?php echo esc_attr( $id . $label ) ?>"
                data-group-id="<?php echo esc_attr( $id ) ?>"
                name="<?php echo esc_attr( $id ) ?>"
                value="<?php echo esc_attr( $val ) ?>"
                <?php checked( $val ,  get_option( $id ) ? get_option( $id ) : $std ) ?>
                <?php if(!empty($alert_message)) : ?>
                    data-alert-message="<?php echo esc_attr($alert_message) ?>"
                <?php endif; ?>
                >
            <?php if(isset($img_url)) : ?>
                <img src="<?php echo esc_url($img_url) ?>" alt="radio image" title="<?php echo esc_attr($label) ?>">
            <?php endif; ?>
            <span><?php echo esc_html($label) ?></span>
        </label>
    <?php endforeach; ?>
</div>
<?php printf('<p class="tt-option-description">%s</p>',$descr) ?>
