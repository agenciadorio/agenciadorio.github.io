<?php 

load_theme_textdomain('nation', get_template_directory() . '/languages');

/**
 * Optional: set 'ot_show_pages' filter to false.
 * This will hide the settings & documentation pages.
 */
add_filter( 'ot_show_pages', '__return_false' );

/**
 * Required: set 'ot_theme_mode' filter to true.
 */
add_filter( 'ot_theme_mode', '__return_true' );

/**
 * Required: include OptionTree.
 */
include_once( 'option-tree/ot-loader.php' );

/**
 * Theme Options
 */
include_once( 'includes/theme-options.php' );

// Load external file to add support for MultiPostThumbnails. Allows you to set more than one "feature image" per post.
require_once('includes/multi-post-thumbnails.php');

if ( ! function_exists( 'ot_wpml_filter' ) ) {
  function ot_wpml_filter( $options, $option_id ) {
    // Return translated strings using WMPL
    if ( function_exists('icl_t') ) {
      $settings = get_option( 'option_tree_settings' );
      if ( isset( $settings['settings'] ) ) {
        foreach( $settings['settings'] as $setting ) {
          // List Item & Slider
          if ( $option_id == $setting['id'] && in_array( $setting['type'], array( 'list-item', 'slider' ) ) ) {
            foreach( $options[$option_id] as $key => $value ) {
              foreach( $value as $ckey => $cvalue ) {
                $id = $option_id . '_' . $ckey . '_' . $key;
                $_string = icl_t( 'Theme Options', $id, $cvalue );
                if ( ! empty( $_string ) ) {
                  $options[$option_id][$key][$ckey] = $_string;
                }
              }
            }
          // All other acceptable option types
          } else if ( $option_id == $setting['id'] && in_array( $setting['type'], apply_filters( 'ot_wpml_option_types', array( 'text', 'textarea', 'textarea-simple' ) ) ) ) {
            $_string = icl_t( 'Theme Options', $option_id, $options[$option_id] );
            if ( ! empty( $_string ) ) {
              $options[$option_id] = $_string;
            }
          }
        }
      }
    }
    return $options[$option_id];
  }
}

if ( ! function_exists( 'ot_get_option' ) ) {
  function ot_get_option( $option_id, $default = '' ) {
    /* get the saved options */ 
    $options = get_option( 'option_tree' );
    /* look for the saved value */
    if ( isset( $options[$option_id] ) && '' != $options[$option_id] ) {
      return ot_wpml_filter( $options, $option_id );
    }
    return $default;
  }
}

function strip_array_indices( $ArrayToStrip ) {
    foreach( $ArrayToStrip as $objArrayItem) {
        $NewArray[] =  $objArrayItem;
    }
 
    return( $NewArray );
}

// Loading JS scripts and CSS style
add_action( 'wp_enqueue_scripts','nation_include' );

function nation_include() {
	global $wpdb, $wp_locale;
	
	// register script 
	wp_register_script( 'allscript', get_template_directory_uri() . '/js/allscript.js', array('jquery','smoothscroll','jquery-ui-datepicker'), '1.0', false );
	wp_register_script( 'jflickrfeed', get_template_directory_uri() . '/js/jflickrfeed.js', array('jquery'), '3.0', false );
	wp_register_script( 'lightbox', get_template_directory_uri() . '/js/lightbox.js', array('jquery'), '0.5', false );
	wp_register_script( 'retina', get_template_directory_uri() . '/js/retina.js', array('jquery'), '3.0', false );
	wp_register_script( 'smoothscroll', get_template_directory_uri() . '/js/smoothScroll.js', array('jquery'), '1.2.1', false ); 
	wp_register_script( 'quovolver', get_template_directory_uri() . '/js/quovolver.js', array('jquery'), '1.0', false );
	
	wp_register_style( 'bookingcalendar', get_stylesheet_directory_uri() . '/css/booking-calendar-pro.css' );
	wp_register_style( 'settings', get_stylesheet_directory_uri() . '/css/settings.css' );
	wp_register_style( 'style',	get_stylesheet_directory_uri() . '/style.css' );
	wp_register_style( 'options', get_stylesheet_directory_uri() . '/css/options.css' );
	
	$gmapsKey = ot_get_option('gmaps_key');
	wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key='.$gmapsKey.'&sensor=false');  


	// load script
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_script('comment-reply');
	wp_enqueue_script('smoothscroll');
	
	if ( is_page_template('home-page.php') ) {
		wp_enqueue_script( 'quovolver' );
	}
	
	if ( is_page_template('gallery.php') || is_page_template('gallery-without-header.php') || is_page_template('gallery-with-sidebar.php') ) {
		wp_enqueue_script( 'lightbox' );
	}
	
	wp_enqueue_style('bookingcalendar');
	wp_enqueue_style('settings');
	wp_enqueue_style('style');
	wp_enqueue_style('options');
	
	$protocol = is_ssl() ? 'https' : 'http';
	wp_enqueue_style( 'mytheme-opensans', "$protocol://fonts.googleapis.com/css?family=Open+Sans:300,400,800" );
	
	
	wp_enqueue_script('jflickrfeed');
	wp_enqueue_script('retina');
	wp_enqueue_script('allscript');
	
	$primaryColor = ot_get_option('primary_color');
	$secondaryColor = ot_get_option('secondary_color');
	
	//Check does revolution slider plugin was installed
	$mainSlider = ( shortcode_exists( 'rev_slider' ) ) ? true : false;
	$bookingCalendar = ( defined('NATION_BOOKING_ACTIVE') ) ? true : false;
	
	//Send template path to script
	$templatePath = get_template_directory_uri(); 
	
	//Get Theme Options for Google Maps and send it to our initialization script
	if ( isset($gmapsKey) && ! empty($gmapsKey) ) { $showMap = true; } else { $showMap = false; }
	
	$mapCenter = ot_get_option('map_center');
	if ( !isset($mapCenter) || empty($mapCenter) ) { $mapCenter = ''; }
	
	$mapZoom = ot_get_option('home_map_zoom');
	if ( !isset($mapZoom) || empty($mapZoom) ) { $mapZoom = 5; } 
	
	$mapType = ot_get_option('home_map_type');
	if ( !isset($mapType) || empty($mapType) ) { $mapType = "HYBRID"; } 
	
	$mapMarker = ot_get_option('home_page_marker');
	if ( !isset($mapMarker) || empty($mapMarker) ) { $mapMarker = ""; } 
	
	$mapTitle = ot_get_option('where_to_find_us_title');
	if ( !isset($mapTitle) || empty($mapTitle) ) { $mapTitle = ""; } 
	
	$findusAddress = ot_get_option('where_to_find_us_address');
	if ( !isset($findusAddress) || empty($findusAddress) ) { $findusAddress = ""; } 
	
	$dateformat = $wpdb->get_var( "SELECT date_format FROM ".$wpdb->prefix."nation_booking_settings");
	
	//Pass some variables to allscript files 
	$passVar = array( 'menuColor' => $secondaryColor, 'primaryColor' => $primaryColor, 'mainSlider' => $mainSlider, 'bookingCalendar' => $bookingCalendar, 
		'templatePath' => $templatePath, 'showMap' => $showMap, 'mapCenter' => $mapCenter, 'mapZoom' => $mapZoom, 'mapType' => $mapType, 'mapMarker' => $mapMarker,
		'mapTitle' => $mapTitle, 'findusAddress' => $findusAddress, 'dateformat' => $dateformat, 
		'monthShortNames' =>  __("Jan,Feb,Mar,Apr,May,Jun,Jul,Aug,Sep,Oct,Nov,Dec","nation"),
		'monthLongNames' => __("January,February,March,April,May,June,July,August,September,October,November,December","nation"),
		'dayShortNames' => __("Sun,Mon,Tue,Wed,Thu,Fri,Sat","nation"),
		'dayLongNames' => __("Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday","nation"),
		'dayMicroNames' => __("Su,Mo,Tu,We,Th,Fr,Sa","nation"),
		'emailMismatch' => __("Emails that you entered mismatch, please try to enter it again","nation")
	);
    wp_localize_script( 'allscript', 'nationOption', $passVar );
} 

// Change text of search plugin
add_filter('get_search_form', 'my_search_form'); 
function my_search_form($text) {
     $text = str_replace('value=""', 'value="Search ..."', $text);
     return $text;
}

// Initializing custom post type
add_action( 'init', 'create_my_post_types' );


function create_my_post_types() {
	register_post_type( 'gallery', 
		array(
			'labels' => array(
				'name' => 'Gallery',
				'singular_name' => 'Gallery'
			),
			'public' => true,
			'supports' => array( 'title', 'editor', 'thumbnail' ),
			'menu_icon' => get_stylesheet_directory_uri() . '/images/media-button.png'
		)
	);
	register_post_type( 'rooms', 
		array(
			'labels' => array(
				'name' => 'Rooms' ,
				'singular_name' => 'Room',
				'add_new' => 'Add New',
				'add_new_item' => 'Add New Room',
				'edit' => 'Edit Room',
				'edit_item' => 'Edit Rooms',
			),
			'public' => true,
			'supports' => array( 'excerpt', 'title', 'editor', 'thumbnail', 'comments'),
			'menu_icon' => get_stylesheet_directory_uri() . '/images/media-button.png'
		)
	);
	register_post_type( 'testimonials', 
		array(
			'labels' => array(
				'name' => 'Testimonials' ,
				'singular_name' => 'Testimonial',
				'add_new' => 'Add New',
				'add_new_item' => 'Add New Testimonial',
				'edit' => 'Edit Testimonial',
				'edit_item' => 'Edit Testimonials',
			),
			'public' => true,
			'supports' => array( 'excerpt', 'title', 'editor', 'thumbnail', 'comments'),
			'menu_icon' => get_stylesheet_directory_uri() . '/images/media-button.png'
		)
	);
}

// Define additional "post thumbnails" for rooms and posts
if (class_exists('MultiPostThumbnails')) {
    new MultiPostThumbnails(array(
        'label' => '1st Room Image',
        'id' => 'slider-image-1',
        'post_type' => 'rooms'
        )
    );
    new MultiPostThumbnails(array(
        'label' => '2nd Room Image',
        'id' => 'slider-image-2',
        'post_type' => 'rooms'
        )
    );
    new MultiPostThumbnails(array(
        'label' => '3rd Room Image',
        'id' => 'slider-image-3',
        'post_type' => 'rooms'
        )
    );
	new MultiPostThumbnails(array(
        'label' => '4th Room Image',
        'id' => 'slider-image-4',
        'post_type' => 'rooms'
        )
    );
	new MultiPostThumbnails(array(
        'label' => 'Post Thumbnail',
        'id' => 'post-thumbnail',
        'post_type' => 'post'
        )
    );
};

//Set Content Width
if ( ! isset( $content_width ) ) $content_width = 1224;


