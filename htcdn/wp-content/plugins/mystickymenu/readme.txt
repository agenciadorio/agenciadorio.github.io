=== myStickymenu ===
Contributors: damiroquai
Donate link: http://wordpress.transformnews.com/contact
Tags: sticky, menu, header, sticky menu, sticky header, floating, floating menu
Requires at least: 3.5.1
Tested up to: 4.8
Stable tag: 1.8.9
License: GPLv2 or later

This lightweight plugin will made your menu or header sticky on top of page, after desired number of pixels when scrolled.

== Description ==
By default, sticky menu is designed to use element class ".navbar" as "Sticky Class". That value should be modified in plugin settings for different themes to make it work. Sticky Class is actually nothing more than an element class (or id) of the element that should become sticky. 

Advancement of this simple plugin is that increases usability and page views of your WordPress site since menu is available to the user all the time.

Plugin is localized (multi language support) and responsive (as far as your theme is). Also there is possibility to add custom css code which make this plugin very flexible, customizable and user friendly.

Plugin is originally designed for Twenty Thirteen template but should work on any theme.  

[Plugin Home + Demo URL][1] 
[myStickymenu theme support page][2]

[1]: http://wordpress.transformnews.com/plugins/mystickymenu-simple-sticky-fixed-on-top-menu-implementation-for-twentythirteen-menu-269
[2]: http://wordpress.transformnews.com/tutorials/mystickymenu-theme-support-682

== Installation ==
Install like any other plugin. After install activate. 
Go to Settings / myStickymenu and change Sticky Class to .your_navbar_class or #your_navbar_id… 
Also make sure that Disable CSS style option is not enabled, you must add myStickymenu CSS style to your style.css file first. [More about disable CSS option][3] 

