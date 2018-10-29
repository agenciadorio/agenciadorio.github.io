=== Tesla Login Customizer ===
Contributors: TeslaThemes
Tags: login, wp-login, customizer, recaptcha, colorpicker, imagepicker, login-customizer, login-page, login-page-customizer, login-logo, wp-login-customizer,custom-login
Requires at least: 3.9
Tested up to: 4.4.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Customize your WordPress login/register/forgot password page with ease.

== Description ==
Easily customize your WordPress login page in minutes with no coding skills required. 
Transform the default log-in page into a professional and unique page that blends nicely with your brand or identity.

Customize your WordPress login/register/forgot password page with simple options. Change the layout of the login form or add layers of security with ease.

= Live demos =
* [Demo 1 - Mercury with reCaptcha](http://demo.teslathemes.com/wp-login.php)
* [Demo 2 - Venus](http://demo.teslathemes.com/levelup/wp-login.php)
* [Demo 3 - Terra](http://demo.teslathemes.com/cre8or/wp-login.php)
* [Demo 4 - Mars](http://teslathemes.com/demo/wp/magellan/wp-login.php)
* [Demo 5 - Jupiter](http://teslathemes.com/demo/wp/sevenfold/wp-login.php)

Tesla Login Customizer allows you to change almost any aspect of the login page:
= General =
* Primary Color
* Background Image
* Background Image repeat
* Background Size Type
* Background Color
* Background Color
* Font Family
* Font Variations
* Font Subset
* Font Size
* Font Color
* Redirect after Login
* Redirect after Register
* Custom Login Url

= Templates =
Template picker. Here, with one click, you can enable a pre-made theme, for the login page, from our designers. You can customize it by changing options in other tabs.

= Logo =
* Hide Logo
* Logo Image
* Logo Size Type
* Logo Width
* Logo Height
* Logo Link
* Logo image title

= Form =
* Form Position
* Form Heading
* Form BG Image
* Form BG Size Type
* Form BG Image Repeat
* Form BG Color
* Form Padding
* Form Shake Disable
* Form animation in
* Form animation Out
* Form animation Error
* Button Text Color
* Button BG Color
* reCAPTCHA
* reCAPTCHA Site Key
* reCAPTCHA Theme
* reCaptcha Challenge Type
* reCaptcha Language

= Advanced =
* Custom CSS
* Custom JS


== Installation ==

1. Upload `tesla-login-customizer` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enable one of our Login Templates or easily make one of yours by changing options.
4. Enjoy your custom login page.

== Frequently Asked Questions ==

= Is Tesla Login Customizer multisite ready ? =

Yes.

= Does Tesla Login Customizer work with custom login url ? =

Yes. Versions 1.0.4 +

= Is Tesla Login Customizer retina ready ? =

Yes. Versions 1.2+

= How to change the form padding ? =

Go to Tesla Login -> Form -> Form Padding and insert any padding values you want.

= How to customize a template ? =

Pick it in the template tab and then start changing options. Use the advanced tab to insert custom css & js.

= Do I need to use a template ? =

No. You can use the Default one which is the WordPress' default view, and just change needed settings.

= How to disable default WordPress form shake on error ? =

Go to Tesla Login -> Form -> Form Shake and disable it.

= How to add animation to the form ? =

Go to Tesla Login -> Form -> Form animation (In/Out/Error) and select your desired ones.

= How to change reCaptcha's language ? =

Go to Tesla Login -> Form -> reCaptcha Language and insert the desired language code.

== Screenshots ==

1. Demo of Mercury Template
2. Demo of Venus Template
3. Demo of Terra Template
4. Demo of Mars Template
5. Demo of Jupiter Template
6. General Options of the plugin. Here you can customize the Primary color, background image etc.
7. Template picker. Here, with one click, you can enable a pre-made theme, for the login page, from our designers. You can customize it by changing options in other tabs.
8. Logo Options. Hide it entirely or change the WP default logo. Change it's size , title and link.
9. Form Options. Choose position of the form on the login screen. Add Heading/message. Pick image or background color. Add and customize reCAPTCHA etc.

== Changelog ==
= 1.3.4 =
* Fix       : Less Library Class Compatibility

= 1.3.3 =
* Fix       : Custom CSS loading last

= 1.3.2 =
* Added     : Compatibility with plugin "My Private Site"
* Fix       : detecting login page

= 1.3.1 =
* Fix       : Forgot password placeholder
* Fix       : Forgot password submit with out animation

= 1.3 =
* Added     : reCaptcha language option

= 1.2.4 =
* Fixed     : Blurry logo on retina screens

= 1.2.3 =
* Fixed     : Multiple forms on login page submission
* Fixed     : Responsive minor fix

= 1.2.2 =
* Fixed     : Var dump appearing occasionally removed

= 1.2.1 =
* Fixed     : Font picker cache not retrieving from googleapis

= 1.2 =
* Fixed     : WP 4.4 Compatibility (only style issues)

= 1.1 =
* Fixed     : reCaptcha not appearing on Register page
* Added     : All templates are now free , even the Premium Mercury one

= 1.0.7 =
* Fixed     : Localization files not being loaded

= 1.0.6 =
* Fixed     : Error from custom css/js

= 1.0.5 =
* Fixed     : Error from array shorthand [] in PHP < 5.4.0
* Added     : Custom Login Url option added to use when the automatic way won't work

= 1.0.4 =
* Added     : Working with Custom Login URL
* Fixed     : Register & Lost Pass links not visible when font size null
* Fixed     : Error from array shorthand on older PHP versions

= 1.0.3 =
* Changed   : Load optimization

= 1.0.2 =
* Fixed     : less images path
* Added     : Demo pages for all templates

= 1.0.1 =
* Changed   : Local Images
* Fixed     : less for WP installations with register on

= 1.0 =
* Initial release.