//Format top menu output
class main_menu_walker extends Walker_Nav_Menu {
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		if ($depth == 1) { $output .= "\n$indent<ul class='sub_menu2'>\n"; }
		else {
			$output .= "\n$indent<ul class='sub_menu'>\n";
		}
	}
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        global $wp_query;
		
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $class_names = $value = '';
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
		
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
        $class_names = ' class="'. esc_attr( $class_names ) . '"';
		
        if($depth == 0) {$output .= $indent . '<li' . $value . $class_names .'>';}
		
		else {
			if ($depth==1) { $output .= "<li class='submenu-arrow-wrap'><div class='top-submenu-arrow'></div></li>"; };
			$output .= $indent . '<li' . $value . $class_names .'>';
		}
		
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

        $prepend = '<div class="top-navigation-content-wrap">';
        $append = '</div>';
        $description  = ! empty( $item->description ) ? '<span class="under-title">'.esc_attr( $item->description ).'</span>' : '';
       
	   if($depth != 0) { $description = $append = $prepend = "";}
            $item_output = ! empty ($args->before) ? $args->before : '';
            $item_output .= '<a'. $attributes .'>';
            $item_output .= ! empty ($args->link_before) ? $args->link_before : '';
			$item_output .= $prepend.apply_filters( 'the_title', $item->title, $item->ID );
            $item_output .= $description;
			$item_output .= ! empty ($args->link_after) ? $args->link_after : '';
			$item_output .= $append;
            $item_output .= '</a>';
            $item_output .= ! empty ($args->after) ? $args->after : '';
            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

class language_menu_walker extends Walker_Nav_Menu {
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		if ($depth == 1) { $output .= "\n$indent<ul class='sub_menu2'>\n"; }
		else {
			$output .= "\n$indent<ul class='sub_menu'>\n";
		}
	}
  
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        global $wp_query;
		
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $class_names = $value = '';
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
		
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
        $class_names = ' class="'. esc_attr( $class_names ) . '"';
		
        if($depth == 0) {$output .= $indent . '<li' . $value . $class_names .'>';}
		
		else {
			if ($depth==1) { $output .= "<li class='submenu-arrow-wrap'><div class='top-submenu-arrow'></div></li>"; };
			$output .= $indent . '<li' . $value . $class_names .'>';
		}
		
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

        $description  = ! empty( $item->description ) ? '<img src="'.get_template_directory_uri()."/images/languages/".esc_attr( $item->description ).'.png" class="country-flag">' : '';
			
        $item_output = ! empty ( $args->before ) ? $args->before : '';
        $item_output .= '<a'. $attributes .'> '.$description;
        $item_output .= ! empty ($args->link_before) ? $args->link_before : '';
		$item_output .= apply_filters( 'the_title', $item->title, $item->ID );
        $item_output .= ! empty ($args->link_after) ? $args->link_after : '';
        $item_output .= '</a>';
        $item_output .= ! empty ($args->after) ? $args->after : '';
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

//Format top menu output for mobile
class mobile_walker extends Walker_Nav_Menu {
	function start_lvl( &$output, $depth = 0, $args = array() ){
      $indent = str_repeat("\t", $depth);
    }
    function end_lvl( &$output, $depth = 0, $args = array() ){
      $indent = str_repeat("\t", $depth);
    }
	
	function start_el( &$output, $item, $depth=0, $args = array(), $id = 0 ) {
		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
 
		$class_names = $value = '';
 
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
 
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';
 
		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
 
		$link =  ' href="'   . esc_attr( $item->url        ) .'"';
 
		//check if the menu is a submenu
		switch ($depth){
		  case 0:
			   $dp = "";
			   break;
		  case 1:
			   $dp = "-";
			   break;
		  case 2:
			   $dp = "--";
			   break;
		  case 3:
			   $dp = "---";
			   break;
		  default:
			   $dp = "";
		}
		$output .= $indent . '<li'. $value . $class_names . '><a'. $link .'>'.$dp;
		$item_output = ! empty( $args->before ) ? $args->before : '';
		$item_output .= ! empty ($args->link_before ) ? $args->link_before : '';
		$item_output .= apply_filters( 'the_title', $item->title, $item->ID ) . 
		$item_output .= ! empty( $args->link_after ) ? $args->link_after : '';
		$item_output .= ! empty ($args->after) ? $args->after : '';
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
 
	function end_el( &$output, $comment, $depth = 0, $args = array() ) {
		$output .= "</a></li>\n";
	}
}

//Format top menu output for mobile
class footer_walker extends Walker_Nav_Menu {
	
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		global $wp_query;
		$link =  ' href="'   . esc_attr( $item->url ) .'"';
		$output .= '<a'. $link .'>';
		$item_output = ! empty( $args->before ) ? $args->before : '';
		$item_output .= ! empty( $args->link_before ) ? $args->link_before : '';
		$item_output .= apply_filters( 'the_title', $item->title, $item->ID );
		$item_output .= ! empty( $args->link_after ) ? $args->link_after : '';
		$item_output .= ! empty( $args->after ) ? $args->after : '';
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
	
	function end_el( &$output, $comment, $depth = 0, $args = array() ) {
		$output .= "</a>";
	}
}

// Add metaboxes
add_action( 'add_meta_boxes', 'nation_meta_box' );  
add_action( 'save_post', 'metabox1_save' ); 
if ( defined('NATION_BOOKING_ACTIVE') ) { 
	add_action( 'save_post', 'metabox2_save' ); 
}
add_action( 'save_post', 'metabox3_save' ); 
add_action( 'save_post', 'metabox4_save' ); 
add_action( 'save_post', 'metabox5_save' ); 

function nation_meta_box() {  
	if ( defined('NATION_BOOKING_ACTIVE') ) {
		add_meta_box( 'metabox2', 'Select Calendar for this Room', 'metabox2_rendering', 'rooms', 'normal', 'core' ); 
	}
	add_meta_box( 'metabox1', 'Description', 'metabox1_rendering', 'rooms', 'normal', 'core' );  
	add_meta_box( 'metabox5', 'Single Blog Template', 'metabox5_rendering', 'post', 'normal', 'core' );	
	add_meta_box( 'metabox3', 'Room Title', 'metabox3_rendering', 'rooms', 'normal', 'core' ); 
	add_meta_box( 'metabox3', 'Page Title', 'metabox3_rendering', 'page', 'normal', 'core' ); 
	add_meta_box( 'metabox3', 'Page Title', 'metabox3_rendering', 'post', 'normal', 'core' ); 
	add_meta_box( 'metabox4', 'Author Info', 'metabox4_rendering', 'testimonials', 'normal', 'core' ); 
	
}  

function metabox1_rendering($page) {
	$values = get_post_custom( $page->ID ); 
	$max_person = isset( $values['max_person'] ) ? esc_attr( $values['max_person'][0] ) : '';  
	$room_bed = isset( $values['room_bed'] ) ? esc_attr( $values['room_bed'][0] ) : ''; 
	$room_size = isset( $values['room_size'] ) ? esc_attr( $values['room_size'][0] ) : ''; 
	$features = isset( $values['room_features'] ) ? esc_attr( $values['room_features'][0] ) : ''; 
	$policies = isset( $values['room_policies'] ) ? esc_attr( $values['room_policies'][0] ) : ''; 
	
    wp_nonce_field( 'metabox1_nonce', 'metabox1_nonce' ); 
?>
	
	<div style="display:inline-block;margin:10px;margin-left:20px;">
		<label for="person_per_room"><?php _e('Max Person per Room','nation'); ?></label><br />
		<p><input name="max_person" id="person_per_room" value="<?php echo $max_person ?>"></p>
	</div>
	
	<div style="display:inline-block;margin:10px;margin-left:20px;">
		<label for="room_bed"><?php _e('Room bed(s)','nation'); ?></label><br />
		<p><input name="room_bed" id="room_bed" value="<?php echo $room_bed ?>"></p>
	</div>
	
	<div style="display:inline-block;margin:10px;margin-left:20px;">
		<label for="room_size"><?php _e('Room size','nation'); ?></label><br />
		<p><input name="room_size" id="room_size" value="<?php echo $room_size ?>"></p>
	</div>

	<p><label for="room_features" style="margin-left:10px"><?php _e("Features (leave this field blank if you don't want to show features tab for this room):",'nation'); ?></label><br />
    <p><textarea style="margin-left:10px" rows="5" cols="90" name="room_features" id="room_features"><?php echo $features ?></textarea></p></p>

	<p><label for="room_policies" style="margin-left:10px"><?php _e("Policies (leave this field blank if you don't want to show policies tab for this room):",'nation'); ?></label><br />
    <p><textarea style="margin-left:10px" rows="5" cols="90" name="room_policies" id="room_policies"><?php echo $policies ?></textarea></p></p>
	
<?php }

function metabox1_save($page) {
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;  
    if( !isset( $_POST['metabox1_nonce'] ) || !wp_verify_nonce( $_POST['metabox1_nonce'], 'metabox1_nonce' ) ) return; 
    if( !current_user_can( 'edit_posts' ) ) return;  
        
	if( isset( $_POST['max_person'] ) ) 
		update_post_meta( $page, 'max_person', esc_attr( $_POST['max_person']) ); 
	
	if( isset( $_POST['room_bed'] ) ) 
		update_post_meta( $page, 'room_bed', esc_attr( $_POST['room_bed']) ); 
	
	if( isset( $_POST['room_size'] ) ) 
		update_post_meta( $page, 'room_size', esc_attr( $_POST['room_size']) ); 
		
	if( isset( $_POST['room_policies'] ) ) 
		update_post_meta( $page, 'room_policies', esc_attr( $_POST['room_policies']) ); 
		
	if( isset( $_POST['room_features'] ) ) 
		update_post_meta( $page, 'room_features', esc_attr( $_POST['room_features']) ); 
}

if ( defined('NATION_BOOKING_ACTIVE') ) {

	function metabox2_rendering($page) {
		$values = get_post_custom( $page->ID );
		$calendarG = isset( $values['calendar'] ) ? esc_attr( $values['calendar'][0] ) : '';  
		wp_nonce_field( 'metabox2_nonce', 'metabox2_nonce' ); 
	?>
	
	<div style="display:inline-block;margin:10px;margin-left:10px;">
		<div><?php _e('<b>Note: </b> you have to select different calendar for every room.','nation'); ?></div><br />
		<?php 
			global $wpdb;
			$calendars = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "nation_booking_calendars");
			echo "<select name='calendar' style='width:250px !important'>";
			foreach($calendars as $calendar){
			?>
				<option <?php if ( $calendarG == $calendar->id ) echo 'selected="selected"'; ?> value="<?php echo $calendar->id ?>"> <?php echo $calendar->cal_name ?> </option>
			<?php
			}
			echo "</select>";
		?>
		<br><br><div><?php _e('(you can create new calendar by clicking on "Nation Booking System" link in the left column)','nation'); ?></div>
	</div>
	
	<?php }

	function metabox2_save($page) {
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;  
		if( !isset( $_POST['metabox2_nonce'] ) || !wp_verify_nonce( $_POST['metabox2_nonce'], 'metabox2_nonce' ) ) return; 
		if( !current_user_can( 'edit_posts' ) ) return;  
        
		if( isset( $_POST['calendar'] ) ) 
			update_post_meta( $page, 'calendar', esc_attr( $_POST['calendar']) );          
	}

}

