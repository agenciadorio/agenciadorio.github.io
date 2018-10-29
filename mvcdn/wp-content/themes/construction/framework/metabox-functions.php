<?php

add_action( 'cmb2_init', 'construction_general_metabox' );
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_init' hook.
 */
function construction_general_metabox() {
	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$cmb_demo = new_cmb2_box( array(
		'id'            => 'general-metabox',
		'title'         => esc_html__( 'SEO Fields', 'construction' ),
		'object_types'  => array( 'page', 'post' ), // Post type
		// 'show_on_cb' => 'construction_show_if_front_page', // function should return a bool value
		// 'context'    => 'normal',
		// 'priority'   => 'high',
		// 'show_names' => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // true to keep the metabox closed by default
	) );

	$cmb_demo->add_field( array(
            'name' => esc_html__('SEO title', 'construction'),
            'desc' => esc_html__('Title for SEO (optional)', 'construction'),
            'id'   => construction_get_prefix('seo_title' ),
            'type' => 'text',
	) );
	$cmb_demo->add_field( array(
            'name' => esc_html__('SEO Keywords', 'construction'),
            'desc' => esc_html__('SEO keywords (optional)', 'construction'),
            'id'   => construction_get_prefix('seo_keywords' ),
            'type' => 'text'
	) );
	$cmb_demo->add_field( array(
            'name' => esc_html__('SEO Description', 'construction'),
            'desc' => esc_html__('SEO description (optional)', 'construction'),
            'id'   => construction_get_prefix('seo_description' ),
            'type' => 'textarea_small',
	) );
}


// add_action( 'cmb2_init', 'construction_body_metabox' );

// function construction_body_metabox(){
// 	$cmb_demo = new_cmb2_box( array(
// 		'id'            => construction_get_prefix('bodylayout'),
// 		'title'         => esc_html__( 'Body layout', 'construction' ),
// 		'object_types'  => array( 'page' ), // Post type
// 	) );
// 	$cmb_demo->add_field( array(
// 	    'name' => esc_html__('Choose body layout','construction'),
// 	    'id'   => construction_get_prefix('border_layout'),
// 	    'type' => 'select',
//         'options'          => array(
//         	"no" => esc_html__("Normal",'construction'),
//             "1" => esc_html__("Border Box",'construction'),
//         	'global' => esc_html__('Inherit From Theme Options','construction'),
//         ),
//         'default' => 'global'
// 	) );

// 	$cmb_demo->add_field( array(
// 	    'name' => esc_html__('Choose Border Box Color','construction'),
// 	    'id'      => construction_get_prefix('border_box_color'),
// 	    'type'    => 'colorpicker',
// 	    'default' => '',
// 	) );
// }

add_action( 'cmb2_init', 'construction_header_metabox' );
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_init' hook.
 */
function construction_header_metabox(){

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$cmb_demo = new_cmb2_box( array(
		'id'            => construction_get_prefix('hd-metabox'),
		'title'         => esc_html__( 'Header Settings', 'construction' ),
		'object_types'  => array( 'page' ), // Post type
		// 'show_on_cb' => 'construction_show_if_front_page', // function should return a bool value
	) );

	$cmb_demo->add_field( array(
	    'name' => esc_html__('Show Page Title Bar','construction'),
	    'desc' => esc_html__('show Page title bar (optional)','construction'),
	    'id'   => construction_get_prefix('show-title-bar'),
	    'type' => 'select',
        'options' => array(
        	'no' => esc_html__('No, Please!', 'construction'),
        	'1' => esc_html__('Yes, Please!', 'construction'),
        	'global' => esc_html__('Inherit From Theme Options','construction')
        ),
        'default' => 'no'

	) );
	$cmb_demo->add_field( array(
		'name'    => esc_html__( 'Page title bar background', 'construction' ),
		'desc'    => esc_html__( 'Custom page title bar background for "Header cover image" type, default inherit from theme options', 'construction' ),
		'id'      => construction_get_prefix('title-bar-img'),
		'type'    => 'file'
	) );

	// $cmb_demo->add_field( array(
	//     'name' => esc_html__('Sub title for header( page title bar required )','construction'),
	//     'desc' => esc_html__('Enter sub title for page title bar (optional)','construction'),
	//     'id'   => construction_get_prefix('sub-title'),
	//     'type' => 'text'
	// ) );
}

add_action( 'cmb2_init', 'construction_footer_metabox' );
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_init' hook.
 */
function construction_footer_metabox(){

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$cmb_demo = new_cmb2_box( array(
		'id'            => construction_get_prefix('ft-metabox'),
		'title'         => esc_html__( 'Footer Settings', 'construction' ),
		'object_types'  => array( 'page' ), // Post type
		// 'show_on_cb' => 'construction_show_if_front_page', // function should return a bool value
	) );

	$cmb_demo->add_field( array(
	    'name' => esc_html__('Show Newsletter','construction'),
	    'desc' => esc_html__('show Newsletter (optional)','construction'),
	    'id'   => construction_get_prefix('show-newsletter'),
	    'type' => 'select',
        'options' => array(
        	'no' => esc_html__('No, Please!', 'construction'),
        	'1' => esc_html__('Yes, Please!', 'construction'),
        	'global' => esc_html__('Inherit From Theme Options','construction')
        ),
        'default' => '1'

	) );
	
}

