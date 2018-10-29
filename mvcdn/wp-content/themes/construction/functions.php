<?php
// text domain
if( ! defined('CONSTRUCTION_URI_PATH') ) define('CONSTRUCTION_URI_PATH', get_template_directory_uri() );
if( ! defined('CONSTRUCTION_ABS_PATH') ) define('CONSTRUCTION_ABS_PATH', get_template_directory() );
if( ! defined('CONSTRUCTION_ABS_FR') ) define('CONSTRUCTION_ABS_FR', CONSTRUCTION_ABS_PATH.'/framework');
if( ! defined('CONSTRUCTION_URI_ASSETS') ) define('CONSTRUCTION_URI_ASSETS', CONSTRUCTION_URI_PATH.'/assets');
if( ! defined('CONSTRUCTION_URI_UPLOAD') ) define('CONSTRUCTION_URI_UPLOAD', CONSTRUCTION_URI_PATH.'/upload');



add_action('init', 'construction_lib_requirement');
require_once CONSTRUCTION_ABS_FR .'/custom-functions.php';//please add your custom function to this file instead of in functions.php
require_once CONSTRUCTION_ABS_FR .'/metabox-functions.php';
require_once CONSTRUCTION_ABS_FR .'/theme-configs.php';
require_once CONSTRUCTION_ABS_FR .'/widget.php';
require_once CONSTRUCTION_ABS_FR .'/wp_bootstrap_navwalker.php';
if( function_exists('vc_set_shortcodes_templates_dir')){
  $dir = CONSTRUCTION_ABS_PATH . '/vc_templates';
  vc_set_shortcodes_templates_dir( $dir );
}

global $construction_theme_options;

function construction_setup()
{
    // Add RSS feed links to <head> for posts and comments.
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    // Enable support for Post Thumbnails, and declare two sizes.
    add_theme_support('post-thumbnails');
    
    // This theme uses wp_nav_menu() in two locations.
    register_nav_menus(array(
        'primary'     => esc_html__('Main Navigation', 'construction'),
       
    ));
    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support('html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
    ));
    load_theme_textdomain( 'construction', get_template_directory() . '/languages' );
    /*
     * Enable support for Post Formats.
     * See http://codex.wordpress.org/Post_Formats
     */
    add_theme_support('post-formats', array(
        'quote', 'video', 'audio', 'image', 'aside', 'gallery', 'link', 'status', 'chat',
    ));
    add_theme_support('custom-header');
    add_theme_support('custom-background');

}

add_action('after_setup_theme', 'construction_setup');

// set content width

if (!isset($content_width)) {
    $content_width = 875;

}

function construction_lib_requirement(){
  
  if(function_exists('vc_add_param')){
    require_once CONSTRUCTION_ABS_PATH . '/vc_functions.php';
  }
}

