<?php

extract(shortcode_atts(array(
    'to' => '100',
    'speed' => '10000',
    'timer_color' => '',
    'title' => '',
    'title_color' => '',
    'animate' => '',
    'duration' => '0.8s',
    'delay' => '0.8s',
	'el_class' =>''

), $atts));

wp_enqueue_script('counter');
$class = array('counterup-item');
$class[] = $el_class;

$attrs = '';

$main_color = construction_get_option('main-color');

if( ! empty( $animate ) ){
	$class[] = 'wow';
	$class[] = $animate;
	
	if( ! empty( $duration ) ){
		$attrs .= ' data-duration="'. esc_attr( $duration ) .'"';
	}

	if( ! empty( $delay ) ){
		$attrs .= ' data-delay="'. esc_attr( $delay ) .'"';
	}
}

$timer = array('counter');

$ctitle = array('counter-item-text');
$style = array('timer'=>'timer_color', 'ctitle'=>'title_color' );
foreach( $style as $arr => $color ){
	if( $$color === $main_color ){
		array_push( $$arr, 'default-color' );
	}elseif( ! empty($$color ) ){
		array_push( $$arr, '" style="color:'. esc_attr($$color) );
	}
}

?>
<!-- COUNTER ITEM -->
<div class="<?php echo esc_attr( implode(' ', $class ) );?>" <?php echo esc_attr($attrs);?>>
	<h3 class="<?php echo implode(' ', $timer );?>" data-speed="<?php echo (int) $speed;?>"><?php echo (int) $to;?></h3>
	<?php if( ! empty( $title ) ):?>
	<p><span class="<?php echo implode(' ', $ctitle );?>"><?php echo esc_attr( $title );?></span></p>
	<?php endif;?>
</div>



