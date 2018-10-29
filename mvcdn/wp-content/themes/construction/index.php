<?php get_header();
?>

<section class="vc_row wpb_row vc_row-fluid blog-page p-t-60 p-b-60">
	<div class="container">
		<div class="col-sm-7 col-md-8">
			<?php
				$args = apply_filters('construction-config-argument-index',array(
					'thumb_size' => '750x310',
					'show_pagination' => 1,
				    'show_readmore' => 1,
				    'readmore_text' => esc_html__('Read More','construction'),
				    'excerpt_length' => 30,
				    'el_class' => ''
				));
				extract( $args );

				$paged = (int) is_front_page() ? (get_query_var('page') ? get_query_var('page') : 1 ) : (get_query_var('paged') ? get_query_var('paged') : 1);

				$class = array('blog-listed-style blog blog-grid blog-lg');
				$class[] = $el_class;

				if( have_posts() ):
?>
					<div class="<?php echo construction_join( $class );?>">
						<?php
						// fullwidth

							while( have_posts() ) : the_post();
							?>
								<?php
								$post_format = get_post_format();
								$post_format = empty( $post_format ) ? '' : '-'. get_post_format();
								$located = CONSTRUCTION_ABS_PATH.'/partials/blog/content'. $post_format .'.php';
								if( ! empty( $located ) ){
									require $located;
								}else{
									require CONSTRUCTION_ABS_PATH.'/partials/blog/content.php';
								}
							?>
							<?php endwhile;
						// pagination
						global $wp_query;
						if( $show_pagination &&  $wp_query->max_num_pages > 1 ):

							?>
							<nav aria-label="Page navigation" class="text-left pag-nav col-md-12">
							<?php $big = 999999999; // need an unlikely integer;
								echo str_replace("<ul class='page-numbers'>","<ul class='pagination'>",paginate_links( array(
									'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
									'format' => '?paged=%#%',
									'current' => max( 1, $paged ),
									'total' =>$wp_query->max_num_pages,
									'prev_text' =>  '<span aria-label="Next"><i class="fa fa-chevron-left"></i></span>',
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
		</div>
		<div class="blog-sidebar main-sidebar col-sm-5 col-md-4">
			<?php get_sidebar(); ?>
		</div>
	</div>
</section>
<?php get_footer(); ?>