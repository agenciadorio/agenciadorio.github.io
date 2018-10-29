<?php
$atts = shortcode_atts(array(
    'button_link' => '#',
    'button_name' => esc_html__('GO TO THE BLOG','construction'),
    'el_class' => ''
), $atts);
extract( $atts );

$class = array('m-link-section default-bg');
$class[] = $el_class;
?>
<section class="<?php echo construction_join( $class );?>">
	<?php if( ! empty( $button_link ) ): ?>
		<a href="<?php echo esc_url( $button_link );?>"></a>
	<?php endif; ?>
	<h3 class="m-link-section-title upper">
		<?php echo esc_attr( $button_name ); ?>
	</h3>
</section>