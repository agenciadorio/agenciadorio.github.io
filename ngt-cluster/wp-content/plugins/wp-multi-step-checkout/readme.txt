=== WooCommerce Multi-Step Checkout ===
Created: 30/10/2017
Contributors: diana_burduja
Email: diana@burduja.eu
Tags: multistep checkout, multi-step-checkout, woocommerce, checkout, shop checkout, checkout steps, checkout wizard, checkout style, checkout page
Requires at least: 3.0.1
Tested up to: 4.9
Stable tag: 1.11
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Requires PHP: 5.2.4

Change your WooCommerce checkout page with a multi-step checkout page. This will let your customers have a faster and easier checkout process, therefore a better conversion rate for you.


== Description ==

Create a better user experience by splitting the checkout process in several steps. This will also improve your conversion rate.

The plugin was made with the use of the WooCommerce standard templates. This ensure that it should work with most the themes out there. Nevertheless, if you find that something isn't properly working, let us know in the Support forum.

= Features =

* Sleak design
* Mobile friendly
* Responsive layout
* Adjust the main color to your theme
* Inherit the form and buttons design from your theme
* Keyboard navigation

= Available translations = 

* German
* French

Tags: multistep checkout, multi-step-checkout, woocommerce, checkout, shop checkout, checkout steps, checkout wizard, checkout style, checkout page

== Installation ==

* From the WP admin panel, click "Plugins" -> "Add new".
* In the browser input box, type "WooCommerce Multi-Step Checkout".
* Select the "WooCommerce Multi-Step Checkout" plugin and click "Install".
* Activate the plugin.

OR...

* Download the plugin from this page.
* Save the .zip file to a location on your computer.
* Open the WP admin panel, and click "Plugins" -> "Add new".
* Click "upload".. then browse to the .zip file downloaded from this page.
* Click "Install".. and then "Activate plugin".

OR...

* Download the plugin from this page.
* Extract the .zip file to a location on your computer.
* Use either FTP or your hosts cPanel to gain access to your website file directories.
* Browse to the `wp-content/plugins` directory.
* Upload the extracted `wp-image-zoooom` folder to this directory location.
* Open the WP admin panel.. click the "Plugins" page.. and click "Activate" under the newly added "WooCommerce Multi-Step Checkout" plugin.

== Frequently Asked Questions ==

= The login form isn't showing in the wizard =
Please check the 'Display returning customer login reminder on the "Checkout" page' option found on the WP Admin -> WooCommerce -> Settings -> Accounts page

= Is the plugin GDPR compatible? =
The plugin doesn't add any cookies and it doesn't modify/add/delete any of the form fields. It simply reorganizes the checkout form into steps.

== Screenshots ==

1. Login form
2. Billing
3. Review Order
4. Choose Payment
5. Settings page
6. On mobile devices

== Changelog ==

= 1.11 =
* 28/07/2018
* Fix: warning for sizeof() in PHP >= 7.2
* Fix: rename the CSS enqueue identifier
* Tweak: rename the "Cheating huh?" error message

= 1.10 =
* 25/06/2018
* Fix: PHP notice for WooCommerce older than 3.0
* Fix: message in login form wasn't translated

= 1.9 =
* 21/05/2018
* Change: add instructions on how to remove the login form
* Fix: add the `woocommerce_before_checkout_form` filter even when the login form is missing
* Compatibility with the Avada theme
* Tweak: for Divi theme add the left arrow for the "Back to cart" and "Previous" button

= 1.8 =
* 31/03/2018
* Tweak: add minified versions for CSS and JS files
* Fix: unblock the form after removing the .processing CSS class
* Fix: hide the next/previous buttons on the Retailer theme 

= 1.7 =
* 07/02/2018
* Fix: keyboard navigation on Safari/Chrome
* Fix: correct Settings link on the Plugins page
* Fix: option for enabling the keyboard navigation

= 1.6 =
* 19/01/2018
* Fix: center the tabs for wider screens
* Fix: show the "Have a coupon?" form from WooCommerce

= 1.5 =
* 18/01/2018
* Fix: for logged in users show the "Next" button and not the "Skip Login" button

= 1.4 =
* 18/12/2017
* Feature: allow to change the text on Steps and Buttons
* Tweak: change the settings page appearance
* Fix: change the "Back to Cart" tag from <a> to <button> in order to keep the theme's styling
* Add French translation

= 1.3 =
* 05/12/2017
* Add "language" folder and prepare the plugin for internationalization
* Add German translation

= 1.2 =
* 20/11/2017
* Fix: the steps were collapsing on mobile
* Fix: arrange the buttons in a row on mobile

= 1.1 =
* 09/11/2017
* Add a Settings page and screenshots
* Feature: scroll the page up when moving to another step and the tabs are out of the viewport

= 1.0 =
* 30/10/2017
* Initial commit

== Upgrade Notice ==

Nothing at the moment
