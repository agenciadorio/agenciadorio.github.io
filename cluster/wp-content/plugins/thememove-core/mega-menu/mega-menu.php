<?php
include( 'class-tm-walker-nav-menu.php' );

// Register Custom Post Type
function thememove_register_mega_menu() {

	$labels = array(
		'name'               => _x( 'Mega Menus', 'Post Type General Name', 'thememove' ),
		'singular_name'      => _x( 'Mega Menu', 'Post Type Singular Name', 'thememove' ),
		'menu_name'          => __( 'Mega Menu', 'thememove' ),
		'name_admin_bar'     => __( 'Mega Menu', 'thememove' ),
		'parent_item_colon'  => __( 'Parent Menu:', 'thememove' ),
		'all_items'          => __( 'All Menus', 'thememove' ),
		'add_new_item'       => __( 'Add New Menu', 'thememove' ),
		'add_new'            => __( 'Add New', 'thememove' ),
		'new_item'           => __( 'New Menu', 'thememove' ),
		'edit_item'          => __( 'Edit Menu', 'thememove' ),
		'update_item'        => __( 'Update Menu', 'thememove' ),
		'view_item'          => __( 'View Menu', 'thememove' ),
		'search_items'       => __( 'Search Menu', 'thememove' ),
		'not_found'          => __( 'Not found', 'thememove' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'thememove' ),
	);
	$args   = array(
		'label'               => __( 'tm_mega_menu', 'thememove' ),
		'description'         => __( 'ThemeMove Mega Menu', 'thememove' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 20,
		'menu_icon'           => 'dashicons-list-view',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'rewrite'             => false,
		'capability_type'     => 'page',
	);
	register_post_type( 'tm_mega_menu', $args );

}

// Hook into the 'init' action
add_action( 'init', 'thememove_register_mega_menu', 0 );

function thememove_mega_menu_support_visual_composer() {
	$pt_array = ( $pt_array = get_option( 'wpb_js_content_types' ) ) ? ( $pt_array ) : array( 'page' );

	if ( ! in_array( 'tm_mega_menu', $pt_array ) ) {
		$pt_array[] = 'tm_mega_menu';

		update_option( 'wpb_js_content_types', $pt_array );
	}
}

add_action( 'admin_init', 'thememove_mega_menu_support_visual_composer' );

// Generate VC Custom CSS
function thememove_generate_vc_custom_css() {
	$locations = get_nav_menu_locations();

	if ( empty( $locations['primary'] ) ) {
		return;
	}

	$primary_menu = wp_get_nav_menu_object( $locations['primary'] );
	$nav_items    = wp_get_nav_menu_items( $primary_menu->term_id );

	$mega_menu_ids = array();
	foreach ( (array) $nav_items as $nav_item ) {
		if ( 'tm_mega_menu' == $nav_item->object ) {
			$mega_menu_ids[] = $nav_item->object_id;
		}
	}

	if ( ! empty( $mega_menu_ids ) ) {
		$post_custom_css_array       = array();
		$shortcodes_custom_css_array = array();

		foreach ( $mega_menu_ids as $mega_menu_id ) {
			$post_custom_css = get_post_meta( $mega_menu_id, '_wpb_post_custom_css', true );
			if ( ! empty( $post_custom_css ) ) {
				$post_custom_css_array[] = $post_custom_css;
			}

			$shortcodes_custom_css = get_post_meta( $mega_menu_id, '_wpb_shortcodes_custom_css', true );
			if ( ! empty( $shortcodes_custom_css ) ) {
				$shortcodes_custom_css_array[] = $shortcodes_custom_css;
			}
		}

		if ( ! empty( $post_custom_css_array ) ) {
			echo '<style type="text/css" data-type="vc_custom-css">';
			echo implode( '', $shortcodes_custom_css_array );
			echo '</style>';
		}

		if ( ! empty( $shortcodes_custom_css_array ) ) {
			echo '<style type="text/css" data-type="vc_shortcodes-custom-css">';
			echo implode( '', $shortcodes_custom_css_array );
			echo '</style>';
		}
	}
}

add_action( 'wp_head', 'thememove_generate_vc_custom_css', 1000 );