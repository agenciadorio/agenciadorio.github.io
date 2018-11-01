<?php
	/*
	Plugin Name: Finance Extra
	Plugin URI: http://www.themesawesome.com
	Description: A plugin to add functionality to Premium Theme Finance from Themes Awesome
	Version: 1.0
	Author: Themes Awesome
	Author URI: http://www.themesawesome.com
	License: GPL2
	*/



define( 'FINANCE_EXTRA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'FINANCE_EXTRA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );



// Flush rewrite rules on activation
function finance_extra_activation() {
    flush_rewrite_rules(true);
}

/*-----------------------------------------------------------------------------------*/
/* The Service custom post type
/*-----------------------------------------------------------------------------------*/
add_action('init', 'finance_service_register'); 
    function finance_service_register() { 


        $labels = array(
            'name'               => _x('Service', 'Service General Name', 'finance'),
            'singular_name'      => _x('Service', 'Service Singular Name', 'finance'),
            'add_new'            => _x('Add New', 'Add New Service Name', 'finance'),
            'add_new_item'       => __('Add New Service', 'finance'),
            'edit_item'          => __('Edit Service', 'finance'),
            'new_item'           => __('New Service', 'finance'),
            'view_item'          => __('View Service', 'finance'),
            'search_items'       => __('Search Service', 'finance'),
            'not_found'          => __('Nothing found', 'finance'),
            'not_found_in_trash' => __('Nothing found in Trash', 'finance'),
            'parent_item_colon'  => ''
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'query_var'          => 'service',
            'capability_type'    => 'post',
            'hierarchical'       => false,
            'rewrite'            => array( 'slug' => 'service' ),
            'supports'           => array('title','editor','thumbnail'),
            'menu_position'       => 5,

        ); 

        register_post_type('finance-service' , $args);

    }

/*-----------------------------------------------------------------------------------*/
/* The Team custom post type
/*-----------------------------------------------------------------------------------*/

add_action('init', 'finance_team_register'); 
    function finance_team_register() {

        $labels = array(
            'name'                => _x( 'Team', 'Post Type General Name', 'finance' ),
            'singular_name'       => _x( 'Team', 'Post Type Singular Name', 'finance' ),
            'menu_name'           => __( 'Team', 'finance' ),
            'parent_item_colon'   => __( 'Parent Team:', 'finance' ),
            'all_items'           => __( 'All Team', 'finance' ),
            'view_item'           => __( 'View Team', 'finance' ),
            'add_new_item'        => __( 'Add New Team', 'finance' ),
            'add_new'             => __( 'Add New', 'finance' ),
            'edit_item'           => __( 'Edit Team', 'finance' ),
            'update_item'         => __( 'Update Team', 'finance' ),
            'search_items'        => __( 'Search Team', 'finance' ),
            'not_found'           => __( 'Not found', 'finance' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'finance' ),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'query_var'          => 'team',
            'capability_type'    => 'post',
            'hierarchical'       => false,
            'rewrite'            => array( 'slug' => 'team' ),
            'supports'           => array('title','editor','thumbnail'),
            'menu_position'       => 6,

        ); 
        register_post_type( 'finance-team', $args );
    }

/*-----------------------------------------------------------------------------------*/
/* The Project custom post type
/*-----------------------------------------------------------------------------------*/

add_action('init', 'finance_project_register'); 
    function finance_project_register() {

        $labels = array(
            'name'                => _x( 'Case', 'Post Type General Name', 'finance' ),
            'singular_name'       => _x( 'Case', 'Post Type Singular Name', 'finance' ),
            'menu_name'           => __( 'Case', 'finance' ),
            'parent_item_colon'   => __( 'Parent Case:', 'finance' ),
            'all_items'           => __( 'All Case', 'finance' ),
            'view_item'           => __( 'View Case', 'finance' ),
            'add_new_item'        => __( 'Add New Case', 'finance' ),
            'add_new'             => __( 'Add New', 'finance' ),
            'edit_item'           => __( 'Edit Case', 'finance' ),
            'update_item'         => __( 'Update Case', 'finance' ),
            'search_items'        => __( 'Search Case', 'finance' ),
            'not_found'           => __( 'Not found', 'finance' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'finance' ),
        );
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'query_var'          => 'project',
            'capability_type'    => 'post',
            'hierarchical'       => false,
            'rewrite'            => array( 'slug' => 'case' ),
            'supports'           => array('title','editor','thumbnail'),
            'menu_position'       => 7,

        ); 
        register_post_type( 'finance-project', $args );

    }

