<?php
/**
 * The template for displaying all single posts.
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

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'template-parts/content', 'single' ); ?>

					<?php
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
					?>

				<?php endwhile; // End of the loop. ?>

			</main><!-- #main -->

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
	</div><!-- #primary -->

<?php get_footer(); ?>