=== wuk.ch DNS-Prefetch / Prerender ===
Contributors: web updates kmu GmbH
Donate link: http://wuk.ch/spenden/
Tags: dns-prefetch, prerender, speed
Requires at least: 4.0
Tested up to: 4.5.2
Stable tag: trunk
License: GPLv2 or later

Adds dns-prefetch and prerender functionalities on WordPress for better PageSpeed.

== Description ==

The Plugin implements 2 things:

DNS-Prefetch
------------
It looks for all CSS and JS Files which are loaded from external webpages and implement a dns-prefetch header tag.
This saves a lot of connection time on page load.

HTML5 Prefetch and Google Prerender
-----------------------------------
It makes a new table with small statistics and measures (internal) referal page to next page.
With these statistics, the most clicked "next" page will be automatically added as prerender. Prerender opens with very small CPU load the "next" guessed page already as a hidden Tab in the client browser. If the client select this page, the page can instantly showed without delay.
Tests have shown a pageload decrease of 68%.

Please note: The prerender needs some stats to work correctly. Don't try to generate some statistic, wait few days / weeks and you have organic and true statistic.

If you have questions, please do not hesitate to contact us: [wuk.ch](http://wuk.ch/ "web updates kmu GmbH")

== Frequently Asked Questions ==

= My Startpage doesn't reflect the most clicked page in the Prerender. Why? =

When your Startpage is your blogpage, then the Prerender Algorithm will automatically link to the newest article, as I count this as the most interesting one.
All other pages are counted over the statistics

== Upgrade Notice ==

Copy new version of Plugin over the old one.

== Installation ==

1. Extract the content of the `wukch-dns-prefetch-prerender.zip`
2. Upload the extracted content to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. No Configuration is needed.

== Screenshots ==

1. dns-prefetch and prerender in the sourcecode

= Manual Deinstallation =

1. Delete the content of the `/wp-content/plugins/preloader` directory
2. Connect to the database 
3. Delete the standard tables `wp_wukstats`

== Changelog ==

= 1.1.4 =
* [Bugfix] PHP 7.1.9 Bugfix

= 1.1.3 =
* [Bugfix] multiple dns-prefetch output of hostname is fixed 

= 1.1.1 =
* [Bugfix] Disable prefetch and prerender in WP Backend

= 1.1.0 =
* [Feature] HTML5 Prefetch Tag Support added

= 1.0.1 =
* [Bugfix] Repository Folder Fix

= 1.0.0 =
* [Feature] First Release to the WordPress Repository
