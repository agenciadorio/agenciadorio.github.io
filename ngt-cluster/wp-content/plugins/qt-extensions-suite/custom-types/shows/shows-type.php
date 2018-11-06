<?php

add_action('init', 'show_register_type');  
function show_register_type() {
	$labelsshow = array(
		'name' => esc_attr__("Radio Shows","qt-extensions-suite" ),
		'singular_name' => esc_attr__("Show","qt-extensions-suite" ),
		'add_new' => esc_attr__('Add new ',"qt-extensions-suite" ),
		'add_new_item' => esc_attr__('Add new show',"qt-extensions-suite" ),
		'edit_item' => esc_attr__('Edit show',"qt-extensions-suite" ),
		'new_item' => esc_attr__('New show',"qt-extensions-suite" ),
		'all_items' => esc_attr__('All shows',"qt-extensions-suite" ),
		'view_item' => esc_attr__('View show',"qt-extensions-suite" ),
		'search_items' => esc_attr__('Search show',"qt-extensions-suite" ),
		'not_found' =>  esc_attr__('No Show found',"qt-extensions-suite" ),
		'not_found_in_trash' => esc_attr__('No Shows found in trash', "qt-extensions-suite" ),
		'parent_item_colon' => '',
		'menu_name' => esc_attr__('Radio Shows',"qt-extensions-suite" )
	);
	$args = array(
		'labels' => $labelsshow,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'page',
		'has_archive' => true,
		'hierarchical' => false,
		'menu_position' => 49,
		'page-attributes' => true,
		'show_in_nav_menus' => true,
		'show_in_admin_bar' => true,
		'show_in_menu' => true,
		'menu_icon' =>  'dashicons-pressthis',
		'supports' => array('title', 'thumbnail','editor', 'excerpt', 'comments', 'revisions'  )
	); 
    register_post_type( "shows" , $args );

	/* ============= create custom taxonomy for the shows ==========================*/
	$labels = array(
	    'name' => esc_attr__( 'Genres',"qt-extensions-suite" ),
	    'singular_name' => esc_attr__( 'Genre',"qt-extensions-suite" ),
	    'search_items' =>  esc_attr__( 'Search by genre',"qt-extensions-suite" ),
	    'popular_items' => esc_attr__( 'Popular genres',"qt-extensions-suite" ),
	    'all_items' => esc_attr__( 'All shows',"qt-extensions-suite" ),
	    'parent_item' => null,
	    'parent_item_colon' => null,
	    'edit_item' => esc_attr__( 'Edit genre',"qt-extensions-suite" ), 
	    'update_item' => esc_attr__( 'Update genre',"qt-extensions-suite" ),
	    'add_new_item' => esc_attr__( 'Add New genre',"qt-extensions-suite" ),
	    'new_item_name' => esc_attr__( 'New genre Name',"qt-extensions-suite" ),
	    'separate_items_with_commas' => esc_attr__( 'Separate genres with commas',"qt-extensions-suite" ),
	    'add_or_remove_items' => esc_attr__( 'Add or remove genres',"qt-extensions-suite" ),
	    'choose_from_most_used' => esc_attr__( 'Choose from the most used genres',"qt-extensions-suite" ),
	    'menu_name' => esc_attr__( 'Genres',"qt-extensions-suite" )
  	); 
	register_taxonomy('showgenre','shows',array(
	    'hierarchical' => false,
	    'labels' => $labels,
	    'show_ui' => true,
	    'update_count_callback' => '_update_post_term_count',
	    'query_var' => true,
	    'rewrite' => array( 'slug' => 'showgenre' ),
	));
}

/*
*
*	Meta boxes ===========================================================================
*
*	======================================================================================
*/

$fields = array(
   array(
		'label' => 'Subtitle',
		'description' => 'Used in parallax header',
		'id'    => 'subtitle',
		'type'  => 'text'
		)
   	, array(
		'label' => 'Subtitle 2',
		'description' => 'Used in the parallax header',
		'id'    => 'subtitle2',
		'type'  => 'text'
		)
   /*	, array(
		'label' => 'Parallax header',
		'id'    => 'parallaxheader',
		'type'  => 'image'
		)
    */
   , array(
		'label' => 'Short show description',
		'description' => 'Used in the schedule',
		'id'    => 'show_incipit',
		'type'  => 'editor'
		)
   	
);

$sample_box = new custom_add_meta_box( 'shows_meta', 'Show details', $fields, 'shows', true );
