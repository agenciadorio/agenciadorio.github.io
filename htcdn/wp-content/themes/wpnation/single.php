<?php 

get_header(); 

$blogTemplate = ( get_post_meta( $post->ID, 'single_template', true )) ? get_post_meta( $post->ID, 'single_template', true ) : 'blog-right';


?>
		<!-- BEGIN BLOG TITLE -->
		<?php	
		$showTitle = get_post_meta($post->ID, "page_title",true);
		
		if ($showTitle == "Yes") {
		
			$breadcrumb = get_post_meta($post->ID, "breadcrumb",true);
			$pageIcon = get_post_meta($post->ID, "page_icon",true);
			$pageClass = get_post_meta($post->ID, "page_class",true);
			$pageDescription = get_post_meta($post->ID, "page_description",true); 
			$pageTitleAlign = get_post_meta($post->ID, "page_align",true); 
		?>
		
		<style>
		<?php if (!$pageDescription && $breadcrumb=="Yes") { ?>
		#crumbs {
			margin-top:2px;
		}
		<?php } 
		if ($pageTitleAlign =="Center") { ?>
		#main-title-wrap, #main-title-undertext {
			text-align:center;
		}
		<?php } ?>
		</style>
		
		<div id="top-content-divider">
			<div class="container">
				<div id="main-title-wrap"><?php if ($pageIcon) { ?><span class="<?php echo $pageIcon; ?>"></span><?php } ?> <?php the_title(); ?> <?php if ($breadcrumb == "Yes") { nation_breadcrumbs(); } ?></div>
				<?php if ($pageDescription) { ?>
				<div id="main-title-undertext"><?php echo $pageDescription; ?></div>
				<?php } ?>
			</div>
		</div>
		
		<?php 
		} else {
			echo "<div id='top-divider' class='container'></div>";
		}
		?>
		<!-- END BLOG TITLE -->
		
		<!-- BEGIN BLOG CONTENT -->
		<div class="container">
			
			<?php  
				if ($blogTemplate == 'blog-left') { ?>
			
			<!-- BLOG SIDEBAR -->
			<div class="four columns blog-sidebar blog-sidebar-left">
				<?php dynamic_sidebar( 'blog_sidebar' );  ?>	
			</div>
			<!-- END BLOG SIDEBAR -->
			
			<?php } ?>
			<div class="columns blog-wrap blog-single <?php if ($blogTemplate == 'blog-right') { echo "eleven"; } else if ($blogTemplate == 'blog-left') { echo "eleven offset-by-one blog-wrap blog-sidebar-left-content"; } else if ($blogTemplate == 'blog-fullwidth') { echo "sixteen blog-fullwidth"; } ?>">
				
				<?php if ( have_posts() ): while ( have_posts() ) : the_post(); ?>
				
				<!-- BEGIN BLOG POST -->
				<div <?php post_class('blog-post-wrap'); ?>>
					<div class="blog-image-wrap">
						<?php 
						if ( has_post_thumbnail() ) {
							if ($blogTemplate == 'blog-right' || $blogTemplate == 'blog-left' ) { 
								the_post_thumbnail( 'blog-normal', array('class' => 'main-blog-images') );
							} else {
								the_post_thumbnail( 'blog-fullwidth', array('class' => 'main-blog-images') );
							}
						} 	
						?>
					</div>
					<div class="blog-post-header">
						<?php the_title() ?>
					</div>
					<div class="blog-post-meta-wrap">
						<div class="blog-category"><span class="icon-calendar"></span><?php _e('Date:','nation'); ?> <?php the_time(get_option('date_format')); ?></div>
						<div class="blog-author"><span class="icon-user"></span><?php _e('Author:','nation'); ?> <?php the_author_posts_link() ?></div>
						<div class="blog-category"><span class="icon-tag"></span><?php _e('Category:','nation'); ?> <?php the_category(', ') ?></div>
						<div class="clear"></div>
					</div>
					<div class="blog-post-content">
						<p><?php the_content(); ?></p>
						<?php wp_link_pages( array(
							'before'           => '<p>' . __( 'Pages:', 'nation' ),
							'after'            => '</p>',
							'next_or_number'   => 'number',
							'separator'        => ' ',
							'nextpagelink'     => __( 'Next page', 'nation' ),
							'previouspagelink' => __( 'Previous page', 'nation' ),
							'pagelink'         => '%',
							'echo'             => 1
						) ); ?>
						
						<div class="blog-tag-wrap"><?php the_tags(); ?></div>
					</div>				
					
					<?php if ( is_singular() && get_the_author_meta( 'description' ) ) { ?>
					<div class="blog-author-wrap">
						<?php echo str_replace( "class=\"avatar", "class=\"blog-author-image", get_avatar( get_the_author_meta('user_email'), "120" ) ); ?>	
						<div class="blog-author-header"><?php the_author_meta( 'display_name' ); ?></div>
						<div class="blog-author-description"><?php the_author_meta( 'description' ); ?></div>
						<div class="clear"></div>
					</div>
					<?php } ?>
					
					<?php
						comments_template();
						
						endwhile;endif;	
					?>
			
				</div>
				<!-- END BLOG POST -->
												
			</div>
			
			<?php  if ($blogTemplate == 'blog-right') { ?>		
		
			<!-- BLOG SIDEBAR -->
			<div class="four columns offset-by-one blog-sidebar">
				<?php dynamic_sidebar( 'blog_sidebar' );  ?>	
			</div>
			<!-- END BLOG SIDEBAR -->
			
			<?php } ?>
			
		</div>
		<!-- END BLOG CONTENT -->
	
<?php get_footer(); ?>
