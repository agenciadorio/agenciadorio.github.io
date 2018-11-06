<?php

add_action('init', 'member_register_type');  
function member_register_type() {
	$labelsmember = array(
		'name' => esc_attr__("Staff Members","qt-extensions-suite" ),
		'singular_name' => esc_attr__("Staff member","qt-extensions-suite" ),
		'add_new' => esc_attr__('Add new member',"qt-extensions-suite" ),
		'add_new_item' => esc_attr__('Add new member',"qt-extensions-suite" ),
		'edit_item' => esc_attr__('Edit member',"qt-extensions-suite" ),
		'new_item' => esc_attr__('New member',"qt-extensions-suite" ),
		'all_items' => esc_attr__('All members',"qt-extensions-suite" ),
		'view_item' => esc_attr__('View member',"qt-extensions-suite" ),
		'search_items' => esc_attr__('Search member',"qt-extensions-suite" ),
		'not_found' =>  esc_attr__('No member found',"qt-extensions-suite" ),
		'not_found_in_trash' => esc_attr__('No members found in Trash', "qt-extensions-suite" ),
		'parent_item_colon' => '',
		'menu_name' => esc_attr__('Staff members',"qt-extensions-suite" )
	);
	$args = array(
		'labels' => $labelsmember,
		'public' => true,
		'publicly_queryable' => true,
		'member_ui' => true, 
		'member_in_menu' => true, 
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'page',
		'has_archive' => true,
		'hierarchical' => false,
		'page-attributes' => true,
		'member_in_nav_menus' => true,
		'member_in_admin_bar' => true,
		'member_in_menu' => true,
		'menu_icon' =>  'dashicons-businessman',
		'menu_position' => 40,
		'supports' => array('title', 'thumbnail','page-attributes','editor', 'revisions'  )
	); 
    register_post_type( "members" , $args );

	/* ============= create custom taxonomy for the members ==========================*/
	$labels = array(
	    'name' => esc_attr__( 'Types',"qt-extensions-suite" ),
	    'singular_name' => esc_attr__( 'Type',"qt-extensions-suite" ),
	    'search_items' =>  esc_attr__( 'Search by Type',"qt-extensions-suite" ),
	    'popular_items' => esc_attr__( 'Popular Types',"qt-extensions-suite" ),
	    'all_items' => esc_attr__( 'All members',"qt-extensions-suite" ),
	    'parent_item' => null,
	    'parent_item_colon' => null,
	    'edit_item' => esc_attr__( 'Edit Type',"qt-extensions-suite" ), 
	    'update_item' => esc_attr__( 'Update Type',"qt-extensions-suite" ),
	    'add_new_item' => esc_attr__( 'Add New Type',"qt-extensions-suite" ),
	    'new_item_name' => esc_attr__( 'New Type Name',"qt-extensions-suite" ),
	    'separate_items_with_commas' => esc_attr__( 'Separate Types with commas',"qt-extensions-suite" ),
	    'add_or_remove_items' => esc_attr__( 'Add or remove Types',"qt-extensions-suite" ),
	    'choose_from_most_used' => esc_attr__( 'Choose from the most used Types',"qt-extensions-suite" ),
	    'menu_name' => esc_attr__( 'Member types',"qt-extensions-suite" )
  	); 
	register_taxonomy('membertype','members',array(
	    'hierarchical' => false,
	    'labels' => $labels,
	    'member_ui' => true,
	    'update_count_callback' => '_update_post_term_count',
	    'query_var' => true,
	    'rewrite' => array( 'slug' => 'membertype' )
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
		'label' => 'Portrait image (squared, 300x300px)',
		'id'    => 'portrait',
		'type'  => 'image'
		)
	,array(
		'label' => 'Role',
		'id'    => 'member_role',
		'type'  => 'text'
		)
   	,array(
		'label' => 'Facebook link',
		'id'    => 'QT_facebook',
		'type'  => 'text'
		)
   	,array(
		'label' => 'Twitter link',
		'id'    => 'QT_twitter',
		'type'  => 'text'
		)
   	,array(
		'label' => 'Pinterest link',
		'id'    => 'QT_pinterest',
		'type'  => 'text'
		)
   	,array(
		'label' => 'Vimeo link',
		'id'    => 'QT_vimeo',
		'type'  => 'text'
		)
   	,array(
		'label' => 'Wordpress link',
		'id'    => 'QT_wordpress',
		'type'  => 'text'
		)
   	,array(
		'label' => 'Youtube link',
		'id'    => 'QT_youtube',
		'type'  => 'text'
		)
   	,array(
		'label' => 'Soundcloud link',
		'id'    => 'QT_soundcloud',
		'type'  => 'text'
		)
   	,array(
		'label' => 'Myspace link',
		'id'    => 'QT_space',
		'type'  => 'text'
		)
   	,array(
		'label' => 'Itunes link',
		'id'    => 'QT_itunes',
		'type'  => 'text'
		)
   	,array(
		'label' => 'Mixcloud link',
		'id'    => 'QT_mixcloud',
		'type'  => 'text'
		)
   	,array(
		'label' => 'Resident Advisor link',
		'id'    => 'QT_resident-advisor',
		'type'  => 'text'
		)
   	,array(
		'label' => 'ReverbNation link',
		'id'    => 'QT_reverbnation',
		'type'  => 'text'
		)

    
   	
);

$sample_box = new custom_add_meta_box( 'members_meta', 'member details', $fields, 'members', true );
