<?php
/**
 * Number group Option template
 */
$group_value = get_option($id);?>
<div>
    <?php foreach($group as $input) :
        $input_value = !empty($group_value[$input]) ? $group_value[$input] : ''?>
        <label>
            <input
                type="number"
                id="<?php echo esc_attr( $id . $input ) ; ?>"
                name="<?php echo esc_attr( $id ) ; echo "[" . esc_attr($input) . "]" ?>"
                value="<?php echo $input_value ; ?>"
                placeholder="<?php echo esc_attr( $placeholder ) ?>"
                <?php if(isset($min)) : ?>
                    min="<?php echo esc_attr($min) ?>"
                <?php endif ?>
                <?php if(!empty($max)) : ?>
                    max="<?php echo esc_attr($max) ?>"
                <?php endif ?>
                >
            <?php echo esc_html($input) ?>
        </label>
    <?php endforeach ; ?>
</div>
<p class="tt-option-description description"><?php print $descr ?></p>