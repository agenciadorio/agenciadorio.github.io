<?php

define ('CUSTOMTYPE_GALLERY','mediagallery');



/* = main function 
=========================================*/

function qtgallery_register_type() {

	$labelsoption = array(
  'name' => esc_attr__("Gallery","qt-extensions-suite"),
        'singular_name' => esc_attr__("Gallery","qt-extensions-suite"),
        'add_new' => esc_attr__("Add new","qt-extensions-suite"),
        'add_new_item' => esc_attr__("Add new gallery","qt-extensions-suite"),
        'edit_item' => esc_attr__("Edit gallery","qt-extensions-suite"),
        'new_item' => esc_attr__("New gallery","qt-extensions-suite"),
        'all_items' => esc_attr__("All galleries","qt-extensions-suite"),
        'view_item' => esc_attr__("View gallery","qt-extensions-suite"),
        'search_items' => esc_attr__("Search gallery","qt-extensions-suite"),
        'not_found' => esc_attr__("No galleries found","qt-extensions-suite"),
        'not_found_in_trash' => esc_attr__("No galleries found in trash","qt-extensions-suite"),
        'menu_name' => esc_attr__("Galleries","qt-extensions-suite")
    );

  	$args = array(
        'labels' => $labelsoption,
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
         'menu_icon' => 'dashicons-format-gallery',
    	'page-attributes' => false,
    	'show_in_nav_menus' => true,
    	'show_in_admin_bar' => true,
    	'show_in_menu' => true,
        'supports' => array('title','thumbnail','editor')
  	); 

    register_post_type( CUSTOMTYPE_GALLERY , $args );
  
    //add_theme_support( 'post-formats', array( 'gallery','status','video','audio' ) );
	
	$labels = array(
		'name' => esc_attr__( 'Gallery type',"qt-extensions-suite" ),
		'singular_name' => esc_attr__( 'Types',"qt-extensions-suite" ),
		'search_items' =>  esc_attr__( 'Search by type',"qt-extensions-suite" ),
		'popular_items' => esc_attr__( 'Popular type',"qt-extensions-suite" ),
		'all_items' => esc_attr__( 'All types',"qt-extensions-suite" ),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => esc_attr__( 'Edit Type',"qt-extensions-suite" ), 
		'update_item' => esc_attr__( 'Update Type',"qt-extensions-suite" ),
		'add_new_item' => esc_attr__( 'Add New Type',"qt-extensions-suite" ),
		'new_item_name' => esc_attr__( 'New Type Name',"qt-extensions-suite" ),
		'separate_items_with_commas' => esc_attr__( 'Separate Types with commas',"qt-extensions-suite" ),
		'add_or_remove_items' => esc_attr__( 'Add or remove Types',"qt-extensions-suite" ),
		'choose_from_most_used' => esc_attr__( 'Choose from the most used Types',"qt-extensions-suite" ),
		'menu_name' => esc_attr__( 'Types',"qt-extensions-suite" ),
	); 

	

    $fields = array(
        /* */           
		array(
			'label' => 'Style',
			'class' => 'style',
			'id' => 'style',
			'type' => 'select',
			'options' => array(
	                   array('label' => 'Masonry','value' => 'masonry'),
	                   array('label' => 'Carousel','value' => 'carousel')
	                   )
			),
		array( // Repeatable & Sortable Text inputs
			'label'	=> 'Gallery', // <label>
			'id'	=> 'galleryitem', // field id and name
			'type'	=> 'repeatable', // type of field
			'sanitizer' => array( // array of sanitizers with matching kets to next array
				'featured' => 'meta_box_santitize_boolean',
				'title' => 'sanitize_text_field',
				'desc' => 'wp_kses_data'
			)
			,'repeatable_fields' => array ( // array of fields to be repeated
				'title' => array(
					'label' => 'Title',
					'id' =>  'title',
					'type' => 'text'
				),
				'video' => array(
					'label' => 'Video',
					'id' =>  'video',
					'description' => 'Youtube or Vimeo url',
					'type' => 'text'
				),
				'image' => array(
					'label' => 'Image',
					'id' => 'image',
					'type' => 'image'
				)
			)
		)	                       
    );
    if(post_type_exists(CUSTOMTYPE_GALLERY)){
        if(function_exists('custom_meta_box_field')){
            $sample_box 		= new custom_add_meta_box(CUSTOMTYPE_GALLERY, 'Gallery details', $fields, CUSTOMTYPE_GALLERY, true );
        }
    }
	
}

add_action('init', 'qtgallery_register_type');  






