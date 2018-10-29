<?php
/**
 * Filters for StructurePress WP theme
 *
 * @package StructurePress
 */

class StructurePressFilters {

	function __construct() {
		// Add shortcodes in widgets
		add_filter( 'widget_text', 'do_shortcode' );

		// ProteusWidgets
		add_filter( 'pw/widget_views_path', array( $this, 'set_widgets_view_path' ) );
		add_filter( 'pw/testimonial_widget', array( $this, 'set_testimonial_settings' ) );
		add_filter( 'pw/featured_page_widget_page_box_image_size', array( $this, 'set_page_box_image_size' ) );
		add_filter( 'pw/featured_page_widget_inline_image_size', array( $this, 'set_inline_image_size' ) );
		add_filter( 'pw/featured_page_excerpt_lengths', array( $this, 'featured_page_excerpt_lengths' ) );
		add_filter( 'pw/social_icons_fa_icons_list', array( $this, 'social_icons_fa_icons_list' ) );
		add_filter( 'pw/default_social_icon', array( $this, 'default_social_icon' ) );
		add_filter( 'pw/theme_prefix', array( $this, 'theme_prefix' ) );

		// Custom text after excerpt
		add_filter( 'excerpt_more', array( $this, 'excerpt_more' ) );

		// Footer widgets with dynamic layouts
		add_filter( 'dynamic_sidebar_params', array( $this, 'footer_widgets_params' ), 9, 1 );

		// Google fonts
		add_filter( 'structurepress_pre_google_web_fonts', array( $this, 'additional_fonts' ) );
		add_filter( 'structurepress_subsets_google_web_fonts', array( $this, 'subsets_google_web_fonts' ) );

		// Page builder
		add_filter( 'siteorigin_panels_settings_defaults', array( $this, 'siteorigin_panels_settings_defaults' ) );
		add_filter( 'siteorigin_panels_widgets', array( $this, 'add_icons_to_page_builder_for_pw_widgets' ), 15 );
		add_filter( 'siteorigin_panels_widget_dialog_tabs', array( $this, 'siteorigin_panels_add_widgets_dialog_tabs' ), 15 );

		// SiteOrigin custom widgets
		add_filter( 'siteorigin_widgets_widget_folders', array( $this, 'siteorigin_widgets_widget_folders' ) );
		add_filter( 'siteorigin_widgets_template_file_pw_open_position', array( $this, 'siteorigin_widgets_template_file_pw_open_position' ) );
		add_filter( 'admin_init', array( $this, 'siteorigin_widgets_active_widgets' ) );

		// Embeds
		add_filter( 'embed_oembed_html', array( $this, 'embed_oembed_html' ), 10, 1 );

		// Protocols
		add_filter( 'kses_allowed_protocols', array( $this, 'kses_allowed_protocols' ) );

		// Filter the text in the footer
		foreach ( array( 'structurepress_footer_left_txt', 'structurepress_footer_center_txt', 'structurepress_footer_right_txt' ) as $structurepress_filter ) {
			add_filter( $structurepress_filter, 'wptexturize' );
			add_filter( $structurepress_filter, 'capital_P_dangit' );
		}

		// One Click Demo Import plugin
		add_filter( 'pt-ocdi/import_files', array( $this, 'ocdi_import_files' ) );
		add_action( 'pt-ocdi/after_import', array( $this, 'ocdi_after_import_setup' ) );
		add_filter( 'pt-ocdi/message_after_file_fetching_error', array( $this, 'ocdi_message_after_file_fetching_error' ) );

		// Remove references to SiteOrigin Premium.
		add_filter( 'siteorigin_premium_upgrade_teaser', '__return_false' );
	}


	/**
	* Filter the Testimonial widget fields that the Structurepress theme will need from ProteusWidgets - Tesimonial widget
	*/
	function set_testimonial_settings( $attr ) {
		$attr['number_of_testimonial_per_slide'] = 1;
		$attr['rating']                          = false;
		$attr['author_description']              = true;
		$attr['author_avatar']                   = true;
		$attr['bootstrap_version']               = 4;
		return $attr;
	}


	/**
	 * Custom text after excerpt
	 */
	function excerpt_more( $more ) {
		return _x( ' &hellip;', 'custom read more text after the post excerpts' , 'structurepress-pt' );
	}


	/**
	 * Filter the dynamic sidebars and alter the BS col classes for the footer wigets
	 * @param  array $params
	 * @return array
	 */
	function footer_widgets_params( $params ) {
		static $counter              = 0;
		static $first_row            = true;
		$footer_widgets_layout_array = StructurePressHelpers::footer_widgets_layout_array();

		if ( 'footer-widgets' === $params[0]['id'] ) {
			// 'before_widget' contains __col-num__, see inc/theme-sidebars.php
			$params[0]['before_widget'] = str_replace( '__col-num__', $footer_widgets_layout_array[ $counter ], $params[0]['before_widget'] );

			// first widget in the any non-first row
			if ( false === $first_row && 0 === $counter ) {
				$params[0]['before_widget'] = '</div><div class="row">' . $params[0]['before_widget'];
			}

			$counter++;
		}

		end( $footer_widgets_layout_array );
		if ( $counter > key( $footer_widgets_layout_array ) ) {
			$counter   = 0;
			$first_row = false;
		}

		return $params;
	}