function metabox3_rendering($page) {
	$values = get_post_custom( $page->ID );
	$pageDescription = isset( $values['page_description'] ) ? esc_attr( $values['page_description'][0] ) : '';  
	$pageTitle = isset( $values['page_title'] ) ? esc_attr( $values['page_title'][0] ) : "Yes"; 
	$pageClass = isset( $values['page_class'] ) ? esc_attr( $values['page_class'][0] ) : '';
	$breadcrumb = isset( $values['breadcrumb'] ) ? esc_attr( $values['breadcrumb'][0] ) : 'No'; 
	$pageIcon = isset( $values['page_icon'] ) ? esc_attr( $values['page_icon'][0] ) : '';
	$pageTitleAlign = isset( $values['page_align'] ) ? esc_attr( $values['page_align'][0] ) : 'Left';
    wp_nonce_field( 'metabox3_nonce', 'metabox3_nonce' ); 
?>
	<p><div><?php _e('Does page title should shows?','nation'); ?></div>
	<p><input type="radio" id="title_yes" name="page_title" value="Yes" <?php checked( $pageTitle, 'Yes' ); ?> />
	<label for="title_yes"><?php _e('Yes','nation'); ?></label>&nbsp;&nbsp;&nbsp;
	<input type="radio" id="title_no" name="page_title" value="No" <?php checked( $pageTitle, 'No' ); ?> />
	<label for="title_no"><?php _e('No','nation'); ?></label></p></p>
	
	<?php if (get_post_type() == 'page') $cPage='page'; if (get_post_type() == 'rooms') $cPage='room'; if (get_post_type() == 'post') $cPage='post'; ?>
	<p><label for="page_description"><?php printf(__("Description field (leave this field blank if you don't want to show description for that %s):",'nation'),$cPage); ?></label><br />
    <p><textarea rows="5" cols="90" name="page_description" id="page_description"><?php echo $pageDescription ?></textarea></p></p>
	
	<p><div>Does breadcrumb should shows?</div>
	<p><input type="radio" id="breadcrumb_yes" name="breadcrumb" value="Yes" <?php checked( $breadcrumb, 'Yes' ); ?> />
	<label for="breadcrumb_yes"><?php _e('Yes','nation'); ?></label>&nbsp;&nbsp;&nbsp;
	<input type="radio" id="breadcrumb_no" name="breadcrumb" value="No" <?php checked( $breadcrumb, 'No' ); ?> />
	<label for="breadcrumb_no"><?php _e('No','nation'); ?></label></p></p>
	
	<p><div>Choose page title alignment:</div>
	<p><input type="radio" id="title-align-left" name="page_align" value="Left" <?php checked( $pageTitleAlign, 'Left' ); ?> />
	<label for="title-align-left"><?php _e('Left','nation'); ?></label>&nbsp;&nbsp;&nbsp;
	<input type="radio" id="title-align-center" name="page_align" value="Center" <?php checked( $pageTitleAlign, 'Center' ); ?> />
	<label for="title-align-center"><?php _e('Center','nation'); ?></label></p></p>
	
	<p><div><?php _e('Add icon to page title:','nation') ?></div></p>
	<input name="page_icon" id="page_icon" value="<?php echo $pageIcon; ?>">
	
	<p><div><?php _e('Add class to page wrap:','nation') ?></div></p>
	<input name="page_class" id="page_class" value="<?php echo $pageClass; ?>">
<?php }

function metabox3_save($page) {
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;  
    if( !isset( $_POST['metabox3_nonce'] ) || !wp_verify_nonce( $_POST['metabox3_nonce'], 'metabox3_nonce' ) ) return; 
    if( !current_user_can( 'edit_posts' ) ) return;  
        
    if( isset( $_POST['page_description'] ) ) 
        update_post_meta( $page, 'page_description', esc_attr( $_POST['page_description']) );    
    
	if( isset( $_POST['page_title'] ) ) 
        update_post_meta( $page, 'page_title', esc_attr( $_POST['page_title']) ); 	
		
	if( isset( $_POST['page_class'] ) ) 
        update_post_meta( $page, 'page_class', esc_attr( $_POST['page_class']) ); 

	if( isset( $_POST['breadcrumb'] ) ) 
		update_post_meta( $page, 'breadcrumb', esc_attr( $_POST['breadcrumb']) );  
		
	if( isset( $_POST['page_icon'] ) ) 
		update_post_meta( $page, 'page_icon', esc_attr( $_POST['page_icon']) ); 
		
	if( isset( $_POST['page_align'] ) ) 
		update_post_meta( $page, 'page_align', esc_attr( $_POST['page_align']) ); 
}

function metabox4_rendering($page) {
	$values = get_post_custom( $page->ID );
	$authorName = isset( $values['author_name'] ) ? esc_attr( $values['author_name'][0] ) : ''; 
	$authorOccupation = isset( $values['author_occupation'] ) ? esc_attr( $values['author_occupation'][0] ) : ''; 	
    wp_nonce_field( 'metabox4_nonce', 'metabox4_nonce' ); 
	
?>
	
	<p><div><?php _e('Author Name:','nation') ?></div></p>
	<input name="author_name" id="author_name_field" value="<?php echo $authorName; ?>">
	
	<p><div><?php _e('Author Occupation:','nation') ?></div></p>
	<input name="author_occupation" id="author_occupation_field" value="<?php echo $authorOccupation; ?>">
	
<?php }

function metabox4_save($page) {
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;  
    if( !isset( $_POST['metabox4_nonce'] ) || !wp_verify_nonce( $_POST['metabox4_nonce'], 'metabox4_nonce' ) ) return; 
    if( !current_user_can( 'edit_posts' ) ) return;  
        
    if( isset( $_POST['author_name'] ) ) 
        update_post_meta( $page, 'author_name', esc_attr( $_POST['author_name']) );    
	
	if( isset( $_POST['author_occupation'] ) ) 
        update_post_meta( $page, 'author_occupation', esc_attr( $_POST['author_occupation']) );  
          
}

function metabox5_rendering($page) {
	$values = get_post_custom( $page->ID );
	$template = isset( $values['single_template'] ) ? esc_attr( $values['single_template'][0] ) : '';  
    wp_nonce_field( 'metabox5_nonce', 'metabox5_nonce' ); 
	
?>
	
	<div style="display:inline-block;margin:10px;margin-left:10px;">
		<div><?php _e('Select page template for blog post.','nation'); ?></div><br />
		<select name='single_template' style='width:250px !important'>
			<option value="blog-right"> <?php _e('Blog standard','nation') ?> </option>
			<option value="blog-fullwidth"> <?php _e('Blog fullwidth','nation') ?></option>
			<option value="blog-left"> <?php _e('Blog left sidebar','nation') ?> </option>
		</select>	
	</div>
	
<?php }

function metabox5_save($page) {
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;  
    if( !isset( $_POST['metabox5_nonce'] ) || !wp_verify_nonce( $_POST['metabox5_nonce'], 'metabox5_nonce' ) ) return; 
    if( !current_user_can( 'edit_posts' ) ) return;  
        
    if( isset( $_POST['single_template'] ) ) 
        update_post_meta( $page, 'single_template', esc_attr( $_POST['single_template']) );    
          
}


//Prompt to Install plugins after theme activation
require_once dirname( __FILE__ ) . '/plugin-activation.php';
add_action( 'tgmpa_register', 'my_theme_register_required_plugins' );


function my_theme_register_required_plugins() {

	$plugins = array(
		array(
			'name'     				=> 'Slider Revolution',
			'slug'     				=> 'revslider',
			'source'   				=> get_stylesheet_directory() . '/plugins/revslider.zip',
			'required' 				=> true,
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
		),
		array(
			'name'     				=> 'Nation Booking System',
			'slug'     				=> 'nation-booking',
			'source'   				=> get_stylesheet_directory() . '/plugins/nation-booking.zip',
			'required' 				=> true,
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
		),
		
		array(
            'name'      => 'Contact Form 7',
            'slug'      => 'contact-form-7',
            'required'  => true,
        ),
	);

	// Change this to your theme text domain, used for internationalising strings
	$theme_text_domain = 'nation';

	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = array(
		'domain'       		=> $theme_text_domain,         	// Text domain - likely want to be the same as your theme.
		'default_path' 		=> '',                         	// Default absolute path to pre-packaged plugins
		'parent_menu_slug' 	=> 'themes.php', 				// Default parent menu slug
		'parent_url_slug' 	=> 'themes.php', 				// Default parent URL slug
		'menu'         		=> 'install-required-plugins', 	// Menu slug
		'has_notices'      	=> true,                       	// Show admin notices or not
		'is_automatic'    	=> false,					   	// Automatically activate plugins after installation or not
		'message' 			=> '',							// Message to output right before the plugins table
		'strings'      		=> array(
			'page_title'                       			=> __( 'Install Required Plugins', 'nation' ),
			'menu_title'                       			=> __( 'Install Plugins', 'nation' ),
			'installing'                       			=> __( 'Installing Plugin: %s', 'nation' ), // %1$s = plugin name
			'oops'                             			=> __( 'Something went wrong with the plugin API.', 'nation' ),
			'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_install'  					=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
			'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_activate' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
			'notice_ask_to_update' 						=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_update' 						=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
			'install_link' 					  			=> _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
			'activate_link' 				  			=> _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
			'return'                           			=> __( 'Return to Required Plugins Installer', 'nation' ),
			'plugin_activated'                 			=> __( 'Plugin activated successfully.', 'nation' ),
			'complete' 									=> __( 'All plugins installed and activated successfully. %s', 'nation' ), // %1$s = dashboard link
			'nag_type'									=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
		)
	);

	tgmpa( $plugins, $config );

}

function custom_excerpt_length( $length ) {
	return 200;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

// Custom excerpt
function nation_excerpt($num=0) {
	global $post;
    $limit = $num+1;
	if ($num!=0) {
		$texcerpt=get_the_excerpt();
		$excerpt = explode(' ', $texcerpt, $limit);
		array_pop($excerpt);
		$excerpt = implode(" ",$excerpt);
		if (strlen($texcerpt)>strlen($excerpt)+1) $excerpt .= "...";
	} else {
		$excerpt = get_the_excerpt();
	}
	
    echo $excerpt;
}


/* Widget section */

// Create Blog Navigation Widget
class blog_navigation_widget extends WP_Widget {
	
	function __construct() {
		parent::__construct(
	 		'blog_navigation',
			'Nation Blog Navigation',
			array( 'description' => 'Navigation link for blog page' )
		);
	}
	
	function widget($args, $instance) {
		extract( $args );	
		$title = $instance['title'];
	
		echo $before_widget;
		
		if ($title) {
			echo $before_title . $title . $after_title;
		}
 
		echo "<ul id=\"blog-categories\">";
		
		$category1 = get_category_by_slug('slider');	
		wp_list_categories( array( 'orderby' => 'name', 'show_count' => '1', 'title_li' => '', 'exclude' => $category1['cat_ID'] ) ); 

		echo "</ul>";
		echo $after_widget;
    }

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['link_count'] = strip_tags($new_instance['link_count']);
		return $instance;
    }
	
	function form($instance) { 
		if (isset($instance['title'])) {
			$title = esc_attr($instance['title']);
		}
		else {
			$title = '';
		}
		if (isset($instance['link_count'])) {
			$link_count = esc_attr($instance['link_count']);
		}
		else {
			$link_count = '';
		}
    ?>
    <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo 'Title:'; ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </p>
    <?php
	}
}

// Create Popular Post Widget
class blog_popular_posts_widget extends WP_Widget {
	
	function __construct() {
		parent::__construct(
	 		'blog_popular',
			'Nation Popular Post',
			array( 'description' => 'Show popular blog post (ranged by comments count)' )
		);
	}
	
