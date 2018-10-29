<?php

use ProteusThemes\CustomizerUtils\Setting;
use ProteusThemes\CustomizerUtils\Control;

/**
 * Contains methods for customizing the theme customization screen.
 *
 * @package StructurePress
 * @link http://codex.wordpress.org/Theme_Customization_API
 */

class StructurePress_Customizer_Base {
	/**
	 * The singleton manager instance
	 *
	 * @see wp-includes/class-wp-customize-manager.php
	 * @var WP_Customize_Manager
	 */
	protected $wp_customize;

	// Holds the array for the DynamiCSS
	private $dynamic_css = array();

	public function __construct( WP_Customize_Manager $wp_manager ) {
		// init the dynamic_css property
		$this->dynamic_css = $this->dynamic_css_init();

		// set the private propery to instance of wp_manager
		$this->wp_customize = $wp_manager;

		// register the settings/panels/sections/controls, main method
		$this->register();

		/**
		 * Action and filters
		 */

		// render the CSS and cache it to the theme_mod when the setting is saved
		add_action( 'customize_save_after' , array( $this, 'cache_rendered_css' ) );

		// save logo width/height dimensions
		add_action( 'customize_save_logo_img' , array( __CLASS__, 'save_logo_dimensions' ), 10, 1 );

		// handle the postMessage transfer method with some dynamically generated JS in the footer of the theme
		add_action( 'customize_preview_init', array( $this, 'enqueue_customizer_js' ), 31 );

	}

