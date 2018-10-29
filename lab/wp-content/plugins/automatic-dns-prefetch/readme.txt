=== Automatic DNS Prefetch ===
Contributors: mabujo
Tags: wordpress, plugin, DNS Prefetch, optimisation, page speed
Requires at least: 3.9
Tested up to: 4.4
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin speeds up your page load speed by adding DNS prefetch statements for external libraries.

== Description ==

Modern browsers allow pages to prefetch DNS records of external libraries. This plugin looks through WordPress enqueued scripts and styles and adds DNS prefetch statements for scripts and stylesheets added by other plugins.

This means that when the browser comes to download an external script or stylesheet, it already has looked up the DNS record for the external domain, and can proceed immediately to downloading the script, saving latency during loading of external resources.

You can read more about this technique in the [Chromium document](https://www.chromium.org/developers/design-documents/dns-prefetching).

Plugin page [Automatic DNS Prefetch plugin @ mabujo](https://mabujo.com/blog/automatic-dns-prefetch-wordpress-plugin/).

== Installation ==

Installing "Automatic DNS Prefetch" can be done either by searching for "Automatic DNS Prefetch" via the "Plugins > Add New" screen in your WordPress dashboard, or by using the following steps:

1. Download the plugin via WordPress.org
1. Upload the ZIP file through the 'Plugins > Add New > Upload' screen in your WordPress dashboard
1. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

= 1.0 =
* 2012-12-19
* Initial release

== Upgrade Notice ==

= 1.0 =
* 2012-12-19
* Initial release
