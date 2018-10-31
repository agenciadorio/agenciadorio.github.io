<?php
	global $enhanced_category;
	// if not previously set up, then let setup_ec_data get the current query term/category
	if (empty($categoryId)) {
		$categoryId = null;
	}

	// get enhanced category post and set it up as global current post
	$enhanced_category->setup_ec_data($categoryId);
?>

<!-- enchanced category page (ECP) content -->

<?php
// try to load specialized template if exists. Prioritize specialized ECP template part.
if ( !locate_template( array('content-ecp.php', 'content-page.php'), true, false ) ) {
?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<div class="post-thumbnail">
		
		
		</div>

		<div class="entry-content">
			<?php the_content(); ?>
		</div><!-- .entry-content -->

		<?php edit_post_link( __( 'Edit'), '<footer class="entry-footer"><span class="edit-link">', '</span></footer><!-- .entry-footer -->' ); ?>

	</article><!-- #post-## -->
<?php
}

// If comments are open or we have at least one comment, load up the comment template.
if ( comments_open() || get_comments_number() ) {
	comments_template();
}
?>

<style id="mfn-dnmc-bg-css">
body:not(.template-slider) #Header_wrapper{background-image:url(<?php the_post_thumbnail_url( $size ); ?> );background-repeat:no-repeat;background-position:center center}
</style>

