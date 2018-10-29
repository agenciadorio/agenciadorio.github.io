<?php get_header();
while( have_posts() ) : the_post();
?>
<section class="blog-detail-page p-t-60 p-b-60">
	<div class="container">
		<div class="row">
			<div class="col-sm-7 col-md-8">
				<div class="blog blog-grid blog-lg">
					<!-- BLOG ARTICLE -->
					<div class="blog-item">
						<?php if( has_post_thumbnail() ):?>
							<div class="blog-article-img">
								<?php the_post_thumbnail('full', array('class'=>'img-responsive')); ?>
							</div>
						<?php endif;?>
						<div class="blog-caption">
							
							<div class="clearfix main-blog-content">
								<?php the_content(); ?>
								<?php
					            $defaults = array(
					              'before'           => '<div id="page-links"><strong>Page: </strong>',
					              'after'            => '</div>',
					              'link_before'      => '<span>',
					              'link_after'       => '</span>',
					              'next_or_number'   => 'number',
					              'separator'        => ' ',
					              'nextpagelink'     => esc_html__( 'Next page','construction' ),
					              'previouspagelink' => esc_html__( 'Previous page','construction' ),
					              'pagelink'         => '%',
					              'echo'             => 1
					            );
					           ?>
					          <?php wp_link_pages($defaults); ?>
							</div>
						</div>
					</div>
					<?php if( construction_get_option('show_post_info')):?>
						<div class="share-post">
							<div class="row">
								<div class="col-lg-5">
									<span><?php esc_html_e('Share this post on :','construction');?></span>
									<?php get_template_part( 'partials/social'); ?>
								</div>
								<div class="col-lg-7">
									<span><?php esc_html_e('Tags : ','construction');?></span>
									<?php the_tags( '<ul class="list-inline"><li>', '</li><li>', '</li></ul>' ); ?>
								</div>
							</div>
						</div>
					<?php endif;?>
				</div>
			<!-- COMMENTS -->
			<?php
				if('open' == $post->comment_status){
					comments_template();
				}
			?>
			<!-- Relate Post -->
			<?php if( construction_get_option('post_show_related')){
					$t_related = array();
					$atts = '';
					$cats = get_the_category();
					if( !empty( $cats ) ){
						foreach( $cats as $cat ){
							$t_related[] = $cat->term_id;
						}
					}
					$args = array(
					    'posts_per_page' => construction_get_option('post_noo_related'),
					    'tpl' => 'tpl2',
						'orderby' => '',
						'columns' => 2,
						'order' => '',
						'thumb_size' => construction_get_option('post-related-size','600x450'),
						'show_pagination' => 0,
					    'show_readmore' => 0,
					    'readmore_text' => esc_html__('Read More','construction'),
					    'excerpt_length' => 30,
					    'exclude' => get_the_ID(),
					    'cats' => implode(',',$t_related),
					    'el_class' => ''
					);
					foreach( $args as $k=>$v){
						$atts .= " {$k}='{$v}'";
					}

				echo do_shortcode('[qk_blog_list'. $atts .']');
			}?>
			</div>
			<div class="main-sidebar col-sm-5 col-md-4">
				<?php get_sidebar(); ?>
			</div>
		</div><!-- BLOG SIDEBAR -->
	</div>
</section><!-- End Content -->
<?php endwhile;wp_reset_postdata(); ?>
<?php get_footer(); ?>