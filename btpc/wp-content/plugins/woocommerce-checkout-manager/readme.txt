=== WooCommerce Checkout Manager ===

Contributors: visser, visser.labs, Emark
Donate link: https://www.visser.com.au/donations/
Tags: woocommerce, ecommerce, e-commerce, store, cart, checkout, manager, editor, field, shipping, billing, order
Requires at least: 3.0
Tested up to: 4.9.5
Stable tag: 4.2.1
License: GPLv2 or later

Manages WooCommerce Checkout, the advanced way.

== Description ==

WooCommerce Checkout Manager allows you to customise and manage the fields on your [WooCommerce](http://wordpress.org/plugins/woocommerce/) Checkout page. Re-order, rename, hide and extend Checkout fields within the Billing, Shipping and Additional sections.

**Notice**: There has been a change of Plugin ownership on 11/03/2016, please see the *Change of Plugin ownership* section below for more information.

= FEATURES =

* Add new fields to the checkout page and re-order them.
* Make checkout fields optional.
* Remove & Make required fields. 
* Added fields will appear on Order Summary, Receipt and Back-end in Orders.
* Enable/ Disable "Additional Fields" section name on the Order Summary and Receipt.
* **Fifteen ( 15 )** fields types included: Text Input, Text Area, Password, Radio Button, Check Box, Select Options, Date Picker, Time Picker, Color Picker, Heading, Multi-Select, Multi-Checkbox, Country, State, File Picker.
* Compatible with [WPML](http://wpml.org/), [WooCommerce Print Invoice & Delivery Note](http://wordpress.org/plugins/woocommerce-delivery-notes/), [ Store Exporter Deluxe](http://www.visser.com.au/plugins/store-exporter-deluxe//), [ WooCommerce Order/Customer CSV Export](http://www.woothemes.com/products/ordercustomer-csv-export/).
* Show or Hide fields for different User Roles.
* Upload files on Checkout Page.
* Sort Orders by Field Name.
* Export Orders by Field Name.
* Add new fields to the **Billing** and **Shipping** sections **separately** from Additional fields. 
* These fields can be edited on your customers **account** page.
* Create Conditional Fields.
* Create fields to remove tax.
* Create fields to add additional amount.
* Replace Text using Text/ Html Swapper.
* Allow Customers to **Upload files** for each order on order details page.
* Show or Hide added field for Specific Product or Category Only.
* Display **Payment Method** and Shipping Method used by customer.
* Disable any added field from Checkout details page and Order Receipt.
* **Retain fields information** for customers when they navigate back and forth from checkout.
* Disable Billing Address fields for chosen shipping goods. Which makes them visible only for virtual goods.
* **DatePicker:** Change the default format (dd-mm-yy), Set Minimum Date and Maximum Date, Disable days in the week (Sun - Sat).
* **TimePicker:** Includes restriction of both start and end hours, set the minutes interval and manually input labels.
* Area to insert your own **Custom CSS**.
* Display **Order Time**.
* Set Default State for checkout.
* **Import/ Export** added fields data.
* Fields label can accept html characters.
* Re-position the added fields: Before Shipping Form, After Shipping Form, Before Billing Form, After Billing Form or After Order Notes
* **Insert Notice:** Before Customer Address Fields and Before Order Summary on checkout page.

= Change of Plugin ownership =

11/03/2016 marks a change of ownership of WooCommerce Checkout Manager from Emark to visser who will be responsible for resolving critical Plugin issues and ensuring the Plugin meets WordPress security and coding standards in the form of regular Plugin updates.

== Installation ==

= Minimum Requirements =

* WooCommerce 2.2 +
* WordPress 3.8 or greater
* PHP 5.2.4 or greater
* MySQL 5.0 or greater

= Automatic Plugin installation =

1. Login to your WordPress Administration
2. Navigate to the Plugins screen and click Add New
3. Within the Search Plugins text field enter 'WooCommerce Checkout Manager' and press Enter
4. Click the Install Now button

= Manual Plugin installation =

The manual installation method involves downloading the Plugin and uploading it to your web server via an FTP application. The [WordPress Codex](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation) contains instructions on how to do this.

= Updating =

Automatic updates are delivered just like any other WordPress Plugin.

== Frequently Asked Questions ==

= How do I add conditional Checkout fields? =

Read through the [Adding Conditional Checkout fields](https://www.visser.com.au/documentation/woocommerce-checkout-manager/usage/#Adding_Conditional_Checkout_Fields) walkthrough on our website.

= [Insert feature] is not working within WooCommerce Checkout Manager =

First de-activate and re-activate the WooCommerce Checkout Manager from the Plugins screen within the WordPress Administration. This triggers the Plugin installation script to be run and often resolves issues when updating from a legacy release of WooCommerce Checkout Manager (e.g. <4.0).

If your issue persists visit the [WooCommerce Checkout Manager > Support section](https://wordpress.org/support/plugin/woocommerce-checkout-manager). This is the place to comment on existing topics and raise new support topics.

= Why can't I do [insert feature] within WooCommerce Checkout Manager? =

Visit the [WooCommerce Checkout Manager > Ideas/Feature requests Trello board](https://trello.com/b/XSpf40lq) or open a new topic on the [WooCommerce Checkout Manager > Support section](https://wordpress.org/support/plugin/woocommerce-checkout-manager).

= How to fix fields that are not showing on checkout page properly? = 

Usually this is an CSS issue. If your theme comes with the option to input your very own custom CSS, you can use the abbreviation field name as part of the CSS code to set the fields in the way that you want. 

Example :
`#myfield1_field {
	float: right;
}`

= How do I review the Order data from the custom fields? =

Your Order data can be reviewed in each Order within the default WooCommerce Order Data box of the WooCommerce > Edit Order screen within the WordPress Administration. Custom fields are separated by Billing, Shipping and Additional sections.

= How do you access saved data to be used with WooCommerce PDF Invoices & Packing Slips? =

The above plugin requests that you code the fields in the template. To access the saved data, use the abbreviation name of the field. As we are using the first abbreviation field as an example. Where "myfield1" is the abbreviation name, and "My custom field:" is the label.

Example:
`<?php $wpo_wcpdf->custom_field('myfield1', 'My custom field:'); ?>`

== Screenshots ==

1. Customise or add additional fields to your Checkout screen.

2. New Checkout fields appear in the Order Summary.

3. New Checkout fields also appear in the Order Receipt e-mail sent to the customer.

4. You can add up to 15 different types of Checkout fields.

5. Text fields, Description blocks, Dropdowns, Radio lists, Date & Time Pickers, etc.

6. Add Date and Time fields to the Checkout.

7. Let you customer fill in forms using dropdown lists at Checkout.

8. Customize Checkout fields from the WooCheckout menu item in the WordPress Administration.

== Changelog ==

= 4.2.1 =
* Fixed: PHP warning on Checkout screen (thanks @chefpanda123)

= 4.2 =
* Fixed: Billing State and Shipping State required validation
* Fixed: Display required state for Billing Address 2 and Shipping Address 2 (thanks James)

= 4.1.9 =
* Fixed: Styling placement of Reset, Import and Save Changes buttons

= 4.1.8 =
* Changed: Removed Export menu until exports are fixed
* Fixed: Uploaded files notification e-mail not working (thanks John)
* Changed: Using wc_mail() instead of wp_mail() for e-mail generation

= 4.1.7 =
* Fixed: Undefined notice in e-mail template (thanks Vitor)

= 4.1.6 =
* Fixed: Replace 1 with Yes, 0 with No for checkbox default values (thanks @james-roberts)

= 4.1.5 =
* Fixed: Check for get_shipping_method and get_payment_method_title methods (thanks jobsludo)

= 4.1.4 =
* Changed: Removed wooccm_admin_updater_notice()
* Changed: Using WC localisation for '%s is a required field.'

= 4.1.3.1 =
* Fixed: Incorrectly calling Order ID in admin.php (thanks Anik)

= 4.1.3 =
* Fixed: WooCommerce 3.0 compatibility using $order->id
* Changed: Cleaned up the code across the Plugin

= 4.1.2.1 =
* Fixed: WooCommerce 3.0 compatbility in wooccm_add_payment_method_to_new_order()

= 4.1.2 =
* Fixed: Show required indicator for Billing/Shipping Address 2
* Changed: Cleaned up the code across the Plugin

= 4.1.1 =
* Fixed: PHP 7.1 compatibility on Checkout fields (thanks Marcelo)
* Added: Hover text to disabled Abbreviation fields (thanks @flaviomsantos)

= 4.1 =
* Fixed: Checkbox label not matching (thanks Laura)
* Fixed: City not updating shipping prices (thanks Alon)

= 4.0.9 =
* Added: ID to custom fields on Edit Order screen
* Added: Hover state to custom fields on Edit Order screen
* Fixed: Shipping Methods not updating at Checkout

= 4.0.8 =
* Fixed: PHP notice on Checkout screen
* Added: WordPress Action to override DatePicker Options
* Changed: Check for farbtastic on ColorPicker
* Added: Modal prompt on deleting Checkout field
* Added: Hover labels for WooCheckout fields
* Fixed: Checkout issue with Multi-Checkbox Type

= 4.0.7 =
* Changed: Wide is now the default Position for new custom Checkout fields
* Fixed: Multi-checkbox showing reversed on Checkout screen

= 4.0.6 =
* Fixed: Billing fields not showing in Edit Order screen
* Fixed: Additional checkbox required state not working
* Fixed: Billing checkbox required state not working
* Fixed: Shipping checkbox required state not working

= 4.0.5 =
* Fixed: Notice unable to be dismissed outside WooCheckout screen
* Fixed: Only dismiss notices to Users with manage_options User Capability
* Fixed: Only show Administrator Actions to Users with manage_options User Capability

= 4.0.4 =
* Fixed: Required field message for non-required fields at Checkout
* Added: Delete WCM WordPress Options to Advanced tab
* Added: Delete WCM Orders Post meta to Advanced tab
* Added: Delete WCM Users meta to Advanced tab
* Added: Confirmation prompt to Advanced tab links
* Changed: Hide empty File uploader fields on Edit Order screen
* Added: Force show Billing fields to Switches tab
* Changed: Took out all !important CSS references
* Fixed: Line-breaks being stripped from Text Area fields
* Changed: Default rows for textarea field is 5
* Changed: Default columns for textarea field is 25
* Added: wooccm_checkout_field_texarea_rows Filter for overriding default textarea field rows
* Added: wooccm_checkout_field_texarea_columns Filter for overriding default textarea field rows
* Changed: WooCheckout screen now using template files
* Changed: Center Position label to Full-width

= 4.0.3 =
* Changed: Notice references to WooCommerce Checkout Manager
* Fixed: Broken JavaScript on Checkout page (thanks mandelkind)
* Fixed: Checking for array variables before loading them
* Added: WordPress Filters to override DatePicker and TimePicker (thanks freddes51)
* Added: Additional fields appear under General Details on the Edit Order screen
* Fixed: Image editor on Checkout page when logged-in as Administrator
* Changed: Handler tab to Order Notes on WooCheckout screen
* Added: Advanced tab to WooCheckout screen
* Fixed: Heading type breaking the table on the Order Received screen

= 4.0.2 =
* Fixed: PHP warning notices on Checkout page (thanks sfowles)
* Fixed: PHP warning on Export screen
* Changed: Cleaned up the Import dialog
* Fixed: jQuery error on Billing file upload field
* Fixed: Add Order Files on Edit Order screen uploader
* Fixed: References to hard coded Plugin directory
* Fixed: References to hard coded Pro Plugin directory

= 4.0.1 =
* Changed: Change of Plugin ownership from Emark to visser
* Changed: Removed registration key engine
* Fixed: WooCheckout Admin menu entries
* Fixed: PHP warning on WooCheckout screen
* Changed: Data update required notice for 4.0+ upgrade
* Added: Modal prompt on data update notice
* Changed: Heading placement on Setting and Export screen
* Changed: Order of Sections on Export screen
* Added: Modal prompt on reset button
* Fixed: Sanitize all $_GET and $_POST data

= 4.0 =
* Validation Error Fixed.
* Fix minor security issues
* Export Options fixed
* Minor data display fixed
* User roles bug fix.
* Restrict display of fields by user roles.
* Restriction added - File Types, Max number of Uploads, Upload for order status
* Hidden toggler and Conditional conflict fixed.
* Offset fixed.
* File Upload bug fixed.
* Color Picker Update
* File Picker added
* Field filter fixes
* Checkbox fixes.
* Storage fixes.
* Checkbox Toggler deprecated - Use Option Toggler for checkbox vlaues
* Class function added.
* Checkbox & Conditional in both Billing and Shipping Fixed.
* License GUI fix.
* Conditional Biling fix #1.
* Required fix shipping #1
* Retain fields fix 1.
* GUI upgrade.
* Conditional required fix.
* important update! - Required fix 3.
* Remove duplicates in shipping column.
* important update! - Required fix 2.
* Required fields, revert back.
* Billing, Shipping Required fix.
* Hide field from product, fix.
* Reset option fix.
* Major Updates fix2.
* Major Updates fix.
* Sort by Field Name
* GUI fix.
* Copy suffix, fix.
* Included sort feature.
* Extra Export feature included.
* WooCommerce built in export compatible.
* Export fix.
* Radio button name changed.
* Session limiter on cart page fixed.
* Tax remove fixed.
* Retain fields fixed.
* Add amount fixed.
* Select options translation fixed.
* Order Details page fix 1.
* Required fields fix 1.
* Fields Display on e-mail.
* Translation in notices fixed.
* Backend fields display fixed.
* Create field limit fixed.
* Text/ Html Swapper fix.
* Fields disappears on update, fixed.
* Javascript error fixed.
* 7 field creation expanded and fixed.
* Export functions fixed.
* Upgrade notice fix.
* Minor bug fix.
* Fixes empty array errors.
* Make all fields required. 
* Minor bug fixes.
* Add new fields to the billing fields.
* Add new fields to the shipping fields.
* Fields show in Account Page.
* Select Options fixed + Required fields fixed.
* Compatible with WP 4.1
* Update of debug mode errors
* Errors fixed for debug mode.
* Fee function fixed.
* Upload bug fix. License check fix.
* Hide field bug fix.
* Multi-Checkbox included.
* Bug fix for uploading files back-end.
* Positioning + Clear added for billing and shipping section.
* Minor bug fixes.
* Datepicker languages added.
* Admin language switch added.
* WPML bug fixed.
* Bug fix in Show & Hide Field Function
* More function added for hiding of fields
* Conditional Bug fix.
* Compatibility with 2.1.7 WooCommerce && WPML
* Checkout compatibility
* minor bug fix.
* Minor bug fixes, GUI upgrade.
* Two new field types included. 
* Import/ Export added fields data.
* Fields label can accept html characters. 
* Unlimited Select Options and Radio Buttons
* Bug Fix: Automatic update fix & DatePicker
* Bug Fix: Conditional Logic

= 3.6.8 =
Add Error Fix 2.
GUI upgrade.

= 3.6.7 =
Add Error Fix.
Add WooCommerce Order/Customer CSV Export support
Able to Change additional information header

= 3.6.6 =
GUI + Code clean up.
Multi-lang Save issue fix.

= 3.6.5 =
WPML bug fixes 4

= 3.6.4 =
WPML bug fixes 3 

= 3.6.3 =
WPML bug fix 2 (translation for e-mails)

= 3.6.2 =
WPML bug fix

= 3.6.1 =
Compatibility with 2.1.7 WooCommerce && WPML

= 3.6 =
Bug fixes.

= 3.5.9 =
Bug fix.

= 3.5.81 =
Bulgarian language by Ivo Minchev

= 3.5.8 =
Bug fix.

= 3.5.7 =
Bug fix.

= 3.5.6 =
Included translations - Vietnamse, Italian, European Portuguese, Brazilian Portuguese
Layout fixed on Order Summary Page

= 3.5.5 =
Translations updated

= 3.5.4 =
Added feature.

= 3.5.3 =
bug fix- force selection for option and minor fix.

= 3.5.2 =
updating to standard.

= 3.5.1 =
Select option and checkbox functions, included.

= 3.5 =
Select date function, included.

= 3.4 =
bug fixed.

= 3.3 =
fields positioning, fixed.

= 3.2 =
code review

= 3.1 =
bug fix

= 3.0 =
Javascript fix and rename fields inserted

= 2.9 =
Bug fixes

= 2.8 =
Bug fixes

= 2.7 =
required attribute bug fix and included translations

= 2.6 =
remove fields for shipping

= 2.5 =
Added features for shipping

= 2.4 =
Localization Ready

= 2.3 =
Additional features

= 2.2 =
bug fix

= 2.1 =
Checkout process fix

= 2.0 =
Custom fields data are added to the receipt

= 1.7 =
add/remove required field for each new fields

= 1.6 =
more bugs fixed

= 1.5 =
some bugs fixed

= 1.4 =
More features added.

= 1.3 =
bug fix!

= 1.2 =
Added required attribute removal

= 1.0 =
Initial

== Upgrade Notice ==

= 2.0.1 =
The 2.0.1 Plugin update marks a change of ownership of WooCommerce Checkout Manager from Emark to visser who will be responsible for resolving critical issues and ensuring the Plugin meets WordPress security and coding standards in the form of regular Plugin updates.