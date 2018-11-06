<?php  

/**
 *
 *		Filename: gridstacks.php
 *		Function: this extension allows multiple different type of data visualization across multiple types of posts
 *		Author: Qantumthemes [Qantumthemes.com]
 *		
 * 
 */

define ("GRIDSTACK_URL",get_template_directory_uri() . '/includes/frontend/gridstacks/');

require_once 'stacks/owl/gridstack-owl.php'; // based on owl carousel http://owlgraphic.com/owlcarousel/index.html
require_once 'stacks/carousel/gridstack-carousel.php'; // based on original materializecss carousel
require_once 'stacks/diamonds/gridstack-diamonds.php'; // pure css
require_once 'stacks/grids/gridstack-grid.php'; // pure css
require_once 'stacks/grids/gridstack-list.php'; // pure css
require_once 'stacks/skywheel/gridstack-skywheel.php'; // pure css
require_once 'stacks/slideshow/gridstack-slideshow.php'; // based on original materializecss slideshow
require_once 'stacks/slideshow/gridstack-customslideshow.php'; // display custom list of pictures
require_once 'stacks/podcast/gridstack-podcast.php'; // display custom list of pictures




/**
 * Simplified shortcode that uses only qt_gridstackshortcode and extracts the correct shortcode
 * Created in conjunction with the gridstackeditor which is in qt-extension-suite plugin
 * qt-extension-suite/qt_tinymce_extensions/gridstackeditor
 * 
 */

if(!function_exists('qt_gridstackshortcode_function')) {
	function qt_gridstackshortcode_function($atts){
		/**
		 *	Defaults
		 * 	All parameters can be bypassed by same attribute in the shortcode
		 */
		extract( shortcode_atts( array(

			/* PHp and Query parameters */
			'gridstack' => 'qt-carousel',
			'quantity' => 4,
			'posttype' => 'post',
			'taxonomy' => false,
			'term_ids' => false,
			'archivelink' => 1			
		), $atts ) );

		if( $gridstack != "qt-carousel" && 
			$gridstack != "qt-diamonds" && 
			$gridstack != "qt-grid" && 
			$gridstack != "qt-list" && 
			$gridstack != "qt-owlcarousel" && 
			$gridstack != "qt-owlcarousel-row" && 
			$gridstack != "qt-skywheel" && 
			$gridstack != "qt-slideshow") {

			$gridstack = "qt-carousel";
		}
		ob_start();
		echo do_shortcode('['.$gridstack.' quantity="'.$quantity.'" posttype="'.$posttype.'" taxonomy="'.$taxonomy.'" term_ids="'.$term_ids.'" archivelink="'.$archivelink.'"]');
		return ob_get_clean();

	}
}
add_shortcode( "qt_gridstackshortcode", "qt_gridstackshortcode_function");
