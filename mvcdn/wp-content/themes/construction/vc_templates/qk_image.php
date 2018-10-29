<?php
extract(shortcode_atts(array(
	'tpl'=>'imgframe1',
    'title' => '',
    'img_url'=>'',
    'el_class' => ''
), $atts));


$class[] = $el_class;
$img_url = esc_url(wp_get_attachment_url(intval($img_url)));
?>
<div class="<?php echo esc_attr( implode( ' ', $class ) );?>">
	<?php if($tpl == 'imgframe3' || $tpl == 'imgframe6') {?>
		<div class="<?php echo esc_attr($tpl);?>">
			<img src="<?php echo esc_attr($img_url);?>" alt=""/>
			<?php if($tpl == 'imgframe3'){?>
				<strong><?php echo esc_html($title);?></strong>
			<?php } ?>
		</div>
	<?php } else { ?>
		<img src="<?php echo esc_url($img_url);?>" class="<?php echo esc_attr($tpl);?>" />
	<?php } ?>
</div>