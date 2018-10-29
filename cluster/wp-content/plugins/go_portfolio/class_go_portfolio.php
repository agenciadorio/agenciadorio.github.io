<?php
/**
 * Go Portfolio - WordPress Responsive Portfolio 
 *
 * @package   Go Portfolio - WordPress Responsive Portfolio 
 * @author    Granth <granthweb@gmail.com>
 * @link      http://granthweb.com
 * @copyright 2016 Granth
 */

/**
 * Plugin main class
 *
 * @package   Go Portfolio
 * @author    Granth <granthweb@gmail.com>
 */
 
class GW_Go_Portfolio {

	protected static $plugin_version = '1.6.4';
	protected $plugin_slug = 'go-portfolio';
	protected static $plugin_prefix = 'gw_go_portfolio';	
	protected static $instance = null;
	protected $screen_hooks = null;


	/**
	 * Initialize the plugin
	 */
	
	private function __construct() {

		/* Set the constants */
		add_action( 'init', array( $this, 'define_constants' ) );

		/* Load plugin text domain */
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		
		add_action( 'init', array( $this, 'add_thumbnail_support' ) );
		
		/* Load the functions files */
		add_action( 'init', array( $this, 'load_includes' ) );	
		
		/* Plugin version check */
		add_action( 'init',  array( $this, 'plugin_version_check' ) );

		/* Register post types */
		add_action( 'init',  array( $this, 'register_custom_post_types' ) );
		
		/* Register taxonomy for attachment */
		add_action( 'init',  array( $this, 'register_custom_tax' ) );
		
		/* Meta boxes */
		add_action( 'init',  array( $this, 'create_meta_box' ), 9999 );
		
		/* Admin notices */
		add_action( 'admin_notices', array( $this, 'print_admin_notices' ) );
		
		/* Add the options page and menu item */
		add_action( 'admin_menu', array( $this, 'register_menu_pages' ) );

		/* Load admin styles and js */
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		/* Load public styles and js */
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		/* Shortcodes */
		add_shortcode( 'go_portfolio', array( $this, 'go_portfolio_shortcode' ) );
		add_shortcode( 'gopf_meta', array( $this, 'go_portfolio_meta_shortcode' ) );		
		add_shortcode( 'gopf_list_terms', array( $this, 'go_portfolio_list_terms_shortcode' ) );		

		/* Ajax hooks */
		add_action( 'wp_ajax_nopriv_go_portfolio_plugin_menu_page', array( $this, 'ajax_nopriv' ) );
		add_action( 'wp_ajax_go_portfolio_plugin_menu_page', array( $this, 'plugin_menu_page' ) );
		add_action( 'wp_ajax_nopriv_go_portfolio_reset_template_style', array( $this, 'ajax_nopriv' ) );
		add_action( 'wp_ajax_go_portfolio_reset_template_style', array( $this, 'reset_template_style' ) );	
		
		/* Public ajax hooks */
		add_action( 'wp_ajax_nopriv_go_portfolio_ajax_load_portfolio', array( $this, 'ajax_load_portfolio' ) );
		add_action( 'wp_ajax_go_portfolio_ajax_load_portfolio', array( $this, 'ajax_load_portfolio' ) );
								
	}

	/**
	 * Return an instance of this class
	 */
	 
	public static function get_instance() {
		if ( self::$instance == null ) {
			self::$instance = new self;
		}
		
		return self::$instance;
	}
	
	/**
	 * Get plugin prefix
	 */

	public static function plugin_prefix() {
		return  self::$plugin_prefix;
	}

	/**
	 * Fired when the plugin is activated 
	 */
	 
	public static function activate( $network_wide ) {
		
		/* Load template files and save to db */
		$templates = get_option( self::$plugin_prefix . '_templates' );
		if ( !$templates ) {
			$templates = self::load_templates();
			if ( $templates ) { update_option ( self::$plugin_prefix . '_templates', $templates ); }
		}

		/* Load style files and save to db */
		$styles = get_option( self::$plugin_prefix . '_styles' );
		if ( !$styles ) {
			$styles = self::load_styles();
			if ( $styles ) { update_option ( self::$plugin_prefix . '_styles', $styles ); }	
		}
		
		/* Create general settings db data with default values */
		$general_settings = get_option( self::$plugin_prefix . '_general_settings' );
		if ( !$general_settings ) {
			
			/* Set default values */
			$general_settings['responsivity']=1;
			$general_settings['colw-min']='130px';
			$general_settings['colw-max']='';
			$general_settings['size1-min']='768px';
			$general_settings['size1-max']='959px';
			$general_settings['size2-min']='480px';
			$general_settings['size2-max']='767px';
			$general_settings['size3-min']='';
			$general_settings['size3-max']='479px';
			$general_settings['max-width']='400px';
			$general_settings['max-width2']='';
			update_option( self::$plugin_prefix . '_general_settings', $general_settings );
		}
				
		/* Save version info to db and generate static css file */
		delete_option( self::$plugin_prefix . '_version' );	
		$get_plugin_version = self::plugin_version_check();
		if ( !$get_plugin_version ) { 
			update_option ( self::$plugin_prefix . '_version', self::$plugin_version );	
		} 
		
		/* Update notices notices */
		if ( isset( $notices ) ) { self::update_admin_notices ( $notices ); }	
		
		/* Flush rewrite rules */
		global $wp_rewrite;
		$wp_rewrite->flush_rules();
				
	}
	
	
	/**
	 * Fired when the plugin is deactivated 
	 */
	 
	public static function deactivate() {

	}	


	/**
	 * Fired when the plugin is uninstalled
	 */
	 
	public static function uninstall( $network_wide ) {

		/* Delete db data */
		delete_option( self::$plugin_prefix . '_general_settings' );
		delete_option( self::$plugin_prefix . '_cpts' );
		delete_option( self::$plugin_prefix . '_cpts_hash' );
		delete_option( self::$plugin_prefix . '_tax_hash' );		
		delete_option( self::$plugin_prefix . '_portfolios' );
		delete_option( self::$plugin_prefix . '_templates' );				
		delete_option( self::$plugin_prefix . '_styles' );
		delete_option( self::$plugin_prefix . '_version' );	
		delete_option( self::$plugin_prefix . '_notices' );								

		/* Flush rewrite rules */
		global $wp_rewrite;
		$wp_rewrite->flush_rules();

	}


	/**
	 * Define constants
	 */
	 
	public function define_constants() {

		/* Set constant path to the plugin directory */
		define( 'GW_GO_PORTFOLIO_DIR', plugin_dir_path( __FILE__ ) );

		/* Set the constant path to the plugin directory URI */
		define( 'GW_GO_PORTFOLIO_URI', plugin_dir_url( __FILE__ ) );

		/* Set the constant path to the includes directory */
		define( 'GW_GO_PORTFOLIO_INCLUDES', GW_GO_PORTFOLIO_DIR . trailingslashit( 'includes' ) );
		
	}


	/**
	 * Loads the initial files needed by the plugin
	 */
	 
	public function load_includes() {
		
		require_once( GW_GO_PORTFOLIO_INCLUDES . 'functions.php' );
		require_once( GW_GO_PORTFOLIO_INCLUDES . 'class_gw_metabox.php' );

	}
	

	/**
	 * Load the plugin text domain for translation
	 */
	 
