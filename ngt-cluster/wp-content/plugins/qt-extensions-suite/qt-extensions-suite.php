<?php
/*
Plugin Name: QT Extensions Suite
Plugin URI: http://www.qantumthemes.com/
Description: Add Custom Extensions to your website: custom types and more
Author: QantumThemes
Version: 1.1.7
Text Domain: qt-extensions-suite
Domain Path: /languages
*/

/**
 *
 *	The plugin textdomain
 * 
 */


function qtextensions_load_plugin_textdomain() {
  load_plugin_textdomain( 'qt-extensions-suite', FALSE, basename( dirname( __FILE__ ) ) . '/languages' );
}

add_action( 'plugins_loaded', 'qtextensions_load_plugin_textdomain' );







if(!function_exists('custom_meta_box_field')){
	require	plugin_dir_path( __FILE__ ) . '/metaboxes/meta_box.php';
}

require plugin_dir_path( __FILE__ ) . '/custom-types/shows/shows-type.php';
require plugin_dir_path( __FILE__ ) . '/custom-types/schedule/schedule-type.php';
require plugin_dir_path( __FILE__ ) . '/custom-types/chart/chart-type.php';
require plugin_dir_path( __FILE__ ) . '/custom-types/member/member-type.php';

require plugin_dir_path( __FILE__ ) . '/custom-types/artist/artist-type.php';
require plugin_dir_path( __FILE__ ) . '/custom-types/podcast/podcast-type.php';
require plugin_dir_path( __FILE__ ) . '/custom-types/events/events-type.php';
require plugin_dir_path( __FILE__ ) . '/custom-types/gallery/gallery-type.php';
require plugin_dir_path( __FILE__ ) . '/custom-types/release/release-type.php';
require plugin_dir_path( __FILE__ ) . '/custom-types/radio/radio-type.php';
include_once(dirname( __FILE__ ) .'/qt_tinymce_extensions/qt-tinymce-buttons.php' );



if(is_admin()){
	include_once(plugin_dir_path( __FILE__ ) .'/qt-user-picture/qt-user-picture.php' );
	if(defined( 'WPB_VC_VERSION' )){
		include_once(plugin_dir_path( __FILE__ ) .'/visual-composer/qt-visualcomposer-integration.php' );
	}
}





// NOTE: Icon Choice (FontAwesome and Google Material Icons) added in meta_box.php
// Icons editor button added from theme

/*
*
*	Gets the current post type in the WordPress Admin
*
*/
if(!function_exists('get_current_post_type')){
function get_current_post_type() {
  global $post, $typenow, $current_screen;
  if ( $post && $post->post_type )
    return $post->post_type;
  elseif( $typenow )
    return $typenow;
  elseif( $current_screen && $current_screen->post_type )
    return $current_screen->post_type;
  elseif( isset( $_REQUEST['post_type'] ) )
    return sanitize_key( $_REQUEST['post_type'] );
  return null;
}}



/* Get group of meta for a post
=============================================*/
if(!function_exists('qantumthemes_get_group')){
function qantumthemes_get_group( $group_name , $post_id = NULL ){
  	global $post; 	  
 	if(!$post_id){ $post_id = $post->ID; }
  	$post_meta_data = get_post_meta($post_id, $group_name, true);  
  	return $post_meta_data;
}}



/*
*	Scripts and styles Backend
*	=============================================================
*/
if(!function_exists("qt_extensionsuite_loader_backend")){
function qt_extensionsuite_loader_backend(){
	wp_enqueue_script( 'qtExtensionSuiteScript',plugins_url( '/assets/main.admin.js' , __FILE__ ), $deps = array("jquery"), $ver = false, $in_footer = true );
	wp_enqueue_style( 'qtExtensionSuiteStyle',plugins_url( '/assets/style.admin.css' , __FILE__ ),false);
}}
add_action("admin_enqueue_scripts",'qt_extensionsuite_loader_backend');






/*
*	Icons shortcode
*	=============================================================
*/
if(!function_exists("qticon_shortcode")){
	function qticon_shortcode($atts){
		extract( shortcode_atts( array(
			'class' => "",
			'size' => ""
		), $atts ) );
		return '<i class="qt-editoricon '.esc_attr($class .' '. $size).'"></i>';
	}
}
add_shortcode( 'qticon', 'qticon_shortcode' );

/*
*	Page special attributes
*	=============================================================
*/

