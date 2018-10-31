<?php
/**
 * Plugin generic functions file
 *
 * @package Album and Image Gallery Plus Lightbox
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Escape Tags & Slashes
 *
 * Handles escapping the slashes and tags
 *
 * @package Album and Image Gallery Plus Lightbox
 * @since 1.0.0
 */
function aigpl_esc_attr($data) {
    return esc_attr( stripslashes($data) );
}

/**
 * Strip Slashes From Array
 *
 * @package Album and Image Gallery Plus Lightbox
 * @since 1.0.0
 */
function aigpl_slashes_deep($data = array(), $flag = false) {
  
    if($flag != true) {
        $data = aigpl_nohtml_kses($data);
    }
    $data = stripslashes_deep($data);
    return $data;
}

/**
 * Strip Html Tags 
 * 
 * It will sanitize text input (strip html tags, and escape characters)
 * 
 * @package Album and Image Gallery Plus Lightbox
 * @since 1.0.0
 */

function aigpl_nohtml_kses($data = array()) {
  
  if ( is_array($data) ) {
    
    $data = array_map('aigpl_nohtml_kses', $data);
    
  } elseif ( is_string( $data ) ) {
    $data = trim( $data );
    $data = wp_filter_nohtml_kses($data);
  }
  
  return $data;
}

/**
 * Function to unique number value
 * 
 * @package Album and Image Gallery Plus Lightbox
 * @since 1.0.0
 */
function aigpl_get_unique() {
	static $unique = 0;
	$unique++;

	return $unique;
}

/**
 * Function to add array after specific key
 * 
 * @package Album and Image Gallery Plus Lightbox
 * @since 1.0.0
 */
function aigpl_add_array(&$array, $value, $index, $from_last = false) {
    
    if( is_array($array) && is_array($value) ) {

        if( $from_last ) {
            $total_count    = count($array);
            $index          = (!empty($total_count) && ($total_count > $index)) ? ($total_count-$index): $index;
        }
        
        $split_arr  = array_splice($array, max(0, $index));
        $array      = array_merge( $array, $value, $split_arr);
    }
    
    return $array;
}

/**
 * Function to get post featured image
 * 
 * @package Album and Image Gallery Plus Lightbox
 * @since 1.0.0
 */
function aigpl_get_image_src( $post_id = '', $size = 'full' ) {
    $size   = !empty($size) ? $size : 'full';
    $image  = wp_get_attachment_image_src( $post_id, $size );

    if( !empty($image) ) {
        $image = isset($image[0]) ? $image[0] : '';
    }

    return $image;
}

/**
 * Function to get post excerpt
 * 
 * @package Album and Image Gallery Plus Lightbox
 * @since 1.0.0
 */
function aigpl_get_post_excerpt( $post_id = null, $content = '', $word_length = '55', $more = '...' ) {
    
    $has_excerpt    = false;
    $word_length    = !empty($word_length) ? $word_length : '55';
    
    // If post id is passed
    if( !empty($post_id) ) {
        if (has_excerpt($post_id)) {

            $has_excerpt    = true;
            $content        = get_the_excerpt();

        } else {
            $content = !empty($content) ? $content : get_the_content();
        }
    }

    if( !empty($content) && (!$has_excerpt) ) {
        $content = strip_shortcodes( $content ); // Strip shortcodes
        $content = wp_trim_words( $content, $word_length, $more );
    }

    return $content;
}

/**
 * Function to get `igsp-gallery` shortcode designs
 * 
 * @package Album and Image Gallery Plus Lightbox
 * @since 1.0.0
 */
function aigpl_designs() {
    $design_arr = array(
                    'design-1'  => __('Design 1', 'album-and-image-gallery-plus-lightbox')
                );
    return apply_filters('aigpl_designs', $design_arr );
}

/**
 * Function to get `igsp-gallery` shortcode designs
 * 
 * @package Album and Image Gallery Plus Lightbox
 * @since 1.0.0
 */
function aigpl_album_designs() {
    $design_arr = array(
                    'design-1'  => __('Design 1', 'album-and-image-gallery-plus-lightbox'),
                );
    return apply_filters('aigpl_album_designs', $design_arr );
}