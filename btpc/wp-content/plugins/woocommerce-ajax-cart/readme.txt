=== WooCommerce Ajax Cart Plugin ===
Contributors: moiseh, el.severo
Tags: woocommerce, ajax, cart, shipping
Requires at least: 4.2
Tested up to: 4.8.2
Stable tag: 1.2.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

WooCommerce AJAX Cart is a WordPress Plugin that changes the default behavior of WooCommerte Cart Page, allowing a buyer to see the Total price calculation when change the Quantity of a product, without need to manually click on "Update cart" button.

This improves the user experience when purchasing a product. No other hacks/code/theme changes is needed, this functionality is added when the plugin is activated.

[youtube https://www.youtube.com/watch?v=nXUjO2cGljs ]

Want a lot of more cool & extra features for shop, cart, product and checkout? Please check this new plugin: [WooCommerce Better Usability](https://wordpress.org/plugins/woo-better-usability/).

== Installation ==

1. Upload `woocommerce-ajax-cart.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Done. This plugin no requires extra configurations to work

== Screenshots ==

1. When user clicks on "+" or "-" of Quantity field, an AJAX request was made to update the prices.

== Changelog ==

= 1.0 =
* Initial version

= 1.1 =
* Remove product from cart automatically (AJAX) when changes quantity to zero

= 1.2 =
* Updated to work with Woocommerce 2.7
* Added "-" and "+" buttons in order to facilitate product quantity change on cart
* Added confirmation message when user selects the product quantity as zero
* Added option to display item quantity as select for better usability
* Update order totals when the cart is inside checkout page

= 1.2.1 =
* Fixed on change trigger for quantity field, on checkout page.
* Fixed event lost when remove or delete product from the cart

= 1.2.2 =
* Added translation support for most common terms
* Fixed broken dependencies on admin
* Make the quantity select respect max stock

= 1.2.3 =
* Fixed bug with woocommerce 3.2.1 when update cart totals
* Simplified the ajax call for update cart totals using the native woocommerce
* Work with PayPal for WooCommerce plugin

= 1.2.4 =
* Fixed bug that making the auto update stop working when change product quantity many times
* Making new string Updating translatable

= 1.2.5 =
* Fixed bug with woocommerce 3.3.1 not updating the cart when quantity changes

= 1.2.6 =
* Making backward compatibility with themes that not changed the update cart input to button

== Frequently Asked Questions ==

== Upgrade Notice ==