	function widget($args, $instance) {
		extract( $args );	
		$title = $instance['title'];
		$postsCount = $instance['posts_count'];
		
		if (!$postsCount) {
			$postsCount=3;
		}
		echo $before_widget;
		
		if ($title) {
			echo $before_title . $title . $after_title;
		}
 
		echo "<div id=\"popular-post-wrap\">";
		
		
		$the_query = new WP_Query( array('post_type' => 'post', 'showposts' => $postsCount, 'orderby' => 'comment_count'  ) );
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				
				echo "<div class='popular-post-wrap'>";
				
				if (MultiPostThumbnails::has_post_thumbnail('post', 'post-thumbnail')) {
					MultiPostThumbnails::the_post_thumbnail('post', 'post-thumbnail'); 
				}  else {
					if ( has_post_thumbnail() ) {
						the_post_thumbnail('blog-thumbnail');
					} 
				}
						
				echo "	<div class='popular-post-content-wrap'>";
				echo "		<div class='popular-post-header'><a href='".get_post_permalink()."'>".get_the_title()."</a></div>";
				echo "		<div class='popular-post-meta'>".get_the_date("M d, Y")." ".__('by','nation')." ".get_the_author()."</div>";
				echo "	</div>";
				echo "	<div style='clear:both'></div>";
				
				echo "</div>";

			}
		} else {	
			_e("There no posts that match the specified criteria!",'nation');
		}
		/* Restore original Post Data */
		wp_reset_postdata();
		

		echo "</div>";
		echo $after_widget;
    }

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['posts_count'] = strip_tags($new_instance['posts_count']);
		return $instance;
    }
	
	function form($instance) { 
		if (isset($instance['title'])) {
			$title = esc_attr($instance['title']);
		}
		else {
			$title = '';
		}
		if (isset($instance['posts_count'])) {
			$postsCount = esc_attr($instance['posts_count']);
		}
		else {
			$postsCount = '';
		}
    ?>
    <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','nation'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </p>
	<p>
        <label for="<?php echo $this->get_field_id('posts_count'); ?>"><?php _e('Popular Posts Count:','nation'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('posts_count'); ?>" name="<?php echo $this->get_field_name('posts_count'); ?>" type="text" value="<?php echo $postsCount; ?>" />
    </p>
    <?php
	}

}


// Create Latest from Category widget
class latest_from_category_widget extends WP_Widget {
	
	function __construct() {
		parent::__construct(
	 		'latest_from_category',
			'Nation Latest from Category',
			array( 'description' => 'Show latest post from specified category' )
		);
	}
	
	function widget($args, $instance) {
		extract( $args );	
		$title = $instance['title'];
		$category = $instance['category'];
		$postsCount = $instance['posts_count'];
		
		if (!$postsCount) {
			$postsCount=3;
		}
		echo $before_widget;
		
		if ($title) {
			echo $before_title . $title . $after_title;
		}
 
		echo "<div id=\"category-latest\">";
		
		
		$the_query = new WP_Query( array('post_type' => 'post', 'showposts' => $postsCount, 'category_name' => $category  ) );
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				
				echo "<div class='sidebar-events-wrap'>";
				
				if (MultiPostThumbnails::has_post_thumbnail('post', 'post-thumbnail')) {
					MultiPostThumbnails::the_post_thumbnail( 'post', 'post-thumbnail', NULL, NULL, array('class'=>'popular-post-image') ); 
				}  
					
				echo "		<div class='category-latest-header'><a href='".get_post_permalink()."'>".get_the_title()."</a></div>";
				echo "		<div class='category-latest-meta'>".get_the_date("M d, Y")." ".__('by','nation')." ".get_the_author()."</div>";
				echo "	<div style='clear:both'></div>";
				echo "</div>";

			}
		} else {	
			_e("There no posts that matches the specified criteria!",'nation');
		}
		/* Restore original Post Data */
		wp_reset_postdata();
		
		echo "</div>";
		echo $after_widget;
    }

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['category'] = strip_tags($new_instance['category']);
		$instance['posts_count'] = strip_tags($new_instance['posts_count']);
		return $instance;
    }
	
	function form($instance) { 
		if (isset($instance['title'])) {
			$title = esc_attr($instance['title']);
		}
		else {
			$title = '';
		}
		if (isset($instance['posts_count'])) {
			$postsCount = esc_attr($instance['posts_count']);
		}
		else {
			$postsCount = '';
		}
		if (isset($instance['category'])) {
			$category = esc_attr($instance['category']);
		}
		else {
			$category = '';
		}
    ?>
    <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','nation'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </p>
	<p>
        <label for="<?php echo $this->get_field_id('posts_count'); ?>"><?php _e('Popular Posts Count:','nation'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('posts_count'); ?>" name="<?php echo $this->get_field_name('posts_count'); ?>" type="text" value="<?php echo $postsCount; ?>" />
    </p>
	<p>
        <label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Select Category:','nation'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>" type="text" value="<?php echo $category; ?>" />
    </p>
    <?php
	}
}


// Create Archive Widget
class archives_widget extends WP_Widget {
	function __construct() {
		parent::__construct(
	 		'archives',
			'Nation Archives',
			array( 'description' => 'Display archives link' )
		);
	}
	
	function widget($args, $instance) {
		extract( $args );
		$title = $instance['title'];
		$link_count = isset( $instance['link_count'] ) ? $instance['link_count'] : 5;
		echo $before_widget;
		if ($title) {
			echo $before_title . $title . $after_title;
		} else {
			echo $before_title . 'Archive' . $after_title;
		}
		echo "<ul id=\"archives\">\n";	
		wp_get_archives(array( 'echo' => 1, 'limit' => $link_count ));
		echo "</ul>\n";
		echo $after_widget;
    }

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['link_count'] = strip_tags($new_instance['link_count']);
		return $instance;
    }
	
	function form($instance) { 
		if (isset($instance['title'])) {
			$title = esc_attr($instance['title']);
		}
		else {
			$title = '';
		}
		if (isset($instance['link_count'])) {
			$link_count = esc_attr($instance['link_count']);
		}
		else {
			$link_count = '';
		}
    ?>
	<p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo 'Title:'; ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('link_count'); ?>"><?php echo 'Number of link to display:'; ?></label>
        <input style="width:70px" id="<?php echo $this->get_field_id('link_count'); ?>" name="<?php echo $this->get_field_name('link_count'); ?>" type="text" value="<?php echo $link_count; ?>" />
    </p>
    <?php
	}

}

// Create Contact Widget
class contact_widget extends WP_Widget {
	function __construct() {
		parent::__construct(
	 		'contact',
			'Nation Contact',
			array( 'description' => 'Display contact information' )
		);
	}
	function widget($args, $instance) {
		extract( $args );
		
		$title = $instance['title'];
		$text = $instance['text'];
		$telephone = $instance['telephone'];
		$email = $instance['email'];
		$skype = $instance['skype'];
	
		echo $before_widget;
		
		if ($title) {
			echo $before_title . $title . $after_title;
		}
		echo "<div id='contact-us-wrap'>";
		if (isset($text)) echo "<div id=\"contact-us-wrap-intro\">".$text."</div>";
		
		if ( ($telephone != '') || ($email != '') || ($skype != '') ) {
			echo "<ul>";
				if ($telephone != '') {
					echo "<li id=\"by-phone\"><span class='icon-mobile-phone'></span><div class='contact-info-content'><div class='contact-info-method-name'>".__('telephone:','nation')."</div> <br>".$telephone."</div><div style='clear:both'></div></li>";
				}
				if ($email != '') {
					echo "<li id=\"contact-email\"><span class='icon-envelope-alt'></span><div class='contact-info-content'><div class='contact-info-method-name'>".__('email:','nation')."</div> <br>".$email."</div><div style='clear:both'></div></li>";
				}
				if ($skype != '') {
					echo "<li id=\"contact-skype\"><span class='icon-skype'></span><div class='contact-info-content'><div class='contact-info-method-name'>".__('skype:','nation')."</div> <br>".$skype."</div><div style='clear:both'></div></li>";
				}
			echo "</ul>";
		}
		echo "</div>";
		echo $after_widget;
    }

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['text'] = strip_tags($new_instance['text']);
		$instance['telephone'] = strip_tags($new_instance['telephone']);
		$instance['email'] = strip_tags($new_instance['email']);
		$instance['skype'] = strip_tags($new_instance['skype']);
		return $instance;
    }
	
	function form($instance) {
		if (isset($instance['title'])) {
			$title = esc_attr($instance['title']);
		} else {
			$title = '';
		}
		if (isset($instance['text'])) {
			$text = esc_attr($instance['text']);
		} else {
			$text = '';
		}
		if (isset($instance['telephone'])) {
			$telephone = esc_attr($instance['telephone']);
		} else {
			$telephone = '';
		}
		if (isset($instance['email'])) {
			$email = esc_attr($instance['email']);
		} else {
			$email = '';
		}
		if (isset($instance['skype'])) {
			$skype = esc_attr($instance['skype']);
		} else {
			$skype ='';
		}
		
    ?>
	<p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo 'Title:'; ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </p>
	<p>
        <label for="<?php echo $this->get_field_id('text'); ?>"><?php echo 'Text:'; ?></label>
        <textarea class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('telephone'); ?>"><?php echo 'Telephone:'; ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('telephone'); ?>" name="<?php echo $this->get_field_name('telephone'); ?>" type="text" value="<?php echo $telephone; ?>" />
    </p>
	<p>
        <label for="<?php echo $this->get_field_id('email'); ?>"><?php echo 'Email:'; ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('email'); ?>" name="<?php echo $this->get_field_name('email'); ?>" type="text" value="<?php echo $email; ?>" />
    </p>
	<p>
        <label for="<?php echo $this->get_field_id('skype'); ?>"><?php echo 'Skype:'; ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('skype'); ?>" name="<?php echo $this->get_field_name('skype'); ?>" type="text" value="<?php echo $skype; ?>" />
    </p>
    <?php
	}

}

// Create Last Posts Widget
class last_posts_widget extends WP_Widget {
	
	function __construct() {
		parent::__construct(
	 		'last_posts',
			'Nation Last posts',
			array( 'description' => 'Display latest post from blog' )
		);
	}
	
	function widget($args, $instance) {
		extract( $args );
		$title = $instance['title'];
		$post_count = $instance['post_count'];
		if ($post_count == '') $post_count = 5;
		
		echo $before_widget;
		
		if ($title) {
			echo $before_title . $title . $after_title;
		}
		
		echo "<ul id=\"blog-post-sidebar\">";
		
		query_posts( array( 'post_type' => 'post', 'posts_per_page' => $post_count ) );
		if ( have_posts() ): while ( have_posts() ) : the_post(); 
		?>
		
		<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><br /><span>by <?php the_author() ?></span></li>
		
		<?php
		endwhile;endif;
		echo "</ul>";
		
		echo $after_widget;
    }

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['post_count'] = strip_tags($new_instance['post_count']);
		return $instance;
    }
	
	function form($instance) { 
		if (isset($instance['title'])) {
			$title = esc_attr($instance['title']);
		} else {
			$title = '';
		}
		if (isset($instance['post_count'])) {
			$post_count = esc_attr($instance['post_count']);
		} else {
			$post_count = '';
		}
    ?>
	<p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo 'Title:'; ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </p>
	<p>
        <label for="<?php echo $this->get_field_id('post_count'); ?>"><?php echo 'Number of post to display:'; ?></label>
        <input style="width:70px" id="<?php echo $this->get_field_id('post_count'); ?>" name="<?php echo $this->get_field_name('post_count'); ?>" type="text" value="<?php echo $post_count; ?>" />
    </p>
    <?php
	}
}


class recent_comments_widget extends WP_Widget {

	function __construct() {
		parent::__construct(
	 		'recent_comments',
			'Nation Recent Comments',
			array( 'description' => 'Display recent comments from blog' )
		);
	}

