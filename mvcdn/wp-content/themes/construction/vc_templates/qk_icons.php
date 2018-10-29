<?php
extract(shortcode_atts(array(
	'tpl' => 'tpl1',
    'icon' => '',
    'title' => '',
	'css' => '',
	'el_class' =>'',
	
), $atts));
$class = array('ui-icon-box');
$class[] = $el_class;
$class[] = $tpl;
$class[] = vc_shortcode_custom_css_class( $css );
if( $tpl !== 'tpl1'):
	$class[] = 'dx-item';
?>
	<div class="<?php echo construction_join($class);?>">
		<?php if( ! empty( $icon ) ):?>
			<i class="<?php echo esc_attr( $icon );?>"></i>
		<?php endif;?>
		<h3><?php the_title(); ?></h3>
		<?php if( ! empty( $content )) echo apply_filters( 'the_content', $content );?>
	</div>
<?php else:
	$class[] = 'wcu-item';
?>
	<div class="<?php echo construction_join($class);?>">
		<div class="wcu-item-inner">
			<?php if( ! empty( $icon ) ):?>
				<i class="<?php echo esc_attr( $icon );?>"></i>
			<?php endif;?>
			<h3><?php the_title(); ?></h3>
			<?php if( ! empty( $content )) echo apply_filters( 'the_content', $content );?>
		</div>
	</div>
<?php endif; ?>