// register sidebar
function construction_widgets_init() {  
  register_sidebar( array(
    'name'          => esc_html__( 'Main Sidebar', 'construction' ),
    'id'            => 'sidebar-1',
    'description'   => esc_html__( 'Main sidebar that appears on Main Sidebar.', 'construction' ),
    'before_widget' => '<div id="%1$s" class="widget sidebar-widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3 class="sidebar-widget-title title">',
    'after_title'   => '</h3>',
  ) );
  
  register_sidebar( array(
    'name'          => esc_html__( 'Header Left Sidebar', 'construction' ),
    'id'            => construction_get_prefix('header-left-sidebar'),
    'description'   => esc_html__( 'Header left sidebar.', 'construction' ),
    'before_widget' => '<div id="%1$s" class="widget sidebar-widget head-widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3 class="sidebar-widget-title">',
    'after_title'   => '</h3>',
  ) );
  register_sidebar( array(
    'name'          => esc_html__( 'Header Right Sidebar', 'construction' ),
    'id'            => construction_get_prefix('header-right-sidebar'),
    'description'   => esc_html__( 'Header right sidebar.', 'construction' ),
    'before_widget' => '<div id="%1$s" class="widget sidebar-widget head-widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3 class="sidebar-widget-title">',
    'after_title'   => '</h3>',
  ) );
  register_sidebar( array(
    'name'          => esc_html__( 'Newsletter Left Sidebar (Phone)', 'construction' ),
    'id'            => construction_get_prefix('phone-sidebar'),
    'description'   => esc_html__( 'Phone sidebar.', 'construction' ),
    'before_widget' => '<div id="%1$s" class="widget sidebar-widget phone-widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3 class="sidebar-widget-title">',
    'after_title'   => '</h3>',
  ) );
  register_sidebar( array(
    'name'          => esc_html__( 'Newsletter Right Sidebar', 'construction' ),
    'id'            => construction_get_prefix('newsletter-sidebar'),
    'description'   => esc_html__( 'Newsletter right sidebar.', 'construction' ),
    'before_widget' => '<div id="%1$s" class="widget sidebar-widget newsletter-widget %2$s">',
    'after_widget'  => '</div></div>',
    'before_title'  => '<div class="col-md-4 col-lg-4"><h3 class="title">',
    'after_title'   => '</h3></div><div class="col-md-8 col-lg-8 p-r-0">',
  ) );
  register_sidebar( array(
    'name'          => esc_html__( 'Project Sidebar', 'construction' ),
    'id'            => construction_get_prefix('project-sidebar'),
    'description'   => esc_html__( 'Project sidebar.', 'construction' ),
    'before_widget' => '<div id="%1$s" class="widget sidebar-widget project-widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3 class="sidebar-widget-title">',
    'after_title'   => '</h3>',
  ) );

  register_sidebar( array(
    'name'          => esc_html__( 'Single Service Sidebar', 'construction' ),
    'id'            => construction_get_prefix('service-sidebar'),
    'description'   => esc_html__( 'Single Service sidebar.', 'construction' ),
    'before_widget' => '<div id="%1$s" class="widget sidebar-widget service-widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3 class="sidebar-widget-title">',
    'after_title'   => '</h3>',
  ) );

  register_sidebar( array(
    'name'          => esc_html__( 'Footeer Widget 1', 'construction' ),
    'id'            => construction_get_prefix('footer-widget1'),
    'description'   => esc_html__( 'Footer widget 1', 'construction' ),
    'before_widget' => '<div id="%1$s" class="widget footer-wg %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<div class="footer-heading"><h3 class="widget-title">',
    'after_title'   => '</h3></div>',
  ) );

  register_sidebar( array(
    'name'          => esc_html__( 'Footeer Widget 2', 'construction' ),
    'id'            => construction_get_prefix('footer-widget2'),
    'description'   => esc_html__( 'Footer widget 2', 'construction' ),
    'before_widget' => '<div id="%1$s" class="widget footer-wg %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<div class="footer-heading"><h3 class="widget-title">',
    'after_title'   => '</h3></div>',
  ) );
  register_sidebar( array(
    'name'          => esc_html__( 'Footeer Widget 3', 'construction' ),
    'id'            => construction_get_prefix('footer-widget3'),
    'description'   => esc_html__( 'Footer widget 3', 'construction' ),
    'before_widget' => '<div id="%1$s" class="widget footer-wg %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<div class="footer-heading"><h3 class="widget-title">',
    'after_title'   => '</h3></div>',
  ) );
  register_sidebar( array(
    'name'          => esc_html__( 'Footeer Widget 4', 'construction' ),
    'id'            => construction_get_prefix('footer-widget4'),
    'description'   => esc_html__( 'Footer widget 4', 'construction' ),
    'before_widget' => '<div id="%1$s" class="widget footer-wg %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<div class="footer-heading"><h3 class="widget-title">',
    'after_title'   => '</h3></div>',
  ) );
}
add_action( 'widgets_init', 'construction_widgets_init' );


