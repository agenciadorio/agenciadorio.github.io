<?php
/**
 * 404 page
 *
 * @package StructurePress
 */

get_header();

?>

<div class="error-404">
	<div class="container">
		<img src="<?php echo esc_attr( get_template_directory_uri() ) . '/assets/images/404.png'; ?>" alt="<?php esc_html_e( '404 Picture' , 'structurepress-pt' ); ?>">
		<div class="error-404__content">
			<h2><?php esc_html_e( 'Looks like something went wrong' , 'structurepress-pt' ); ?></h2>
			<p class="error-404__text">
			<?php
				printf(
					/* translators: the first %s for line break, the second and third %s for link to home page wrap */
					esc_html__( 'Page you are looking for is not here. %s Go %s Home %s or try to search:' , 'structurepress-pt' ),
					'<br>',
					'<b><a href="' . esc_url( home_url( '/' ) ) . '">',
					'</a></b>'
				);
			?>
			</p>
			<div class="widget_search">
				<?php get_search_form(); ?>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>