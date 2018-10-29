<?php
/**
 * Template part for displaying single posts.
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<?php the_post_thumbnail( 'post-thumbnail', array( 'class' => 'img-fluid  hentry__featured-image' ) ); ?>
	<?php endif; ?>

	<header class="hentry__header">
		<time datetime="<?php the_time( 'c' ); ?>" class="hentry__date"><?php echo get_the_date(); ?></time>
		<?php the_title( '<h1 class="hentry__title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<div class="hentry__content  entry-content">
		<?php the_content(); ?>

		<!-- Multi Page in One Post -->
		<?php
			$structurepress_args = array(
				'before'      => '<div class="multi-page  clearfix">' . /* translators: after that comes pagination like 1, 2, 3 ... 10 */ esc_html__( 'Pages:', 'structurepress-pt' ) . ' &nbsp; ',
				'after'       => '</div>',
				'link_before' => '<span class="btn  btn-primary">',
				'link_after'  => '</span>',
				'echo'        => 1,
			);
			wp_link_pages( $structurepress_args );
		?>
	</div><!-- .entry-content -->

	<footer class="hentry__footer">
		<?php if ( 'post' == get_post_type() ) : ?>
			<div class="hentry__meta">
				<?php get_template_part( 'template-parts/meta' ); ?>
			</div><!-- .hentry__meta -->
		<?php endif; ?>
		<?php // _s_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->