function construction_theme_scripts_styles() {
  global $wp_scripts, $construction_theme_options;
  
  if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ){
    wp_enqueue_script( 'comment-reply' );
  }
  // include style

  wp_enqueue_style('bootstrap', get_template_directory_uri() .'/assets/css/bootstrap.min.css' );
  wp_enqueue_style('animate', get_template_directory_uri() .'/assets/css/animate/animate.css' );
  wp_enqueue_style('font-awesome', get_template_directory_uri() .'/assets/css/font-awesome.min.css' );
  wp_enqueue_style('owl.carousel', get_template_directory_uri() .'/assets/css/owl-coursel/owl.carousel.css' );
  wp_enqueue_style('owl.theme', get_template_directory_uri() .'/assets/css/owl-coursel/owl.theme.css' );
  wp_register_style( 'flexslider', get_template_directory_uri() .'/assets/css/flexslider.min.css', array(), '2.6.3' );
  wp_enqueue_style('construction-main', get_template_directory_uri() .'/assets/css/style.css',array(),'1.2' );
  // commont style
  wp_enqueue_style('construction-style', get_stylesheet_uri() );

  wp_add_inline_style( 'construction-style', construction_get_inline_css() );

  wp_enqueue_script( 'owl.carousel', get_template_directory_uri() .'/assets/js/owl-coursel/owl.carousel.js', array('jquery'),false,true );
   wp_enqueue_script( 'wow', get_template_directory_uri() .'/assets/js/wow.js', array('jquery'),false,true );

   wp_register_script( 'flexslider', get_template_directory_uri() .'/assets/js/jquery.flexslider-min.js', array(), '2.6.3', true );
   wp_enqueue_script( 'bootstrap', get_template_directory_uri() .'/assets/js/bootstrap.min.js', array('jquery'), '3.3.7', true );

  wp_register_script( 'waypoints', get_template_directory_uri() .'/assets/js/jquery.counterup/waypoints.min.js', array('jquery'),false,true );
  wp_register_script( 'waypoints', get_template_directory_uri() .'/assets/js/jquery.counterup/waypoints.min.js', array('jquery'),false,true );
  wp_register_script( 'counter', get_template_directory_uri() .'/assets/js/jquery.counterup/jquery.counterup.min.js', array('waypoints'), false, true );
  wp_register_script( 'construction-script', get_template_directory_uri() .'/assets/js/script.js', array('jquery'), false, true );
  wp_enqueue_script('construction-script');
  wp_localize_script( 'construction-script', 'construction_ajax', array(
    'ajaxurl' => esc_url(admin_url( 'admin-ajax.php' 
  ) ) ) );

  
}
add_action( 'wp_enqueue_scripts', 'construction_theme_scripts_styles' );

/**

 * Creates a nicely formatted and more specific title element text

 * for output in head of document, based on current view.

 *

 * @since Twenty Twelve 1.0

 *

 * @param string $title Default title text for current view.

 * @param string $sep Optional separator.

 * @return string Filtered title.

 */

function construction_wp_title($title, $sep)
{
    global $paged, $page;
    if (is_feed()) {
        return $title;
    }
    // Add the site name.
    $title .= get_bloginfo('name');
    // Add the site description for the home/front page.
    $site_description = get_bloginfo('description', 'display');
    if ($site_description && (is_home() || is_front_page())) {
        $title = "$title $sep $site_description";
    }
    // Add a page number if necessary.
    if ($paged >= 2 || $page >= 2) {
        $title = "$title $sep " . sprintf(__('Page %s', 'construction'), max($paged, $page));
    }
    return $title;

}

add_filter('wp_title', 'construction_wp_title', 10, 2);

// get image src

function construction_get_img($img, $w, $h, $alt)
{
    
        $img = wp_get_attachment_url($img);
       
    
    return $img;

}

// hextorgb

function construction_hex2rgb($hex)
{
    $hex = str_replace("#", "", $hex);
    if (strlen($hex) == 3) {
        $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
        $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
        $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }
    $rgb = array($r, $g, $b);
    //return implode(",", $rgb); // returns the rgb values separated by commas
    return $rgb; // returns an array with the rgb values

}

//Custom Excerpt Function

function construction_excerpt($limit = 30, $more = '...')
{
    $excerpt = explode(' ', get_the_excerpt(), $limit);
    if (count($excerpt) >= $limit) {
        array_pop($excerpt);
        $excerpt = implode(" ", $excerpt) . $more;
    } else {
        $excerpt = implode(" ", $excerpt);
    }
    $excerpt = preg_replace('`[[^]]*]`', '', $excerpt);
    return $excerpt;

}

//pagination

function construction_pagination($prev = 'Prev', $next = 'Next', $pages = '')
{
    global $wp_query, $wp_rewrite, $textdomain;
    $current = $wp_query->query_vars['paged'] > 1 ? $wp_query->query_vars['paged'] : 1;
    if ($pages == '') {
        global $wp_query;
        $pages = $wp_query->max_num_pages;
        if (!$pages) {
            $pages = 1;
        }
    }
    $pagination = array(
        'base'      => str_replace(999999999, '%#%', get_pagenum_link(999999999)),
        'format'    => '',
        'current'   => $current,
        'total'     => $pages,
        'prev_text' => $prev,
        'next_text' => $next,
        'type'      => 'list',
    );
    if ($wp_rewrite->using_permalinks()) {
        $pagination['base'] = user_trailingslashit(trailingslashit(remove_query_arg('s', get_pagenum_link(1))) . 'page/%#%/', 'paged');
    }
    if (!empty($wp_query->query_vars['s'])) {
        $pagination['add_args'] = array('s' => get_query_var('s'));
    }
    $return = paginate_links($pagination);
    echo $return;

}

