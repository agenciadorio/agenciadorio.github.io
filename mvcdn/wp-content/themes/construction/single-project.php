<?php get_header();

while(have_posts()) : the_post();
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
					<div class="project_detail">
						<?php
							$gallery = construction_get_meta( get_the_ID(), 'gallery');
			                if( ! empty( $gallery ) ):
			                	wp_enqueue_script( 'flexslider');
			                	wp_enqueue_style( 'flexslider');
			                ?>
			                <div id="project-media-gallrey" class="flexslider<?php if( $is_active_sidebar ) echo ' active_sidebar';?>">
			                	<ul class="slides">
					                <?php
					                $thumbs = array();
					                foreach( $gallery as $image ):
					                	
								        	$thumbs[] = $image;
								        
					                ?>
					                <li class="hover-img"><img src="<?php echo esc_url( $image );?>" class="scale" alt="<?php esc_html_e('Project Media','construction');?>"></li>          
					                <?php endforeach;?>
			                    </ul>
			                </div>
			                <?php if( ! empty( $thumbs ) ):?>
				                <div id="project-carousel" class="wrap-p flexslider">
				                	<ul class="slides">
										<?php foreach( $thumbs as $thumb ): ?>
											<li class="hover-img p-2"><img src="<?php echo esc_url( $thumb );?>" class="scale" alt="<?php esc_html_e('Project Media','construction');?>"></li>  
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif;?>
			            <?php endif;?>
						<div class="clearfix">
							<?php the_content(); ?>
						</div>
						<ul class="list-default m-t-30">
							<li><span><?php esc_html_e('Category:','construction');?></span><?php the_terms( get_the_ID(), 'project_type', '', ',' ); ?></li>
							<?php $from_date = construction_get_meta( get_the_ID(), 'from_date'); if( ! empty( $from_date )):?>
								<li><span><?php esc_html_e('From Date:','construction');?></span><?php echo esc_attr( $from_date );?>S</li>
							<?php endif;?>
							<?php $end_date = construction_get_meta( get_the_ID(), 'end_date'); if( ! empty( $end_date )):?>
								<li><span><?php esc_html_e('To day:','construction');?></span><?php echo esc_attr( $end_date );?>S</li>
							<?php endif;?>
							<?php $client = construction_get_meta( get_the_ID(), 'client'); if( ! empty( $client )):?>
								<li><span><?php esc_html_e('Client:','construction');?></span><?php echo esc_attr( $client );?>S</li>
							<?php endif;?>
							<?php $complete_date = construction_get_meta( get_the_ID(), 'complete_date');
								$complete_date = empty( $complete_date ) ? esc_html__('Runing','construction') : $complete_date;
								?>
							<li><span><?php esc_html_e('Value:','construction');?></span><?php echo esc_attr( $complete_date );?></li>
						</ul>
					</div>
				</div>
			</div>

			<?php if( construction_get_option('pj_show_related')):
				$terms = get_the_terms( get_the_ID(), 'project_type' );
				$related = array();
				if( $terms ){
					foreach( $terms as $term ){
						$related[] = $term->term_id;
					}
				}

				if( !empty( $related ) ){
					$atts = '';
					$args = array(
					    'el_class' => '',
					    'tpl' => 'slider',
						'thumb_size' => construction_get_option('pj-thumb-size','600x450'),
						'columns' => 3,		
						'show_pagination' => 0,
						'show_button' => 1,
						'show_title' => 1,		
						'orderby' =>'',
						'btn_class' => '',
						'order' =>'',
						'taxonomy' => implode(',', $related),
						'posts_per_page' => construction_get_option('pj_no_related_products', 3)
					);
					foreach( $args as $k=>$v){
						$atts .= " {$k}='{$v}'";
					}
			?>
				<div class="projects-related">
					<?php echo do_shortcode('[qk_portfolio'. $atts .']'); ?>
				</div>
			<?php } endif;?>
		</div>
	</section>
    <?php
endwhile;wp_reset_postdata();

get_footer(); ?>