<?php 
/**
 * Plugin Name: QK Register Post Type
 * Description: This plugin register all post type come with theme.
 * Version: 1.0
 * Author: Quannt
 * Author URI: http://qkthemes.com
 */
?>
<?php
//wp_type, wp_type_cat
// add_action( 'init', 'construction_codex_gallery_init' );
add_action( 'init', 'construction_codex_testimonial_init' );
add_action( 'init', 'construction_codex_project_init' );
add_post_type_support( 'project', 'post-formats' );
// add_action( 'init', 'construction_codex_panel_init' );
add_action( 'init', 'construction_codex_service_init' );
// add_action( 'init', 'construction_codex_footer_init' );

/*  Project*/
function construction_codex_project_init() {
  $labels = array(
    'name'               => __( 'Project', 'post type general name', 'construction' ),
    'singular_name'      => __( 'Project', 'post type singular name', 'construction' ),
    'menu_name'          => __( 'Project', 'admin menu', 'construction' ),
    'name_admin_bar'     => __( 'Project', 'add new on admin bar', 'construction' ),
    'add_new'            => __( 'Add New', 'Project', 'construction' ),
    'add_new_item'       => __( 'Add New Project', 'construction' ),
    'new_item'           => __( 'New Project', 'construction' ),
    'edit_item'          => __( 'Edit Project', 'construction' ),
    'view_item'          => __( 'View Project', 'construction' ),
    'all_items'          => __( 'All Project', 'construction' ),
    'search_items'       => __( 'Search Project', 'construction' ),
    'parent_item_colon'  => __( 'Parent Project:', 'construction' ),
    'not_found'          => __( 'No Project found.', 'construction' ),
    'not_found_in_trash' => __( 'No Project found in Trash.', 'construction' ),
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'menu_icon'      => 'dashicons-admin-users',
    'publicly_queryable' => true,
    'menu_position'    => 2,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array( 'slug' => 'project' ),
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => null,
    'supports'           => array( 'title', 'thumbnail', 'editor','excerpt' ) 
  );

  register_post_type( 'project', $args );
}

// create two taxonomies, genres and writers for the post type "book"
function create_project_taxonomies() {
  
  // Add new taxonomy, make it hierarchical (like categories)
  $labels = array(
    'name'              => __( 'Project Types', 'construction' ),
    'singular_name'     => __( 'Project Type', 'construction' ),
    'search_items'      => __( 'Search Project Types','construction' ),
    'all_items'         => __( 'All Project Typees','construction' ),
    'parent_item'       => __( 'Parent Project Type','construction' ),
    'parent_item_colon' => __( 'Parent Project Type:','construction' ),
    'edit_item'         => __( 'Edit Project Type','construction' ),
    'update_item'       => __( 'Update Project Type','construction' ),
    'add_new_item'      => __( 'Add New Project Type','construction' ),
    'new_item_name'     => __( 'New Project Type Name','construction' ),
    'menu_name'         => __( 'Project Type' ,'construction'),
  );

  $args = array(
    'hierarchical'      => true,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => 'project-type' ),
  );

  register_taxonomy( 'project_type', array( 'project' ), $args );

  // Add new taxonomy, make it hierarchical (like categories)
  $labels = array(
    'name'              => __( 'Service Types', 'construction' ),
    'singular_name'     => __( 'Service Type', 'construction' ),
    'search_items'      => __( 'Search Service Types','construction' ),
    'all_items'         => __( 'All Service Typees','construction' ),
    'parent_item'       => __( 'Parent Service Type','construction' ),
    'parent_item_colon' => __( 'Parent Service Type:','construction' ),
    'edit_item'         => __( 'Edit Service Type','construction' ),
    'update_item'       => __( 'Update Service Type','construction' ),
    'add_new_item'      => __( 'Add New Service Type','construction' ),
    'new_item_name'     => __( 'New Service Type Name','construction' ),
    'menu_name'         => __( 'Service Type' ,'construction'),
  );

  $args = array(
    'hierarchical'      => true,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => 'service-type' ),
  );

  register_taxonomy( 'service-type', array( 'service' ), $args );

}
add_action( 'init', 'create_project_taxonomies' );