//Custom comment List:

function construction_theme_comment($comment, $args, $depth){
  global $comment;

  $old_depth = construction_get_option('old-depth');
  construction_update_option('old-depth', $depth );
  $url     = get_author_posts_url( $comment->user_id );
  
  ?>
  <li <?php comment_class('comment-item');?> id="comment-<?php comment_ID()?>">
   <a href="<?php echo esc_url($url);?>" class="comment-img">
      <?php
        $avatar = get_user_meta( get_the_author_meta('ID'), '_construction_user_avatar', true);
        if( ! empty( $avatar ) ){
          echo '<img src="'. $avatar .'" >';
        }else{
          echo get_avatar(get_the_author_meta( 'ID' ), 70, array('class'=>'img-responsive'));
        }
      ?>
    </a>

    <div class="auth">
      <div class="comment-heading">
         <h5><?php echo get_comment_author_link(); ?></h5>
        <span><?php echo esc_attr( get_comment_time('D m Y -g:i a') );?></span>
      </div>
    </div>
    <div class="comment-txt">
      <?php
          comment_text();
        ?>
        <span class="reply"><?php echo comment_reply_link(array('depth' => $depth, 'max_depth' => $args['max_depth'])); ?></span>
    </div>
    

<?php

}

if (function_exists('vc_add_param')) {
    $lists_animate = array(__('choose type animate', 'construction') => '', esc_html__('bounceIn', 'construction') => 'bounceIn', esc_html__('flipOutX', 'construction') => 'flipOutX', esc_html__('flipInY', 'construction') => 'flipInY', esc_html__('fadeIn', 'construction') => 'fadeIn', esc_html__('fadeInUp', 'construction') => 'fadeInUp', esc_html__('fadeInDown', 'construction') => 'fadeInDown', esc_html__('fadeInLeft', 'construction') => 'fadeInLeft', esc_html__('fadeInRight', 'construction') => 'fadeInRight', esc_html__('pulse', 'construction') => 'pulse', esc_html__('rollIn', 'construction') => 'rollIn', esc_html__('rotateIn', 'construction') => 'rotateIn');
    $animate_params = array(
      array(
        "type"        => "dropdown",
        "heading"     => esc_html__('Choose type animate','construction'),
        "param_name"  => "animate",
        "value"       => $lists_animate,
        "std"         => ''
      ),
      array(
        "type"        => "textfield",
        "heading"     => "",
        "param_name"  => "wow_duration",
        "value"       => "",
        "dependency" => array('construction'=>'animate','not_empty'=>true),
        "std"         => '0.8s',
        "description" => esc_html__("Enter time animate duration with units,ex:0.8s", 'construction')
      ),
      array(
        "type"        => "textfield",
        "heading"     => "",
        "param_name"  => "wow_delay",
        "value"       => "",
        "dependency" => array('construction'=>'animate','not_empty'=>true),
        "std"         => '0.8s',
        "description" => esc_html__("Enter time animate delay with units,ex:0.8s", 'construction')
      )
    );
    vc_add_param('vc_row', array(
        "type"        => "textfield",
        "heading"     => esc_html__('Section ID', 'construction'),
        "param_name"  => "el_id",
        "value"       => "",
        "description" => esc_html__("Set ID section", 'construction'),
    ));
    vc_add_params('vc_row', $animate_params);
    vc_add_params('vc_column', $animate_params);
    vc_add_params('vc_column_inner', $animate_params);
    vc_add_params('qk_button', $animate_params);
    vc_add_params('qk_title', $animate_params);
    vc_add_param('vc_button2', array(
        "type"        => "checkbox",
        "heading"     => esc_html__('Button style', 'construction'),
        "param_name"  => "construction_btn",
        "value"       => array(__('Yes', 'construction') => 'yes'),
        "std"         => "yes",
        "description" => esc_html__("Use default style", 'construction'),
    ));

}

function construction_get_option( $name, $default=false, $media = false ){
  global $construction_theme_options;

  $name = construction_get_prefix( $name );

  if( isset( $construction_theme_options[ $name ] ) ){
    $value =  $construction_theme_options[ $name ];
    if( $media ){
      return $value['url'];
    }
    return $value;
  }

  return $default;
}

function construction_update_option( $name, $value ){
  global $construction_theme_options;

  $name = construction_get_prefix( $name );

  $construction_theme_options[ $name ] = $value;
  return $value;
}


