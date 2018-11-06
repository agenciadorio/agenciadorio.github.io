<?php

add_action('init', 'podcast_register_type');  

function podcast_register_type() {

	$labelspodcast = array(
		'name' => esc_attr__("Podcast","qt-extensions-suite"),
		'singular_name' => esc_attr__("Podcast","qt-extensions-suite"),
		'add_new' => esc_attr__("Add new","qt-extensions-suite"),
		'add_new_item' => esc_attr__("Add new podcast","qt-extensions-suite"),
		'edit_item' => esc_attr__("Edit podcast","qt-extensions-suite"),
		'new_item' => esc_attr__("New podcast","qt-extensions-suite"),
		'all_items' => esc_attr__("All podcasts","qt-extensions-suite"),
		'view_item' => esc_attr__("View podcast","qt-extensions-suite"),
		'search_items' => esc_attr__("Search podcast","qt-extensions-suite"),
		'not_found' => esc_attr__("No podcasts found","qt-extensions-suite"),
		'not_found_in_trash' => esc_attr__("No podcasts found in trash","qt-extensions-suite"),
		'menu_name' => esc_attr__("Podcasts","qt-extensions-suite")
  	);

  	$args = array(
		'labels' => $labelspodcast,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'page',
		'has_archive' => true,
		'hierarchical' => false,
		'menu_position' => 40,
		'page-attributes' => true,
		'show_in_nav_menus' => true,
		'show_in_admin_bar' => true,
		'show_in_menu' => true,
		 'menu_icon' => 'dashicons-megaphone',
		'supports' => array('title', 'thumbnail','editor' )
  	); 

	register_post_type( 'podcast' , $args );

	/* ============= create custom taxonomy for the podcasts ==========================*/

	$labels = array(
		'name' => esc_attr__( 'Podcast filters',"qt-extensions-suite" ),
		'singular_name' => esc_attr__( 'Filter',"qt-extensions-suite" ),
		'search_items' =>  esc_attr__( 'Search by filter',"qt-extensions-suite" ),
		'popular_items' => esc_attr__( 'Popular filters',"qt-extensions-suite" ),
		'all_items' => esc_attr__( 'All Podcasts',"qt-extensions-suite" ),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => esc_attr__( 'Edit Filter',"qt-extensions-suite" ), 
		'update_item' => esc_attr__( 'Update Filter',"qt-extensions-suite" ),
		'add_new_item' => esc_attr__( 'Add New Filter',"qt-extensions-suite" ),
		'new_item_name' => esc_attr__( 'New Filter Name',"qt-extensions-suite" ),
		'separate_items_with_commas' => esc_attr__( 'Separate Filters with commas',"qt-extensions-suite" ),
		'add_or_remove_items' => esc_attr__( 'Add or remove Filters',"qt-extensions-suite" ),
		'choose_from_most_used' => esc_attr__( 'Choose from the most used Filters',"qt-extensions-suite" ),
		'menu_name' => esc_attr__( 'Filters',"qt-extensions-suite" )
  	); 

  register_taxonomy('podcastfilter','podcast',array(
	'hierarchical' => false,
	'labels' => $labels,
	'show_ui' => true,
	'update_count_callback' => '_update_post_term_count',
	'query_var' => true,
	'rewrite' => array( 'slug' => 'podcastfilter' ),
  ));

}




$podcast_tab_custom = array(

	array(
		'label' => esc_attr__( 'Artist Name', "qt-extensions-suite" ),
		'id'    => '_podcast_artist',
		'type'  => 'text'
		),
	array(
		'label' => esc_attr__( 'Date', "qt-extensions-suite" ),
		'id'    => '_podcast_date',
		'type'  => 'date'
		),
	array(
		'label' => esc_attr__( 'Soundcloud/Mixcloud/Youtube/MP3', "qt-extensions-suite" ),
		'id'    => '_podcast_resourceurl',
		'desc' => esc_attr__('To link an external podcast like Soundcloud, choose "From Url" in the media window. If uploading a file, you can have file size restrictions. Please contact your hosting provider to remove those limits',"qt-extensions-suite"),
		'type'  => 'file'
		),
);
$podcast_tab_custom_box = new custom_add_meta_box( 'podcast_customtab', esc_attr__('Podcast details', "qt-extensions-suite"), $podcast_tab_custom, 'podcast', true );
