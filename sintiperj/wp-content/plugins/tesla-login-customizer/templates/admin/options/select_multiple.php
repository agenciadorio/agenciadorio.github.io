<?php
/**
 * Multiple Select Option template
 */
?>
<select multiple
    id="<?php echo esc_attr( $id ) ?>"
    name="<?php echo esc_attr( $id );?>[]"
    class="tt-multiple-select"
    placeholder="<?php echo esc_attr($placeholder) ?>"
    >
    <?php
    if(get_option( $id ))
        $current_value = get_option( $id );
    elseif( !empty( $std ) )
        $current_value = $std;
    else
        $current_value = array();

    foreach( $value as $val => $label ) : ?>
        <option value="<?php echo esc_attr( $val ) ?>" <?php selected( true , in_array($val,$current_value, true) ) ?>>
            <?php echo esc_html($label) ?>
        </option>
    <?php endforeach; ?>
</select>
