<?php
/**
 * The main template file.
 *
 * Main blog page
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 */

get_header();

$structurepress_sidebar = get_field( 'sidebar', (int) get_option( 'page_for_posts' ) );

if ( ! $structurepress_sidebar ) {
	$structurepress_sidebar = 'left';
}

get_template_part( 'template-parts/page-header' );
get_template_part( 'template-parts/breadcrumbs' );

?>

	<div id="primary" class="content-area  container">
		<div class="row">
			<main id="main" class="site-main  col-xs-12<?php echo 'left' === $structurepress_sidebar ? '  col-lg-9  col-lg-push-3' : ''; echo 'right' === $structurepress_sidebar ? '  col-lg-9' : ''; ?>" role="main">
				<?php if ( have_posts() ) : ?>

					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>

						<?php
							/*
							 * Include the Post-Format-specific template for the content.
							 * If you want to override this in a child theme, then include a file
							 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
							 */
							get_template_part( 'template-parts/content', get_post_format() );
						?>

					<?php endwhile; ?>

					<?php
						the_posts_pagination( array(
							'prev_text' => '<i class="fa fa-caret-left"></i>',
							'next_text' => '<i class="fa fa-caret-right"></i>',
						) );
					?>

				<?php else : ?>

					<?php get_template_part( 'template-parts/content', 'none' ); ?>

				<?php endif; ?>
			</main>

			<?php if ( 'none' !== $structurepress_sidebar ) : ?>
				<div class="col-xs-12  col-lg-3<?php echo 'left' === $structurepress_sidebar ? '  col-lg-pull-9' : ''; ?>">
					<div class="sidebar" role="complementary">
						<?php
						if ( is_active_sidebar( 'blog-sidebar' ) ) {
							dynamic_sidebar( apply_filters( 'structurepress_blog_sidebar', 'blog-sidebar', get_the_ID() ) );
						}
						?>
					</div>
				</div>
			<?php endif; ?>

		</div>
	</div>

<?php get_footer(); ?>