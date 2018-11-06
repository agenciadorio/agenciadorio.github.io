<?php
/**
 * Plugin Name: WooCommerce Multi-Step Checkout
 * Plugin URI: https://wordpress.org/plugins/wp-multi-step-checkout/
 * Description: Nice multi-step checkout for your WooCommerce store
 * Version: 1.11
 * Author: SilkyPress
 * Author URI: https://www.silkypress.com
 * License: GPL2
 *
 * Text Domain: wp-multi-step-checkout
 * Domain Path: /languages/
 *
 * WC requires at least: 2.3.0
 * WC tested up to: 3.3.0
 * Requires PHP: 5.2.4
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPMultiStepCheckout' ) ) :
/**
 * Main WPMultiStepCheckout Class
 *
 * @class WPMultiStepCheckout
 */
final class WPMultiStepCheckout {
    public $version = '1.11';
    public $options = array();

    protected static $_instance = null;

    public $theme = '';

    /**
     * Main WPMultiStepCheckout Instance
     *
     * Ensures only one instance of WPMultiStepCheckout is loaded or can be loaded
     *
     * @static
     * @return WPMultiStepCheckout - Main instance
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
      * Cloning is forbidden.
      */
    public function __clone() {
         _doing_it_wrong( __FUNCTION__, __( 'An error has occurred. Please reload the page and try again.' ), '1.0' );
    }

    /**
     * Unserializing instances of this class is forbidden.
     */
    public function __wakeup() {
        _doing_it_wrong( __FUNCTION__, __( 'An error has occurred. Please reload the page and try again.' ), '1.0' );
    }

