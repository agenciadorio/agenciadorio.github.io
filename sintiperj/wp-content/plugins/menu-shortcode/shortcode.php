<?php
   /*
   Plugin Name: menu shortcode
   Plugin URI: http://www.demo.com
   Description: A plugin through which we can call the menu by short code
   Version: 1.0
   Author: Nirmal Bhagwani
   Author URI: nirmalbhagwani.wordpress.com
   License: GPL2
   */
function list_menu($atts, $content = null) {
	extract(shortcode_atts(array(  
		'menu'            => '', 
		'container'       => 'div', 
		'container_class' => '', 
		'container_id'    => '', 
		'menu_class'      => 'menu', 
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'depth'           => 0,
		'walker'          => '',
		'theme_location'  => ''), 
		$atts));
 
 
	return wp_nav_menu( array( 
		'menu'            => $menu, 
		'container'       => $container, 
		'container_class' => $container_class, 
		'container_id'    => $container_id, 
		'menu_class'      => $menu_class, 
		'menu_id'         => $menu_id,
		'echo'            => false,
		'fallback_cb'     => $fallback_cb,
		'before'          => $before,
		'after'           => $after,
		'link_before'     => $link_before,
		'link_after'      => $link_after,
		'depth'           => $depth,
		'walker'          => $walker,
		'theme_location'  => $theme_location));
}

function redirect_to($atts, $content = null) {
extract(shortcode_atts(array(  
		'location'            => '', 
		'duration'       => '',), 
		$atts));
		echo '<meta http-equiv="refresh" content="' .  $duration . ';url=' . $location . '">';
		echo "Please wait while you are redirected...or <a href=" . $myURL . ">Click Here</a> if you do not want to wait";
		return ob_get_clean();
}

//Create the shortcode
add_shortcode("listmenu", "list_menu");
add_shortcode("redirect", "redirect_to");
add_filter ('widget_text', 'do_shortcode');