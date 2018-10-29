<?php
extract(shortcode_atts(array(
    'brands' => '',
    'perview' => 6,
    'thumb_size' => 'full',
    'links'=> '',
    'el_class' => ''
), $atts));
$class = array('owl row');
$class[] = $el_class;
if( ! empty( $brands ) ):
	$brands = explode(',', $brands);
	$perview = (int) $perview;
	$perview_small = $perview > 4 ? 4 : $perview;
	$perview_tablet = $perview > 3 ? 3 : $perview;
	if( ! empty( $links ) ){
		$links = explode(',', $links);
	}
?>
<div class="<?php echo construction_join($class);?>" data-items="<?php echo esc_attr($perview);?>" data-itemsDesktop="<?php echo esc_attr($perview);?>" data-itemsDesktopSmall="4" data-itemsTablet="<?php echo esc_attr($perview_tablet);?>" data-itemsMobile="1" data-pag="false" data-buttons="true">
	<?php
		foreach( $brands as $k=>$brand ):
		
		if( has_post_thumbnail() ):
		?>
		<div class="client-item col-xs-12">
			<a href="<?php echo isset( $links[$k] ) ? esc_url( $links[$k]) : '#';?>">
				<?php the_post_thumbnail(); ;?>
			</a>
		</div>
		<?php 
			endif;
		endforeach;
	?>
</div>
<?php endif;?>