function construction_get_meta( $id, $name ){
  $value = get_post_meta( $id, construction_get_prefix( $name ), true );
  return $value === 'no' ? 0 : $value;
}

function construction_get_prefix( $name ){
  return "construction_$name";
}


/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.6.1 for parent theme odeo for publication on ThemeForest
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */


require_once CONSTRUCTION_ABS_FR . '/class-tgm-plugin-activation.php';

add_action('tgmpa_register', 'construction_register_required_plugins');

/**

 * Register the required plugins for this theme.

 *

 * In this example, we register two plugins - one included with the TGMPA library

 * and one from the .org repo.

 *

 * The variable passed to tgmpa_register_plugins() should be an array of plugin

 * arrays.

 *

 * This function is hooked into tgmpa_init, which is fired within the

 * TGM_Plugin_Activation class constructor.

 */

function construction_register_required_plugins()
{
    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(
        // This is an example of how to include a plugin from a private repo in your theme.
        array(
            'name'     => esc_html__('WPBakery Visual Composer','construction'), // The plugin name.
            'slug'     => 'js_composer', // The plugin slug (typically the folder name).
            'source'   => CONSTRUCTION_URI_PATH . '/framework/plugins/js_composer.zip', // The plugin source.
            'required' => true, // If false, the plugin is only 'recommended' instead of required.
        ), array(            
            'name'               => esc_html__('QK Custom Functions','construction'), // The plugin name.
            'slug'               => 'qk-functions', // The plugin slug (typically the folder name).
            'source'             => get_template_directory() . '/framework/plugins/qk-functions.zip', // The plugin source.
            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
        ),array(            
            'name'               => esc_html__('QK Register Post Type','construction'), // The plugin name.
            'slug'               => 'qk-post_type', // The plugin slug (typically the folder name).
            'source'             => get_template_directory() . '/framework/plugins/qk-post_type.zip', // The plugin source.
            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
        ),
        
        // This is an example of how to include a plugin from the WordPress Plugin Repository.
        array(
            'name'     => esc_html__('Redux Framework','construction'),
            'slug'     => 'redux-framework',
            'required' => true,
        ),array(
            'name'      => esc_html__('CMB2','construction'),
            'slug'      => 'cmb2',
            'required'  => true,
        ),
        array(
            'name'     => esc_html__('Contact Form 7','construction'),
            'slug'     => 'contact-form-7',
            'required' => true,
        ),
    );
    
    /**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */
    $config = array(
        'default_path' => '', // Default absolute path to pre-packaged plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'has_notices'  => true, // Show admin notices or not.
        'dismissable'  => true, // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '', // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false, // Automatically activate plugins after installation or not.
        'message'      => '', // Message to output right before the plugins table.
        'strings'      => array(
            'page_title'                      => esc_html__('Install Required Plugins', 'construction'),
            'menu_title'                      => esc_html__('Install Plugins', 'construction'),
            'installing'                      => esc_html__('Installing Plugin: %s', 'construction'), // %s = plugin name.
            'oops'                            => esc_html__('Something went wrong with the plugin API.', 'construction'),
            'notice_can_install_required'     => _n_noop('This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'construction'), // %1$s = plugin name(s).
            'notice_can_install_recommended'  => _n_noop('This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'construction'), // %1$s = plugin name(s).
            'notice_cannot_install'           => _n_noop('Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'construction'), // %1$s = plugin name(s).
            'notice_can_activate_required'    => _n_noop('The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'construction'), // %1$s = plugin name(s).
            'notice_can_activate_recommended' => _n_noop('The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'construction'), // %1$s = plugin name(s).
            'notice_cannot_activate'          => _n_noop('Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'construction'), // %1$s = plugin name(s).
            'notice_ask_to_update'            => _n_noop('The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'construction'), // %1$s = plugin name(s).
            'notice_cannot_update'            => _n_noop('Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'construction'), // %1$s = plugin name(s).
            'install_link'                    => _n_noop('Begin installing plugin', 'Begin installing plugins', 'construction'),
            'activate_link'                   => _n_noop('Begin activating plugin', 'Begin activating plugins', 'construction'),
            'return'                          => esc_html__('Return to Required Plugins Installer', 'construction'),
            'plugin_activated'                => esc_html__('Plugin activated successfully.', 'construction'),
            'complete'                        => esc_html__('All plugins installed and activated successfully. %s', 'construction'), // %s = dashboard link.
            'nag_type'                        => 'updated', // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
        ),
    );
    tgmpa($plugins, $config);

}

?>