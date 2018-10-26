<?php

if ( ! function_exists( 'electro_brands_carousel_element' ) ) :

	function electro_brands_carousel_element( $atts, $content = null ){

		extract(shortcode_atts(array(
			'title'				=> '',
			'limit'				=> '',
			'has_no_products'	=> false,
			'orderby' 			=> 'date',
			'order' 			=> 'desc',
			'include'			=> '',
			'is_touchdrag'		=> false
		), $atts));

		$section_args = array(
			'section_title'		=> $title
		);

		$taxonomy_args = array(
			'orderby'		=> $orderby,
			'order'			=> $order,
			'number'		=> $limit,
			'hide_empty'	=> $has_no_products
		);

		if( ! empty( $include ) ) {
			$include = explode( ",", $include );
			$taxonomy_args['include'] = $include;
		}

		$carousel_args 	= array(
			'touchDrag'			=> $is_touchdrag,
		);

		$html = '';
		if( function_exists( 'electro_brands_carousel' ) ) {
			ob_start();
			electro_brands_carousel( $section_args, $taxonomy_args, $carousel_args );
			$html = ob_get_clean();
		}

	    return $html;
	}

	add_shortcode( 'electro_brands_carousel' , 'electro_brands_carousel_element' );

endif;