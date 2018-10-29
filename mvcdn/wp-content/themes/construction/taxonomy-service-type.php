<?php get_header();
    ?>
    <!-- Service -->
	<section class="block-service p-t-60 p-b-60">
		<div class="container">
			<?php
				$atts = array(
					'tpl' => 'grid',
					'columns' => 2,
					'size' => construction_get_option('sv-thumb-size','600x450'),
				    'show_pagination' => 1,
				    'show_readmore' => 1,
				    'readmore_text' => esc_html__('Read More','construction'),
				    'excerpt_length' => 50,
				    'el_class' => ''
				);
				extract( $atts );
				$class[] = $el_class;
				$class[] = $tpl;
				if( have_posts() ):
					$posts_per_page = $posts_per_page > 0 ? (int)$posts_per_page : -1;
					$class[] = 'serveice-grid';
					$column_class = array('wow fadeInUp');
					$column_class[] = construction_get_class_column( $columns );
					$i = 0;
					while( have_posts() ) : the_post();
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
				global $wp_query;
				if( $show_pagination &&  $wp_query->max_num_pages > 1 ):?>
					<nav aria-label="Page navigation" class="pag-nav col-md-12">
					<?php $big = 999999999; // need an unlikely integer;
						echo str_replace("<ul class='page-numbers'>","<ul class='pagination'>",paginate_links( array(
							'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
							'format' => '?paged=%#%',
							'current' => max( 1, $paged ),
							'total' => $wp_query->max_num_pages,
							'prev_text' => '<span aria-label="Next"><i class="fa fa-chevron-left"></i></span>', 
							'next_text' =>  '<span aria-label="Previous"><i class="fa fa-chevron-right"></i></span>',
							'type' => 'list'
						) ) );
					?>
					</nav>
				<?php endif;
				wp_reset_postdata();?>
				<?php else:?>
					<h2><?php esc_html_e('No Post Found!','construction');?></h2>
				<?php endif;?>
		</div>
	</section>
    <?php

get_footer(); ?>