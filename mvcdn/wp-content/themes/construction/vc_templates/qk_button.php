<?php
extract(shortcode_atts(array(
    'title' => '',
	'size' => '',
	'bg' => 'btn-bg-1',
	'link'=>'#',
	'el_class' =>'',
	'css' => '',
	'animate' => '',
	'wow_duration' => '0.8s',
	'wow_delay' => '0.8s'
), $atts));

$class = array(
	'btn btn-1',
	vc_shortcode_custom_css_class( $css )
);

$wrap_attrs = array();
// added animated wow
if( ! empty( $animate ) ){

	$class[] = 'wow';

	$class[] = $animate;

	if( ! empty( $wow_duration ) ){
		$wrap_attrs[] = 'data-wow-duration='. esc_attr( $wow_duration );
	}

	if( ! empty( $wow_delay ) ){
		$wrap_attrs[] = 'data-wow-delay='. esc_attr( $wow_delay );
	}
}

if( ! empty( $el_class ) ){
	$class = construction_get_button_class( $class, $el_class);
}

if( ! empty( $size ) ) $class[] = $size;

$class[] = $bg;

?>
<a <?php echo construction_join( $wrap_attrs );?> href="<?php echo esc_url( $link );?>" class="<?php echo esc_attr(implode(' ', $class ) );?>">
	<?php
		echo esc_attr( $title );
	?>
</a>