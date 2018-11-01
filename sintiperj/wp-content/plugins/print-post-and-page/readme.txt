=== Print Post and Page ===
Contributors: html5andblog, apritchard2751
Tags: deprecated
Requires at least: 4.0
Tested up to: 4.8.2
License: GPLv2 or Later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Print Post and Page is no longer maintained / developed. We recommend you choose a different plugin.

== Description ==

**Notice: This Plugin is no longer being actively developed or maintained. At the moment, we believe it to be fairly stable but that might not continue with future WordPress versions. We recommend you looks for an alternative plugin.**

**This plugin is likely to be deleted from the WordPress plugin directory late 2018 / early 2019.**

**Printer Friendly**

Strips theme styling from post and page content while retaining any plugin CSS to allow Shortcode content to be printed as it's displayed. This is to save printer ink and only print the post content in a user friendly way.

**Printing Posts**

After installing the plugin simply go to the setting page and tick the active box. This will add the print icon to all posts, allowing for a very quick installation. Configure the color and sizing options to get the print icon and text looking as you want it to.

Now when someone clicks the print button it'll open a print dialog to allow the post to be printed.

To disable the print button on specific posts, simply find the post and edit it. In the post edit screen there will be a new box added to the bottom right of the page to disable the print icon for this post.

**Printing Pages**

Unlike posts, with pages you have to add a shortcode *[printicon align="left"]* to the pages that you want the print icon to be displayed on. This has the option of align to align the icon either 'left' or 'right'.

The shortcode itself can be added before or after the content to place it and gets its color and sizing from the setting menu.

**Customize the Icon**

When the plugin is activated it will a menu to the admin side menu called 'Print'. In this menu you can set the icon size, change the icon color, set the text label as well as changing alignment and placement.

The print icon used is part of [Font Awesome](http://fortawesome.github.io/Font-Awesome/).

**PLEASE NOTE**

This plugin uses session storage to store a local copy of the article that a user is reading. This is done purely to store a printer friendly version of the article which can be used for printing. It cannot be used to indenify the user, and is automatically removed when the browser is closed.

There may be slight differences in printout between browsers. There's no standardized print functionality, so all browsers interpret the document differently.

**Thanks**

[Font Awesome](http://fortawesome.github.io/Font-Awesome/) - Print Icon - [License](http://fortawesome.github.io/Font-Awesome/license/)

[Spectrum Colorpicker](https://github.com/bgrins/spectrum) - jQuery Color Picker Plugin in the Admin Menu - [License](https://github.com/bgrins/spectrum/blob/master/LICENSE)

Thanks to [Ben Nadel](http://www.bennadel.com/blog/1591-ask-ben-print-part-of-a-web-page-with-jquery.htm) for his article on Print Part of a Website, This is a great article and great resource in general.

Thanks to Patrick Evans for his answer on [Stackoverflow](http://stackoverflow.com/a/25461414) - This is licensed under [CC BY-SA 3.0](http://creativecommons.org/licenses/by-sa/3.0/) - This snippet was modified to help with getting the content to print.


== Installation ==

**Plugin Directory**

1. Search the plugin directory for plugin.
2. Click 'Install Now'.
3. Activate the plugin.
4. 'Print' should be added to the side menu in the admin area, along with the default settings.
5. Modify or keep default settings.

**Upload**

1. Download the plugin Zip file.
2. Go to plugins in the Wordpress Admin area.
3. Select 'Upload Plugin'
4. Activate the plugin.
5. 'Print' should be added to the side menu in the admin area, along with the default settings.
6. Modify or keep default settings.

== Frequently Asked Questions ==

**How do I Print Pages?**

Add the shortcode [printicon align="left"] for a left align print icon or [printicon align="right"] for a right aligned print icon. The plugin has to be be active in the settings menu for this to work.

**Can I Disable the Plugin on Individual Posts?**

Yes - In the post editor there will be an option added to the bottom right which can be selected to disable the icon on that particular post.

== Screenshots ==

1. Settings Screen

2. Print Icon on Post

3. Post Before Printing (Theme - [Themble Bones](http://themble.com/bones/))

4. Post After Printing with Plugin

== Changelog ==

**Version 1.6**

* Added support for custom post types

**Version 1.5**

* Changed the way article content is set for printing. It used to use a hidden element, now it makes use of session storage
* Print functionality improvements and additional sanitization functions added

**Version 1.3**

* Fixed Issue of CSS not printing

**Version 0.1**

* Beta Release of Plugin