add_action( 'cmb2_init', 'construction_testimonial_metabox' );

function construction_testimonial_metabox(){
	$cmb_demo = new_cmb2_box( array(
		'id'            => construction_get_prefix('testimonial'),
		'title'         => esc_html__( 'Testimonial Options', 'construction' ),
		'object_types'  => array( 'testimonial' ), // Post type
	) );
	$cmb_demo->add_field( array(
        'name' => esc_html__('Job', 'construction'),
        'desc' => esc_html__('Ex: (ceo)', 'construction'),
        'id'   => construction_get_prefix('job'),
        'type' => 'text'
	) );

}

add_action( 'cmb2_init', 'construction_service_metabox' );
function construction_service_metabox(){
	$cmb_demo = new_cmb2_box( array(
		'id'            => construction_get_prefix('service'),
		'title'         => esc_html__( 'Service Options', 'construction' ),
		'object_types'  => array( 'service' ), // Post type
	) );
	$cmb_demo->add_field( array(
        'name' => esc_html__('Brochure File', 'construction'),
        'desc' => esc_html__('Choose Brochure File', 'construction'),
        'id'   => construction_get_prefix('brochure'),
        'type' => 'file'
	) );

}


add_action( 'cmb2_init', 'construction_project_metabox' );

function construction_project_metabox(){
	$cmb_demo = new_cmb2_box( array(
		'id'            => construction_get_prefix('project'),
		'title'         => esc_html__( 'Project Options', 'construction' ),
		'object_types'  => array( 'project' ), // Post type
		// 'show_on_cb' => 'construction_show_if_front_page', // function should return a bool value
		// 'context'    => 'normal',
		// 'priority'   => 'high',
		// 'show_names' => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // true to keep the metabox closed by default
	) );
	$cmb_demo->add_field( array(
		'name'    => esc_html__( 'Choose List Image', 'construction' ),
		'desc'    => esc_html__( 'Choose list image about project', 'construction' ),
		'id'      => construction_get_prefix('gallery'),
		'type'    => 'file_list',
		'show_on_cb' => 'construction_show_if_normal_project'
	) );
	$cmb_demo->add_field( array(
        'name' => esc_html__('Client', 'construction'),
        'desc' => esc_html__('Client (optional)', 'construction'),
        'id'   => construction_get_prefix('client'),
        'type' => 'text'
	) );

	$cmb_demo->add_field( array(
        'name' => esc_html__('From Date', 'construction'),
        'desc' => esc_html__('Date realised (optional)', 'construction'),
        'id'   => construction_get_prefix('from_date'),
        'type' => 'text_date',
        'date_format' => 'm/d/Y'
	) );

	$cmb_demo->add_field( array(
        'name' => esc_html__('End Date', 'construction'),
        'desc' => esc_html__('Date end (optional)', 'construction'),
        'id'   => construction_get_prefix('end_date'),
        'type' => 'text_date',
        'date_format' => 'm/d/Y'
	) );

	$cmb_demo->add_field( array(
        'name' => esc_html__('Complete Date', 'construction'),
        'desc' => esc_html__('Date Complete (optional)', 'construction'),
        'id'   => construction_get_prefix('complete_date'),
        'type' => 'text_date',
        'date_format' => 'm/d/Y'
	) );

}


function construction_show_if_normal_project( $cmb ){
	if( get_post_format( $cmb->object_id ) !== 'video' ){
		return true;
	}
	return false;
}

function construction_show_if_post_video( $cmb ){
	if( get_post_format( $cmb->object_id ) === 'video' ){
		return true;
	}
	return false;
}

function construction_show_if_post_gallery( $cmb ){
	if( get_post_format( $cmb->object_id ) === 'gallery' ){
		return true;
	}
	return false;
}


function construction_show_if_post_audio( $cmb ){
	if( get_post_format( $cmb->object_id ) === 'audio' ){
		return true;
	}
	return false;
}

function construction_show_if_post_link( $cmb ){
	if( get_post_format( $cmb->object_id ) === 'link' ){
		return true;
	}
	return false;
}


function construction_show_if_post_embed( $cmb ){
	if( get_post_format( $cmb->object_id ) === 'audio' || get_post_format( $cmb->object_id ) === 'video' ){
		return true;
	}
	return false;
}


add_action( 'cmb2_init', 'construction_blog_metabox' );

