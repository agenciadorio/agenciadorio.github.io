<?php

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
		'label' => 'Buy link',
		'id'    => 'general_release_details_buy_link',
		'type'  => 'text'
		),
    array(
		'label' => 'Buy link text',
		'id'    => 'general_release_details_buy_link_text',
		'type'  => 'text'
		),
    array(
		'label' => 'Catalog Number',
		'id'    => 'general_release_details_catalognumber',
		'type'  => 'text'
		),
);


$details_box = new custom_add_meta_box( 'release_details', 'Release Details', $fields_release, 'release', true );

$sample_box = new custom_add_meta_box( 'release_tracks', 'Release Tracks', $fields, 'release', true );
$buylinks_box = new custom_add_meta_box( 'release_buylinkss', 'Custom Buy Links', $fields_links, 'release', true );