	public function load_plugin_textdomain() {
		
		load_plugin_textdomain( 'go_portfolio_textdomain', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	
	}


	/**
	 * Register and enqueue admin styles
	 */
	 
	public function enqueue_admin_styles() {

		if ( ! isset( $this->screen_hooks ) ) { return; }

		$screen = get_current_screen();
		
		if ( in_array( $screen->id, $this->screen_hooks ) ) {
			
			global $wp_version;
			
			/* Load version dependent styles */
			if ( version_compare( $wp_version, 3.5, ">=" ) ) {				
				wp_enqueue_style( 'wp-color-picker' );
			} else {
				wp_enqueue_style( 'farbtastic' );
				wp_enqueue_style( 'thickbox' );				
			}

			/* Load plugin styles */
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', GW_GO_PORTFOLIO_URI . 'admin/css/go_portfolio_admin_styles.css', array(), self::$plugin_version );
			
		}
	}


	/**
	 * Register and enqueue admin js
	 */
	 
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->screen_hooks ) ) { return; }

		$screen = get_current_screen();
		
		if ( in_array( $screen->id, $this->screen_hooks ) ) {
    		
			global $wp_version;
			
			/* Load version dependent scripts */
			if ( version_compare( $wp_version, 3.5, ">=" ) ) {
				wp_enqueue_media();
				wp_enqueue_script( $this->plugin_slug .'-admin-scripts', GW_GO_PORTFOLIO_URI . 'admin/js/go_portfolio_admin_scripts.js', array( 'jquery', 'wp-color-picker' ), self::$plugin_version );
			} else {
				wp_enqueue_script( 'farbtastic' );
				wp_enqueue_script( 'thickbox' );
				wp_enqueue_script( 'media-upload' );
				wp_enqueue_script( $this->plugin_slug .'-admin-scripts', GW_GO_PORTFOLIO_URI . 'admin/js/go_portfolio_admin_scripts.js', array( 'jquery' ), self::$plugin_version );
			}

		}		
	}
	
	 
	/**
	 * Register and enqueue public styles
	 */
	 
	public function enqueue_styles() {
		
		global $post;
		
		$general_settings = get_option( self::$plugin_prefix . '_general_settings' );

		if ( !empty( $general_settings['plugin-pages-rule'] ) &&  $general_settings['plugin-pages'] && !empty( $post ) ) {
		
			$page_ids = $general_settings['plugin-pages'];
		
			if ( !empty( $page_ids ) ) {
				
				$pages =  trim( preg_replace( '/([^0-9][^,]{0})+/', ',', $page_ids ), ',' );
				$pages = explode( ',', $pages );
				
				if ( $general_settings['plugin-pages-rule'] == 'in' && !in_array( $post->ID, $pages ) ) return;
				if ( $general_settings['plugin-pages-rule'] == 'not_in' && in_array( $post->ID, $pages ) ) return;
					
			}
			
		}
		
		wp_enqueue_style( $this->plugin_slug .'-magnific-popup-styles', GW_GO_PORTFOLIO_URI . 'assets/plugins/magnific-popup/magnific-popup.css', array(), self::$plugin_version );

		$zindex_value = isset( $general_settings['lb-zindex'] ) ? floatval( $general_settings['lb-zindex'] ) : '';	
		if ( $zindex_value != '' ) {
			$mfp_css = '.mfp-bg { z-index:' . $zindex_value . ' !important;} .mfp-wrap { z-index:' . ( $zindex_value+1 ) . ' !important;}';
			wp_add_inline_style( $this->plugin_slug .'-magnific-popup-styles', $mfp_css );			
		}
		wp_enqueue_style( $this->plugin_slug .'-styles', GW_GO_PORTFOLIO_URI . 'assets/css/go_portfolio_styles.css', array(), self::$plugin_version );
		if ( isset( $general_settings['responsivity'] ) ) {
		$responsive_css = '@media only screen' . ( isset( $general_settings['size1-min'] ) && $general_settings['size1-min'] != '' ? ' and (min-width: ' . $general_settings['size1-min'] . ')' : '' ) . (
		isset( $general_settings['size1-max'] ) && $general_settings['size1-max'] != '' ? ' and (max-width: ' . $general_settings['size1-max'] . ')' : '' ) . ' {
		.gw-gopf-posts { letter-spacing:10px; }
		.gw-gopf {
			'. ( isset( $general_settings['max-width3'] ) && !empty( $general_settings['max-width3'] ) ? 'max-width:' . floatval( $general_settings['max-width3'] ) . 'px;' : '' ) .'
			margin:0 auto;
		}
		.gw-gopf-1col .gw-gopf-col-wrap { 
        	float:left !important;		
			margin-left:0 !important;
        	width:100%;		
		} 
		.gw-gopf-2cols .gw-gopf-col-wrap,
		.gw-gopf-3cols .gw-gopf-col-wrap,
		.gw-gopf-4cols .gw-gopf-col-wrap,
		.gw-gopf-5cols .gw-gopf-col-wrap,
		.gw-gopf-6cols .gw-gopf-col-wrap,
		.gw-gopf-7cols .gw-gopf-col-wrap,
		.gw-gopf-8cols .gw-gopf-col-wrap,
		.gw-gopf-9cols .gw-gopf-col-wrap,
		.gw-gopf-10cols .gw-gopf-col-wrap { width:50% !important; }		
	}

		@media only screen' . ( isset( $general_settings['size2-min'] ) && $general_settings['size2-min'] != '' ? ' and (min-width: ' . $general_settings['size2-min'] . ')' : '' ) . (
		isset( $general_settings['size2-max'] ) && $general_settings['size2-max'] != '' ? ' and (max-width: ' . $general_settings['size2-max'] . ')' : '' ) . ' {
		.gw-gopf-posts { letter-spacing:20px; }
		.gw-gopf {
			'. ( isset( $general_settings['max-width2'] ) && !empty( $general_settings['max-width2'] ) ? 'max-width:' . floatval( $general_settings['max-width2'] ) . 'px;' : '' ) .'
			margin:0 auto;
		}		
		.gw-gopf-1col .gw-gopf-col-wrap,
		.gw-gopf-2cols .gw-gopf-col-wrap,
		.gw-gopf-3cols .gw-gopf-col-wrap,
		.gw-gopf-4cols .gw-gopf-col-wrap,
		.gw-gopf-5cols .gw-gopf-col-wrap,
		.gw-gopf-6cols .gw-gopf-col-wrap,
		.gw-gopf-7cols .gw-gopf-col-wrap,
		.gw-gopf-8cols .gw-gopf-col-wrap,
		.gw-gopf-9cols .gw-gopf-col-wrap,
		.gw-gopf-10cols .gw-gopf-col-wrap { 
        	float:left !important;		
			margin-left:0 !important;
        	width:100%;
		}

		/* RTL */
		.gw-gopf-rtl.gw-gopf-1col .gw-gopf-col-wrap,
		.gw-gopf-rtl.gw-gopf-2cols .gw-gopf-col-wrap,
		.gw-gopf-rtl.gw-gopf-3cols .gw-gopf-col-wrap,
		.gw-gopf-rtl.gw-gopf-4cols .gw-gopf-col-wrap,
		.gw-gopf-rtl.gw-gopf-5cols .gw-gopf-col-wrap,
		.gw-gopf-rtl.gw-gopf-6cols .gw-gopf-col-wrap,
		.gw-gopf-rtl.gw-gopf-7cols .gw-gopf-col-wrap,
		.gw-gopf-rtl.gw-gopf-8cols .gw-gopf-col-wrap,
		.gw-gopf-rtl.gw-gopf-9cols .gw-gopf-col-wrap,
		.gw-gopf-rtl.gw-gopf-10cols .gw-gopf-col-wrap { float:right !important; }
		
		.gw-gopf-slider-type.gw-gopf-rtl.gw-gopf-1col .gw-gopf-col-wrap,
		.gw-gopf-slider-type.gw-gopf-rtl.gw-gopf-2cols .gw-gopf-col-wrap,
		.gw-gopf-slider-type.gw-gopf-rtl.gw-gopf-3cols .gw-gopf-col-wrap,
		.gw-gopf-slider-type.gw-gopf-rtl.gw-gopf-4cols .gw-gopf-col-wrap,
		.gw-gopf-slider-type.gw-gopf-rtl.gw-gopf-5cols .gw-gopf-col-wrap,
		.gw-gopf-slider-type.gw-gopf-rtl.gw-gopf-6cols .gw-gopf-col-wrap,
		.gw-gopf-slider-type.gw-gopf-rtl.gw-gopf-7cols .gw-gopf-col-wrap,
		.gw-gopf-slider-type.gw-gopf-rtl.gw-gopf-8cols .gw-gopf-col-wrap,
		.gw-gopf-slider-type.gw-gopf-rtl.gw-gopf-9cols .gw-gopf-col-wrap,
		.gw-gopf-slider-type.gw-gopf-rtl.gw-gopf-10cols .gw-gopf-col-wrap { float:left !important; }
		
	}
	
	
		@media only screen' . ( isset( $general_settings['size3-min'] ) && $general_settings['size3-min'] != '' ? ' and (min-width: ' . $general_settings['size3-min'] . ')' : '' ) . (
		isset( $general_settings['size3-max'] ) && $general_settings['size3-max'] != '' ? ' and (max-width: ' . $general_settings['size3-max'] . ')' : '' ) . ' {
		.gw-gopf-posts { letter-spacing:30px; }
		.gw-gopf {
			'. ( isset( $general_settings['max-width'] ) && !empty( $general_settings['max-width'] ) ? 'max-width:' . floatval( $general_settings['max-width'] ) . 'px;' : '' ) .'
			margin:0 auto;
		}
		.gw-gopf-1col .gw-gopf-col-wrap,
		.gw-gopf-2cols .gw-gopf-col-wrap,
		.gw-gopf-3cols .gw-gopf-col-wrap,
		.gw-gopf-4cols .gw-gopf-col-wrap,
		.gw-gopf-5cols .gw-gopf-col-wrap,
		.gw-gopf-6cols .gw-gopf-col-wrap,
		.gw-gopf-7cols .gw-gopf-col-wrap,
		.gw-gopf-8cols .gw-gopf-col-wrap,
		.gw-gopf-9cols .gw-gopf-col-wrap,
		.gw-gopf-10cols .gw-gopf-col-wrap {
        	margin-left:0 !important;
        	float:left !important;
        	width:100%;
         }
		 
		/* RTL */
		.gw-gopf-rtl.gw-gopf-1col .gw-gopf-col-wrap,
		.gw-gopf-rtl.gw-gopf-2cols .gw-gopf-col-wrap,
		.gw-gopf-rtl.gw-gopf-3cols .gw-gopf-col-wrap,
		.gw-gopf-rtl.gw-gopf-4cols .gw-gopf-col-wrap,
		.gw-gopf-rtl.gw-gopf-5cols .gw-gopf-col-wrap,
		.gw-gopf-rtl.gw-gopf-6cols .gw-gopf-col-wrap,
		.gw-gopf-rtl.gw-gopf-7cols .gw-gopf-col-wrap,
		.gw-gopf-rtl.gw-gopf-8cols .gw-gopf-col-wrap,
		.gw-gopf-rtl.gw-gopf-9cols .gw-gopf-col-wrap,
		.gw-gopf-rtl.gw-gopf-10cols .gw-gopf-col-wrap { float:right !important; }
		
		.gw-gopf-slider-type.gw-gopf-rtl.gw-gopf-1col .gw-gopf-col-wrap,
		.gw-gopf-slider-type.gw-gopf-rtl.gw-gopf-2cols .gw-gopf-col-wrap,
		.gw-gopf-slider-type.gw-gopf-rtl.gw-gopf-3cols .gw-gopf-col-wrap,
		.gw-gopf-slider-type.gw-gopf-rtl.gw-gopf-4cols .gw-gopf-col-wrap,
		.gw-gopf-slider-type.gw-gopf-rtl.gw-gopf-5cols .gw-gopf-col-wrap,
		.gw-gopf-slider-type.gw-gopf-rtl.gw-gopf-6cols .gw-gopf-col-wrap,
		.gw-gopf-slider-type.gw-gopf-rtl.gw-gopf-7cols .gw-gopf-col-wrap,
		.gw-gopf-slider-type.gw-gopf-rtl.gw-gopf-8cols .gw-gopf-col-wrap,
		.gw-gopf-slider-type.gw-gopf-rtl.gw-gopf-9cols .gw-gopf-col-wrap,
		.gw-gopf-slider-type.gw-gopf-rtl.gw-gopf-10cols .gw-gopf-col-wrap { float:left !important; }		
		 
	}';
	
			wp_add_inline_style( $this->plugin_slug .'-styles', $responsive_css );			
		}
		
		$font_import = '';
		if ( isset( $general_settings['primary-font-css'] ) && !empty( $general_settings['primary-font-css'] ) ) { $font_import = '@import url(' . $general_settings['primary-font-css'] . ');'; }
		if ( isset( $general_settings['secondary-font-css'] ) && !empty( $general_settings['secondary-font-css'] ) ) { $font_import = '@import url(' . $general_settings['secondary-font-css'] . ');'; }
		if ( !empty( $font_import ) ) wp_add_inline_style( $this->plugin_slug .'-styles', $font_import );	
				
	}


	/**
	 * Register and enqueues public js
	 */
	 
	public function enqueue_scripts() {
		
		global $post;
		
		$general_settings = get_option( self::$plugin_prefix . '_general_settings' );
		
		if ( !empty( $general_settings['plugin-pages-rule'] ) &&  $general_settings['plugin-pages'] && !empty( $post ) ) {
		
			$page_ids = $general_settings['plugin-pages'];
		
			if ( !empty( $page_ids ) ) {
				
				$pages =  trim( preg_replace( '/([^0-9][^,]{0})+/', ',', $page_ids ), ',' );
				$pages = explode( ',', $pages );
				if ( $general_settings['plugin-pages-rule'] == 'in' && !in_array( $post->ID, $pages ) ) return;
				if ( $general_settings['plugin-pages-rule'] == 'not_in' && in_array( $post->ID, $pages ) ) return;
					
			}
			
		}
		
		$in_footer = empty( $general_settings['js-in-header'] );

		$general_settings = get_option( self::$plugin_prefix . '_general_settings' );
		wp_enqueue_script( $this->plugin_slug . '-magnific-popup-script', plugins_url( 'assets/plugins/magnific-popup/jquery.magnific-popup.min.js', __FILE__ ), array( 'jquery' ), self::$plugin_version, $in_footer );
		wp_enqueue_script( $this->plugin_slug . '-isotope-script', plugins_url( 'assets/plugins/jquery.isotope.min.js', __FILE__ ), array( 'jquery' ), self::$plugin_version, $in_footer );
		wp_enqueue_script( $this->plugin_slug . '-caroufredsel-script', plugins_url( 'assets/plugins/jquery.carouFredSel-6.2.1-packed.js', __FILE__ ), array( 'jquery' ), self::$plugin_version, $in_footer );
		wp_enqueue_script( $this->plugin_slug . '-touchswipe-script', plugins_url( 'assets/plugins/jquery.touchSwipe.min.js', __FILE__ ), array( 'jquery' ), self::$plugin_version, $in_footer );				
		wp_enqueue_script( $this->plugin_slug . '-script', plugins_url( 'assets/js/go_portfolio_scripts.js', __FILE__ ), array( 'jquery' ), self::$plugin_version, $in_footer );
		wp_localize_script( $this->plugin_slug . '-script', self::$plugin_prefix . '_settings', array( 
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'mobileTransition' => isset( $general_settings['disable-mobile-trans'] ) ? 'disabled': 'enabled'
			) 
		);  
	}

	/**
	 * Plugin version check
	 */
	 
	public static function plugin_version_check() {
		/* Save version info to db and generate static css file */
		$saved_version = get_option( self::$plugin_prefix . '_version' );
		if ( $saved_version ) {
			if ( version_compare( $saved_version, self::$plugin_version, "!=" ) ) {
				update_option ( self::$plugin_prefix . '_version', self::$plugin_version );
				$templates = self::load_templates();
				if ( $templates ) { update_option ( self::$plugin_prefix . '_templates', $templates ); }
				$styles = self::load_styles();
				if ( $styles ) { update_option ( self::$plugin_prefix . '_styles', $styles ); }
				$notices[] = array ( 
					'success' => true,
					'permanent' => false,
					'message' => sprintf( __( 'Go Portfolio - WordPress Responsive Portfolio  plugin has been updated! Current version: %1$s', 'go_portfolio_textdomain' ), self::$plugin_version )
				);
				if ( version_compare( $saved_version, '1.4.0', "<" ) ) {
					$notices[] = array ( 
						'success' => true,
						'permanent' => false,
						'message' => sprintf( __( 'Please read <a href="%1$s" target="_blank">this</a> the plugin update information carefully!', 'go_portfolio_textdomain' ), 'http://www.go-plugins.com/portfolio/update-info/' )
					);					
				}
				
				if ( isset( $notices ) ) { self::update_admin_notices ( $notices ); }					
			}
			return self::$plugin_version;
		} else {
			return false;	
		}
	}

	/**
	 * Add thumnbnail support for all post types
	 */

	public function add_thumbnail_support() {
		global $_wp_theme_features;
		$_wp_theme_features['post-thumbnails'] = true;
	}

	/**
	 * Register the administration menus for this plugin
	 */
	 
	public function register_menu_pages() {

		$general_settings = get_option( self::$plugin_prefix . '_general_settings' );
		$capability = isset( $general_settings['capability'] ) ? $general_settings['capability'] : 'manage_options';		

		/* Main menu page */
		$this->screen_hooks[] = add_menu_page( 
			__( 'Go Portfolio', 'go_portfolio_textdomain' ),
			__( 'Go Portfolio', 'go_portfolio_textdomain' ), 
			$capability, 
			$this->plugin_slug, 
			array( $this, 'plugin_menu_page' ), 
			GW_GO_PORTFOLIO_URI . 'admin/images/icon_wp_nav.png'
		);
		
		/* Submenu page - Custom Post Types */
		$this->screen_hooks[] = add_submenu_page( 
			$this->plugin_slug,
			__( 'Custom Post Types', 'go_portfolio_textdomain' ) . ' | ' . __( 'Go Portfolio', 'go_portfolio_textdomain' ),
			__( 'Custom Post Types', 'go_portfolio_textdomain' ),
			$capability,
			$this->plugin_slug . '-custom-post-types', 
			array( $this, 'plugin_submenu_page_ctps' )
		);

		/* Submenu page - General Settings */
		$this->screen_hooks[] = add_submenu_page(
			$this->plugin_slug,
			__( 'General Settings', 'go_portfolio_textdomain' ) . ' | ' . __( 'Go Portfolio', 'go_portfolio_textdomain' ),
			__( 'General Settings', 'go_portfolio_textdomain' ),
			$capability,
			$this->plugin_slug . '-settings',
			array( $this, 'plugin_submenu_page_general_settings' )
		);
		
		/* Submenu page - Template & Style Editor */
		$this->screen_hooks[] = add_submenu_page(
			$this->plugin_slug,
			__( 'Template & Style Editor', 'go_portfolio_textdomain' ) . ' | ' . __( 'Go Portfolio', 'go_portfolio_textdomain' ),
			__( 'Template & Style Editor', 'go_portfolio_textdomain' ),
			$capability,
			$this->plugin_slug . '-editor',
			array( $this, 'plugin_submenu_page_editor' )
		);	

		/* Submenu page - Import & Export */
		$this->screen_hooks[] = add_submenu_page(
			$this->plugin_slug,
			__( 'Import & Export', 'go_portfolio_textdomain' ) . ' | ' . __( 'Go Portfolio', 'go_portfolio_textdomain' ),
			__( 'Import & Export', 'go_portfolio_textdomain' ),
			$capability,
			$this->plugin_slug . '-import-export',
			array( $this, 'plugin_submenu_page_import_export' )
		);		

	}


	/**
	 * Main menu page
	 */
	 
	public function plugin_menu_page() {
		include_once( GW_GO_PORTFOLIO_INCLUDES. 'menu_page.php' );
	}

	
	/**
	 * Submenu page for Custom Post Types
	 */
	 
	public function plugin_submenu_page_ctps() {
		include_once( GW_GO_PORTFOLIO_INCLUDES. 'submenu_page_ctps.php' );
	}

	
	/**
	 * Submenu page for General settings
	 */
	
	public function plugin_submenu_page_general_settings() {
		include_once( GW_GO_PORTFOLIO_INCLUDES. 'submenu_page_general_settings.php' );
	}

	
	/**
	 * Submenu page for Template & Style Editor
	 */
	
	public function plugin_submenu_page_editor() {
		include_once( GW_GO_PORTFOLIO_INCLUDES. 'submenu_page_editor.php' );
	}	


	/**
	 * Submenu page for Import & Export
	 */
	
	public function plugin_submenu_page_import_export() {
		include_once( GW_GO_PORTFOLIO_INCLUDES. 'submenu_page_import_export.php' );
	}


	/**
	 * Print admin notices
	 */
	 	
	public function print_admin_notices() {

		$new_current_notices = $current_notices = get_option( self::$plugin_prefix . '_notices', array() ); 
		if ( $current_notices && !empty ( $current_notices ) ) {
			foreach ( $current_notices as $nkey => $current_notice ) {
				$output='<div class="' . ( isset( $current_notice['success'] ) && $current_notice['success'] == true ? 'updated' : 'error' ) . '">';
				$output.='<p><strong>' . ( isset( $current_notice['message'] ) ? $current_notice['message'] : '' ) . '</strong></p>';
				$output.='</div>';
				echo $output;
				if ( isset( $current_notice['permanent'] ) && $current_notice['permanent'] == false ) {
					unset( $new_current_notices[$nkey] );
				}
			}	
		}
		
		if ( $new_current_notices != $current_notices ) {
			update_option ( self::$plugin_prefix . '_notices', $new_current_notices );  
		}
	}	


	/**
	 * Update admin notices
	 */
	 
	public static function update_admin_notices( $notices = array() ) {

		if ( $notices && is_array( $notices ) && !empty( $notices ) ) {
			$current_notices = get_option( self::$plugin_prefix . '_notices', array() ); 
			$new_current_notices = array_merge( $notices, $current_notices );
			if ( $new_current_notices != $current_notices ) {
				update_option ( self::$plugin_prefix . '_notices', $new_current_notices );  
			}
		}
		
	}


	/**
	 * Generate inline styles
	 */

	public static function generate_inline_styles( $portfolio ) {
		include( GW_GO_PORTFOLIO_INCLUDES . 'generate_inline_style.php' );
	}	


	/**
	 * Load templates from files
	 */
	
	public static function load_templates( $template_file=null ) {
		
		/* Get param if set - read one certain json file or all files */
		$template_file = $template_file ? $template_file : '*.json';

		/* Read template files */
		$directory = plugin_dir_path( __FILE__ ) . 'templates/templates/';

		if ( is_dir( $directory ) ) {
		
			$file_list = array();
			$file_list = glob( $directory . $template_file );
			
			if (!$file_list) {

				if ( $dh = opendir( $directory ) ) {
					while ( ( $file = readdir( $dh ) ) !== false ) {
						if ( filetype( $directory . $file ) == 'file' ) {
							if ( $template_file == '*.json' ) {
								$fileinfo = pathinfo( $file );
								if ( $fileinfo['extension'] == 'json' ) {
									$file_list[] = $directory . $file;
								}
							} elseif ( $template_file == $file ) {
								$file_list[] = $directory . $file;
							}
						}
					}
					closedir($dh);
				}
			}
			
			if ( is_array( $file_list ) && !empty( $file_list ) ) {
				sort( $file_list );

				foreach ( $file_list as $filename ) {
					$json_data = json_decode( file_get_contents( $filename ) ) ? json_decode( file_get_contents( $filename ) ) : null;
					if ( $json_data ) {
						$templates[$json_data->id] = array (
							'name' => $json_data->name,
							'description' => $json_data->name,
							'json_file'	=> basename( $filename ),
							'tpl_file' => $json_data->tpl_file
						);
					}
	
					if ( file_exists( $directory . $json_data->tpl_file ) && is_file( $directory . $json_data->tpl_file ) ) {
						$data = file_get_contents ( $directory . $json_data->tpl_file );
						$templates[$json_data->id]['data'] = $data;
					}
				}				
			} 
			
		}
		
		return isset( $templates ) ? $templates : null;
	}


	/**
	 * Load styles from files
	 */
	
	public static function load_styles( $style_file=null ) {
		
		/* Get param if set - read one certain json file or all files */
		$style_file = $style_file ? $style_file : '*.json';

		/* Read style files */
		$directory = plugin_dir_path( __FILE__ ) . 'templates/styles/';

		if ( is_dir( $directory ) ) {

			$file_list = array();
			$file_list = glob( $directory . $style_file );
			
			if (!$file_list) {
				if ( $dh = opendir( $directory ) ) {
					while ( ( $file = readdir( $dh ) ) !== false ) {
						if ( filetype( $directory . $file ) == 'file' ) {
							if ( $style_file == '*.json' ) {
								$fileinfo = pathinfo( $file );
								if ( $fileinfo['extension'] == 'json' ) {
									$file_list[] = $directory . $file;
								}
							} elseif ( $style_file == $file ) {
								$file_list[] = $directory . $file;
							}
						}
					}
					closedir($dh);
				}
			}

			if ( is_array( $file_list ) && !empty( $file_list ) ) {
				sort( $file_list );			
			
				foreach ( $file_list as $filename ) {
					$json_data = json_decode( file_get_contents( $filename ) ) ? json_decode( file_get_contents( $filename ) ) : null;
					if ( $json_data ) {
						$styles[$json_data->id] = array (
							'name' => $json_data->name,
							'description' => $json_data->name,
							'json_file'	=> basename( $filename ),
							'css_file' => $json_data->css_file,
							'class' => $json_data->class
						);
	
						if ( file_exists( $directory . $json_data->css_file ) && is_file( $directory . $json_data->css_file ) ) {
							$data = file_get_contents ( $directory . $json_data->css_file );
							$styles[$json_data->id]['data'] = $data;
						};		
						
						if ( isset($json_data->effects) && is_array( $json_data->effects ) ) {
							foreach ( $json_data->effects as $effect ) {
								$styles[$json_data->id]['effects'][$effect->id] = $effect->name;
							}
						}
					}
				}
			
			}
		}
		
		return isset( $styles ) ? $styles : null;		
	}


	/**
	 * General AJAX callback function for users that are not logged in
	 */
	 
	public function ajax_nopriv() {
		die ( __( 'Oops, authorized persons only!', 'go_portfolio_textdomain' ) );
	}


	/**
	 * Reset a template or a style via AJAX
	 */
	 
	public function reset_template_style() {
		
		/* Reset a template */
		$template = isset( $_GET['template'] ) ? $_GET['template'] : null;
		if ( $template ) {		
			$templates = get_option( self::$plugin_prefix . '_templates' );
			if ( isset( $templates[$template] ) ) {
				print_r( $templates[$template]['data'] );
				exit;
			}
		}
		
		/* Reset a style */
		$style = isset( $_GET['style'] ) ? $_GET['style'] : null;
		if ( $style ) {		
			$styles = get_option( self::$plugin_prefix . '_styles' );
			if ( isset( $styles[$style] ) ) {
				print_r( $styles[$style]['data'] );
				exit;
			}
		}		
		exit;
	}


	/**
	 * Register custom post types
	 */
	 
	public function register_custom_post_types() {

		/* Get custom post types from db */
		$custom_post_types = get_option( self::$plugin_prefix . '_cpts' );
		$cpts_hash = get_option( self::$plugin_prefix . '_cpts_hash' );
		$new_cpts_hash = '';
	
		/* Register cpts & custom taxonomy if enabled */
		if ( function_exists( 'register_post_type' ) && function_exists( 'register_taxonomy' ) ) { 
			if ( isset( $custom_post_types ) && !empty( $custom_post_types ) ) {
				foreach ( $custom_post_types as $custom_post_type ) {
	
					$cpt_labels = array(
						'name' => $custom_post_type['name'],
						'singular_name' => $custom_post_type['singular_name'],
						'add_new' => __( 'Add New', 'go_portfolio_textdomain' ),
						'add_new_item' => sprintf( __( 'Add New %s', 'go_portfolio_textdomain' ), $custom_post_type['singular_name'] ),
						'edit_item' => sprintf( __( 'Edit %s', 'go_portfolio_textdomain' ), $custom_post_type['singular_name'] ),
						'new_item' => sprintf( __( 'New %s', 'go_portfolio_textdomain' ), $custom_post_type['singular_name'] ),
						'all_items' => sprintf( __( 'All %s', 'go_portfolio_textdomain' ), $custom_post_type['name'] ),
						'view_item' => sprintf( __( 'View %s', 'go_portfolio_textdomain' ), $custom_post_type['singular_name'] ),
						'search_items' => sprintf( __( 'Search %s', 'go_portfolio_textdomain' ), $custom_post_type['name'] ),
						'not_found' =>  sprintf( __( 'No %s found', 'go_portfolio_textdomain' ), $custom_post_type['name'] ),
						'not_found_in_trash' => sprintf( __( 'No %s found in Trash', 'go_portfolio_textdomain' ), $custom_post_type['name'] ), 
						'parent_item_colon' => '',
						'menu_name' => $custom_post_type['name']
					  );
					
					$cpt_args = array(
						'labels' 			=> $cpt_labels,
						'hierarchical'      => false,
						'public' 			=> true,
						'has_archive' 		=> isset ( $custom_post_type['has-archive'] ) ? true : false,
						'exclude_from_search' => isset ( $custom_post_type['search-exclude'] ) ? true : false,
						'supports' 			=> array( 'title', 'editor', 'thumbnail', 'custom-fields','comments','page-attributes', 'excerpt', 'revisions' ),
						'menu_icon'			=> GW_GO_PORTFOLIO_URI . 'admin/images/icon_wp_nav.png'
					);
				
					$ctax_cat_labels = array(
						'name'              => sprintf( __( '%s Categories', 'go_portfolio_textdomain' ), $custom_post_type['singular_name'] ),
						'singular_name' 	=> sprintf( __( '%s Category', 'go_portfolio_textdomain' ), $custom_post_type['singular_name'] ),
						'search_items'      => sprintf( __( 'Search %s Categories', 'go_portfolio_textdomain' ), $custom_post_type['singular_name'] ),
						'all_items'         => sprintf( __( 'All %s Categories', 'go_portfolio_textdomain' ), $custom_post_type['singular_name'] ),
						'parent_item'       => sprintf( __( 'Parent %s Category', 'go_portfolio_textdomain' ), $custom_post_type['singular_name'] ),
						'parent_item_colon' => sprintf( __( 'Parent %s Category:', 'go_portfolio_textdomain' ), $custom_post_type['singular_name'] ),
						'edit_item'         => sprintf( __( 'Edit %s Category', 'go_portfolio_textdomain' ), $custom_post_type['singular_name'] ),
						'update_item'       => sprintf( __( 'Update %s Category', 'go_portfolio_textdomain' ), $custom_post_type['singular_name'] ),
						'add_new_item'      => sprintf( __( 'Add New %s Category', 'go_portfolio_textdomain' ), $custom_post_type['singular_name'] ),
						'new_item_name'     => sprintf( __( 'New %s Category Name', 'go_portfolio_textdomain' ), $custom_post_type['singular_name'] ),
						'menu_name'         => sprintf( __( '%s Category', 'go_portfolio_textdomain' ), $custom_post_type['singular_name'] )
					);
					
					$ctax_cat_args = array(
						'labels' 			=> $ctax_cat_labels,
						'hierarchical'      => true,
						'public' 			=> true,
						'query_var'         => true,
						'update_count_callback' => '_update_post_term_count'
					);
					
					$ctax_tag_labels = array(
						'name'              => sprintf( __( '%s Tags', 'go_portfolio_textdomain' ), $custom_post_type['singular_name'] ),
						'singular_name' 	=> sprintf( __( '%s Tag', 'go_portfolio_textdomain' ), $custom_post_type['singular_name'] ),
						'search_items'      => sprintf( __( 'Search %s Tags', 'go_portfolio_textdomain' ), $custom_post_type['singular_name'] ),
						'all_items'         => sprintf( __( 'All %s Tags', 'go_portfolio_textdomain' ), $custom_post_type['singular_name'] ),
						'parent_item'       => sprintf( __( 'Parent %s Tag', 'go_portfolio_textdomain' ), $custom_post_type['singular_name'] ),
						'parent_item_colon' => sprintf( __( 'Parent %s Tag:', 'go_portfolio_textdomain' ), $custom_post_type['singular_name'] ),
						'edit_item'         => sprintf( __( 'Edit %s Tag', 'go_portfolio_textdomain' ), $custom_post_type['singular_name'] ),
						'update_item'       => sprintf( __( 'Update %s Tag', 'go_portfolio_textdomain' ), $custom_post_type['singular_name'] ),
						'add_new_item'      => sprintf( __( 'Add New %s Tag', 'go_portfolio_textdomain' ), $custom_post_type['singular_name'] ),
						'new_item_name'     => sprintf( __( 'New %s Tag Name', 'go_portfolio_textdomain' ), $custom_post_type['singular_name'] ),
						'menu_name'         => sprintf( __( '%s Tag', 'go_portfolio_textdomain' ), $custom_post_type['singular_name'] )
					);
					
					$ctax_tag_args = array(
						'labels' 			=> $ctax_tag_labels,
						'hierarchical'      => false,
						'public' 			=> true,
						'query_var'         => true,
						'update_count_callback' => '_update_post_term_count'
					);				
									
					if ( isset( $custom_post_type['enabled'] ) ) { 
						register_post_type( $custom_post_type['slug'], $cpt_args );
						
						/* Register taxonomies */
						
						if ( isset( $custom_post_type['custom-tax-cat'] ) || isset( $custom_post_type['custom-tax-tag'] ) ) { 

							$all_tax = get_taxonomies(); 
							
							/* Check if taxonomy is already registered */
							if ( isset( $all_tax ) && is_array( $all_tax ) ) {
								
								/* Register category */
								if ( isset( $custom_post_type['custom-tax-cat'] ) && !in_array( $custom_post_type['slug'] . '-cat', $all_tax ) ) {
									register_taxonomy( $custom_post_type['slug'] . '-cat', array( $custom_post_type['slug'] ), $ctax_cat_args );
								}
		
								/* Register tag */
								if ( isset( $custom_post_type['custom-tax-tag'] ) && !in_array( $custom_post_type['slug'] . '-tag', $all_tax ) ) {
									register_taxonomy( $custom_post_type['slug'] . '-tag',  array( $custom_post_type['slug'] ), $ctax_tag_args );
								}					
							}
							
						}
						
						/* Register additional taxnomy for the cpt */
						if ( isset( $custom_post_type['tax'] ) && !empty( $custom_post_type['tax'] ) ) {
							foreach( $custom_post_type['tax'] as $add_tax ) {
								register_taxonomy_for_object_type( $add_tax, $custom_post_type['slug'] );
							}
						}
						
						apply_filters( 'manage_edit-'.$custom_post_type['slug'].'_columns', array ( $this, 'cpt_edit_columns' ), '' );
						add_filter( 'manage_edit-'.$custom_post_type['slug'].'_columns', array ( $this, 'cpt_edit_columns' ) );
						
						add_action( 'manage_'.$custom_post_type['slug'].'_posts_custom_column',  array ( $this, 'cpt_custom_columns' ) );
					}
	
					/* Create hash from slugs */
					$new_cpts_hash .= $custom_post_type['slug'];
				}
				
				/* Do flush rewrite if cpts has benn changed */
				$new_cpts_hash = md5( $new_cpts_hash );
				
				if ( !$cpts_hash || $cpts_hash != $new_cpts_hash ) {
					update_option( self::$plugin_prefix . '_cpts_hash', $new_cpts_hash );
					global $wp_rewrite;
					$wp_rewrite->flush_rules();
				}
			}
		}
	
	}


	/**
	 * Register taxonomy for media
	 */
	 
	public function register_custom_tax() {

		$tax_hash = get_option( self::$plugin_prefix . '_tax_hash' );
		$general_settings = get_option( self::$plugin_prefix . '_general_settings' );
		
		if ( isset( $general_settings['enable_post_type']['attachment'] ) && function_exists( 'register_post_type' ) && function_exists( 'register_taxonomy' ) ) { 
			$ctax_cat_labels = array(
				'name'              => sprintf( __( '%s Categories', 'go_portfolio_textdomain' ), __( 'Media', 'go_portfolio_textdomain' ) ),
				'singular_name' 	=> sprintf( __( '%s Category', 'go_portfolio_textdomain' ), __( 'Media', 'go_portfolio_textdomain' ) ),
				'search_items'      => sprintf( __( 'Search %s Categories', 'go_portfolio_textdomain' ), __( 'Media', 'go_portfolio_textdomain' ) ),
				'all_items'         => sprintf( __( 'All %s Categories', 'go_portfolio_textdomain' ), __( 'Media', 'go_portfolio_textdomain' ) ),
				'parent_item'       => sprintf( __( 'Parent %s Category', 'go_portfolio_textdomain' ), __( 'Media', 'go_portfolio_textdomain' ) ),
				'parent_item_colon' => sprintf( __( 'Parent %s Category:', 'go_portfolio_textdomain' ), __( 'Media', 'go_portfolio_textdomain' ) ),
				'edit_item'         => sprintf( __( 'Edit %s Category', 'go_portfolio_textdomain' ), __( 'Media', 'go_portfolio_textdomain' ) ),
				'update_item'       => sprintf( __( 'Update %s Category', 'go_portfolio_textdomain' ), __( 'Media', 'go_portfolio_textdomain' ) ),
				'add_new_item'      => sprintf( __( 'Add New %s Category', 'go_portfolio_textdomain' ), __( 'Media', 'go_portfolio_textdomain' ) ),
				'new_item_name'     => sprintf( __( 'New %s Category Name', 'go_portfolio_textdomain' ), __( 'Media', 'go_portfolio_textdomain' ) ),
				'menu_name'         => sprintf( __( '%s Category', 'go_portfolio_textdomain' ), __( 'Media', 'go_portfolio_textdomain' ) )
			);
			
			$ctax_cat_args = array(
				'labels' 			=> $ctax_cat_labels,
				'hierarchical'      => true,
				'public' 			=> true,
				'query_var'         => true,
				'update_count_callback' => '_update_generic_term_count'
			);
			
			$ctax_tag_labels = array(
				'name'              => sprintf( __( '%s Tags', 'go_portfolio_textdomain' ), __( 'Media', 'go_portfolio_textdomain' ) ),
				'singular_name' 	=> sprintf( __( '%s Tag', 'go_portfolio_textdomain' ), __( 'Media', 'go_portfolio_textdomain' ) ),
				'search_items'      => sprintf( __( 'Search %s Tags', 'go_portfolio_textdomain' ), __( 'Media', 'go_portfolio_textdomain' ) ),
				'all_items'         => sprintf( __( 'All %s Tags', 'go_portfolio_textdomain' ), __( 'Media', 'go_portfolio_textdomain' ) ),
				'parent_item'       => sprintf( __( 'Parent %s Tag', 'go_portfolio_textdomain' ), __( 'Media', 'go_portfolio_textdomain' ) ),
				'parent_item_colon' => sprintf( __( 'Parent %s Tag:', 'go_portfolio_textdomain' ), __( 'Media', 'go_portfolio_textdomain' ) ),
				'edit_item'         => sprintf( __( 'Edit %s Tag', 'go_portfolio_textdomain' ), __( 'Media', 'go_portfolio_textdomain' ) ),
				'update_item'       => sprintf( __( 'Update %s Tag', 'go_portfolio_textdomain' ), __( 'Media', 'go_portfolio_textdomain' ) ),
				'add_new_item'      => sprintf( __( 'Add New %s Tag', 'go_portfolio_textdomain' ), __( 'Media', 'go_portfolio_textdomain' ) ),
				'new_item_name'     => sprintf( __( 'New %s Tag Name', 'go_portfolio_textdomain' ), __( 'Media', 'go_portfolio_textdomain' ) ),
				'menu_name'         => sprintf( __( '%s Tag', 'go_portfolio_textdomain' ), __( 'Media', 'go_portfolio_textdomain' ) )
			);
			
			$ctax_tag_args = array(
				'labels' 			=> $ctax_tag_labels,
				'hierarchical'      => false,
				'public' 			=> true,
				'query_var'         => true,
				'update_count_callback' => '_update_generic_term_count'
			);				

			/* Check if taxonomy is already registered */
			$all_tax = get_taxonomies(); 
			if ( isset( $all_tax ) && is_array( $all_tax ) ) {
				
				/* Register category */
				if ( !in_array( 'media-cat', $all_tax ) ) {
					register_taxonomy( 'media-cat', array( 'attachment' ), $ctax_cat_args );
				}
	
				/* Register tag */
				if ( !in_array( 'media-tag', $all_tax ) ) {
					register_taxonomy( 'media-tag',  array( 'attachment' ), $ctax_tag_args );
				}					
			}
			
			/* Do flush rewrite if tax has benn changed */
			if ( !$tax_hash ) {
				update_option( self::$plugin_prefix . '_tax_hash', 'attachment' );
				global $wp_rewrite;
				$wp_rewrite->flush_rules();
			}			
			
		}
				
	}


	/**
	 * Colum header settings for custom post types
	 */
	
	public function cpt_edit_columns( $columns ) { 
		$columns = array( 
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Title', 'go_portfolio_textdomain' ),
			'featured_image' => __( 'Featured Image', 'go_portfolio_textdomain' ),
			'author' => __( 'Author', 'go_portfolio_textdomain' ),
			'description' => __( 'Description', 'go_portfolio_textdomain' ),
			'cat' => __( 'Categories', 'go_portfolio_textdomain' ),
			'tag' => __( 'Tags', 'go_portfolio_textdomain' ),
			'tax' => __( 'Other Taxonomies', 'go_portfolio_textdomain' ),
			'date' => __( 'Date', 'go_portfolio_textdomain' ),
			'comments' => '<div title="Comments" class="comment-grey-bubble"></div>'				
		); 
		
		return $columns;  
	}


	/**
	 * Column settings for custom post types
	 */
	
	public function cpt_custom_columns( $column ) { 
		global $post;
		$screen = get_current_screen();
		$current_page = isset( $screen->parent_file ) ? $screen->parent_file : null;
		$external_tax = array();
		$taxonomies = get_object_taxonomies( $post->post_type, 'objects' );
		if ( !empty( $taxonomies ) ) {
			foreach( $taxonomies as $tax_key => $taxonomy ) {

				/* Get categories */
				if ( preg_match('/'.$post->post_type.'-cat$/', $tax_key ) ) {

					$cat_terms = get_the_terms( $post->ID, $tax_key );

					if ( isset( $cat_terms ) && !empty( $cat_terms ) ) {
						foreach( $cat_terms as $cat_term_key => $cat_term ) {
							if ( $current_page ) {
								$cat_term_list[]='<a href="' . $current_page . '&' . $taxonomy->query_var . '=' . $cat_term->slug . '">' . $cat_term->name . '</a>';
							};
							
						}
					}

					$cat_list = isset ( $cat_term_list ) && !empty( $cat_term_list ) ? implode(', ', $cat_term_list ) : '';
				}
				
				/* Get tags */			
				if ( preg_match('/'.$post->post_type.'-tag$/', $tax_key ) ) {
					
					$tag_terms = get_the_terms( $post->ID, $tax_key );

					if ( isset( $tag_terms ) && !empty( $tag_terms ) ) {
						foreach( $tag_terms as $tag_term_key => $tag_term ) {
							if ( $current_page ) {
								$tag_term_list[]='<a href="' . $current_page . '&' . $taxonomy->query_var . '=' . $tag_term->slug . '">' . $tag_term->name . '</a>';
							};
							
						}
					}

					$tag_list = isset ( $tag_term_list ) && !empty( $tag_term_list ) ? implode(', ', $tag_term_list ) : '';
					
				}
				
				if ( !preg_match('/'.$post->post_type.'-tag$/', $tax_key ) && !preg_match('/'.$post->post_type.'-cat$/', $tax_key ) ) {
					$external_tax[$tax_key]=$taxonomy;
				}
			}
			
			if ( isset( $external_tax ) && !empty( $external_tax ) ) {
				foreach( $external_tax as $etax_key => $etax ) {
					
					$tax_terms = get_the_terms( $post->ID, $etax_key );
					
					if ( isset( $tax_terms ) && !empty( $tax_terms ) ) {
						foreach( $tax_terms as $tax_term_key => $tax_term ) {
							if ( $current_page ) {
								$tax_term_list[]='<a href="' . $current_page . '&' . $etax->query_var . '=' . $tax_term->slug . '">' . $tax_term->name . '</a>';
							};
							
						}
					}
					
					$tax_list = isset ( $tax_term_list ) && !empty( $tax_term_list ) ? implode(', ', $tax_term_list ) : '';					
					$tax_term_list=array();
					
					$term_list_by_tax[]=array(
						'label' => $etax->labels->name,
						'terms' => $tax_list
					);
					
				}
			}

		}
	
		switch ( $column ) {
	
			case 'description': 
				$content = $post->post_content; 
				$content = apply_filters( 'get_the_excerpt', '', 12 );
				$content = apply_filters( 'the_content', $content );
				echo $content;
				break;
			case 'featured_image': 
				echo get_the_post_thumbnail( $post->ID, array( 50, 50 ) ); 
				break;
			case 'author' :
			    echo get_post_meta( $post->ID , 'publisher' , true ); 
			    break;				 
			case 'cat':
				if ( !isset( $cat_list ) || empty( $cat_list ) ) { 
					echo '-';
				} else {
					echo $cat_list;
				}
				break;
			case 'tag':
				if ( !isset( $tag_list ) || empty( $tag_list ) ) { 
					echo '-';
				} else {
					echo $tag_list;
				}
				break;
			case 'tax':
				if ( !isset( $term_list_by_tax ) || empty( $term_list_by_tax ) ) { 
					echo '-';
				} else {
					foreach ( $term_list_by_tax as $term_list ) {
						echo $term_list['label'].'<br>';
						echo ( !empty( $term_list['terms'] ) ? $term_list['terms'] : '-' ) . '<br><br>';
					}
				}
				break;						
		}  
	}


	/**
	 * Create metabox
	 */
	 
	public function create_meta_box() {

		$post_types = array();
		$custom_post_types = get_option( self::$plugin_prefix . '_cpts' );
		$general_settings = get_option( self::$plugin_prefix . '_general_settings' );
		$args = array(
		   'public'   => true,
		   '_builtin' => false,  
		);			
		$output = 'objects';
		$operator = 'and';
		$all_custompost_types = get_post_types( $args, $output, $operator );
		$post_type_list=array();
		
		$custom_post_type_list=array();
		/* Add plugin post types */
		if ( $custom_post_types ) {
			foreach ( $custom_post_types as $custom_post_type ) {
				$post_type_list[] = $custom_post_type['slug']; 
				$custom_post_type_list[] = $custom_post_type['slug']; 
			}
		}
		
		/* Add other cpt is enabled */
		if ( $all_custompost_types ) {
			foreach ( $all_custompost_types as $all_cpt_key => $all_custompost_type ) {
				if ( post_type_supports( $all_cpt_key, 'thumbnail' ) && !in_array ( $all_cpt_key, $custom_post_type_list ) && isset( $general_settings['enable_post_type'][$all_cpt_key] ) ) {
					$post_type_list[] = $all_cpt_key; 
				}
			}
		}		
		
		/* Add regular blog post if enabled */
		if ( isset( $general_settings['enable_post_type']['post'] ) ) { $post_type_list[] = 'post'; }
		if ( isset( $general_settings['enable_post_type']['attachment'] ) ) { $post_type_list[] = 'attachment'; }
		if ( isset( $general_settings['enable_post_type']['page'] ) ) { $post_type_list[] = 'page'; }
		
		/* Create meta box fields */
		$meta_box_fields = array( 
			
			/* Thumbnail options */
			array( 
				'name' => __( 'Thumbnail type', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_thumb_type',
				'type' => 'select',
				'desc' => __( 'Select thumbnail type.', 'go_portfolio_textdomain' ),
				'options' => array( 
					array( 'name' => __( 'Image', 'go_portfolio_textdomain' ), 'value' => 'image', 'data-children'=> 'image' ),
					array( 'name' => __( 'Video', 'go_portfolio_textdomain' ), 'value' => 'video', 'data-children'=> 'video' ),	
					array( 'name' => __( 'Audio', 'go_portfolio_textdomain' ), 'value' => 'audio', 'data-children'=> 'audio' ),		
				),
				'class' => 'regular-text',		
				'data-parent' => 'thumbnail-type'
			),
			
			/* Image Thumbnail */
			array( 
				'name' => __( 'Thumbnail image', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_thumb_img_src',
				'default' => '',
				'desc' => __( 'Source of the lightbox image if you would like to set different image from the thumbnail image.', 'go_portfolio_textdomain' ),
				'type' => 'img-upload',
				'class' => '',
				'wrapper-data-parent' => 'thumbnail-type',
				'wrapper-data-children' => 'image'		
			),
			
			array( 
				'name' => __( 'Video thumbnail type', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_thumb_video_type',
				'type' => 'select',
				'desc' => __( 'Select video type.', 'go_portfolio_textdomain' ),
				'options' => array( 
					array( 'name' => __( 'Youtube video', 'go_portfolio_textdomain' ), 'value' => 'youtube_video', 'data-children'=> 'youtube-video' ),
					array( 'name' => __( 'Vimeo video', 'go_portfolio_textdomain' ), 'value' => 'vimeo_video', 'data-children'=> 'vimeo-video' ),
					array( 'name' => __( 'Screenr video', 'go_portfolio_textdomain' ), 'value' => 'screenr_video', 'data-children'=> 'screenr-video' ),	
					array( 'name' => __( 'Dailymotion video', 'go_portfolio_textdomain' ), 'value' => 'dailymotion_video', 'data-children'=> 'dailymotion-video' ),
					array( 'name' => __( 'Metacafe video', 'go_portfolio_textdomain' ), 'value' => 'metacafe_video', 'data-children'=> 'metacafe-video' )
				),
				'class' => 'regular-text',
				'data-parent' => 'thumbnail-video-type',
				'wrapper-data-parent' => 'thumbnail-type',
				'wrapper-data-children' => 'video'
			),

			/* Audio thumbail */
			array( 
				'name' => __( 'Audio thumbnail type', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_thumb_audio_type',
				'type' => 'select',
				'desc' => __( 'Select audio type.', 'go_portfolio_textdomain' ),
				'options' => array( 
					array( 'name' => __( 'Soundcloud audio', 'go_portfolio_textdomain' ), 'value' => 'soundcloud_audio', 'data-children'=> 'soundcloud-audio' ),
					array( 'name' => __( 'Mixcloud audio', 'go_portfolio_textdomain' ), 'value' => 'mixcloud_audio', 'data-children'=> 'mixcloud-audio' ),
					array( 'name' => __( 'Beatport audio', 'go_portfolio_textdomain' ), 'value' => 'beatport_audio', 'data-children'=> 'beatport-audio' ),							
				),
				'class' => 'regular-text',
				'data-parent' => 'thumbnail-audio-type',
				'wrapper-data-parent' => 'thumbnail-type thumbnail-type',
				'wrapper-data-children' => 'audio'
			),
			
			/* Youtube video thumbnail */
			array( 
				'name' => __( 'Youtube video ID', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_thumbnail_youtube_video_id',
				'default' => '',
				'desc' => __( 'ID of the video.', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'regular-text',
				'wrapper-data-parent' => 'thumbnail-type thumbnail-video-type',
				'wrapper-data-children' => 'youtube-video'		
			),
			array( 
				'name' =>  __( 'Height', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_thumbnail_youtube_video_h',
				'default' => '',
				'desc' => __( 'Height of the video (optional).', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'regular-text',
				'wrapper-data-parent' => 'thumbnail-type thumbnail-video-type',
				'wrapper-data-children' => 'youtube-video'
			),					
			
			/* Vimeo video thumbnail */
			array( 
				'name' => __( 'Vimeo video ID', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_thumbnail_vimeo_video_id',
				'default' => '',
				'desc' => __( 'ID of the video.', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'regular-text',
				'wrapper-data-parent' => 'thumbnail-type thumbnail-video-type',
				'wrapper-data-children' => 'vimeo-video'		
			),
			array( 
				'name' =>  __( 'Height', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_thumbnail_vimeo_video_h',
				'default' => '',
				'desc' => __( 'Height of the video (optional).', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'regular-text',
				'wrapper-data-parent' => 'thumbnail-type thumbnail-video-type',
				'wrapper-data-children' => 'vimeo-video'
			),	
			array( 
				'name' =>  __( 'Color', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_thumbnail_vimeo_video_c',
				'default' => '',
				'desc' => __( 'Vimeo control colors (if the video allows).', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'small-text',
				'wrapper-data-parent' => 'thumbnail-type thumbnail-video-type',
				'wrapper-data-children' => 'vimeo-video'
			),			
		
			/* Screenr video thumbnail */
			array( 
				'name' => __( 'Screenr video ID', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_thumbnail_screenr_video_id',
				'default' => '',
				'desc' => __( 'ID of the video.', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'regular-text',
				'wrapper-data-parent' => 'thumbnail-type thumbnail-video-type',
				'wrapper-data-children' => 'screenr-video'		
			),
			array( 
				'name' =>  __( 'Height', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_thumbnail_screenr_video_h',
				'default' => '',
				'desc' => __( 'Height of the video (optional).', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'regular-text',
				'wrapper-data-parent' => 'thumbnail-type thumbnail-video-type',
				'wrapper-data-children' => 'screenr-video'
			),
			
			/* Dailymotion video thumbnail */
			array( 
				'name' => __( 'Dailymotion video ID', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_thumbnail_dailymotion_video_id',
				'default' => '',
				'desc' => __( 'ID of the video.', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'regular-text',
				'wrapper-data-parent' => 'thumbnail-type thumbnail-video-type',
				'wrapper-data-children' => 'dailymotion-video'		
			),
			array( 
				'name' =>  __( 'Height', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_thumbnail_dailymotion_video_h',
				'default' => '',
				'desc' => __( 'Height of the video (optional).', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'regular-text',
				'wrapper-data-parent' => 'thumbnail-type thumbnail-video-type',
				'wrapper-data-children' => 'dailymotion-video'
			),

			/* Metacafe video thumbnail */
			array( 
				'name' => __( 'Metacafe video ID', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_thumbnail_metacafe_video_id',
				'default' => '',
				'desc' => __( 'ID of the video.', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'regular-text',
				'wrapper-data-parent' => 'thumbnail-type thumbnail-video-type',
				'wrapper-data-children' => 'metacafe-video'		
			),
			array( 
				'name' =>  __( 'Height', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_thumbnail_metacafe_video_h',
				'default' => '',
				'desc' => __( 'Height of the video (optional).', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'regular-text',
				'wrapper-data-parent' => 'thumbnail-type thumbnail-video-type',
				'wrapper-data-children' => 'metacafe-video'
			),						

			/* Soundcloud audio thumbnail */
			array( 
				'name' => __( 'Soundcloud track ID', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_thumbnail_soundcloud_audio_id',
				'default' => '',
				'desc' => __( 'Track ID of the audio.', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'regular-text',
				'wrapper-data-parent' => 'thumbnail-type thumbnail-type thumbnail-audio-type',
				'wrapper-data-children' => 'soundcloud-audio'		
			),
			array( 
				'name' =>  __( 'Height', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_thumbnail_soundcloud_audio_h',
				'default' => '',
				'desc' => __( 'Height of the audio (optional).', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'regular-text',
				'wrapper-data-parent' => 'thumbnail-type thumbnail-audio-type',
				'wrapper-data-children' => 'soundcloud-audio'
			),
			array( 
				'name' =>  __( 'Color', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_thumbnail_soundcloud_audio_c',
				'default' => '',
				'desc' => __( 'Color of the player.', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'small-text',
				'wrapper-data-parent' => 'thumbnail-type thumbnail-type thumbnail-audio-type',
				'wrapper-data-children' => 'soundcloud-audio'
			),						

			/* Mixcloud audio lightbox */
			array( 
				'name' => __( 'Mixcloud track URL', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_thumbnail_mixcloud_audio_id',
				'default' => '',
				'desc' => __( 'URL of the audio.', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'regular-text',
				'wrapper-data-parent' => 'thumbnail-type thumbnail-type thumbnail-audio-type',
				'wrapper-data-children' => 'mixcloud-audio'		
			),
			array( 
				'name' =>  __( 'Height', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_thumbnail_mixcloud_audio_h',
				'default' => '',
				'desc' => __( 'Height of the audio (optional).', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'regular-text',
				'wrapper-data-parent' => 'thumbnail-type thumbnail-audio-type',
				'wrapper-data-children' => 'mixcloud-audio'
			),
			array( 
				'name' =>  __( 'Color', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_thumbnail_mixcloud_audio_c',
				'default' => '',
				'desc' => __( 'Color of the player.', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'small-text',
				'wrapper-data-parent' => 'thumbnail-type thumbnail-type thumbnail-audio-type',
				'wrapper-data-children' => 'mixcloud-audio'
			),						
			
			/* Beatport audio thumbnail */
			array( 
				'name' => __( 'Beatport track ID', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_thumbnail_beatport_audio_id',
				'default' => '',
				'desc' => __( 'Track ID of the audio.', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'regular-text',
				'wrapper-data-parent' => 'thumbnail-type thumbnail-type thumbnail-audio-type',
				'wrapper-data-children' => 'beatport-audio'		
			),
			array( 
				'name' =>  __( 'Height', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_thumbnail_beatport_audio_h',
				'default' => '',
				'desc' => __( 'Height of the audio (optional).', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'regular-text',
				'wrapper-data-parent' => 'thumbnail-type thumbnail-audio-type',
				'wrapper-data-children' => 'beatport-audio'
			),			
			
			/* Lightbox options */
			array( 
				'name' => __( 'Hide overlay?', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_hide_overlay',
				'desc' => __( 'Whether to hide overlay.', 'go_portfolio_textdomain' ),		
				'type' => 'checkbox',
				'wrapper-data-parent' => 'thumbnail-type',
				'wrapper-data-children' => 'image'		
			),	
			array( 
				'name' => __( 'Lighbox type', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_lighbox_type',
				'type' => 'select',
				'desc' => __( 'Select lighbox type.', 'go_portfolio_textdomain' ),
				'options' => array( 
					array( 'name' => __( 'Image', 'go_portfolio_textdomain' ), 'value' => 'image', 'data-children'=> 'image_lb' ),
					array( 'name' => __( 'Video', 'go_portfolio_textdomain' ), 'value' => 'video', 'data-children'=> 'video_lb' ),
					array( 'name' => __( 'Audio', 'go_portfolio_textdomain' ), 'value' => 'audio', 'data-children'=> 'audio_lb' ),
					array( 'name' => __( 'Other', 'go_portfolio_textdomain' ), 'value' => 'other', 'data-children'=> 'other_lb' ),								
				),
				'class' => 'regular-text',		
				'data-parent' => 'lightbox-type',
				'wrapper-data-parent' => 'thumbnail-type',
				'wrapper-data-children' => 'image'		
			),

			/* Image lighbox */
			array( 
				'name' => __( 'Lightbox image', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_lightbox_img_src',
				'default' => '',
				'desc' => __( 'Source of the lightbox image if you would like to set different image from the thumbnail image.', 'go_portfolio_textdomain' ),
				'type' => 'img-upload',
				'class' => '',
				'wrapper-data-parent' => 'lightbox-type',
				'wrapper-data-children' => 'image_lb'		
			),	
						
			/* Video lighbox */
			array( 
				'name' => __( 'Video lightbox type', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_lightbox_video_type',
				'type' => 'select',
				'desc' => __( 'Select video type.', 'go_portfolio_textdomain' ),
				'options' => array( 
					array( 'name' => __( 'Youtube video', 'go_portfolio_textdomain' ), 'value' => 'youtube_video', 'data-children'=> 'youtube-video' ),
					array( 'name' => __( 'Vimeo video', 'go_portfolio_textdomain' ), 'value' => 'vimeo_video', 'data-children'=> 'vimeo-video' ),
					array( 'name' => __( 'Screenr video', 'go_portfolio_textdomain' ), 'value' => 'screenr_video', 'data-children'=> 'screenr-video' ),
					array( 'name' => __( 'Dailymotion video', 'go_portfolio_textdomain' ), 'value' => 'dailymotion_video', 'data-children'=> 'dailymotion-video' ),
					array( 'name' => __( 'Metacafe video', 'go_portfolio_textdomain' ), 'value' => 'metacafe_video', 'data-children'=> 'metacafe-video' )		
				),
				'class' => 'regular-text',
				'data-parent' => 'lightbox-video-type',
				'wrapper-data-parent' => 'thumbnail-type lightbox-type',
				'wrapper-data-children' => 'video_lb'
			),
			
			/* Audio lighbox */
			array( 
				'name' => __( 'Audio lightbox type', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_lightbox_audio_type',
				'type' => 'select',
				'desc' => __( 'Select audio type.', 'go_portfolio_textdomain' ),
				'options' => array( 
					array( 'name' => __( 'Soundcloud audio', 'go_portfolio_textdomain' ), 'value' => 'soundcloud_audio', 'data-children'=> 'soundcloud-audio' ),
					array( 'name' => __( 'Mixcloud audio', 'go_portfolio_textdomain' ), 'value' => 'mixcloud_audio', 'data-children'=> 'mixcloud-audio' ),
					array( 'name' => __( 'Beatport audio', 'go_portfolio_textdomain' ), 'value' => 'beatport_audio', 'data-children'=> 'beatport-audio' ),							
				),
				'class' => 'regular-text',
				'data-parent' => 'lightbox-audio-type',
				'wrapper-data-parent' => 'thumbnail-type lightbox-type',
				'wrapper-data-children' => 'audio_lb'
			),			
			
			/* Youtube video lightbox */
			array( 
				'name' => __( 'Youtube video ID', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_lightbox_youtube_video_id',
				'default' => '',
				'desc' => __( 'ID of the video.', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'regular-text',
				'wrapper-data-parent' => 'thumbnail-type lightbox-type lightbox-video-type',
				'wrapper-data-children' => 'youtube-video'		
			),
			
			/* Vimeo video lightbox */
			array( 
		
				'name' => __( 'Vimeo video ID', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_lightbox_vimeo_video_id',
				'default' => '',
				'desc' => __( 'ID of the video.', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'regular-text',
				'wrapper-data-parent' => 'thumbnail-type lightbox-type lightbox-video-type',
				'wrapper-data-children' => 'vimeo-video'		
			),
			array( 
				'name' =>  __( 'Color', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_lightbox_vimeo_video_c',
				'default' => '',
				'desc' => __( 'Vimeo control colors (if the video allows).', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'small-text',
				'wrapper-data-parent' => 'thumbnail-type lightbox-type lightbox-video-type',
				'wrapper-data-children' => 'vimeo-video'
			),			
		
			/* Screenr video lightbox */
			array( 
				'name' => __( 'Screenr video ID', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_lightbox_screenr_video_id',
				'default' => '',
				'desc' => __( 'ID of the video.', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'regular-text',
				'wrapper-data-parent' => 'thumbnail-type lightbox-type lightbox-video-type',
				'wrapper-data-children' => 'screenr-video'		
			),
			
			/* Dailymotion video lightbox */
			array( 
				'name' => __( 'Dailymotion video ID', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_lightbox_dailymotion_video_id',
				'default' => '',
				'desc' => __( 'ID of the video.', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'regular-text',
				'wrapper-data-parent' => 'thumbnail-type lightbox-type lightbox-video-type',
				'wrapper-data-children' => 'dailymotion-video'		
			),
			
			/* Metacafe video lightbox */
			array( 
				'name' => __( 'Metacafe video ID', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_lightbox_metacafe_video_id',
				'default' => '',
				'desc' => __( 'ID of the video.', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'regular-text',
				'wrapper-data-parent' => 'thumbnail-type lightbox-type lightbox-video-type',
				'wrapper-data-children' => 'metacafe-video'		
			),		
			
			/* Soundcloud audio lightbox */
			array( 
				'name' => __( 'Soundcloud track ID', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_lightbox_soundcloud_audio_id',
				'default' => '',
				'desc' => __( 'Track ID of the audio.', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'regular-text',
				'wrapper-data-parent' => 'thumbnail-type lightbox-type lightbox-audio-type',
				'wrapper-data-children' => 'soundcloud-audio'		
			),
			array( 
				'name' =>  __( 'Color', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_lightbox_soundcloud_audio_c',
				'default' => '',
				'desc' => __( 'Color of the player.', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'small-text',
				'wrapper-data-parent' => 'thumbnail-type lightbox-type lightbox-audio-type',
				'wrapper-data-children' => 'soundcloud-audio'
			),
			
			/* Mixcloud audio lightbox */
			array( 
				'name' => __( 'Mixcloud track URL', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_lightbox_mixcloud_audio_id',
				'default' => '',
				'desc' => __( 'URL of the audio.', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'regular-text',
				'wrapper-data-parent' => 'thumbnail-type lightbox-type lightbox-audio-type',
				'wrapper-data-children' => 'mixcloud-audio'		
			),
			array( 
				'name' =>  __( 'Color', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_lightbox_mixcloud_audio_c',
				'default' => '',
				'desc' => __( 'Color of the player.', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'small-text',
				'wrapper-data-parent' => 'thumbnail-type lightbox-type lightbox-audio-type',
				'wrapper-data-children' => 'mixcloud-audio'
			),
			
			/* Beatport audio lightbox */
			array( 
				'name' => __( 'Beatport track ID', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_lightbox_beatport_audio_id',
				'default' => '',
				'desc' => __( 'Track ID of the audio.', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'regular-text',
				'wrapper-data-parent' => 'thumbnail-type lightbox-type lightbox-audio-type',
				'wrapper-data-children' => 'beatport-audio'		
			),								
			
			/* Other lighbox */
			array( 
				'name' => __( 'Other lightbox type', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_lightbox_other_type',
				'type' => 'select',
				'desc' => __( 'Select type.', 'go_portfolio_textdomain' ),
				'options' => array( 
					array( 'name' => __( 'Custom iframe', 'go_portfolio_textdomain' ), 'value' => 'custom_iframe', 'data-children'=> 'custom-iframe' )	
				),
				'class' => 'regular-text',
				'data-parent' => 'lightbox-iframe-type',
				'wrapper-data-parent' => 'thumbnail-type lightbox-type',
				'wrapper-data-children' => 'other_lb'
			),
			
			/* Custom iframe lightbox */
			array( 
				'name' => __( 'URL', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_lightbox_iframe_url',
				'default' => '',
				'desc' => __( 'Site URL.', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'regular-text',
				'wrapper-data-parent' => 'thumbnail-type lightbox-type lightbox-iframe-type',
				'wrapper-data-children' => 'custom-iframe'		
			),
			array( 
				'name' =>  __( 'Height', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_lightbox_iframe_h',
				'default' => '',
				'desc' => __( 'Iframe height (optional).', 'go_portfolio_textdomain' ),
				'type' => 'text',
				'class' => 'small-text',
				'wrapper-data-parent' => 'thumbnail-type lightbox-type lightbox-iframe-type',
				'wrapper-data-children' => 'custom-iframe'
			),	

			/* Lightbox button options */	
			array( 
				'name' => __( 'Hide lightbox button on overlay?', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_hide_lightbox_button',
				'desc' => __( 'Whether to hide the lightbox button or circle on overlay.', 'go_portfolio_textdomain' ),		
				'type' => 'checkbox',
				'wrapper-data-parent' => 'thumbnail-type',
				'wrapper-data-children' => 'image'		
			),
			array( 
				'name' => __( 'Hide read more button on overlay?', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_hide_link_button',
				'desc' => __( 'Whether to hide the read more button or circle on overlay.', 'go_portfolio_textdomain' ),		
				'type' => 'checkbox',
				'wrapper-data-parent' => 'thumbnail-type',
				'wrapper-data-children' => 'image'			
			),
			array( 
				'name' => __( 'Custom post link', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_post_link',
				'default' => '',
				'desc' => __( 'Whether to replace the default links which redirect to the post.', 'go_portfolio_textdomain' ),		
				'type' => 'text',
				'class' => 'regular-text'		
			),
			array( 
				'name' => __( 'Open link in new window?', 'go_portfolio_textdomain' ),
				'id' => self::$plugin_prefix . '_post_link_target',
				'desc' => __( 'Whether to open the link in new window.', 'go_portfolio_textdomain' ),		
				'type' => 'checkbox'		
			),			
		 );
		
		/* Add new metaboxes */
		$add_nex_meta_boxes = new GW_Meta_Box( self::$plugin_prefix . '_options', __( 'Go Portfolio Options', 'go_portfolio_textdomain' ), $meta_box_fields, $post_type_list );

	}

	
	/**
	 * Post meta shortcode function
	 */
	 
	public function go_portfolio_meta_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array( 
			'key' 	=> null,
			'post_id' => null
		), $atts ) );

		$post_meta = get_post_meta( $post_id, '' );
		$shortcode_content = isset( $post_meta[$key][0] ) && !empty( $post_meta[$key][0] ) ? $post_meta[$key][0] : '';
		$shortcode_content = apply_filters( 'go_portfolio_meta_filter', $shortcode_content, $key, $post_id );
		return $shortcode_content;
	}
	
	
	/**
	 * List post terms shortcode function
	 */
	 
	public function go_portfolio_list_terms_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array( 
			'taxonomy' => null,
			'orderby' => 'name',
			'order' => 'ASC',
			'separator' => ', ',
			'post_id' => null
		), $atts ) );

		$shortcode_content = '';
		
		if ( $taxonomy && $post_id ) {
			$post_terms = wp_get_post_terms( $post_id, $taxonomy, 
				array(
					'orderby' => $orderby,
					'order' => $order
				) 
			);
			
		};
		
		if ( !is_wp_error($post_terms) && isset( $post_terms ) && !empty( $post_terms ) ) {
			foreach ( $post_terms as $post_term ) {
				$shortcode_content .= '<span data-id="' . $post_term->term_id . '" data-slug="' . $post_term->slug . '">' . $post_term->name . '</span>' . $separator;
			}
		}
		
		$shortcode_content = trim( $shortcode_content, $separator );
		$shortcode_content = apply_filters( 'go_portfolio_list_terms_sc_filter', $shortcode_content, $key, $post_id );
		return $shortcode_content;
	}
	

	/**
	 * Load portfolio via ajax
	 */
	 
	public function ajax_load_portfolio() {
		if ( !isset( $_POST ) ) { 
			die ( __( 'Oops, authorized persons only!', 'go_portfolio_textdomain' ) );
		} else {
			$taxonomy = isset( $_POST['taxonomy'] ) ? $_POST['taxonomy'] : '';
			$term_slug = isset( $_POST['term_slug'] ) ? $_POST['term_slug'] : '';	
			$post_per_page = isset( $_POST['post_per_page'] ) ? $_POST['post_per_page'] : 0;			
			$portfolio_id = isset( $_POST['portfolio_id'] ) ? $_POST['portfolio_id'] : 0;
			$current_page = isset( $_POST['current_page'] ) ? floatval( $_POST['current_page'] ) : 0;
			/* should be 0 */
			$current_page = 0;
			$portfolio_id = isset( $_POST['portfolio_id'] ) ? $_POST['portfolio_id'] : 0;
			$exclude_ids = $loaded_ids = isset( $_POST['loaded_ids'] ) ? explode( ',', $_POST['loaded_ids'] ) : array();
			$current_id = isset( $_POST['current_id'] ) ? floatval( $_POST['current_id'] ) : null;
			if ($current_id) {
				$post = get_post($current_id);
				if ( isset( $post->ID ) && !empty( $post->ID ) ) {				
					$exclude_ids[]=$post->ID;
				}
			}
						
		}
		
		if ( $portfolio_id ) {
			echo do_shortcode('[go_portfolio id="' . esc_attr( $portfolio_id ) . '" post_per_page="' . esc_attr( $post_per_page ) . '" taxonomy="' . esc_attr( $taxonomy ) . '" term_slug="' . esc_attr( $term_slug ) . '" current_page="' . esc_attr( $current_page ) . '" exclude_posts="' . esc_attr( isset( $loaded_ids ) && !empty( $loaded_ids ) ? implode( ',',$exclude_ids ) : '' ) . '"]');	
		}
		exit;
	}
	
	
	/**
	 * Portfolio shortcode function
	 */

	public function go_portfolio_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array( 
			'id' 	=> null,
			'margin_top' => '0',
			'margin_bottom' => '0',
			'current_page' => '1',
			'exclude_posts' => '',
			'taxonomy' => '',
			'term_slug' => '',
			'post_per_page' => null
		 ), $atts ) );

		$shortcode_content = null;
		
		/* Check the id */
		if ( !isset( $id ) ) { 
		
			/* If id is missing */
			return '<p>' .  __( 'You must set a portfolio id.', 'go_portfolio_textdomain' ) . '</p>';
		
		} else {
			
			/* If id is ok */
			$id = sanitize_key( $id );

			/* Get data from db */
			$portfolios = get_option( self::$plugin_prefix . '_portfolios' );
			$custom_post_types = get_option( self::$plugin_prefix . '_cpts' );
			$templates = get_option( self::$plugin_prefix . '_templates' );
			$styles = get_option( self::$plugin_prefix . '_styles' );
			$css_style = '';
			
			/* Check if portfolio exists and really registered */
			if ( !empty( $portfolios ) ) {
					foreach ( $portfolios as $portfolio_key => $portfolio ) {
		
					/* Check if given id exist in plugin db */
					if ( $portfolio['id'] == $id ) {

							global $post;
				
							$general_settings = get_option( self::$plugin_prefix . '_general_settings' );
							
							if ( !empty( $general_settings['plugin-pages-rule'] ) &&  $general_settings['plugin-pages'] && !empty( $post ) ) {
							
								$page_ids = $general_settings['plugin-pages'];
							
								if ( !empty( $page_ids ) ) {
									
									$pages =  trim( preg_replace( '/([^0-9][^,]{0})+/', ',', $page_ids ), ',' );
									$pages = explode( ',', $pages );
									
									if ( $general_settings['plugin-pages-rule'] == 'in' && in_array( $post->ID, $pages ) ||  $general_settings['plugin-pages-rule'] == 'not_in' && !in_array( $post->ID, $pages ) ) {
										
										ob_start();
										echo '<style>';
										self::generate_inline_styles( $portfolio );
										echo '</style>';
										$css_style = ob_get_clean();
										
									}
									
										
								}
								
							} else {
								ob_start();
								echo '<style>';
								self::generate_inline_styles( $portfolio );
								echo '</style>';
								$css_style = ob_get_clean();
							}
							
							/* Check if post type is registered */
							$post_types = get_post_types( '', 'objects' );
							if ( isset( $post_types[$portfolio['post-type']] ) ) {
								$query_post_type = $portfolio['post-type'];
												
								global $wp_query, $post;
								$new_wp_query = null;
								$new_wp_query = new WP_Query();
		
								/* Set query post type */
								$arg_post_type = isset( $portfolio['post-type'] ) && !empty( $portfolio['post-type'] ) ? $portfolio['post-type'] : 'post';

								/* Set query taxonomy & terms */
								$arg_tax = isset ( $portfolio['post-tax'][$arg_post_type] ) && !empty( $portfolio['post-tax'][$arg_post_type] ) ? $portfolio['post-tax'][$arg_post_type] : array();
								$arg_terms = isset( $portfolio['post-term'][$arg_post_type][$arg_tax] ) && !empty( $portfolio['post-term'][$arg_post_type][$arg_tax] ) && !in_array( 'all', $portfolio['post-term'][$arg_post_type][$arg_tax] ) ? $portfolio['post-term'][$arg_post_type][$arg_tax] : array(); 
								/* Set filter tax */
								$filter_tax = isset ( $portfolio['filter-tax'][$arg_post_type] ) && !empty( $portfolio['filter-tax'][$arg_post_type] ) ? $portfolio['filter-tax'][$arg_post_type] : array();
								
								/* For backward compatibility */
								$filter_tax = empty( $filter_tax ) ? $arg_tax : $filter_tax;
								$all_post_term_list = array();
								
								/* Get current page info */
								$current_page = floatval( isset( $current_page ) && !empty( $current_page ) ? $current_page : 1 );
								
								/* Set query args */
								$new_wp_query_args = array (
									'post_type' => $portfolio['post-type'],
									'posts_per_page' => isset( $portfolio['post-count'] ) && !empty( $portfolio['post-count'] ) ? $portfolio['post-count'] : '-1',
									'cache_results' => false,
									'post_status' => $arg_post_type == 'attachment' ? 'inherit' : 'publish',
									'ignore_sticky_posts' => true,
									'paged' => $current_page
								);
								
								/* post per page override */
								if ( isset( $post_per_page ) && !empty( $post_per_page ) ) {
									$new_wp_query_args['posts_per_page'] = $post_per_page;
								}
								
								/* Modify query args - exclude posts */
								if ( isset( $exclude_posts ) && !empty( $exclude_posts ) ) {
									$excluded_posts = explode(',', trim( $exclude_posts ) );
									foreach( $excluded_posts as $key => $excluded_post ) {
										$excluded_post = trim( floatval( $excluded_post ) );
										if (!get_post( $excluded_post ) ) {
											unset( $excluded_posts[$key] );
										}
									}
									if ( isset( $portfolio['exclude-current'] ) ) { $excluded_posts[] = $post->ID; }
									$new_wp_query_args['post__not_in'] = $excluded_posts;
								} else {
									if ( isset( $portfolio['exclude-current'] ) ) { 
										$new_wp_query_args['post__not_in'][] = $post->ID;
									}
								}
								
								if ( isset( $new_wp_query_args['post__not_in'] ) && !empty( $new_wp_query_args['post__not_in'] ) ) {
									$new_wp_query_args['post__not_in'] = array_unique( $new_wp_query_args['post__not_in'] );
								}
								
								/* Gallery visual builder mode */
								if ( $arg_post_type == 'attachment' && isset( $portfolio['gallery-query-method'] ) && !empty( $portfolio['gallery-query-method'] ) && $portfolio['gallery-query-method'] == 'visual' ) {
									if ( isset( $portfolio['inquery-items']['attachment'] ) && !empty( $portfolio['inquery-items']['attachment'] ) ) {
										foreach ( $portfolio['inquery-items']['attachment'] as $portfolio_item_key => $portfolio_item ) {
											$items[] = $portfolio_item_key;
										}
										$new_wp_query_args['post__in'] = $items;
										if ( isset( $excluded_posts ) && isset( $items ) ) {
											$new_items = array_diff( $items,$excluded_posts );
											$new_wp_query_args['post__in'] = $new_items;
										}
										
										if ( !isset( $portfolio['orderby-vb'] ) || !isset( $portfolio['order-vb'] ) ) {
											$new_wp_query_args['orderby'] = 'post__in';
										} else {
											$new_wp_query_args['orderby'] = $portfolio['orderby-vb'];
											$new_wp_query_args['order'] = $portfolio['order-vb'];
											if ( $portfolio['orderby-vb'] == 'post__in' && $portfolio['order-vb'] == 'DESC' ) $new_wp_query_args['post__in'] = array_reverse( $items);
										}
									}
								}
								
								/* Manual query mode */
								if ( $arg_post_type != 'attachment' || $arg_post_type == 'attachment' && isset( $portfolio['gallery-query-method'] ) && !empty( $portfolio['gallery-query-method'] ) && $portfolio['gallery-query-method'] == 'manual' ) {
									
									/* Modify query args - order */
									$new_wp_query_args['orderby'] = $portfolio['orderby'];
									$new_wp_query_args['order'] = $portfolio['order'];
																		
									/* Modify query args - taxnomy */
									if ( isset( $arg_tax ) && !empty( $arg_tax ) && isset( $arg_terms ) && !empty( $arg_terms )  ) {
										
										/* WPML tax term fix */
										if( function_exists( 'icl_object_id' ) ) {
											foreach( $arg_terms as $arg_term ) {
												$arg_terms_translated[] = icl_object_id ( $arg_term, $arg_tax, true, ICL_LANGUAGE_CODE );
											}
											if ( isset( $arg_terms_translated ) && !empty( $arg_terms_translated ) ) { $arg_terms = $arg_terms_translated; }
										}										
										
										$new_wp_query_args['tax_query'] = array(
											array(
												'taxonomy' => $arg_tax,
												'field' => 'id',												
												'terms' => $arg_terms
											)
										);
									}									
								}								
								
								if ( !empty( $taxonomy ) && !empty( $term_slug ) ) {
									$new_wp_query_args['tax_query'] = array(
										array(
											'taxonomy' => $taxonomy,
											'field' => 'slug',
											'terms' => $term_slug
										)
									);
								}
								

								$new_wp_query_args = apply_filters( 'go_portfolio_query_filter', $new_wp_query_args, $portfolio['id'] );																						
								$new_wp_query -> query( $new_wp_query_args );
								$posts_count = $new_wp_query->found_posts;
								$pages_count = $new_wp_query->max_num_pages;
								$post_per_page = $new_wp_query->query['posts_per_page'];
																
								/* Get template */
								if ( isset( $portfolio['template'] ) && !empty( $portfolio['template'] ) ) {
									$template_type = $portfolio['template'];
									if ( isset( $portfolio['template-data'] ) && !empty( $portfolio['template-data'] ) ) {
										$template = stripslashes( $portfolio['template-data'] );
									} else {
										$template = stripslashes( $templates[$portfolio['template']]['data'] );
									} 
								} else {
									return '<p>' .  __( 'The template is missing.', 'go_portfolio_textdomain' ) . '</p>';
								}
		
								/* Set portfolio classes */
								$layout_type = isset( $portfolio['layout-type'] ) && !empty( $portfolio['layout-type'] ) ? $portfolio['layout-type']  : 'grid';
								
								/* 1. Slider layout */
								if ( $layout_type == 'slider' ) { 
									$slider_data['auto']['play'] = isset( $portfolio['slider-autoplay'] ) && !empty( $portfolio['slider-autoplay'] ) ? true : false;
									$slider_data['auto']['timeoutDuration'] = isset( $portfolio['slider-autoplay-timeout'] ) && !empty( $portfolio['slider-autoplay-timeout'] ) ? floatval( $portfolio['slider-autoplay-timeout'] ) : null;
									$slider_data['auto']['pauseOnHover'] = true;
									$slider_data['circular'] = isset( $portfolio['slider-infinite'] ) && !empty( $portfolio['slider-infinite'] ) ? true : false;
									$slider_data['infinite'] = isset( $portfolio['slider-infinite'] ) && !empty( $portfolio['slider-infinite'] ) ? true : false;
									$slider_data['direction'] = isset( $portfolio['slider-autoplay-direction'] ) && $portfolio['slider-autoplay-direction'] == 'right' ? 'right' : 'left';
									$post_classes[] = 'gw-gopf-slider-type';
								}
								
								/* 2. Grid layout */
								if ( $layout_type == 'grid' ) { $post_classes[] = 'gw-gopf-grid-type'; }
								if ( isset( $portfolio['layout-direction'] ) && !empty( $portfolio['layout-direction'] ) ) {  $post_classes[] = $portfolio['layout-direction']; }								
								if ( isset( $portfolio['column-layout'] ) && !empty( $portfolio['column-layout'] ) ) { $post_classes[]=$portfolio['column-layout']; }
								if ( isset( $portfolio['style'] ) && !empty( $portfolio['style'] ) ) { $post_classes[]=$styles[$portfolio['style']]['class']; }	
								if ( isset( $portfolio['style'] ) && !empty( $portfolio['style'] ) && isset( $portfolio['effect-data'] ) && !empty( $portfolio['effect-data'] ) ) { $post_classes[]=$styles[$portfolio['style']]['class'] . '-' . $portfolio['effect-data']; }
								$post_classes[] = isset( $portfolio['filter-type'] ) && $portfolio['filter-type'] == 'opacity' ? 'gw-gopf-filter-opacity' : '';
								$post_classes[] = isset( $portfolio['pagination'] ) ? 'gw-gopf-pagination' : '';;
								if ( !isset( $portfolio['filter-v-pos'] ) || ( isset( $portfolio['filter-v-pos'] ) && $portfolio['filter-v-pos'] == 'top') ) {
									ob_start();
								}								
								?>						
								<div id="<?php echo esc_attr( self::$plugin_prefix . '_' . $portfolio['id'] ); ?>" style="<?php echo esc_attr( ( isset( $margin_top ) ? 'margin-top:' . $margin_top . ';' : '' ) . ( isset( $margin_bottom ) ? 'margin-bottom:' . $margin_bottom . ';' : '' ) ); ?>">
									<!--[if lt IE 9]><div class="gw-gopf gw-gopf-ie <?php echo esc_attr( implode( ' ', $post_classes ) ); ?>" data-url="<?php echo admin_url('admin-ajax.php'); ?>" data-id="<?php echo esc_attr( $portfolio_key ); ?>" data-cols="<?php echo isset( $portfolio['column-layout'] ) && !empty( $portfolio['column-layout'] ) ? floatval( str_replace( 'gw-gopf-', '', $portfolio['column-layout'] ) ) : 1; ?>" data-rowspace="<?php echo isset( $portfolio['v-space'] ) && !empty( $portfolio['v-space'] ) ? floatval( $portfolio['v-space'] ) : 0; ?>" data-rtl="<?php echo isset( $portfolio['layout-direction'] ) && $portfolio['layout-direction'] == 'gw-gopf-rtl' ? 'true' : 'false'; ?>" data-transenabled="<?php echo esc_attr( isset( $portfolio['trans-enabled'] ) ? 'true' : 'false' ); ?>" data-lbenabled="<?php echo esc_attr( !isset( $portfolio['disable-lightbox'] ) ? 'true' : 'false' ); ?>" data-lbgallery="<?php echo esc_attr( isset( $portfolio['lightbox-gallery'] ) ? 'true' : 'false' ); ?>" data-deep-linking="<?php echo esc_attr( isset( $portfolio['lightbox-deep-linking'] ) ? 'true' : 'false' ); ?>"  data-filter-type="<?php echo esc_attr( isset( $portfolio['filter-type'] ) && $portfolio['filter-type'] == 'opacity' ? 'opacity' : 'isotope' ); ?>"><![endif]-->
									<!--[if gte IE 9]> <!--><div class="gw-gopf gw-gopf-no-trans <?php echo esc_attr( implode( ' ', $post_classes ) ); ?>" data-url="<?php echo admin_url('admin-ajax.php'); ?>" data-id="<?php echo esc_attr( $portfolio_key ); ?>" data-cols="<?php echo isset( $portfolio['column-layout'] ) && !empty( $portfolio['column-layout'] ) ? floatval( str_replace( 'gw-gopf-', '', $portfolio['column-layout'] ) ) : 1; ?>" data-rowspace="<?php echo isset( $portfolio['v-space'] ) && !empty( $portfolio['v-space'] ) ? floatval( $portfolio['v-space'] ) : 0; ?>" data-rtl="<?php echo isset( $portfolio['layout-direction'] ) && $portfolio['layout-direction'] == 'gw-gopf-rtl' ? 'true' : 'false'; ?>" data-transenabled="<?php echo esc_attr( isset( $portfolio['trans-enabled'] ) ? 'true' : 'false' ); ?>" data-lbenabled="<?php echo esc_attr( !isset( $portfolio['disable-lightbox'] ) ? 'true' : 'false' ); ?>" data-lbgallery="<?php echo esc_attr( isset( $portfolio['lightbox-gallery'] ) ? 'true' : 'false' ); ?>" data-deep-linking="<?php echo esc_attr( isset( $portfolio['lightbox-deep-linking'] ) ? 'true' : 'false' ); ?>"  data-filter-type="<?php echo esc_attr( isset( $portfolio['filter-type'] ) && $portfolio['filter-type'] == 'opacity' ? 'opacity' : 'isotope' ); ?>"> <!--<![endif]-->
									<?php ob_start(); ?>
									<div class="gw-gopf-posts-wrap">
										<?php 
										/* Print slider arrows */
										if ( $layout_type == 'slider' ) : 
										?>
										<div class="gw-gopf-slider-controls-wrap gw-gopf-clearfix<?php echo ( isset( $portfolio['slider-arrows-align'] ) && !empty( $portfolio['slider-arrows-align'] ) ?  ' '. $portfolio['slider-arrows-align'] : '' ); ?>">
											<div class="gw-gopf-slider-controls gw-gopf-clearfix">
												<div class="gw-gopf-control-prev"><a href="#"><img src="<?php echo GW_GO_PORTFOLIO_URI . 'assets/images/icon_prev.png'; ?>" class="gw-gopf-retina" alt="<?php  _e( 'Previous', 'go_portfolio_textdomain' ); ?>"></a></div>
												<div class="gw-gopf-control-next"><a href="#"><img src="<?php echo GW_GO_PORTFOLIO_URI . 'assets/images/icon_next.png'; ?>" class="gw-gopf-retina" alt="<?php  _e( 'Next', 'go_portfolio_textdomain' ); ?>"></a></div>
											</div>
										</div>
										<?php endif; ?>							
										<div class="gw-gopf-posts-wrap-inner">
											<div class="gw-gopf-posts gw-gopf-clearfix"<?php echo ( isset( $portfolio['column-layout'] ) && !empty( $portfolio['column-layout'] ) ? ' data-col="' . preg_replace('/[^0-9]/', '', $portfolio['column-layout'] ) . '"' : '' ); ?><?php echo ( $layout_type == 'slider' ? ' data-slider="' . esc_js( json_encode( $slider_data ) ) . '"' : '' ); ?>>
												<?php 
												$all_post_ids = array();									
												
												/* Get thumbs sizes */
												$thumbanail_size = isset( $portfolio['thumbnail-size'] ) && !empty( $portfolio['thumbnail-size'] ) ? $portfolio['thumbnail-size'] : 'full';
												$lightbox_size = isset( $portfolio['lightbox-size'] ) && !empty( $portfolio['lightbox-size'] ) ? $portfolio['lightbox-size'] : 'full';
												
												/* Loop */
												while( $new_wp_query->have_posts() ) : $new_wp_query->the_post();
												?>

												<?php
												/* Portfolio posts */
												$all_post_ids[] = $post->ID;

												/* Get post term list */
												$post_term_list = array();
												if ( !empty( $filter_tax ) ) { $post_terms = get_the_terms( $post->ID, $filter_tax ); }
												if ( isset( $post_terms ) && !empty( $post_terms ) ) {
													foreach ( $post_terms as $post_term ) {
														$post_term_list[] = $post_term->slug;
														$all_post_term_list[] = $post_term->term_id;
													}
												}
												/* Set post & thumbnail types */
												
												$post_meta = get_post_meta( $post->ID, '' );
												/* WooCommerece settings */
												if ( defined( 'WOOCOMMERCE_VERSION' ) && isset( $query_post_type ) && $query_post_type=='product' ) {
													$woo_product = get_product( $post->ID );
													$woo_is_variation = isset( $woo_product->product_type ) && ( $woo_product->product_type == 'variation' || $woo_product->product_type == 'variable' ) ? true : false;
													if ( $woo_is_variation ) { $post = $woo_product->post; }
												}
												
												$thumbnail_type = isset( $post_meta['gw_go_portfolio_thumb_type'][0] ) && !empty( $post_meta['gw_go_portfolio_thumb_type'][0] ) ? $post_meta['gw_go_portfolio_thumb_type'][0]  : 'image';
												$lighbox_type = isset( $post_meta['gw_go_portfolio_lighbox_type'][0] ) && !empty( $post_meta['gw_go_portfolio_lighbox_type'][0] ) ? $post_meta['gw_go_portfolio_lighbox_type'][0]  : 'image';
												$has_overlay = isset( $portfolio['overlay'] ) && $thumbnail_type == 'image' && !isset( $post_meta['gw_go_portfolio_hide_overlay'][0] ) ? true : false;
												$post_link = isset( $post_meta['gw_go_portfolio_post_link'][0] ) && !empty( $post_meta['gw_go_portfolio_post_link'][0] ) ? $post_meta['gw_go_portfolio_post_link'][0] : get_permalink();
												
												/* Get template data */
												$replaced_template = null;
												$has_lighbox = true;
												$force_img_thumb = true;												
												?>
												<div id="<?php echo esc_attr( $post->ID ); ?>" class="gw-gopf-col-wrap" data-filter="<?php echo esc_attr( implode(' ', $post_term_list ) ); ?>">
													<div class="gw-gopf-post-col<?php echo ( $has_overlay ? ' gw-gopf-has-overlay' : '' ); ?><?php echo ( isset( $portfolio['overlay-hover'] ) && $portfolio['overlay-hover']=='2' ?  ' gw-gopf-post-overlay-hover' : '' ); ?>">
													<?php											
													/* 1. Post link */
													if ( isset( $template_data['post_link'] ) ) { unset( $template_data['post_link'] ); }
													$template_data['post_link'] = $post_link;
													
													/* 2. Post media */
													if ( isset( $template_data['post_media'] ) ) { unset( $template_data['post_media'] ); }
													
													/* 3. Post overlay */
													$button_style_class = isset( $portfolio['overlay-style'] ) && $portfolio['overlay-style'] == '2' ? 'gw-gopf-btn gw-gopf-post-overlay-btn' : 'gw-gopf-circle gw-gopf-post-overlay-circle';
													if ( isset( $portfolio['overlay-btn-style'] ) && !empty( $portfolio['overlay-btn-style'] ) ) { $button_style_class .= ' ' . $portfolio['overlay-btn-style']; }
													$popup_height = null;
													
													if ( $lighbox_type == 'image' ) {
														$tn_id = null;
														$tn_img_data = null;
														$tn_img_file = null;
														$lb_img_data = null;
														$lb_img_file = null;
														$lightbox_img_src = get_post_meta( $post->ID, 'gw_go_portfolio_lightbox_img_src' ,true );
														$thumb_img_src = get_post_meta( $post->ID, 'gw_go_portfolio_thumb_img_src' ,true );
														$matches=null;
														preg_match( '/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $post->post_content, $matches );
														if ( isset( $portfolio['first-img-thumb'] ) && $portfolio['first-img-thumb'] == 'force' && isset( $matches ) && !empty( $matches ) ) {
															$lb_img_file = $matches[1];
														} elseif ( isset( $lightbox_img_src[0] ) && !empty( $lightbox_img_src[0] ) ) {
															$lb_img_file = $lightbox_img_src[0];
														} elseif ( isset( $thumb_img_src[0] ) && !empty( $thumb_img_src[0] ) ) {
															$lb_img_file = $thumb_img_src[0];
														} elseif ( has_post_thumbnail() || $arg_post_type == 'attachment' ) {
															global $wp_version;
															if ( version_compare( $wp_version, 4.2, ">=" ) ) {	
																$tn_id = $arg_post_type == 'attachment' ? $post->ID : get_post_thumbnail_id( $post->ID );													
															} else {
																$tn_id = $arg_post_type == 'attachment' ? $post->guid : get_post_thumbnail_id( $post->ID );
															}
															$lb_img_data = wp_get_attachment_image_src( $tn_id, $lightbox_size );
															if ( $lb_img_data ) { $lb_img_file = $lb_img_data[0]; }															
														} elseif ( isset( $portfolio['first-img-thumb'] ) && $portfolio['first-img-thumb'] == 'fallback' && isset( $matches ) && !empty( $matches ) ) {
															$lb_img_file = $matches[1];
														}
														$lighbox_link = isset( $lightbox_img_src[0] ) && !empty( $lightbox_img_src[0] ) ?  $lightbox_img_src[0] : ( isset( $lb_img_file ) ? $lb_img_file : '#' );
														$lighbox_class = 'gw-gopf-magnific-popup';
														if ( isset( $portfolio['overlay-style'] ) && $portfolio['overlay-style'] == '2' ) {
															$button_content = isset( $portfolio['overlay-btn-link-image'] ) ? $portfolio['overlay-btn-link-image'] : '';
														} elseif ( isset( $portfolio['overlay-style'] ) && $portfolio['overlay-style'] == '1' ) {
															$button_content = '<img src="' . GW_GO_PORTFOLIO_URI . 'assets/images/icon_large.png" class="gw-gopf-retina" alt="' . __( 'Show more', 'go_portfolio_textdomain' ) . '">';
														}
													} elseif ( $lighbox_type == 'video' || $lighbox_type == 'audio' ) {
														$lighbox_link = '#';
														$lighbox_class = 'gw-gopf-magnific-popup-html';

														if ( isset( $portfolio['overlay-style'] ) && $portfolio['overlay-style'] == '2' ) {
															if ( $lighbox_type == 'video' ) {
																$button_content = isset( $portfolio['overlay-btn-link-video'] ) ? $portfolio['overlay-btn-link-video'] : '';
															} else {
																$button_content = isset( $portfolio['overlay-btn-link-audio'] ) ? $portfolio['overlay-btn-link-audio'] : '';
															}
														} elseif ( isset( $portfolio['overlay-style'] ) && $portfolio['overlay-style'] == '1' ) {
															if ( $lighbox_type == 'video' ) {
																$button_content = '<img src="' . GW_GO_PORTFOLIO_URI . 'assets/images/icon_video.png" class="gw-gopf-retina" alt="' . __( 'Show more', 'go_portfolio_textdomain' ) . '">';
															} else {
																$button_content = '<img src="' . GW_GO_PORTFOLIO_URI . 'assets/images/icon_audio.png" class="gw-gopf-retina" alt="' . __( 'Show more', 'go_portfolio_textdomain' ) . '">';
															}
														}
														
														$lighbox_content = '';
														
														/* Video types */
														if ( isset( $post_meta['gw_go_portfolio_lightbox_video_type'][0] ) && $post_meta['gw_go_portfolio_lightbox_video_type'][0]== 'youtube_video' && isset( $post_meta['gw_go_portfolio_lightbox_youtube_video_id'][0] ) ) {
															$lighbox_link = '//www.youtube.com/watch?v=' . $post_meta['gw_go_portfolio_lightbox_youtube_video_id'][0];
														} elseif ( isset( $post_meta['gw_go_portfolio_lightbox_video_type'][0] ) && $post_meta['gw_go_portfolio_lightbox_video_type'][0]== 'vimeo_video' && isset( $post_meta['gw_go_portfolio_lightbox_vimeo_video_id'][0] ) ) {
															$color = isset( $post_meta['gw_go_portfolio_lightbox_vimeo_video_c'][0] ) && !empty( $post_meta['gw_go_portfolio_lightbox_vimeo_video_c'][0] ) ? 
															( mb_strlen( $post_meta['gw_go_portfolio_lightbox_vimeo_video_c'][0] = preg_replace( '/[^0-9a-f]/','', $post_meta['gw_go_portfolio_lightbox_vimeo_video_c'][0] ) ) == 6 ? $post_meta['gw_go_portfolio_lightbox_vimeo_video_c'][0] : '0' ) : '0';																		
															$lighbox_link = '//vimeo.com/' . $post_meta['gw_go_portfolio_lightbox_vimeo_video_id'][0] . '?color=' . $color;
														} elseif ( isset( $post_meta['gw_go_portfolio_lightbox_video_type'][0] ) && $post_meta['gw_go_portfolio_lightbox_video_type'][0]== 'screenr_video' && isset( $post_meta['gw_go_portfolio_lightbox_screenr_video_id'][0] ) ) {
															$lighbox_link = 'http://www.screenr.com/embed/' . $post_meta['gw_go_portfolio_lightbox_screenr_video_id'][0];
														} elseif ( isset( $post_meta['gw_go_portfolio_lightbox_video_type'][0] ) && $post_meta['gw_go_portfolio_lightbox_video_type'][0]== 'dailymotion_video' && isset( $post_meta['gw_go_portfolio_lightbox_dailymotion_video_id'][0] ) ) {
															$lighbox_link = '//dailymotion.com/embed/video/' . $post_meta['gw_go_portfolio_lightbox_dailymotion_video_id'][0];
														} elseif ( isset( $post_meta['gw_go_portfolio_lightbox_video_type'][0] ) && $post_meta['gw_go_portfolio_lightbox_video_type'][0]== 'metacafe_video' && isset( $post_meta['gw_go_portfolio_lightbox_metacafe_video_id'][0] ) ) {
															$lighbox_link = 'http://www.metacafe.com/embed/' . $post_meta['gw_go_portfolio_lightbox_metacafe_video_id'][0];
														}
														
														/* Audio types */
														if ( isset( $post_meta['gw_go_portfolio_lightbox_audio_type'][0] ) && $post_meta['gw_go_portfolio_lightbox_audio_type'][0]== 'soundcloud_audio' && isset( $post_meta['gw_go_portfolio_lightbox_soundcloud_audio_id'][0] ) ) {
															$color = isset( $post_meta['gw_go_portfolio_lightbox_soundcloud_audio_c'][0] ) && !empty( $post_meta['gw_go_portfolio_lightbox_soundcloud_audio_c'][0] ) ? 
															( mb_strlen( $post_meta['gw_go_portfolio_lightbox_soundcloud_audio_c'][0] = preg_replace( '/[^0-9a-f]/','', $post_meta['gw_go_portfolio_lightbox_soundcloud_audio_c'][0] ) ) == 6 ? $post_meta['gw_go_portfolio_lightbox_soundcloud_audio_c'][0] : '0' ) : '0';																		
															$lighbox_link = '//w.soundcloud.com/player/?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F'. $post_meta['gw_go_portfolio_lightbox_soundcloud_audio_id'][0] . '&amp;color=' . $color . '&amp;auto_play=true&amp;show_artwork=true';
															$popup_height = 166;
														} elseif ( isset( $post_meta['gw_go_portfolio_lightbox_audio_type'][0] ) && $post_meta['gw_go_portfolio_lightbox_audio_type'][0]== 'mixcloud_audio' && isset( $post_meta['gw_go_portfolio_lightbox_mixcloud_audio_id'][0] ) ) {
															$color = isset( $post_meta['gw_go_portfolio_lightbox_mixcloud_audio_c'][0] ) && !empty( $post_meta['gw_go_portfolio_lightbox_mixcloud_audio_c'][0] ) ? 
															( mb_strlen( $post_meta['gw_go_portfolio_lightbox_mixcloud_audio_c'][0] = preg_replace( '/[^0-9a-f]/','', $post_meta['gw_go_portfolio_lightbox_mixcloud_audio_c'][0] ) ) == 6 ? $post_meta['gw_go_portfolio_lightbox_mixcloud_audio_c'][0] : '0' ) : '0';																		
															$lighbox_link = '//www.mixcloud.com/widget/iframe/?feed='. urlencode( trim( $post_meta['gw_go_portfolio_lightbox_mixcloud_audio_id'][0], '/' ) ) . '%2F&amp;show_tracklist=&amp;stylecolor=' . $color;
															$popup_height = 480;
														} elseif ( isset( $post_meta['gw_go_portfolio_lightbox_audio_type'][0] ) && $post_meta['gw_go_portfolio_lightbox_audio_type'][0]== 'beatport_audio' && isset( $post_meta['gw_go_portfolio_lightbox_beatport_audio_id'][0] ) ) {
															$lighbox_link = 'http://embed.beatport.com/player?id=' . $post_meta['gw_go_portfolio_lightbox_beatport_audio_id'][0] . '&type=track&auto=1';
															$popup_height = 166;
														} 																	

													} elseif ( $lighbox_type == 'other' ) {
														$lighbox_link = '#';
														$lighbox_class = 'gw-gopf-magnific-popup-html';

														if ( isset( $portfolio['overlay-style'] ) && $portfolio['overlay-style'] == '2' ) {
															$button_content = isset( $portfolio['overlay-btn-link-video'] ) ? $portfolio['overlay-btn-link-video'] : '';
														} elseif ( isset( $portfolio['overlay-style'] ) && $portfolio['overlay-style'] == '1' ) {
															$button_content = '<img src="' . GW_GO_PORTFOLIO_URI . 'assets/images/icon_large.png" class="gw-gopf-retina" alt="' . __( 'Show more', 'go_portfolio_textdomain' ) . '">';
														}
														
														if ( isset( $post_meta['gw_go_portfolio_lightbox_other_type'][0] ) && $post_meta['gw_go_portfolio_lightbox_other_type'][0]== 'custom_iframe' && isset( $post_meta['gw_go_portfolio_lightbox_iframe_url'][0] ) ) {
															$popup_height = isset( $post_meta['gw_go_portfolio_lightbox_iframe_h'][0] ) && !empty( $post_meta['gw_go_portfolio_lightbox_iframe_h'][0] ) ? 
															( floatval( $post_meta['gw_go_portfolio_lightbox_iframe_h'][0] ) != 0 ? floatval( $post_meta['gw_go_portfolio_lightbox_iframe_h'][0] ) : null ) : null;
															$lighbox_link = isset( $post_meta['gw_go_portfolio_lightbox_iframe_url'][0] ) && !empty( $post_meta['gw_go_portfolio_lightbox_iframe_url'][0] ) ? $post_meta['gw_go_portfolio_lightbox_iframe_url'][0] : '#'; 
														}
														
														
													}
													
													$lighbox_content = '';
													
													/* Lightbox button links */
													$post_lb_button_data_raw = '<a title="' . esc_attr( isset( $portfolio['lightbox-caption'] ) ? trim( get_the_title() ) : '' ) . '" data-id="' . $post->ID . '_' . $portfolio_key . '" href="' . $lighbox_link . '" data-content="' . $lighbox_content . '" data-mfp-src="' . $lighbox_link . '" class="' . $lighbox_class . ' gw-gopf-post-overlay gw-gopf-post-overlay-link"' . ( isset( $popup_height ) ? ' data-height="' . $popup_height . '"' : '' ) . '>';
													         $post_lb_button = '<a title="' . esc_attr( isset( $portfolio['lightbox-caption'] ) ? trim( get_the_title() ) : '' ) . '" data-id="' . $post->ID . '_' . $portfolio_key . '" href="' . $lighbox_link . '" data-content="' . $lighbox_content . '" data-mfp-src="' . $lighbox_link . '" class="' . $button_style_class . ' ' . $lighbox_class . '"' . ( isset( $popup_height ) ? ' data-height="' . $popup_height . '"' : '' ) . '>' . $button_content . '</a>';
																
													/* Read more button links */
													if ( isset( $portfolio['overlay-style'] ) && $portfolio['overlay-style'] == '2' ) {
														$button_content = isset( $portfolio['overlay-btn-link-post'] ) ? $portfolio['overlay-btn-link-post'] : '';
													} elseif ( isset( $portfolio['overlay-style'] ) && $portfolio['overlay-style'] == '1' ) {
														$button_content = '<img src="' . GW_GO_PORTFOLIO_URI . 'assets/images/icon_link.png" class="gw-gopf-retina" alt="">';
													}														
													
													$post_link_button_data_raw = '<a href="' . $template_data['post_link'] . '" class="gw-gopf-post-overlay gw-gopf-post-overlay-link"' . ( isset( $post_meta['gw_go_portfolio_post_link_target'][0] ) ? ' target="_blank"' : '' ) . '>';
													$post_link_button = '<a href="' . $template_data['post_link'] . '" class="' . $button_style_class . '"' . ( isset( $post_meta['gw_go_portfolio_post_link_target'][0] ) ? ' target="_blank"' : '' ) . '>' . $button_content . '</a>';
															
													/* Overlay and button */
													if ( $has_overlay ) {	
														$template_data['post_overlay_buttons'] = '<div class="gw-gopf-post-overlay-bg"></div><div class="gw-gopf-post-overlay-inner">';
														if ( isset( $portfolio['overlay-button-lb'] ) && !isset( $post_meta['gw_go_portfolio_hide_lightbox_button'][0] ) ) {
																$template_data['post_overlay_buttons'] .= $post_lb_button;
														}
														if ( isset( $portfolio['overlay-button-link'] ) && !isset( $post_meta['gw_go_portfolio_hide_link_button'][0] ) ) {
																$template_data['post_overlay_buttons'] .= $post_link_button;
														}
														$template_data['post_overlay_buttons'] .= '</div>';														
													}
													
													/* 4. Image thumbnail */
													$template_data['post_media'] = null;													
													if ( $thumbnail_type == 'image' ) {																													
														$tn_id = null;
														$tn_img_data = null;
														$tn_img_file = null;
														$lb_img_data = null;
														$lb_img_file = null;														
														$img_height = null;
														if ( isset( $portfolio['width'] ) && !empty( $portfolio['width'] ) && floatval( $portfolio['width'] ) > 0 && isset( $portfolio['height'] ) && !empty( $portfolio['height'] ) && floatval( $portfolio['height'] ) > 0 ) {
															$img_ratio = floatval( $portfolio['height'] ) / floatval( $portfolio['width'] );
														} else {
															$img_height = isset( $portfolio['height'] ) && !empty( $portfolio['height'] ) && floatval( $portfolio['height'] ) > 0 ? floatval( $portfolio['height'] ) : null;
														}
														$thumb_img_src = get_post_meta( $post->ID, 'gw_go_portfolio_thumb_img_src' ,true );
														if ( ( !isset( $portfolio['overlay-button-lb'] ) || isset( $post_meta['gw_go_portfolio_hide_lightbox_button'][0] ) ) 
														&& ( !isset( $portfolio['overlay-button-link'] ) || isset( $post_meta['gw_go_portfolio_hide_link_button'][0] ) ) 
														|| ( !isset( $portfolio['overlay'] ) || isset( $post_meta['gw_go_portfolio_hide_overlay'][0] ) ) ) { 
															if ( isset( $portfolio['media-link'] ) && $portfolio['media-link'] == 'lightbox' ) {
																$template_data['post_media'] = $post_lb_button_data_raw . '</a>';
															} elseif ( isset( $portfolio['media-link'] ) && $portfolio['media-link'] == 'link' ) {
																$template_data['post_media'] = $post_link_button_data_raw . '</a>';
															} else {
																$template_data['post_media']='';
															}
														}
														$matches=null;
														preg_match( '/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $post->post_content, $matches );
														if ( isset( $portfolio['first-img-thumb'] ) && $portfolio['first-img-thumb'] == 'force' && isset( $matches ) && !empty( $matches ) ) {
															$tn_img_file = $lb_img_file = $matches[1];
															$template_data['post_media'] .= '<div class="gw-gopf-post-media-wrap" style="background-image:url(\'' . $tn_img_file . '\'); filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=' . $tn_img_file . ', sizingMethod=\'scale\');' . ( isset( $img_ratio ) ? ' height:0; padding-bottom:'. $img_ratio * 100 . '%' : '' ) . ( isset( $img_height ) ? 'height:' . $img_height . 'px' : '' ) . '">';															
															$template_data['post_media'] .= $matches[0];
															$template_data['post_media'] .= '</div>';													
														} elseif ( isset( $thumb_img_src[0] ) && !empty( $thumb_img_src[0] ) ) {
															$alt = '';
															if ( isset( $thumb_img_src[0] ) ) {
																$pathinfo = pathinfo( $thumb_img_src[0] );
																if ( isset( $pathinfo['filename'] ) ) $alt = $pathinfo['filename'];
															}
															$tn_img_file = $lb_img_file = $thumb_img_src[0];
															$template_data['post_media'] .= '<div class="gw-gopf-post-media-wrap" style="background-image:url(\'' . $tn_img_file . '\'); filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=' . $tn_img_file . ', sizingMethod=\'scale\');' . ( isset( $img_ratio ) ? ' height:0; padding-bottom:'. $img_ratio * 100 . '%' : '' ) . ( isset( $img_height ) ? 'height:' . $img_height . 'px' : '' ) . '">';
															$template_data['post_media'] .= '<img src="' . $thumb_img_src[0] . '" alt="' . $alt . '">';
															$template_data['post_media'] .= '</div>';															
														} elseif ( has_post_thumbnail() || $arg_post_type == 'attachment' ) {
															global $wp_version;
															if ( version_compare( $wp_version, 4.2, ">=" ) ) {	
																$tn_id = $arg_post_type == 'attachment' ? $post->ID : get_post_thumbnail_id( $post->ID );														
															} else {
																$tn_id = $arg_post_type == 'attachment' ? $post->guid : get_post_thumbnail_id( $post->ID );
															}
															$tn_img_data = wp_get_attachment_image_src( $tn_id, $thumbanail_size );
															$lb_img_data = wp_get_attachment_image_src( $tn_id, $lightbox_size );

															$alt = '';
															$alt = get_post_meta( $tn_id, '_wp_attachment_image_alt', true );
															if ( $alt == '') {
																$attachment = get_post( $tn_id );
																if ( !empty( $attachment ) ) $alt = $attachment->post_title;
															}
															if ( !empty( $alt ) ) $alt = trim( strip_tags( $alt ) );
															
															if ( $tn_img_data ) { $tn_img_file = $tn_img_data[0]; }
															if ( $lb_img_data ) { $lb_img_file = $lb_img_data[0]; }
															$template_data['post_media'] .= '<div class="gw-gopf-post-media-wrap" style="background-image:url(\'' . $tn_img_file . '\'); filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=' . $tn_img_file . ', sizingMethod=\'scale\');' . ( isset( $img_ratio ) ? ' height:0; padding-bottom:'. $img_ratio * 100 . '%' : '' ) . ( isset( $img_height ) ? 'height:' . $img_height . 'px' : '' ) . '">';
															$template_data['post_media'] .= $arg_post_type == 'attachment' ? '<img src="' . $tn_img_file . '" alt="' . $alt . '">' : get_the_post_thumbnail( $post->ID, $thumbanail_size );
															$template_data['post_media'] .= '</div>';															
														} elseif ( isset( $portfolio['first-img-thumb'] ) && $portfolio['first-img-thumb'] == 'fallback' && isset( $matches ) && !empty( $matches ) ) {
															$tn_img_file = $lb_img_file = $matches[1];
															$template_data['post_media'] .= '<div class="gw-gopf-post-media-wrap" style="background-image:url(\'' . $tn_img_file . '\'); filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=' . $tn_img_file . ', sizingMethod=\'scale\');' . ( isset( $img_ratio ) ? ' height:0; padding-bottom:'. $img_ratio * 100 . '%' : '' ) . ( isset( $img_height ) ? 'height:' . $img_height . 'px' : '' ) . '">';
															$template_data['post_media'] .= $matches[0];
															$template_data['post_media'] .= '</div>';
														}
														$template_data['post_media'] .='';
													} 
													
													/* 5. Video & audio thumbnail */												
													if ( $thumbnail_type == 'video' || $thumbnail_type == 'audio' ) {
														$height = null;
														$video_type = isset( $post_meta['gw_go_portfolio_thumb_type'][0] ) && $post_meta['gw_go_portfolio_thumb_type'][0] == 'video' ? $post_meta['gw_go_portfolio_thumb_video_type'][0] : null;
														$audio_type = isset( $post_meta['gw_go_portfolio_thumb_type'][0] ) && $post_meta['gw_go_portfolio_thumb_type'][0] == 'audio' ? $post_meta['gw_go_portfolio_thumb_audio_type'][0] : null;
														$portfolio['width'] = isset( $portfolio['width'] ) && !empty( $portfolio['width'] ) ? floatval( $portfolio['width'] ) : null;

														/* Video types */
														if ( $video_type ) {
															$media_ratio = $portfolio['width'] && !empty( $portfolio['width'] ) && $portfolio['height'] && !empty( $portfolio['height'] ) ? $portfolio['height'] / $portfolio['width'] : 0.5625;
															if ( $video_type == 'youtube_video' ) {	
																$post_meta['gw_go_portfolio_thumbnail_youtube_video_h'][0] = isset( $post_meta['gw_go_portfolio_thumbnail_youtube_video_h'][0] ) && !empty( $post_meta['gw_go_portfolio_thumbnail_youtube_video_h'][0] ) ? floatval( $post_meta['gw_go_portfolio_thumbnail_youtube_video_h'][0] ) : null;
																$height = $post_meta['gw_go_portfolio_thumbnail_youtube_video_h'][0] && !empty( $post_meta['gw_go_portfolio_thumbnail_youtube_video_h'][0] ) ? $post_meta['gw_go_portfolio_thumbnail_youtube_video_h'][0] : null;
																$media_embed='<iframe src="//www.youtube.com/embed/' . ( isset( $post_meta['gw_go_portfolio_thumbnail_youtube_video_id'][0] ) ?  $post_meta['gw_go_portfolio_thumbnail_youtube_video_id'][0] : '' ) . '?wmode=opaque" frameborder="0" height="100%" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
															} elseif ( $video_type == 'vimeo_video' ) {
																$post_meta['gw_go_portfolio_thumbnail_vimeo_video_h'][0] = isset( $post_meta['gw_go_portfolio_thumbnail_vimeo_video_h'][0] ) && !empty( $post_meta['gw_go_portfolio_thumbnail_vimeo_video_h'][0] ) ? floatval( $post_meta['gw_go_portfolio_thumbnail_vimeo_video_h'][0] ) : null;
																$height = $post_meta['gw_go_portfolio_thumbnail_vimeo_video_h'][0] && !empty( $post_meta['gw_go_portfolio_thumbnail_vimeo_video_h'][0] ) ? $post_meta['gw_go_portfolio_thumbnail_vimeo_video_h'][0] : null;																
																$color = isset( $post_meta['gw_go_portfolio_thumbnail_vimeo_video_c'][0] ) && !empty( $post_meta['gw_go_portfolio_thumbnail_vimeo_video_c'][0] ) ? 
																( mb_strlen( $post_meta['gw_go_portfolio_thumbnail_vimeo_video_c'][0] = preg_replace( '/[^0-9a-f]/','', $post_meta['gw_go_portfolio_thumbnail_vimeo_video_c'][0] ) ) == 6 ? $post_meta['gw_go_portfolio_thumbnail_vimeo_video_c'][0] : '0' ) : '0';
																$media_embed='<iframe src="//player.vimeo.com/video/' . ( isset( $post_meta['gw_go_portfolio_thumbnail_vimeo_video_id'][0] ) ?  $post_meta['gw_go_portfolio_thumbnail_vimeo_video_id'][0] : '' ) . '?wmode=opaque&color=' . $color . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';													
															} elseif ( $video_type == 'screenr_video' ) {
																$post_meta['gw_go_portfolio_thumbnail_screenr_video_h'][0] = isset( $post_meta['gw_go_portfolio_thumbnail_screenr_video_h'][0] ) && !empty( $post_meta['gw_go_portfolio_thumbnail_screenr_video_h'][0] ) ? floatval( $post_meta['gw_go_portfolio_thumbnail_screenr_video_h'][0] ) : null;
																$height = $post_meta['gw_go_portfolio_thumbnail_screenr_video_h'][0] && !empty( $post_meta['gw_go_portfolio_thumbnail_screenr_video_h'][0] ) ? $post_meta['gw_go_portfolio_thumbnail_screenr_video_h'][0] : null;
																$media_embed='<iframe src="http://www.screenr.com/embed/' . ( isset( $post_meta['gw_go_portfolio_thumbnail_screenr_video_id'][0] ) ?  $post_meta['gw_go_portfolio_thumbnail_screenr_video_id'][0] : '' ) . '"?wmode=opaque" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';																									
															} elseif ( $video_type == 'dailymotion_video' ) {
																$post_meta['gw_go_portfolio_thumbnail_dailymotion_video_h'][0] = isset( $post_meta['gw_go_portfolio_thumbnail_dailymotion_video_h'][0] ) && !empty( $post_meta['gw_go_portfolio_thumbnail_dailymotion_video_h'][0] ) ? floatval( $post_meta['gw_go_portfolio_thumbnail_dailymotion_video_h'][0] ) : null;
																$height = $post_meta['gw_go_portfolio_thumbnail_dailymotion_video_h'][0] && !empty( $post_meta['gw_go_portfolio_thumbnail_dailymotion_video_h'][0] ) ? $post_meta['gw_go_portfolio_thumbnail_dailymotion_video_h'][0] : null;
																$video_embed='<iframe src="//www.dailymotion.com/embed/video/' . ( isset( $post_meta['gw_go_portfolio_thumbnail_dailymotion_video_id'][0] ) ?  $post_meta['gw_go_portfolio_thumbnail_dailymotion_video_id'][0] : '' ) . '"?wmode=opaque" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';																									
															} elseif ( $video_type == 'metacafe_video' ) {
																$post_meta['gw_go_portfolio_thumbnail_metacafe_video_h'][0] = isset( $post_meta['gw_go_portfolio_thumbnail_metacafe_video_h'][0] ) && !empty( $post_meta['gw_go_portfolio_thumbnail_metacafe_video_h'][0] ) ? floatval( $post_meta['gw_go_portfolio_thumbnail_metacafe_video_h'][0] ) : null;
																$height = $post_meta['gw_go_portfolio_thumbnail_metacafe_video_h'][0] && !empty( $post_meta['gw_go_portfolio_thumbnail_metacafe_video_h'][0] ) ? $post_meta['gw_go_portfolio_thumbnail_metacafe_video_h'][0] : null;
																$media_embed='<iframe src="http://www.metacafe.com/embed/' . ( isset( $post_meta['gw_go_portfolio_thumbnail_metacafe_video_id'][0] ) ?  $post_meta['gw_go_portfolio_thumbnail_metacafe_video_id'][0] : '' ) . '"?wmode=opaque" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';																									
															}
														}
														
														/* Audio types */
														if ( $audio_type ) {
															$media_ratio = $portfolio['width'] && !empty( $portfolio['width'] ) && $portfolio['height'] && !empty( $portfolio['height'] ) ? $portfolio['height'] / $portfolio['width'] : 0.5625;
															if ( isset( $post_meta['gw_go_portfolio_thumb_audio_type'][0] ) && $post_meta['gw_go_portfolio_thumb_audio_type'][0]== 'soundcloud_audio' && isset( $post_meta['gw_go_portfolio_thumbnail_soundcloud_audio_id'][0] ) ) {
																$post_meta['gw_go_portfolio_thumbnail_soundcloud_audio_h'][0] = isset( $post_meta['gw_go_portfolio_thumbnail_soundcloud_audio_h'][0] ) && !empty( $post_meta['gw_go_portfolio_thumbnail_soundcloud_audio_h'][0] ) ? floatval( $post_meta['gw_go_portfolio_thumbnail_soundcloud_audio_h'][0] ) : null;
																$height = $post_meta['gw_go_portfolio_thumbnail_soundcloud_audio_h'][0] && !empty( $post_meta['gw_go_portfolio_thumbnail_soundcloud_audio_h'][0] ) ? $post_meta['gw_go_portfolio_thumbnail_soundcloud_audio_h'][0] : null;																
																$color = isset( $post_meta['gw_go_portfolio_thumbnail_soundcloud_audio_c'][0] ) && !empty( $post_meta['gw_go_portfolio_thumbnail_soundcloud_audio_c'][0] ) ? 
																( mb_strlen( $post_meta['gw_go_portfolio_thumbnail_soundcloud_audio_c'][0] = preg_replace( '/[^0-9a-f]/','', $post_meta['gw_go_portfolio_thumbnail_soundcloud_audio_c'][0] ) ) == 6 ? $post_meta['gw_go_portfolio_thumbnail_soundcloud_audio_c'][0] : '0' ) : '0';																		
																$media_embed = '<iframe src="//w.soundcloud.com/player/?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F'. $post_meta['gw_go_portfolio_thumbnail_soundcloud_audio_id'][0] . '&amp;color=' . $color . '&amp;show_artwork=true&amp;wmode=opaque" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
															} elseif ( isset( $post_meta['gw_go_portfolio_thumb_audio_type'][0] ) && $post_meta['gw_go_portfolio_thumb_audio_type'][0]== 'mixcloud_audio' && isset( $post_meta['gw_go_portfolio_thumbnail_mixcloud_audio_id'][0] ) ) {
																$post_meta['gw_go_portfolio_thumbnail_mixcloud_audio_h'][0] = isset( $post_meta['gw_go_portfolio_thumbnail_mixcloud_audio_h'][0] ) && !empty( $post_meta['gw_go_portfolio_thumbnail_mixcloud_audio_h'][0] ) ? floatval( $post_meta['gw_go_portfolio_thumbnail_mixcloud_audio_h'][0] ) : null;
																$height = $post_meta['gw_go_portfolio_thumbnail_mixcloud_audio_h'][0] && !empty( $post_meta['gw_go_portfolio_thumbnail_mixcloud_audio_h'][0] ) ? $post_meta['gw_go_portfolio_thumbnail_mixcloud_audio_h'][0] : null;																
																$color = isset( $post_meta['gw_go_portfolio_thumbnail_mixcloud_audio_c'][0] ) && !empty( $post_meta['gw_go_portfolio_thumbnail_mixcloud_audio_c'][0] ) ? 
																( mb_strlen( $post_meta['gw_go_portfolio_thumbnail_mixcloud_audio_c'][0] = preg_replace( '/[^0-9a-f]/','', $post_meta['gw_go_portfolio_thumbnail_mixcloud_audio_c'][0] ) ) == 6 ? $post_meta['gw_go_portfolio_thumbnail_mixcloud_audio_c'][0] : '0' ) : '0';																		
																$media_embed = '<iframe src="//www.mixcloud.com/widget/iframe/?feed='. urlencode( trim( $post_meta['gw_go_portfolio_thumbnail_mixcloud_audio_id'][0], '/' ) ) . '%2F&amp;show_tracklist=&amp;stylecolor=' . $color . '&wmode=opaque" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
															} elseif ( isset( $post_meta['gw_go_portfolio_thumb_audio_type'][0] ) && $post_meta['gw_go_portfolio_thumb_audio_type'][0]== 'beatport_audio' && isset( $post_meta['gw_go_portfolio_thumbnail_beatport_audio_id'][0] ) ) {
																$post_meta['gw_go_portfolio_thumbnail_beatport_audio_h'][0] = isset( $post_meta['gw_go_portfolio_thumbnail_beatport_audio_h'][0] ) && !empty( $post_meta['gw_go_portfolio_thumbnail_beatport_audio_h'][0] ) ? floatval( $post_meta['gw_go_portfolio_thumbnail_beatport_audio_h'][0] ) : null;
																$height = $post_meta['gw_go_portfolio_thumbnail_beatport_audio_h'][0] && !empty( $post_meta['gw_go_portfolio_thumbnail_beatport_audio_h'][0] ) ? $post_meta['gw_go_portfolio_thumbnail_beatport_audio_h'][0] : null;																
																$media_embed = '<iframe src="http://embed.beatport.com/player?id=' . $post_meta['gw_go_portfolio_thumbnail_beatport_audio_id'][0] . '&amp;type=track&amp;wmode=opaque" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
															} 															
														}

														$media_mw_style = '';
														if ( $height ) {
															$media_mw_style = ' style="height:' . $height . 'px;"';
														} else {
															$media_mw_style = ' style="padding-bottom:' . $media_ratio * 100 . '%;"';
														}
														if ( empty( $template_data['post_media'] ) ) { $template_data['post_media'] = '<div class="gw-gopf-post-media-wrap"' . $media_mw_style . '">' . $media_embed . '</div>'; }
													}
													
													/* 6. Post title - Cut title if max length property is set */
													$template_data['post_title'] = trim( get_the_title() );
													$portfolio['title-length'] = floatval( $portfolio['title-length'] );
													if ( isset( $portfolio['title-length'] ) && !empty( $portfolio['title-length'] ) ) {
														if ( mb_strlen( $template_data['post_title'] ) > $portfolio['title-length'] ) { $template_data['post_title'] = mb_substr ( get_the_title(), 0,  $portfolio['title-length'] ) . ''; }
													}
		
													/* 7. Post date */
													$template_data['post_date'] = apply_filters( 'go_portfolio_date_format', date_i18n( get_option( 'date_format' ), get_post_time( 'U', true ) ), $portfolio['id'] );
													
													/* 8. Post excerpt - custom excerpt */
													$excerpt_src = isset( $portfolio['excerpt-src'] ) && !empty ( $portfolio['excerpt-src'] ) ? $portfolio['excerpt-src'] : 'content';
													$post_content_src = $excerpt_src == 'content' ? $post->post_content : get_the_excerpt();
													$strip_shortcodes = isset( $portfolio['excerpt-strip-sc'] ) ? true : false;
													$strip_html = isset( $portfolio['excerpt-strip-html'] ) ? true : false;
													$allowed_tags = isset( $portfolio['excerpt-allowed-tags'] ) ? trim( $portfolio['excerpt-allowed-tags'] ) : '';
													$excerpt_more = isset( $portfolio['excerpt-more'] ) ? trim( $portfolio['excerpt-more'] ) : '...';
													$loop_excerpt_length = isset( $portfolio['excerpt-length'] ) && !empty ( $portfolio['excerpt-length'] ) ? $portfolio['excerpt-length'] : null;
													
													/* Post content without <!--more--> tag */
													if ( !strpos( $post->post_content, '<!--more-->') ) {
														$content = go_portfolio_wp_trim_excerpt( $post_content_src, $loop_excerpt_length, $excerpt_more, $strip_shortcodes, $strip_html, $allowed_tags );		
													} else {
														/* Post content with <!--more--> tag */
														if ( $portfolio['excerpt-src'] == 'content' ) {
															$post_content_src = substr( $post_content_src, 0, strpos( $post_content_src, '<!--more-->' ) );
														}
														$content = go_portfolio_wp_trim_excerpt( $post_content_src, $loop_excerpt_length, $excerpt_more, $strip_shortcodes, $strip_html, $allowed_tags );
													}											
													
													$template_data['post_excerpt'] = $content;										
													
													/* 9. Post button text */
													if ( isset( $template_data['post_button_text'] ) ) { unset( $template_data['post_button_text'] ); }
													$template_data['post_button_text'] = $portfolio['post-button-text'];
													
													/* 10. Post button style */
													if ( isset( $template_data['post_button_style'] ) ) { unset( $template_data['post_button_style'] ); }
													$template_data['post_button_style'] = $portfolio['post_button_style'];													
													
													/* 11. Post link target */
													$template_data['post_link_target'] = isset( $post_meta['gw_go_portfolio_post_link_target'][0] ) ? '_blank' : '_self';
													
													/* 12. Post ID */
													$template_data['post_id'] = $post->ID;
													
													/* 13. Post author */
													$template_data['post_author'] = get_the_author();
													
													/* WooCommerce template parts */
													if ( defined( 'WOOCOMMERCE_VERSION' ) && isset( $query_post_type ) && $query_post_type=='product' ) {
													
														/* 14.1. Add to Cart button */
														//$template_data['woo_add_to_cart'] = do_shortcode('[add_to_cart_url id="' . $post->ID . '"]');
														$shop_page_url = get_permalink( woocommerce_get_page_id( 'shop' ) );
											            $template_data['woo_add_to_cart'] = $shop_page_url . '?add-to-cart=' . $post->ID;
														if ( $woo_is_variation ) { $template_data['woo_add_to_cart'] = $post_link; }
														
														/* 14.2. Price */
														$sale_price = $woo_product->is_on_sale();
														$regular_price = '';
														$template_data['woo_price'] = $template_data['woo_price'] = $woo_product->get_price_html();

														/* 14.3. On Sale */
														if ( !empty ( $sale_price ) ) {
															// for future use: $template_data['woo_on_sale'] = isset( $portfolio['woo_on_sale'] ) && !empty( $portfolio['woo_on_sale'] ) ? '<div class="gw-gopf-circle gw-gopf-woo-sale">' . $portfolio['woo_on_sale'] . '</div>': 'SALE';
															$template_data['woo_on_sale'] = '<div class="gw-gopf-woo-sale">' .  __( 'SALE', 'go_portfolio_textdomain' ) . '</div>';
														} else {
															$template_data['woo_on_sale'] = '';
														}
													}
																				
													/* Replace template */
													$template = preg_replace( '/\r\n+|\r+|\n+|\t+/i', '', $template);
													$template = preg_replace( '#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $template);
													$replaced_template = $template;
													foreach( $template_data as $key => $value ) { 
														$value = addcslashes ($value, '$');
														$replaced_template = preg_replace( '/(\{\{)\s?('.$key.'+\s?)(\}\})/', $value, $replaced_template );
													}
													$replaced_template = preg_replace( '/(\{\{)\s?(.+\s?)(\}\})/', '', $replaced_template );
													echo $replaced_template;
													?>
													</div>
												</div>
												<?php 
												endwhile;
												$new_wp_query = null;
												wp_reset_postdata();
												?>			
											
											</div>
										</div>								
									</div>
									<?php 
									/* Pagination */
									
									/* Get pagination type */
									if ( isset( $portfolio['pagination-type'] ) && $portfolio['pagination-type'] == 'load-more' ) {
										$load_more_button_text = isset( $portfolio['load-more-button-text'] ) && !empty( $portfolio['load-more-button-text'] ) ? $portfolio['load-more-button-text'] : 'Load More';
										$load_more_button_loading_text = isset( $portfolio['load-more-button-loading-text'] ) && !empty( $portfolio['load-more-button-loading-text'] ) ? $portfolio['load-more-button-loading-text'] : 'Loading...';
										$button_src = '<a href="#" class="gw-gopf-pagination-load-more' . esc_attr( isset( $portfolio['load-more-button-style'] ) && !empty( $portfolio['load-more-button-style'] ) ? ' ' . $portfolio['load-more-button-style'] : '' ) .  '" data-original="' . esc_attr( $load_more_button_text ) . '" data-modified="' . esc_attr( $load_more_button_loading_text ) . '">' . $load_more_button_text . '</a>';
									}
									?>
									<div class="gw-gopf-clearfix"></div>
									<div class="gw-gopf-pagination-wrapper gw-gopf-clearfix<?php echo ( isset( $portfolio['pagination-align'] ) && !empty( $portfolio['pagination-align'] ) ?  ' '. $portfolio['pagination-align'] : '' ); ?>" data-posts="<?php echo esc_attr( $posts_count );?>" data-posts-per-page="<?php echo esc_attr( $post_per_page );?>" data-loaded="<?php echo implode( ',', $all_post_ids ); ?>" data-pages="<?php echo esc_attr( $pages_count );?>" data-current-page="<?php echo esc_attr( $current_page );?>" data-current-id="<?php echo isset( $portfolio['exclude-current'] ) ? $post->ID : '' ; ?>">
									<?php
									if ( isset( $portfolio['pagination'] ) && $layout_type == 'grid' && $pages_count > 1 ) :
									?>
										<div class="gw-gopf-pagination">
										<?php echo $button_src; ?>
										</div>
									<?php
																		endif;
									?>
									</div>
									<?php
									if ( !isset( $portfolio['filter-v-pos'] ) || ( isset( $portfolio['filter-v-pos'] ) && $portfolio['filter-v-pos'] == 'top') ) {
										$portfolio_posts_content = ob_get_contents();
										ob_end_clean();
									}
									?>
								
								<?php 
								/* Portfolio filter */
								$current_terms = array();
								$filter_terms = array();
								$term_count = array();								
								if ( !empty( $filter_tax ) && !empty( $arg_tax ) ) {
									if ( $filter_tax == $arg_tax ) {
										if ( isset( $arg_terms ) && empty( $arg_terms ) ) {
											$filter_terms = $all_post_term_list;
											if ( isset( $portfolio['post-type'] ) && $portfolio['post-type'] == 'attachment' && isset( $portfolio['gallery-query-method'] ) && $portfolio['gallery-query-method'] == 'visual' ) {
												$current_terms = get_terms( $filter_tax, 'include=' . implode ( ',', $filter_terms ) );
												$term_count = array_count_values( $filter_terms );
											} else {
												$current_terms = get_terms( $filter_tax, '' );	
											}
										} else {
											$current_terms = get_terms( $filter_tax, 'include=' . implode ( ',', $arg_terms ) );
											$filter_terms = $all_post_term_list;
										}										
									} else {
										$current_terms = get_terms( $filter_tax, '' );
										$filter_terms = $all_post_term_list;
									}
								}
								if ( isset( $portfolio['filterable'] ) && $layout_type == 'grid' ) : 
								if ( isset( $current_terms ) && !empty( $current_terms ) && !isset( $current_terms->errors ) ) :
								?>							
								<div data-tax="<?php echo esc_attr( $filter_tax ); ?>" class="gw-gopf-filter gw-gopf-clearfix <?php echo ( isset( $portfolio['filter-align'] ) && !empty( $portfolio['filter-align'] ) ?  ' '. $portfolio['filter-align'] : '' ); ?>">
									<div class="gw-gopf-cats">
										<span data-count="<?php echo esc_attr( $posts_count ); ?>" class="gw-gopf-current"><a href="#"<?php echo ( isset( $portfolio['filter-current-tag-style'] ) && !empty( $portfolio['filter-current-tag-style'] ) ? 'class="' . $portfolio['filter-current-tag-style'] . '"' : '' ); ?>><?php echo ( isset( $portfolio['filter-all-text'] ) && !empty( $portfolio['filter-all-text'] ) ? $portfolio['filter-all-text'] : 'All' ); ?></a></span><?php 
										foreach ( $current_terms as $current_term ) :
										?><span data-count="<?php echo esc_attr( isset( $term_count[$current_term->term_id] ) ? $term_count[$current_term->term_id] : $current_term->count ); ?>" data-filter="<?php echo esc_attr( $current_term->slug ); ?>" class="<?php echo isset( $filter_terms ) && is_array( $filter_terms ) && in_array($current_term->term_id, $filter_terms) ? '' : ( !isset( $portfolio['pagination'] ) ? 'gw-gopf-hidden' : '' ) ?>"><a href="#"<?php echo ( isset( $portfolio['filter-tag-style'] ) && !empty( $portfolio['filter-tag-style'] ) ? 'class="' . $portfolio['filter-tag-style'] . '"' : '' ); ?>><?php echo $current_term->name; ?></a></span><?php 
										endforeach;		
										?>
									</div>
								</div>
								<div class="gw-gopf-clearfix"></div>
								<?php 
								endif; 
								endif;
								/* /Portfolio filter */
								?>
								<?php 
								if ( !isset( $portfolio['filter-v-pos'] ) || ( isset( $portfolio['filter-v-pos'] ) && $portfolio['filter-v-pos'] == 'top') ) {
									echo $portfolio_posts_content; 
								}
								?>								
								</div>								
								</div>															
								<?php
								
								/* return shorcode */
								$shortcode_content = ob_get_contents();
								$shortcode_content = do_shortcode( $shortcode_content );
								$shortcode_content = preg_replace( '/\r\n+|\r+|\n+|\t+/i', '', $shortcode_content);
								$shortcode_content = preg_replace( '#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $shortcode_content);
								ob_end_clean();
								return $css_style . $shortcode_content;									
								break;
								
							} else {
								
								/* If custom post type doesn't exist */
								return '<p>' . sprintf( __( 'Post type with a slug of "%s" is not registered.', 'go_portfolio_textdomain' ), $portfolio['post-type'] ) . '</p>';	
							}		
					} 
				}
			}

			/* If the id doesn't exist */
			return '<p>' . sprintf( __( 'Portfolio with an id of "%s" is not defined.', 'go_portfolio_textdomain' ), $id ) . '</p>';		
		}		
				
	}	


}