if(!function_exists("add_special_fields")){

function add_special_fields() {
    $qt_design_settings = array (
		/*array(
			'label' => 'Custom page background',
			'id' =>  'qw_post_custom_bg',
			'type' => 'image'
		) 
		,*/ array (
			'label' => 'Hide title',
			'id' =>  'qw_post_hide_title',
			'type' => 'checkbox'
		)   
		, array (
			'label' => 'Hide featured image',
			'id' =>  'qw_post_hide_featuredimage',
			'type' => 'checkbox'
		) 

		, array (
			'label' => 'Transparent menu',
			'id' =>  'qw_header_transparent_menu',
			'pagetemplate' => 'page-composer.php',
			'default' => '1',
			'type' => 'checkbox'
		) 

		, array (
			'label' => 'Transparent menu',
			'id' =>  'qw_header_transparent_menu_tripleview',
			'pagetemplate' => 'page-tripleview.php',
			'default' => '1',
			'type' => 'checkbox'
		) 

		, array (
			'label' => 'Add polygon decoration',
			'id' =>  'qw_polydecor_page',
			'pagetemplate' => 'page-composer.php',
			'default' => '0',
			'type' => 'checkbox'
		) 

		, array (
			'label' => 'Page sections',
			'pagetemplate' => 'page-tripleview.php',
			'type' => 'section'
		) 


		,array ( // Repeatable & Sortable Text inputs
		'label'	=> 'Sections', // <label>
		'pagetemplate' => 'page-tripleview.php',
		'id'	=> 'sections', // field id and name
		'type'	=> 'repeatable', // type of field
		'sanitizer' => array ( // array of sanitizers with matching kets to next array
			'featured' => 'meta_box_santitize_boolean',
			'title' => 'sanitize_text_field',
			'desc' => 'wp_kses_data'
		),
		
		'repeatable_fields' => array ( // array of fields to be repeated
			'section_title' => array (
				'label' => 'Section title',
				'id' => 'section_title',
				'type' => 'text'
			) ,
			array (
				'label' => 'Section type',
				'id' 	=> 'section_type',
				//'class' => 'qw-conditional-fields',
				'type' 	=> 'select',
				'options' => array (
				                 	array('label' => __('Album releases',"qt-extensions-suite"),'value' => 'release'),
				                 	array('label' => __('Artists',"qt-extensions-suite"),'value' => 'artist'),
				                 	array('label' => __('Charts',"qt-extensions-suite"),'value' => 'chart'),		
				                 	array('label' => __('Events',"qt-extensions-suite"),'value' => 'event'),	
				                 	array('label' => __('Podcast',"qt-extensions-suite"),'value' => 'podcast'),			
				                 	array('label' => __('Post',"qt-extensions-suite"),'value' => 'post'),	
				                 	array('label' => __('Staff members',"qt-extensions-suite"),'value' => 'members'),
				                 	array('label' => __('Latest release',"qt-extensions-suite"),'value' => 'lastrelease'),
				                 	array('label' => __('Radio station',"qt-extensions-suite"),'value' => 'radiochannel')
								)
			),
			'background_image' => array (
				'label' => 'Section header image',
				'id' => 'background_image',
				'type' => 'image'
			),
			'section_icon' => array (
				'label' => 'Menu Icon',			
				'id' 	=> 'section_icon',
				'type' 	=> 'iconchoice'
			),
			'hide_title' => array (
				'label' => 'Hide title',
				'id' =>  'hide_title',
				'default' => '0',
				'type' => 'checkbox'
			) ,
			'section_link' => array (
				'label' => 'Link to archive',
				'desc' => 'Adding the URL to create a button at the end of the section',
				'id' => 'section_link',
				'default' => false,
				'type' => 'text'
			),
			'section_hide' => array (
				'label' => 'Hide this section (without deleting)',
				'id' =>  'section_hide',
				'default' => '0',
				'type' => 'checkbox'
			) ,
	
		)
	)                 
    );
    if(post_type_exists('page')){
        if(function_exists('custom_meta_box_field')){
            $main_box = new custom_add_meta_box('qt_design_settings', 'Design customization', $qt_design_settings, 'page', true );
        }
    }
    if(post_type_exists('post')){
        if(function_exists('custom_meta_box_field')){
            $main_box = new custom_add_meta_box('qt_design_settings', 'Design background', $qt_design_settings, 'post', true );
        }
    }
   
}}

add_action('init', 'add_special_fields');  




/*
*
*	We add some columns with featured images in the post archive so is easier 
*
*/
if (function_exists( 'add_theme_support' )){
    add_filter('manage_posts_columns', 'posts_columns', 5);
    add_action('manage_posts_custom_column', 'posts_custom_columns', 5, 2);    
}
function posts_columns($defaults){
    $defaults['wps_post_thumbs'] = __('Thumbs',"qt-extensions-suite");
    return $defaults;
}
function posts_custom_columns($column_name, $id){
	if($column_name === 'wps_post_thumbs'){
        echo the_post_thumbnail( array(125,80) );
    }
}

/**
 * Theme shortcodes
 */

