<?php
/**
 * Template part for displaying posts.
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail( 'post-thumbnail', array( 'class' => 'img-fluid  hentry__featured-image' ) ); ?>
		</a>
	<?php endif; ?>

	<div class="hentry__container">
		<header class="hentry__header">
			<time datetime="<?php the_time( 'c' ); ?>" class="hentry__date"><?php echo get_the_date(); ?></time>
			<?php the_title( sprintf( '<h2 class="hentry__title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		</header><!-- .hentry__header -->

		<div class="hentry__content  entry-content">
			<?php
			$structurepress_is_excerpt = ( 1 === (int) get_option( 'rss_use_excerpt', 0 ) );
			if ( $structurepress_is_excerpt ) {
				the_excerpt();
			}
			else {
				/* translators: %s: Name of current post */
				the_content( sprintf(
					wp_kses( __( 'Read more %s', 'structurepress-pt' ), array( 'span' => array( 'class' => array() ) ) ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				) );
			}
			?>
		</div><!-- .entry-content -->

		<footer class="hentry__footer">
			<?php if ( 'post' == get_post_type() ) : ?>
				<div class="hentry__meta">
					<?php get_template_part( 'template-parts/meta' ); ?>
				</div><!-- .hentry__meta -->
			<?php endif; ?>
		</footer>
	</div>
</article><!-- #post-## -->