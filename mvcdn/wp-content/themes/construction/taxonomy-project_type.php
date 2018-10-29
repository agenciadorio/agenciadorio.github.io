<?php get_header();
    ?>
    <!-- Project -->
	<section class="block-project-detail p-t-60 p-b-60">
		<div class="container">	
			<div class="row">
				<?php $project_sidebar = construction_get_prefix( 'project-sidebar');
					$is_active_sidebar = is_active_sidebar( $project_sidebar );
					if( $is_active_sidebar ):
				?>
				<div class="col-sm-5 col-md-4 col-lg-4">
					<?php dynamic_sidebar( $project_sidebar );?>
				</div>
				<div class="col-sm-7 col-md-8 col-lg-8">
				<?php else:?>
				<div class="col-sm-12 col-md-12 col-lg-12">
				<?php endif;?>
					<div class="project-list">
						<?php
						$atts = array(
						    'el_class' => '',
						    'tpl' => 'grid',
							'thumb_size' => construction_get_option('pj-thumb-size','600x450'),
							'columns' => 2,		
							'show_pagination' => 1,
							'show_button' => 1,
							'show_title' => 1,
							'btn_class' => ''
						);
						
						extract( $atts );

						$columns = (int) $columns;

						$paged = (int) is_front_page() ? (get_query_var('page') ? get_query_var('page') : 1 ) : (get_query_var('paged') ? get_query_var('paged') : 1);

    					if( have_posts() ) :
							$class = array('row wrap-p');
					    	$class[] = 'project-'. $tpl;
					    	$class[] = $el_class;
					    	$class_columns = array('project-item');
							$class_columns[] = construction_get_class_column( $columns );
							$class_columns[] = 'p-2';
						?>
						<div class="<?php echo esc_attr( implode(' ', $class ) );?>">
							<?php
								while( have_posts() ) : the_post();?>
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
							global $wp_query;
							if( $show_pagination &&  $wp_query->max_num_pages > 1 ):?>
								<nav aria-label="Page navigation" class="pag-nav col-md-12">
								<?php $big = 999999999; // need an unlikely integer;
									echo str_replace("<ul class='page-numbers'>","<ul class='pagination'>",paginate_links( array(
										'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
										'format' => '?paged=%#%',
										'current' => max( 1, $paged ),
										'total' =>$wp_query->max_num_pages,
										'prev_text' => '<span aria-label="Next"><i class="fa fa-chevron-left"></i></span>', 
										'next_text' => '<span aria-label="Previous"><i class="fa fa-chevron-right"></i></span>', 
										'type' => 'list'
									) ) );
								?>
								</nav>
							<?php endif;
							wp_reset_postdata();?>
						</div>
						<?php else:?>
							<h2><?php esc_html_e('No Post Found!','construction');?></h2>
						<?php endif;?>
					</div>
				</div>
			</div>
		</div>
	</section>
    <?php
wp_reset_postdata();

get_footer(); ?>