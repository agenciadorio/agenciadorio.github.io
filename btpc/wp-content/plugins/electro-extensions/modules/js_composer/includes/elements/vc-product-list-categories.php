<?php

if ( ! function_exists( 'electro_product_list_categories_element' ) ) :

	function electro_product_list_categories_element( $atts, $content = null ){

		extract(shortcode_atts(array(
			'title'				=> '',
			'limit'				=> '',
			'has_no_products'	=> false,
			'orderby' 			=> 'name',
			'order' 			=> 'ASC',
			'include'			=> '',
		), $atts));

		$cat_args = array(
			'number'			=> $limit,
			'hide_empty'		=> $has_no_products,
			'orderby' 			=> $orderby,
			'order' 			=> $order,
		);

		if( ! empty( $include ) ) {
			$include = explode( ",", $include );
			$cat_args['include'] = $include;
		}

		$args = array(
			'section_title'			=> $title,
			'category_args'			=> $cat_args,
		);

		$html = '';
		if( function_exists( 'electro_home_list_categories' ) ) {
			ob_start();
			electro_home_list_categories( $args );
			$html = ob_get_clean();
		}

	    return $html;
	}

	add_shortcode( 'electro_product_list_categories' , 'electro_product_list_categories_element' );

endif;