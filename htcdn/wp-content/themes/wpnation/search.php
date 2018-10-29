<?php 

get_header(); 

?>

		<!-- BEGIN PAGE TITLE -->
		<style>
		#crumbs {
			margin-top:2px;
		}
		</style>
		<div id="top-content-divider">
			<div class="container">
				<div id="main-title-wrap"><span class="icon-search"></span> <?php printf( __( 'Search Results for: %s', 'nation' ), get_search_query() ); ?></div>
			</div>
		</div>
		<!-- END PAGE TITLE -->
		
		<!-- BEGIN BLOG CONTENT -->
		<div class="container">
			<div class="eleven columns blog-wrap">
				<div id="search-again-wrap">
					<h3><?php _e("Search again",'nation'); ?></h3>
					<div><?php _e("Not found what you're looking for? Try a new search with different keyword!",'nation'); ?></div>
					<?php get_search_form(); ?>
					<div class="clear"></div>
				</div>
				<?php 
					if ( have_posts() ): while ( have_posts() ) : the_post();
				?>
				
				<!-- BEGIN BLOG POST -->
				<div class="blog-post-wrap">
					<div class="blog-post-header">
						<a href="<?php the_permalink(); ?>"><?php the_title() ?></a>
					</div>
					<div class="blog-post-meta-wrap">
						<div class="blog-category"><span class="icon-calendar"></span><?php _e('Date:','nation'); ?> <?php the_time(get_option('date_format')); ?></div>
						<div class="blog-author"><span class="icon-user"></span><?php _e('Author:','nation'); ?> <?php the_author_posts_link() ?></div>
						<div class="blog-category"><span class="icon-tag"></span><?php _e('Category:','nation'); ?> <?php the_category(', ') ?></div>
						<div class="clear"></div>
					</div>
					<div class="blog-post-content">
						<p><?php nation_excerpt(); ?></p>
						<a href="<?php the_permalink(); ?>"><button class="blog-readmore-button"><?php _e('Read More','nation'); ?> <span class="icon-eye-open"></span></button></a>
						<div class="blog-comments"><span class="icon-comments"></span><?php _e('Comments:','nation'); ?> <?php comments_number(0,1,'%'); ?></div> 
					</div>
				</div>
				<!-- END BLOG POST -->
				
				<!-- BEGIN BLOG PAGINATION -->
				<?php
					endwhile;
					
					global $wp_query;
					$big = 999999999; // need an unlikely integer
				
					echo "<div id='blog-page-navigation-wrap'>".paginate_links( array(
						'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
						'format' => '?paged=%#%',
						'current' => max( 1, get_query_var('paged') ),
						'total' => $wp_query->max_num_pages,
						'prev_text'    => __('<span class="icon-angle-left"></span> Prev', 'nation'),
						'next_text'    => __('Next <span class="icon-angle-right"></span>', 'nation'),
					) )."</div>";
					
					
					endif;
				?>
				<!-- END BLOG PAGINATION -->
								
			</div>
			
			<!-- BLOG SIDEBAR -->
			<div class="four columns offset-by-one blog-sidebar">
				<?php dynamic_sidebar( 'blog_sidebar' );  ?>	
			</div>
			<!-- END BLOG SIDEBAR -->
			
		</div>
		<!-- END BLOG CONTENT -->
	
<?php get_footer(); ?>
