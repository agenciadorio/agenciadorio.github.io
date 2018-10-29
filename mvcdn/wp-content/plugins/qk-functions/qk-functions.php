<?php 
/**
 * Plugin Name: QK Custom Functions
 * Description: This plugin register all custom functions come with theme.
 * Version: 1.0
 * Author: Quannt
 * Author URI: http://qkthemes.com
 */
?>
<?php 
///heyhey add your custom function to here
add_filter('widget_text', 'do_shortcode');
add_shortcode( 'animation-tag', 'construction_animation_tag');
add_shortcode( 'counter-list', 'construction_counter_list');
add_shortcode( 'standar-list', 'construction_standar_list');
add_filter('portfolio-style', 'construction_portfolio_style');
add_shortcode( 'social-link', 'construction_social_link' );
add_shortcode('rotate-text', 'construction_rotated_text');
?>