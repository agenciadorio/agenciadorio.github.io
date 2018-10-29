<?php
/*
Plugin Name: WP News and Scrolling Widgets
Plugin URL: https://www.wponlinesupport.com/plugins/
Text Domain: sp-news-and-widget
Domain Path: /languages/
Description: A simple News and three widgets(static, scrolling and with thumbs) plugin
Version: 4.0.3
Author: WP Online Support
Author URI: https://www.wponlinesupport.com
Contributors: WP Online Support
*/

if( !defined( 'WPNW_VERSION' ) ) {
	define( 'WPNW_VERSION', '4.0.3' ); // Version of plugin
}
if( !defined( 'WPNW_DIR' ) ) {
	define( 'WPNW_DIR', dirname( __FILE__ ) ); // Plugin dir
}
if( !defined( 'WPNW_POST_TYPE' ) ) {
	define( 'WPNW_POST_TYPE', 'news' ); // Plugin post type
}
if( !defined( 'WPNW_CAT' ) ) {
	define( 'WPNW_CAT', 'news-category' ); // Plugin Category
}

register_activation_hook( __FILE__, 'install_newsfree_version' );
function install_newsfree_version() {
	if( is_plugin_active('wp-news-and-widget-pro/sp-news-and-widget.php') ){
		 add_action('update_option_active_plugins', 'deactivate_newsfree_version');
	}
}

function deactivate_newsfree_version() {
   deactivate_plugins('wp-news-and-widget-pro/sp-news-and-widget.php',true);
}

function freenews_admin_notice() {

	global $pagenow;

	$dir 				= WP_PLUGIN_DIR . '/wp-news-and-widget-pro/sp-news-and-widget.php';
	$notice_link        = add_query_arg( array('message' => 'wpnw-plugin-notice'), admin_url('plugins.php') );
	$notice_transient   = get_transient( 'wpnw_install_notice' );

	if( $notice_transient == false && $pagenow == 'plugins.php' && file_exists($dir) && current_user_can( 'install_plugins' ) ) {
		echo '<div class="updated notice" style="position:relative;">
			<p>
				<strong>'.sprintf( __('Thank you for activating %s', 'sp-news-and-widget'), 'WP News and three widgets').'</strong>.<br/>
				'.sprintf( __('It looks like you had PRO version %s of this plugin activated. To avoid conflicts the extra version has been deactivated and we recommend you delete it.', 'sp-news-and-widget'), '<strong>(<em>WP News and three widgets PRO</em>)</strong>' ).'
			</p>
			<a href="'.esc_url( $notice_link ).'" class="notice-dismiss" style="text-decoration:none;"></a>
		</div>';
	}
}
add_action( 'admin_notices', 'freenews_admin_notice');

function sp_news_load_textdomain() {
	load_plugin_textdomain( 'sp-news-and-widget', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}
add_action('plugins_loaded', 'sp_news_load_textdomain');

function wpnawstyle_css_script() {
	wp_enqueue_style( 'cssnews',  plugin_dir_url( __FILE__ ). 'assets/css/stylenews.css', array(), WPNW_VERSION );
	wp_enqueue_script( 'vnewsticker', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.newstape.js', array( 'jquery' ), WPNW_VERSION);
	wp_enqueue_script( 'sp-news-public', plugin_dir_url( __FILE__ ) . 'assets/js/sp-news-public.js', array( 'jquery' ), WPNW_VERSION);
}
add_action( 'wp_enqueue_scripts', 'wpnawstyle_css_script' );

function string_limit_newswords( $post_id = null, $content = '', $word_length = '55', $more = '...' ) {

	$has_excerpt  = false;
	$word_length    = !empty($word_length) ? $word_length : '55';

	// If post id is passed
	if( !empty($post_id) ) {
		if (has_excerpt($post_id)) {
			$has_excerpt    = true;
			$content        = get_the_excerpt();
		} else {
			$content = !empty($content) ? $content : get_the_content();
		}
	}

	if( !empty($content) && (!$has_excerpt) ) {
		$content = strip_shortcodes( $content ); // Strip shortcodes
		$content = wp_trim_words( $content, $word_length, $more );
	}

	return $content;
}

function spnews_display_tags( $query ) {
	if( is_tag() && $query->is_main_query() ) {       
	   $post_types = array( 'post', WPNW_POST_TYPE );
		$query->set( 'post_type', $post_types );
	}
}
add_filter( 'pre_get_posts', 'spnews_display_tags' );

function news_pagination($args = array()){    
	$big = 999999999; // need an unlikely integer
	$paging = apply_filters('news_blog_paging_args', array(
					'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					'format'    => '?paged=%#%',
					'current'   => max( 1, $args['paged'] ),
					'total'     => $args['total'],
					'prev_next' => true,
					'prev_text' => __('« Previous', 'wp-blog-and-widgets'),
					'next_text' => __('Next »', 'wp-blog-and-widgets'),
				));
	
	echo paginate_links($paging);
}

function news_get_unique() {
  static $unique = 0;
  $unique++;

  return $unique;
}

// Posttype
require_once( WPNW_DIR . '/includes/sp-news-post-type.php' );

// Shortcode
require_once( WPNW_DIR . '/includes/sp-news-shortcode.php' );

// Widget
require_once( WPNW_DIR . '/widget_function.php' );

// Shortcode
require_once( WPNW_DIR . '/admin/class-wpnw-admin.php' );

// How it work file, Load admin files
if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
	require_once( WPNW_DIR . '/admin/wpnw-how-it-work.php' );
}

/* Plugin Analytics Data */
function wpos_analytics_anl20_load() {

	require_once dirname( __FILE__ ) . '/wpos-analytics/wpos-analytics.php';

	$wpos_analytics =  wpos_anylc_init_module( array(
							'id'            => 20,
							'file'          => plugin_basename( __FILE__ ),
							'name'          => 'WP News and Scrolling Widgets',
							'slug'          => 'wp-news-and-scrolling-widgets',
							'type'          => 'plugin',
							'menu'          => 'edit.php?post_type=news',
							'text_domain'   => 'sp-news-and-widget',
							'promotion'     => array(
													'bundle' => array(
														'name'  => 'Download FREE 50 Plugins, 10+ Themes and Dashboard Plugin',
														'desc'  => 'Download FREE 50 Plugins, 10+ Themes and Dashboard Plugin',
														'file'  => 'https://www.wponlinesupport.com/latest/wpos-free-50-plugins-plus-12-themes.zip'
													)
												),
							'offers'        => array(
													'trial_premium' => array(
															1 => array(
																'image' => 'http://analytics.wponlinesupport.com/?anylc_img=20',
															),
													),
												),
						));

	return $wpos_analytics;
}

// Init Analytics
wpos_analytics_anl20_load();
/* Plugin Analytics Data Ends */