<?php

define ('CUSTOM_TYPE_CHART','chart');
add_action('init', 'chart_register_type');  
function chart_register_type() {
	$labelschart = array(
		'name' => esc_attr__("Charts","qt-extensions-suite"),
		'singular_name' => esc_attr__("Chart","qt-extensions-suite"),
		'add_new' => esc_attr__('Add new chart', "qt-extensions-suite"),
		'add_new_item' => esc_attr__("Add new chart","qt-extensions-suite"),
		'edit_item' => esc_attr__("Edit chart","qt-extensions-suite"),
		'new_item' => esc_attr__("New chart","qt-extensions-suite"),
		'all_items' => esc_attr__("All charts","qt-extensions-suite"),
		'view_item' => esc_attr__("View chart","qt-extensions-suite"),
		'search_items' => esc_attr__("Search charts","qt-extensions-suite"),
		'not_found' =>  esc_attr__("No charts found","qt-extensions-suite"),
		'not_found_in_trash' => esc_attr__("No charts found in trash","qt-extensions-suite"),
		'parent_item_colon' => '',
		'menu_name' => esc_attr__("Song charts","qt-extensions-suite")
	);
	$args = array(
		'labels' => $labelschart,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'page',
		'has_archive' => true,
		'hierarchical' => false,
		'menu_position' => null,
		'page-attributes' => true,
		'show_in_nav_menus' => true,
		'show_in_admin_bar' => true,
		'show_in_menu' => true,
		'menu_position' => 41,
		'menu_icon' => 'dashicons-playlist-audio',
		'supports' => array('title', 'thumbnail','editor' )
	); 
	register_post_type( CUSTOM_TYPE_CHART , $args );

	/* ============= create custom taxonomy for the charts ==========================*/
	 $labels = array(
	'name' => esc_attr__( 'Chart categories',"qt-extensions-suite"),
	'singular_name' => esc_attr__( 'Category',"qt-extensions-suite"),
	'search_items' =>  esc_attr__( 'Search by category',"qt-extensions-suite" ),
	'popular_items' => esc_attr__( 'Popular categorys',"qt-extensions-suite" ),
	'all_items' => esc_attr__( 'All charts',"qt-extensions-suite" ),
	'parent_item' => null,
	'parent_item_colon' => null,
	'edit_item' => esc_attr__( 'Edit category',"qt-extensions-suite" ), 
	'update_item' => esc_attr__( 'Update category',"qt-extensions-suite" ),
	'add_new_item' => esc_attr__( 'Add New category',"qt-extensions-suite" ),
	'new_item_name' => esc_attr__( 'New category Name',"qt-extensions-suite" ),
	'separate_items_with_commas' => esc_attr__( 'Separate categorys with commas',"qt-extensions-suite" ),
	'add_or_remove_items' => esc_attr__( 'Add or remove categorys',"qt-extensions-suite" ),
	'choose_from_most_used' => esc_attr__( 'Choose from the most used categorys',"qt-extensions-suite" ),
	'menu_name' => esc_attr__( 'Chart categories',"qt-extensions-suite" ),
  ); 
  register_taxonomy('chartcategory','chart',array(
	'hierarchical' => false,
	'labels' => $labels,
	'show_ui' => true,
	'update_count_callback' => '_update_post_term_count',
	'query_var' => true,
	'rewrite' => array( 'slug' => 'chartcategory' ),
  ));
}



$fields_chart = array(
	array( // Repeatable & Sortable Text inputs
		'label'	=> 'Chart Tracks', // <label>
		'desc'	=> 'Add one for each track in the chart', // description
		'id'	=> 'track_repeatable', // field id and name
		'type'	=> 'repeatable', // type of field
		'sanitizer' => array( // array of sanitizers with matching kets to next array
			'featured' => 'meta_box_santitize_boolean',
			'title' => 'sanitize_text_field',
			'desc' => 'wp_kses_data'
		),
		
		'repeatable_fields' => array ( // array of fields to be repeated
			'releasetrack_track_title' => array(
				'label' => 'Title',
				'id' => 'releasetrack_track_title',
				'type' => 'text'
			)
			,'releasetrack_artist_name' => array(
				'label' => 'Artist/s',
				//'desc'	=> '(All artists separated by comma)', // description
				'id' => 'releasetrack_artist_name',
				'type' => 'text'
			)
			,'releasetrack_soundcloud_url' => array(
				'label' => 'Soundcloud or MP3 Url',
				'desc'	=> 'Will be transformed into an embedded player in the chart page', // description
				'id' 	=> 'releasetrack_scurl',
				'type' 	=> 'text'
			)
			,'releasetrack_buy_url' => array(
				'label' => 'Track Buy link',
				'desc'	=> 'A link to buy the single track', // description
				'id' 	=> 'releasetrack_buyurl',
				'type' 	=> 'text'
			)
			,'releasetrack_img' => array(
				'label' => 'Cover',
				'desc'	=> 'Better 600x600', // description
				'id' => 'releasetrack_img',
				'type' => 'image'
			)
	
		)
	)
);





$tracks_box = new custom_add_meta_box( 'chart_tracks', 'Chart Tracks', $fields_chart, 'chart', true );




