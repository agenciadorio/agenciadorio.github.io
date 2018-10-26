<?php
if ( ! function_exists( 'electro_vc_products_2_1_2_block' ) ) :

function electro_vc_products_2_1_2_block( $atts, $content = null ) {

	extract( shortcode_atts( array(
		'title'					=> '',
		'shortcode_tag'			=> 'recent_products',
		'orderby' 				=> '',
		'order' 				=> '',
		'category'				=> '',
		'product_id'			=> '',
		'cat_limit' 			=> '',
		'cat_has_no_products' 	=> '',
		'cat_orderby' 			=> '',
		'cat_order' 			=> '',
		'cat_include'			=> '',
		'cat_slugs'				=> '',
	), $atts ) );

	$args = array(
		'section_title' 	=> $title,
		'category_args'		=> array(
			'number'		=> $cat_limit,
			'hide_empty'	=> $cat_has_no_products,
			'orderby' 		=> $cat_orderby,
			'order' 		=> $cat_order,
		)
	);

	if( ! empty( $cat_include ) ) {
		$cat_include = explode( ",", $cat_include );
		$args['category_args']['include'] = $cat_include;
	}

	if( ! empty( $cat_slugs ) ) {
		$cat_slugs = explode( ",", $cat_slugs );
		$args['category_args']['slug'] = $cat_slugs;
	}

	$product_query_args = array(
		'per_page'		=> 5,
		'orderby'		=> $orderby,
		'order'			=> $order,
	);

	if( 'products' == $shortcode_tag && ! empty( $product_id ) ) {
		$product_query_args['ids'] = $product_id;
	} elseif( 'product_category' == $shortcode_tag && ! empty( $category ) ) {
		$product_query_args['category'] = $category;
	}

	if( class_exists( 'Electro_Products' ) ) {
		$args['products'] = Electro_Products::$shortcode_tag( $product_query_args );
	}

	$html = '';
	if( function_exists( 'electro_products_2_1_2_block' ) ) {
		ob_start();
		electro_products_2_1_2_block( $args );
		$html = ob_get_clean();
	}

	return $html;
}

add_shortcode( 'electro_products_2_1_2' , 'electro_vc_products_2_1_2_block' );

endif;