	/**
	 * Filter setting ProteusWidgets mustache widget views path for StructurePress
	 */
	function set_widgets_view_path() {
		return get_template_directory() . '/inc/widgets-views';
	}


	/**
	* Filter the Featured page widget pw-page-box image size for StructurePress (ProteusWidgets)
	*/
	function set_page_box_image_size( $image ) {
		$image['width']  = 360;
		$image['height'] = 240;
		return $image;
	}


	/**
	* Filter the Featured page widget pw-page-box image size for StructurePress (ProteusWidgets)
	*/
	function default_social_icon( $image ) {
		return 'fa-linkedin-square';
	}


	/**
	* Filter the Featured page widget pw-inline image size for StructurePress (ProteusWidgets)
	*/
	function set_inline_image_size( $image ) {
		$image['width']  = 100;
		$image['height'] = 70;
		return $image;
	}


	/**
	* Filter the Featured page widget pw-inline image size for StructurePress (ProteusWidgets)
	*/
	function featured_page_excerpt_lengths( $excerpt_lengths ) {
		$excerpt_lengths['inline_excerpt'] = 55;
		$excerpt_lengths['block_excerpt']  = 200;
		return $excerpt_lengths;
	}


	/**
	* Filter for the list of Font-Awesome icons in social icons widget in StructurePress (ProteusWidgets)
	*/
	function social_icons_fa_icons_list() {
		return array(
			'fa-facebook-square',
			'fa-twitter-square',
			'fa-youtube-square',
			'fa-google-plus-square',
			'fa-pinterest-square',
			'fa-tumblr-square',
			'fa-xing-square',
			'fa-vimeo-square',
			'fa-linkedin-square',
			'fa-rss-square',
			'fa-github-square',
			'fa-bitbucket-square',
		);
	}

	/**
	 * Return Google fonts and sizes
	 *
	 * @see https://github.com/grappler/wp-standard-handles/blob/master/functions.php
	 * @return array Google fonts and sizes.
	 */
	function additional_fonts( $fonts ) {

		/* translators: If there are characters in your language that are not supported by Roboto or Open Sans, translate this to 'off'. Do not translate into your own language. */
		if ( 'off' !== _x( 'on', 'Roboto or Open Sans font: on or off', 'structurepress-pt' ) ) {
			$fonts['Roboto'] = array(
				'700' => '700',
			);
			$fonts['Open Sans'] = array(
				'400' => '400',
				'700' => '700',
			);
		}

		return $fonts;
	}


	/**
	 * Add subsets from customizer, if needed.
	 *
	 * @return array
	 */
	function subsets_google_web_fonts( $subsets ) {
		$additional_subset = get_theme_mod( 'charset_setting', 'latin' );

		array_push( $subsets, $additional_subset );

		return $subsets;
	}


	/**
	 * Embedded videos and video container around them
	 */
	function embed_oembed_html( $html ) {
		if (
			false !== strstr( $html, 'youtube.com' ) ||
			false !== strstr( $html, 'wordpress.tv' ) ||
			false !== strstr( $html, 'wordpress.com' ) ||
			false !== strstr( $html, 'vimeo.com' )
		) {
			$out = '<div class="embed-responsive  embed-responsive-16by9">' . $html . '</div>';
		} else {
			$out = $html;
		}
		return $out;
	}


	/**
	 * Add more allowed protocols
	 *
	 * @link https://developer.wordpress.org/reference/functions/wp_allowed_protocols/
	 */
	static function kses_allowed_protocols( $protocols ) {
		return array_merge( $protocols, array( 'skype' ) );
	}


	/**
	 * Change the default settings for SO
	 * @param  array $settings
	 * @return array
	 */
	function siteorigin_panels_settings_defaults( $settings ) {
		$settings['title-html'] = '<h3 class="widget-title"><span class="widget-title__inline">{{title}}</span></h3>';
		$settings['full-width-container'] = '.boxed-container';
		$settings['mobile-width'] = '991';

		return $settings;
	}


	/**
	* Set SiteOrigin custom widgets folder
	*/
	function siteorigin_widgets_widget_folders( $folders ){
		$folders[] = get_template_directory() . '/inc/so-widgets/';
		return $folders;
	}


