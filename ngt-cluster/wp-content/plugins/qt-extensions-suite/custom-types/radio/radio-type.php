<?php

/* = custom post type release
===================================================*/



add_action('init', 'radiochannel_register_type');  


function radiochannel_register_type() {

   
	
	$labelsradio = array(
		'name' => esc_attr__("Radio channels","qt-extensions-suite"),
		'singular_name' => esc_attr__("Radio channel","qt-extensions-suite"),
		'add_new' => esc_attr__('Add new channel',"qt-extensions-suite"),
		'add_new_item' => esc_attr__("Add new radio channel","qt-extensions-suite"),
		'edit_item' => esc_attr__("Edit radio channel","qt-extensions-suite"),
		'new_item' => esc_attr__("New radio channel","qt-extensions-suite"),
		'all_items' => esc_attr__('All radio channels',"qt-extensions-suite"),
		'view_item' => esc_attr__("View radio channel","qt-extensions-suite"),
		'search_items' => esc_attr__("Search radio channels","qt-extensions-suite"),
		'not_found' =>  esc_attr__("No radio channels found","qt-extensions-suite"),
		'not_found_in_trash' => esc_attr__("No radio channels found in Trash","qt-extensions-suite"), 
		'parent_item_colon' => '',
		'menu_name' => esc_attr__("Radio channels","qt-extensions-suite")
	);
	$args = array(
		'labels' => $labelsradio,
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
		'menu_icon' => 'dashicons-media-audio',
		'menu_class' => 'color_red',
		'supports' => array('title', 'thumbnail','editor', 'page-attributes' )
	); 
    register_post_type( 'radiochannel' , $args );



	

}




/* = Fields
===================================================*/




			
$radio_details = array(
	
	array(
		'label' => 'MP3 Stream URL',		
		'id'    => 'mp3_stream_url',
		'type'  => 'text'
		)
);

$radiochannel_details_box = new custom_add_meta_box( 'radio_details', 'Radio channel details', $radio_details, 'radiochannel', true );



