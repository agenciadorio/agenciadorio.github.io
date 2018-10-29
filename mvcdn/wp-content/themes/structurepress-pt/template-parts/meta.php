<div class="meta">
	<span class="meta__item  meta__item--author"><?php esc_html_e( 'by ' , 'structurepress-pt' ); the_author(); ?></span>
	<?php if ( comments_open( get_the_ID() ) ) : // only show comments count if the comments are open ?>
	<span class="meta__item  meta__item--comments"><a href="<?php comments_link(); ?>"><?php StructurePressHelpers::pretty_comments_number(); ?></a></span>
	<?php endif; ?>
	<?php if ( has_category() ) { ?>
		<span class="meta__item  meta__item--categories"><?php esc_html_e( '' , 'structurepress-pt' ); ?> <?php the_category( ' &nbsp; / &nbsp; ' ); ?></span>
	<?php } ?>
	<?php if ( has_tag() ) { ?>
		<span class="meta__item  meta__item--tags"><?php esc_html_e( '' , 'structurepress-pt' ); ?> <?php the_tags( '', ' &nbsp; / &nbsp; ' ); ?></span>
	<?php } ?>
</div>