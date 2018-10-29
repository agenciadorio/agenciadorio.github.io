<?php

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Initialization function
add_action('init', 'sp_cpt_news_init');

function sp_cpt_news_init() {
  // Create new News custom post type
    $news_labels = array(
                    'name'                 => _x('News', 'sp-news-and-widget'),
                    'singular_name'        => _x('news', 'sp-news-and-widget'),
                    'add_new'              => _x('Add News Item', 'sp-news-and-widget'),
                    'add_new_item'         => __('Add New News Item', 'sp-news-and-widget'),
                    'edit_item'            => __('Edit News Item', 'sp-news-and-widget'),
                    'new_item'             => __('New News Item', 'sp-news-and-widget'),
                    'view_item'            => __('View News Item', 'sp-news-and-widget'),
                    'search_items'         => __('Search  News Items','sp-news-and-widget'),
                    'not_found'            =>  __('No News Items found', 'sp-news-and-widget'),
                    'not_found_in_trash'   => __('No News Items found in Trash', 'sp-news-and-widget'),
                    'parent_item_colon'    => '',
                    'menu_name'          => _x( 'News', 'admin menu', 'sp-news-and-widget' )
  );
  $news_args = array(
                    'labels'              => $news_labels,
                    'public'              => true,
                    'publicly_queryable'  => true,
                    'exclude_from_search' => false,
                    'show_ui'             => true,
                    'show_in_menu'        => true, 
                    'query_var'           => true,
                    'rewrite'             => array( 
                    							'slug'       => 'news',
                    							'with_front' => false
                							),
                    'capability_type'     => 'post',
                    'has_archive'         => true,
                    'hierarchical'        => false,
                    'menu_position'       => 5,
                	'menu_icon'   		  => 'dashicons-feedback',
                    'supports'            => array('title','editor','thumbnail','excerpt','comments', 'author'),
                    'taxonomies'          => array('post_tag')
  );
    register_post_type( WPNW_POST_TYPE, $news_args );
}

/* Register Taxonomy */
add_action( 'init', 'news_taxonomies');

function news_taxonomies() {
    $labels = array(
                'name'              => _x( 'Category', 'sp-news-and-widget' ),
                'singular_name'     => _x( 'Category', 'sp-news-and-widget' ),
                'search_items'      => __( 'Search Category', 'sp-news-and-widget' ),
                'all_items'         => __( 'All Category', 'sp-news-and-widget' ),
                'parent_item'       => __( 'Parent Category', 'sp-news-and-widget' ),
                'parent_item_colon' => __( 'Parent Category:', 'sp-news-and-widget' ),
                'edit_item'         => __( 'Edit Category', 'sp-news-and-widget' ),
                'update_item'       => __( 'Update Category', 'sp-news-and-widget' ),
                'add_new_item'      => __( 'Add New Category', 'sp-news-and-widget' ),
                'new_item_name'     => __( 'New Category Name', 'sp-news-and-widget' ),
                'menu_name'         => __( 'Category', 'sp-news-and-widget' ),
    );

    $args = array(
                'hierarchical'      => true,
                'labels'            => $labels,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => array( 'slug' => 'news-category' ),
    );
    register_taxonomy( WPNW_CAT, array( WPNW_POST_TYPE ), $args );
}

function wpnaw_rewrite_flush() {
	sp_cpt_news_init();
    news_taxonomies();

    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'wpnaw_rewrite_flush' );