[3: http://wordpress.transformnews.com/tutorials/disable-css-style-in-mystickymenu-938

== Frequently Asked Questions ==

= How to find Sticky Class, what should I enter here? =
So this depends on what you want to make sticky and what theme do you use, but for example if you want your menu to be sticky, than you can examine the code (in firefox right click and “View page source”) and find div in which your menu is situated. This div have some class or id, and that’s the Sticky Class we need. If using class than don’t forget to ad dot (.) in front of class name, or hash (#) in front of id name in Sticky Class field. Twenty Thirteen default working class is ".navbar" without of quotes.

= Is there any way to restrict the width to the width of the header, rather than it being full width? =
Yes, just leave "Sticky Background Color" field blank (clear). Than if needed define custom background color for sticky header inside ".myfixed css class" field using .myfixed class. 

= Ho do I add small logo to the menu? =
That will depend on a theme you use, but if initially your menu and logo are in one div than you can use that div class or id in myStickymenu settings. 

If not you can change that in your header template file and add logo and menu divs inside new div with some custom class or id, than use that class or id in myStickymenu settings as a sticky class.

In CSS you can style your custom class while not sticky using custom class you added before. Furthermore you can style your menu while sticky using .myfixed class which is added by js to your custom class while sticky is active. 

In some cases you can use the whole header div and than just style it different with .myfixed class, hide what you don’t need using CSS display:none; property, and position logo and menu as you like. 

== Screenshots ==

1.  screenshot-1.png shows administration settings.
2.  screenshot-2.png shows menu when page is scrolled towards the bottom.


== Changelog ==

= 1.8.9 =
* Added: New option - Disable at certain posts and pages.

= 1.8.8 =
* Fixed: removed esc_attr for echo css, since input is already sanitized.

= 1.8.7 =
* Fixed: minor bug when browser resized.

= 1.8.6 =
* Fixed: minor bug.

= 1.8.5 =
* Improved: Improved performance and optimized scripts.

= 1.8.4 =
* Fixed: changed is_home() to is_front_page() for menu activation height on front page.

= 1.8.3 =
* Change: minor cosmetic changes…

= 1.8.2 =
* Fixed: js load on https

= 1.8.1 =
* Added: “Disable CSS“. If you plan to add style manually to your style.css in order to improve your site performance disable plugin CSS style printed by default in document head element.
* Minimized mystickymenu.js to improve performance.

= 1.8 =
* Added: "Make visible when scrolled on Homepage" after number of pixels. Now it’s possible to have one activation height for home page and another for the rest of the pages.
* Added German language

= 1.7 =
* Added multi language support (localization).
* Added languages - English (default), Spanish, Serbian and Croatian.
* Added Iris color picker script.
* Fixed jumping of page on scroll while menu is activated (height is defined before scroll event).
* mystickymenu.js moved to js folder

= 1.6 =
* Added: "Make visible when scroled" after number of pixels option.
* Fixed opacity 100 bug.

= 1.5 =
* Added option to enter exact width in px when sticky menu should be disabled "Disable at Small Screen Sizes".
* Added “.myfixed css class” setting field – edit .myfixed css style via plugin settings to create custom style.
* Fixed google adsense clash and undefined index notice.
* is_user_logged_in instead of old “Remove CSS Rules for Static Admin Bar while Sticky” option

= 1.4 =
* Added fade in or slide down effect settings field for sticky class.
* Added new wrapped div around selected sticky class with id mysticky_wrap which should make menu works smoother and extend theme support.

= 1.3 =
* Added "block direct access" to the mystickymenu plugin file (for security sake).
* Added Enable / Disable at small screen sizes and Remove not necessary css for all themes without admin bar on front page.
* Added “margin-top :0px” to .myfixed class in head which should extend theme support.

= 1.2 =
* Fixed mystickymenu.js for IE browsers, so myStickymenu is now compatible with IE 10, 11
  
= 1.1 =
* Added administration options, now available through Dashboard / Settings / myStickymenu. Options are as follows: Sticky Class, Sticky z-index, Sticky Width, Sticky Background Color, Sticky Opacity, Sticky Transition Time. 
* Old mystickymenu.css file is deprecated and not in use anymore.

= 1.0 =
* First release of myStickymenu plugin

== Upgrade Notice ==

= 1.8.4 =
* Fixed: changed is_home() to is_front_page() for menu activation height on front page.

= 1.8.3 =
* Change: minor cosmetic changes…

= 1.8.2 =
* Fixed: js load on https

= 1.8.1 =
* Added: “Disable CSS“. If you plan to add style manually to your style.css in order to improve your site performance disable plugin CSS style printed by default in document head element.
* Minimized mystickymenu.js to improve performance.

= 1.8 =
* Added: "Make visible when scrolled on Homepage" after number of pixels. Now it’s possible to have one activation height for home page and another for the rest of the pages.

= 1.7 =
* Added multi language support (localization).
* Added Iris color picker script.
* Fixed jumping of page on scroll while menu is activated (height defined before scroll event).
* mystickymenu.js moved to js folder

= 1.6 =
* After plugin update go to mystickymenu plugin settings and save changes with desired value for a new parameters. Clear cache if some cache system used on your site.
* Added: “Make visible when scroled” after number of pixels option.
* Fixed opacity 100 bug.

= 1.5 =
* Added option to enter exact width in px when sticky menu should be disabled "Disable at Small Screen Sizes".
* Added “.myfixed css class” setting field – edit .myfixed css style via plugin settings to create custom style.
* Fixed google adsense clash and undefined index notice.
* is_user_logged_in instead of old "Remove CSS Rules for Static Admin Bar while Sticky" option

= 1.4 =
* Added fade in or slide down effect settings field for sticky class.
* Added new wrapped div around selected sticky class with id mysticky_wrap.

= 1.3 =
* Added "block direct access" to the mystickymenu plugin file.
* Added Enable / Disable at small screen sizes and Remove not necessary css.
* Added "margin-top :0px" to .myfixed class in head which should extend theme support.

= 1.2 =
* Fixed mystickymenu.js for IE browsers, so myStickymenu is now compatible with IE 10, 11
  
= 1.1 =
* Added administration options, now available through Dashboard / Settings / myStickymenu. Options are as follows: Sticky Class, Sticky z-index, Sticky Width, Sticky Background Color, Sticky Opacity, Sticky Transition Time. 
* Old mystickymenu.css file is deprecated and not in use anymore.



