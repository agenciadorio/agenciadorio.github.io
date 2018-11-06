<?php

add_action('init', 'schedule_register_type');  

function schedule_register_type() {
	$labelsschedule = array(
		'name' => esc_attr__("Schedule","qt-extensions-suite" ),
		'singular_name' => esc_attr__("Schedule","qt-extensions-suite" ),
		'add_new' => esc_attr__('Add New ',"qt-extensions-suite" ),
		'add_new_item' => esc_attr__('Add New Schedule',"qt-extensions-suite" ),
		'edit_item' => esc_attr__('Edit Schedule',"qt-extensions-suite" ),
		'new_item' => esc_attr__('New Schedule',"qt-extensions-suite" ),
		'all_items' => esc_attr__('All Schedule',"qt-extensions-suite" ),
		'view_item' => esc_attr__('View Schedule',"qt-extensions-suite" ),
		'search_items' => esc_attr__('Search Schedule',"qt-extensions-suite" ),
		'not_found' =>  esc_attr__('No Schedule found',"qt-extensions-suite" ),
		'not_found_in_trash' => esc_attr__('No Schedule found in Trash', "qt-extensions-suite" ),
		'parent_item_colon' => '',
		'menu_name' => esc_attr__('Radio schedule',"qt-extensions-suite" )
	);
	$args = array(
		'labels' => $labelsschedule,
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
		'menu_icon' =>  'dashicons-clipboard',
		'supports' => array('title','page-attributes', 'thumbnail')
	); 
    register_post_type( "schedule" , $args );



    /* ============= create custom taxonomy for the shows ==========================*/
	$labels = array(
	    'name' => esc_attr__( 'Categories',"qt-extensions-suite" ),
	    'singular_name' => esc_attr__( 'Category',"qt-extensions-suite" ),
	    'search_items' =>  esc_attr__( 'Search by category',"qt-extensions-suite" ),
	    'popular_items' => esc_attr__( 'Popular categories',"qt-extensions-suite" ),
	    'all_items' => esc_attr__( 'All categories',"qt-extensions-suite" ),
	    'parent_item' => null,
	    'parent_item_colon' => null,
	    'edit_item' => esc_attr__( 'Edit category',"qt-extensions-suite" ), 
	    'update_item' => esc_attr__( 'Update category',"qt-extensions-suite" ),
	    'add_new_item' => esc_attr__( 'Add New category',"qt-extensions-suite" ),
	    'new_item_name' => esc_attr__( 'New category Name',"qt-extensions-suite" ),
	    'separate_items_with_commas' => esc_attr__( 'Separate categories with commas',"qt-extensions-suite" ),
	    'add_or_remove_items' => esc_attr__( 'Add or remove categories',"qt-extensions-suite" ),
	    'choose_from_most_used' => esc_attr__( 'Choose from the most used categories',"qt-extensions-suite" ),
	    'menu_name' => esc_attr__( 'Schedule categories',"qt-extensions-suite" )
  	); 
	register_taxonomy('schedule_cat','schedule',array(
	    'hierarchical' => false,
	    'labels' => $labels,
	    'show_ui' => true,
	    'update_count_callback' => '_update_post_term_count',
	    'query_var' => true,
	    'rewrite' => array( 'slug' => 'schedule_cat' ),
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
		'label' => 'Happens only on a specific day (optional)',
		'description' => 'Used to identify the current show',
		'id'    => 'specific_day',
		'type'  => 'date'
		),
    array(
		'label' => 'Day of the week (Recursive)',
		'description' => 'Used to identify the current show',
		'id'    => 'week_day',
		'type'  => 'checkbox_group',
		'options' => array( 
		                   array('label'=> esc_attr__("Monday","qt-extensions-suite"), 'value' => 'mon'),
		                   array('label'=> esc_attr__("Tuesday","qt-extensions-suite"), 'value' => 'tue'),
		                   array('label'=> esc_attr__("Wednesday","qt-extensions-suite"), 'value' => 'wed'),
		                   array('label'=> esc_attr__("Thursday","qt-extensions-suite"), 'value' => 'thu'),
		                   array('label'=> esc_attr__("Friday","qt-extensions-suite"), 'value' => 'fri'),
		                   array('label'=> esc_attr__("Saturday","qt-extensions-suite"), 'value' => 'sat'),
		                   array('label'=> esc_attr__("Sunday","qt-extensions-suite"), 'value' => 'sun')
		                   )
		),
	array( // Repeatable & Sortable Text inputs
		'label'	=> 'Shows', // <label>
		'desc'	=> 'Add here the shows', // description
		'id'	=> 'track_repeatable', // field id and name
		'type'	=> 'repeatable', // type of field
		'sanitizer' => array( // array of sanitizers with matching kets to next array
			'featured' => 'meta_box_santitize_boolean',
			'title' => 'sanitize_text_field',
			'desc' => 'wp_kses_data'
		),
		'repeatable_fields' => array ( // array of fields to be repeated
			'show_id' => array(
				'label' => 'Show',
				'id' => 'show_id',
				'posttype' => 'shows',
				'type' => 'post_chosen'
			),
			'show_time' => array(
				'label' => 'Time (HH:MM)',
				'id' => 'show_time',
				'type' => 'time'
			)
			,'show_end' => array(
				'label' => 'Time End (HH:MM)',
				'id' => 'show_time_end',
				'type' => 'time'
			)
		)
	)
);

$sample_box = new custom_add_meta_box( 'schedule_shows', 'Schedule shows', $fields, 'schedule', true );




