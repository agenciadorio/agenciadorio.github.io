<?php
define ('CUSTOM_TYPE_ARTIST','artist');
add_action('init', 'artist_register_type');  
function artist_register_type() {
	$name = CUSTOM_TYPE_ARTIST;
	
	$labels = array(
  'name' => esc_attr__("Artist","qt-extensions-suite"),
        'singular_name' => esc_attr__("Artist","qt-extensions-suite"),
        'add_new' => esc_attr__("Add new","qt-extensions-suite"),
        'add_new_item' => esc_attr__("Add new artist","qt-extensions-suite"),
        'edit_item' => esc_attr__("Edit artist","qt-extensions-suite"),
        'new_item' => esc_attr__("New artist","qt-extensions-suite"),
        'all_items' => esc_attr__("All artists","qt-extensions-suite"),
        'view_item' => esc_attr__("View artist","qt-extensions-suite"),
        'search_items' => esc_attr__("Search artist","qt-extensions-suite"),
        'not_found' => esc_attr__("No artists found","qt-extensions-suite"),
        'not_found_in_trash' => esc_attr__("No artists found in trash","qt-extensions-suite"),
        'menu_name' => esc_attr__("Artists","qt-extensions-suite")
	);
	

		
    $args = array(
        'labels' => $labels,
        'singular_label' => esc_attr__(ucfirst(CUSTOM_TYPE_ARTIST)),
        'public' => true,
        'show_ui' => true,
        'capability_type' => 'page',
		'has_archive' => true,
		'publicly_queryable' => true,
		'rewrite' => true,

	    'menu_position' => 40,
		'query_var' => true,
		'exclude_from_search' => false,
		'can_export' => true,
        'hierarchical' => false,
		'page-attributes' => true,
		'menu_icon' => 'dashicons-star-filled',
        'supports' => array('title', 'thumbnail','editor', 'page-attributes' )

    );  
    register_post_type( CUSTOM_TYPE_ARTIST , $args );
	
	$labels = array(
		'name' => esc_attr__( 'Artist genres',"qt-extensions-suite" ),
		'singular_name' => esc_attr__( 'Genres',"qt-extensions-suite" ),
		'search_items' =>  esc_attr__( 'Search by genre',"qt-extensions-suite" ),
		'popular_items' => esc_attr__( 'Popular genres',"qt-extensions-suite" ),
		'all_items' => esc_attr__( 'All Artists',"qt-extensions-suite" ).'s',
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => esc_attr__( 'Edit Genre',"qt-extensions-suite" ), 
		'update_item' => esc_attr__( 'Update Genre',"qt-extensions-suite" ),
		'add_new_item' => esc_attr__( 'Add New Genre',"qt-extensions-suite" ),
		'new_item_name' => esc_attr__( 'New Genre Name',"qt-extensions-suite" ),
		'separate_items_with_commas' => esc_attr__( 'Separate Genres with commas',"qt-extensions-suite" ),
		'add_or_remove_items' => esc_attr__( 'Add or remove Genres',"qt-extensions-suite" ),
		'choose_from_most_used' => esc_attr__( 'Choose from the most used Genres',"qt-extensions-suite" ),
		'menu_name' => esc_attr__( 'Genres',"qt-extensions-suite" )
	); 
	register_taxonomy('artistgenre','artist',array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var' => true,
		'rewrite' => array( 'slug' => 'artistgenre' )
	));
}



$artist_tab_data_arr = array(
	array(
		'label' => esc_attr__( 'Agency', "qt-extensions-suite" ),
		'id'    => '_artist_booking_contact_name',
		'type'  => 'text'
		),

    array(
		'label' => esc_attr__( 'Phone', "qt-extensions-suite" ),
		'id'    => '_artist_booking_contact_phone',
		'type'  => 'text'
		),
    array(
		'label' => esc_attr__( 'Email', "qt-extensions-suite" ),
		'id'    => '_artist_booking_contact_email',
		'type'  => 'text'
		),

    array(
		'label' => esc_attr__( 'Nationality', "qt-extensions-suite" ),
		'id'    => '_artist_nationality',
		'type'  => 'text'
		),

    array(
		'label' => esc_attr__( 'Resident in', "qt-extensions-suite" ),
		'id'    => '_artist_resident',
		'type'  => 'text'
		),
  /*  array(
		'label' => esc_attr__( 'Website', "qt-extensions-suite" ),
		'id'    => '_artist_website',
		'type'  => 'text'
		),
    array(
		'label' => esc_attr__( 'Facebook', "qt-extensions-suite" ),
		'id'    => '_artist_facebook',
		'type'  => 'text'
		),
    array(
		'label' => esc_attr__( 'Beatport', "qt-extensions-suite" ),
		'id'    => '_artist_beatport',
		'type'  => 'text'
		),
    array(
		'label' => esc_attr__( 'Soundcloud', "qt-extensions-suite" ),
		'id'    => '_artist_soundcloud',
		'type'  => 'text'
		),

    array(
		'label' => esc_attr__( 'Mixcloud', "qt-extensions-suite" ),
		'id'    => '_artist_mixcloud',
		'type'  => 'text'
		),
    array(
		'label' => esc_attr__( 'Myspace', "qt-extensions-suite" ),
		'id'    => '_artist_myspace',
		'type'  => 'text'
		),
    array(
		'label' => esc_attr__( 'Resident Advisor', "qt-extensions-suite" ),
		'id'    => '_artist_residentadv',
		'type'  => 'text'
		),

    array(
		'label' => esc_attr__( 'Twitter', "qt-extensions-suite" ),
		'id'    => '_artist_twitter',
		'type'  => 'text'
		),*/

    array(
		'label' => esc_attr__( 'Youtube Video 1', "qt-extensions-suite" ),
		'id'    => '_artist_youtubevideo1',
		'type'  => 'text'
		),
    array(
		'label' => esc_attr__( 'Youtube Video 2', "qt-extensions-suite" ),
		'id'    => '_artist_youtubevideo2',
		'type'  => 'text'
		),
    array(
		'label' => esc_attr__( 'Youtube Video 3', "qt-extensions-suite" ),
		'id'    => '_artist_youtubevideo3',
		'type'  => 'text'
		),
);

$artist_tab_data = new custom_add_meta_box( 'artist_tab_data', 'Artist data', $artist_tab_data_arr, 'artist', true );






$artist_tab_custom = array(
	/*array(
		'label' => 'Choose first tab',
		'id'    => 'custom_tab_order',
		'type' => 'radio',
		'options' => array(
			array('label' => esc_attr__('Bio',"qt-extensions-suite"),'value' => 'bio'),
			array('label' => esc_attr__('Music',"qt-extensions-suite"),'value' => 'mus'),
			array('label' => esc_attr__('Video',"qt-extensions-suite"),'value' => 'vid'),
			array('label' => esc_attr__('Booking',"qt-extensions-suite"),'value' => 'boo'),
			array('label' => esc_attr__('Custom content',"qt-extensions-suite"),'value' => 'cus')
			)


		),*/
	array(
		'label' => 'Add a custom tab content here',
		'id' => false,
		'type'  => 'chapter'
		),
	array(
		'label' => 'Custom tab title',
		'id'    => 'custom_tab_title',
		'type'  => 'text'
		),
	array(
		'label' => 'Custom tab content',
		'id'    => 'custom_tab_content',
		'type'  => 'editor'
		)
);
$artist_tab_custom_box = new custom_add_meta_box( 'artist_customtab', 'Tabs Customizer', $artist_tab_custom, 'artist', true );



?>