	private function dynamic_css_init () {
		$darken1  = new Setting\DynamicCSS\ModDarken( 1 );
		$darken5  = new Setting\DynamicCSS\ModDarken( 5 );
		$darken7  = new Setting\DynamicCSS\ModDarken( 7 );
		$darken20  = new Setting\DynamicCSS\ModDarken( 20 );
		$lighten5 = new Setting\DynamicCSS\ModLighten( 5 );
		$button_gradient = new Setting\DynamicCSS\ModLinearGradient( $darken1, $orientation = 'to bottom' );

		return array(
			'top_bar_bg' => array(
				'default'    => '#f2f2f2',
				'css_props' => array( // list of all css properties this setting controls
					array( // each property in it's own array
						'name'      => 'background-color',
						'selectors' => array(
							'noop' => array( // regular selectors
								'.top',
								'.top-navigation a',
							),
						),
					),
					array(
						'name'      => 'border-color',
						'selectors' => array(
							'noop' => array(
								'.top-navigation .sub-menu a',
								'.top-navigation .sub-menu .sub-menu',
							),
						),
						'modifier'  => $darken5, // separate data type, with the modify() method (implemented interface) which takes value and returns modified value OR callable function with 1 argument
					),
					array(
						'name'      => 'border-color',
						'selectors' => array(
							'noop' => array(
								'.top',
								'.top::before',
								'.top::after',
								'.top__container::before',
								'.header::before',
								'.header::after',
							),
						),
						'modifier'  => $darken1,
					),
				),
			),

			'top_bar_color' => array(
				'default'    => '#999999',
				'css_props' => array(
					array(
						'name'      => 'color',
						'selectors' => array(
							'noop' => array(
								'.top',
								'.top-navigation a',
							),
						),
					),
					array(
						'name'      => 'color',
						'selectors' => array(
							'noop' => array(
								'.top-navigation a:focus',
								'.top-navigation a:hover',
							),
						),
						'modifier'  => $darken5,
					),
				),
			),

			'header_bg' => array(
				'default'    => '#ffffff',
				'css_props' => array(
					array(
						'name'      => 'background-color',
						'selectors' => array(
							'noop' => array(
								'.header',
								'.top::before',
								'.top::after',
								'.top__container::before',
								'.header::before',
								'.header::after',
							),
							'@media (min-width: 992px)' => array( // selectors which should be in MQ
								'.header__navigation',
							),
						),
					),
				),
			),

			'main_navigation_mobile_color' => array(
				'default' => '#333333',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'noop' => array(
								'.home-icon',
								'.main-navigation a',
							),
						),
					),
				),
			),

			'main_navigation_mobile_color_hover' => array(
				'default' => '#000000',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'noop' => array(
								'.home-icon:hover',
								'.main-navigation .menu-item:focus > a',
								'.main-navigation .menu-item:hover > a',
							),
						),
					),
				),
			),

			'main_navigation_mobile_sub_color' => array(
				'default' => '#999999',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'@media (max-width: 991px)' => array(
								'.main-navigation .sub-menu a',
							),
						),
					),
				),
			),

			'main_navigation_mobile_background' => array(
				'default' => '#f2f2f2',
				'css_props' => array(
					array(
						'name' => 'background-color',
						'selectors' => array(
							'@media (max-width: 991px)' => array(
								'.header__navigation',
							),
						),
					),
					array(
						'name' => 'border-color',
						'selectors' => array(
							'@media (max-width: 991px)' => array(
								'.header__navigation',
								'.main-navigation a',
								'.home-icon',
							),
						),
						'modifier'  => $darken7,
					),
				),
			),

			'main_navigation_color' => array(
				'default' => '#999999',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'@media (min-width: 992px)' => array(
								'.main-navigation a',
								'.home-icon',
								'.main-navigation > .menu-item-has-children::after',
							),
						),
					),
				),
			),

			'main_navigation_color_hover' => array(
				'default' => '#333333',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'@media (min-width: 992px)' => array(
								'.home-icon:hover',
								'.main-navigation .menu-item:focus > a',
								'.main-navigation .menu-item:hover > a',
								'.main-navigation > .current-menu-item > a',
							),
						),
					),
				),
			),

			'main_navigation_sub_bg' => array(
				'default' => '#edac15',
				'css_props' => array(
					array(
						'name' => 'background-color',
						'selectors' => array(
							'@media (min-width: 992px)' => array(
								'.main-navigation .sub-menu a',
								'.main-navigation > .current-menu-item > a::after',
							),
						),
					),
					array(
						'name' => 'background-color',
						'selectors' => array(
							'@media (min-width: 992px)' => array(
								'.main-navigation .sub-menu .menu-item > a:hover',
							),
						),
						'modifier'  => $darken5,
					),
					array(
						'name' => 'border-color',
						'selectors' => array(
							'@media (min-width: 992px)' => array(
								'.main-navigation .sub-menu a',
								'.main-navigation .sub-menu .menu-item:first-of-type',
								'.main-navigation .sub-menu .sub-menu a',
							),
						),
						'modifier'  => $darken5,
					),
					array(
						'name' => 'color',
						'selectors' => array(
							'@media (min-width: 992px)' => array(
								'.main-navigation .sub-menu .menu-item-has-children::after',
							),
						),
						'modifier'  => $darken5,
					),
				),
			),

			'main_navigation_sub_color' => array(
				'default' => '#ffffff',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'@media (min-width: 992px)' => array(
								'.main-navigation .sub-menu a',
								'.main-navigation .sub-menu .menu-item:focus > a',
								'.main-navigation .sub-menu .menu-item:hover > a',
							),
						),
					),
				),
			),

			'page_header_bg_color' => array(
				'default' => '#f2f2f2',
				'css_props' => array(
					array(
						'name' => 'background-color',
						'selectors' => array(
							'noop' => array(
								'.page-header',
							),
						),
					),
					array(
						'name' => 'border-color',
						'selectors' => array(
							'noop' => array(
								'.page-header',
							),
						),
						'modifier'  => $darken1,
					),
				),
			),

			'page_header_color' => array(
				'default' => '#333333',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'noop' => array(
								'.page-header h1',
								'.page-header h2',
							),
						),
					),
				),
			),

			'page_header_subtitle_color' => array(
				'default' => '#999999',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'noop' => array(
								'.page-header__subtitle',
							),
						),
					),
				),
			),

			'breadcrumbs_bg' => array(
				'default'    => '#ffffff',
				'css_props' => array(
					array(
						'name'      => 'background-color',
						'selectors' => array(
							'noop' => array(
								'.breadcrumbs',
							),
						),
					),
				),
			),

			'breadcrumbs_color' => array(
				'default'    => '#333333',
				'css_props' => array(
					array(
						'name'      => 'color',
						'selectors' => array(
							'noop' => array(
								'.breadcrumbs a',
							),
						),
					),
					array(
						'name'      => 'color',
						'selectors' => array(
							'noop' => array(
								'.breadcrumbs a:hover',
							),
						),
						'modifier'  => $darken20,
					),
				),
			),

			'breadcrumbs_color_active' => array(
				'default'    => '#999999',
				'css_props' => array(
					array(
						'name'      => 'color',
						'selectors' => array(
							'noop' => array(
								'.breadcrumbs span > span',
							),
						),
					),
				),
			),

			'text_color_content_area' => array(
				'default' => '#999999',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'noop' => array(
								'.content-area',
								'.icon-box__subtitle',
							),
						),
					),
				),
			),

			'headings_color' => array(
				'default' => '#333333',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'noop' => array(
								'h1',
								'h2',
								'h3',
								'h4',
								'h5',
								'h6',
								'hentry__title',
								'.hentry__title a',
								'.page-box__title a',
								'.latest-news--block .latest-news__title a',
								'.accordion__panel .panel-title a',
								'.icon-menu__link',
								'.step__title',
								'body.woocommerce-page ul.products li.product h3',
								'.woocommerce ul.products li.product h3',
							),
						),
					),
				),
			),

			'primary_color' => array(
				'default' => '#edac15',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'noop' => array(
								'.icon-box .fa',
								'.step:hover .step__number',
								'.person-profile__social-icon:focus',
								'.person-profile__social-icon:hover',
								'.contact-profile__social-icon:focus',
								'.contact-profile__social-icon:hover',
								'.footer-top a.icon-container:hover',
								'.portfolio-grid__card:focus .portfolio-grid__card-title',
								'.portfolio-grid__card:hover .portfolio-grid__card-title',
								'body.woocommerce-page ul.products li.product a',
								'body.woocommerce-page ul.products li.product a:hover img',
								'.woocommerce ul.products li.product a',
								'.woocommerce ul.products li.product a:hover img',
								'body.woocommerce-page ul.products li.product .price',
								'.woocommerce ul.products li.product .price',
								'body.woocommerce-page .star-rating',
								'.woocommerce .star-rating',
								'body.woocommerce-page div.product p.price',
								'body.woocommerce-page p.stars a',
								'body.woocommerce-page ul.product_list_widget .amount',
								'.woocommerce.widget_shopping_cart .total .amount',
							),
						),
					),
					array(
						'name' => 'background-color',
						'selectors' => array(
							'noop' => array(
								'.contact-profile__name',
								'.person-profile__tag',
								'.latest-news--block .latest-news__date',
								'.widget_calendar caption',
								'.pagination .current',
								'.portfolio-grid__nav-item.is-active::after',
								'body.woocommerce-page .widget_shopping_cart_content .buttons .checkout',
								'body.woocommerce-page button.button.alt',
								'body.woocommerce-page .woocommerce-error a.button',
								'body.woocommerce-page .woocommerce-info a.button',
								'body.woocommerce-page .woocommerce-message a.button',
								'.woocommerce-cart .wc-proceed-to-checkout a.checkout-button',
								'body.woocommerce-page #payment #place_order',
								'body.woocommerce-page #review_form #respond input#submit',
								'.woocommerce button.button.alt:disabled',
								'.woocommerce button.button.alt:disabled:hover',
								'.woocommerce button.button.alt:disabled[disabled]',
								'.woocommerce button.button.alt:disabled[disabled]:hover',
								'body.woocommerce-page nav.woocommerce-pagination ul li span.current',
								'body.woocommerce-page .widget_product_search .search-field + input',
								'body.woocommerce-page div.product .woocommerce-tabs ul.tabs li.active a::after',
								'body.woocommerce-page div.product .woocommerce-tabs ul.tabs li:hover a::after',
								'body.woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle',
								'body.woocommerce-page .widget_price_filter .ui-slider .ui-slider-range',
								'.structurepress-table thead th',
							),
						),
					),
					array(
						'name' => 'border-color',
						'selectors' => array(
							'noop' => array(
								'.accordion__panel:focus',
								'.accordion__panel:hover',
								'.testimonial',
								'.logo-panel img:hover',
								'.btn-primary',
								'body.woocommerce-page .widget_shopping_cart_content .buttons .checkout',
								'body.woocommerce-page nav.woocommerce-pagination ul li span.current',
							),
						),
					),
					array(
						'name' => 'border-bottom-color',
						'selectors' => array(
							'noop' => array(
								'.portfolio-grid__card:focus .portfolio-grid__card-block::after',
								'.portfolio-grid__card:hover .portfolio-grid__card-block::after',
							),
						),
					),
					array(
						'name' => 'border-top-color',
						'selectors' => array(
							'noop' => array(
								'.contact-profile__container',
								'.person-profile__container',
								'.latest-news--block .latest-news__content',
							),
						),
					),
					array(
						'name' => 'border-left-color',
						'selectors' => array(
							'noop' => array(
								'.brochure-box:focus',
								'.brochure-box:hover',
								'.sidebar .widget_nav_menu .menu > li.current-menu-item > a',
								'.sidebar .widget_nav_menu .menu > li > a:focus',
								'.sidebar .widget_nav_menu .menu > li > a:hover',
								'.sticky .hentry__container',
								'.hentry__container:hover',
								'.latest-news--inline:focus',
								'.latest-news--inline:hover',
								'.latest-news--inline + .latest-news--more-news:hover',
								'.open-position:hover .open-position__content-container',
								'.portfolio--left',
								'body.woocommerce-page .widget_product_categories .product-categories > li.current-cat > a',
								'body.woocommerce-page .widget_product_categories .product-categories > li > a:focus',
								'body.woocommerce-page .widget_product_categories .product-categories > li > a:hover',
							),
						),
					),
					array(
						'name' => 'background',
						'selectors' => array(
							'noop' => array(
								'.btn-primary',
								'body.woocommerce-page span.onsale, .woocommerce span.onsale',
							),
						),
						'modifier'  => $button_gradient,
					),
					array(
						'name' => 'border-color',
						'selectors' => array(
							'noop' => array(
								'.btn-primary:hover',
								'.btn-primary:focus',
								'.btn-primary:active:focus',
								'body.woocommerce-page button.button.alt:hover',
							),
						),
						'modifier'  => $darken5,
					),
					array(
						'name' => 'background',
						'selectors' => array(
							'noop' => array(
								'.btn-primary:hover',
								'.btn-primary:focus',
								'.btn-primary:active:focus',
								'body.woocommerce-page .widget_product_search .search-field + input:hover',
								'body.woocommerce-page .widget_product_search .search-field + input:focus',
								'body.woocommerce-page button.button.alt:hover',
								'body.woocommerce-page .woocommerce-error a.button:hover',
								'body.woocommerce-page .woocommerce-info a.button:hover',
								'body.woocommerce-page .woocommerce-message a.button:hover',
								'.woocommerce-cart .wc-proceed-to-checkout a.checkout-button:hover',
								'body.woocommerce-page #payment #place_order:hover',
							),
						),
						'modifier'  => $darken5,
					),
				),
			),

			'link_color' => array(
				'default' => '#539ad0',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'noop' => array(
								'a',
							),
						),
					),
					array(
						'name' => 'color',
						'selectors' => array(
							'noop' => array(
								'a:focus',
								'a:hover',
							),
						),
						'modifier'  => $darken7,
					),
				),
			),

			'footer_bg_color' => array(
				'default' => '#eeeeee',
				'css_props' => array(
					array(
						'name' => 'background-color',
						'selectors' => array(
							'noop' => array(
								'.footer-top',
							),
						),
					),
				),
			),

			'footer_title_color' => array(
				'default' => '#333333',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'noop' => array(
								'.footer-top__headings',
							),
						),
					),
				),
			),

			'footer_text_color' => array(
				'default' => '#999999',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'noop' => array(
								'.footer-top',
							),
						),
					),
				),
			),

			'footer_link_color' => array(
				'default' => '#999999',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'noop' => array(
								'.footer-top .widget_nav_menu .menu a',
							),
						),
					),
				),
			),

			'footer_bottom_bg_color' => array(
				'default' => '#eeeeee',
				'css_props' => array(
					array(
						'name' => 'background-color',
						'selectors' => array(
							'noop' => array(
								'.footer-bottom',
							),
						),
					),
				),
			),

			'footer_bottom_text_color' => array(
				'default' => '#999999',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'noop' => array(
								'.footer-bottom',
							),
						),
					),
				),
			),

			'footer_bottom_link_color' => array(
				'default' => '#333333',
				'css_props' => array(
					array(
						'name' => 'color',
						'selectors' => array(
							'noop' => array(
								'.footer-bottom a',
							),
						),
					),
				),
			),

		);
	}

	/**
	* This hooks into 'customize_register' (available as of WP 3.4) and allows
	* you to add new sections and controls to the Theme Customize screen.
	*
	* Note: To enable instant preview, we have to actually write a bit of custom
	* javascript. See live_preview() for more.
	*
	* @see add_action('customize_register',$func)
	*/
	public function register () {
		/**
		 * Settings
		 */

		// branding
		$this->wp_customize->add_setting( 'logo_img' );
		$this->wp_customize->add_setting( 'logo2x_img' );
		$this->wp_customize->add_setting( 'logo_top_margin', array( 'default' => 12 ) );
		$this->wp_customize->add_setting( 'logo_top_margin_sticky', array( 'default' => 24 ) );

		// header
		$this->wp_customize->add_setting( 'top_bar_visibility', array( 'default' => 'yes' ) );

		// navigation
		$this->wp_customize->add_setting( 'main_navigation_home_icon', array( 'default' => 'yes' ) );
		$this->wp_customize->add_setting( 'main_navigation_sticky', array( 'default' => 'static' ) );

		// page header area
		$this->wp_customize->add_setting( 'show_page_title_area', array( 'default' => 'yes' ) );

		// page header area
		$this->wp_customize->add_setting( 'page_header_bg_img', array( 'default' => '' ) );
		$this->wp_customize->add_setting( 'page_header_bg_img_repeat', array( 'default' => 'repeat' ) );
		$this->wp_customize->add_setting( 'page_header_bg_img_position_x', array( 'default' => 'left' ) );
		$this->wp_customize->add_setting( 'page_header_bg_img_attachment', array( 'default' => 'scroll' ) );

		// featured page
		$this->wp_customize->add_setting( 'featured_page_select', array( 'default' => 'none' ) );
		$this->wp_customize->add_setting( 'featured_page_custom_text' );
		$this->wp_customize->add_setting( 'featured_page_custom_url' );
		$this->wp_customize->add_setting( 'featured_page_open_in_new_window' );

		// typography
		$this->wp_customize->add_setting( 'charset_setting', array( 'default' => 'latin' ) );

		// theme layout & color
		$this->wp_customize->add_setting( 'layout_mode', array( 'default' => 'wide' ) );

		// shop
		if ( StructurePressHelpers::is_woocommerce_active() ) {
			$this->wp_customize->add_setting( 'products_per_page', array( 'default' => 9 ) );
			$this->wp_customize->add_setting( 'single_product_sidebar', array( 'default' => 'left' ) );
		}

		// portfolio
		if ( StructurePressHelpers::is_portfolio_plugin_active() ) {
			$this->wp_customize->add_setting( 'portfolio_parent_page', array( 'default' => 0 ) );
			$this->wp_customize->add_setting( 'portfolio_name_signular', array( 'default' => 'Project' ) );
			$this->wp_customize->add_setting( 'portfolio_name_plural', array( 'default' => 'Projects' ) );
			$this->wp_customize->add_setting( 'portfolio_gallery_columns', array( 'default' => 1 ) );
			$this->wp_customize->add_setting( 'portfolio_slug', array(
				'default'           => 'portfolio',
				'sanitize_callback' => array( $this, 'sanitize_portfolio_slug' ),
			) );
		}

		// footer
		$this->wp_customize->add_setting( 'footer_widgets_layout', array( 'default' => '[4,6,8]' ) );

		$this->wp_customize->add_setting( 'footer_center_txt', array( 'default' => '<i class="fa  fa-2x  fa-cc-visa"></i> &nbsp; <i class="fa  fa-2x  fa-cc-mastercard"></i> &nbsp; <i class="fa  fa-2x  fa-cc-amex"></i> &nbsp; <i class="fa  fa-2x  fa-cc-paypal"></i>' ) );
		$this->wp_customize->add_setting( 'footer_left_txt', array( 'default' => '<a href="https://www.proteusthemes.com/wordpress-themes/structurepress/">StructurePress Theme</a> Made by ProteusThemes.' ) );
		$this->wp_customize->add_setting( 'footer_right_txt', array( 'default' => '&copy; 2009-2015 StructurePress. All rights reserved.' ) );

		// custom code (css/js)
		$this->wp_customize->add_setting( 'custom_js_head' );
		$this->wp_customize->add_setting( 'custom_js_footer' );
		$this->wp_customize->add_setting( 'custom_css', array( 'default' => '' ) );

		// Migrate any existing theme CSS to the core option added in WordPress 4.7.
		if ( function_exists( 'wp_update_custom_css_post' ) ) {
			$css = get_theme_mod( 'custom_css', '' );

			if ( ! empty( $css ) ) {
				$core_css = wp_get_custom_css(); // Preserve any CSS already added to the core option.
				$return   = wp_update_custom_css_post( '/* Migrated CSS from old Theme Custom CSS setting: */' . PHP_EOL . $css . PHP_EOL . PHP_EOL . '/* New custom CSS: */' . PHP_EOL . $core_css );
				if ( ! is_wp_error( $return ) ) {
					// Remove the old theme_mod, so that the CSS is stored in only one place moving forward.
					remove_theme_mod( 'custom_css' );
				}
			}

			// Add new "CSS setting" that will only notify the users that the new "Additional CSS" field is available.
			// It can't be the same name ('custom_css'), because the core control is also named 'custom_css' and it would not display the WP core "Additional CSS" control.
			$this->wp_customize->add_setting( 'pt_custom_css', array( 'default' => '' ) );
		}

		// acf
		$this->wp_customize->add_setting( 'show_acf', array( 'default' => 'no' ) );

		// all the DynamicCSS settings
		foreach ( $this->dynamic_css as $setting_id => $args ) {
			$this->wp_customize->add_setting(
				new Setting\DynamicCSS( $this->wp_customize, $setting_id, $args )
			);
		}

		/**
		 * Panel and Sections
		 */

		// one ProteusThemes panel to rule them all
		$this->wp_customize->add_panel( 'panel_structurepress', array(
			'title'       => _x( '[PT] Theme Options', 'backend', 'structurepress-pt' ),
			'description' => _x( 'All StructurePress theme specific settings.', 'backend', 'structurepress-pt' ),
			'priority'    => 10,
		) );

		// individual sections
		$this->wp_customize->add_section( 'structurepress_section_logos', array(
			'title'       => _x( 'Logo', 'backend', 'structurepress-pt' ),
			'description' => _x( 'Logo for the StructurePress theme.', 'backend', 'structurepress-pt' ),
			'priority'    => 10,
			'panel'       => 'panel_structurepress',
		) );

		// Header
		$this->wp_customize->add_section( 'structurepress_section_header', array(
			'title'       => _x( 'Header', 'backend', 'structurepress-pt' ),
			'description' => _x( 'All layout and appearance settings for the header.', 'backend', 'structurepress-pt' ),
			'priority'    => 20,
			'panel'       => 'panel_structurepress',
		) );

		$this->wp_customize->add_section( 'structurepress_section_navigation', array(
			'title'       => _x( 'Navigation', 'backend', 'structurepress-pt' ),
			'description' => _x( 'Navigation for the StructurePress theme.', 'backend', 'structurepress-pt' ),
			'priority'    => 30,
			'panel'       => 'panel_structurepress',
		) );

		$this->wp_customize->add_section( 'structurepress_section_page_header', array(
			'title'       => _x( 'Page Header Area', 'backend', 'structurepress-pt' ),
			'description' => _x( 'All layout and appearance settings for the page header area (regular pages).', 'backend', 'structurepress-pt' ),
			'priority'    => 33,
			'panel'       => 'panel_structurepress',
		) );

		$this->wp_customize->add_section( 'structurepress_section_breadcrumbs', array(
			'title'       => _x( 'Breadcrumbs', 'backend', 'structurepress-pt' ),
			'description' => _x( 'All layout and appearance settings for breadcrumbs.', 'backend', 'structurepress-pt' ),
			'priority'    => 35,
			'panel'       => 'panel_structurepress',
		) );

		$this->wp_customize->add_section( 'structurepress_section_theme_colors', array(
			'title'       => _x( 'Theme Layout &amp; Colors', 'backend', 'structurepress-pt' ),
			'priority'    => 40,
			'panel'       => 'panel_structurepress',
		) );

		if ( StructurePressHelpers::is_woocommerce_active() ) {
			$this->wp_customize->add_section( 'structurepress_section_shop', array(
				'title'       => _x( 'Shop', 'backend', 'structurepress-pt' ),
				'priority'    => 80,
				'panel'       => 'panel_structurepress',
			) );
		}

		if ( StructurePressHelpers::is_portfolio_plugin_active() ) {
			$this->wp_customize->add_section( 'structurepress_section_portfolio', array(
				'title'       => _x( 'Portfolio', 'backend', 'structurepress-pt' ),
				'priority'    => 85,
				'panel'       => 'panel_structurepress',
			) );
		}

		$this->wp_customize->add_section( 'section_footer', array(
			'title'       => _x( 'Footer', 'backend', 'structurepress-pt' ),
			'description' => _x( 'All layout and appearance settings for the footer.', 'backend', 'structurepress-pt' ),
			'priority'    => 90,
			'panel'       => 'panel_structurepress',
		) );

		$this->wp_customize->add_section( 'section_custom_code', array(
			'title'       => _x( 'Custom Code' , 'backend', 'structurepress-pt' ),
			'priority'    => 100,
			'panel'       => 'panel_structurepress',
		) );

		$this->wp_customize->add_section( 'section_other', array(
			'title'       => _x( 'Other' , 'backend', 'structurepress-pt' ),
			'priority'    => 150,
			'panel'       => 'panel_structurepress',
		) );

		/**
		 * Controls
		 */

		// Section: structurepress_section_logos
		$this->wp_customize->add_control( new WP_Customize_Image_Control(
			$this->wp_customize,
			'logo_img',
			array(
				'label'       => _x( 'Logo Image', 'backend', 'structurepress-pt' ),
				'description' => _x( 'Max height for the logo image is 120px.', 'backend', 'structurepress-pt' ),
				'section'     => 'structurepress_section_logos',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Image_Control(
			$this->wp_customize,
			'logo2x_img',
			array(
				'label'       => _x( 'Retina Logo Image', 'backend', 'structurepress-pt' ),
				'description' => _x( '2x logo size, for screens with high DPI.', 'backend', 'structurepress-pt' ),
				'section'     => 'structurepress_section_logos',
			)
		) );
		$this->wp_customize->add_control(
			'logo_top_margin',
			array(
				'type'        => 'number',
				'label'       => _x( 'Logo top margin', 'backend', 'structurepress-pt' ),
				'description' => _x( 'In pixels.', 'backend', 'structurepress-pt' ),
				'section'     => 'structurepress_section_logos',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 120,
					'step' => 5,
				),
			)
		);
		$this->wp_customize->add_control(
			'logo_top_margin_sticky',
			array(
				'type'            => 'number',
				'label'           => _x( 'Logo top margin on Sticky menu', 'backend', 'structurepress-pt' ),
				'description'     => _x( 'In pixels.', 'backend', 'structurepress-pt' ),
				'section'         => 'structurepress_section_logos',
				'active_callback' => array( $this, 'is_nav_set_to_sticky' ),
				'input_attrs'     => array(
					'min'  => 0,
					'max'  => 120,
					'step' => 5,
				),
			)
		);

		// Section: header
		$this->wp_customize->add_control( 'top_bar_visibility', array(
			'type'        => 'select',
			'priority'    => 0,
			'label'       => _x( 'Top bar visibility', 'backend', 'structurepress-pt' ),
			'description' => _x( 'Show or hide?', 'backend', 'structurepress-pt' ),
			'section'     => 'structurepress_section_header',
			'choices'     => array(
				'yes'         => _x( 'Show', 'backend', 'structurepress-pt' ),
				'no'          => _x( 'Hide', 'backend', 'structurepress-pt' ),
				'hide_mobile' => _x( 'Hide on Mobile', 'backend', 'structurepress-pt' ),
			),
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'top_bar_bg',
			array(
				'priority' => 2,
				'label'    => _x( 'Top bar background color', 'backend', 'structurepress-pt' ),
				'section'  => 'structurepress_section_header',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'top_bar_color',
			array(
				'priority' => 3,
				'label'    => _x( 'Top bar text color', 'backend', 'structurepress-pt' ),
				'section'  => 'structurepress_section_header',
			)
		) );

		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'header_bg',
			array(
				'priority' => 30,
				'label'    => _x( 'Header background color', 'backend', 'structurepress-pt' ),
				'section'  => 'structurepress_section_header',
			)
		) );

		// Section: structurepress_section_navigation
		$this->wp_customize->add_control( 'main_navigation_home_icon', array(
			'type'        => 'select',
			'priority'    => 110,
			'label'       => _x( 'Home Icon', 'backend', 'structurepress-pt' ),
			'section'     => 'structurepress_section_navigation',
			'choices'     => array(
				'yes'         => _x( 'Show', 'backend', 'structurepress-pt' ),
				'no'          => _x( 'Hide', 'backend', 'structurepress-pt' ),
			),
		) );
		$this->wp_customize->add_control( 'main_navigation_sticky', array(
			'type'        => 'select',
			'priority'    => 111,
			'label'       => _x( 'Static or sticky navbar?', 'backend', 'structurepress-pt' ),
			'section'     => 'structurepress_section_navigation',
			'choices'     => array(
				'static' => _x( 'Static', 'backend', 'structurepress-pt' ),
				'sticky' => _x( 'Sticky', 'backend', 'structurepress-pt' ),
			),
		) );
		$this->wp_customize->add_control( 'featured_page_select', array(
				'type'        => 'select',
				'priority'    => 113,
				'label'       => _x( 'Featured page', 'backend', 'structurepress-pt' ),
				'description' => _x( 'To which page should the Featured Page button link to?', 'backend', 'structurepress-pt' ),
				'section'     => 'structurepress_section_navigation',
				'choices'     => $this->get_all_pages_id_title(),
		) );
		$this->wp_customize->add_control( 'featured_page_custom_text', array(
				'priority'    => 115,
				'label'       => _x( 'Custom Button Text', 'backend', 'structurepress-pt' ),
				'section'     => 'structurepress_section_navigation',
				'active_callback' => array( $this, 'is_featured_page_custom_url' ),
		) );

		$this->wp_customize->add_control( 'featured_page_custom_url', array(
				'priority'    => 117,
				'label'       => _x( 'Custom URL', 'backend', 'structurepress-pt' ),
				'section'     => 'structurepress_section_navigation',
				'active_callback' => array( $this, 'is_featured_page_custom_url' ),
		) );

		$this->wp_customize->add_control( 'featured_page_open_in_new_window', array(
				'type'        => 'checkbox',
				'priority'    => 120,
				'label'       => _x( 'Open link in a new window/tab.', 'backend', 'structurepress-pt' ),
				'section'     => 'structurepress_section_navigation',
				'active_callback' => array( $this, 'is_featured_page_selected' ),
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_navigation_color',
			array(
				'priority' => 130,
				'label'    => _x( 'Main navigation link color', 'backend', 'structurepress-pt' ),
				'section'  => 'structurepress_section_navigation',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_navigation_color_hover',
			array(
				'priority' => 132,
				'label'    => _x( 'Main navigation link hover color', 'backend', 'structurepress-pt' ),
				'section'  => 'structurepress_section_navigation',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_navigation_sub_bg',
			array(
				'priority' => 160,
				'label'    => _x( 'Main navigation submenu background', 'backend', 'structurepress-pt' ),
				'section'  => 'structurepress_section_navigation',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_navigation_sub_color',
			array(
				'priority' => 170,
				'label'    => _x( 'Main navigation submenu link color', 'backend', 'structurepress-pt' ),
				'section'  => 'structurepress_section_navigation',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_navigation_mobile_color',
			array(
				'priority' => 190,
				'label'    => _x( 'Main navigation link color (mobile)', 'backend', 'structurepress-pt' ),
				'section'  => 'structurepress_section_navigation',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_navigation_mobile_color_hover',
			array(
				'priority' => 192,
				'label'    => _x( 'Main navigation link hover color (mobile)', 'backend', 'structurepress-pt' ),
				'section'  => 'structurepress_section_navigation',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_navigation_mobile_sub_color',
			array(
				'priority' => 194,
				'label'    => _x( 'Main navigation submenu link color (mobile)', 'backend', 'structurepress-pt' ),
				'section'  => 'structurepress_section_navigation',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_navigation_mobile_background',
			array(
				'priority' => 188,
				'label'    => _x( 'Main navigation background color (mobile)', 'backend', 'structurepress-pt' ),
				'section'  => 'structurepress_section_navigation',
			)
		) );

		// section: structurepress_section_page_header
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'page_header_bg_color',
			array(
				'priority' => 10,
				'label'    => _x( 'Page Header Background Color', 'backend', 'structurepress-pt' ),
				'section'  => 'structurepress_section_page_header',
			)
		) );

		$this->wp_customize->add_control( new WP_Customize_Image_Control(
			$this->wp_customize,
			'page_header_bg_img',
			array(
				'priority' => 20,
				'label'    => _x( 'Page Header Background Image', 'backend', 'structurepress-pt' ),
				'section'  => 'structurepress_section_page_header',
			)
		) );
		$this->wp_customize->add_control( 'page_header_bg_img_repeat', array(
			'priority'   => 21,
			'label'      => _x( 'Page Header Background Repeat', 'backend', 'structurepress-pt' ),
			'section'    => 'structurepress_section_page_header',
			'type'       => 'radio',
			'choices'    => array(
				'no-repeat'  => __( 'No Repeat', 'structurepress-pt' ),
				'repeat'     => __( 'Tile', 'structurepress-pt' ),
				'repeat-x'   => __( 'Tile Horizontally', 'structurepress-pt' ),
				'repeat-y'   => __( 'Tile Vertically', 'structurepress-pt' ),
			),
		) );
		$this->wp_customize->add_control( 'page_header_bg_img_position_x', array(
			'priority'   => 22,
			'label'      => _x( 'Page Header Background Position', 'backend', 'structurepress-pt' ),
			'section'    => 'structurepress_section_page_header',
			'type'       => 'radio',
			'choices'    => array(
				'left'       => __( 'Left', 'structurepress-pt' ),
				'center'     => __( 'Center', 'structurepress-pt' ),
				'right'      => __( 'Right', 'structurepress-pt' ),
			),
		) );
		$this->wp_customize->add_control( 'page_header_bg_img_attachment', array(
			'priority'   => 23,
			'label'      => _x( 'Page Header Background Attachment', 'backend', 'structurepress-pt' ),
			'section'    => 'structurepress_section_page_header',
			'type'       => 'radio',
			'choices'    => array(
				'scroll'     => __( 'Scroll', 'structurepress-pt' ),
				'fixed'      => __( 'Fixed', 'structurepress-pt' ),
			),
		) );

		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'page_header_color',
			array(
				'priority' => 30,
				'label'    => _x( 'Page Header Color', 'backend', 'structurepress-pt' ),
				'section'  => 'structurepress_section_page_header',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'page_header_subtitle_color',
			array(
				'priority' => 31,
				'label'    => _x( 'Main Subtitle Color', 'backend', 'structurepress-pt' ),
				'section'  => 'structurepress_section_page_header',
			)
		) );
		$this->wp_customize->add_control( 'show_page_title_area', array(
			'type'        => 'select',
			'priority'    => 35,
			'label'       => _x( 'Show page title area', 'backend', 'structurepress-pt' ),
			'description' => _x( 'This will hide the page title area on all pages. You can also hide individual page headers in page settings. To remove breadcrumbs from all pages, please deactivate the Breadcrumb NavXT plugin.', 'backend', 'structurepress-pt' ),
			'section'     => 'structurepress_section_page_header',
			'choices'     => array(
				'yes'         => _x( 'Show', 'backend', 'structurepress-pt' ),
				'no'          => _x( 'Hide', 'backend', 'structurepress-pt' ),
			),
		) );

		// Section: structurepress_section_breadcrumbs
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'breadcrumbs_bg',
			array(
				'priority' => 10,
				'label'    => _x( 'Breadcrumbs background color', 'backend', 'structurepress-pt' ),
				'section'  => 'structurepress_section_breadcrumbs',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'breadcrumbs_color',
			array(
				'priority' => 20,
				'label'    => _x( 'Breadcrumbs text color', 'backend', 'structurepress-pt' ),
				'section'  => 'structurepress_section_breadcrumbs',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'breadcrumbs_color_active',
			array(
				'priority' => 30,
				'label'    => _x( 'Breadcrumbs active text color', 'backend', 'structurepress-pt' ),
				'section'  => 'structurepress_section_breadcrumbs',
			)
		) );

		// Section: structurepress_section_theme_colors
		$this->wp_customize->add_control( 'layout_mode', array(
			'type'     => 'select',
			'priority' => 10,
			'label'    => _x( 'Layout', 'backend', 'structurepress-pt' ),
			'section'  => 'structurepress_section_theme_colors',
			'choices'  => array(
				'wide'  => _x( 'Wide', 'backend', 'structurepress-pt' ),
				'boxed' => _x( 'Boxed', 'backend', 'structurepress-pt' ),
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'text_color_content_area',
			array(
				'priority' => 30,
				'label'    => _x( 'Text color', 'backend', 'structurepress-pt' ),
				'section'  => 'structurepress_section_theme_colors',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'link_color',
			array(
				'priority' => 35,
				'label'    => _x( 'Link color', 'backend', 'structurepress-pt' ),
				'section'  => 'structurepress_section_theme_colors',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'headings_color',
			array(
				'priority' => 33,
				'label'    => _x( 'Headings color', 'backend', 'structurepress-pt' ),
				'section'  => 'structurepress_section_theme_colors',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'primary_color',
			array(
				'priority' => 34,
				'label'    => _x( 'Primary color', 'backend', 'structurepress-pt' ),
				'section'  => 'structurepress_section_theme_colors',
			)
		) );

		// Section: structurepress_section_shop
		if ( StructurePressHelpers::is_woocommerce_active() ) {
			$this->wp_customize->add_control( 'products_per_page', array(
					'label'   => _x( 'Number of products per page', 'backend', 'structurepress-pt' ),
					'section' => 'structurepress_section_shop',
				)
			);
			$this->wp_customize->add_control( 'single_product_sidebar', array(
					'label'   => _x( 'Sidebar on single product page', 'backend', 'structurepress-pt' ),
					'section' => 'structurepress_section_shop',
					'type'    => 'select',
					'choices' => array(
						'none'  => _x( 'No sidebar', 'backend', 'structurepress-pt' ),
						'left'  => _x( 'Left', 'backend', 'structurepress-pt' ),
						'right' => _x( 'Right', 'backend', 'structurepress-pt' ),
					)
				)
			);
		}

		// Section: structurepress_section_portfolio
		if ( StructurePressHelpers::is_portfolio_plugin_active() ) {
			$this->wp_customize->add_control( 'portfolio_parent_page', array(
					'label'       => _x( 'Portfolio parent page', 'backend', 'structurepress-pt' ),
					'description' => _x( 'Page with all the portfolio items.', 'backend', 'structurepress-pt' ),
					'section'     => 'structurepress_section_portfolio',
					'type'        => 'dropdown-pages',
				)
			);
			$this->wp_customize->add_control( 'portfolio_name_signular', array(
					'label'   => _x( 'Singular name for portfolio', 'backend', 'structurepress-pt' ),
					'section' => 'structurepress_section_portfolio',
				)
			);
			$this->wp_customize->add_control( 'portfolio_name_plural', array(
					'label'   => _x( 'Plural name for portfolio', 'backend', 'structurepress-pt' ),
					'section' => 'structurepress_section_portfolio',
				)
			);
			$this->wp_customize->add_control( 'portfolio_slug', array(
					'label'       => __( 'Portfolio slug', 'structurepress-pt' ),
					'description' => __( 'This is used in the URL part. After changing this, you must save the permalink settings again in Settings &rarr; Permalinks.', 'structurepress-pt' ),
					'section'     => 'structurepress_section_portfolio',
				)
			);
			$this->wp_customize->add_control( 'portfolio_gallery_columns', array(
					'label'   => _x( 'Number of columns in gallery', 'backend', 'structurepress-pt' ),
					'section' => 'structurepress_section_portfolio',
					'type'    => 'select',
					'choices' => array(
						'1' => '1',
						'2' => '2',
						'3' => '3',
					)
				)
			);
		}

		// Section: section_footer
		$this->wp_customize->add_control( new Control\LayoutBuilder(
			$this->wp_customize,
			'footer_widgets_layout',
			array(
				'priority'    => 1,
				'label'       => _x( 'Footer widgets layout', 'backend', 'structurepress-pt' ),
				'description' => _x( 'Select number of widget you want in the footer and then with the slider rearrange the layout', 'backend', 'structurepress-pt' ),
				'section'     => 'section_footer',
				'input_attrs' => array(
					'min'     => 0,
					'max'     => 12,
					'step'    => 1,
					'maxCols' => 6,
				)
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'footer_bg_color',
			array(
				'priority' => 10,
				'label'    => _x( 'Footer background color', 'backend', 'structurepress-pt' ),
				'section'  => 'section_footer',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'footer_title_color',
			array(
				'priority' => 30,
				'label'    => _x( 'Footer widget title color', 'backend', 'structurepress-pt' ),
				'section'  => 'section_footer',
			)
		) );

		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'footer_text_color',
			array(
				'priority' => 31,
				'label'    => _x( 'Footer text color', 'backend', 'structurepress-pt' ),
				'section'  => 'section_footer',
			)
		) );

		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'footer_link_color',
			array(
				'priority' => 32,
				'label'    => _x( 'Footer link color', 'backend', 'structurepress-pt' ),
				'section'  => 'section_footer',
			)
		) );

		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'footer_bottom_bg_color',
			array(
				'priority' => 35,
				'label'    => _x( 'Footer bottom background color', 'backend', 'structurepress-pt' ),
				'section'  => 'section_footer',
			)
		) );

		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'footer_bottom_text_color',
			array(
				'priority' => 36,
				'label'    => _x( 'Footer bottom text color', 'backend', 'structurepress-pt' ),
				'section'  => 'section_footer',
			)
		) );

		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'footer_bottom_link_color',
			array(
				'priority' => 37,
				'label'    => _x( 'Footer bottom link color', 'backend', 'structurepress-pt' ),
				'section'  => 'section_footer',
			)
		) );

		$this->wp_customize->add_control( 'footer_left_txt', array(
			'type'        => 'text',
			'priority'    => 110,
			'label'       => _x( 'Footer text on the left', 'backend', 'structurepress-pt' ),
			'description' => _x( 'You can use HTML: a, span, i, em, strong, img.', 'backend', 'structurepress-pt' ),
			'section'     => 'section_footer',
		) );

		$this->wp_customize->add_control( 'footer_center_txt', array(
			'type'        => 'text',
			'priority'    => 105,
			'label'       => _x( 'Footer text on the center', 'backend', 'structurepress-pt' ),
			'description' => _x( 'You can use HTML: a, span, i, em, strong, img.', 'backend', 'structurepress-pt' ),
			'section'     => 'section_footer',
		) );

		$this->wp_customize->add_control( 'footer_right_txt', array(
			'type'        => 'text',
			'priority'    => 120,
			'label'       => _x( 'Footer text on the right', 'backend', 'structurepress-pt' ),
			'description' => _x( 'You can use HTML: a, span, i, em, strong, img.', 'backend', 'structurepress-pt' ),
			'section'     => 'section_footer',
		) );

		// Section: section_custom_code
		if ( function_exists( 'wp_update_custom_css_post' ) ) {
			// Show the notice of custom CSS setting migration.
			$this->wp_customize->add_control( 'pt_custom_css', array(
				'type'        => 'hidden',
				'label'       => esc_html__( 'Custom CSS', 'structurepress-pt' ),
				'description' => esc_html__( 'This field is obsolete. The existing code was migrated to the "Additional CSS" field, that can be found in the root of the customizer. This new "Additional CSS" field is a WordPress core field and was introduced in WP version 4.7.', 'structurepress-pt' ),
				'section'     => 'section_custom_code',
			) );
		}
		else {
			$this->wp_customize->add_control( 'custom_css', array(
				'type'        => 'textarea',
				'label'       => _x( 'Custom CSS', 'backend', 'structurepress-pt' ),
				'description' => sprintf( _x( '%s How to find CSS classes %s in the theme.', 'backend', 'structurepress-pt' ), '<a href="https://www.youtube.com/watch?v=V2aAEzlvyDc" target="_blank">', '</a>' ),
				'section'     => 'section_custom_code',
			) );
		}

		$this->wp_customize->add_control( 'custom_js_head', array(
			'type'        => 'textarea',
			'label'       => _x( 'Custom JavaScript (head)', 'backend', 'structurepress-pt' ),
			'description' => _x( 'You have to include the &lt;script&gt;&lt;/script&gt; tags as well. Paste your Google Analytics tracking code here.', 'backend', 'structurepress-pt' ),
			'section'     => 'section_custom_code',
		) );

		$this->wp_customize->add_control( 'custom_js_footer', array(
			'type'        => 'textarea',
			'label'       => _x( 'Custom JavaScript (footer)', 'backend', 'structurepress-pt' ),
			'description' => _x( 'You have to include the &lt;script&gt;&lt;/script&gt; tags as well.', 'backend', 'structurepress-pt' ),
			'section'     => 'section_custom_code',
		) );

		// Section: section_other
		$this->wp_customize->add_control( 'show_acf', array(
			'type'        => 'select',
			'label'       => _x( 'Show ACF admin panel?', 'backend', 'structurepress-pt' ),
			'description' => _x( 'If you want to use ACF and need the ACF admin panel set this to <strong>Yes</strong>. Do not change if you do not know what you are doing.', 'backend', 'structurepress-pt' ),
			'section'     => 'section_other',
			'choices'     => array(
				'no'  => _x( 'No', 'backend', 'structurepress-pt' ),
				'yes' => _x( 'Yes', 'backend', 'structurepress-pt' ),
			),
		) );
		$this->wp_customize->add_control( 'charset_setting', array(
			'type'     => 'select',
			'label'    => _x( 'Character set for Google Fonts', 'backend' , 'structurepress-pt' ),
			'section'  => 'section_other',
			'choices'  => array(
				'latin'        => 'Latin',
				'latin-ext'    => 'Latin Extended',
				'cyrillic'     => 'Cyrillic',
				'cyrillic-ext' => 'Cyrillic Extended',
			)
		) );
	}

	/**
	 * Returns all published pages (IDs and titles).
	 *
	 * used by the featured_page_select control
	 *
	 * @return map with key: ID and value: title
	 */
	public function get_all_pages_id_title() {
		$args = array(
			'sort_order'  => 'ASC',
			'sort_column' => 'post_title',
			'post_type'   => 'page',
			'post_status' => 'publish',
		);
		$pages = get_pages( $args );

		// Create the pages map with the default value of none and the custom url option.
		$featured_page_choices               = array();
		$featured_page_choices['none']       = _x( 'None', 'backend', 'structurepress-pt' );
		$featured_page_choices['custom-url'] = _x( 'Custom URL', 'backend', 'structurepress-pt' );

		// Parse through the objects returned and add the key value pairs to the featured_page_choices map
		foreach ( $pages as $page ) {
			$featured_page_choices[ $page->ID ] = $page->post_title;
		}

		return $featured_page_choices;
	}

	/**
	 * Returns if the featured page is selected.
	 *
	 * used by the featured_page_open_in_new_window control
	 *
	 * @return boolean
	 */
	public function is_featured_page_selected() {
		return 'none' !== get_theme_mod( 'featured_page_select', 'none' );
	}

	/**
	 * Returns true if the sticky menu is set.
	 *
	 * used by the logo_top_margin_sticky control
	 *
	 * @return boolean
	 */
	public function is_nav_set_to_sticky() {
		return 'sticky' === get_theme_mod( 'main_navigation_sticky', 'static' );
	}

	/**
	 * Returns if the featured page is selected.
	 *
	 * used by the featured_page_custom_url control
	 *
	 * @return boolean
	 */
	public function is_featured_page_custom_url() {
		return 'custom-url' === get_theme_mod( 'featured_page_select', 'none' );
	}

	/**
	 * Cache the rendered CSS after the settings are saved in the DB.
	 * This is purely a performance improvement.
	 *
	 * Used by hook: add_action( 'customize_save_after' , array( $this, 'cache_rendered_css' ) );
	 *
	 * @return void
	 */
	public function cache_rendered_css() {
		set_theme_mod( 'cached_css', $this->render_css() );
	}

	/**
	 * Get the dimensions of the logo image when the setting is saved
	 * This is purely a performance improvement.
	 *
	 * Used by hook: add_action( 'customize_save_logo_img' , array( $this, 'save_logo_dimensions' ), 10, 1 );
	 *
	 * @return void
	 */
	public static function save_logo_dimensions( $setting ) {
		$logo_width_height = array();
		$img_data          = getimagesize( esc_url( $setting->post_value() ) );

		if ( is_array( $img_data ) ) {
			$logo_width_height = array_slice( $img_data, 0, 2 );
			$logo_width_height = array_combine( array( 'width', 'height' ), $logo_width_height );
		}

		set_theme_mod( 'logo_dimensions_array', $logo_width_height );
	}

	/**
	 * Render the CSS from all the settings which are of type `Setting\DynamicCSS`
	 *
	 * @return string text/css
	 */
	public function render_css() {
		$out = '';

		foreach ( $this->get_dynamic_css_settings() as $setting ) {
			$out .= $setting->render_css();
		}

		return $out;
	}

	/**
	 * Get only the CSS settings of type `Setting\DynamicCSS`.
	 *
	 * @see is_dynamic_css_setting
	 * @return array
	 */
	public function get_dynamic_css_settings() {
		return array_filter( $this->wp_customize->settings(), array( $this, 'is_dynamic_css_setting' ) );
	}

	/**
	 * Helper conditional function for filtering the settings.
	 *
	 * @see
	 * @param  mixed  $setting
	 * @return boolean
	 */
	protected function is_dynamic_css_setting( $setting ) {
		return is_a( $setting, '\ProteusThemes\CustomizerUtils\Setting\DynamicCSS' );
	}

	/**
	 * Enqueue the JS for live preview the settings of type `Setting\DynamicCSS`.
	 *
	 * All the color changes are transported to the live preview frame using the 'postMessage'
	 * method. The settings key IDs, selectors and CSS props are passed to the script.
	 *
	 * Used by hook: 'customize_preview_init'
	 *
	 * @see add_action('customize_preview_init',$func)
	 */
	public function enqueue_customizer_js() {
		wp_enqueue_script(
			'structurepress-live-customize',
			get_template_directory_uri() . '/vendor/proteusthemes/wp-customizer-utilities/assets/live-customize.js',
			array( 'jquery', 'customize-preview' ),
			false,
			true
		);

		wp_localize_script( 'structurepress-live-customize', 'ptCustomizerDynamicCSS', $this->js_dynamic_css() );
	}

	/**
	 * Prepare suitable data to pass to JS - only selectors, setting IDs and name of
	 * CSS properties. All selectors are returned, not taking MQ in account.
	 * @return array
	 */
	private function js_dynamic_css() {
		$out = array();

		foreach ( $this->dynamic_css as $setting_id => $setting ) {
			foreach ( $setting['css_props'] as $css_prop ) {
				$css_selectors = array();

				foreach ( $css_prop['selectors'] as $selectors ) {
					$css_selectors += $selectors;
				}

				$css_selectors = array_filter( $css_selectors );
				$css_selectors = array_unique( $css_selectors );

				if ( ! empty( $css_selectors ) ) {
					$out[] = array(
						'settingID' => $setting_id,
						'selectors' => join( ', ', $css_selectors ),
						'cssProp'   => $css_prop['name'],
					);
				}
			}
		}

		return $out;
	}

	/**
	 * Create a sanitized slug, but always with a fallback to portfolio if it is empty
	 * @param  string $title
	 * @return string
	 */
	public static function sanitize_portfolio_slug( $title ) {
		return sanitize_title( $title, 'portfolio' );
	}
}