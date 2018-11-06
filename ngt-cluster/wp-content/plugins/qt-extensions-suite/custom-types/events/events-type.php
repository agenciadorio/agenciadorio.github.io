<?php
if(!defined('CUSTOM_TYPE_EVENT')){
	define ('CUSTOM_TYPE_EVENT','event');
}
define ('EVENT_PREFIX','event');
define ( 'CUSTOM_PLUGIN_DIR_EVENTS', plugin_dir_url( __FILE__ )  . '/custom-types/qt-events/' );



/* = main function 
=========================================*/

function event_register_type() {

	$labelsevent = array(
        'name' => esc_attr__("Event","qt-extensions-suite"),
        'singular_name' => esc_attr__("Event","qt-extensions-suite"),
        'add_new' => esc_attr__("Add new","qt-extensions-suite"),
        'add_new_item' => esc_attr__("Add new event","qt-extensions-suite"),
        'edit_item' => esc_attr__("Edit event","qt-extensions-suite"),
        'new_item' => esc_attr__("New event","qt-extensions-suite"),
        'all_items' => esc_attr__("All events","qt-extensions-suite"),
        'view_item' => esc_attr__("View event","qt-extensions-suite"),
        'search_items' => esc_attr__("Search event","qt-extensions-suite"),
        'not_found' => esc_attr__("No events found","qt-extensions-suite"),
        'not_found_in_trash' => esc_attr__("No events found in trash","qt-extensions-suite"),
        'menu_name' => esc_attr__("Events","qt-extensions-suite")
    );

  $args = array(
        'labels' => $labelsevent,
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
         'menu_icon' => 'dashicons-calendar-alt',
    	'page-attributes' => true,
    	'show_in_nav_menus' => true,
    	'show_in_admin_bar' => true,
    	'show_in_menu' => true,
        'supports' => array('title','thumbnail','editor','page-attributes'/*,'post-formats'*/)
  ); 

    register_post_type( CUSTOM_TYPE_EVENT , $args );
  
    //add_theme_support( 'post-formats', array( 'gallery','status','video','audio' ) );
	
	 $labels = array(
		'name' => esc_attr__( 'Event type',"qt-extensions-suite" ),
		'singular_name' => esc_attr__( 'Types',"qt-extensions-suite" ),
		'search_items' =>  esc_attr__( 'Search by genre',"qt-extensions-suite" ),
		'popular_items' => esc_attr__( 'Popular genres',"qt-extensions-suite" ),
		'all_items' => esc_attr__( 'All events',"qt-extensions-suite" ),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => esc_attr__( 'Edit Type',"qt-extensions-suite" ), 
		'update_item' => esc_attr__( 'Update Type',"qt-extensions-suite" ),
		'add_new_item' => esc_attr__( 'Add New Type',"qt-extensions-suite" ),
		'new_item_name' => esc_attr__( 'New Type Name',"qt-extensions-suite" ),
		'separate_items_with_commas' => esc_attr__( 'Separate Types with commas',"qt-extensions-suite" ),
		'add_or_remove_items' => esc_attr__( 'Add or remove Types',"qt-extensions-suite" ),
		'choose_from_most_used' => esc_attr__( 'Choose from the most used Types',"qt-extensions-suite" ),
		'menu_name' => esc_attr__( 'Event types',"qt-extensions-suite" ),
	  ); 

	  register_taxonomy('eventtype','event',array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var' => true,
		'rewrite' => array( 'slug' => 'eventtype' ),
	  ));

    

	
}

add_action('init', 'event_register_type');  













if(!function_exists('qt_add_eventmetas')){
function qt_add_eventmetas (){
	



	$event_meta_boxfields = array(
	    array(
			'label' => esc_attr__('Date', "qt-extensions-suite"),
			'id'    =>  EVENT_PREFIX.'date',
			'type'  => 'date'
		),

		  array(
			'label' => esc_attr__('Facebook Event Link', "qt-extensions-suite"),
			'id'    => EVENT_PREFIX.'facebooklink',
			'type'  => 'text'
		),
		  array( // Repeatable & Sortable Text inputs
			'label'	=> esc_attr__('Ticket Buy Links', "qt-extensions-suite"), // <label>
			'desc'	=> 'Add one for each link to external websites',esc_attr__('Street', "qt-extensions-suite") ,// description
			'id'	=> EVENT_PREFIX.'repeatablebuylinks', // field id and name
			'type'	=> 'repeatable', // type of field
			'sanitizer' => array( // array of sanitizers with matching kets to next array
				'featured' => 'meta_box_santitize_boolean',
				'title' => 'sanitize_text_field',
				'desc' => 'wp_kses_data'
			),
			'repeatable_fields' => array ( // array of fields to be repeated
				'custom_buylink_anchor' => array(
					'label' => esc_attr__('Ticket buy text', "qt-extensions-suite"),
					'desc'	=> '(example: This website, or Ticket One, or something else)',
					'id' => 'cbuylink_anchor',
					'type' => 'text'
				),
				'custom_buylink_url' => array(
					'label' => esc_attr__('Ticket buy link ', "qt-extensions-suite"),
					'id' => 'cbuylink_url',
					'type' => 'text'
				)
			)
		)  
	);
	$event_meta_box= new custom_add_meta_box( 'event_customtab', esc_attr__('Event details', "qt-extensions-suite"), $event_meta_boxfields, 'event', true );

}}



add_action('init', 'qt_add_eventmetas');  























/* = Visualization
========================================================*/



function do_eventmap($coord,$title){
			$latlong = explode(',',$coord);


?>

	<h2 class="qw-page-subtitle qw-top30"><?php echo esc_attr__("Event Map","qt-extensions-suite"); ?></h2><div class="qw-separator"></div>
 	<style>
      
    </style>
   

    <?php
			
			return "
				<div id=\"map\" class=\"qtevent_map\" style=\"width: 100%; height: 370px\"></div> 
				<script type=\"text/javascript\" id=\"qteventscript\" >
				var mylat = ".esc_attr($latlong[0]).";
				var mylon = ".esc_attr($latlong[1]).";
				var nomesede = \"".addslashes($title)."\";
				var locations = [
				  [nomesede, mylat, mylon, 4]
				];
				var map = new google.maps.Map(document.getElementById('map'), {
				  zoom: 16, center: new google.maps.LatLng(mylat, mylon), mapTypeId: google.maps.MapTypeId.ROADMAP
				});
				var infowindow = new google.maps.InfoWindow();
				var marker, i;
			
				for (i = 0; i < locations.length; i++) {  
				  marker = new google.maps.Marker({
					position: new google.maps.LatLng(locations[i][1], locations[i][2]),
					map: map
				  });
				  google.maps.event.addListener(marker, 'click', (function(marker, i) {
					return function() {
					  infowindow.setContent(locations[i][0]);
					  infowindow.open(map, marker);
					}
				  })(marker, i));
				}
			  </script>";
		}
function do_eventcomments($link){
	return '<h2 class=\"qw-page-subtitle qw-top30\">'.esc_attr__("Comments","qt-extensions-suite").'</h2><hr class="qw-separator"><div id="fb-root"></div><div class="eventcomments"><script src="http://connect.facebook.net/'.esc_url(get_bloginfo( 'language' )).'/all.js#appId=187479484629057&amp;xfbml=1"></script><fb:comments href="'.esc_url($link).'" num_posts="100" width="550"></fb:comments></div>';

}




if(!is_admin()){
	//add_filter ('the_content','add_event_fields_in_content');
}

//include 'widget.php';
include 'eventslist_by_date.php';
//include 'shortcode.php';