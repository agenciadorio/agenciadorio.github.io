=== Album and Image Gallery plus Lightbox ===
Contributors: wponlinesupport, anoopranawat, pratik-jain 
Tags: wponlinesupport, album, image album, gallery, magnific-popup, magnific image slider, image gallery, responsive image gallery, image slider, image gallery slider, gallery slider, album slider, lightbox, albums, best gallery plugin, fancybox, free photo gallery, galleries, gallery, image, image captions,  images, media, media gallery, photo, photo albums, photo gallery, photographer, photography, photos, picture, Picture Gallery, pictures, responsive, responsive galleries, responsive gallery, singlepic, slideshow, slideshow galleries, slideshow gallery, slideshows, thumbnail galleries, thumbnail gallery, thumbnails, watermarking, watermarks, wordpress gallery plugin, wordpress photo gallery plugin, wordpress responsive gallery, wp gallery, wp gallery plugins
Requires at least: 3.5
Tested up to: 4.9.7
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A quick, easy way to add and display responsive image gallery and image album in a grid or slider with lightbox.

== Description ==
A very simple plugin to add image gallery, image album in your post, page and custom post type section and display it on frontend of your website in a Grid, Slider OR carousel view with the help of shorcode. The gallery field provides a simple and intuitive interface for managing a collection of images.

