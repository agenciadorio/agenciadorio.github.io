<?php
$atts = shortcode_atts(array(
    'posts_per_page' => '',
    'tpl' => 'tpl1',
	'orderby' => '',
	'columns' => 2,
	'order' => '',
	'thumb_size' => 'thumbnail',
	'cats' => '',
	'exclude' => '',
	'show_pagination' => 0,
    'show_readmore' => 0,
    'readmore_text' => esc_html__('Read More','construction'),
    'excerpt_length' => 30,
    'el_class' => ''
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

if( ! empty( $cats ) ){
	$args['cat'] = $cats;
}

if( ! empty( $exclude ) ){
	$args['post__not_in'] = explode(',',$exclude);
}

$class = array('blog-listed-style blog blog-grid blog-lg');
$class[] = $el_class;
$blog = new WP_Query($args);
if( $blog->have_posts() ):
?>
<div class="<?php echo construction_join( $class );?>">
	<?php
	// fullwidth
	if( $tpl === 'tpl1' ){

		while( $blog->have_posts() ) : $blog->the_post();
		?>
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
		<?php endwhile;?>
		<!-- type list Thumb -->
	<?php }elseif( $tpl === 'tpl2' ){
		while( $blog->have_posts() ) : $blog->the_post();
		?>
			<div class="blog-item">
				<div class="row">
					<div class="col-lg-5">
						<?php if( has_post_thumbnail()):?>
							<a href="<?php the_permalink(); ?>" class="hover-img">
								<?php the_post_thumbnail(); 
							?>
							</a>
						<?php endif;?>
					</div>
					<div class="col-lg-7">
						<div class="blog-caption">
							<ul class="blog-date blog-date-left">
								<?php
									$archive_year  = get_the_time('Y');
									$archive_month = get_the_time('m');
									$archive_day = get_the_time('d');
									?>
								<li><a href="<?php echo get_day_link( $archive_year, $archive_month, $archive_day ); ?>"><i class="fa fa-calendar"></i><?php the_time(get_option( 'date_format' )); ?>
									</a></li>
								<li><a href="<?php comments_link();?>"><i class="fa fa-comment"></i><?php comments_number( 'no comments', 'one comment', '% comments' ); ?></a></a></li>
							</ul>
							<h3 class="blog-heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<p><?php echo construction_excerpt( $excerpt_length ); ?></p>
							<?php if( $show_readmore ):?>
								<a href="<?php the_permalink(); ?>" class="btn btn-1 btn-bg-1 btn-sm">
									<?php echo esc_attr( $readmore_text ); ?>
								</a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		<?php endwhile;
		// List Column Type
	}else{
		// type column
		$class_column = array();
		$class_column[] = construction_get_class_column( $columns );
		$wrap_attrs = construction_get_animation( $class_column );
		?>
		<div class="row">
			<?php while( $blog->have_posts() ) : $blog->the_post();?>
				<div <?php echo construction_join( $wrap_attrs );?> class="<?php echo construction_join( $class_column );?>">
					<div class="blog-item">
						<div class="row">
							<div class="col-lg-5">
								<?php if( has_post_thumbnail()):?>
									<a href="<?php the_permalink(); ?>" class="hover-img">
										<?php the_post_thumbnail();
									?>
									</a>
								<?php endif;?>
							</div>
							<div class="col-lg-7">
								<div class="blog-caption">
									<ul class="blog-date blog-date-left">
										<?php
											$archive_year  = get_the_time('Y');
											$archive_month = get_the_time('m');
											$archive_day = get_the_time('d');
											?>
										<li><a href="<?php echo get_day_link( $archive_year, $archive_month, $archive_day ); ?>"><i class="fa fa-calendar"></i><?php the_time(get_option( 'date_format' )); ?>
											</a></li>
										<li><a href="<?php comments_link();?>"><i class="fa fa-comment"></i><?php comments_number( 'no comments', 'one comment', '% comments' ); ?></a></a></li>
									</ul>
									<h3 class="blog-heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
									<p><?php echo construction_excerpt( $excerpt_length ); ?></p>
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
			<?php endwhile;?>
		</div>
		<?php
	}
	// pagination
	if( $show_pagination &&  $blog->max_num_pages > 1 ):?>
		<nav aria-label="Page navigation" class="text-left pag-nav col-md-12">
		<?php $big = 999999999; // need an unlikely integer;
			global $wp_query, $wp_rewrite;
		    $wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
		    if($pages==''){
		        global $wp_query;
		         $pages = $wp_query->max_num_pages;
		         if(!$pages)
		         {
		             $pages = 1;
		         }
		    }if(is_front_page() and !is_home()) {
				$curent = (get_query_var('page')) ? get_query_var('page') : 1;
			} else {
				$curent = (get_query_var('paged')) ? get_query_var('paged') : 1;
			}
			echo str_replace("<ul class='page-numbers'>","<ul class='pagination'>",paginate_links( array(
				'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format' => '?paged=%#%',
				'current' => max( 1, $curent ),
				'total' =>$blog->max_num_pages,
				'prev_text' => '<span aria-label="Next"><i class="fa fa-chevron-left"></i></span>', 
				'next_text' => '<span aria-label="Previous"><i class="fa fa-chevron-right"></i></span>',
				'type' => 'list'
			) ) );
		?>
		</nav>
	<?php endif;

	wp_reset_postdata(); ?>
</div>
<?php else:?>
	<h2><?php esc_html_e('No Post Found!','construction');?></h2>
<?php endif;?>