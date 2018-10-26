<?php
/* 
Plugin Name: Ultimate WooCommerce Expandable Categories 
Plugin URI: http://magniumthemes.com/
Description: Add Expand subcategories feature for default WooCommerce Categories widget.
Version: 1.2
Author: MagniumThemes
Author URI: http://magniumthemes.com/
Copyright MagniumThemes.com. All rights reserved.
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* Register hook */
class MGWC {

	public function __construct() {
		register_activation_hook( __FILE__, array( $this, 'ob_install' ) );
		register_deactivation_hook( __FILE__, array( $this, 'ob_uninstall' ) );

		/**
		 * add action of plugin
		 */
		add_action( 'admin_init', array( $this, 'obScriptInit' ) );
		add_action( 'init', array( $this, 'obScriptInitFrontend' ) );

		/*Setting*/
		add_action( 'plugins_loaded', array( $this, 'init_mgwoocommercecat' ) );

		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );

		add_action( 'admin_notices', array( $this, 'show_admin_notice') );
		
	}


	public function show_admin_notice() {
    ?>
    <div class="uwc-message error notice is-dismissible" style="display:none;">
        <p><?php _e( '<strong>You are using FREE Version of Ultimate WooCommerce Expandable Categories plugin without this additional features:</strong>', 'mgwoocommercecat' ); ?></p>
        <ul>
        	<li>- Multilevel categories support</li>
        	<li>- Highlight active category</li>
        	<li>- Plugin work with ANY theme that support WooCommerce</li>
        	<li>- Open all parent categories if active category located inside</li>
        	<li>- Detailed Documentation guide</li>
        	<li>- Free Plugin updates and dedicated support</li>
        </ul>
    	<a style="margin:10px 0; display:block;" href="//www.bluehost.com/track/magniumthemes/uwc" target="_blank">
        <img border="0" src="<?php echo plugin_dir_url( '' ) . basename( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR; ?>img/hosting-wp-button.png">
        </a>

        <a class="button-primary" style="margin-bottom: 10px;" href="http://codecanyon.net/item/ultimate-woocommerce-expandable-categories/12048496/?ref=dedalx" target="_blank">Update to PRO version to get premium features</a>
    
    </div>

                    
	<?php
	}
	/**
	 * This is an extremely useful function if you need to execute any actions when your plugin is activated.
	 */
	function ob_install() {
		global $wp_version;
		If ( version_compare( $wp_version, "2.9", "<" ) ) {
			deactivate_plugins( basename( __FILE__ ) ); // Deactivate our plugin
			wp_die( "This plugin requires WordPress version 2.9 or higher." );
		}
	}

	/**
	 * This function is called when deactive.
	 */
	function ob_uninstall() {
		//do something
	}

	/**
	 * Function set up include javascript, css.
	 */
	function obScriptInit() {
		wp_enqueue_script( 'mgwc-script-admin', plugin_dir_url( '' ) . basename( dirname( __FILE__ ) ) . '/js/mgwoocommercecat-admin.js', array(), false, true );
		wp_enqueue_style( 'mgwc-style-admin', plugin_dir_url( '' ) . basename( dirname( __FILE__ ) ) . '/css/mgwoocommercecat-admin.css' );
	}

	function obScriptInitFrontend() {
		wp_enqueue_script( 'mgwc-script-frontend', plugin_dir_url( '' ) . basename( dirname( __FILE__ ) ) . '/js/mgwoocommercecat.js', array(), false, true );
		wp_enqueue_style( 'mgwc-style-frontend', plugin_dir_url( '' ) . basename( dirname( __FILE__ ) ) . '/css/mgwoocommercecat.css' );

	}

	/**
	 * Init when plugin load
	 */
	function init_mgwoocommercecat() {
		load_plugin_textdomain( 'mgwoocommercecat' );
		$this->load_plugin_textdomain();
	}

	/*Load Language*/
	function replace_mgwoocommercecat_default_language_files() {

		$locale = apply_filters( 'plugin_locale', get_locale(), 'mgwoocommercecat' );

		return plugins_url( "languages/mgwoocommercecat-$locale.mo", __FILE__ );

	}

	/**
	 * Function load language
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'mgwoocommercecat' );

		// Admin Locale
		if ( is_admin() ) {

			load_textdomain( 'mgwoocommercecat', plugins_url( "languages/mgwoocommercecat-$locale.mo", __FILE__ ) );
		}

		// Global + Frontend Locale
		load_textdomain( 'mgwoocommercecat', plugins_url( "languages/mgwoocommercecat-$locale.mo", __FILE__ ) );
		load_plugin_textdomain( 'mgwoocommercecat', false, WP_PLUGIN_DIR . plugins_url( "languages/", __FILE__ ) );
	}

	/*
	 * Function Setting link in plugin manager
	 */
	public function plugin_row_meta( $links, $file ) {
		if ( $file == plugin_basename( __FILE__ ) ) {
			$row_meta = array(
				'getpro'	=>	'<a href="http://codecanyon.net/item/ultimate-woocommerce-expandable-categories/12048496/?ref=dedalx" target="_blank" style="color: blue;font-weight:bold;">' . __( 'Get PRO version', 'mgwoocommercecat' ) . '</a>',
				'about'	=>	'<a href="http://magniumthemes.com/" target="_blank" style="color: red;font-weight:bold;">' . __( 'Premium WordPress Themes & Plugins', 'mgwoocommercecat' ) . '</a>',
			);

			return array_merge( $links, $row_meta );
		}

		return (array) $links;
	}

}

$mgwoocommercecat = new MGWC();
?>