function construction_blog_metabox(){
	$cmb_demo = new_cmb2_box( array(
		'id'            => construction_get_prefix('blog'),
		'title'         => esc_html__( 'Blog Options', 'construction' ),
		'object_types'  => array( 'post' ), // Post type
		// 'show_on_cb' => 'construction_show_if_front_page', // function should return a bool value
		// 'context'    => 'normal',
		// 'priority'   => 'high',
		// 'show_names' => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // true to keep the metabox closed by default
	) );
	$cmb_demo->add_field( array(
		'name'    => esc_html__( 'Choose List Image', 'construction' ),
		'desc'    => esc_html__( 'Choose list image about project', 'construction' ),
		'id'      => construction_get_prefix('gallery'),
		'type'    => 'file_list',
		'show_on_cb' => 'construction_show_if_post_gallery'
	) );
	$cmb_demo->add_field( array(
		'name'    => esc_html__( 'Enter video url', 'construction' ),
		'desc'    => esc_html__( 'Enter video url (vimeo, youtube, etc )', 'construction' ),
		'id'      => construction_get_prefix('video'),
		'type'    => 'oembed',
		'show_on_cb' => 'construction_show_if_post_video'
	) );

	$cmb_demo->add_field( array(
		'name'    => esc_html__( 'Height of Embed', 'construction' ),
		'desc'    => esc_html__( 'Enter height (px)', 'construction' ),
		'id'      => construction_get_prefix('height'),
		'type'    => 'text',
		'show_on_cb' => 'construction_show_if_post_embed'
	) );

}


add_action( 'cmb2_init', 'construction_user_profile_metabox' );
/**
 * Hook in and add a metabox to add fields to the user profile pages
 */
function construction_user_profile_metabox() {

	// Start with an underscore to hide fields from custom fields list

	/**
	 * Metabox for the user profile screen
	 */
	$cmb_user = new_cmb2_box( array(
		'id'               => construction_get_prefix('edit'),
		'title'            => esc_html__( 'User Profile Metabox', 'construction' ),
		'object_types'     => array( 'user' ), // Tells CMB2 to use user_meta vs post_meta
		'show_names'       => true,
		'new_user_section' => 'add-new-user', // where form will show on new user page. 'add-existing-user' is only other valid option.
	) );

	$cmb_user->add_field( array(
		'name' => esc_html__( 'Position', 'construction' ),
		'desc' => esc_html__( 'User position', 'construction' ),
		'id'   => construction_get_prefix('position'),
		'type' => 'text',
	) );

	$cmb_user->add_field( array(
		'name'     => esc_html__( 'Extra Info', 'construction' ),
		'desc'     => esc_html__( 'field description (optional)', 'construction' ),
		'id'       => construction_get_prefix('extra_info'),
		'type'     => 'title',
		'on_front' => false,
	) );

	$cmb_user->add_field( array(
		'name'    => esc_html__( 'Avatar', 'construction' ),
		'desc'    => esc_html__( 'field description (optional)', 'construction' ),
		'id'      => construction_get_prefix('avatar'),
		'type'    => 'file',
	) );

	$cmb_user->add_field( array(
		'name' => esc_html__( 'Facebook URL', 'construction' ),
		'desc' => esc_html__( 'field description (optional)', 'construction' ),
		'id'   => construction_get_prefix('facebookurl'),
		'type' => 'text_url',
	) );

	$cmb_user->add_field( array(
		'name' => esc_html__( 'Twitter URL', 'construction' ),
		'desc' => esc_html__( 'field description (optional)', 'construction' ),
		'id'   => construction_get_prefix('twitterurl'),
		'type' => 'text_url',
	) );

	$cmb_user->add_field( array(
		'name' => esc_html__( 'Google+ URL', 'construction' ),
		'desc' => esc_html__( 'field description (optional)', 'construction' ),
		'id'   => construction_get_prefix('googleplusurl'),
		'type' => 'text_url',
	) );

	$cmb_user->add_field( array(
		'name' => esc_html__( 'Linkedin URL', 'construction' ),
		'desc' => esc_html__( 'field description (optional)', 'construction' ),
		'id'   => construction_get_prefix('linkedinurl'),
		'type' => 'text_url',
	) );

	$cmb_user->add_field( array(
		'name' => esc_html__( 'User Birthday', 'construction' ),
		'desc' => esc_html__( 'Birthday (optional)', 'construction' ),
		'id'   => construction_get_prefix('birthday'),
		'type' => 'text_date',
	) );

	$cmb_user->add_field( array(
		'name' => esc_html__( 'Gender', 'construction' ),
		'desc' => esc_html__( 'Gender (optional)', 'construction' ),
		'id'   => construction_get_prefix('sex'),
		'options'          => array(
	        'male' => esc_html__( 'Male', 'construction' ),
	        'feemale'   => esc_html__( 'Free Male','construction'),
	        'none'     => esc_html__( 'Hide', 'construction' ),
	    ),
		'type' => 'select',
	) );

	$cmb_user->add_field( array(
		'name' => esc_html__( 'Country', 'construction' ),
		'desc' => esc_html__( 'User city (optional)', 'construction' ),
		'id'   => construction_get_prefix('country'),
		'type' => 'text',
	) );

	$cmb_user->add_field( array(
		'name' => esc_html__( 'City', 'construction' ),
		'desc' => esc_html__( 'User city (optional)', 'construction' ),
		'id'   => construction_get_prefix('city'),
		'type' => 'text',
	) );

	$cmb_user->add_field( array(
		'name' => esc_html__( 'Address', 'construction' ),
		'desc' => esc_html__( 'User address (optional)', 'construction' ),
		'id'   => construction_get_prefix('address'),
		'type' => 'text',
	) );

}
