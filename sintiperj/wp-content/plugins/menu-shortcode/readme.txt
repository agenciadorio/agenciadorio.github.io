=== menu shortcode ===
Contributors: nil4you
Donate link: http://nirmalbhagwani.wordpress.com/
Tags: menu shortcode, shortcode, menu in widget, widget menu shortcode, redirect short code
Requires at least: 3.0.1
Tested up to: 4.5.2
Stable tag: 1.1.0
License: GPLv2
License URI: http://nirmalbhagwani.wordpress.com/2014/01/13/pageview-readme/

From this plugin you can call menu from shortcode. Where you have option of giving it id, class etc. Read usage to know more.

1. Write the shortcode just 	[listmenu menu="menu name goes here" menu_id="menu id goes here"]
				i.e. [listmenu menu="quick footer" menu_id="footer_menu"]
2. you have variety of option to give below is it's explanation

		'container_class' => 'menu container classname', 
		'container_id'    => menu container id', 
		'menu_class'      => 'menu class', 
		'menu_id'         => 'menu id',
		'before'          => 'before menu html',
		'after'           => 'after menu html',
		'link_before'     => 'before link title',
		'link_after'      => 'after link title'

3. redirect shortcode [redirect location="http://www.google.com" duration="1"] or
[redirect location="http://localhost:1337/aaneel" duration="0.2"]
where you can set location and duration in post and widget as well.
remember full url is necessary.



== Installation ==

This section describes how to install the plugin and get it working.


1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. You can view it in sidebar

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Nirmal Bhagwani; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write mail me at nirmal4designing@gmail.com

== Usage ==
1. Write the shortcode just 	[listmenu menu="menu name goes here" menu_id="menu id goes here"]
				i.e. [listmenu menu="quick footer" menu_id="footer_menu"]
2. you have variety of option to give below is it's explanation

		'container_class' => 'menu container classname', 
		'container_id'    => menu container id', 
		'menu_class'      => 'menu class', 
		'menu_id'         => 'menu id',
		'before'          => 'before menu html',
		'after'           => 'after menu html',
		'link_before'     => 'before link title',
		'link_after'      => 'after link title'

3. redirect shortcode [redirect location="http://www.google.com" duration="1"] or
[redirect location="http://localhost:1337/aaneel" duration="0.2"]
where you can set location and duration in post and widget as well.
remember full url is necessary.


== improvment suggestion welcomed ==
You are always welcome for improvment suggestion.