require plugin_dir_path( __FILE__ ) . '/theme-shortcodes/radio-suite/shortcodes.php';
require plugin_dir_path( __FILE__ ) . '/theme-shortcodes/gridstacks/gridstacks.php';
require plugin_dir_path( __FILE__ ) . '/theme-shortcodes/qt-gallery-shortcode.php';
require plugin_dir_path( __FILE__ ) . '/theme-shortcodes/qt-release-shortcode.php';
require plugin_dir_path( __FILE__ ) . '/theme-shortcodes/qt-titles-shortcode.php';
require plugin_dir_path( __FILE__ ) . '/theme-shortcodes/qt-chart-shortcode.php';
require plugin_dir_path( __FILE__ ) . '/widgets/qt-archives-widget.php';
require plugin_dir_path( __FILE__ ) . '/widgets/qt-widget-onair.php';
require plugin_dir_path( __FILE__ ) . '/widgets/qt-widget-chart.php';
require plugin_dir_path( __FILE__ ) . '/widgets/qt-widget-upcoming.php';

/* Kirki Framework Files Inclusion
 * Documentation: https://github.com/aristath/kirki/wiki
=============================================*/
require_once plugin_dir_path( __FILE__ )  . '/customizer/kirki/kirki.php' ;
require_once plugin_dir_path( __FILE__ )  . '/customizer/kirki-configuration/class-kirki2-kirki.php';
require_once plugin_dir_path( __FILE__ )  . '/customizer/kirki-configuration/include-kirki.php';
require_once plugin_dir_path( __FILE__ )  . '/customizer/kirki-configuration/config.php';



/* 14. User special fields
=============================================*/
$qt_user_social = array(

	"twitter" => array(
					'label' => esc_attr__( 'Twitter Url' , "qt-extensions-suite" ),
					'icon' => "qticon-twitter" )
	,"facebook" => array(
					'label' => esc_attr__( 'Facebook Url' , "qt-extensions-suite" ),
					'icon' => "qticon-facebook" ) 
	,"google" => array(
					'label' => esc_attr__( 'Google Url' , "qt-extensions-suite" ),
					'icon' => "qticon-google" )
	,"flickr" => array(
					'label' => esc_attr__( 'Flickr Url' , "qt-extensions-suite" ),
					'icon' => "qticon-flickr" )
	,"pinterest" => array(
					'label' => esc_attr__( 'Pinterest Url' , "qt-extensions-suite" ),
					'icon' => "qticon-pinterest" )
	,"amazon" => array(
					'label' => esc_attr__( 'Amazon Url' , "qt-extensions-suite" ),
					'icon' => "qticon-amazon" )
	,"github" => array(
					'label' => esc_attr__( 'Github Url' , "qt-extensions-suite" ),
					'icon' => "fa fa-github-alt" )
	,"soundcloud" => array(
					'label' => esc_attr__( 'Soundcloud Url' , "qt-extensions-suite" ),
					'icon' => "qticon-cloud" )
	,"vimeo" => array(
					'label' => esc_attr__( 'Vimeo Url' , "qt-extensions-suite" ),
					'icon' => "qticon-vimeo" )
	,"tumblr" => array(
					'label' => esc_attr__( 'Tumblr Url' , "qt-extensions-suite" ),
					'icon' => "qticon-tumblr" )
	,"youtube" => array(
					'label' => esc_attr__( 'Youtube Url' , "qt-extensions-suite" ),
					'icon' => "qticon-youtube" )
	,"wordpress" => array(
					'label' => esc_attr__( 'WordPress Url' , "qt-extensions-suite" ),
					'icon' => "qticon-wordpress" )
	,"wikipedia" => array(
					'label' => esc_attr__( 'Wikipedia Url' , "qt-extensions-suite" ),
					'icon' => "qticon-wikipedia" )
	,"instagram" => array(
					'label' => esc_attr__( 'Instagram Url' , "qt-extensions-suite" ),
					'icon' => "qticon-instagram" )
);

global $qt_user_social;
if ( ! function_exists( 'qantumthemes_modify_contact_methods' ) ) {
function qantumthemes_modify_contact_methods( $profile_fields ) {
	global $qt_user_social;
	foreach ( $qt_user_social as $q => $v ){
		$profile_fields[$q] = $v['label'];
	}
	return $profile_fields;
}}
add_filter('user_contactmethods', 'qantumthemes_modify_contact_methods');

/*
Saving the user meta
*/
add_action( 'personal_options_update', 'qantumthemes_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'qantumthemes_save_extra_profile_fields' );
function qantumthemes_save_extra_profile_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;
    global $qt_user_social;
	foreach ( $qt_user_social as $q => $v ){
		 update_user_meta( $user_id, $q , esc_url($_POST[$q]), esc_url(get_the_author_meta( $q , $user_id )) );
	}
}

