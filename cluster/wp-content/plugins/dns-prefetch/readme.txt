=== DNS Prefetch ===
Tags: dns, prefetch, optimization
Requires at least: 4.0
Tested up to: 4.1
Contributors: jp2112
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7EX9NB9TLFHVW
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds dns prefetching meta tags to your site.

== Description ==

This plugin implements DNS prefetching per the Mozilla specification for the Firefox browser. Hopefully, other browsers will eventually support DNS prefetching.

See https://developer.mozilla.org/en-US/docs/Controlling_DNS_prefetching for more detail.

Disclaimer: This plugin is not affiliated with or endorsed by Mozilla.

<h3>If you need help with this plugin</h3>

If this plugin breaks your site or just flat out does not work, create a thread in the <a href="http://wordpress.org/support/plugin/dns-prefetch">Support</a> forum with a description of the issue. Make sure you are using the latest version of WordPress and the plugin before reporting issues, to be sure that the issue is with the current version and not with an older version where the issue may have already been fixed.

<strong>Please do not use the <a href="http://wordpress.org/support/view/plugin-reviews/dns-prefetch">Reviews</a> section to report issues or request new features.</strong>

== Installation ==

1. Upload plugin file through the WordPress interface.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Settings &raquo; DNS Prefetch, configure plugin.
4. View any of your pages, they should contain the following meta tag:

`<meta http-equiv="x-dns-prefetch-control" content="on">`

In addition, if you configured any additional domains, they should also be listed after the line of code above.

== Frequently Asked Questions ==

= How do I use the plugin? =

Go to Settings &raquo; DNS Prefetch and enter any domains you want to be prefetched by Firefox browsers, in addition to the ones already linked on your home page. Make sure the "enabled" checkbox is checked.

For example, you might have www.example.com linked on one of your subpages. By adding "//www.example.com" you instruct browsers to resolve the DNS for that domain, decreasing the latency should someone with a Firefox browser visit that page.

= I entered some text but don't see anything on the page. =

Are you caching your pages?

= I don't want the admin CSS. How do I remove it? =

Add this to your functions.php:

`remove_action('admin_head', 'insert_dpf_admin_css');`

== Screenshots ==

1. Plugin settings page (note the URLs entered)
2. HTML source of a webpage (the URLs above are added to the HTML source)

== Changelog ==

= 0.1.0 =
- confirmed compatibility with WordPres 4.1

= 0.0.9 =
- updated .pot file and readme

= 0.0.8 =
- minor code optimizations
- fixed validation issue

= 0.0.7 =
- permanent fix for Undefined Index issue
- admin CSS and page updates

= 0.0.6 =
- code fix

= 0.0.5 = 
- code fix
- updated support tab

= 0.0.4 = 
- minor code optimizations
- changed load behavior thanks to http://wordpress.org/support/topic/load-using-wp_head-with-highest-priority

= 0.0.3 =
- fix 2 for wp_kses

= 0.0.2 =
- fix for wp_kses

= 0.0.1 =
- created
- verified compatibility with WP 3.9

== Upgrade Notice ==

= 0.1.0 =
- confirmed compatibility with WordPres 4.1

= 0.0.9 =
- updated .pot file and readme

= 0.0.8 =
- minor code optimizations; fixed validation issue

= 0.0.7 =
- permanent fix for Undefined Index issue; admin CSS and page updates

= 0.0.6 =
- code fix

= 0.0.5 = 
- code fix; updated support tab

= 0.0.4 = 
- minor code optimizations, changed load behavior

= 0.0.3 =
- fix 2 for wp_kses

= 0.0.2 =
- fix for wp_kses

= 0.0.1 =
created, verified compatibility with WP 3.9