	function widget( $args, $instance ) {
		global $nation_comments, $comment;
		
		extract( $args );
		$title = $instance['title'];
		$comments_count = $instance['comments_count'];
		$comments_length = $instance['comments_length'];
		
		
		if ($comments_count == '') $comments_count = 3;
		if ($comments_length == '') $comments_length = 40;
		
		echo $before_widget;
		
		if ($title) {
			echo $before_title . $title . $after_title;
		}	
		$nation_comments = get_comments( array( 'number' => $comments_count, 'status' => 'approve', 'post_status' => 'publish' ) );
	
		$output = '<div id="popular-posts-wrap">';
		if ( $nation_comments ) {
			foreach ( (array) $nation_comments as $comment) {
				$avatar = str_replace( "class=\"avatar", "class=\"recent-comment-image avatar", get_avatar( $comment, "50" ) );		
				$output.= '<div class="popular-post-wrap">' . $avatar;
				$commentContent = get_comment_text($comment->comment_ID);
				if ( strlen( $commentContent ) > $comments_length ) {
					$commentContent = str_split( $commentContent, $comments_length );
					$commentContent = $commentContent[0];
					$commentContent .= "...";
				}
				
				$output .= sprintf('<div class="popular-post-content-wrap"><div class="popular-post-header">"%2$s"</div><div class="popular-post-meta">by %1$s</div></div>', get_comment_author(), '<a href="' . esc_url( get_comment_link($comment->comment_ID) ) . '">' . $commentContent . '</a>');
				$output .= '<div style="clear:both"></div></div>';
			}
 		}
		$output .= '</div>';
		echo $output;
		echo $after_widget;
	}

function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['comments_count'] = strip_tags($new_instance['comments_count']);
		$instance['comments_length'] = strip_tags($new_instance['comments_length']);
		return $instance;
    }
	
	function form($instance) { 
		if (isset($instance['title'])) {
			$title = esc_attr($instance['title']);
		} else {
			$title = '';
		}
		if (isset($instance['comments_count'])) {
			$comments_count = esc_attr($instance['comments_count']);
		} else {
			$comments_count = '';
		}
		if (isset($instance['comments_length'])) {
			$comments_length = esc_attr($instance['comments_length']);
		} else {
			$comments_length = '';
		}
    ?>
	<p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','nation'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </p>
	<p>
        <label for="<?php echo $this->get_field_id('comments_count'); ?>"><?php _e('Number of comments to display:','nation'); ?></label>
        <input style="width:70px" id="<?php echo $this->get_field_id('comments_count'); ?>" name="<?php echo $this->get_field_name('comments_count'); ?>" type="text" value="<?php echo $comments_count; ?>" />
    </p>
	<p>
        <label for="<?php echo $this->get_field_id('comments_length'); ?>"><?php _e('Enter max comment length:','nation'); ?></label>
        <input style="width:70px" id="<?php echo $this->get_field_id('comments_length'); ?>" name="<?php echo $this->get_field_name('comments_length'); ?>" type="text" value="<?php echo $comments_length; ?>" />
    </p>
    <?php
	}
}


/* Footer Section Widgets */

class footer_contact_widget extends WP_Widget {
	
	function __construct() {
		parent::__construct(
	 		'footer_contact_widget',
			'Footer Contact Widget',
			array( 'description' => 'Display contact information in footer section' )
		);
	}
	
	function widget($args, $instance) {
		extract( $args );
		$title = $instance['title'];
		$footerFormUrl = $instance['footer_form_url'];
		$footerEmail = $instance['footer_email'];
		$footerTelephone = $instance['footer_telephone'];
		$footerSkype = $instance['footer_skype'];
		$footerAddress = $instance['footer_address'];
		$footerContactText = $instance['footer_contact_text'];
		
		echo $before_widget;
		
		if ($title) {
			echo "<div class='footer-header'>" . $title . "</div>";
		}
		
		if ($footerContactText) {
			echo "<div id='footer-subscribe-text'>".$footerContactText."</div>";
		}
		
		if ($footerFormUrl) {
			echo "<div id='mc_embed_signup'>";
			echo "<form action=" . $footerFormUrl . " method='post' id='mc-embedded-subscribe-form' name='mc-embedded-subscribe-form' class='validate' target='_blank' novalidate>";
			echo "<input type='email' value='' name='EMAIL' class='email footer-subscribe-email-field' id='mce-EMAIL' placeholder='email address' required>";
			echo "<button class='footer-subscribe-button' type='submit' id='mc-embedded-subscribe'>Submit</button></form></div>";
		}
		
		if ($footerEmail || $footerTelephone || $footerSkype || $footerAddress) {
			echo "<div id='footer-contact-info-wrap'>";
			
			if ($footerEmail) { 
				
				echo "<div id='footer-email-wrap'><span id='email-title'><span class='icon-envelope-alt'></span>".__('Email:','nation')."</span> <span id='email-value'>" . $footerEmail . "</span><div style='clear:both;'></div></div>";
			}
		
			if ($footerTelephone != '') { 
				echo "<div id='footer-phone-wrap'><span id='phone-title'><span class='icon-phone'></span>".__('Telephone:','nation')."</span> <span id='phone-value'>" . $footerTelephone . "</span><div style='clear:both;'></div></div>";
			} 
		
			if ($footerSkype != '') { 
				echo "<div id='footer-skype-wrap'><span id='skype-title'><span class='icon-skype'></span>".__('Skype:','nation')."</span> <span id='skype-value'>" . $footerSkype . "</span><div style='clear:both;'></div></div>";
			} 
		
			if ($footerAddress != '') { 
				echo "<div id='footer-address-wrap'><span id='address-title'><span class='icon-compass'></span>".__('Address','nation')."</span> <span id='address-value'>".$footerAddress."</span><div style='clear:both;'></div></div>";
			}
					
		}
					
		echo "</div>";
		
		echo $after_widget;
    }

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['footer_form_url'] = strip_tags($new_instance['footer_form_url']);
		$instance['footer_email'] = strip_tags($new_instance['footer_email']);
		$instance['footer_telephone'] = strip_tags($new_instance['footer_telephone']);
		$instance['footer_skype'] = strip_tags($new_instance['footer_skype']);
		$instance['footer_address'] = $new_instance['footer_address'];
		$instance['footer_contact_text'] = strip_tags($new_instance['footer_contact_text']);
		return $instance;
    }
	
	function form($instance) { 
		if (isset($instance['title'])) {
			$title = esc_attr($instance['title']);
		} else {
			$title = '';
		}
		if (isset($instance['footer_form_url'])) {
			$footerFormUrl = esc_attr($instance['footer_form_url']);
		} else {
			$footerFormUrl = '';
		}
		if (isset($instance['footer_email'])) {
			$footerEmail = esc_attr($instance['footer_email']);
		} else {
			$footerEmail = '';
		}
		if (isset($instance['footer_telephone'])) {
			$footerTelephone = esc_attr($instance['footer_telephone']);
		} else {
			$footerTelephone = '';
		}
		if (isset($instance['footer_skype'])) {
			$footerSkype = esc_attr($instance['footer_skype']);
		} else {
			$footerSkype = '';
		}
		if (isset($instance['footer_address'])) {
			$footerAddress = esc_attr($instance['footer_address']);
		} else {
			$footerAddress = '';
		}
		if (isset($instance['footer_contact_text'])) {
			$footerContactText = esc_attr($instance['footer_contact_text']);
		} else {
			$footerContactText = '';
		}
    ?>
	<p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','nation'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </p>
	<p>
        <label for="<?php echo $this->get_field_id('footer_contact_text'); ?>"><?php _e('Contact text:','nation'); ?></label>
         <textarea class="widefat" rows="6" id="<?php echo $this->get_field_id('footer_contact_text'); ?>" name="<?php echo $this->get_field_name('footer_contact_text'); ?>" type="text"><?php echo $footerContactText; ?></textarea>
    </p>
	<p>
        <label for="<?php echo $this->get_field_id('footer_form_url'); ?>"><?php _e('Subscribe page URL:','nation'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('footer_form_url'); ?>" name="<?php echo $this->get_field_name('footer_form_url'); ?>" type="text" value="<?php echo $footerFormUrl; ?>" />
    </p>
	<p>
        <label for="<?php echo $this->get_field_id('footer_email'); ?>"><?php _e('Enter email:','nation'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('footer_email'); ?>" name="<?php echo $this->get_field_name('footer_email'); ?>" type="text" value="<?php echo $footerEmail; ?>" />
    </p>
	<p>
        <label for="<?php echo $this->get_field_id('footer_telephone'); ?>"><?php _e('Enter telephone:','nation'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('footer_telephone'); ?>" name="<?php echo $this->get_field_name('footer_telephone'); ?>" type="text" value="<?php echo $footerTelephone; ?>" />
    </p>
	<p>
        <label for="<?php echo $this->get_field_id('footer_skype'); ?>"><?php _e('Enter skype:','nation'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('footer_skype'); ?>" name="<?php echo $this->get_field_name('footer_skype'); ?>" type="text" value="<?php echo $footerSkype; ?>" />
    </p>
	<p>
        <label for="<?php echo $this->get_field_id('footer_address'); ?>"><?php _e('Enter address:','nation'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('footer_address'); ?>" name="<?php echo $this->get_field_name('footer_address'); ?>" type="text" value="<?php echo $footerAddress; ?>" />
    </p>
	
    <?php
	}

}


// Create Twitter Footer Widget
class twitter_footer_widget extends WP_Widget {
	
	function __construct() {
		parent::__construct(
	 		'twitter_footer_widget',
			'Footer Twitter Widget',
			array( 'description' => 'Display twitter feed in footer section' )
		);
	}
	
	function widget($args, $instance) {
		extract( $args );
		$title = $instance['title'];
		$twitter_footer_username = $instance['twitter_footer_username'];
		$twitter_footer_count = $instance['twitter_footer_count'];
			
		
		echo $before_widget;
	
		if ($title) {
			echo "<div class='footer-header'>" . $title . "</div>";
		}
		
		echo "<div id='twitter-feed'></div>";		
		?>
		<script type="text/javascript">
		(function($) {
			$(document).ready(function () {
				var displaylimit = <?php if ( isset( $twitter_footer_count ) && ! empty( $twitter_footer_count ) ) { echo $twitter_footer_count; } else { echo 2; } ?>;
				var showdirecttweets = true;
				var showretweets = true;
				var showtweetlinks = true;
				var showprofilepic = true;
				
				$.getJSON('<?php echo get_template_directory_uri() ?>/get-tweets.php',{"twitterusername": "<?php echo $twitter_footer_username; ?>", "displaylimit": displaylimit },
				function(feeds) {
						
						if ( typeof feeds.errors != "undefined") {
							var feedHTML = "<div style='width:256px'>Error occured while trying to display twitter feed. Please check does you enter your consumer key, consumer secret key, access token and access token secret in get-tweets.php file correctly!</div>";
						} else {
							var feedHTML = "";
						}
						var displayCounter = 1;
						for (var i=0; i<feeds.length; i++) {
							var tweetscreenname = feeds[i].user.name;
							var tweetusername = feeds[i].user.screen_name;
							var profileimage = feeds[i].user.profile_image_url_https;
							var status = feeds[i].text;
							var isaretweet = false;
							var isdirect = false;
							var tweetid = feeds[i].id_str;
 
							//If the tweet has been retweeted, get the profile pic of the tweeter
							if(typeof feeds[i].retweeted_status != 'undefined'){
								profileimage = feeds[i].retweeted_status.user.profile_image_url_https;
								tweetscreenname = feeds[i].retweeted_status.user.name;
								tweetusername = feeds[i].retweeted_status.user.screen_name;
								tweetid = feeds[i].retweeted_status.id_str
								isaretweet = true;
							};
 
							//Check to see if the tweet is a direct message
							if (feeds[i].text.substr(0,1) == "@") {
								isdirect = true;
							}
 
							if (((showretweets == true) || ((isaretweet == false) && (showretweets == false))) && ((showdirecttweets == true) || ((showdirecttweets == false) && (isdirect == false)))) {
								if ((feeds[i].text.length > 1) && (displayCounter <= displaylimit)) {
									if (showtweetlinks == true) {
										status = addlinks(status);
									}
									feedHTML += '<div class="twitterRow">';
									feedHTML += '<div class="twitter-text"><span class="icon-twitter"></span><p><span class="tweetprofilelink"><strong><a href="https://twitter.com/'+tweetusername+'" >'+tweetscreenname+'</a></strong> <a href="https://twitter.com/'+tweetusername+'" >@'+tweetusername+'</a></span><br/>'+status+'</p></div>';
									feedHTML += '</div><div class="twitter-row-divider"></div>';
									displayCounter++;
									
								}
							}
						}
				 
					$('#twitter-feed').html(feedHTML);
				
				});
 
				function addlinks(data) {
					//Add link to all http:// links within tweets
					data = data.replace(/((https?|s?ftp|ssh)\:\/\/[^"\s\<\>]*[^.,;'">\:\s\<\>\)\]\!])/g, function(url) {
						return '<a href="'+url+'" >'+url+'</a>';
					});
 
					//Add link to @usernames used within tweets
					data = data.replace(/\B@([_a-z0-9]+)/ig, function(reply) {
						return '<a href="http://twitter.com/'+reply.substring(1)+'" style="font-weight:lighter;" >'+reply.charAt(0)+reply.substring(1)+'</a>';
					});
					return data;
				}
 
			});
		})( jQuery );
		</script>
		
		<?php
				
		if ( !$twitter_footer_username ) {
			_e('<p>Please enter twitter username whose tweets you want to show.</p>','nation');
		}
				
		echo $after_widget;
		
    }

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['twitter_footer_username'] = strip_tags($new_instance['twitter_footer_username']);
		$instance['twitter_footer_count'] = strip_tags($new_instance['twitter_footer_count']);

		return $instance;
    }
	
	function form($instance) { 
		if (isset($instance['title'])) {
			$title = esc_attr($instance['title']);
		}
		else {
			$title = '';
		}
		if (isset($instance['twitter_footer_username'])) {
			$twitter_footer_username = esc_attr($instance['twitter_footer_username']);
		}	
		else {
			$twitter_footer_username = '';
		}
		if (isset($instance['twitter_footer_count'])) {
			$twitter_footer_count = esc_attr($instance['twitter_footer_count']);
		}
		else {
			$twitter_footer_count = '';
		}
	?>
	<p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo 'Title:'; ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </p>
	<p>
        <label for="<?php echo $this->get_field_id('twitter_footer_username'); ?>"><?php echo 'Twitter username :'; ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('twitter_footer_username'); ?>" name="<?php echo $this->get_field_name('twitter_footer_username'); ?>" type="text" value="<?php echo $twitter_footer_username; ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('twitter_footer_count'); ?>"><?php echo 'Number of tweet to display:'; ?></label>
        <input style="width:70px" id="<?php echo $this->get_field_id('twitter_footer_count'); ?>" name="<?php echo $this->get_field_name('twitter_footer_count'); ?>" type="text" value="<?php echo $twitter_footer_count; ?>" />
    </p>
    <?php
	}

}


// Create Flickr Widget
class footer_flickr_widget extends WP_Widget {
	
	function __construct() {
		parent::__construct(
	 		'flickr',
			'Footer Flickr Feed',
			array( 'description' => 'Display flickr feed' )
		);
	}
	
	function widget($args, $instance) {
		extract( $args );
		
		$title = $instance['title'];
		$user_id = $instance['user_id'];
		$image_count = $instance['image_count'];
		
		echo $before_widget;
		
		if ($title) {
			echo "<div class='footer-header'>" . $title . "</div>";
		}
		
		if ($image_count == '') $image_count = 4;
		
		?>

		<script type="text/javascript">
		(function($) {
			$(document).ready(function() {
				//Flickr Feed
				$('#flickr-feed').jflickrfeed({
					limit:<?php echo $image_count; ?>,
					qstrings:{ id: '<?php echo $user_id; ?>' },
					itemTemplate: 
						'<li>' +
						'<a href="{{image_b}}"><img src="{{image_s}}" alt="{{title}}" /></a>' +
						'</li>',
				});
								
			});
		})( jQuery );
		</script>
		
		<?php
		
		echo "<ul id=\"flickr-feed\"></ul>";
		echo "<div style='clear:both'></div>";

		echo $after_widget;
    }

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['image_count'] = strip_tags($new_instance['image_count']);
		$instance['user_id'] = strip_tags($new_instance['user_id']);
		return $instance;
    }
	
	function form($instance) { 
		if (isset($instance['title'])) {
			$title = esc_attr($instance['title']);
		}
		else {
			$title = '';
		}
		if (isset($instance['image_count'])) {
			$image_count = esc_attr($instance['image_count']);
		}	
		else {
			$image_count = '';
		}
		if (isset($instance['user_id'])) {
			$user_id = esc_attr($instance['user_id']);
		}
		else {
			$user_id = '';
		}
	?>
	<p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo 'Title:'; ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </p>
	<p>
        <label for="<?php echo $this->get_field_id('user_id'); ?>"><?php echo 'Flickr user name ID :'; ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('user_id'); ?>" name="<?php echo $this->get_field_name('user_id'); ?>" type="text" value="<?php echo $user_id; ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('image_count'); ?>"><?php echo 'Number of image to display:'; ?></label>
        <input style="width:70px" id="<?php echo $this->get_field_id('image_count'); ?>" name="<?php echo $this->get_field_name('image_count'); ?>" type="text" value="<?php echo $image_count; ?>" />
    </p>
    <?php
	}

}


