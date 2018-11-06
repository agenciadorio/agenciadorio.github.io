<?php

define ('CUSTOM_TYPE_RELEASE','release');
add_action('init', 'release_register_type');  
function release_register_type() {
	$labelsrelease = array(
        'name' => esc_attr__("Release","qt-extensions-suite"),
        'singular_name' => esc_attr__("Release","qt-extensions-suite"),
        'add_new' => esc_attr__("Add new","qt-extensions-suite"),
        'add_new_item' => esc_attr__("Add new release","qt-extensions-suite"),
        'edit_item' => esc_attr__("Edit release","qt-extensions-suite"),
        'new_item' => esc_attr__("New release","qt-extensions-suite"),
        'all_items' => esc_attr__("All releases","qt-extensions-suite"),
        'view_item' => esc_attr__("View release","qt-extensions-suite"),
        'search_items' => esc_attr__("Search release","qt-extensions-suite"),
        'not_found' => esc_attr__("No releases found","qt-extensions-suite"),
        'not_found_in_trash' => esc_attr__("No releases found in trash","qt-extensions-suite"),
        'menu_name' => esc_attr__("Album releases","qt-extensions-suite")
	);
	$args = array(
		'labels' => $labelsrelease,
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
		'menu_icon' => 'dashicons-controls-play',
		'supports' => array('title', 'thumbnail','editor', 'page-attributes' )
	); 
    register_post_type( CUSTOM_TYPE_RELEASE , $args );

	/* ============= create custom taxonomy for the releases ==========================*/
	 $labels = array(
    'name' => __( 'Release genres',"qt-extensions-suite" ),
    'singular_name' => __( 'Genre',"qt-extensions-suite" ),
    'search_items' =>  __( 'Search by genre',"qt-extensions-suite" ),
    'popular_items' => __( 'Popular genres',"qt-extensions-suite" ),
    'all_items' => __( 'All releases',"qt-extensions-suite" ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit genre',"qt-extensions-suite" ), 
    'update_item' => __( 'Update genre',"qt-extensions-suite" ),
    'add_new_item' => __( 'Add New genre',"qt-extensions-suite" ),
    'new_item_name' => __( 'New genre Name',"qt-extensions-suite" ),
    'separate_items_with_commas' => __( 'Separate genres with commas',"qt-extensions-suite" ),
    'add_or_remove_items' => __( 'Add or remove genres',"qt-extensions-suite" ),
    'choose_from_most_used' => __( 'Choose from the most used genres',"qt-extensions-suite" ),
    'menu_name' => __( 'Music genres',"qt-extensions-suite" ),
  ); 
  register_taxonomy('genre','release',array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'genre' ),
  ));
}



$prefix = 'track_';

$fields = array(
	
	array( // Repeatable & Sortable Text inputs
		'label'	=> 'Release Tracks', // <label>
		'desc'	=> 'Add one for each track in the release', // description
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
			),
			'releasetrack_artist_name' => array(
				'label' => 'Artists',
				'desc'	=> '(All artists separated bu comma)', // description
				'id' => 'releasetrack_artist_name',
				'type' => 'text'
			),
			'releasetrack_mp3_demo' => array(
				'label' => 'MP3 Demo',
				'desc'	=> '(Never upload your full quality tracks, someone can steal them)', // description
				'id' => 'releasetrack_mp3_demo',
				'type' => 'file'
			),
			/**/'releasetrack_soundcloud_url' => array(
				'label' => 'Soundcloud or Youtube',
				'desc'	=> 'Will be transformed into an embedded player in the release page', // description
				'id' 	=> 'releasetrack_scurl',
				'type' 	=> 'text'
			),
			'releasetrack_buy_url' => array(
				'label' => 'Track Buy link',
				'desc'	=> 'A link to buy the single track', // description
				'id' 	=> 'releasetrack_buyurl',
				'type' 	=> 'text'
			),
			'releasetrack_price' => array(
				'label' => 'Track price',
				'id' 	=> 'releasetrack_price',
				'type' 	=> 'text'
			),
			/*'exclude_from_playlist' => array(
				'label' => 'Exclude from Playlist',
				'desc'	=> 'Check to exclude', // description
				'id' 	=> 'exclude',
				'type' 	=> 'checkbox'
			),*/
	
		)
	)
);



$fields_links = array(
	
	array( // Repeatable & Sortable Text inputs
		'label'	=> 'Custom Buy Links', // <label>
		'desc'	=> 'Add one for each link to external websites', // description
		'id'	=> $prefix.'repeatablebuylinks', // field id and name
		'type'	=> 'repeatable', // type of field
		'sanitizer' => array( // array of sanitizers with matching kets to next array
			'featured' => 'meta_box_santitize_boolean',
			'title' => 'sanitize_text_field',
			'desc' => 'wp_kses_data'
		),
		'repeatable_fields' => array ( // array of fields to be repeated
			'custom_buylink_anchor' => array(
				'label' => 'Custom Buy Text',
				'desc'	=> '(example: Itunes, Beatport, Trackitdown)',
				'id' => 'cbuylink_anchor',
				'type' => 'text'
			),
			'custom_buylink_url' => array(
				'label' => 'Custom Buy URL ',
				'desc'	=> '(example: http://...)', // description
				'id' => 'cbuylink_url',
				'type' => 'text'
			)
		)
	)
);


	

$fields_release = array(
    array(
		'label' => 'Label',
		'id'    => 'general_release_details_label',
		'type'  => 'text'
		),
    array(
		'label' => 'Release date (YYYY-MM-DD)',
		'id'    => 'general_release_details_release_date',
		'type'  => 'date'
		),
    array(
		'label' => 'Catalog Number',
		'id'    => 'general_release_details_catalognumber',
		'type'  => 'text'
		),
    array(
		'label' => 'Use featured player',
		'id'    => 'general_release_featuredplayer',
		'type'  => 'checkbox'
		)
);


$details_box = new custom_add_meta_box( 'release_details', 'Release Details', $fields_release, 'release', true );
$sample_box = new custom_add_meta_box( 'release_tracks', 'Release Tracks', $fields, 'release', true );
$buylinks_box = new custom_add_meta_box( 'release_buylinkss', 'Custom Buy Links', $fields_links, 'release', true );