	/**
	* Set SiteOrigin template for Open Position widget
	*/
	function siteorigin_widgets_template_file_pw_open_position( $template_file ){
		return get_template_directory() . '/inc/so-widgets/widget-open-position/tpl/open-position-template.php';
	}


	/**
	* Activate SiteOrigin custom widgets
	*/
	function siteorigin_widgets_active_widgets(){
		if ( ! get_theme_mod( 'so_custom_widgets_activated' ) && class_exists( 'SiteOrigin_Widgets_Bundle' ) ) {
			SiteOrigin_Widgets_Bundle::single()->activate_widget( 'widget-open-position' );
			set_theme_mod( 'so_custom_widgets_activated', true );
		}
	}


	/**
	* Theme prefix for Proteus Widgets
	*/
	function theme_prefix(){
		return 'structurepress-';
	}


	/**
	 * Define demo import files for One Click Demo Import plugin.
	 */
	function ocdi_import_files() {
		return array(
			array(
				'import_file_name'       => 'StructurePress Import',
				'import_file_url'        => 'http://artifacts.proteusthemes.com/xml-exports/structurepress-latest.xml',
				'import_widget_file_url' => 'http://artifacts.proteusthemes.com/json-widgets/structurepress.json'
			),
		);
	}


	/**
	 * After import theme setup for One Click Demo Import plugin.
	 */
	function ocdi_after_import_setup() {

		// Menus to Import and assign - you can remove or add as many as you want
		$top_menu  = get_term_by( 'name', 'Top Menu', 'nav_menu' );
		$main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );

		set_theme_mod( 'nav_menu_locations', array(
				'top-bar-menu' => $top_menu->term_id,
				'main-menu'    => $main_menu->term_id,
			)
		);

		// Set options for front page and blog page
		$front_page_id = get_page_by_title( 'Home' );
		$blog_page_id  = get_page_by_title( 'News' );

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page_id->ID );
		update_option( 'page_for_posts', $blog_page_id->ID );

		// Set options for Breadcrumbs NavXT
		$breadcrumbs_settings = get_option( 'bcn_options', array() );
		$breadcrumbs_settings['hseparator'] = '';
		$shop_page = get_page_by_title( 'Shop' );
		if ( ! is_null( $shop_page ) ) {
			$breadcrumbs_settings['apost_product_root'] = $shop_page->ID;
		}
		$breadcrumbs_settings['bpost_product_archive_display'] = false;
		$projects_page = get_page_by_title( 'Projects' );
		if ( ! is_null( $projects_page ) ) {
			$breadcrumbs_settings['apost_portfolio_root'] = $projects_page->ID;
		}
		$breadcrumbs_settings['bpost_portfolio_archive_display'] = false;
		$breadcrumbs_settings['bpost_portfolio_taxonomy_display'] = false;
		update_option( 'bcn_options', $breadcrumbs_settings );

		// Set logo in customizer
		set_theme_mod( 'logo_img', get_template_directory_uri() . '/assets/images/logo.png' );
		set_theme_mod( 'logo2x_img', get_template_directory_uri() . '/assets/images/logo@2x.png' );

		// Set regular page title area background
		set_theme_mod( 'page_header_bg_img', get_template_directory_uri() . '/assets/images/subpage_bg.jpg' );

		_e( 'After import setup ended!', 'structurepress-pt' );
	}


	/**
	 * Message for manual demo import for One Click Demo Import plugin.
	 */
	function ocdi_message_after_file_fetching_error() {
		return sprintf( __( 'Please try to manually import the demo data. Here are instructions on how to do that: %sDocumentation: Import XML File%s', 'structurepress-pt' ), '<a href="https://www.proteusthemes.com/docs/structurepress-pt/#import-xml-file" target="_blank">', '</a>' );
	}


	/**
	 * Add PW widgets to Page Builder group and add icon class.
	 *
	 * @param array $widgets All widgets in page builder list of widgets.
	 *
	 * @return array
	 */
	function add_icons_to_page_builder_for_pw_widgets( $widgets ) {
		foreach ( $widgets as $class => $widget ) {
			if ( strstr( $widget['title'], 'ProteusThemes:' ) ) {
				$widgets[ $class ]['icon']   = 'pw-pb-widget-icon';
				$widgets[ $class ]['groups'] = array( 'pw-widgets' );
			}
		}

		return $widgets;
	}


	/**
	 * Add another tab section in the Page Builder "add new widget" dialog.
	 *
	 * @param array $tabs Existing tabs.
	 *
	 * @return array
	 */
	function siteorigin_panels_add_widgets_dialog_tabs( $tabs ) {
		$tabs['pw_widgets'] = array(
			'title' => esc_html__( 'ProteusThemes Widgets', 'structurepress-pt' ),
			'filter' => array(
				'groups' => array( 'pw-widgets' ),
			),
		);

		return $tabs;
	}
}

// Single instance
$structurepress_filters = new StructurePressFilters();
