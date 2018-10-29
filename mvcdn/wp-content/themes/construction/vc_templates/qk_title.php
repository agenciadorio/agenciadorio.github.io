<?php
extract(shortcode_atts(array(
	'title' => '',
	'title_color' => '',
    'el_class' => '',
    'el_align' => '',
    'css' => '',
    'animate' => '',
	'wow_duration' => '0.8s',
	'wow_delay' => '0.8s'
), $atts));

$class = array(
	'heading',
	vc_shortcode_custom_css_class( $css )
);
$class[] = $el_class;

$title_class = array('section-title');
if( ! empty( $title_color ) ){
	$title_class = construction_get_style_color( $title_class, $title_color, "default-color" );
}
if( ! empty( $el_align ) ) $class[] = $el_align;

$wrap_attrs = construction_get_animation( $class, $animate, $wow_duration, $wow_delay );

?>

<div <?php echo construction_join( $wrap_attrs );?> class="<?php echo esc_attr( implode(' ', $class ) );?>">
	<h2 class="<?php echo implode(' ', $title_class );?>"><?php echo esc_attr( $title );?></h2>
	<?php if( ! empty( $content ) ):?>
		<?php echo apply_filters( 'the_content', $content ); ?>
	<?php endif;?>
</div>