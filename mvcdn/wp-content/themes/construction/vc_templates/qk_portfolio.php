<?php
	$atts = shortcode_atts(array(
	    'el_class' => '',
	    'tpl' => 'grid',
		'thumb_size' => 'large',	
		'columns' => 2,		
		'show_pagination' => 0,
		'show_button' => 1,
		'show_title' => 0,
		'taxonomy' => '',
		'orderby' =>'',
		'order' =>'',
		'post_type' => 'project',
		'posts_per_page' => 8

	), $atts);

	extract( $atts );

	$posts_per_page = $posts_per_page > 0 ? (int)$posts_per_page : -1;
	$columns = (int) $columns;

	$paged = (int) is_front_page() ? (get_query_var('page') ? get_query_var('page') : 1 ) : (get_query_var('paged') ? get_query_var('paged') : 1);

	$args = array(
	    'post_type' => $post_type,
		'paged' => $paged,
		'order' => $order,
		'orderby' => $orderby,
		'posts_per_page' =>$posts_per_page,
	    'post_status' => 'publish'
	);

	if( ! empty( $taxonomy ) ){
		$taxonomy = explode(',', $taxonomy);
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'project_type',
				'field'    => 'id',
				'terms'    => $taxonomy
			),
		);
	}

	$is_slider = $tpl === 'slider';

    $p = new WP_Query($args);
    if( $p->have_posts() ) :
    	$class = array('row wrap-p');
    	$class[] = 'project-'. $tpl;
    	$class[] = $el_class;
    	$class_columns = array('project-item');
?>
<div class="<?php echo esc_attr( implode(' ', $class ) );?>">
<!-- type slider -->
<?php if( $tpl === 'slider' ):
	$wrap_attrs = construction_get_animation( $class_columns );
	$class_columns[] = 'p-2';
	$columns_small = $columns > 3 ? 3 : $columns;
	$columns_tablet = $columns > 2 ? 2 : $columns;
	$show_pagination = (boolean) $show_pagination;
	$show_button = (boolean) $show_button;
?>
	<div class="owl" data-items="<?php echo esc_attr($columns);?>" data-itemsDesktop="<?php echo esc_attr($columns);?>" data-itemsDesktopSmall="<?php echo esc_attr($columns_small);?>" data-itemsTablet="<?php echo esc_attr($columns_tablet);?>" data-itemsMobile="1" data-pag="<?php echo json_encode( $show_pagination );?>" data-buttons="<?php echo json_encode( $show_button );?>">
	<?php
		while( $p->have_posts() ) : $p->the_post();
	?>
		<!-- portfolio item -->
		<div <?php echo construction_join( $wrap_attrs );?> class="<?php echo construction_join( $class_columns );?>">
			<a href="<?php the_permalink(); ?>" class="hover-img">
				<?php the_post_thumbnail();
				?>
				<?php if( $show_title ):?>
				<div class="hover-caption">
					<p><?php the_title(); ?></p>
				</div>
				<?php endif;?>
			</a>
		</div>
	<?php endwhile;
	wp_reset_postdata();?>
	</div>

<!-- type tab -->
<?php elseif( $tpl === 'tab'):
	$class_columns[] = construction_get_class_column( $columns );
?>

<!-- Type Grid -->
<?php else:
	$class_columns[] = construction_get_class_column( $columns );
	$class_columns[] = 'p-2';
?>
	<?php
		while( $p->have_posts() ) : $p->the_post();?>
		<!-- portfolio item -->
		<div class="<?php echo construction_join( $class_columns );?>">
			<a href="<?php the_permalink(); ?>" class="hover-img">
				<?php the_post_thumbnail();
				?>
				<?php if( $show_title ):?>
				<div class="hover-caption">
					<p><?php the_title(); ?></p>
				</div>
				<?php endif;?>
			</a>
		</div>
	<?php endwhile;
	if( $show_pagination &&  $p->max_num_pages > 1 ):?>
		<nav aria-label="Page navigation" class="pag-nav col-md-12">
		<?php $big = 999999999; // need an unlikely integer;
			echo str_replace("<ul class='page-numbers'>","<ul class='pagination'>",paginate_links( array(
				'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format' => '?paged=%#%',
				'current' => max( 1, $paged ),
				'total' =>$p->max_num_pages,
				'prev_text' =>  '<span aria-label="Next"><i class="fa fa-chevron-left"></i></span>',
				'next_text' =>  '<span aria-label="Previous"><i class="fa fa-chevron-right"></i></span>',
				'type' => 'list'
			) ) );
		?>
		</nav>
	<?php endif;
	wp_reset_postdata();
?>
<?php endif;?>
<!-- Project items -->

</div><!-- END Project -->
<?php
endif;
?>