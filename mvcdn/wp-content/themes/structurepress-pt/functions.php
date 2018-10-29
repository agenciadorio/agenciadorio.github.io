<?php
/**
 * StructurePress functions and definitions
 *
 * @author Marko Prelec <marko.prelec@proteusnet.com>
 * @author Gregor Capuder <gregor.capuder@proteusnet.com>
 * @author Primoz Cigler <primoz@proteusnet.com>
 */

// Display informative message if PHP version is less than 5.3.2
if ( version_compare( phpversion(), '5.3.2', '<' ) ) {
	die( sprintf( esc_html__( 'This theme requires %2$sPHP 5.3.2+%3$s to run. Please contact your hosting company and ask them to update the PHP version of your site to at least PHP 5.3.2.%4$s Your current version of PHP: %2$s%1$s%3$s', 'structurepress-pt' ), phpversion(), '<strong>', '</strong>', '<br>' ) );
}


// Composer autoloader
require_once trailingslashit( get_template_directory() ) . 'vendor/autoload.php';


/**
 * Define the version variable to assign it to all the assets (css and js)
 */
define( 'STRUCTUREPRESS_WP_VERSION', wp_get_theme()->get( 'Version' ) );


/**
 * Define the development constant
 */
if ( ! defined( 'STRUCTUREPRESS_DEVELOPMENT' ) ) {
	define( 'STRUCTUREPRESS_DEVELOPMENT', false );
}


/**
 * Helper functions used in the theme
 */
require_once get_template_directory() . '/inc/helpers.php';


/**
 * Advanced Custom Fields calls to require the plugin within the theme
 */
StructurePressHelpers::load_file( '/inc/acf.php' );


/**
 * Theme support and thumbnail sizes
 */
