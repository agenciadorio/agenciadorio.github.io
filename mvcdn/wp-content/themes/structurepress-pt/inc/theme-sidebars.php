<?php
/**
 * Register sidebars for StructurePress
 *
 * @package StructurePress
 */

function structurepress_sidebars() {
	// Blog Sidebar
	register_sidebar(
		array(
			'name'          => esc_html_x( 'Blog Sidebar', 'backend', 'structurepress-pt' ),
			'id'            => 'blog-sidebar',
			'description'   => esc_html_x( 'Sidebar on the blog layout.', 'backend', 'structurepress-pt' ),
			'class'         => 'blog  sidebar',
			'before_widget' => '<div class="widget  %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="sidebar__headings">',
			'after_title'   => '</h4>',
		)
	);

	// Regular Page Sidebar
	register_sidebar(
		array(
			'name'          => esc_html_x( 'Regular Page Sidebar', 'backend', 'structurepress-pt' ),
			'id'            => 'regular-page-sidebar',
			'description'   => esc_html_x( 'Sidebar on the regular page.', 'backend', 'structurepress-pt' ),
			'class'         => 'sidebar',
			'before_widget' => '<div class="widget  %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="sidebar__headings">',
			'after_title'   => '</h4>',
		)
	);

	// woocommerce shop sidebar
	if ( StructurePressHelpers::is_woocommerce_active() ) {
		register_sidebar(
			array(
				'name'          => esc_html_x( 'Shop Sidebar', 'backend' , 'structurepress-pt' ),
				'id'            => 'shop-sidebar',
				'description'   => esc_html_x( 'Sidebar for the shop page', 'backend' , 'structurepress-pt' ),
				'class'         => 'sidebar',
				'before_widget' => '<div class="widget  %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="sidebar__headings">',
				'after_title'   => '</h4>',
			)
		);
	}

	// Slider
	register_sidebar(
		array(
			'name'          => esc_html_x( 'Slider', 'backend', 'structurepress-pt' ),
			'id'            => 'slider-widgets',
			'description'   => esc_html_x( 'Slider widget area for Icon Box and Social Icons widgets.', 'backend', 'structurepress-pt' ),
			'before_widget' => '<div class="widget  %2$s">',
			'after_widget'  => '</div>',
		)
	);

	// Footer
	$footer_widgets_num = count( StructurePressHelpers::footer_widgets_layout_array() );

	// only register if not 0
	if ( $footer_widgets_num > 0 ) {
		register_sidebar(
			array(
				'name'          => esc_html_x( 'Footer', 'backend', 'structurepress-pt' ),
				'id'            => 'footer-widgets',
				'description'   => sprintf( esc_html_x( 'Footer area works best with %d widgets. This number can be changed in the Appearance &rarr; Customize &rarr; Theme Options &rarr; Footer.', 'backend', 'structurepress-pt' ), $footer_widgets_num ),
				'before_widget' => '<div class="col-xs-12  col-md-__col-num__"><div class="widget  %2$s">', // __col-num__ is replaced dynamically in filter 'dynamic_sidebar_params'
				'after_widget'  => '</div></div>',
				'before_title'  => '<h6 class="footer-top__headings">',
				'after_title'   => '</h6>',
			)
		);
	}
}
add_action( 'widgets_init', 'structurepress_sidebars' );