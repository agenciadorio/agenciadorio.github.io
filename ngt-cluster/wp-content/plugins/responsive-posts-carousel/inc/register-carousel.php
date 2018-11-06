<?php
$labels = array(
	'name'               => _x( 'Carousels', 'Carousel', 'responsive-posts-carousel' ),
	'singular_name'      => _x( 'Carousel', 'Carousel', 'responsive-posts-carousel' ),
	'menu_name'          => _x( 'Carousels', 'admin menu', 'responsive-posts-carousel' ),
	'name_admin_bar'     => _x( 'Carousel', 'Carousels', 'responsive-posts-carousel' ),
	'add_new'            => _x( 'Add New', 'Carousel', 'responsive-posts-carousel' ),
	'add_new_item'       => __( 'Add New Carousel', 'responsive-posts-carousel' ),
	'new_item'           => __( 'New Carousel', 'responsive-posts-carousel' ),
	'edit_item'          => __( 'Edit Carousel', 'responsive-posts-carousel' ),
	'view_item'          => __( 'View Carousel', 'responsive-posts-carousel' ),
	'all_items'          => __( 'All Carousels', 'responsive-posts-carousel' ),
	'search_items'       => __( 'Search Carousels', 'responsive-posts-carousel' ),
	'parent_item_colon'  => __( 'Parent Carousels:', 'responsive-posts-carousel' ),
	'not_found'          => __( 'No Carousel found.', 'responsive-posts-carousel' ),
	'not_found_in_trash' => __( 'No Carousel found in Trash.', 'responsive-posts-carousel' )
);

$args = array(
	'labels'             => $labels,
    'description'        => __( 'Create Posts Carousels.', 'responsive-posts-carousel' ),
	'public'             => false,
	'publicly_queryable' => false,
	'show_ui'            => true,
	'show_in_menu'       => true,
	'menu_icon'       	 => 'dashicons-editor-insertmore',
	'query_var'          => true,
	'capability_type'    => 'post',
	'has_archive'        => false,
	'hierarchical'       => false,
	'supports'           => array( 'title' )
);

register_post_type( 'wcp_carousel', $args );
?>