<?php

//Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) )
	$content_width = 1170; /* pixels */

/*-----------------------------------------------------------------------------------*/
/*  SETUP THEME
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'finance_setup' ) ) :

	function finance_setup() {
		// several theme support
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form' ) );	
		add_theme_support( 'html5', array( 'gallery', 'caption' ) );
		load_theme_textdomain( 'finance', get_template_directory() .'/languages' );
		add_theme_support( "title-tag" );

}
endif;
add_action( 'after_setup_theme', 'finance_setup' );

function finance_thumbnail_setup() {
add_image_size( 'finance-service-loop', 360, 180, true );
add_image_size( 'finance-project-loop', 320, 210, true );
add_image_size( 'finance-team-project', 350, 350, true );
}

add_action('after_setup_theme', 'finance_thumbnail_setup');

/*-----------------------------------------------------------------------------------*/
/*  ACF
/*-----------------------------------------------------------------------------------*/

//add_filter('acf/settings/show_admin', '__return_false');

/*-----------------------------------------------------------------------------------*/
/*  SCRIPTS & STYLES
/*-----------------------------------------------------------------------------------*/

function finance_scripts() {

//All necessary CSS
wp_enqueue_style( 'bootstrap', get_template_directory_uri() .'/css/bootstrap.min.css', array(), null );
wp_enqueue_style( 'finance-plugin-css', get_template_directory_uri() .'/css/plugin.css', array(), null );
wp_enqueue_style( 'finance-responsive-css', get_template_directory_uri() .'/css/responsive.css', array(), null );
wp_enqueue_style( 'finance-style', get_stylesheet_uri(), array( 'bootstrap','finance-plugin-css' ) );
wp_enqueue_style( 'finance-font', get_template_directory_uri() .'/css/font.css', array(), null );

//All Necessary Script

wp_enqueue_script( 'finance-plugins', get_template_directory_uri(). '/js/plugin.js', array( 'jquery' ), '', true );
wp_enqueue_script( 'finance-main-js', get_template_directory_uri(). '/js/main.js', array( 'jquery' ), '', true );
}

add_action( 'wp_enqueue_scripts', 'finance_scripts' );

/*admin style*/
function finance_admin_style() {
	wp_enqueue_style( 'finance-admin-css', get_template_directory_uri() .'/css/admin.css', array(), null );
}
add_action('admin_head', 'finance_admin_style');

/*comment*/
function finance_comment_reply(){
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
        
}
add_action( 'wp_enqueue_scripts', 'finance_comment_reply' );

/*disable text editor*/

add_action( 'init', 'finance_my_remove_post_type_support', 10 );
function finance_my_remove_post_type_support() {
	if( is_page_template('template/home-template.php') ) {
    	remove_post_type_support( 'page', 'editor' );
	}
}

/*-----------------------------------------------------------------------------------*/
/*  CALL FRAMEWORK
/*-----------------------------------------------------------------------------------*/

require_once( get_template_directory() . '/inc/option/panel/config.php' );

/*-----------------------------------------------------------------------------------*/
/*  MENU
/*-----------------------------------------------------------------------------------*/

//Register Menus

add_action( 'after_setup_theme', 'finance_register_my_menu' );
function finance_register_my_menu() {
	register_nav_menu( 'header-menu', esc_html__( 'Header Menu', 'finance' ) );
}

//TOP MENU
function finance_top_nav_menu(){
  wp_nav_menu( array(
	'theme_location' => 'header-menu',
	'container'       => 'ul',
   'menu_class'      => 'sm menus',
	'fallback_cb'  => 'finance_header_menu_cb'
  ));
}

function finance_top_mobile_menu(){
  wp_nav_menu( array(
	'theme_location' => 'header-menu',
	'container'       => 'ul',
   'menu_class'      => 'menus-mobile',
	'fallback_cb'  => 'finance_header_menu_mobile_cb'
  ));
}

// FALLBACK IF PRIMARY MENU HAVEN'T SET YET
function finance_header_menu_cb() {
  echo '<ul id="menu-top-menu" class="menus">';
  wp_list_pages('title_li=');
  echo '</ul>';
}

function finance_header_menu_mobile_cb() {
  echo '<ul id="menu-top-mobile" class="menus-mobile">';
  wp_list_pages('title_li=');
  echo '</ul>';
}

/*-----------------------------------------------------------------------------------*/
/*  HEADER
/*-----------------------------------------------------------------------------------*/

// logo text or image huh?
function finance_logo_type(){

$options = get_option('finance_framework');
$logo = '';
if (isset($options['logo_upload'])) {
$logo = $options['logo_upload'];
$finance_upload_logo = $logo['url'];
}


if ( ! empty( $finance_upload_logo ) ) { ?>

	<div class="logo-image">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( $finance_upload_logo ); ?>" class="image-logo" alt="<?php esc_html_e( 'logo', 'finance' ); ?>" /></a>
	</div>
	
	<?php } else { ?> 
	
	<div class="logo-title">
		<h2 class="site-title">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
		</h2>
	</div>

<?php }
} 


/*-----------------------------------------------------------------------------------*/
/*  WIDGET
/*-----------------------------------------------------------------------------------*/


// SETUP DEFAULT SIDEBAR
function finance_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Primary Sidebar', 'finance' ),
		'id'            => 'primary-sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="heading-block"><h4>',
		'after_title'   => '</h4></div>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Service Sidebar', 'finance' ),
		'id'            => 'service-sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="heading-block"><h4>',
		'after_title'   => '</h4></div>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Project Sidebar', 'finance' ),
		'id'            => 'project-sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="heading-block"><h4>',
		'after_title'   => '</h4></div>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Team Sidebar', 'finance' ),
		'id'            => 'team-sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="heading-block"><h4>',
		'after_title'   => '</h4></div>',
	) );
}
add_action( 'widgets_init', 'finance_widgets_init' );

require_once( get_template_directory() . '/inc/widget/finance-latestpost-widget.php' );
require_once( get_template_directory() . '/inc/widget/finance-socialbox-widget.php' );

/*-----------------------------------------------------------------------------------*/
/*  PAGINATION
/*-----------------------------------------------------------------------------------*/

function finance_pagination($pages = '', $range = 2)
{  
		 $showitems = ($range * 2)+1;  

		 global $paged;
		 if(empty($paged)) $paged = 1;

		 if($pages == '')
		 {
				 global $wp_query;
				 $pages = $wp_query->max_num_pages;
				 if(!$pages)
				 {
						 $pages = 1;
				 }
		 }   

		 if(1 != $pages)
		 {
				 echo "<div class='pagination col-md-12 text-center'>";
				 if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a>";

				 for ($i=1; $i <= $pages; $i++)
				 {
						 if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
						 {
								 echo ($paged == $i)? "<span class='current'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
						 }
				 }

				 if ($paged < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a>";
				 echo "</div>\n";
		 }
}

/*-----------------------------------------------------------------------------------*/
/*  CUSTOM FUNCTIONS
/*-----------------------------------------------------------------------------------*/
require_once( get_template_directory() . '/inc/function/custom.php' );
require_once( get_template_directory() . '/inc/function/navigation.php' );
require_once( get_template_directory() . '/inc/function/aq_resizer.php' );
require_once( get_template_directory() . '/inc/function/comment.php' );
require_once( get_template_directory() . '/inc/function/akmanda-customizer.php' );
require_once( get_template_directory() . '/inc/function/meta-box.php' );
require_once( get_template_directory() . '/inc/function/thefooter.php' );

// INSTALL NECESSARY PLUGINS
require_once( get_template_directory() . '/class-tgm.php' ); /*activate plugin function*/