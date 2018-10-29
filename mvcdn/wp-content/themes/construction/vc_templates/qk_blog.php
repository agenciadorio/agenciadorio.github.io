<?php
$atts = shortcode_atts(array(
    'posts_per_page' => -1,
	'show_pagination' => 0,
	// 'tpl'=> '',
	'orderby' => '',
	'columns' => 3,
	'order' => '',
	'thumb_size' => 'full',
	'show_readmore' => 0,
	'readmore_text' => esc_html__('Read More &rarr;','construction'),
    'excerpt_length' => 20
), $atts);
extract( $atts );
$paged = (int) is_front_page() ? (get_query_var('page') ? get_query_var('page') : 1 ) : (get_query_var('paged') ? get_query_var('paged') : 1);
$posts_per_page = $posts_per_page > 0 ? (int)$posts_per_page  : -1;
$args = array(
	'posts_per_page' => $posts_per_page,
	'order' => $order,
	'orderby' => $orderby,
	'paged'=>$paged
);

$class = array('row','blog-template');
$blog = new WP_Query($args);
if( $blog->have_posts() ):
	$columns = (int) $columns;
	$columns_small = $columns > 3 ? 3 : $columns;
	$columns_tablet = $columns > 2 ? 2 : $columns;
	?>
	<div class="<?php echo construction_join( $class );?>">
		<div class="owl" data-items="<?php echo esc_attr($columns);?>" data-itemsDesktop="<?php echo esc_attr($columns);?>" data-itemsDesktopSmall="<?php echo esc_attr($columns_small);?>" data-itemsTablet="<?php echo esc_attr($columns_tablet);?>" data-itemsMobile="1" data-pag="<?php echo json_encode( (boolean)$show_pagination );?>" data-buttons="true">
			<?php while( $blog->have_posts() ) : $blog->the_post();?>
			<div class="col-xs-12">
				<div class="item-w">
				<?php
					$format = get_post_format();
					$post_format = empty( $format ) ? '' : '-'. get_post_format();
					$located = CONSTRUCTION_ABS_PATH.'/partials/blog/content'. $post_format .'.php';
					if( ! empty( $located ) ){
						require $located;
					}else{
						require CONSTRUCTION_ABS_PATH.'/partials/blog/content.php';
					}
				?>
				</div>
			</div>
		<?php endwhile; wp_reset_postdata();?>
		</div>
	</div>
<?php endif;?>