<?php
if ( ! function_exists( 'electro_vc_products_cards_carousel_block' ) ) :

function electro_vc_products_cards_carousel_block( $atts, $content = null ) {

	extract( shortcode_atts( array(
		'title'					=> '',
		'rows'					=> 2,
		'columns'				=> 2,
		'show_nav'				=> false,
		'show_carousel_nav'		=> false,
		'show_top_text'			=> false,
		'show_categories'		=> false,
		'limit'					=> 16,
		'shortcode_tag'			=> 'recent_products',
		'orderby' 				=> '',
		'order' 				=> '',
		'category'				=> '',
		'product_id'			=> '',
		'cat_limit' 			=> '',
		'cat_has_no_products' 	=> false,
		'cat_orderby' 			=> '',
		'cat_order' 			=> '',
		'cat_include'			=> '',
		'cat_slugs'				=> '',
	), $atts ) );

	$product_query_args = array(
		'per_page'		=> $limit,
		'orderby'		=> $orderby,
		'order'			=> $order,
	);

	if( 'products' == $shortcode_tag && ! empty( $product_id ) ) {
		$product_query_args['ids'] = $product_id;
	} elseif( 'product_category' == $shortcode_tag && ! empty( $category ) ) {
		$product_query_args['category'] = $category;
	}

	$categories_args = array(
		'number'		=> $cat_limit,
		'hide_empty'	=> $cat_has_no_products,
		'orderby' 		=> $cat_orderby,
		'order' 		=> $cat_order,
	);

	if( ! empty( $cat_include ) ) {
		$cat_include = explode( ",", $cat_include );
		$categories_args['include'] = $cat_include;
	}

	if( ! empty( $cat_slugs ) ) {
		$cat_slugs = explode( ",", $cat_slugs );
		$categories_args['slug'] = $cat_slugs;
	}

	$args = array(
		'section_args' 	=> array(
			'section_title'		=> $title,
			'show_nav'			=> false,
			'show_carousel_nav'	=> $show_carousel_nav,
			'show_top_text'		=> $show_top_text,
			'show_categories'	=> $show_categories,
			'categories_args'	=> $categories_args,
			'columns'			=> $columns,
			'rows'				=> $rows,
			'total'				=> $limit,
		),
		'carousel_args'	=> array()
	);

	if( class_exists( 'Electro_Products' ) ) {
		$args['section_args']['products'] = Electro_Products::$shortcode_tag( $product_query_args );
	}

	if( $show_top_text || $show_categories ) {
		$args['section_args']['show_nav'] = true;
	}

	$html = '';
	if( function_exists( 'electro_product_cards_carousel' ) ) {
		ob_start();
		electro_product_cards_carousel( $args['section_args'], $args['carousel_args'] );
		$html = ob_get_clean();
	}

	return $html;
}

add_shortcode( 'electro_vc_products_cards_carousel' , 'electro_vc_products_cards_carousel_block' );

endif;