if ( ! function_exists( 'structurepress_theme_setup' ) ) {
	function structurepress_theme_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on StructurePress, use a find and replace
		 * to change 'structurepress-pt' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'structurepress-pt', get_template_directory() . '/languages' );

		/**
		 * Loads separate textdomain for the proteuswidgets which are included with composer.
		 */
		load_theme_textdomain( 'proteuswidgets', get_template_directory() . '/languages/proteuswidgets' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// WooCommerce basic support
		add_theme_support( 'woocommerce' );

		/**
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/**
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'structurepress-jumbotron-slider-l', 1920, 660, true );
		add_image_size( 'structurepress-jumbotron-slider-m', 960, 330, true );
		add_image_size( 'structurepress-jumbotron-slider-s', 480, 165, true );
		add_image_size( 'portfolio-gallery', 540, 540 );
		// set_post_thumbnail_size( 600, 400, true );

		// Menus
		add_theme_support( 'menus' );
		register_nav_menu( 'main-menu', _x( 'Main Menu', 'backend', 'structurepress-pt' ) );
		register_nav_menu( 'top-bar-menu', _x( 'Top Bar Menu', 'backend', 'structurepress-pt' ) );

		/**
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// add excerpt support for pages
		add_post_type_support( 'page', 'excerpt' );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'structurepress_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );
	}
	add_action( 'after_setup_theme', 'structurepress_theme_setup' );
}


/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @see https://codex.wordpress.org/Content_Width
 */
if ( ! isset( $content_width ) ) {
	$content_width = 1140; /* pixels */
}


/**
 * Enqueue CSS stylesheets
 */
if ( ! function_exists( 'structurepress_enqueue_styles' ) ) {
	function structurepress_enqueue_styles() {
		wp_enqueue_style( 'structurepress-main', get_stylesheet_uri(), array(), STRUCTUREPRESS_WP_VERSION );

		// custom WooCommerce CSS (enqueue it only if the WooCommerce plugin is active)
		if ( StructurePressHelpers::is_woocommerce_active() ) {
			wp_enqueue_style( 'structurepress-woocommerce', get_template_directory_uri() . '/woocommerce.css' , array( 'structurepress-main' ) , STRUCTUREPRESS_WP_VERSION );
		}
	}
	add_action( 'wp_enqueue_scripts', 'structurepress_enqueue_styles' );
}


/**
 * Enqueue Google Web Fonts.
 */
if ( ! function_exists( 'structurepress_enqueue_google_web_fonts' ) ) {
	function structurepress_enqueue_google_web_fonts() {
		wp_enqueue_style( 'structurepress-google-fonts', StructurePressHelpers::google_web_fonts_url(), array(), null );
	}
	add_action( 'wp_enqueue_scripts', 'structurepress_enqueue_google_web_fonts' );
}


/**
 * Enqueue JS scripts
 */
if ( ! function_exists( 'structurepress_enqueue_scripts' ) ) {
	function structurepress_enqueue_scripts() {
		// modernizr for the frontend feature detection
		wp_enqueue_script( 'structurepress-modernizr', get_template_directory_uri() . '/assets/js/modernizr.custom.20160712.js', array(), null );

		// picturefill for the support of the <picture> element today
		wp_enqueue_script( 'structurepress-picturefill', get_template_directory_uri() . '/bower_components/picturefill/dist/picturefill.min.js', array( 'structurepress-modernizr' ), '2.2.1' );

		// requirejs
		wp_register_script( 'structurepress-requirejs', get_template_directory_uri() . '/bower_components/requirejs/require.js', array(), null, true );

		// main JS file, conditionally
		if ( true === STRUCTUREPRESS_DEVELOPMENT ) {
			wp_enqueue_script( 'structurepress-main', get_template_directory_uri() . '/assets/js/main.js', array(
				'jquery',
				'underscore',
				'structurepress-requirejs',
			), STRUCTUREPRESS_WP_VERSION, true );
		}
		else {
			wp_enqueue_script( 'structurepress-main', get_template_directory_uri() . '/assets/js/main.min.js', array(
				'jquery',
				'underscore',
			), STRUCTUREPRESS_WP_VERSION, true );
		}

		// Pass data to the main script
		wp_localize_script( 'structurepress-main', 'StructurePressVars', array(
			'pathToTheme'  => get_template_directory_uri(),
		) );

		// for nested comments
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
	add_action( 'wp_enqueue_scripts', 'structurepress_enqueue_scripts' );
}


/**
 * Register admin JS scripts
 */
if ( ! function_exists( 'structurepress_admin_enqueue_scripts' ) ) {
	function structurepress_admin_enqueue_scripts() {
		// mustache for ProteusWidgets
		wp_register_script( 'structurepress-mustache.js', get_template_directory_uri() . '/bower_components/mustache/mustache.min.js' );

		// enqueue admin utils js
		wp_enqueue_script( 'structurepress-admin-utils', get_template_directory_uri() . '/assets/admin/js/admin.js', array( 'jquery', 'underscore', 'backbone', 'structurepress-mustache.js' ) );

		// register fa CSS
		wp_register_style( 'structurepress-font-awesome', get_template_directory_uri() . '/bower_components/font-awesome/css/font-awesome.min.css', array(), '4.4.0' );

		// enqueue CSS for admin area
		wp_enqueue_style( 'structurepress-admin-css', get_template_directory_uri() . '/assets/admin/css/admin.css' );

	}
	add_action( 'admin_enqueue_scripts', 'structurepress_admin_enqueue_scripts' );
}


/**
 * Require the files in the folder /inc/
 */
$structurepress_files_to_require = array(
	'theme-widgets',
	'theme-vc-include',
	'theme-sidebars',
	'filters',
	'compat',
	'theme-customizer',
	'woocommerce',
);

// Conditionally require the includes files, based if they exist in the child theme or not
foreach ( $structurepress_files_to_require as $file ) {
	StructurePressHelpers::load_file( sprintf( '/inc/%s.php', $file ) );
}


/**
 * WIA-ARIA nav walker and accompanying JS file
 */

if ( ! function_exists( 'structurepress_wai_aria_js' ) ) {
	function structurepress_wai_aria_js() {
		wp_enqueue_script( 'structurepress-wp-wai-aria', get_template_directory_uri() . '/vendor/proteusthemes/wai-aria-walker-nav-menu/wai-aria.js', array( 'jquery' ), null, true );
	}
	add_action( 'wp_enqueue_scripts', 'structurepress_wai_aria_js' );
}


/**
 * Require some files only when in admin
 */
if ( is_admin() ) {
	// other files
	$structurepress_admin_files_to_require = array(
		// custom code
		'tgm-plugin-activation',
		'documentation-link',
	);

	foreach ( $structurepress_admin_files_to_require as $file ) {
		StructurePressHelpers::load_file( sprintf( '/inc/%s.php', $file ) );
	}
}

add_filter( 'wp_image_editors', 'change_graphic_lib' );

function change_graphic_lib($array) {
return array( 'WP_Image_Editor_GD', 'WP_Image_Editor_Imagick' );
}