View [DEMO](http://wponlinesupport.com/wp-plugin/album-image-gallery-plus-lightbox/) | [PRO DEMO and Features](http://wponlinesupport.com/wp-plugin/album-image-gallery-plus-lightbox/) for additional information.

Gallery Plugin enables you to create several media such as image gallery, photo albums, portfolio and also simple picture to an image slider or image lightbox and image carousel.

**This plugin contain four shortcode**

= Here is the shortcode example =

* Gallery Grid Shortcode: 

<code>[aigpl-gallery]</code>  
* Gallery Slider Shortcode: 

<code>[aigpl-gallery-slider]</code> 
* Image Album Grid Shortcode: 

<code>[aigpl-gallery-album]</code>  

* Image Album Slider Shortcode: 

<code>[aigpl-gallery-album-slider]</code> 

Where you can display image gallery and image album with lightbox

= Use Following Gallery parameters with shortcode =
<code>[aigpl-gallery]</code>

* **ID:** [aigpl-gallery id="5"] (Gallery id for which you want to display images.)
* **Grid:** [aigpl-gallery grid="1"] (Number of columns for image gallery. Values are 1 to 12)
* **Link Behaviour:** [aigpl-gallery link_target="self"] (Choose link behaviour. Values are "self" OR "blank")
* **Gallery Height:** [aigpl-gallery gallery_height="400"] (Control height of the image. You can enter any numeric number. You can set "auto" for auto height.)
* **Display Title:** [aigpl-gallery show_title="true"] (Display image title or not. Values are "true" OR "false")
* **Display Description:** [aigpl-gallery show_description="true"] (Display image description. Values are "true" OR "false")
* **Display Caption:** [aigpl-gallery show_caption="true"] (Display image caption. Values are "true" OR "false")
* **Image Size:** [aigpl-gallery image_size="full"] (Choose appropriate image size from the WordPress. Values are "full", "medium", "large" OR "thumbnail".)
* **Popup:** [aigpl-gallery popup="true"] (Display gallery image in a popup. Values are "true" OR "false")


= Use Following Gallery Slider parameters with shortcode =
<code>[aigpl-gallery-slider]</code>

* **ID:** [aigpl-gallery-slider id="5"] (Gallery id for which you want to display images.)
* **Link Behaviour:** [aigpl-gallery-slider link_target="self"] (Choose link behaviour. Values are "self" OR "blank")
* **Gallery Height:** [aigpl-gallery-slider gallery_height="400"] (Control height of the image. You can enter any numeric number. You can set "auto" for auto height.)
* **Display Title:** [aigpl-gallery-slider show_title="true"] (Display image title or not. Values are "true" OR "false")
* **Display Description:** [aigpl-gallery-slider show_description="true"] (Display image description. Values are "true" OR "false")
* **Display Caption:** [aigpl-gallery-slider show_caption="true"] (Display image caption. Values are "true" OR "false")
* **Image Size:** [aigpl-gallery-slider image_size="full"] (Choose appropriate image size from the WordPress. Values are "full", "medium", "large" OR "thumbnail".)
* **Popup:** [aigpl-gallery-slider popup="true"] (Display gallery image in a popup. Values are "true" OR "false")
* **Slider Columns:** [aigpl-gallery-slider slidestoshow="2"] (Display number of images at a time in slider.)
* **Slides to Scroll:** [aigpl-gallery-slider slidestoscroll="2"] (Scroll number of images at a time.)
* **Slider Pagination and Arrows:** [aigpl-gallery-slider dots="false" arrows="false"]
* **Autoplay:** [aigpl-gallery-slider autoplay="true"] (Start slider automatically. Values are "true" OR "false".)
* **Autoplay Interval:** [aigpl-gallery-slider autoplay_interval="3000"] (Delay between two slides.)
* **Slider Speed:** [aigpl-gallery-slider speed="3000"] (Control speed of slider.)


= Use Following Gallery Album parameters with shortcode =
<code>[aigpl-gallery-album]</code>

* **Limit:** [aigpl-gallery-album limit="5"] (Gallery id for which you want to display images.)
* **Album Grid:** [aigpl-gallery-album album_grid="3"] (Number of columns for image album. Values are 1 to 12.)
* **Link Behaviour:** [aigpl-gallery-album album_link_target="self"] (Choose link behaviour whether to open in a new tab or not. Values are "self" OR "blank")
* **Album Height:** [aigpl-gallery-album album_height="400"] (Control height of the album. You can enter any numeric number.)
* **Album Title:** [aigpl-gallery-album album_title="true"] (Display album title. Values are "true" or "false".)
* **Album Description:** [aigpl-gallery-album album_description="true"] (Display album description. Values are "true" or "false".)
* **Album Full Content:** [aigpl-gallery-album album_full_content="true"] (Display album full description. Values are "true" or "false".)
* **Words Limit:** [aigpl-gallery-album words_limit="40"] (Display number of words for album description.)
* **Content Tail (Continue Reading):** [aigpl-gallery-album content_tail="..."] (Display three dots as a contineous reading.)
* **Display Specific Album:** [aigpl-gallery-album id="5,10"] (Display specific album.)
* **Display By Category:** [aigpl-gallery-album category="category_id"] (Display album by their category ID.)
* **Total Photo Label:** [aigpl-gallery-album total_photo="{total} Photos"] (Control photo count label. "{total}" will replace the number of album photos.)
* **Popup:** [aigpl-gallery-album popup="true"] (Display gallery image in a popup. Values are "true" OR "false")
* **Grid:** [aigpl-gallery-album grid="1"] (Number of columns for image gallery. Values are 1 to 12)
* **Gallery Height:** [aigpl-gallery-album gallery_height="400"] (Control height of the image. You can enter any numeric number. You can set "auto" for auto height.)
* **Display Caption:** [aigpl-gallery-album show_caption="true"] (Display image caption. Values are "true" OR "false")
* **Link Behaviour:** [aigpl-gallery-album link_target="self"] (Choose link behaviour. Values are "self" OR "blank")
* **Display Title:** [aigpl-gallery-album show_title="true"] (Display image title or not. Values are "true" OR "false")
* **Display Description:** [aigpl-gallery-album show_description="true"] (Display image description. Values are "true" OR "false")
* **Popup:** [aigpl-gallery-album popup="true"] (Display gallery image in a popup. Values are "true" OR "false")
* **Image Size:** [aigpl-gallery-album image_size="full"] (Choose appropriate image size from the WordPress. Values are "full", "medium", "large" OR "thumbnail".)


= Use Following Gallery Album Slider parameters with shortcode =
<code>[aigpl-gallery-album-slider]</code>

* **Limit:** [aigpl-gallery-album-slider limit="5"] (Gallery id for which you want to display images.)
* **Link Behaviour:** [aigpl-gallery-album-slider album_link_target="self"] (Choose link behaviour whether to open in a new tab or not. Values are "self" OR "blank")
* **Album Height:** [aigpl-gallery-album-slider album_height="400"] (Control height of the album. You can enter any numeric number.)
* **Album Title:** [aigpl-gallery-album-slider album_title="true"] (Display album title. Values are "true" or "false".)
* **Album Description:** [aigpl-gallery-album-slider album_description="true"] (Display album description. Values are "true" or "false".)
* **Album Full Content:** [aigpl-gallery-album-slider album_full_content="true"] (Display album full description. Values are "true" or "false".)
* **Words Limit:** [aigpl-gallery-album-slider words_limit="40"] (Display number of words for album description.)
* **Content Tail (Continue Reading):** [aigpl-gallery-album-slider content_tail="..."] (Display three dots as a contineous reading.)
* **Display Specific Album:** [aigpl-gallery-album-slider id="5,10"] (Display specific album.)
* **Display By Category:** [aigpl-gallery-album-slider category="category_id"] (Display album by their category ID.)
* **Total Photo Label:** [aigpl-gallery-album-slider total_photo="{total} Photos"] (Control photo count label. "{total}" will replace the number of album photos.)
* **Popup:** [aigpl-gallery-album-slider popup="true"] (Display gallery image in a popup. Values are "true" OR "false")
* **Grid:** [aigpl-gallery-album-slider grid="1"] (Number of columns for image gallery. Values are 1 to 12)
* **Gallery Height:** [aigpl-gallery-album-slider gallery_height="400"] (Control height of the image. You can enter any numeric number. You can set "auto" for auto height.)
* **Display Caption:** [aigpl-gallery-album-slider show_caption="true"] (Display image caption. Values are "true" OR "false")
* **Link Behaviour:** [aigpl-gallery-album-slider link_target="self"] (Choose link behaviour. Values are "self" OR "blank")
* **Display Title:** [aigpl-gallery-album-slider show_title="true"] (Display image title or not. Values are "true" OR "false")
* **Display Description:** [aigpl-gallery-album-slider show_description="true"] (Display image description. Values are "true" OR "false")
* **Popup:** [aigpl-gallery-album-slider popup="true"] (Display gallery image in a popup. Values are "true" OR "false")
* **Image Size:** [aigpl-gallery-album-slider image_size="full"] (Choose appropriate image size from the WordPress. Values are "full", "medium", "large" OR "thumbnail".)
* **Slider Columns:** [aigpl-gallery-album-slider album_slidestoshow="2"] (Display number of images at a time in slider.)
* **Slides to Scroll:** [aigpl-gallery-album-slider album_slidestoscroll="2"] (Scroll number of images at a time.)
* **Slider Pagination and Arrows:** [aigpl-gallery-album-slider album_dots="false" album_arrows="false"]
* **Autoplay:** [aigpl-gallery-album-slider album_autoplay="true"] (Start slider automatically. Values are "true" OR "false".)
* **Autoplay Interval:** [aigpl-gallery-album-slider album_autoplay_interval="3000"] (Delay between two slides.)
* **Slider Speed:** [aigpl-gallery-album-slider album_speed="3000"] (Control speed of slider.)


= Template code is =
<code><?php echo do_shortcode('[aigpl-gallery]'); ?></code>
<code><?php echo do_shortcode('[aigpl-gallery-slider]'); ?></code>
<code><?php echo do_shortcode('[aigpl-gallery-album]'); ?></code>
<code><?php echo do_shortcode('[aigpl-gallery-album-slider]'); ?></code>

= How to install : =
[youtube https://www.youtube.com/watch?v=tv5vymtalS4]


= Available Features : =
* Gallery Grid
* Gallery Slider
* Image Album Grid
* Image Album Slider
* Category wise album
* Easy Drag & Drop image feature
* Strong shortcode parameters
* Slider RTL support
* Fully responsive
* 100% Multilanguage

= PRO Features Include =
> * Gallery Grid
> * Gallery Slider
> * Image Album Grid
> * Image Album Slider
> * Category wise Album
> * 15+ Designs for Album grid & Slider view
> * 15+ Designs for Gallery grid & Slider view
> * Display gallery image with title and description
> * Display image album with title and description
> * Masonry Style for Gallery
> * Masonry Style for Album
> * Album Images in a responsive lightbox
> * Gallery Images in a responsive lightbox
> * Easy Drag & Drop Image Feature
> * Custom link to gallery image
> * Strong Shortcode Parameters
> * Slider CenterMode Effect
> * Slider RTL support
> * Fully Responsive
> * 100% Multilanguage
>
> View [PRO DEMO and Features](http://wponlinesupport.com/wp-plugin/album-image-gallery-plus-lightbox/) for additional information.
>

= Privacy & Policy =
* We have also opt-in e-mail selection , once you download the plugin , so that we can inform you and nurture you about products and its features.

== Installation ==

1. Upload the 'Album and Image Gallery plus Lightbox' folder to the '/wp-content/plugins/' directory.
2. Activate the "Album and Image Gallery plus Lightbox" list plugin through the 'Plugins' menu in WordPress.
3. Add a new page and add desired short code in that.

= How to install : =
[youtube https://www.youtube.com/watch?v=tv5vymtalS4]


== Screenshots ==

1. How to add Album cover photo and gallery
2. All Album and shortcodes
3. Shortcodes and how to display
4. Album grid and slider


== Changelog ==

= 1.1.4 (27, Jul 2018) =
* [+] Tweak - Taken better care of Image Alt tag.
* [+] Tweak - Used 'wp_reset_postdata' instead of 'wp_reset_query'.
* [*] Fix - Popup issue when slider or carousel is used in loop mode.

= 1.1.4 (05, June 2018) =
* [*] Follow some WordPress Detailed Plugin Guidelines.

= 1.1.3 (07-05-2018) =
* [*] Fixed some design related issues reprted by some users

= 1.1.2 =
* [*] Resolved error when wrong id is passed in [aigpl-gallery-album] and [aigpl-gallery-album-slider]
* [*] Correct wrong text domain in some files.

= 1.1 =
* [+] Added 'How it Work' page for better user interface.

= 1.0 =
* Initial release.