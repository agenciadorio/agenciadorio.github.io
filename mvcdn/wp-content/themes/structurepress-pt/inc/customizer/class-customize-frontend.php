<?php
/**
 * Class which handles the output of the WP customizer on the frontend.
 * Meaning that this stuff loads always, no matter if the global $wp_cutomize
 * variable is present or not.
 */

/**
 * Customizer frontend related code
 */
class StructurePress_Customize_Frontent {

	/**
	 * Add actions to load the right staff at the right places (header, footer).
	 */
	function __construct() {
		add_action( 'wp_enqueue_scripts' , array( $this, 'customizer_css' ), 20 );
		add_action( 'wp_head' , array( $this, 'head_output' ) );
		add_action( 'wp_footer' , array( $this, 'footer_output' ) );
	}

	/**
	* This will output the custom WordPress settings to the live theme's WP head.
	*
	* Used by hook: 'wp_head'
	*
	* @see add_action( 'wp_head' , array( $this, 'head_output' ) );
	*/
	public static function customizer_css() {
		$css = array();

		$css[] = self::get_customizer_colors_css();
		$css[] = self::get_logo_top_margin_css();
		$css[] = self::get_custom_css();
		$css[] = self::get_navigation_width();
		$css[] = self::get_page_header_bg_image();

		$css_string = join( PHP_EOL, $css );

		if ( $css_string ) {
			wp_add_inline_style( self::get_inline_styles_handler(), $css_string );
		}
	}


	/**
	 * Woocommerce css handler if woo is active, main css handler otherwise
	 * @return string
	 */
	public static function get_inline_styles_handler() {
		if ( StructurePressHelpers::is_woocommerce_active() ) {
			return 'structurepress-woocommerce';
		}

		return 'structurepress-main';
	}


	/**
	 * Branding CSS, generated dynamically and cached stringifyed in db
	 * @return string CSS
	 */
	public static function get_customizer_colors_css() {
		$out        = '';
		$cached_css = get_theme_mod( 'cached_css', '' );

		$out .= '/* WP Customizer start */' . PHP_EOL;
		$out .= apply_filters( 'structurepress_cached_css', $cached_css );
		$out .= PHP_EOL . '/* WP Customizer end */';

		return $out;
	}


	/**
	 * Custom CSS, written in customizer
	 * @return string CSS
	 */
	public static function get_custom_css() {
		$out      = '';
		$user_css = get_theme_mod( 'custom_css', '' );

		if ( strlen( $user_css ) ) {
			$out .= PHP_EOL . '/* User custom CSS start */' . PHP_EOL;
			$out .= $user_css . PHP_EOL; // no need to filter this, because it is 100% custom code
			$out .= PHP_EOL . '/* User custom CSS end */' . PHP_EOL;
		}

		return $out;
	}


	/**
	 * Set top margin of the logo
	 *
	 * @return string CSS
	 */
	public static function get_logo_top_margin_css() {
		$out = '';
		if ( 'sticky' === get_theme_mod( 'main_navigation_sticky', 'static' ) ) {
			$out .= sprintf(
				'@media (min-width: 992px) { .is-sticky-nav .header__logo img { margin-top: %dpx; } }',
				absint( get_theme_mod( 'logo_top_margin_sticky', 24 ) )
			);
		}

		$out .= sprintf(
			'@media (min-width: 992px) { .header__logo img { margin-top: %dpx; } }',
			absint( get_theme_mod( 'logo_top_margin', 12 ) )
		);

		return $out;
	}


	/**
	 * Set main navigation to full width if featured page is not set
	 *
	 * @return string CSS
	 */
	public static function get_navigation_width() {
		$out = '';

		if ( 'none' === get_theme_mod( 'featured_page_select', 'none' ) ) {
			$out = '@media (min-width: 992px) { .header__navigation { width: calc(100% - 18.75rem); } }';
		}

		return $out;
	}


	/**
	 * Page header background image
	 *
	 * @return string CSS
	 */
	public static function get_page_header_bg_image() {
		$out = '';
		$page_header_bg_img            = get_theme_mod( 'page_header_bg_img', '' );
		$page_header_bg_img_repeat     = get_theme_mod( 'page_header_bg_img_repeat', 'repeat' );
		$page_header_bg_img_position_x = get_theme_mod( 'page_header_bg_img_position_x', 'left' );
		$page_header_bg_img_attachment = get_theme_mod( 'page_header_bg_img_attachment', 'scroll' );

		if ( '' != $page_header_bg_img ) {
			$out = '.page-header {';
			$out .= " background-image: url('$page_header_bg_img');";
			$out .= " background-repeat: $page_header_bg_img_repeat;";
			$out .= " background-position: top $page_header_bg_img_position_x;";
			$out .= " background-attachment: $page_header_bg_img_attachment;";
			$out .= '}';
		}

		return $out;
	}


	/**
	 * Outputs the code in head of the every page
	 *
	 * Used by hook: add_action( 'wp_head' , array( $this, 'head_output' ) );
	 */
	public static function head_output() {
		// custom JS from the customizer
		$script = get_theme_mod( 'custom_js_head', '' );

		if ( ! empty( $script ) ) {
			echo PHP_EOL . $script . PHP_EOL;
		}

	}

	/**
	 * Outputs the code in footer of the every page, right before closing </body>
	 *
	 * Used by hook: add_action( 'wp_footer' , array( $this, 'footer_output' ) );
	 */
	public static function footer_output() {
		$script = get_theme_mod( 'custom_js_footer', '' );

		if ( ! empty( $script ) ) {
			echo PHP_EOL . $script . PHP_EOL;
		}
	}
}