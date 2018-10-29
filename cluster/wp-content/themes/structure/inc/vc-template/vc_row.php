<?php
/**
 * Shortcode attributes
 * @var $atts
 * @var $el_class
 * @var $bg_image
 * @var $bg_color
 * @var $bg_image_repeat
 * @var $font_color
 * @var $parallax_image
 * @var $padding
 * @var $margin_bottom
 * @var $full_width
 * @var $css
 * @var $el_id
 * @var $row_type
 * @var $type
 * @var $video
 * @var $video_webm
 * @var $video_mp4
 * @var $video_ogv
 * @var $background_color
 * @var $parallax_speed
 * @var $background_image
 * @var $content - shortcode content
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Row
 */
$output = $after_output = '';
$atts   = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
//wp_enqueue_style('js_composer_front');
wp_enqueue_script( 'wpb_composer_front_js' );
//wp_enqueue_style('js_composer_custom_css');
$el_class  = $this->getExtraClass( $el_class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_row wpb_row ' . ( $this->settings( 'base' ) === 'vc_row_inner' ? 'vc_inner ' : '' ) . get_row_css_class() . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );
$_image    = "";
if ( $background_image != '' || $background_image != ' ' ) {
	$_image = wp_get_attachment_image_src( $background_image, 'full' );
}
if ( $video == "show_video" ) {
	$css_class_video = " video-section";
}
if ( $row_type == "row" ) {
	if ( $type == "grid" ) {
		$output .= '<div ';
		if ( $el_id ) {
			$output .= 'id= "' . esc_attr( $el_id ) . '"';
		}
		$css_class_type = " boxed";
		$output .= ' class="' . $css_class . ' ';
		if ( $video == "show_video" ) {
			$output .= $css_class_video . '';
		}
		$output .= ' "';
		$output .= ' style="';
		if ( $background_color ) {
			$output .= 'background-color: ' . $background_color . ';';
		}
		if ( $background_image ) {
			$output .= 'background-image:url(' . $background_image . ');';
		}
		$output .= '"><div class="' . $css_class_type . '">';
		if ( $video == "show_video" ) {
			$output .= '<div class="video-mask"></div><video controls="controls" muted="muted" preload="auto" loop="true" autoplay="true">';
			if ( ! empty( $video_webm ) ) {
				$output .= '<source type="video/webm" src="' . $video_webm . '">';
			}
			if ( ! empty( $video_mp4 ) ) {
				$output .= '<source type="video/mp4" src="' . $video_mp4 . '">';
			}
			if ( ! empty( $video_ogv ) ) {
				$output .= '<source type="video/ogg" src="' . $video_ogv . '">';
			}
			$output .= '</video>';
		}
		$output .= '<div class="container"><div class="row">';

		$output .= wpb_js_remove_wpautop( $content );
		$output .= '</div></div></div></div>' . $this->endBlockComment( 'row' );
	} elseif ( $type == "full_width" ) {
		$css_class_type = " full-width";
		$output .= '<div ';
		if ( $el_id ) {
			$output .= 'id= "' . esc_attr( $el_id ) . '"';
		}
		$output .= ' class="' . $css_class . $css_class_type . ' ';
		if ( $video == "show_video" ) {
			$output .= $css_class_video . '';
		}
		$output .= ' "';
		$output .= ' style="';
		if ( $background_image ) {
			$output .= 'background-image:url(' . $background_image . ');';
		}
		if ( $background_color ) {
			$output .= 'background-color: ' . $background_color . ';';
		}
		$output .= '">';
		if ( $video == "show_video" ) {
			$output .= '<div class="mk-section-mask"></div><video poster="' . $v_image . ' controls="controls" muted="muted" preload="auto" loop="true" autoplay="true">';
			if ( ! empty( $video_webm ) ) {
				$output .= '<source type="video/webm" src="' . $video_webm . '">';
			}
			if ( ! empty( $video_mp4 ) ) {
				$output .= '<source type="video/mp4" src="' . $video_mp4 . '">';
			}
			if ( ! empty( $video_ogv ) ) {
				$output .= '<source type="video/ogg" src="' . $video_ogv . '">';
			}
			$output .= '</video>';
		}
		$output .= '<div class="row">';
		$output .= wpb_js_remove_wpautop( $content );
		$output .= '</div></div>' . $this->endBlockComment( 'row' );
	}
} elseif ( $row_type == "parallax" ) {
	$output .= '<div data-stellar-background-ratio="' . $parallax_speed . '" data-stellar-horizontal-offset="0" class="' . $css_class . ' full-width parallax"';
	$output .= ' style="';
	if ( $background_image ) {
		$output .= 'background-image:url(' . $background_image . ');';
	}
	$output .= '">';
	$output .= '<div class="row">';
	$output .= '<div class="container"><div class="row">';
	$output .= wpb_js_remove_wpautop( $content );
	$output .= '</div></div></div></div>' . $this->endBlockComment( 'row' );
}


echo $output;