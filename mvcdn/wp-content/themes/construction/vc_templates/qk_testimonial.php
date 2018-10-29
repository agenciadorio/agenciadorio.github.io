<?php
extract(shortcode_atts(array(
	'columns' => 3,
    'order' => '',
    'hide_avatar' => 0,
    'el_class' => ''

), $atts));

$class[] = $el_class;

$order = $order > 0 ? (int)$order : -1;

$args = array('post_type'=>'testimonial','posts_per_page' => $order);

$q = new WP_Query($args);

	$class[] = 'testimonial-slider';
	if( $q->have_posts()):
		$columns = (int) $columns;
		$columns_small = $columns > 3 ? 3 : $columns;
		$columns_tablet = $columns > 2 ? 2 : $columns;
?>
<div class="<?php echo construction_join( $class );?>">
	<div class="owl" data-items="<?php echo esc_attr($columns);?>" data-itemsDesktop="<?php echo esc_attr($columns);?>" data-itemsDesktopSmall="<?php echo esc_attr($columns_small);?>" data-itemsTablet="<?php echo esc_attr($columns_tablet);?>" data-itemsMobile="1" data-pag="true" data-buttons="false">
		<?php while( $q->have_posts() ) : $q->the_post(); ?>	
		<div class="testtim-item">
			<?php if( ! $hide_avatar ):?>
				<figure>
					<?php the_post_thumbnail( 'full', array('class'=>'img-responsive') ); ?>
				</figure>
			<?php endif;?>
			<?php the_content(); ?>
			<h4 class="heading-4">- <?php the_title(); ?> -</h4>
			<span><?php echo construction_get_meta( get_the_ID(), 'job' );?></span>
		</div>
		<?php endwhile;wp_reset_postdata(); ?>
	</div>
</div>
<?php endif;
?>