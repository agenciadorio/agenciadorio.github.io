<?php
extract(shortcode_atts(array(
	'title' => '',
	'btn_name' => '',
    'el_class' => '',
    'btn_link' => '#',
    'css' => ''

), $atts));

$class = array(
	'banner',
	vc_shortcode_custom_css_class( $css )
);
$class[] = $el_class;
?>
<!-- Banner -->
<div class="<?php echo construction_join( $class );?>">
	<div class="pull-left">
		<div class="left-btn">
		</div>
	</div>
	<h3 class="wow fadeInLeft" data-wow-delay="1s"><?php echo esc_attr( $title );?></h3>
	<a href="<?php echo esc_url( $btn_link );?>" data-wow-delay="1s" class="btn btn-1 btn-bg-4 btn-sm wow fadeInRight"><?php echo esc_attr( $btn_name );?></a>
	<div class="pull-right">
		<div class="right-btn">
		</div>
	</div>
</div>