    /**
     * WPMultiStepCheckout Constructor
     */
    public function __construct() {

        define('WMSC_VERSION', $this->version );
        define('WMSC_PLUGIN_FILE', __FILE__);

        $this->load_plugin_textdomain();

        if ( ! function_exists( 'WC' ) ) {
          add_action( 'admin_notices', array($this, 'install_woocommerce_admin_notice' ) );
          return false;
        }

        if ( is_admin() ) {
            include_once 'includes/admin-side.php';
        }

        $this->update_14_version();

        // Replace the checkout template
        add_filter( 'woocommerce_locate_template', array( $this, 'woocommerce_locate_template' ), 10, 3 );

        $this->adjust_hooks();

        // Enqueue the scripts for the frontend
        add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
        add_action( 'wp_head', array( $this, 'wp_head') );
        add_action( 'wp_head', array( $this, 'compatibilities'), 40 );
        add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );

    }

    /**
     * Modify the default WooCommerce hooks
     */
    public function adjust_hooks() {
      // Remove login messages
      remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
      remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

      // Split the `Order` and the `Payment` tabs
      remove_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );
      remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
      add_action( 'wpmc-woocommerce_order_review', 'woocommerce_order_review', 20 );
      add_action( 'wpmc-woocommerce_checkout_payment', 'woocommerce_checkout_payment', 10 );

      // Split the `woocommerce_before_checkout_form`
      remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
      remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
      add_action( 'wpmc-woocommerce_checkout_login_form', 'woocommerce_checkout_login_form', 10 );
      add_action( 'wpmc-woocommerce_checkout_coupon_form', 'woocommerce_checkout_coupon_form', 10 );
    }

    /**
     * Load the form-checkout.php template from this plugin
     */
    public function woocommerce_locate_template( $template, $template_name, $template_path ){
        if( 'checkout/form-checkout.php' != $template_name )
          return $template;
        $template = plugin_dir_path( __FILE__ ) . 'includes/form-checkout.php';
        return $template;
    }

    /**
     * Enqueue the JS and CSS assets
     */
    public function wp_enqueue_scripts() {
        $options = get_option('wmsc_options');
        $keyboard_nav = (isset($options['keyboard_nav']) && $options['keyboard_nav'] ) ? true : false;

        $u = plugins_url('/', __FILE__) . 'assets/'; // URL of the assets folder
        $v = $this->version; // this plugin's version
        $d = array( 'jquery' ); // dependencies
        $w = false; // where? in footer?
        $p = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

        // Load scripts
        wp_register_script( 'wpmc', $u . 'js/script'.$p.'.js', $d, $v, $w );
        wp_localize_script( 'wpmc', 'WPMC', array('keyboard_nav' => $keyboard_nav ));
        wp_enqueue_script ( 'wpmc' );
        wp_enqueue_style  ( 'wpmc', $u.'css/style-progress'.$p.'.css',  array(), $v );
    }


    /**
     * Change the main color
     */
    public function wp_head() {
        $options = get_option('wmsc_options');
        $color = (isset($options['main_color'])) ? $options['main_color'] : '#1e85be';

      ?>
      <style type="text/css">
      .wpmc-tabs-wrapper .wpmc-tab-item.current::before {
          border-bottom-color: <?php echo $color; ?>;
      }
      .wpmc-tabs-wrapper .wpmc-tab-item.current .wpmc-tab-number {
          border-color: <?php echo $color; ?>
      }
      </style>
      <?php
    }


    /**
     * Compatibilities with themes
     */
    public function compatibilities() {
      if ( $this->theme('storefront')) { ?>
        <style type="text/css">
          #order_review, #order_review_heading { float: left; }
        </style>
      <?php }

      if ( $this->theme('avada')) { ?>
        <style type="text/css">
          .wpmc-nav-wrapper { float: left; margin-top: 10px; }
        </style>
      <?php }


        if ( $this->theme('theretailer')) { ?>
          <style type="text/css">
          .wpmc-nav-buttons button.button { display: none !important; }
          .wpmc-nav-buttons button.button.current { display: inline-block !important; }
          </style>
        <?php }

      if ( $this->theme('Divi')) { ?>
        <style type="text/css">
            #wpmc-back-to-cart:after, #wpmc-prev:after { display: none; } 
            #wpmc-back-to-cart:before, #wpmc-prev:before{ position: absolute; left: 1em; margin-left: 0em; opacity: 0; font-family: "ETmodules"; font-size: 32px; line-height: 1em; content: "\34"; -webkit-transition: all 0.2s; -moz-transition: all 0.2s; transition: all 0.2s; } 
            #wpmc-back-to-cart:hover, #wpmc-prev:hover { padding-right: 0.7em; padding-left: 2em; left: 0.15em; } 
            #wpmc-back-to-cart:hover:before, #wpmc-prev:hover:before { left: 0.2em; opacity: 1;}
        </style>
      <?php }

    }


    /**
     * Compatibilities with themes
     */
    public function after_setup_theme() {
        if ( $this->theme('Avada') ) { 
            if ( function_exists('avada_woocommerce_before_checkout_form' ) ) {
                remove_action( 'woocommerce_before_checkout_form', 'avada_woocommerce_before_checkout_form' );
            }

            if ( function_exists( 'avada_woocommerce_checkout_after_customer_details' ) ) {
                remove_action( 'woocommerce_checkout_after_customer_details', 'avada_woocommerce_checkout_after_customer_details' );
            }

            if ( function_exists( 'avada_woocommerce_checkout_before_customer_details' ) ) {
                remove_action( 'woocommerce_checkout_before_customer_details', 'avada_woocommerce_checkout_before_customer_details' );
            }
        }
    }


    /**
     * Is $string theme active?
     */
    public function theme($string) {
        $string = strtolower($string);
        if (empty($this->theme)) {
            $this->theme = strtolower(get_template());
        }
        if (strpos($this->theme, $string ) !== false)
            return true;

        return false;
    }


    /**
     * Admin notice that WooCommerce is not activated
     */
    public function install_woocommerce_admin_notice() {
      ?>
      <div class="error">
          <p><?php _e( 'The WP Multi-Step Checkout plugin is enabled, but it requires WooCommerce in order to work.', 'Alert Message: WooCommerce require', 'wp-multi-step-checkout' ); ?></p>
      </div>
      <?php
    }


    /**
     * Load the textdomain
     */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'wp-multi-step-checkout', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}


    /**
     * Update options array for the 1.4 version
     */
    function update_14_version() {
        if ( ! $old_options = get_option('wpmc-settings') ) return;

        require_once 'includes/settings-array.php';
        $defaults = get_wmsc_settings();


        $new_options = array();
        foreach($defaults as $_key => $_value ) {
            if ( isset($old_options[$_key]) ) {
                $new_options[$_key] = $old_options[$_key][2];
            } else {
                $new_options[$_key] = $_value['value'];
            }
        }

        update_option('wmsc_options', $new_options);
        delete_option('wpmc-settings');
    }

}

endif;

/**
 * Returns the main instance of WPMultiStepCheckout
 *
 * @return WPMultiStepCheckout
 */
function WPMultiStepCheckout() {
    return WPMultiStepCheckout::instance();
}

WPMultiStepCheckout();

function wpmc_plugin_settings_link($links) {

    $settings_link = '<a href="admin.php?page=wmsc-settings">'.__('Settings', 'wp-multi-step-checkout').'</a>';
    array_push( $links, $settings_link );
    return $links;
}
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'wpmc_plugin_settings_link');