class footer_social_widget extends WP_Widget {
	
	function __construct() {
		parent::__construct(
	 		'footer_social_widget',
			'Footer Social Widget',
			array( 'description' => 'Display social network in footer section' )
		);
	}
	
	function widget($args, $instance) {
		extract( $args );
		$title = $instance['title'];
		$facebook = $instance['facebook'];
		$twitter = $instance['twitter'];
		$googlePlus = $instance['google_plus'];
		$linkedin = $instance['linkedin'];
		$skype = $instance['skype'];
		$instagram = $instance['instagram'];
		
		echo $before_widget;	
		
		if ( isset($title) ) {
			echo "<div class='footer-header'>" . $title . "</div>";
		}
				
		if ( isset($facebook) || isset($twitter) || isset($googlePlus) || isset($linkedin) || isset($skype) || isset($instagram) ) {
			echo "<div id='footer-social-wrap'>";
			
			if ( isset($facebook) && !empty($facebook) ) { 
				echo "<a href=".$facebook." class='icon-facebook'></a>";
			}	

			if ( isset($twitter) && !empty($twitter) ) { 
				echo "<a href=".$twitter." class='icon-twitter'></a>";
			}	

			if ( isset($googlePlus) && !empty($googlePlus) ) { 
				echo "<a href=".$googlePlus." class='icon-google-plus'></a>";
			}	

			if ( isset($linkedin) && !empty($linkedin) ) { 
				echo "<a href=".$linkedin." class='icon-linkedin'></a>";
			}	

			if ( isset($skype) && !empty($skype) ) { 
				echo "<a href=".$skype." class='icon-skype'></a>";
			}	

			if ( isset($instagram) && !empty($instagram) ) { 
				echo "<a href=".$instagram." class='icon-instagram'></a>";
			}				
		}
					
		echo "</div>";
		
		echo $after_widget;
    }

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['facebook'] = strip_tags($new_instance['facebook']);
		$instance['twitter'] = strip_tags($new_instance['twitter']);
		$instance['google_plus'] = strip_tags($new_instance['google_plus']);
		$instance['linkedin'] = strip_tags($new_instance['linkedin']);
		$instance['skype'] = strip_tags($new_instance['skype']);
		$instance['instagram'] = strip_tags($new_instance['instagram']);
		
