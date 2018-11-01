<?php

/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class WPP_Post_type_Poll{
	
	public function __construct(){
		add_action( 'init', array( $this, 'wpp_post_type_poll' ), 0 );
		// add_action( 'init', array( $this, 'wpp_post_type_wpp_by_post' ), 0 );
	}
	
	public function wpp_post_type_poll() {
		if ( post_type_exists( "poll" ) )
		return;

		$singular  = __( 'Poll', WPP_TEXT_DOMAIN );
		$plural    = __( 'Polls', WPP_TEXT_DOMAIN );
	 
	 
		register_post_type( "poll",
			apply_filters( "register_post_type_poll", array(
				'labels' => array(
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => $singular,
					'all_items'             => __( 'All', WPP_TEXT_DOMAIN ) 	." $plural",
					'add_new' 				=> __( 'Add', WPP_TEXT_DOMAIN ) 	." $singular",
					'add_new_item' 			=> __( 'Add', WPP_TEXT_DOMAIN ) 	." $singular",
					'edit' 					=> __( 'Edit', WPP_TEXT_DOMAIN ),
					'edit_item' 			=> __( 'Edit', WPP_TEXT_DOMAIN ) 	." $singular",
					'new_item' 				=> __( 'New', WPP_TEXT_DOMAIN ) 	." $singular",
					'view' 					=> __( 'View', WPP_TEXT_DOMAIN ) 	." $singular",
					'view_item' 			=> __( 'View', WPP_TEXT_DOMAIN ) 	." $singular",
					'search_items' 			=> __( 'Search', WPP_TEXT_DOMAIN ) 	." $singular",
					'not_found' 			=> sprintf( __( 'No %s found', WPP_TEXT_DOMAIN ), $singular ),
					'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', WPP_TEXT_DOMAIN ), $plural ),
					'parent' 				=> __( 'Parent', WPP_TEXT_DOMAIN ) 	." $singular",
				),
				'description' => __( 'This is where you can create and manage', WPP_TEXT_DOMAIN ) ." $plural",
				'public' 				=> true,
				'show_ui' 				=> true,
				'capability_type' 		=> 'post',
				'map_meta_cap'          => true,
				'publicly_queryable' 	=> true,
				'exclude_from_search' 	=> false,
				'hierarchical' 			=> false,
				'rewrite' 				=> true,
				'query_var' 			=> true,
				'supports' 				=> array('comments','thumbnail'),
				'show_in_nav_menus' 	=> false,
				'menu_icon' => 'dashicons-chart-bar',
			) )
		); 
		
		$singular  = __( 'Poll Category', WPP_TEXT_DOMAIN );
		$plural    = __( 'Poll Categories', WPP_TEXT_DOMAIN );
	 
		register_taxonomy( "poll_cat",
			apply_filters( 'register_taxonomy_poll_cat_object_type', array( 'poll' ) ),
	       	apply_filters( 'register_taxonomy_poll_cat_args', array(
				'hierarchical' 			=> true,
		        'show_admin_column' 	=> true,					
		        'update_count_callback' => '_update_post_term_count',
		        'label' 				=> $plural,
		        'labels' => array(
					'name'              => $plural,
					'singular_name'     => $singular,
					'menu_name'         => ucwords( $plural ),
					'search_items'      => __( 'Search', WPP_TEXT_DOMAIN ) 	." $singular",
					'all_items'         => __( 'All', WPP_TEXT_DOMAIN ) 	." $plural",
					'parent_item'       => __( 'Parent', WPP_TEXT_DOMAIN ) 	." $singular",
					'parent_item_colon' => __( 'Parent', WPP_TEXT_DOMAIN ) 	." $singular",
					'edit_item'         => __( 'Edit', WPP_TEXT_DOMAIN ) 	." $singular",
					'update_item'       => __( 'Update', WPP_TEXT_DOMAIN ) 	." $singular",
					'add_new_item'      => __( 'Add', WPP_TEXT_DOMAIN ) 	." $singular",
					'new_item_name'     => sprintf( __( 'New %s Name', WPP_TEXT_DOMAIN ),  $singular )
	            ),
		        'show_ui' 				=> true,
		        'public' 	     		=> true,
				'rewrite' => array(
					'slug' => 'poll_cat',
					'with_front' => false,
					'hierarchical' => true
				),
			) )
		);
			
			
			
	}
	
} new WPP_Post_type_Poll();