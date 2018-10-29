<?php
/**
 * Add the link to documentation under Appearance in the wp-admin
 */

if ( ! function_exists( 'structurepress_add_docs_page' ) ) {
	function structurepress_add_docs_page() {
		add_theme_page(
			_x( 'Documentation', 'backend', 'structurepress-pt' ),
			_x( 'Documentation', 'backend', 'structurepress-pt' ),
			'',
			'proteusthemes-theme-docs',
			'structurepress_docs_page_output'
		);
	}
	add_action( 'admin_menu', 'structurepress_add_docs_page' );

	function structurepress_docs_page_output() {
		?>
		<div class="wrap">
			<h2><?php _ex( 'Documentation', 'backend', 'structurepress-pt' ); ?></h2>

			<p>
				<strong><a href="https://www.proteusthemes.com/docs/structurepress-pt/" class="button button-primary " target="_blank"><?php _ex( 'Click here to see online documentation of the theme!', 'backend', 'structurepress-pt' ); ?></a></strong>
			</p>
		</div>
		<?php
	}
}