<?php
extract(shortcode_atts(array(
	'tpl' => 'grid',
	'columns' => 2,
	'size' => '',
    'posts_per_page' => -1,
    'show_pagination' => 0,
    'show_readmore' => 0,
    'readmore_text' => esc_html__('Read More','construction'),
    'excerpt_length' => 50,
    'order' => '',
    'orderby' => '',
    'el_class' => ''
), $atts));

$class[] = $el_class;
$class[] = $tpl;

$posts_per_page = $posts_per_page > 0 ? (int)$posts_per_page : -1;

$args = array(
	'post_type'=>'service',
	'posts_per_page' => $posts_per_page
);

if( ! empty( $order ) ){
	$args['order'] = $order;
}

if( ! empty( $orderby ) ){
	$args['orderby'] = $orderby;
}
if( $tpl !== 'tabs'){
	$q = new WP_Query($args);
}

?>

<?php
if($tpl === 'slider'):
	$class[] = 'owl row';
	if( $q->have_posts()):
		$columns = (int) $columns;
		$columns_tablet = $columns > 2 ? 2 : $columns;
?>

<div class="<?php echo construction_join( $class );?>" data-items="<?php echo esc_attr($columns);?>" data-itemsDesktop="<?php echo esc_attr($columns);?>" data-itemsDesktopSmall="<?php echo esc_attr($columns);?>" data-itemsTablet="<?php echo esc_attr($columns_tablet);?>" data-itemsMobile="1" data-pag="<?php echo json_encode((boolean) $show_pagination);?>" data-buttons="true">
	<?php while( $q->have_posts() ) : $q->the_post();
	?>
		<div data-duration="0.8s" data-delay="0.8s" class="wol-item col-xs-12 wow fadeInUp">
			<div class="ser-item item-w">
				<?php the_post_thumbnail();
			?>
				<h3><?php the_title(); ?></h3>
				<p>
				<?php echo construction_excerpt( $excerpt_length ); ?>
				</p>
				<?php if( $show_readmore ):?>
					<a href="<?php the_permalink(); ?>" class="btn btn-1 btn-bg-1 btn-sm">
						<?php echo esc_attr( $readmore_text ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	<?php endwhile;?>
</div>
<?php endif;
elseif( $tpl === 'tabs'):
	$class[] = 'row';
?>
<div class="row">
	<div class="col-lg-4">
		<?php $cats = get_terms( 'service-type', array(
			array(
			    'hide_empty' => true
			)
		));
		if( $cats ):
			$lists_cat = [];
		?>
		<ul class="nav nav-tabs service-tabs" role="tablist">
			<?php foreach( $cats as $k=>$cat ):
				$lists_cat[] = $cat->term_id;
			?>
			<li role="presentation" <?php if( $k === 0 ) echo esc_attr('class="active"');?>>
				<a href="#tab<?php echo esc_attr($k);?>" aria-controls="tab<?php echo esc_attr($k);?>" role="tab" data-toggle="tab"><i class="fa fa-chevron-right"></i><?php echo esc_attr( $cat->name );?></a></li>
			<?php endforeach;?>
		</ul>
		<!-- end cats -->
		<?php endif;?>
	</div>
	<div class="col-lg-8"> 
		<!-- Tab panes -->
		<?php if( !empty( $lists_cat ) ):
			$columns = (int) $columns;
			$columns_tablet = $columns > 2 ? 2 : $columns;
		?>
		<div class="tab-content">
			<!-- Tab panes item -->
			<?php foreach( $lists_cat as $k=>$cat ):
				$args['tax_query'] = array(array(
					'taxonomy' => 'service-type',
					'field'    => 'term_id',
					'terms'    => array( $cat ),
				));
				$q = new WP_Query( $args );
			?>
				<div role="tabpanel" class="tab-pane fade <?php if( $k === 0 ) echo ' in active';?>" id="tab<?php echo esc_attr($k);?>">
					<div class="row">
						<div class="owl" data-items="<?php echo esc_attr($columns);?>" data-itemsDesktop="<?php echo esc_attr($columns);?>" data-itemsDesktopSmall="<?php echo esc_attr($columns);?>" data-itemsTablet="<?php echo esc_atrr($columns_tablet);?>" data-itemsMobile="1" data-pag="<?php echo json_encode((boolean) $show_pagination);?>" data-buttons="true">
						<?php while( $q->have_posts() ) : $q->the_post();
								?>
						<div class="owl-item">
							<div class="ser-item item-w">
								<?php the_post_thumbanil(); ?>
								<h3><?php the_title(); ?></h3>
								<p>
								<?php echo construction_excerpt( $excerpt_length ); ?>
								</p>
								<?php if( $show_readmore ):?>
									<a href="<?php the_permalink(); ?>" class="btn btn-1 btn-bg-1 btn-sm">
										<?php echo esc_attr( $readmore_text ); ?>
									</a>
								<?php endif; ?>
							</div>
						</div>
						<!--/end tab 1-->
						<?php endwhile;wp_reset_postdata(); ?>
						</div>
					</div>
				</div>
			<?php endforeach;?>
		</div>
		<?php endif;?>
	</div>
</div>
<?php
else: ?>
<?php if( $q->have_posts()):
	$class[] = 'serveice-grid';
	$column_class = array('wow fadeInUp');
	$column_class[] = construction_get_class_column( $columns );
	$i = 0;
	while( $q->have_posts() ) : $q->the_post();
?>
		<div class="service-item">
			<div class="row">
				<div class="col-sm-6 col-md-6 col-lg-6<?php if( $i % 2 === 0 ){ echo ' p-l-0 pull-right';}else{ echo ' p-r-0';}?>">
					<div class="service-img hover-img">
						<?php the_post_thumbnail(); ?>
					</div>
				</div>
				<div class="col-sm-6 col-md-6 col-lg-6">
					<div class="service-content">
						<div class="<?php echo ( $i % 2 === 0 ) ? 's1' : 's2';?>">
							<h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<p>
							<?php echo construction_excerpt( $excerpt_length ); ?>
							</p>
							<?php if( $show_readmore ):?>
								<a href="<?php the_permalink(); ?>" class="btn btn-1 btn-bg-1 btn-sm">
									<?php echo esc_attr( $readmore_text ); ?>
								</a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php
	$i++;
	endwhile;

	if( $show_pagination &&  $q->max_num_pages > 1 ):?>
		<nav aria-label="Page navigation" class="pag-nav col-md-12">
		<?php $big = 999999999; // need an unlikely integer;
			echo str_replace("<ul class='page-numbers'>","<ul class='pagination'>",paginate_links( array(
				'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format' => '?paged=%#%',
				'current' => max( 1, $paged ),
				'total' =>$q->max_num_pages,
				'prev_text' => '<span aria-label="Next"><i class="fa fa-chevron-left"></i></span>', 
				'next_text' =>  '<span aria-label="Previous"><i class="fa fa-chevron-right"></i></span>',
				'type' => 'list'
			) ) );
		?>
		</nav>
	<?php endif;
	wp_reset_postdata();

	endif;
	// end post
endif;
// end conditional
?>