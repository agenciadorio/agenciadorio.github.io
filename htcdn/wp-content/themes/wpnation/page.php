<?php 

/*
Template Name: Page default
*/

get_header(); 

?>
		<!-- BEGIN PAGE TITLE -->
		<?php	
		$showTitle = get_post_meta($post->ID, "page_title", true);
		
		if ($showTitle != "No") {
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
		<!-- END PAGE TITLE -->
			
	<!-- BEGIN CONTACT PAGE CONTENT -->
	<div class="container">
		<div class="eleven columns page-wrap<?php if ( isset($pageClass) ) { echo " ".$pageClass; } ?>">
			<?php
				if (have_posts()) : while (have_posts()) : the_post();
				the_content();
				
				wp_link_pages( array(
					'before'           => '<p>' . __( 'Pages:', 'nation' ),
					'after'            => '</p>',
					'next_or_number'   => 'number',
					'separator'        => ' ',
					'nextpagelink'     => __( 'Next page', 'nation' ),
					'previouspagelink' => __( 'Previous page', 'nation' ),
					'pagelink'         => '%',
					'echo'             => 1
				) );
						
				endwhile; endif;
			?>
		</div>
		
		<!-- BEGIN PAGE SIDEBAR -->
		<div class="four columns offset-by-one page-sidebar">
			<?php dynamic_sidebar( 'page_sidebar' ); ?>	
		</div>
		<!-- END PAGE SIDEBAR -->
		
	</div>
	<!-- END CONTACT PAGE CONTENT -->
	
<?php get_footer(); ?>
