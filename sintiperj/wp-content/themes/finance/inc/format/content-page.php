<article  id="page-<?php the_ID(); ?>" <?php post_class( 'page'); ?>>

<div class="page-content container clearfix">
 
	<?php the_content(); ?>
	<?php wp_link_pages(); ?>
                     
</div><!-- page-content -->    

	<div class="page-comment container clearfix">
	<?php 

	if ( class_exists( 'Redux' ) ) {

		$options = get_option('finance_framework');
		$finance_allow_comment = $options['allow_comment'];

		if( $finance_allow_comment == '1' ) {
		finance_comment_reply(); 
		if ( comments_open() || '0' != get_comments_number() ) comments_template(); 

	} }
	else {
		finance_comment_reply(); 
		if ( comments_open() || '0' != get_comments_number() ) comments_template();
	}
	?>
	</div>

</article><!-- #page<?php the_ID(); ?> -->