		return $instance;
    }
	
	function form($instance) { 
		if (isset($instance['title'])) {
			$title = esc_attr($instance['title']);
		} else {
			$title = '';
		}
		if (isset($instance['facebook'])) {
			$facebook = esc_attr($instance['facebook']);
		} else {
			$facebook = '';
		}	
		if (isset($instance['twitter'])) {
			$twitter = esc_attr($instance['twitter']);
		} else {
			$twitter = '';
		}
		if (isset($instance['google_plus'])) {
			$googlePlus = esc_attr($instance['google_plus']);
		} else {
			$googlePlus = '';
		}	
		if (isset($instance['linkedin'])) {
			$linkedin = esc_attr($instance['linkedin']);
		} else {
			$linkedin = '';
		}		
		if (isset($instance['skype'])) {
			$skype = esc_attr($instance['skype']);
		} else {
			$skype = '';
		}	
		if (isset($instance['instagram'])) {
			$instagram = esc_attr($instance['instagram']);
		} else {
			$instagram = '';
		}
    ?>
	<p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','nation'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </p>
	<p>
        <label for="<?php echo $this->get_field_id('facebook'); ?>"><?php _e('Facebook URL:','nation'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('facebook'); ?>" name="<?php echo $this->get_field_name('facebook'); ?>" type="text" value="<?php echo $facebook; ?>" />
    </p>
	<p>
        <label for="<?php echo $this->get_field_id('twitter'); ?>"><?php _e('Twitter URL:','nation'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('twitter'); ?>" name="<?php echo $this->get_field_name('twitter'); ?>" type="text" value="<?php echo $twitter; ?>" />
    </p>
	<p>
        <label for="<?php echo $this->get_field_id('google_plus'); ?>"><?php _e('Google+ URL:','nation'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('google_plus'); ?>" name="<?php echo $this->get_field_name('google_plus'); ?>" type="text" value="<?php echo $googlePlus; ?>" />
    </p>
	<p>
        <label for="<?php echo $this->get_field_id('linkedin'); ?>"><?php _e('LinkedIn URL:','nation'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('linkedin'); ?>" name="<?php echo $this->get_field_name('linkedin'); ?>" type="text" value="<?php echo $linkedin; ?>" />
    </p>
	<p>
        <label for="<?php echo $this->get_field_id('skype'); ?>"><?php _e('Skype URL:','nation'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('skype'); ?>" name="<?php echo $this->get_field_name('skype'); ?>" type="text" value="<?php echo $skype; ?>" />
    </p>
	<p>
        <label for="<?php echo $this->get_field_id('instagram'); ?>"><?php _e('Instagram URL:','nation'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('instagram'); ?>" name="<?php echo $this->get_field_name('instagram'); ?>" type="text" value="<?php echo $instagram; ?>" />
    </p>
	
    <?php
	}
}

class footer_text_widget extends WP_Widget {
	
	function __construct() {
		parent::__construct(
	 		'footer_text_widget',
			'Footer Text Widget',
			array( 'description' => 'Display text in footer section' )
		);
	}
	
	function widget($args, $instance) {
		extract( $args );
		
		$title = $instance['title'];
		$footer_text_block = $instance['footer_text_block'];
		
		echo $before_widget;
				
		if ($title) {
			echo "<div class='footer-header'>" . $title . "</div>";
		}
		
		if ($footer_text_block) { 
			echo "<div style='font-size:1.1em;'>" . $footer_text_block . "</div>";
		} 
		
		echo $after_widget;
		
    }

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['footer_text_block'] = $new_instance['footer_text_block'];
		return $instance;
    }
	
	function form($instance) { 
		if (isset($instance['title'])) {
			$title = esc_attr($instance['title']);
		} else {
			$title = '';
		}
		if (isset($instance['footer_text_block'])) {
			$footer_text_block = esc_attr($instance['footer_text_block']);
		} else {
			$footer_text_block = '';
		}
	?>
	<p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo 'Title:'; ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </p>
	<p>
        <label for="<?php echo $this->get_field_id('footer_text_block'); ?>"><?php echo 'Footer text :'; ?></label>
		<textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('footer_text_block'); ?>" name="<?php echo $this->get_field_name('footer_text_block'); ?>"><?php echo $footer_text_block; ?></textarea>
    </p>
   
    <?php
	}

}


// Register Nation Widgets
add_action('widgets_init', create_function('', 'return register_widget("blog_navigation_widget");'));
add_action('widgets_init', create_function('', 'return register_widget("archives_widget");'));
add_action('widgets_init', create_function('', 'return register_widget("footer_flickr_widget");'));
add_action('widgets_init', create_function('', 'return register_widget("contact_widget");'));
add_action('widgets_init', create_function('', 'return register_widget("last_posts_widget");'));
add_action('widgets_init', create_function('', 'return register_widget("footer_contact_widget");'));
add_action('widgets_init', create_function('', 'return register_widget("twitter_footer_widget");'));
add_action('widgets_init', create_function('', 'return register_widget("footer_text_widget");'));
add_action('widgets_init', create_function('', 'return register_widget("footer_social_widget");'));
add_action('widgets_init', create_function('', 'return register_widget("blog_popular_posts_widget");'));
add_action('widgets_init', create_function('', 'return register_widget("latest_from_category_widget");'));
add_action('widgets_init', create_function('', 'return register_widget("recent_comments_widget");'));

	
// Add sidebar 
if ( function_exists ('register_sidebar')) { 
	
	register_sidebar( array(
		'id'          => 'blog_sidebar',
		'name'        => 'Blog Sidebar',
		'description' => 'This sidebar displayed at blog page.',
		'before_title' => "<div class='sidebar-header'>",
		'after_title' => "</div>",
		'before_widget' => "",
		'after_widget' => ""
	) ); 
	
	register_sidebar( array(
		'id'          => 'gallery_sidebar',
		'name'        => 'Gallery Sidebar',
		'description' => 'This sidebar displayed at the gallery.',
		'before_title' => "<div class='sidebar-header'>",
		'after_title' => "</div>",
		'before_widget' => "",
		'after_widget' => ""
	) );
	
	register_sidebar( array(
		'id'          => 'contact_sidebar',
		'name'        => 'Contact Sidebar',
		'description' => 'This sidebar displayed at contact page.',
		'before_title' => "<div class='sidebar-header'>",
		'after_title' => "</div>",
		'before_widget' => "",
		'after_widget' => ""
	) );

	register_sidebar( array(
		'id'          => 'page_sidebar',
		'name'        => 'Page Sidebar',
		'description' => 'This sidebar displayed at all page except contact, blog and gallery.',
		'before_title' => "<div class='sidebar-header'>",
		'after_title' => "</div>",
		'before_widget' => "",
		'after_widget' => ""
	) );
	
	register_sidebar( array(
		'id'          => 'footer_section1',
		'name'        => 'Footer Section 1',
		'description' => 'This section displays first element in footer.',
		'before_widget' => "<div class='footer-widget'>",
		'after_widget' => "</div><div class='social-wrap-divider'></div>"
	) );
	
	register_sidebar( array(
		'id'          => 'footer_section2',
		'name'        => 'Footer Section 2',
		'description' => 'This section displays second element in footer.',
		'before_widget' => "<div class='footer-widget'>",
		'after_widget' => "</div><div class='social-wrap-divider'></div>"
	) );
	
	register_sidebar( array(
		'id'          => 'footer_section3',
		'name'        => 'Footer Section 3',
		'description' => 'This section displays third element in footer.',
		'before_widget' => "<div class='footer-widget'>",
		'after_widget' => "</div><div class='social-wrap-divider'></div>"
	) );
} 

// Add post thumbnails support, automatic feed links and menus support
if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' ); 
	add_theme_support('automatic-feed-links');
}

// Register nav menu 
register_nav_menu( 'top_menu', 'Top Navigation Menu' );
register_nav_menu( 'footer_menu', 'Footer Navigation Menu' );
register_nav_menu( 'language_menu', 'Language Menu' );

// Add image size
if ( function_exists( 'add_image_size' ) ) { 
	add_image_size( 'blog-fullwidth', 1224, 507, true);
	add_image_size( 'blog-normal', 815, 338, true);
	add_image_size( 'main-news', 260, 150, true);
	add_image_size( 'blog-thumbnail', 50, 50, true);
	add_image_size( 'room-normal', 375, 249, true);
	add_image_size( 'room-two', 500, 332, true);
	add_image_size( 'room-one', 450, 299, true);
	add_image_size( 'room-slider', 800, 375, true);
	add_image_size( 'main-from-blog', 250, 130, true);
	add_image_size( 'gallery-full', 9999, 9999, true);
}


// Nation Breadcrumbs
function nation_breadcrumbs() {
  $showOnHome = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
  $delimiter = '&gt;'; // delimiter between crumbs
  $home = 'Home'; // text for the 'Home' link
  $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
  $before = '<span class="current">'; // tag before the current crumb
  $after = '</span>'; // tag after the current crumb
 
  global $post;
  $homeLink = home_url();
 
  if (is_home() || is_front_page()) {
 
    if ($showOnHome == 1) echo '<div id="crumbs"><a href="' . $homeLink . '">' . $home . '</a></div>';
 
  } else {
 
    echo '<div id="crumbs"><a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
 
    if ( is_category() ) {
      $thisCat = get_category(get_query_var('cat'), false);
      if ($thisCat->parent != 0) echo get_category_parents($thisCat->parent, TRUE, ' ' . $delimiter . ' ');
      echo $before . 'Category "' . single_cat_title('', false) . '"' . $after;
 
    } elseif ( is_search() ) {
      echo $before . 'Search results for "' . get_search_query() . '"' . $after;
 
    } elseif ( is_day() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('d') . $after;
 
    } elseif ( is_month() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('F') . $after;
 
    } elseif ( is_year() ) {
      echo $before . get_the_time('Y') . $after;
 
    } elseif ( is_single() && !is_attachment() ) {
      if ( get_post_type() != 'post' ) {
        $post_type = get_post_type_object(get_post_type());
        $slug = $post_type->rewrite;
        echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>';
        if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
      } else {
        $cat = get_the_category(); $cat = $cat[0];
        $cats = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
        if ($showCurrent == 0) $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
        echo $cats;
        if ($showCurrent == 1) echo $before . get_the_title() . $after;
      }
 
    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
      $post_type = get_post_type_object(get_post_type());
      echo $before . $post_type->labels->singular_name . $after;
 
    } elseif ( is_attachment() ) {
      $parent = get_post($post->post_parent);
      $cat = get_the_category($parent->ID); $cat = $cat[0];
      echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
      echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>';
      if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
 
    } elseif ( is_page() && !$post->post_parent ) {
      if ($showCurrent == 1) echo $before . get_the_title() . $after;
 
    } elseif ( is_page() && $post->post_parent ) {
      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      for ($i = 0; $i < count($breadcrumbs); $i++) {
        echo $breadcrumbs[$i];
        if ($i != count($breadcrumbs)-1) echo ' ' . $delimiter . ' ';
      }
      if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
 
    } elseif ( is_tag() ) {
      echo $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;
 
    } elseif ( is_author() ) {
       global $author;
      $userdata = get_userdata($author);
      echo $before . 'Articles posted by ' . $userdata->display_name . $after;
 
    } elseif ( is_404() ) {
      echo $before . 'Error 404' . $after;
    }
 
    if ( get_query_var('nation_paged') ) {
      echo ' '.$delimiter . ' Page' . ' ' . get_query_var('nation_paged');
    }
 
    echo '</div>';
 
  }
} 

// Show description field on menus page by default 
function show_menu_description(){
	global $pagenow;
    if( $pagenow=='nav-menus.php'){
        echo '<style type="text/css">
        .field-description  {display:block !important}
        </style>';
	}
	
	if ($pagenow=='post.php') {
		echo '<style type="text/css">
        #postexcerpt {display:block;}
        </style>';
	}
}
add_action('admin_head', 'show_menu_description');


/** COMMENTS WALKER */
class post_comment extends Walker_Comment {
     
    // init classwide variables
    var $tree_type = 'comment';
    var $db_fields = array( 'parent' => 'comment_parent', 'id' => 'comment_ID' );
 

    /** START_LVL 
     * Starts the list before the CHILD elements are added. */
    function start_lvl( &$output, $depth = 0, $args = array() ) {       
        $GLOBALS['comment_depth'] = $depth + 1; ?>
		<div class="comment-divider">&nbsp;</div>
         
    <?php }
 
    /** END_LVL 
     * Ends the children list of after the elements are added. */
    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $GLOBALS['comment_depth'] = $depth + 1; ?>
         
    <?php }
     
    /** START_EL */
    function start_el( &$output, $comment, $depth = 0, $args = array(), $id = 0 ) {
        $depth++;
        $GLOBALS['comment_depth'] = $depth;
        $GLOBALS['comment'] = $comment; 
        $parent_class = ( empty( $args['has_children'] ) ? '' : 'parent' ); ?>
         
        <div class="comment-wrap reply-<?php echo $depth-1; echo ' '.$parent_class; ?>">
			<?php echo str_replace( "class=\"avatar", "class=\"comment-author-image avatar", get_avatar( $comment->comment_author_email, "100" ) ); ?>	
            <div class="comment-author">
				<?php comment_author(); echo " ".__('says:','nation'); ?> 
			</div>	
            <div class="comment-text">
				<?php comment_text(); ?> 
			</div>
			<div class="comment-meta">
				<div class="comment-data"><?php comment_date(); _e(' at ','nation'); comment_time(); ?></div>
				<div class="comment-reply-link"><?php comment_reply_link( array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth'])) ); ?></div>
			</div>
			<div style="clear:both"></div>
			
			<?php if ( $comment->comment_approved == '0' ) { ?>
				<em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.','nation'); ?></em>
				<br />
    <?php } }
 
    function end_el(&$output, $comment, $depth = 0, $args = array() ) { ?>
        </div>
    <?php }
	
     
}

// For fixing bug with pagination at archive and category page
add_action( 'pre_get_posts',  'set_posts_per_page'  );
function set_posts_per_page( $query ) {
  global $wp_the_query;
  if ( ( ! is_admin() ) && ( $query === $wp_the_query ) && ( $query->is_archive() ) ) {
    $query->set( 'posts_per_page', 1 );
  }
  return $query;
}


// For add home page link to Navigation Menu
function home_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'home_page_menu_args' );


// Add the Nation Shortcode selector to the TinyMCE plugin 
add_action( 'init', 'add_button' ); 

function add_button() { 	
	if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' )  ) {
		return;
	}
	
	if ( get_user_option('rich_editing') ) {
		add_filter('mce_external_plugins', 'add_plugin');  
		add_filter('mce_buttons', 'register_button');  
    }
} 

function register_button($buttons) { 
	global $wp_version;
	
	if ( (float)$wp_version >= 3.9 ) {
		array_push($buttons, "nation_shortcodes");
	} else {
		array_push($buttons, "nation_button");  	
	}
	
	return $buttons;  
}  

function add_plugin($plugin_array) { 
	global $wp_version;
		
	if ( (float)$wp_version >= 3.9 ) {
		$plugin_array['nation_button'] = get_template_directory_uri().'/js/nationshortcodes.js';  
	} else {
		$plugin_array['nation_button'] = get_template_directory_uri().'/js/nationshortcodes-old.js'; 
	}
	return $plugin_array;  
}   

global $wp_version;

if ( (float)$wp_version >= 3.9 ) {
	add_action('admin_head', 'nation_shortcode_icon');

	function nation_shortcode_icon() {
		echo '<style>
		.mce-i-nation-shortcode-icon {
			background: url('.get_template_directory_uri().'/images/nation-shortcode.png'.') !important;
		}
		.mce-listbox button span {
			padding-right:20px;
		}
		</style>';
	}
} 


// Create search field
function nation_search_form( $form ) {
    $form = '<form role="search" method="get" id="searchform" class="searchform" action="' . home_url( '/' ) . '" >
    <div><label class="screen-reader-text" for="s">' . __( 'Search for:', 'nation' ) . '</label>
    <input type="text" value="' . get_search_query() . '" name="s" id="s" />
    <button type="submit" id="searchsubmit"><span class="icon-search"></span></button>
    </div>
    </form>';

    return $form;
}

add_filter( 'get_search_form', 'nation_search_form' );


/* Shortcodes initialization */

//[two_columns]
function two_columns_func( $atts, $content = null ){
	return "<div class='eight columns'>".do_shortcode($content)."</div>";
}
add_shortcode( 'two_columns', 'two_columns_func' );

//[two_columns_last]
function two_columns_last_func( $atts, $content = null ){
	return "<div class='eight columns'>".do_shortcode($content)."</div><div class='clear'></div>";
}
add_shortcode( 'two_columns_last', 'two_columns_last_func' );

//[three_columns]
function three_columns_func( $atts, $content = null ){
	return "<div class='five columns columns-margin'>".do_shortcode($content)."</div>";
}
add_shortcode( 'three_columns', 'three_columns_func' );

//[three_columns_last]
function three_columns_last_func( $atts, $content = null ){
	return "<div class='five columns'>".do_shortcode($content)."</div><div class='clear'></div>";
}
add_shortcode( 'three_columns_last', 'three_columns_last_func' );

//[four_columns]
function four_columns_func( $atts, $content = null ){
	return "<div class='four columns'>".do_shortcode($content)."</div>";
}
add_shortcode( 'four_columns', 'four_columns_func' );

//[four_columns_last]
function four_columns_last_func( $atts, $content = null ){
	return "<div class='four columns'>".do_shortcode($content)."</div><div class='clear'></div>";
}
add_shortcode( 'four_columns_last', 'four_columns_last_func' );

//[header]
function header_func( $atts, $content = null ){
	extract( shortcode_atts( array(
		'type' => 'h2',
	), $atts, 'header' ) );
	
	return "<".$type.">".$content."</".$type.">";
}
add_shortcode( 'header', 'header_func' );

//[dropcap]
function dropcap_func( $atts, $content=null ){
	return "<div class='dropcap'>".$content."</div>";
}
add_shortcode( 'dropcap', 'dropcap_func' );

//[table]
function table_func( $atts ) {
    extract( shortcode_atts( array(
        'cols' => 'none',
        'data' => 'none',
    ), $atts, 'table' ) );
    $cols = explode(',',$cols);
    $data = explode(',',$data);
    $total = count($cols);
    $output = '<table><tr>';
    foreach($cols as $col){
        $output .= '<th>'.$col.'</th>';
	}
    $output .= '</tr><tr>';
    $counter = 1;
    foreach($data as $datum){
        $output .= '<td>'.$datum.'</td>';
        if($counter%$total==0){
            $output .= '</tr>';
        }
        $counter++;
	}
        $output .= '</table>';
    return $output;
}
add_shortcode( 'table', 'table_func' );

//[highlight]
function highlight_func( $atts, $content=null ){
	extract( shortcode_atts( array(
		'color' => '',
	), $atts, 'highlight' ) );
	
	if ($color == "on") {
		return "<span class='color-highlight'>".$content."</span>";
	} else {
		return "<span class='black-highlight'>".$content."</span>";
	}
}
add_shortcode( 'highlight', 'highlight_func' );

//[blockquote]
function blockquote_func( $atts, $content=null ){
	extract( shortcode_atts( array(
		'align' => ' ',
	), $atts, 'blockquote' ) );
	if ($align == ' ') {
		return "<blockquote>".$content."</blockquote>";
	}
	if ($align == 'left') {
		return "<blockquote class='left'>".$content."</blockquote>";
	}
	if ($align == 'right') {
		return "<blockquote class='right'>".$content."</blockquote>";
	}
}
add_shortcode( 'blockquote', 'blockquote_func' );


//[divider]
function divider_func( $atts ){
	extract( shortcode_atts( array(
		'type' => '1',
	), $atts, 'divider' ) );
	if ($type==1) {
		return "<div class='divider type-1'></div>";
	}
	if ($type==2) {
		return "<div class='divider type-2'></div>";
	}
}
add_shortcode( 'divider', 'divider_func' );

//[list]
function list_func( $atts ){
	extract( shortcode_atts( array(
		'divider' => '0',
		'painted' => '0',
		'icon' => '1',
		'elements' => '',
		'class' => ''
	), $atts, 'list' ) );
	$elements = explode(',',$elements);
	
	$output = "<ul class='list";
	
	if ( $painted == 1 ) {
		$output .= " painted"; 
	}
	if ( $divider == 1 ) {
		$output .= " with-divider"; 
	}
	
	if ( $class != '' ) {
		$output .= " ".$class."'>";
	} else {
		$output .= "'>";
	}
	
	if ($icon == 0) {
		$inIcon = "";
	} elseif ($icon == 1) {
		$inIcon = "<span class='icon-ok'></span>";
	} elseif ($icon == 2) {
		$inIcon = "<span class='icon-circle'></span>";
	} elseif ($icon == 3) {
		$inIcon = "<span class='icon-star'></span>";
	} elseif ($icon == 4) {
		$inIcon = "<span class='icon-check-sign'></span>";
	} elseif ($icon == 5) {
		$inIcon = "<span class='icon-ok-sign'></span>";
	} elseif ($icon == 6) {
		$inIcon = "<span class='icon-thumbs-up'></span>";
	} 
	
	foreach($elements as $element){
        $output .= '<li>'.$inIcon.$element.'</li>';
	}
	$output .= "</ul>";
	
	return $output;
}
add_shortcode( 'list', 'list_func' );

//[clear]
function clear_func( $atts ){
	return "<div class='clear'></div>";
}
add_shortcode( 'clear', 'clear_func' );

//[spacing]
function spacing_func( $atts ){
	extract( shortcode_atts( array(
		'size' => '40px',
	), $atts, 'spacing' ) );

	return "<div style='margin-top:".$size."'></div>";
}
add_shortcode( 'spacing', 'spacing_func' );


//[button]
function button_func( $atts, $content = null ){
	extract( shortcode_atts( array(
		'color' => '',
		'type' => 'standard',
		'size' => 'medium',
		'href' => '#',
		'icon' => ''
	), $atts, 'button' ) );
	return "<a href='".$href."' class='button-".$type." ".$size." " .$color."'>".$content."<span class='".$icon."'></span></a>";
}
add_shortcode( 'button', 'button_func' );

//[accordion]
function accordion_func( $atts, $content = null ){
	return "<div class='accordion-widget accordion'>".do_shortcode($content)."</div>";
}
add_shortcode( 'accordion', 'accordion_func' );

//[toggle]
function toggle_func( $atts, $content = null ){
	return "<div class='accordion-widget toggle'>".do_shortcode($content)."</div>";
}
add_shortcode( 'toggle', 'toggle_func' );

//[section]
function section_func( $atts, $content = null ){
	extract( shortcode_atts( array(
		'title' => 'Section Title',
		'show' => '',
	), $atts, 'section' ) );
	return "<div class='accordion-header ".$show."'><a href='#'><span class='icon-plus'></span>".$title."</a></div><div class='accordion-content ".$show."'>".$content."</div>";
}
add_shortcode( 'section', 'section_func' );

//[tabs]
function tabs_func( $atts, $content = null ){
	$GLOBALS['nation_tab_count'] = 0;
	do_shortcode( $content );
	$tabs = '';
	if( is_array( $GLOBALS['nation_tabs'] ) ){
		$z=1;
		$panes = "<div id='tabs-content'>";
		foreach( $GLOBALS['nation_tabs'] as $tab ){
			$tabs .= '<li><a href="#" name="#tab'.$z.'">'.$tab['title'];
			if ( isset($tab['icon']) && $tab['icon'] != '' ) { 
				$tabs .= ' <span class="'.$tab['icon'].'"></span>';
			}
			$tabs .= '</a></li>';
			$panes .= '<div id="tab'.$z.'" class="tab-content">'.$tab['content'].'</div>';
			$z++;
		}
		$return = "<div id='tabs-widget-wrap'>"."<ul id='tabs'>".$tabs.'</ul>'."\n".$panes."</div></div>";
	}
	return $return;
}
add_shortcode( 'tabs', 'tabs_func' );

//[tab]
function tab_func( $atts, $content = null ){
	extract(shortcode_atts(array(
	'title' => 'Tab Title',
	'icon' => ''
	), $atts, 'tab'));
	$x = $GLOBALS['nation_tab_count'];
	$GLOBALS['nation_tabs'][$x] = array( 'title' => $title, 'content' =>  $content, 'icon' => $icon );
	$GLOBALS['nation_tab_count']++;
}
add_shortcode( 'tab', 'tab_func' );

//[info]
function info_func( $atts, $content = null ){
	extract(shortcode_atts(array(
	'icon' => ''
	), $atts, 'info'));
	return "<div class='info-message info'><span class='".$icon."'></span>".$content."</div>";
}
add_shortcode( 'info', 'info_func' );

//[success]
function success_func( $atts, $content = null ){
	extract(shortcode_atts(array(
	'icon' => ''
	), $atts, 'success'));
	return "<div class='info-message success'><span class='".$icon."'></span>".$content."</div>";
}
add_shortcode( 'success', 'success_func' );

//[error]
function error_func( $atts, $content = null ){
	extract(shortcode_atts(array(
	'icon' => ''
	), $atts, 'error'));
	return "<div class='info-message error'><span class='".$icon."'></span>".$content."</div>";
}
add_shortcode( 'error', 'error_func' );

//[warning]
function warning_func( $atts, $content = null ){
	extract(shortcode_atts(array(
	'icon' => ''
	), $atts, 'warning'));
	return "<div class='info-message warning'><span class='".$icon."'></span>".$content."</div>";
}
add_shortcode( 'warning', 'warning_func' );

//[icon]
function icon_func( $atts, $content = null ){
	extract(shortcode_atts(array(
	'name' => ''
	), $atts, 'icon'));
	return "<span class='".$name."'></span>";
}
add_shortcode( 'icon', 'icon_func' );

?>