/**
 * Register a Testimonial slide post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function construction_codex_testimonial_init() {
  $labels = array(
    'name'               => __( 'Testimonial', 'post type general name', 'construction' ),
    'singular_name'      => __( 'Testimonial', 'post type singular name', 'construction' ),
    'menu_name'          => __( 'Testimonial', 'admin menu', 'construction' ),
    'name_admin_bar'     => __( 'Testimonial', 'add new on admin bar', 'construction' ),
    'add_new'            => __( 'Add New', 'Testimonial', 'construction' ),
    'add_new_item'       => __( 'Add New Testimonial', 'construction' ),
    'new_item'           => __( 'New Testimonial', 'construction' ),
    'edit_item'          => __( 'Edit Testimonial', 'construction' ),
    'view_item'          => __( 'View Testimonial', 'construction' ),
    'all_items'          => __( 'All Testimonial', 'construction' ),
    'search_items'       => __( 'Search Testimonial', 'construction' ),
    'parent_item_colon'  => __( 'Parent Testimonial:', 'construction' ),
    'not_found'          => __( 'No Testimonial found.', 'construction' ),
    'not_found_in_trash' => __( 'No Testimonial found in Trash.', 'construction' ),
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'menu_icon'      => 'dashicons-format-status',
    'publicly_queryable' => true,
    'menu_position'    => 2,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array( 'slug' => 'testimonial' ),
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => null,
    'supports'           => array( 'title', 'thumbnail', 'editor' ) 
  );

  register_post_type( 'testimonial', $args );
}

//creat post type service
function construction_codex_service_init() {
  $labels = array(
    'name'               => __( 'Services', 'post type general name', 'construction' ),
    'singular_name'      => __( 'Servcice', 'post type singular name', 'construction' ),
    'menu_name'          => __( 'Servcice', 'admin menu', 'construction' ),
    'name_admin_bar'     => __( 'Servcice', 'add new on admin bar', 'construction' ),
    'add_new'            => __( 'Add New', 'Servcice', 'construction' ),
    'add_new_item'       => __( 'Add New Servcice', 'construction' ),
    'new_item'           => __( 'New Servcice', 'construction' ),
    'edit_item'          => __( 'Edit Servcice', 'construction' ),
    'view_item'          => __( 'View Servcice', 'construction' ),
    'all_items'          => __( 'All Servcice', 'construction' ),
    'search_items'       => __( 'Search Servcice', 'construction' ),
    'parent_item_colon'  => __( 'Parent Servcice:', 'construction' ),
    'not_found'          => __( 'No Servcice found.', 'construction' ),
    'not_found_in_trash' => __( 'No Servcice found in Trash.', 'construction' ),
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'menu_icon'      => 'dashicons-format-status',
    'publicly_queryable' => true,
    'menu_position'    => 2,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => true,
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => null,
    'supports'           => array( 'title', 'thumbnail', 'editor','excerpt' ) 
  );

  register_post_type( 'service', $args );
}

//creat post type service
function construction_codex_footer_init() {
  $labels = array(
    'name'               => __( 'Footers', 'post type general name', 'construction' ),
    'singular_name'      => __( 'footer', 'post type singular name', 'construction' ),
    'menu_name'          => __( 'footer', 'admin menu', 'construction' ),
    'name_admin_bar'     => __( 'footer', 'add new on admin bar', 'construction' ),
    'add_new'            => __( 'Add New', 'footer', 'construction' ),
    'add_new_item'       => __( 'Add New footer', 'construction' ),
    'new_item'           => __( 'New footer', 'construction' ),
    'edit_item'          => __( 'Edit footer', 'construction' ),
    'view_item'          => __( 'View footer', 'construction' ),
    'all_items'          => __( 'All footer', 'construction' ),
    'search_items'       => __( 'Search footer', 'construction' ),
    'parent_item_colon'  => __( 'Parent footer:', 'construction' ),
    'not_found'          => __( 'No footer found.', 'construction' ),
    'not_found_in_trash' => __( 'No footer found in Trash.', 'construction' ),
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'menu_icon'      => 'dashicons-editor-insertmore',
    'publicly_queryable' => true,
    'menu_position'    => 2,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => false,
    'capability_type'    => 'page',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => null,
    'supports'           => array( 'title', 'thumbnail', 'editor' ) 
  );

  register_post_type( 'footer', $args );
}