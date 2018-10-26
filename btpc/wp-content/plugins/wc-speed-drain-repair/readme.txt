=== WooCommerce Speed Drain Repair ===
Contributors: wpfixit
Donate link: http://wpfixit.com
Tags: woocommerce, speed up woocomerce, woocommerce speed, fast woocommerce
Requires at least: 3.0.1
Tested up to: 4.9
Stable tag: 4.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Make WooCommerce sites BLAZING Fast!

== Description ==

WooCommerce can really drain server resources and slow down the load of your site. This plugin stops loading the extra items you do not need inside WooCommerce and speeds up WordPress core admin-ajax.php file.

**Adds the below function:**

`add_action( 'wp_enqueue_scripts', 'child_manage_woocommerce_styles', 99 );
  
function child_manage_woocommerce_styles() {
    //remove generator meta tag
    remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );
  
    //first check that woo exists to prevent fatal errors
    if ( function_exists( 'is_woocommerce' ) ) {
        //dequeue scripts and styles
        if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() ) {
            wp_dequeue_style( 'woocommerce_frontend_styles' );
            wp_dequeue_style( 'woocommerce_fancybox_styles' );
            wp_dequeue_style( 'woocommerce_chosen_styles' );
            wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
            wp_dequeue_script( 'wc_price_slider' );
            wp_dequeue_script( 'wc-single-product' );
            wp_dequeue_script( 'wc-add-to-cart' );
            wp_dequeue_script( 'wc-cart-fragments' );
            wp_dequeue_script( 'wc-checkout' );
            wp_dequeue_script( 'wc-add-to-cart-variation' );
            wp_dequeue_script( 'wc-single-product' );
            wp_dequeue_script( 'wc-cart' );
            wp_dequeue_script( 'wc-chosen' );
            wp_dequeue_script( 'woocommerce' );
            wp_dequeue_script( 'prettyPhoto' );
            wp_dequeue_script( 'prettyPhoto-init' );
            wp_dequeue_script( 'jquery-blockui' );
            wp_dequeue_script( 'jquery-placeholder' );
            wp_dequeue_script( 'fancybox' );
            wp_dequeue_script( 'jqueryui' );
        }
    }
 }`

If you are  are curious what exactly the above function does, we can explain more clearly as to what its doing. Its important to know what you are installing

**This plugin instructs WP to not load the huge variety of WooCommerce scripts unless the user is on a WooCommerce page.**

So the Non-WooCommerce pages of the site will surely load faster since many of these WooCommerce scripts are loaded on each and every page. The store itself will have some memory saved using this plugin, so the WooCommerce pages will be faster as well.

To sum it up, this plugin will turn off the WooCommerce heavy script on Non-WooCommerce pages which we see very valuable because if you are marketing your site well, there should be a blog and these pages will be Non-WooCommerce which will be indexed and drive traffic to products. Almost all visitors will land on a Non-WooCommerce page in most e-commerce sites. 

<strong>There is NO EASIER or FASTER way to speed up WooCommerce sites</strong>


== Installation ==

= Install from WP Dashboard =
  * Log into WP dashboard then click **Plugins** > **Add new** > Then under the title "Install Plugins" click **Upload** > **choose the zip** > **Activate the plugin!**

= Install from FTP =
  * Extract the zip file and drop the contents in the wp-content/plugins/ directory of your WP installation and then activate the Plugin from Plugins page. 

= THAT IS IT: You're done! =

== Frequently Asked Questions ==

= Do I need to do anything after plugin is activated =

No, once activated yo uare all set and you will see the speed differecne

== Screenshots ==

1. Before Plugin
2. After Plugin

== Changelog ==

= 1.2 November 21st, 2017  =
* Update to run on new version of WordPress core

= 1.0 September 30th, 2015  =
* First release of plugin.


== Upgrade Notice ==

= 1.0 =
Get ready for some serious speed
