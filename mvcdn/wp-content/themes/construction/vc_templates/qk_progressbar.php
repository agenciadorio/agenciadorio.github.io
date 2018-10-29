<?php
extract(shortcode_atts(array(
	'type' => '',
	'title' => '',
    'value' => 90,
    'color_title' => '',
    'color' => '',
    'color_percent' => '',
    'el_class' => ''
), $atts));
$is_normal = empty( $type );
$type = $is_normal ? '' : '-thin';

$wrap_class = $percent_class = $title_class = array();

$wrap_class[] = 'm-pgs-bar'. $type;
$wrap_class[] = $el_class;
$wrap_class = construction_get_style_color( $wrap_class, $color, 'm-pgs-bar-color', 'background-color:');

$percent_class[] = 'm-pgs-bar'. $type .'-percent';
$percent_class = construction_get_style_color( $percent_class, $color_percent, 'm-pgs-bar-color', 'background-color:');

$title_class[] = 'm-pgs-bar'. $type .'-title';
$title_class = construction_get_style_color( $title_class, $color_title, 'm-pgs-bar-color', 'color:');
?>

<span class="<?php echo implode(' ', $wrap_class );?>">
	<?php if( $is_normal ):?>
	<h5 class="<?php echo implode(' ', $title_class );?>"><?php echo esc_attr( $title );?></h5>
	<?php endif; ?>
	<span class="<?php echo implode(' ', $percent_class );?>" data-percent="<?php echo (float) $value;?>"></span>
	<?php if( ! $is_normal ):?>
	<h5 class="<?php echo implode(' ', $title_class );?>"><?php echo esc_attr( $title );?></h5>
	<?php endif; ?>
</span>