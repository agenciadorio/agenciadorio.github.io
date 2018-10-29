<?php get_header();

while(have_posts()) : the_post();
    ?>
    <!-- Service -->
	<section class="block-service_detail p-t-60 p-b-60">
		<div class="container">
			<div class="service-item">
				<div class="row">
					<div class="col-md-6 col-lg-7">
						<div class="service-content1">
							<h2 class="title"><?php the_title();?></h2>
							<div class="clearfix">
								<?php the_content(); ?>
							</div>
						</div>
						<div class="atv">
							<?php $brochure_sidebar = construction_get_prefix( 'service-sidebar' );
								if( is_active_sidebar( $brochure_sidebar )){
									dynamic_sidebar( $brochure_sidebar );
								}

								$file = construction_get_meta( get_the_ID(), 'brochure' );
								if( ! empty( $file ) ):
							?>
								<div><a class="down_brc btn btn-1 btn-bg-2" href="<?php echo esc_url( $file );?>"><i class="fa fa-download"></i><?php esc_html_e('DOWNLOAD BROCHURE', 'construction');?></a></div>
							<?php endif;?>
						</div>
					</div>
					<div class="col-md-6 col-lg-5 hidden-xs">
						<?php if(has_post_thumbnail()):?>
						<div class="service-img hover-img">
							<?php the_post_thumbnail('full'); ?>
						</div>
						<?php endif;?>
					</div>
				</div>
				<!-- Service Related -->
				<?php if( construction_get_option('sv_show_related') ):?>
					<div class="services-related project-list project-cate p-t-60">
						<?php
							$atts = '';
							$args = array(
							    'el_class' => '',
							    'tpl' => 'slider',
								'thumb_size' => construction_get_option('sv-thumb-size','600x450'),
								'columns' => 3,
								'show_pagination' => 0,
								'show_button' => 1,
								'show_title' => 1,
								'orderby' =>'',
								'post_type' => 'service',
								'btn_class' => '',
								'order' =>'',
								'posts_per_page' => construction_get_option('sv_no_related', 5)
							);
							foreach( $args as $k=>$v){
								$atts .= " {$k}='{$v}'";
							}

							echo do_shortcode('[qk_portfolio'. $atts .']');
						?>

					</div>
				<?php endif;?>
			</div>
		</div>
	</section>
    <?php
endwhile;wp_reset_postdata();

get_footer(); ?>