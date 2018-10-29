<?php
/**
 * Generate Inline Styles
 * Portfolio creator page
 *
 * @package   Go Portfolio - WordPress Responsive Portfolio 
 * @author    Granth <granthweb@gmail.com>
 * @link      http://granthweb.com
 * @copyright 2016 Granth
 */

$styles = get_option( self::$plugin_prefix . '_styles' );
$id_prefix = ' #' . self::$plugin_prefix . '_' . $portfolio['id'];
$general_settings = get_option( self::$plugin_prefix . '_general_settings' );

/* Get style */
if ( isset( $portfolio['style'] ) && !empty( $portfolio['style'] ) && $styles[$portfolio['style']]['data'] ) {
	$style = $portfolio['style'];
	$style_data = stripslashes( isset( $portfolio['style-data'] ) ? $portfolio['style-data'] : $styles[$portfolio['style']]['data'] );
			
	/* Replace variables */
	foreach( $portfolio['css'] as $selector => $value ) { 
		
		if ( isset( $value['val'] ) && !empty( $value['val'] ) ) {
			$value['val'] = trim( $value['val'] );
		}
		
		/* Check integers */
		if ( isset( $value['type'] ) && $value['type'] == 'int' ) {
			$value['val'] = floatval( $value['val'] );
			if ( empty( $value['val'] ) ) { $value['val'] = 0; }
		}
		
		/* Set font families */
		if ( $selector == 'font_family_xl' && isset ( $value['val'] ) ) {
			if ( $value['val'] == '1' && isset( $general_settings['primary-font'] ) && !empty( $general_settings['primary-font'] ) ) {
				$value['val'] = $general_settings['primary-font'];
			} elseif ( $value['val'] == '2' && isset( $general_settings['secondary-font'] ) && !empty( $general_settings['secondary-font'] ) ) {
				$value['val'] = $general_settings['secondary-font'];
			} else {
				$value['val'] = 'inherit';
			}
		} 

		if ( $selector == 'font_family_l' && isset ( $value['val'] ) ) {
			if ( $value['val'] == '1' && isset( $general_settings['primary-font'] ) && !empty( $general_settings['primary-font'] ) ) {
				$value['val'] = $general_settings['primary-font'];
			} elseif ( $value['val'] == '2' && isset( $general_settings['secondary-font'] ) && !empty( $general_settings['secondary-font'] ) ) {
				$value['val'] = $general_settings['secondary-font'];
			} else {
				$value['val'] = 'inherit';
			}
		} 

		if ( $selector == 'font_family_m' && isset ( $value['val'] ) ) {
			if ( $value['val'] == '1' && isset( $general_settings['primary-font'] ) && !empty( $general_settings['primary-font'] ) ) {
				$value['val'] = $general_settings['primary-font'];
			} elseif ( $value['val'] == '2' && isset( $general_settings['secondary-font'] ) && !empty( $general_settings['secondary-font'] ) ) {
				$value['val'] = $general_settings['secondary-font'];
			} else {
				$value['val'] = 'inherit';
			}
		}

		if ( $selector == 'font_family_s' && isset ( $value['val'] ) ) {
			if ( $value['val'] == '1' && isset( $general_settings['primary-font'] ) && !empty( $general_settings['primary-font'] ) ) {
				$value['val'] = $general_settings['primary-font'];
			} elseif ( $value['val'] == '2' && isset( $general_settings['secondary-font'] ) && !empty( $general_settings['secondary-font'] ) ) {
				$value['val'] = $general_settings['secondary-font'];
			} else {
				$value['val'] = 'inherit';
			}
		}

		/* Set opacity */
		if ( $selector == 'post_opacity' && isset ( $value['val'] ) ) {
			$value['val'] = $value['val']/100;
		}

		if ( $selector == 'box_shadow_opacity' && isset ( $value['val'] ) ) {
			$value['val'] = $value['val']/100;
		}
		
		/* validate colors */
		if ( $selector == 'main_color_1' && isset ( $value['val'] ) ) {
			$value['val'] = preg_match( '/#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $value['val'] ) ? $value['val'] : 'inherit';
		}
		if ( $selector == 'main_color_2' && isset ( $value['val'] ) ) {
			$value['val'] = preg_match( '/#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $value['val'] ) ? $value['val'] : 'inherit';
		}
		if ( $selector == 'main_color_3' && isset ( $value['val'] ) ) {
			$value['val'] = preg_match( '/#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $value['val'] ) ? $value['val'] : 'inherit';
		}
		if ( $selector == 'main_color_4' && isset ( $value['val'] ) ) {
			$value['val'] = preg_match( '/#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $value['val'] ) ? $value['val'] : 'inherit';
		}									
		if ( $selector == 'highlight_color' && isset ( $value['val'] ) ) {
			$value['val'] = preg_match( '/#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $value['val'] ) ? $value['val'] : 'inherit';
		}									
		if ( $selector == 'post_content_color' && isset ( $value['val'] ) ) {
			$value['val'] = preg_match( '/#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $value['val'] ) ? $value['val'] : 'inherit';
		}
										
		$style_data = preg_replace( '~(\(\()\s?('. $selector .'+\s?)(\)\))~', $value['val'], $style_data );
		
	}

	/* Modify column and row space - ".gw-gopf-posts-wrap-inner" */
	$css_prop = null;
	if ( isset( $portfolio['h-space'] ) && !empty( $portfolio['h-space'] ) ) {
		$css_prop = 'margin-left:' . floatval( $portfolio['h-space'] )*-1 . 'px;';
	}
	if ( isset( $portfolio['v-space'] ) && !empty( $portfolio['v-space'] ) ) {
		$css_prop .= 'margin-top:' . floatval( $portfolio['v-space'] )*-1 . 'px;';
	}
	if ( $css_prop ) { echo sprintf( '%1$s .gw-gopf-posts-wrap-inner { %2$s }', $id_prefix, $css_prop ) . "\n"; }

	/* opacity change filtering */
	$css_prop = null;
	if ( isset( $portfolio['filter-inactive-opacity'] ) ) {
		$css_prop = 'filter:alpha(opacity=' . floatval( $portfolio['filter-inactive-opacity'] ) . ') !important;';
		$css_prop .= '-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=' . floatval( $portfolio['filter-inactive-opacity'] ) . ')" !important;';
		$css_prop .= '-khtml-opacity:' . floatval( $portfolio['filter-inactive-opacity'] )/100 . ' !important;';
		$css_prop .= '-moz-opacity:' . floatval( $portfolio['filter-inactive-opacity'] )/100 . ' !important;';
		$css_prop .= 'opacity:' . floatval( $portfolio['filter-inactive-opacity'] )/100 . ' !important;';				
	}
	if ( $css_prop ) { echo sprintf( '%1$s .gw-gopf-filter-opacity .gw-gopf-col-wrap.gw-gopf-disabled { %2$s }', $id_prefix, $css_prop ) . "\n"; }

	/* Modify column and row space - ".gw-gopf-post-col" */
	$css_prop = null;
	if ( isset( $portfolio['h-space'] ) && !empty( $portfolio['h-space'] ) ) {
		$css_prop = 'margin-left:' . floatval( $portfolio['h-space'] ) . 'px;';
	}
	if ( isset( $portfolio['v-space'] ) && !empty( $portfolio['v-space'] ) ) {
		$css_prop .= 'margin-top:' . floatval( $portfolio['v-space'] ) . 'px;';
	}
	if ( $css_prop ) { echo sprintf( '%1$s .gw-gopf-post-col { %2$s }', $id_prefix, $css_prop ) . "\n"; }

	/* Modify space between portfolio filter and portfolio items - ".gw-gopf-cats > div" */
	$css_prop = null;
	if ( isset( $portfolio['filter-v-space'] ) && !empty( $portfolio['filter-v-space'] ) ) {
		if ( !isset( $portfolio['filter-v-pos'] ) || ( isset( $portfolio['filter-v-pos'] ) && $portfolio['filter-v-pos'] == 'top') ) {			
			$css_prop = 'margin-bottom:' . floatval( $portfolio['filter-v-space'] ) . 'px !important;';
		} else {
			$css_prop = 'margin-top:' . floatval( $portfolio['filter-v-space'] ) . 'px !important;';
		}			
	}
	if ( $css_prop ) { echo sprintf( '%1$s .gw-gopf-cats { %2$s }', $id_prefix, $css_prop ) . "\n"; }	
	
	/* Modify left space for portfolio filter categories - ".gw-gopf-filter" */
	$css_prop = null;
	if ( isset( $portfolio['filter-h-space'] ) && !empty( $portfolio['filter-h-space']  ) ) {
		$css_prop = 'margin-left:' . floatval( $portfolio['filter-h-space'] ) * -1 . 'px;';
	}
	if ( $css_prop ) { echo sprintf( '%1$s .gw-gopf-filter { %2$s }', $id_prefix, $css_prop ) . "\n"; }
	
	/* Modify space between portfolio filter categories - ".gw-gopf-cats > div" */
	$css_prop = null;
	if ( isset( $portfolio['filter-h-space'] ) && !empty( $portfolio['filter-h-space'] ) ) {
		$css_prop = 'margin-left:' . floatval( $portfolio['filter-h-space'] ) . 'px;';
	}
	if ( $css_prop ) { echo sprintf( '%1$s .gw-gopf-cats > span { %2$s }', $id_prefix, $css_prop ) . "\n"; }
	
	/* Modify slider arrow spaces - ".gw-gopf-slider-controls > div" */
	$css_prop = null;
	if ( isset( $portfolio['slider-arrows-v-space'] ) && !empty( $portfolio['slider-arrows-v-space'] ) ) {
		$css_prop = 'margin-bottom:' . floatval( $portfolio['slider-arrows-v-space'] ) . 'px;';
	}
	if ( $css_prop ) { echo sprintf( '%1$s .gw-gopf-slider-controls > div { %2$s }', $id_prefix, $css_prop ) . "\n"; }	
		
	/* Modify slider arrow spaces - ".gw-gopf-slider-controls > div" */
	$css_prop = null;
	if ( isset( $portfolio['slider-arrows-h-space'] ) && !empty( $portfolio['slider-arrows-h-space'] ) ) {
		$css_prop .= 'margin-left:' . floatval( $portfolio['slider-arrows-h-space'] ) . 'px;';
	}
	if ( $css_prop ) { echo sprintf( '%1$s .gw-gopf-slider-controls > div { %2$s }', $id_prefix, $css_prop ) . "\n"; }

	/* Modify overlay color - ".gw-gopf-post-overlay-bg" */
	$css_prop = null;
	if ( isset( $portfolio['overlay-color'] ) && !empty( $portfolio['overlay-color'] ) ) {
		$portfolio['overlay-color'] = preg_match( '/#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $portfolio['overlay-color'] ) ? $portfolio['overlay-color'] : 'inherit';
		$css_prop = 'background-color:' . $portfolio['overlay-color'] . ';';
	}
	if ( $css_prop ) { echo sprintf( '%1$s .gw-gopf-post-overlay-bg { %2$s }', $id_prefix, $css_prop ) . "\n"; }	

	/* Modify overlay opacity - ".gw-gopf-post-overlay-bg" */
	$css_prop = null;
	if ( isset( $portfolio['overlay-opacity'] ) ) {
		$css_prop = 'filter:alpha(opacity=' . floatval( $portfolio['overlay-opacity'] ) . ');';
		$css_prop .= '-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=' . floatval( $portfolio['overlay-opacity'] ) . ')";';
		$css_prop .= '-khtml-opacity:' . floatval( $portfolio['overlay-opacity'] )/100 . ';';
		$css_prop .= '-moz-opacity:' . floatval( $portfolio['overlay-opacity'] )/100 . ';';
		$css_prop .= 'opacity:' . floatval( $portfolio['overlay-opacity'] )/100 . ';';			
	}
	if ( $css_prop ) { echo sprintf( '%1$s .gw-gopf-post-overlay-bg { %2$s }', $id_prefix, $css_prop ) . "\n"; }	

	/* Modify post content align - ".gw-gopf-post-content" */
	$css_prop = null;
	if ( isset( $portfolio['post-align'] ) && !empty( $portfolio['post-align'] ) ) {
		$css_prop = 'text-align:' . $portfolio['post-align'] . ';';
	}
	if ( $css_prop ) { echo sprintf( '%1$s .gw-gopf-post-content { %2$s }', $id_prefix, $css_prop ) . "\n"; }

	/* Modify button align - ".gw-gopf-post-more" */
	$css_prop = null;
	if ( isset( $portfolio['post-button-align'] ) && !empty( $portfolio['post-button-align'] ) ) {
		$css_prop = 'text-align:' . $portfolio['post-button-align'] . ';';
	}
	if ( $css_prop ) { echo sprintf( '%1$s .gw-gopf-post-more { %2$s }', $id_prefix, $css_prop ) . "\n"; }	
	
	/* Modify space between portfolio pagination and portfolio items - ".gw-gopf-pagination-wrapper" */
	$css_prop = null;
	if ( isset( $portfolio['pagination-v-space'] ) && !empty( $portfolio['pagination-v-space'] ) ) {
		$css_prop = 'padding-top:' . floatval( $portfolio['pagination-v-space'] ) . 'px;';
	}
	if ( $css_prop ) { echo sprintf( '%1$s .gw-gopf-pagination-wrapper { %2$s }', $id_prefix, $css_prop ) . "\n"; }		

	/* Modify column and row space - ".gw-gopf-post-media-wrap" */
	$css_prop = null;
	if ( isset( $portfolio['thumb-bg-pos-x'] ) && isset( $portfolio['thumb-bg-pos-y'] ) ) {
		$css_prop = 'background-position:' . floatval( $portfolio['thumb-bg-pos-x'] ) . '% ' . floatval( $portfolio['thumb-bg-pos-y'] ) . '%;';
	}
	if ( $css_prop ) { echo sprintf( '%1$s .gw-gopf-post-media-wrap { %2$s }', $id_prefix, $css_prop ) . "\n"; }

	/* Add ID prefix to css selectors */
	$style_data = preg_replace( '/(\/\*[\s\S]*?\*\/|[\t]|[\r]|[\n]|[\r\n])/', 
				'', 
				$style_data );
				
	/* Remove comments & minify */					
	$style_data = preg_replace( '/([^\r\n,{}]+)(,(?=[^}]*{)|\s*{)/', 
				$id_prefix . ' $0', 
				$style_data );
	
	echo $style_data . "\n";
	
}
		