/*-----------------------------------------------------------------------------------*/
/* The Testimonial custom post type
/*-----------------------------------------------------------------------------------*/

add_action('init', 'finance_testimonial_register'); 
    function finance_testimonial_register() { 

        $labels = array(
            'name'               => _x('Testimonial', 'Testimonial General Name', 'finance'),
            'singular_name'      => _x('Testimonial', 'Testimonial Singular Name', 'finance'),
            'add_new'            => _x('Add New', 'Add New Testimonial Name', 'finance'),
            'add_new_item'       => __('Add New Testimonial', 'finance'),
            'edit_item'          => __('Edit Testimonial', 'finance'),
            'new_item'           => __('New Testimonial', 'finance'),
            'view_item'          => __('View Testimonial', 'finance'),
            'search_items'       => __('Search Testimonial', 'finance'),
            'not_found'          => __('Nothing found', 'finance'),
            'not_found_in_trash' => __('Nothing found in Trash', 'finance'),
            'parent_item_colon'  => ''
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'query_var'          => 'testimonial',
            'capability_type'    => 'post',
            'hierarchical'       => false,
            'rewrite'            => array( 'slug' => 'testimonial' ),
            'supports'           => array('title','editor','thumbnail'),
            'menu_position'       => 5,

        ); 

        register_post_type('finance-testimonial' , $args);

    }

/*-----------------------------------------------------------------------------------*/
/* The FAQ custom post type
/*-----------------------------------------------------------------------------------*/

add_action('init', 'finance_faq_register'); 
    function finance_faq_register() { 


        $labels = array(
            'name'               => _x('FAQ', 'FAQ General Name', 'finance'),
            'singular_name'      => _x('FAQ', 'FAQ Singular Name', 'finance'),
            'add_new'            => _x('Add New', 'Add New FAQ Name', 'finance'),
            'add_new_item'       => __('Add New FAQ', 'finance'),
            'edit_item'          => __('Edit FAQ', 'finance'),
            'new_item'           => __('New FAQ', 'finance'),
            'view_item'          => __('View FAQ', 'finance'),
            'search_items'       => __('Search FAQ', 'finance'),
            'not_found'          => __('Nothing found', 'finance'),
            'not_found_in_trash' => __('Nothing found in Trash', 'finance'),
            'parent_item_colon'  => ''
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'query_var'          => 'faq',
            'capability_type'    => 'post',
            'hierarchical'       => false,
            'rewrite'            => array( 'slug' => 'faq' ),
            'supports'           => array('title','editor'),
            'menu_position'       => 5,

        ); 

        register_post_type('finance-faq' , $args);

    }


if(!function_exists('redux_register_custom_extension_loader')) :
    function redux_register_custom_extension_loader($ReduxFramework) {
        $path    = dirname( __FILE__ ) . '/extensions/';
            $folders = scandir( $path, 1 );
            foreach ( $folders as $folder ) {
                if ( $folder === '.' or $folder === '..' or ! is_dir( $path . $folder ) ) {
                    continue;
                }
                $extension_class = 'ReduxFramework_Extension_' . $folder;
                if ( ! class_exists( $extension_class ) ) {
                    // In case you wanted override your override, hah.
                    $class_file = $path . $folder . '/extension_' . $folder . '.php';
                    $class_file = apply_filters( 'redux/extension/' . $ReduxFramework->args['opt_name'] . '/' . $folder, $class_file );
                    if ( $class_file ) {
                        require_once( $class_file );
                    }
                }
                if ( ! isset( $ReduxFramework->extensions[ $folder ] ) ) {
                    $ReduxFramework->extensions[ $folder ] = new $extension_class( $ReduxFramework );
                }
            }
    }
    // Modify {$redux_opt_name} to match your opt_name
    add_action("redux/extensions/finance_framework/before", 'redux_register_custom_extension_loader', 0);
endif;


    include_once dirname( __FILE__ ) . '/finance-importer.php';
